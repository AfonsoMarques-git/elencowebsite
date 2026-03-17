<?php
/**
 * Download missing product images from OC site and attach to WC products.
 * 
 * 1. Parse OC SQL backup to get SKU -> image path mapping
 * 2. Find WC products without thumbnails
 * 3. Download missing images from elencocompleto.pt to product_images/
 * 4. Attach images to WC products
 */
set_time_limit(0);
ini_set('memory_limit', '1G');

require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

define('SQL_FILE',     'c:/Users/afons/Downloads/elenco_webstore_2026-03-11_15-37-00_backup.sql');
define('OC_IMAGE_BASE','https://www.elencocompleto.pt/image/');
define('LOCAL_IMAGES', __DIR__ . '/product_images/');
define('LOG_FILE',     __DIR__ . '/fix_images_log.txt');
define('PROGRESS_FILE',__DIR__ . '/fix_images_progress.json');
define('BATCH_SIZE',   50);

function ilog($msg) {
    $line = '[' . date('H:i:s') . '] ' . $msg . "\n";
    echo $line;
    file_put_contents(LOG_FILE, $line, FILE_APPEND);
}

function load_progress() {
    if (file_exists(PROGRESS_FILE)) {
        return json_decode(file_get_contents(PROGRESS_FILE), true) ?: ['done' => [], 'downloaded' => 0, 'attached' => 0, 'failed' => 0, 'skipped' => 0];
    }
    return ['done' => [], 'downloaded' => 0, 'attached' => 0, 'failed' => 0, 'skipped' => 0];
}

function save_progress($p) {
    file_put_contents(PROGRESS_FILE, json_encode($p));
}

// ── SQL Parser ─────────────────────────────────────────────────
function parse_values($values_str) {
    $rows = [];
    $len = strlen($values_str);
    $i = 0;
    while ($i < $len) {
        if ($values_str[$i] === '(') {
            $i++;
            $fields = [];
            $field = '';
            $in_quote = false;
            while ($i < $len) {
                $c = $values_str[$i];
                if ($in_quote) {
                    if ($c === "'" && ($i + 1 < $len) && $values_str[$i + 1] === "'") {
                        $field .= "'";
                        $i += 2; continue;
                    } elseif ($c === "\\") {
                        $field .= $values_str[$i + 1] ?? '';
                        $i += 2; continue;
                    } elseif ($c === "'") {
                        $in_quote = false;
                        $i++; continue;
                    } else {
                        $field .= $c;
                        $i++; continue;
                    }
                }
                if ($c === "'") { $in_quote = true; $i++; continue; }
                if ($c === ',') { $fields[] = trim($field); $field = ''; $i++; continue; }
                if ($c === ')') { $fields[] = trim($field); $rows[] = $fields; $i++; break; }
                $field .= $c;
                $i++;
            }
        } else {
            $i++;
        }
    }
    return $rows;
}

function parse_sql_inserts($file, $table_name) {
    $rows = [];
    $handle = fopen($file, 'r');
    if (!$handle) return $rows;
    $buffer = '';
    $collecting = false;
    while (($line = fgets($handle)) !== false) {
        if (stripos($line, "INSERT INTO `$table_name`") !== false) {
            $collecting = true;
            $buffer = $line;
        } elseif ($collecting) {
            $buffer .= $line;
        }
        if ($collecting && substr(trim($line), -1) === ';') {
            if (preg_match('/VALUES\s*(.*);$/si', $buffer, $m)) {
                $parsed = parse_values($m[1]);
                $rows = array_merge($rows, $parsed);
            }
            $buffer = '';
            $collecting = false;
        }
    }
    fclose($handle);
    return $rows;
}

// ── Download image from OC ─────────────────────────────────────
function download_oc_image($oc_path) {
    // $oc_path is like "catalog/filename.jpg"
    $filename = basename($oc_path);
    $local_path = LOCAL_IMAGES . $filename;
    
    if (file_exists($local_path)) return $local_path;
    
    // URL-encode just the filename (keep path structure)
    $dir = dirname($oc_path);
    $encoded_url = OC_IMAGE_BASE . $dir . '/' . rawurlencode($filename);
    
    $ch = curl_init($encoded_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    
    if ($code !== 200 || empty($data) || strlen($data) < 100) {
        return false;
    }
    
    // Verify it's an image
    if ($content_type && strpos($content_type, 'image') === false && strpos($content_type, 'octet') === false) {
        return false;
    }
    
    file_put_contents($local_path, $data);
    return $local_path;
}

// ── Attach image to WC product ─────────────────────────────────
function attach_image_to_product($local_path, $product_id) {
    if (!file_exists($local_path)) return false;
    
    $filename = basename($local_path);
    $upload_dir = wp_upload_dir();
    $dest = $upload_dir['path'] . '/' . wp_unique_filename($upload_dir['path'], $filename);
    
    if (!copy($local_path, $dest)) return false;
    
    $filetype = wp_check_filetype(basename($dest), null);
    if (empty($filetype['type'])) return false;
    
    $attachment = [
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];
    
    $attach_id = wp_insert_attachment($attachment, $dest, $product_id);
    if (is_wp_error($attach_id)) return false;
    
    $metadata = wp_generate_attachment_metadata($attach_id, $dest);
    wp_update_attachment_metadata($attach_id, $metadata);
    set_post_thumbnail($product_id, $attach_id);
    
    return true;
}

// ── MAIN ───────────────────────────────────────────────────────
ilog('=== FIX PRODUCT IMAGES START ===');

// Step 1: Parse OC products for SKU -> image mapping
ilog('Parsing OC SQL backup...');
$oc_products = parse_sql_inserts(SQL_FILE, 'elag_product');
ilog('Found ' . count($oc_products) . ' OC products');

// Build SKU (model) -> image map
$oc_images = [];
foreach ($oc_products as $row) {
    $model = trim($row[1] ?? '');
    $image = trim($row[11] ?? '');
    if (!empty($model) && !empty($image) && $image !== 'no_image.png' && $image !== 'no_image.jpg') {
        $oc_images[$model] = $image;
    }
}
ilog('OC products with images: ' . count($oc_images));
unset($oc_products);

// Step 2: Build local images index (case-insensitive)
ilog('Indexing local product_images/...');
$local_index = [];
foreach (scandir(LOCAL_IMAGES) as $f) {
    if ($f === '.' || $f === '..') continue;
    $local_index[strtolower($f)] = $f;
}
ilog('Local images: ' . count($local_index));

// Step 3: Find WC products without thumbnails
global $wpdb;
$no_img_products = $wpdb->get_results("
    SELECT p.ID, pm.meta_value as sku FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_sku'
    WHERE p.post_type='product' AND p.post_status='publish'
    AND p.ID NOT IN (
        SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_thumbnail_id' AND meta_value > 0
    )
    ORDER BY p.ID
");
ilog('WC products without image: ' . count($no_img_products));

$progress = load_progress();
$done_set = array_flip($progress['done']);

$batch = 0;
$total = count($no_img_products);

foreach ($no_img_products as $idx => $p) {
    $pid = $p->ID;
    $sku = trim($p->sku);
    
    if (isset($done_set[$pid])) continue;
    
    $batch++;
    
    if (empty($sku) || !isset($oc_images[$sku])) {
        // No OC image - try to find by SKU filename in local images
        if (!empty($sku)) {
            $found = false;
            foreach (['jpg','jpeg','png','JPG','JPEG','PNG','gif'] as $ext) {
                $try = strtolower($sku . '.' . $ext);
                if (isset($local_index[$try])) {
                    $local_path = LOCAL_IMAGES . $local_index[$try];
                    if (attach_image_to_product($local_path, $pid)) {
                        $progress['attached']++;
                        ilog("  [$pid] SKU=$sku ATTACHED from local (by SKU name)");
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                $progress['skipped']++;
            }
        } else {
            $progress['skipped']++;
        }
        
        $progress['done'][] = $pid;
        $done_set[$pid] = true;
        
        if ($batch % BATCH_SIZE === 0) {
            save_progress($progress);
            wp_cache_flush();
            ilog("--- Progress: $batch/$total | Downloaded: {$progress['downloaded']} | Attached: {$progress['attached']} | Failed: {$progress['failed']} | Skipped: {$progress['skipped']} ---");
        }
        continue;
    }
    
    $oc_path = $oc_images[$sku];
    $filename = basename($oc_path);
    $filename_lower = strtolower($filename);
    
    // Check if already locally available
    $local_path = null;
    if (isset($local_index[$filename_lower])) {
        $local_path = LOCAL_IMAGES . $local_index[$filename_lower];
    } else {
        // Try to download from OC
        $local_path = download_oc_image($oc_path);
        if ($local_path) {
            $progress['downloaded']++;
            // Update local index
            $local_index[strtolower(basename($local_path))] = basename($local_path);
            ilog("  [$pid] SKU=$sku DOWNLOADED: $filename");
        } else {
            // Try alternate: SKU-based filename
            foreach (['jpg','jpeg','png','JPG','JPEG','PNG'] as $ext) {
                $try = strtolower($sku . '.' . $ext);
                if (isset($local_index[$try])) {
                    $local_path = LOCAL_IMAGES . $local_index[$try];
                    break;
                }
            }
            if (!$local_path) {
                $progress['failed']++;
                ilog("  [$pid] SKU=$sku FAILED download: $oc_path");
                $progress['done'][] = $pid;
                $done_set[$pid] = true;
                
                if ($batch % BATCH_SIZE === 0) {
                    save_progress($progress);
                    wp_cache_flush();
                    ilog("--- Progress: $batch/$total | Downloaded: {$progress['downloaded']} | Attached: {$progress['attached']} | Failed: {$progress['failed']} | Skipped: {$progress['skipped']} ---");
                }
                continue;
            }
        }
    }
    
    // Attach image
    if ($local_path && file_exists($local_path)) {
        if (attach_image_to_product($local_path, $pid)) {
            $progress['attached']++;
            ilog("  [$pid] SKU=$sku ATTACHED: " . basename($local_path));
        } else {
            $progress['failed']++;
            ilog("  [$pid] SKU=$sku ATTACH FAILED: " . basename($local_path));
        }
    }
    
    $progress['done'][] = $pid;
    $done_set[$pid] = true;
    
    if ($batch % BATCH_SIZE === 0) {
        save_progress($progress);
        wp_cache_flush();
        ilog("--- Progress: $batch/$total | Downloaded: {$progress['downloaded']} | Attached: {$progress['attached']} | Failed: {$progress['failed']} | Skipped: {$progress['skipped']} ---");
    }
}

save_progress($progress);

ilog('=== FIX PRODUCT IMAGES COMPLETE ===');
ilog("Downloaded: {$progress['downloaded']} | Attached: {$progress['attached']} | Failed: {$progress['failed']} | Skipped (no OC image): {$progress['skipped']}");
