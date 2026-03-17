<?php

/**
 * Spexo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Spexo
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$tmpcoder_theme = (is_object(wp_get_theme()->parent())) ? wp_get_theme()->parent() : wp_get_theme();

define('TMPCODER_THEME_NAME', $tmpcoder_theme->get('Name'));

if (!defined('TMPCODER_THEME_SLUG')) {
    define('TMPCODER_THEME_SLUG', $tmpcoder_theme->get('TextDomain'));
}

if (!defined('TMPCODER_THEME_CORE_VERSION')) {
    define('TMPCODER_THEME_CORE_VERSION', trim($tmpcoder_theme->get('Version')));
}

if (!defined('TMPCODER_THEME_OPTION_NAME')) {
    define('TMPCODER_THEME_OPTION_NAME', 'tmpcoder_global_theme_options_' . TMPCODER_THEME_SLUG);
}

if (!defined('TMPCODER_SPEXO_ADDONS_WIDGETS_URL')) {
    define('TMPCODER_SPEXO_ADDONS_WIDGETS_URL', 'https://spexoaddons.com/widgets');
}

if (!defined('TMPCODER_PURCHASE_PRO_URL')) {
    define('TMPCODER_PURCHASE_PRO_URL', esc_url('https://spexoaddons.com/spexo-addons-pro/'));
}

if (!defined('TMPCODER_CUSTOMIZER_ASSETS')) {
    define('TMPCODER_CUSTOMIZER_ASSETS', trailingslashit(get_template_directory_uri()) . 'inc/admin/customizer/assets/');
}

if (!function_exists('tmpcoder_display_php_version_notices')) {
    add_action('admin_notices', 'tmpcoder_display_php_version_notices');
    function tmpcoder_display_php_version_notices()
    {

        $php_version = null;
        if (defined('PHP_VERSION')) {
            $php_version = PHP_VERSION;
        } elseif (function_exists('phpversion')) {
            $php_version = phpversion();
        }
        if (null === $php_version) {
            echo wp_kses('<div class="notice notice-error">
                        <p>PHP Version could not be detected.</p>
                    </div>', array('div' => 'class', 'p' => array()));
        } else {
            if (version_compare($php_version, '7.4') >= 0) {
                $message = '';
            } else {
                echo '<div class="notice notice-error"><p>';
                printf(
                    /* translators: %s is the PHP version */
                    esc_html__('Your site is running on an outdated version of PHP %s. The minimum recommended version of PHP is 7.4.', 'spexo'),
                    esc_html($php_version)
                );
                echo '<a href="' . esc_url(admin_url() . '?page=spexo-welcome&tab=system-info') . '">' . esc_html('See more details') . '</a>';
                echo '</p></div>';
            }
        }

        // Get the memory from PHP's configuration.
        $memory = ini_get('memory_limit');
        // If we can't get it, fallback to WP_MEMORY_LIMIT.
        if (!$memory || -1 === $memory) {
            $memory = wp_convert_hr_to_bytes(WP_MEMORY_LIMIT);
        }
        // Make sure the value is properly formatted in bytes.
        if (!is_numeric($memory)) {
            $memory = wp_convert_hr_to_bytes($memory);
        }
        if ($memory < 128000000):
            echo '<div class="notice notice-error"><p>';
            printf(
                /* translators: 1$s is the memory size, 2$s is the URL to the documentation */
                esc_html__('%1$s - We recommend setting memory to at least <strong>128MB</strong>. Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%2$s" target="_blank" rel="noopener noreferrer">Increasing memory allocated to PHP.</a>', 'spexo'),
                esc_html(size_format($memory)),
                esc_url('http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP')
            );
            echo '</p></div>';
        endif;
    }
}

if (!function_exists('tmpcoder_min_suffix')) {

    function tmpcoder_min_suffix()
    {

        return defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    }
}

add_action('after_switch_theme', 'tmpcoder_theme_upgrade_action');
function tmpcoder_theme_upgrade_action()
{
    $tmpcoder_theme_setting = get_option(TMPCODER_THEME_OPTION_NAME);
    if (empty($tmpcoder_theme_setting)) {
        $tmpcoder_theme_setting = get_option('tmpcoder_global_theme_options_sastrawp');
        if (!empty($tmpcoder_theme_setting)) {
            update_option(TMPCODER_THEME_OPTION_NAME, $tmpcoder_theme_setting);
        }
    }
}

add_filter('gettext', function ($translated_text, $text, $domain) {
    if ($text === 'Search Results for: %s') {
        return 'Resultados da pesquisa para: %s';
    }
    return $translated_text;
}, 20, 3);

/**
 * Support searching WooCommerce products by SKU on the regular search page.
 */
function tmpcoder_collect_sku_match_product_ids($query)
{
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    if (!post_type_exists('product')) {
        return;
    }

    $raw_search = (string) $query->get('s');
    $search_term = trim(wp_unslash($raw_search));
    if ($search_term === '') {
        return;
    }

    global $wpdb;

    $like = '%' . $wpdb->esc_like($search_term) . '%';
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT p.ID, p.post_type, p.post_parent
             FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
             WHERE pm.meta_key = '_sku'
               AND pm.meta_value LIKE %s
               AND p.post_type IN ('product', 'product_variation')
               AND p.post_status NOT IN ('trash', 'auto-draft')",
            $like
        )
    );

    if (empty($rows)) {
        return;
    }

    $product_ids = array();
    foreach ($rows as $row) {
        $id = ($row->post_type === 'product_variation') ? (int) $row->post_parent : (int) $row->ID;
        if ($id > 0) {
            $product_ids[] = $id;
        }
    }

    $product_ids = array_values(array_unique($product_ids));
    if (!empty($product_ids)) {
        $query->set('tmpcoder_sku_product_ids', $product_ids);
    }
}
add_action('pre_get_posts', 'tmpcoder_collect_sku_match_product_ids');

/**
 * Extend search SQL to include products matched by SKU.
 */
function tmpcoder_extend_search_sql_with_sku_matches($search, $query)
{
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return $search;
    }

    $sku_product_ids = $query->get('tmpcoder_sku_product_ids');
    if (empty($sku_product_ids) || !is_array($sku_product_ids)) {
        return $search;
    }

    global $wpdb;
    $sku_product_ids = array_map('absint', $sku_product_ids);
    $sku_product_ids = array_filter($sku_product_ids);

    if (empty($sku_product_ids)) {
        return $search;
    }

    $ids_sql = implode(',', $sku_product_ids);

    if ($search === '') {
        return " AND ({$wpdb->posts}.ID IN ({$ids_sql})) ";
    }

    if (substr($search, -1) === ')') {
        return substr($search, 0, -1) . " OR {$wpdb->posts}.ID IN ({$ids_sql}))";
    }

    return $search . " OR {$wpdb->posts}.ID IN ({$ids_sql})";
}
add_filter('posts_search', 'tmpcoder_extend_search_sql_with_sku_matches', 20, 2);


add_filter('woocommerce_available_payment_gateways', 'minimo_transferencia_bancaria');

function minimo_transferencia_bancaria($gateways)
{

    if (is_admin())
        return $gateways;

    $minimo = 4.80; // valor mínimo para transferência

    if (WC()->cart->total < $minimo) {
        unset($gateways['bacs']);
    }

    return $gateways;
}

/*
 * Include Function file     */
require get_template_directory() . '/inc/theme-includes.php';

/**
 * Create an 'avaliacoes' post when the Elementor "Avaliação" form is submitted.
 *
 * Form fields: nome, email, avaliacao (1-5), comentario
 * ACF fields:  nome_do_cliente, email, estrelas, comentario
 *
 * Change post_status to 'publish' to skip moderation,
 * or keep 'pending' to review in WP Admin → Avaliações before they go live.
 */
add_action( 'elementor_pro/forms/new_record', function ( $record, $handler ) {
    $form_name = $record->get_form_settings( 'form_name' );

    if ( 'Avaliação' !== $form_name ) {
        return;
    }

    $fields     = $record->get( 'fields' );
    $nome       = isset( $fields['nome']['value'] )       ? sanitize_text_field( $fields['nome']['value'] )           : '';
    $email      = isset( $fields['email']['value'] )      ? sanitize_email( $fields['email']['value'] )                : '';
    $estrelas   = isset( $fields['avaliacao']['value'] )  ? absint( $fields['avaliacao']['value'] )                    : 0;
    $comentario = isset( $fields['comentario']['value'] ) ? sanitize_textarea_field( $fields['comentario']['value'] )  : '';

    if ( empty( $nome ) ) {
        return;
    }

    $post_id = wp_insert_post( array(
        'post_type'    => 'avaliacoes',
        'post_title'   => $nome,
        'post_content' => $comentario,
        'post_status'  => 'pending', // change to 'publish' to skip moderation
        'post_author'  => 1,
    ) );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        update_field( 'field_68c9343c0e690', $nome,       $post_id ); // nome_do_cliente
        update_field( 'field_68c935530e692', $email,      $post_id ); // email
        update_field( 'field_68c9359a0e693', $estrelas,   $post_id ); // estrelas
        update_field( 'field_68c936400e694', $comentario, $post_id ); // comentario
    }
}, 10, 2 );