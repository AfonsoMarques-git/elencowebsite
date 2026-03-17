<?php
/**
 * Spexo AI Alt Text Generator
 * 
 * Handles automatic alt text generation for images using AI
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

namespace Spexo_Addons\AI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Spexo_AI_Alt_Text_Generator
 */
class Spexo_AI_Alt_Text_Generator {

    // Constants for batch processing
    private const BATCH_OPTION_PENDING = 'spexo_ai_alt_text_pending_queue';
    private const BATCH_OPTION_PROCESSING = 'spexo_ai_alt_text_processing';
    private const CRON_HOOK = 'spexo_ai_process_alt_text_queue';
    private const BATCH_SIZE = 1; // Process 1 image at a time to respect rate limits
    private const RETRY_LIMIT = 3; // Maximum retry attempts
    private const RATE_LIMIT_DELAY = 5; // Delay between batches in seconds

    /**
     * Instance
     *
     * @var Spexo_AI_Alt_Text_Generator|null The single instance of the class.
     */
    private static $instance = null;

    /**
     * Get Instance
     *
     * @return Spexo_AI_Alt_Text_Generator
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        $options = get_option('spexo_ai_options', []);
        
        $button_enabled = isset($options['enable_ai_alt_text_button']) ? (bool) $options['enable_ai_alt_text_button'] : true;
        $auto_enabled = isset($options['enable_ai_alt_text_auto_generation']) ? (bool) $options['enable_ai_alt_text_auto_generation'] : false;
        
        if (!$button_enabled && !$auto_enabled) {
            return;
        }

        $has_api_key = !empty($options['openai_api_key']);

        // Hook for new attachments (only if auto generation is enabled and API key exists)
        if ($auto_enabled && $has_api_key) {
            add_action('add_attachment', [$this, 'add_to_processing_queue']);
            add_action(self::CRON_HOOK, [$this, 'process_alt_text_queue']);
            
            // Add custom cron interval
            add_filter('cron_schedules', [$this, 'add_custom_cron_intervals']);
            
            // Schedule recurring cron job if not already scheduled
            if (!wp_next_scheduled(self::CRON_HOOK)) {
                wp_schedule_event(time(), 'spexo_ai_alt_text_interval', self::CRON_HOOK);
            }
        }

        // Hooks for Media Library integration
        if ($button_enabled) {
            add_filter('manage_media_columns', [$this, 'add_alt_text_column']);
            add_action('manage_media_custom_column', [$this, 'display_alt_text_column'], 10, 2);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_media_scripts']);

            // AJAX handler for manual generation
            if ($has_api_key) {
                add_action('wp_ajax_spexo_ai_generate_single_alt', [$this, 'handle_ajax_generate_single_alt']);
            }
            
            // AJAX handler for queue status
            add_action('wp_ajax_spexo_ai_alt_text_queue_status', [$this, 'handle_ajax_queue_status']);
        }

        // Hook for cleanup on plugin deactivation
        register_deactivation_hook(TMPCODER_PLUGIN_FILE, [$this, 'cleanup_cron_jobs']);
        
        // Hook to update cron schedule when settings change
        add_action('update_option_spexo_ai_options', [$this, 'update_cron_schedule'], 10, 3);
    }

    /**
     * Enqueues scripts needed for the media library screen
     */
    public function enqueue_media_scripts($hook) {
        if (!in_array($hook, ['upload.php', 'post.php', 'post-new.php', 'page.php', 'page-new.php'])) {
            return;
        }

        // Determine file extensions based on SCRIPT_DEBUG
        $css_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.css' : '.min.css';
        $js_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.js' : '.min.js';

        wp_enqueue_style(
            'spexo-ai-media-alt-text-styles',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/css/ai-alt-text-media' . $css_ext,
            [],
            TMPCODER_PLUGIN_VER
        );

        wp_enqueue_script(
            'spexo-ai-media-alt-text',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/js/ai-alt-text-media' . $js_ext,
            ['jquery'],
            TMPCODER_PLUGIN_VER,
            true
        );

        $options = get_option('spexo_ai_options', []);
        $has_api_key = !empty($options['openai_api_key']);

        wp_localize_script('spexo-ai-media-alt-text', 'SpexoAiMediaAltText', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spexo_ai_generate_alt_nonce'),
            'generating_text' => esc_html__('Generate with Spexo AI', 'sastra-essential-addons-for-elementor'),
            'error_text' => esc_html__('Error', 'sastra-essential-addons-for-elementor'),
            'has_api_key' => $has_api_key,
            'settings_url' => admin_url('admin.php?page=spexo-ai-settings'),
        ]);
    }

    /**
     * Adds a custom column to the Media Library list view
     */
    public function add_alt_text_column($columns) {
        $new_columns = [];
        foreach ($columns as $key => $title) {
            if ('date' === $key) {
                $new_columns['spexo_ai_alt_text'] = esc_html__('AI Alt Text', 'sastra-essential-addons-for-elementor');
            }
            $new_columns[$key] = $title;
        }
        
        if (!isset($new_columns['spexo_ai_alt_text'])) {
            $new_columns['spexo_ai_alt_text'] = esc_html__('AI Alt Text', 'sastra-essential-addons-for-elementor');
        }
        
        return $new_columns;
    }

    /**
     * Displays content for the custom alt text column
     */
    public function display_alt_text_column($column_name, $attachment_id) {
        if ('spexo_ai_alt_text' !== $column_name) {
            return;
        }

        if (!wp_attachment_is_image($attachment_id)) {
            echo '—';
            return;
        }

        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        $options = get_option('spexo_ai_options', []);
        $has_api_key = !empty($options['openai_api_key']);

        echo '<div class="spexo-ai-alt-text-status" data-attachment-id="' . esc_attr($attachment_id) . '">';
        
        if (!empty($alt_text)) {
            echo '<span>' . esc_html($alt_text) . '</span>';
        } else {
            if ($has_api_key) {
                printf(
                    '<button type="button" class="button button-secondary button-small spexo-ai-generate-alt-button">%s</button>',
                    esc_html__('Generate with Spexo AI', 'sastra-essential-addons-for-elementor')
                );
                echo '<span class="spexo-ai-alt-text-result spexo-ai-status-inline"></span>';
                echo '<span class="spinner spexo-ai-spinner-inline"></span>';
            } else {
                printf(
                    '<a href="%s" class="button button-secondary button-small" target="_blank">%s</a>',
                    esc_url(admin_url('admin.php?page=spexo-ai-settings')),
                    esc_html__('Set API Key', 'sastra-essential-addons-for-elementor')
                );
                echo '<span class="spexo-ai-alt-text-result spexo-ai-status-inline"></span>';
            }
        }
        echo '</div>';
    }

    /**
     * Handles the AJAX request to generate alt text for a single image
     */
    public function handle_ajax_generate_single_alt() {
        check_ajax_referer('spexo_ai_generate_alt_nonce', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }

        $options = get_option('spexo_ai_options', []);
        if (empty($options['openai_api_key'])) {
            wp_send_json_error([
                'message' => esc_html__('OpenAI API key is not configured. Please set it in the AI settings.', 'sastra-essential-addons-for-elementor'),
                'needs_setup' => true
            ], 400);
        }

        $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;

        if (!$attachment_id || !wp_attachment_is_image($attachment_id)) {
            wp_send_json_error(['message' => esc_html__('Invalid attachment ID.', 'sastra-essential-addons-for-elementor')], 400);
        }

        $result = $this->generate_alt_text_for_image($attachment_id, true);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 500);
        } elseif ($result === false) {
            wp_send_json_error(['message' => esc_html__('Alt text generation failed. Check logs or API key.', 'sastra-essential-addons-for-elementor')], 500);
        } elseif (is_string($result)) {
            wp_send_json_success(['alt_text' => $result]);
        } else {
            wp_send_json_error(['message' => esc_html__('An unexpected error occurred.', 'sastra-essential-addons-for-elementor')], 500);
        }
    }

    /**
     * Handles the AJAX request to get queue status
     */
    public function handle_ajax_queue_status() {
        check_ajax_referer('spexo_ai_generate_alt_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }

        $status = $this->get_queue_status();
        wp_send_json_success($status);
    }

    /**
     * Adds custom cron intervals
     */
    public function add_custom_cron_intervals($schedules) {
        $options = get_option('spexo_ai_options', []);
        $interval = isset($options['ai_alt_text_generation_interval']) ? (int) $options['ai_alt_text_generation_interval'] : 60;
        $interval = max(10, min(3600, $interval));
        
        $schedules['spexo_ai_alt_text_interval'] = [
            'interval' => $interval,
            /* translators: %d: number of seconds */
            'display' => sprintf(esc_html__('Every %d Seconds (Spexo AI Alt Text)', 'sastra-essential-addons-for-elementor'), $interval)
        ];
        return $schedules;
    }

    /**
     * Adds a new attachment to the processing queue
     */
    public function add_to_processing_queue($attachment_id) {
        if (!wp_attachment_is_image($attachment_id)) {
            return;
        }

        $existing_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        if (!empty($existing_alt)) {
            return;
        }

        $queue = get_option(self::BATCH_OPTION_PENDING, []);
        
        if (!in_array($attachment_id, $queue)) {
            $queue[] = $attachment_id;
            update_option(self::BATCH_OPTION_PENDING, $queue, false);
        }
    }

    /**
     * Processes the alt text generation queue in batches
     */
    public function process_alt_text_queue() {
        $processing = get_option(self::BATCH_OPTION_PROCESSING, false);
        if ($processing && (time() - $processing) < 300) {
            return;
        }

        update_option(self::BATCH_OPTION_PROCESSING, time(), false);

        try {
            $queue = get_option(self::BATCH_OPTION_PENDING, []);
            
            if (empty($queue)) {
                delete_option(self::BATCH_OPTION_PROCESSING);
                return;
            }

            $batch = array_splice($queue, 0, self::BATCH_SIZE);
            
            foreach ($batch as $attachment_id) {
                $this->process_single_attachment_with_retry($attachment_id);
                
                if (count($batch) > 1) {
                    sleep(2);
                }
            }

            update_option(self::BATCH_OPTION_PENDING, $queue, false);

            if (!empty($queue)) {
                wp_schedule_single_event(time() + self::RATE_LIMIT_DELAY, self::CRON_HOOK);
            }

        } finally {
            delete_option(self::BATCH_OPTION_PROCESSING);
        }
    }

    /**
     * Processes a single attachment with retry logic
     */
    private function process_single_attachment_with_retry($attachment_id) {
        $retry_count = get_post_meta($attachment_id, '_spexo_ai_alt_retry_count', true);
        $retry_count = $retry_count ? (int) $retry_count : 0;

        if ($retry_count >= self::RETRY_LIMIT) {
            delete_post_meta($attachment_id, '_spexo_ai_alt_retry_count');
            return;
        }

        $result = $this->generate_alt_text_for_image($attachment_id, false);
        
        if ($result !== true) {
            update_post_meta($attachment_id, '_spexo_ai_alt_retry_count', $retry_count + 1);
            
            $queue = get_option(self::BATCH_OPTION_PENDING, []);
            if (!in_array($attachment_id, $queue)) {
                $queue[] = $attachment_id;
                update_option(self::BATCH_OPTION_PENDING, $queue, false);
            }
        } else {
            delete_post_meta($attachment_id, '_spexo_ai_alt_retry_count');
        }
    }

    /**
     * Generates alt text for a given image attachment using AI
     */
    public function generate_alt_text_for_image($attachment_id, $is_ajax = false) {
        if (!wp_attachment_is_image($attachment_id)) {
            $error_msg = esc_html__('Not an image.', 'sastra-essential-addons-for-elementor');
            return $is_ajax ? new \WP_Error('invalid_attachment', $error_msg) : $error_msg;
        }

        $existing_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        if (!empty($existing_alt)) {
            return $is_ajax ? $existing_alt : true;
        }

        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'] ?? '';
        $model = $options['openai_model'] ?? 'gpt-4o';
        $image_detail_level = $options['ai_alt_text_image_detail_level'] ?? 'low';

        if (empty($api_key)) {
            $error_msg = esc_html__('OpenAI API key is missing.', 'sastra-essential-addons-for-elementor');
            return $is_ajax ? new \WP_Error('missing_api_key', $error_msg) : $error_msg;
        }

        $image_path = get_attached_file($attachment_id);
        if (!$image_path || !file_exists($image_path)) {
            $error_msg = esc_html__('Could not retrieve image file path.', 'sastra-essential-addons-for-elementor');
            return $is_ajax ? new \WP_Error('no_image_path', $error_msg) : $error_msg;
        }

        $image_data = file_get_contents($image_path);
        if (false === $image_data) {
            $error_msg = esc_html__('Could not read image file.', 'sastra-essential-addons-for-elementor');
            return $is_ajax ? new \WP_Error('read_image_failed', $error_msg) : $error_msg;
        }

        $file_info = wp_check_filetype(basename($image_path));
        if (!$file_info || empty($file_info['type'])) {
            $error_msg = esc_html__('Could not determine image type.', 'sastra-essential-addons-for-elementor');
            return $is_ajax ? new \WP_Error('mime_type_failed', $error_msg) : $error_msg;
        }
        $mime_type = $file_info['type'];

        $base64_image = base64_encode($image_data);
        $image_data_uri = "data:{$mime_type};base64,{$base64_image}";

        // OpenAI API Call
        $api_endpoint = 'https://api.openai.com/v1/chat/completions';

        $prompt_text = 'Generate a concise, descriptive alt text for this image, suitable for SEO and accessibility. Focus on the main subject and action. Maximum 125 characters.';

        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt_text
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $image_data_uri,
                                'detail' => $image_detail_level
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 50
        ];

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($payload),
            'timeout' => 60,
            'method' => 'POST',
            'data_format' => 'body',
        ];

        $response = wp_remote_post($api_endpoint, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            /* translators: %s: error message */
            return $is_ajax ? $response : sprintf(esc_html__('Network error: %s', 'sastra-essential-addons-for-elementor'), $error_message);
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        if ($response_code !== 200 || !isset($decoded_body['choices'][0]['message']['content'])) {
            $api_error_message = isset($decoded_body['error']['message']) ? $decoded_body['error']['message'] : esc_html__('API request failed or returned unexpected data.', 'sastra-essential-addons-for-elementor');
            /* translators: 1: HTTP response code, 2: error message */
            return $is_ajax ? new \WP_Error('api_error', $api_error_message, ['status' => $response_code]) : sprintf(esc_html__('API error (%1$d): %2$s', 'sastra-essential-addons-for-elementor'), $response_code, $api_error_message);
        }

        $generated_alt_text = $decoded_body['choices'][0]['message']['content'];

        if (preg_match('/^\s*I\'m sorry/i', $generated_alt_text)) {
            $generated_alt_text = '';
        }

        $sanitized_alt_text = sanitize_text_field(trim($generated_alt_text));
        $sanitized_alt_text = trim($sanitized_alt_text, '"');
        $sanitized_alt_text = mb_substr($sanitized_alt_text, 0, 125);

        if (!empty($sanitized_alt_text)) {
            if (update_post_meta($attachment_id, '_wp_attachment_image_alt', $sanitized_alt_text)) {
                error_log("Spexo AI Alt Text: Successfully generated alt text for attachment {$attachment_id}: {$sanitized_alt_text}");
                return $is_ajax ? $sanitized_alt_text : true;
            } else {
                $error_msg = esc_html__('Failed to save the generated alt text.', 'sastra-essential-addons-for-elementor');
                error_log("Spexo AI Alt Text: Failed to save alt text for attachment {$attachment_id}");
                return $is_ajax ? new \WP_Error('update_failed', $error_msg) : $error_msg;
            }
        } else {
            $error_msg = esc_html__('AI returned empty or invalid text.', 'sastra-essential-addons-for-elementor');
            error_log("Spexo AI Alt Text: AI returned empty text for attachment {$attachment_id}");
            return $is_ajax ? new \WP_Error('empty_alt_text', $error_msg) : $error_msg;
        }

        $error_msg = esc_html__('An unknown error occurred during generation.', 'sastra-essential-addons-for-elementor');
        return $is_ajax ? new \WP_Error('unknown_error', $error_msg) : $error_msg;
    }

    /**
     * Cleans up cron jobs and options on plugin deactivation
     */
    public function cleanup_cron_jobs() {
        $timestamp = wp_next_scheduled(self::CRON_HOOK);
        if ($timestamp) {
            wp_unschedule_event($timestamp, self::CRON_HOOK);
        }

        delete_option(self::BATCH_OPTION_PENDING);
        delete_option(self::BATCH_OPTION_PROCESSING);

        global $wpdb;
        $wpdb->delete(
            $wpdb->postmeta,
            ['meta_key' => '_spexo_ai_alt_retry_count'],
            ['%s']
        );
    }

    /**
     * Updates the cron schedule if the relevant settings change
     */
    public function update_cron_schedule($old_value, $new_value, $option = 'spexo_ai_options') {
        $old_interval = isset($old_value['ai_alt_text_generation_interval']) ? (int) $old_value['ai_alt_text_generation_interval'] : 60;
        $new_interval = isset($new_value['ai_alt_text_generation_interval']) ? (int) $new_value['ai_alt_text_generation_interval'] : 60;
        
        $old_auto = isset($old_value['enable_ai_alt_text_auto_generation']) ? (bool) $old_value['enable_ai_alt_text_auto_generation'] : false;
        $new_auto = isset($new_value['enable_ai_alt_text_auto_generation']) ? (bool) $new_value['enable_ai_alt_text_auto_generation'] : false;
        
        if ($old_interval !== $new_interval || $old_auto !== $new_auto) {
            $timestamp = wp_next_scheduled(self::CRON_HOOK);
            if ($timestamp) {
                wp_unschedule_event($timestamp, self::CRON_HOOK);
            }
            
            if ($new_auto) {
                wp_schedule_event(time(), 'spexo_ai_alt_text_interval', self::CRON_HOOK);
            }
        }
    }

    /**
     * Gets queue status for debugging purposes
     */
    public function get_queue_status() {
        $queue = get_option(self::BATCH_OPTION_PENDING, []);
        $processing = get_option(self::BATCH_OPTION_PROCESSING, false);
        $next_scheduled = wp_next_scheduled(self::CRON_HOOK);

        return [
            'pending_count' => count($queue),
            'pending_ids' => $queue,
            'is_processing' => $processing ? true : false,
            'processing_since' => $processing ? gmdate('Y-m-d H:i:s', $processing) : null,
            'next_run' => $next_scheduled ? gmdate('Y-m-d H:i:s', $next_scheduled) : null,
        ];
    }
}
