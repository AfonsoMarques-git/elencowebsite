<?php
/**
 * Spexo AI Page Translator
 * 
 * Handles AI-powered page translation functionality
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

namespace Spexo_Addons\AI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Spexo_AI_Page_Translator
 */
class Spexo_AI_Page_Translator {

    /**
     * Instance
     *
     * @var Spexo_AI_Page_Translator|null The single instance of the class.
     */
    private static $instance = null;

    /**
     * Get Instance
     *
     * @return Spexo_AI_Page_Translator
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
        
        if (!isset($options['enable_ai_page_translator']) || $options['enable_ai_page_translator']) {
            // Enqueue Elementor editor scripts
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_elementor_scripts']);
            
            // AJAX handlers
            add_action('wp_ajax_spexo_ai_translate_text', [$this, 'handle_ai_translate_text']);
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
            'spexo-ai-page-translator',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/css/ai-page-translator' . $css_ext,
            [],
            TMPCODER_PLUGIN_VER
        );

        wp_enqueue_script(
            'spexo-ai-page-translator',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/js/ai-page-translator' . $js_ext,
            ['jquery', 'elementor-editor'],
            TMPCODER_PLUGIN_VER,
            true
        );

        wp_localize_script('spexo-ai-page-translator', 'SpexoAiPageTranslator', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'translate_action' => 'spexo_ai_translate_text',
            'translate_nonce' => wp_create_nonce('spexo_ai_translate_text_nonce'),
            'generate_nonce' => wp_create_nonce('spexo_ai_generate_text_nonce'),
            'is_pro' => tmpcoder_is_availble(),
            'settings_url' => admin_url('admin.php?page=spexo-ai-settings'),
            'icon_url' => TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/images/ai-translator.svg',
            'plugin_url' => TMPCODER_PLUGIN_URI,
            'languages' => $this->get_supported_languages(),
        ]);
    }

    /**
     * Handle AI translation AJAX request
     */
    public function handle_ai_translate_text() {
        check_ajax_referer('spexo_ai_translate_text_nonce', 'nonce');

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

        $text = isset($_POST['text']) ? wp_kses_post($_POST['text']) : '';
        $target_language = isset($_POST['target_language']) ? sanitize_text_field($_POST['target_language']) : '';
        $source_language = isset($_POST['source_language']) ? sanitize_text_field($_POST['source_language']) : 'auto';

        if (empty($text) || empty($target_language)) {
            wp_send_json_error(['message' => esc_html__('Text and target language are required.', 'sastra-essential-addons-for-elementor')], 400);
        }

        // Check token limits
        if (!$this->check_token_limits()) {
            wp_send_json_error(['message' => esc_html__('Daily token limit reached.', 'sastra-essential-addons-for-elementor')], 429);
        }

        $result = $this->translate_text_with_ai($text, $target_language, $source_language);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 500);
        }

        // Update token usage
        $this->update_token_usage($result['usage'] ?? []);

        wp_send_json_success([
            'translated_text' => $result['content'],
            'usage' => $result['usage'] ?? [],
            'statistics' => [
                'original_length' => strlen($text),
                'translated_length' => strlen($result['content']),
                'source_language' => $source_language,
                'target_language' => $target_language
            ]
        ]);
    }

    /**
     * Translate text using OpenAI API
     */
    private function translate_text_with_ai($text, $target_language, $source_language = 'auto') {
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'];
        $model = $options['openai_model'] ?? 'gpt-4o';

        // Build the translation prompt
        $system_prompt = "You are a professional translator. Translate the provided text accurately while preserving the original meaning, tone, and context. Maintain any HTML formatting if present.";
        
        $language_names = $this->get_supported_languages();
        $target_lang_name = $language_names[$target_language] ?? $target_language;
        
        if ($source_language === 'auto') {
            $user_prompt = "Translate the following text to {$target_lang_name}:\n\n{$text}";
        } else {
            $source_lang_name = $language_names[$source_language] ?? $source_language;
            $user_prompt = "Translate the following text from {$source_lang_name} to {$target_lang_name}:\n\n{$text}";
        }

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
            'max_tokens' => $this->calculate_max_tokens($text),
            'temperature' => 0.3 // Lower temperature for more consistent translations
        ];

        return $this->make_openai_request($payload);
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
                : esc_html__('Translation request failed.', 'sastra-essential-addons-for-elementor');
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
     * Calculate max tokens needed for translation
     */
    private function calculate_max_tokens($text) {
        // Rough estimation: 1 token ≈ 4 characters
        $estimated_tokens = ceil(strlen($text) / 4);
        
        // Add buffer for translation (usually longer than original)
        $max_tokens = $estimated_tokens * 2;
        
        // Set reasonable limits
        return min(max($max_tokens, 100), 4000);
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
     * Get supported languages for translation
     */
    private function get_supported_languages() {
        return [
            'auto' => esc_html__('Auto-detect', 'sastra-essential-addons-for-elementor'),
            'en' => esc_html__('English', 'sastra-essential-addons-for-elementor'),
            'es' => esc_html__('Spanish', 'sastra-essential-addons-for-elementor'),
            'fr' => esc_html__('French', 'sastra-essential-addons-for-elementor'),
            'de' => esc_html__('German', 'sastra-essential-addons-for-elementor'),
            'it' => esc_html__('Italian', 'sastra-essential-addons-for-elementor'),
            'pt' => esc_html__('Portuguese', 'sastra-essential-addons-for-elementor'),
            'ru' => esc_html__('Russian', 'sastra-essential-addons-for-elementor'),
            'ja' => esc_html__('Japanese', 'sastra-essential-addons-for-elementor'),
            'ko' => esc_html__('Korean', 'sastra-essential-addons-for-elementor'),
            'zh' => esc_html__('Chinese', 'sastra-essential-addons-for-elementor'),
            'ar' => esc_html__('Arabic', 'sastra-essential-addons-for-elementor'),
            'hi' => esc_html__('Hindi', 'sastra-essential-addons-for-elementor'),
            'th' => esc_html__('Thai', 'sastra-essential-addons-for-elementor'),
            'vi' => esc_html__('Vietnamese', 'sastra-essential-addons-for-elementor'),
            'tr' => esc_html__('Turkish', 'sastra-essential-addons-for-elementor'),
            'pl' => esc_html__('Polish', 'sastra-essential-addons-for-elementor'),
            'nl' => esc_html__('Dutch', 'sastra-essential-addons-for-elementor'),
            'sv' => esc_html__('Swedish', 'sastra-essential-addons-for-elementor'),
            'da' => esc_html__('Danish', 'sastra-essential-addons-for-elementor'),
            'no' => esc_html__('Norwegian', 'sastra-essential-addons-for-elementor'),
            'fi' => esc_html__('Finnish', 'sastra-essential-addons-for-elementor'),
            'cs' => esc_html__('Czech', 'sastra-essential-addons-for-elementor'),
            'hu' => esc_html__('Hungarian', 'sastra-essential-addons-for-elementor'),
            'ro' => esc_html__('Romanian', 'sastra-essential-addons-for-elementor'),
            'bg' => esc_html__('Bulgarian', 'sastra-essential-addons-for-elementor'),
            'hr' => esc_html__('Croatian', 'sastra-essential-addons-for-elementor'),
            'sk' => esc_html__('Slovak', 'sastra-essential-addons-for-elementor'),
            'sl' => esc_html__('Slovenian', 'sastra-essential-addons-for-elementor'),
            'et' => esc_html__('Estonian', 'sastra-essential-addons-for-elementor'),
            'lv' => esc_html__('Latvian', 'sastra-essential-addons-for-elementor'),
            'lt' => esc_html__('Lithuanian', 'sastra-essential-addons-for-elementor'),
            'el' => esc_html__('Greek', 'sastra-essential-addons-for-elementor'),
            'he' => esc_html__('Hebrew', 'sastra-essential-addons-for-elementor'),
            'fa' => esc_html__('Persian', 'sastra-essential-addons-for-elementor'),
            'ur' => esc_html__('Urdu', 'sastra-essential-addons-for-elementor'),
            'bn' => esc_html__('Bengali', 'sastra-essential-addons-for-elementor'),
            'ta' => esc_html__('Tamil', 'sastra-essential-addons-for-elementor'),
            'te' => esc_html__('Telugu', 'sastra-essential-addons-for-elementor'),
            'ml' => esc_html__('Malayalam', 'sastra-essential-addons-for-elementor'),
            'kn' => esc_html__('Kannada', 'sastra-essential-addons-for-elementor'),
            'gu' => esc_html__('Gujarati', 'sastra-essential-addons-for-elementor'),
            'pa' => esc_html__('Punjabi', 'sastra-essential-addons-for-elementor'),
            'mr' => esc_html__('Marathi', 'sastra-essential-addons-for-elementor'),
            'ne' => esc_html__('Nepali', 'sastra-essential-addons-for-elementor'),
            'si' => esc_html__('Sinhala', 'sastra-essential-addons-for-elementor'),
            'my' => esc_html__('Burmese', 'sastra-essential-addons-for-elementor'),
            'km' => esc_html__('Khmer', 'sastra-essential-addons-for-elementor'),
            'lo' => esc_html__('Lao', 'sastra-essential-addons-for-elementor'),
            'ka' => esc_html__('Georgian', 'sastra-essential-addons-for-elementor'),
            'am' => esc_html__('Amharic', 'sastra-essential-addons-for-elementor'),
            'sw' => esc_html__('Swahili', 'sastra-essential-addons-for-elementor'),
            'zu' => esc_html__('Zulu', 'sastra-essential-addons-for-elementor'),
            'af' => esc_html__('Afrikaans', 'sastra-essential-addons-for-elementor'),
            'sq' => esc_html__('Albanian', 'sastra-essential-addons-for-elementor'),
            'eu' => esc_html__('Basque', 'sastra-essential-addons-for-elementor'),
            'be' => esc_html__('Belarusian', 'sastra-essential-addons-for-elementor'),
            'bs' => esc_html__('Bosnian', 'sastra-essential-addons-for-elementor'),
            'ca' => esc_html__('Catalan', 'sastra-essential-addons-for-elementor'),
            'cy' => esc_html__('Welsh', 'sastra-essential-addons-for-elementor'),
            'is' => esc_html__('Icelandic', 'sastra-essential-addons-for-elementor'),
            'ga' => esc_html__('Irish', 'sastra-essential-addons-for-elementor'),
            'mk' => esc_html__('Macedonian', 'sastra-essential-addons-for-elementor'),
            'mt' => esc_html__('Maltese', 'sastra-essential-addons-for-elementor'),
            'sr' => esc_html__('Serbian', 'sastra-essential-addons-for-elementor'),
            'uk' => esc_html__('Ukrainian', 'sastra-essential-addons-for-elementor'),
        ];
    }
}
