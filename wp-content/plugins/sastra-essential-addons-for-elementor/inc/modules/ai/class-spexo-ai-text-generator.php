<?php
/**
 * Spexo AI Text Generator
 * 
 * Handles AI text generation and modification for Elementor fields
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

namespace Spexo_Addons\AI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Spexo_AI_Text_Generator
 */
class Spexo_AI_Text_Generator {

    /**
     * Instance
     *
     * @var Spexo_AI_Text_Generator|null The single instance of the class.
     */
    private static $instance = null;

    /**
     * Get Instance
     *
     * @return Spexo_AI_Text_Generator
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
        
        if (!isset($options['enable_ai_buttons']) || $options['enable_ai_buttons']) {
            // Enqueue Elementor editor scripts
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_elementor_scripts']);
            
            // AJAX handlers
            add_action('wp_ajax_spexo_ai_generate_text', [$this, 'handle_ai_generate_text']);
            add_action('wp_ajax_spexo_ai_change_text', [$this, 'handle_ai_change_text']);
            add_action('wp_ajax_spexo_ai_text_check_limits', [$this, 'handle_ai_check_tokens']);
        }
    }

    /**
     * Enqueue Elementor editor scripts
     */
    public function enqueue_elementor_scripts() {
        $options = get_option('spexo_ai_options', []);
        
        if (empty($options['openai_api_key'])) {
            return;
        }

        // Determine file extensions based on SCRIPT_DEBUG
        $css_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.css' : '.min.css';
        $js_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.js' : '.min.js';

        wp_enqueue_style(
            'spexo-ai-textfield',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/css/ai-textfield' . $css_ext,
            [],
            TMPCODER_PLUGIN_VER
        );

        wp_enqueue_script(
            'spexo-ai-textfield',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/js/ai-textfield' . $js_ext,
            ['jquery', 'elementor-editor'],
            TMPCODER_PLUGIN_VER,
            true
        );

        wp_localize_script('spexo-ai-textfield', 'SpexoAiTextField', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'generate_action' => 'spexo_ai_generate_text',
            'generate_nonce' => wp_create_nonce('spexo_ai_generate_text_nonce'),
            'change_action' => 'spexo_ai_change_text',
            'change_nonce' => wp_create_nonce('spexo_ai_change_text_nonce'),
            'settings_url' => admin_url('admin.php?page=spexo-ai-settings'),
            'icon_url' => TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/images/ai-translator.svg',
            'rewrite_icon_url' => TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/images/ai-rewrite.svg',
            'plugin_url' => TMPCODER_PLUGIN_URI,
        ]);
    }

    /**
     * Handle AI text generation AJAX request
     */
    public function handle_ai_generate_text() {
        check_ajax_referer('spexo_ai_generate_text_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }

        $options = get_option('spexo_ai_options', []);
        if (empty($options['openai_api_key'])) {
            wp_send_json_error([
                'message' => esc_html__('OpenAI API key is not configured.', 'sastra-essential-addons-for-elementor'),
                'needs_setup' => true
            ], 400);
        }

        $prompt = isset($_POST['prompt']) ? sanitize_textarea_field($_POST['prompt']) : '';
        $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';
        $editor_type = isset($_POST['editor_type']) ? sanitize_text_field($_POST['editor_type']) : 'text';

        if (empty($prompt)) {
            wp_send_json_error(['message' => esc_html__('Prompt is required.', 'sastra-essential-addons-for-elementor')], 400);
        }

        // Check token limits
        if (!$this->check_token_limits()) {
            wp_send_json_error(['message' => esc_html__('Daily token limit reached.', 'sastra-essential-addons-for-elementor')], 429);
        }

        $result = $this->generate_text_with_ai($prompt, $editor_type);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 500);
        }

        // Update token usage
        $this->update_token_usage($result['usage'] ?? []);

        wp_send_json_success([
            'text' => $result['content'],
            'usage' => $result['usage'] ?? []
        ]);
    }

    /**
     * Handle AI text change AJAX request
     */
    public function handle_ai_change_text() {
        check_ajax_referer('spexo_ai_change_text_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }

        $options = get_option('spexo_ai_options', []);
        if (empty($options['openai_api_key'])) {
            wp_send_json_error([
                'message' => esc_html__('OpenAI API key is not configured.', 'sastra-essential-addons-for-elementor'),
                'needs_setup' => true
            ], 400);
        }

        $prompt = isset($_POST['prompt']) ? sanitize_textarea_field($_POST['prompt']) : '';
        $original = isset($_POST['original']) ? sanitize_textarea_field($_POST['original']) : '';
        $field_name = isset($_POST['field_name']) ? sanitize_text_field($_POST['field_name']) : '';
        $editor_type = isset($_POST['editor_type']) ? sanitize_text_field($_POST['editor_type']) : 'text';

        if (empty($prompt) || empty($original)) {
            wp_send_json_error(['message' => esc_html__('Prompt and original text are required.', 'sastra-essential-addons-for-elementor')], 400);
        }

        // Check token limits
        if (!$this->check_token_limits()) {
            wp_send_json_error(['message' => esc_html__('Daily token limit reached.', 'sastra-essential-addons-for-elementor')], 429);
        }

        $result = $this->change_text_with_ai($original, $prompt, $editor_type);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 500);
        }

        // Update token usage
        $this->update_token_usage($result['usage'] ?? []);

        wp_send_json_success([
            'text' => $result['content'],
            'usage' => $result['usage'] ?? [],
            'append_mode' => $this->should_append_content($prompt)
        ]);
    }

    /**
     * Handle token check AJAX request
     */
    public function handle_ai_check_tokens() {
        check_ajax_referer('spexo_ai_generate_text_nonce', 'nonce');

        $options = get_option('spexo_ai_options', []);
        $daily_limit = $options['daily_token_limit'] ?? 1000000;
        $daily_used = get_option('spexo_ai_daily_tokens_used', 0);
        $has_api_key = !empty($options['openai_api_key']);

        wp_send_json_success([
            'daily_used' => $daily_used,
            'daily_limit' => $daily_limit,
            'limit_reached' => $daily_limit > 0 && $daily_used >= $daily_limit,
            'api_key_valid' => $has_api_key
        ]);
    }

    /**
     * Generate text using OpenAI API
     */
    private function generate_text_with_ai($prompt, $editor_type = 'text') {
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'];
        $model = $options['openai_model'] ?? 'gpt-4o';

        // Build the system prompt based on editor type
        $system_prompt = $this->get_system_prompt_for_editor_type($editor_type);
        
        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system_prompt
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => $this->get_max_tokens_for_editor_type($editor_type),
            'temperature' => 0.7
        ];

        return $this->make_openai_request($payload);
    }

    /**
     * Change text using OpenAI API
     */
    private function change_text_with_ai($original_text, $change_prompt, $editor_type = 'text') {
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'];
        $model = $options['openai_model'] ?? 'gpt-4o';

        $system_prompt = $this->get_system_prompt_for_editor_type($editor_type);
        
        $user_prompt = "Original text:\n{$original_text}\n\nInstructions: {$change_prompt}\n\nPlease modify the original text according to the instructions. Return the complete modified text.";

        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system_prompt
                ],
                [
                    'role' => 'user',
                    'content' => $user_prompt
                ]
            ],
            'max_tokens' => $this->get_max_tokens_for_editor_type($editor_type),
            'temperature' => 0.7
        ];

        return $this->make_openai_request($payload);
    }

    /**
     * Get system prompt based on editor type
     */
    private function get_system_prompt_for_editor_type($editor_type) {
        if ($editor_type === 'wysiwyg') {
            return "You are a professional content writer. Generate high-quality, engaging content that is well-structured and formatted. Use proper HTML formatting when appropriate. Focus on creating valuable, informative, and engaging content.";
        } else {
            return "You are a professional copywriter. Generate concise, compelling text that is clear and effective. Focus on creating impactful, engaging content that serves its purpose well.";
        }
    }

    /**
     * Get max tokens based on editor type
     */
    private function get_max_tokens_for_editor_type($editor_type) {
        if ($editor_type === 'wysiwyg') {
            return 2000; // Allow more tokens for rich content
        } else {
            return 500; // Shorter for simple text fields
        }
    }

    /**
     * Make request to OpenAI API
     */
    private function make_openai_request($payload) {
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'];

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($payload),
            'timeout' => 60,
            'method' => 'POST',
        ];

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', $args);

        if (is_wp_error($response)) {
            return new \WP_Error('api_error', $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        if ($response_code !== 200) {
            $error_message = isset($decoded_body['error']['message']) 
                ? $decoded_body['error']['message'] 
                : esc_html__('API request failed.', 'sastra-essential-addons-for-elementor');
            return new \WP_Error('api_error', $error_message, ['status' => $response_code]);
        }

        if (!isset($decoded_body['choices'][0]['message']['content'])) {
            return new \WP_Error('api_error', esc_html__('Unexpected API response format.', 'sastra-essential-addons-for-elementor'));
        }

        $content = $decoded_body['choices'][0]['message']['content'];
        $usage = $decoded_body['usage'] ?? [];

        return [
            'content' => $content,
            'usage' => $usage
        ];
    }

    /**
     * Check if token limits are reached
     */
    private function check_token_limits() {
        $options = get_option('spexo_ai_options', []);
        $daily_limit = $options['daily_token_limit'] ?? 1000000;
        
        if ($daily_limit <= 0) {
            return true; // No limit set
        }

        $daily_used = get_option('spexo_ai_daily_tokens_used', 0);
        return $daily_used < $daily_limit;
    }

    /**
     * Update token usage
     */
    private function update_token_usage($usage) {
        if (empty($usage['total_tokens'])) {
            return;
        }

        $today = current_time('Y-m-d');
        $last_reset = get_option('spexo_ai_last_token_reset', '');
        
        if ($last_reset !== $today) {
            // Reset daily counter for new day
            update_option('spexo_ai_daily_tokens_used', 0);
            update_option('spexo_ai_last_token_reset', $today);
        }

        $current_used = get_option('spexo_ai_daily_tokens_used', 0);
        update_option('spexo_ai_daily_tokens_used', $current_used + $usage['total_tokens']);
    }

    /**
     * Determine if content should be appended based on prompt
     */
    private function should_append_content($prompt) {
        $append_keywords = ['add', 'append', 'include', 'insert', 'extend', 'expand'];
        $prompt_lower = strtolower($prompt);
        
        foreach ($append_keywords as $keyword) {
            if (strpos($prompt_lower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
