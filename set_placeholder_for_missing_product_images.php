<?php
/**
 * Set a valid placeholder featured image for products with missing/invalid thumbnails.
 *
 * Usage:
 *   php set_placeholder_for_missing_product_images.php
 */

set_time_limit(0);
ini_set('memory_limit', '1G');

require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function log_line($msg)
{
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
}

/**
 * Resolve WooCommerce placeholder to a valid attachment ID.
 */
function resolve_placeholder_attachment_id()
{
    $raw = get_option('woocommerce_placeholder_image', '');

    // Newer Woo installs can store attachment ID directly.
    if (is_numeric($raw) && (int) $raw > 0) {
        $id = (int) $raw;
        if (get_post($id) && wp_attachment_is_image($id)) {
            return $id;
        }
    }

    // It can also store a URL.
    if (is_string($raw) && $raw !== '' && filter_var($raw, FILTER_VALIDATE_URL)) {
        $existing_id = (int) attachment_url_to_postid($raw);
        if ($existing_id > 0 && wp_attachment_is_image($existing_id)) {
            return $existing_id;
        }

        // Import URL into media library if not already attached.
        $tmp = download_url($raw);
        if (!is_wp_error($tmp)) {
            $name = wp_basename(parse_url($raw, PHP_URL_PATH) ?: 'wc-placeholder.png');
            $file = [
                'name'     => $name,
                'tmp_name' => $tmp,
            ];

            $attachment_id = media_handle_sideload($file, 0, 'WooCommerce placeholder image');
            if (!is_wp_error($attachment_id) && wp_attachment_is_image($attachment_id)) {
                return (int) $attachment_id;
            }
        }
    }

    // Fallback to WooCommerce computed placeholder URL.
    if (function_exists('wc_placeholder_img_src')) {
        $fallback_url = wc_placeholder_img_src('woocommerce_thumbnail');
        if (is_string($fallback_url) && $fallback_url !== '' && filter_var($fallback_url, FILTER_VALIDATE_URL)) {
            $existing_id = (int) attachment_url_to_postid($fallback_url);
            if ($existing_id > 0 && wp_attachment_is_image($existing_id)) {
                return $existing_id;
            }

            $tmp = download_url($fallback_url);
            if (!is_wp_error($tmp)) {
                $name = wp_basename(parse_url($fallback_url, PHP_URL_PATH) ?: 'wc-placeholder-fallback.png');
                $file = [
                    'name'     => $name,
                    'tmp_name' => $tmp,
                ];

                $attachment_id = media_handle_sideload($file, 0, 'WooCommerce fallback placeholder image');
                if (!is_wp_error($attachment_id) && wp_attachment_is_image($attachment_id)) {
                    return (int) $attachment_id;
                }
            }
        }
    }

    return 0;
}

$placeholder_id = resolve_placeholder_attachment_id();
if ($placeholder_id <= 0) {
    log_line('ERROR: Could not resolve a valid placeholder attachment image.');
    exit(1);
}

$placeholder_url = wp_get_attachment_url($placeholder_id);
log_line('Using placeholder attachment ID: ' . $placeholder_id);
log_line('Placeholder URL: ' . $placeholder_url);

global $wpdb;
$product_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='product' AND post_status='publish'");

$total = count($product_ids);
$updated = 0;
$already_valid = 0;
$invalid_fixed = 0;
$missing_fixed = 0;

foreach ($product_ids as $product_id) {
    $product_id = (int) $product_id;
    $thumb_id = (int) get_post_thumbnail_id($product_id);

    if ($thumb_id > 0 && get_post($thumb_id) && wp_attachment_is_image($thumb_id)) {
        $already_valid++;
        continue;
    }

    // Missing or invalid thumbnail -> set placeholder.
    set_post_thumbnail($product_id, $placeholder_id);
    $updated++;

    if ($thumb_id > 0) {
        $invalid_fixed++;
    } else {
        $missing_fixed++;
    }
}

log_line('Published products checked: ' . $total);
log_line('Already had valid image: ' . $already_valid);
log_line('Updated to placeholder: ' . $updated);
log_line('  - Missing thumbnail fixed: ' . $missing_fixed);
log_line('  - Invalid thumbnail fixed: ' . $invalid_fixed);
log_line('DONE');
