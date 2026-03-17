<?php
/**
 * Spexo AI Image Generator
 * 
 * Handles AI image generation for Elementor fields
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

namespace Spexo_Addons\AI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Spexo_AI_Image_Generator
 */
class Spexo_AI_Image_Generator {

    /**
     * Instance
     *
     * @var Spexo_AI_Image_Generator|null The single instance of the class.
     */
    private static $instance = null;

    /**
     * Get Instance
     *
     * @return Spexo_AI_Image_Generator
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
        
        if (!isset($options['enable_ai_image_generation_button']) || $options['enable_ai_image_generation_button']) {
            // Enqueue Elementor editor scripts
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_elementor_scripts']);
            
            // AJAX handlers
            add_action('wp_ajax_spexo_ai_generate_image', [$this, 'handle_ai_generate_image']);
            add_action('wp_ajax_spexo_ai_image_check_limits', [$this, 'handle_ai_image_check_limits']);
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
            'spexo-ai-imagefield',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/css/ai-imagefield' . $css_ext,
            [],
            TMPCODER_PLUGIN_VER
        );

        wp_enqueue_script(
            'spexo-ai-imagefield',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/js/ai-imagefield' . $js_ext,
            ['jquery', 'elementor-editor'],
            TMPCODER_PLUGIN_VER,
            true
        );

        // Check Pro license status
        $is_pro = function_exists('tmpcoder_is_availble') ? tmpcoder_is_availble() : false;
        
        wp_localize_script('spexo-ai-imagefield', 'SpexoAiImageField', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'generate_action' => 'spexo_ai_generate_image',
            'generate_nonce' => wp_create_nonce('spexo_ai_generate_image_nonce'),
            'settings_url' => admin_url('admin.php?page=spexo-ai-settings'),
            'icon_url' => TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/images/ai-translator.svg',
            'image_model' => $options['openai_image_model'] ?? 'dall-e-3',
            'is_pro' => $is_pro,
        ]);
    }

    /**
     * Handle AI image generation AJAX request
     */
    public function handle_ai_generate_image() {
        check_ajax_referer('spexo_ai_generate_image_nonce', 'nonce');

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
        $quality = isset($_POST['quality']) ? sanitize_text_field($_POST['quality']) : 'hd';
        $size = isset($_POST['size']) ? sanitize_text_field($_POST['size']) : '1024x1024';
        $model = isset($_POST['model']) ? sanitize_text_field($_POST['model']) : 'dall-e-3';
        $background = isset($_POST['background']) ? sanitize_text_field($_POST['background']) : '';

        if (empty($prompt)) {
            wp_send_json_error(['message' => esc_html__('Prompt is required.', 'sastra-essential-addons-for-elementor')], 400);
        }

        $result = $this->generate_image_with_ai($prompt, $quality, $size, $model, $background);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()], 500);
        }

        // Create WordPress attachment from generated image
        $attachment_id = $this->create_attachment_from_url($result['url'], $prompt);

        if (is_wp_error($attachment_id)) {
            wp_send_json_error(['message' => $attachment_id->get_error_message()], 500);
        }

        wp_send_json_success([
            'attachment_id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id),
            'usage' => $result['usage'] ?? []
        ]);
    }

    /**
     * Handle AI image check limits AJAX request
     */
    public function handle_ai_image_check_limits() {
        check_ajax_referer('spexo_ai_generate_image_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }

        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'] ?? '';
        $model   = $options['openai_model'] ?? '';
        $api_key_valid = !empty($api_key) && !empty($model);

        wp_send_json_success([
            'api_key_valid' => $api_key_valid
        ]);
    }

    /**
     * Generate image using OpenAI API
     */
    private function generate_image_with_ai($prompt, $quality = 'hd', $size = '1024x1024', $model = 'dall-e-3', $background = '') {
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'];

        // Validate parameters based on model
        $validated_params = $this->validate_image_params($model, $quality, $size, $background);

        $payload = [
            'model' => $model,
            'prompt' => $prompt,
            'n' => 1,
            'size' => $validated_params['size'],
        ];

        // Add quality parameter for DALL-E 3
        if ($model === 'dall-e-3') {
            $payload['quality'] = $validated_params['quality'];
        }

        // Add style parameter for DALL-E 3
        if ($model === 'dall-e-3') {
            $payload['style'] = 'natural';
        }

        // Add background parameter for GPT Image 1
        if ($model === 'gpt-image-1' && !empty($background)) {
            $payload['background'] = $background;
        }

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($payload),
            'timeout' => 120, // Longer timeout for image generation
            'method' => 'POST',
        ];

        $response = wp_remote_post('https://api.openai.com/v1/images/generations', $args);

        if (is_wp_error($response)) {
            return new \WP_Error('api_error', $response->get_error_message());
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($response_body, true);

        if ($response_code !== 200) {
            $error_message = isset($decoded_body['error']['message']) 
                ? $decoded_body['error']['message'] 
                : esc_html__('Image generation failed.', 'sastra-essential-addons-for-elementor');
            return new \WP_Error('api_error', $error_message, ['status' => $response_code]);
        }

        if (!isset($decoded_body['data'][0]['url'])) {
            return new \WP_Error('api_error', esc_html__('Unexpected API response format.', 'sastra-essential-addons-for-elementor'));
        }

        return [
            'url' => $decoded_body['data'][0]['url'],
            'usage' => $decoded_body['usage'] ?? []
        ];
    }

    /**
     * Validate image generation parameters based on model
     */
    private function validate_image_params($model, $quality, $size, $background) {
        $validated = [
            'quality' => $quality,
            'size' => $size,
            'background' => $background
        ];

        if ($model === 'dall-e-3') {
            // DALL-E 3 validations
            $valid_qualities = ['hd', 'standard'];
            if (!in_array($quality, $valid_qualities)) {
                $validated['quality'] = 'hd';
            }

            $valid_sizes = ['1024x1024', '1024x1792', '1792x1024'];
            if (!in_array($size, $valid_sizes)) {
                $validated['size'] = '1024x1024';
            }
        } elseif ($model === 'gpt-image-1') {
            // GPT Image 1 validations
            $valid_qualities = ['high', 'medium', 'low', 'auto'];
            if (!in_array($quality, $valid_qualities)) {
                $validated['quality'] = 'high';
            }

            $valid_sizes = ['auto', '1024x1024', '1536x1024', '1024x1536'];
            if (!in_array($size, $valid_sizes)) {
                $validated['size'] = 'auto';
            }
        }

        return $validated;
    }

    /**
     * Create WordPress attachment from image URL
     */
    private function create_attachment_from_url($image_url, $prompt) {
        // Download the image
        $response = wp_remote_get($image_url, [
            'timeout' => 60,
            'sslverify' => false
        ]);

        if (is_wp_error($response)) {
            return new \WP_Error('download_error', $response->get_error_message());
        }

        $image_data = wp_remote_retrieve_body($response);
        if (empty($image_data)) {
            return new \WP_Error('download_error', esc_html__('Failed to download image.', 'sastra-essential-addons-for-elementor'));
        }

        // Generate filename
        $filename = 'ai-generated-' . uniqid() . '.png';
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['path'] . '/' . $filename;

        // Save the image
        $result = file_put_contents($file_path, $image_data);
        if ($result === false) {
            return new \WP_Error('save_error', esc_html__('Failed to save image.', 'sastra-essential-addons-for-elementor'));
        }

        // Prepare attachment data
        $attachment = [
            'post_mime_type' => 'image/png',
            'post_title' => sanitize_text_field($prompt),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        // Insert attachment
        $attachment_id = wp_insert_attachment($attachment, $file_path);

        if (is_wp_error($attachment_id)) {
            return $attachment_id;
        }

        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        // Set alt text based on prompt
        $alt_text = $this->generate_alt_text_from_prompt($prompt);
        update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);

        return $attachment_id;
    }

    /**
     * Generate alt text from prompt
     */
    private function generate_alt_text_from_prompt($prompt) {
        // Clean up the prompt to create a reasonable alt text
        $alt_text = sanitize_text_field($prompt);
        $alt_text = mb_substr($alt_text, 0, 125); // Limit to 125 characters
        
        return $alt_text;
    }
}
