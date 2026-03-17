<?php
/**
 * Spexo AI Manager
 * 
 * Manages all AI-related functionality for Spexo Addons
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

namespace Spexo_Addons\AI;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Spexo_AI_Manager
 */
class Spexo_AI_Manager {

    /**
     * Instance
     *
     * @var Spexo_AI_Manager|null The single instance of the class.
     */
    private static $instance = null;

    /**
     * Get Instance
     *
     * @return Spexo_AI_Manager
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
        $this->load_ai_features();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // AI Settings menu item removed - settings are now in main Settings page
        // add_action('admin_menu', [$this, 'add_ai_menu']);
        add_action('admin_init', [$this, 'register_ai_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_filter('admin_body_class', [$this, 'add_ai_settings_body_class']);
        add_action('load-options.php', [$this, 'maybe_handle_settings_tab_submission']);
        
        // AJAX handlers
        add_action('wp_ajax_spexo_ai_refresh_models', [$this, 'handle_refresh_models']);
        
        // AJAX handlers are registered in individual AI feature classes
    }

    /**
     * Load AI features
     */
    private function load_ai_features() {
        // Load Alt Text Generator
        require_once TMPCODER_PLUGIN_DIR . 'inc/modules/ai/class-spexo-ai-alt-text-generator.php';
        Spexo_AI_Alt_Text_Generator::get_instance();

        // Load Text Generator
        require_once TMPCODER_PLUGIN_DIR . 'inc/modules/ai/class-spexo-ai-text-generator.php';
        Spexo_AI_Text_Generator::get_instance();

        // Load Image Generator
        require_once TMPCODER_PLUGIN_DIR . 'inc/modules/ai/class-spexo-ai-image-generator.php';
        Spexo_AI_Image_Generator::get_instance();

        // Load Page Translator
        require_once TMPCODER_PLUGIN_DIR . 'inc/modules/ai/class-spexo-ai-page-translator.php';
        Spexo_AI_Page_Translator::get_instance();
    }

    /**
     * Add AI menu to admin
     */
    public function add_ai_menu() {
        $hook = add_submenu_page(
            'spexo-welcome',
            esc_html__('AI Settings', 'sastra-essential-addons-for-elementor'),
            esc_html__('AI Settings', 'sastra-essential-addons-for-elementor'),
            'manage_options',
            'spexo-ai-settings',
            [$this, 'render_ai_settings_page']
        );

        if ( $hook ) {
            add_action( "load-{$hook}", [$this, 'redirect_ai_menu_to_settings'] );
        }
    }

    /**
     * Register AI settings
     */
    public function register_ai_settings() {
        register_setting(
            'spexo_ai_settings',
            'spexo_ai_options',
            [$this, 'sanitize_ai_settings']
        );

        // OpenAI API Settings Section
        add_settings_section(
            'spexo_ai_openai_section',
            esc_html__('OpenAI API Settings', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_openai_section'],
            'spexo-ai-settings'
        );

        add_settings_field(
            'openai_api_key',
            esc_html__('OpenAI API Key', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_api_key_field'],
            'spexo-ai-settings',
            'spexo_ai_openai_section'
        );

        add_settings_field(
            'openai_model',
            esc_html__('OpenAI Model', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_model_field'],
            'spexo-ai-settings',
            'spexo_ai_openai_section'
        );

        add_settings_field(
            'openai_image_model',
            esc_html__('OpenAI Image Model', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_image_model_field'],
            'spexo-ai-settings',
            'spexo_ai_openai_section'
        );

        // Editor Integration Section
        add_settings_section(
            'spexo_ai_editor_section',
            esc_html__('Editor Integration', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_editor_section'],
            'spexo-ai-settings'
        );

        add_settings_field(
            'enable_ai_buttons',
            esc_html__('AI Text Editing Buttons', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_ai_buttons_field'],
            'spexo-ai-settings',
            'spexo_ai_editor_section'
        );

        add_settings_field(
            'enable_ai_image_generation_button',
            esc_html__('AI Image Generation Button', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_ai_image_generation_field'],
            'spexo-ai-settings',
            'spexo_ai_editor_section'
        );

        // Alt Text Settings Section
        add_settings_section(
            'spexo_ai_alt_text_section',
            esc_html__('Alt Text Settings', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_alt_text_section'],
            'spexo-ai-settings'
        );

        add_settings_field(
            'enable_ai_alt_text_button',
            esc_html__('AI Alt Text Button', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_alt_text_button_field'],
            'spexo-ai-settings',
            'spexo_ai_alt_text_section'
        );

        add_settings_field(
            'enable_ai_alt_text_auto_generation',
            esc_html__('Auto Generate Alt Text', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_alt_text_auto_generation_field'],
            'spexo-ai-settings',
            'spexo_ai_alt_text_section'
        );

        add_settings_field(
            'ai_alt_text_generation_interval',
            esc_html__('Alt Text Generation Interval', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_alt_text_interval_field'],
            'spexo-ai-settings',
            'spexo_ai_alt_text_section'
        );

        add_settings_field(
            'ai_alt_text_image_detail_level',
            esc_html__('Image Detail Level', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_alt_text_detail_level_field'],
            'spexo-ai-settings',
            'spexo_ai_alt_text_section'
        );

        // Translation Settings Section
        add_settings_section(
            'spexo_ai_translation_section',
            esc_html__('Translation Settings', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_translation_section'],
            'spexo-ai-settings'
        );

        add_settings_field(
            'enable_ai_page_translator',
            esc_html__('AI Page Translator Button', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_page_translator_field'],
            'spexo-ai-settings',
            'spexo_ai_translation_section'
        );

        // Usage Quota Settings Section
        add_settings_section(
            'spexo_ai_quota_section',
            esc_html__('Usage Quota Settings', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_quota_section'],
            'spexo-ai-settings'
        );

        add_settings_field(
            'daily_token_limit',
            esc_html__('Daily Token Limit', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_daily_limit_field'],
            'spexo-ai-settings',
            'spexo_ai_quota_section'
        );

        // Usage Statistics Section
        add_settings_section(
            'spexo_ai_stats_section',
            esc_html__('Usage Statistics', 'sastra-essential-addons-for-elementor'),
            [$this, 'render_stats_section'],
            'spexo-ai-settings'
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        $is_ai_page       = ('spexo-addons_page_spexo-ai-settings' === $hook);
        $is_settings_tab  = false;

        if ('toplevel_page_spexo-welcome' === $hook) {
            $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'getting-started'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $is_settings_tab = ('settings' === $tab);
        }

        if (!$is_ai_page && !$is_settings_tab) {
            return;
        }

        // Determine file extensions based on SCRIPT_DEBUG
        $css_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.css' : '.min.css';
        $js_ext = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.js' : '.min.js';

        wp_enqueue_style(
            'spexo-ai-admin',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/css/ai-admin' . $css_ext,
            [],
            TMPCODER_PLUGIN_VER
        );

        wp_enqueue_script(
            'spexo-ai-admin',
            TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/js/ai-admin' . $js_ext,
            ['jquery'],
            TMPCODER_PLUGIN_VER,
            true
        );

        wp_localize_script('spexo-ai-admin', 'SpexoAiAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spexo_ai_admin_nonce'),
            'text' => [
                'generating' => esc_html__('Generating...', 'sastra-essential-addons-for-elementor'),
                'error' => esc_html__('Error', 'sastra-essential-addons-for-elementor'),
                'success' => esc_html__('Success', 'sastra-essential-addons-for-elementor'),
            ]
        ]);
    }

    /**
     * Add body class for AI Settings page to match common header styling
     */
    public function add_ai_settings_body_class($classes) {
        $screen = get_current_screen();
        
        // Add the common header body class on AI Settings page
        if ($screen && $screen->id === 'spexo-addons_page_spexo-ai-settings') {
            $classes .= ' toplevel_page_spexo-welcome';
        }
        
        return $classes;
    }

    /**
     * Render AI settings page
     */
    public function render_ai_settings_page() {
        // Fallback renderer (should never be reached because of redirect hook).
        echo '<div class="wrap"><p>' . esc_html__( 'Redirecting to AI Settings…', 'sastra-essential-addons-for-elementor' ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Redirect AI submenu to Settings tab.
     */
    public function redirect_ai_menu_to_settings() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $target_url = admin_url( 'admin.php?page=' . TMPCODER_THEME . '-welcome&tab=settings#ai-settings-section' );
        wp_safe_redirect( $target_url );
        exit;
    }

    /**
     * Sanitize AI settings
     */
    public function sanitize_ai_settings($input) {
        $sanitized = [];
        $is_pro = tmpcoder_is_availble();
        $old_options = get_option('spexo_ai_options', []);
        
        $sanitized['openai_api_key'] = isset($input['openai_api_key']) 
            ? sanitize_text_field($input['openai_api_key']) 
            : '';
        
        // PRO FEATURE: OpenAI Model Selection
        if ($is_pro) {
            $sanitized['openai_model'] = isset($input['openai_model']) 
                ? sanitize_text_field($input['openai_model']) 
                : 'gpt-4o';
        } else {
            // Force default model for free version
            $sanitized['openai_model'] = 'gpt-4o-mini';
        }
        
        // PRO FEATURE: OpenAI Image Model Selection
        if ($is_pro) {
            $sanitized['openai_image_model'] = isset($input['openai_image_model']) 
                ? sanitize_text_field($input['openai_image_model']) 
                : 'dall-e-3';
        } else {
            // Force default image model for free version
            $sanitized['openai_image_model'] = 'dall-e-3';
        }

        $sanitized['daily_token_limit'] = isset($input['daily_token_limit']) 
            ? max(0, absint($input['daily_token_limit'])) 
            : 1000000;

        $sanitized['enable_ai_buttons'] = !empty($input['enable_ai_buttons']);
        $sanitized['enable_ai_image_generation_button'] = !empty($input['enable_ai_image_generation_button']);
        $sanitized['enable_ai_alt_text_button'] = !empty($input['enable_ai_alt_text_button']);
        
        // PRO FEATURE: Auto Generate Alt Text
        if ($is_pro) {
            $sanitized['enable_ai_alt_text_auto_generation'] = !empty($input['enable_ai_alt_text_auto_generation']);
        } else {
            // Force disabled for free version
            $sanitized['enable_ai_alt_text_auto_generation'] = false;
        }
        
        $sanitized['enable_ai_page_translator'] = !empty($input['enable_ai_page_translator']);

        // PRO FEATURE: Alt Text Generation Interval
        if ($is_pro) {
            if (isset($input['ai_alt_text_generation_interval'])) {
                $interval = absint($input['ai_alt_text_generation_interval']);
                $sanitized['ai_alt_text_generation_interval'] = max(10, min(3600, $interval));
            } else {
                $sanitized['ai_alt_text_generation_interval'] = 60;
            }
        } else {
            // Keep existing value or default for free version
            $sanitized['ai_alt_text_generation_interval'] = $old_options['ai_alt_text_generation_interval'] ?? 60;
        }

        // PRO FEATURE: Image Detail Level
        if ($is_pro) {
            $allowed_detail_levels = ['low', 'high'];
            $sanitized['ai_alt_text_image_detail_level'] = in_array(($input['ai_alt_text_image_detail_level'] ?? 'low'), $allowed_detail_levels, true)
                ? $input['ai_alt_text_image_detail_level']
                : 'low';
        } else {
            // Force 'low' for free version
            $sanitized['ai_alt_text_image_detail_level'] = 'low';
        }

        return $sanitized;
    }

    // Section and field render methods
    public function render_openai_section() {
        echo '<p class="tmpcoder-ai-option-subtitle">' . esc_html__('Configure your OpenAI API settings to enable AI features.', 'sastra-essential-addons-for-elementor') . '</p>';
    }

    public function render_editor_section() {
        echo '<p class="tmpcoder-ai-option-subtitle">' . esc_html__('Control which AI features are available in the Elementor editor.', 'sastra-essential-addons-for-elementor') . '</p>';
    }

    public function render_alt_text_section() {
        echo '<p class="tmpcoder-ai-option-subtitle">' . esc_html__('Configure automatic alt text generation for images.', 'sastra-essential-addons-for-elementor') . '</p>';
    }

    public function render_translation_section() {
        echo '<p class="tmpcoder-ai-option-subtitle">' . esc_html__('Configure AI-powered page translation features.', 'sastra-essential-addons-for-elementor') . '</p>';
    }

    public function render_quota_section() {
        echo '<p class="tmpcoder-ai-option-subtitle">' . esc_html__('Set daily token limits to control API usage costs.', 'sastra-essential-addons-for-elementor') . '</p>';
    }

    public function render_stats_section() {
        $options = get_option('spexo_ai_options', []);
        $daily_used = get_option('spexo_ai_daily_tokens_used', 0);
        $daily_limit = $options['daily_token_limit'] ?? 1000000;

        // Calculate remaining tokens
        if ($daily_limit === 0) {
            $remaining_display = esc_html__('Unlimited', 'sastra-essential-addons-for-elementor');
        } else {
            $remaining_calc = max(0, $daily_limit - $daily_used);
            $remaining_display = esc_html(number_format($remaining_calc));
        }
        ?>
        <div class="spexo-ai-stats-wrapper">
            <div class="spexo-ai-stats-item">
                <span class="spexo-ai-stats-label"><?php esc_html_e('Tokens Used Today', 'sastra-essential-addons-for-elementor'); ?></span>
                <span class="spexo-ai-stats-value"><?php echo esc_html(number_format($daily_used)); ?></span>
            </div>
            <div class="spexo-ai-stats-item">
                <span class="spexo-ai-stats-label"><?php esc_html_e('Daily Limit', 'sastra-essential-addons-for-elementor'); ?></span>
                <span class="spexo-ai-stats-value"><?php echo $daily_limit === 0 ? esc_html__('Unlimited', 'sastra-essential-addons-for-elementor') : esc_html(number_format($daily_limit)); ?></span>
            </div>
            <div class="spexo-ai-stats-item">
                <span class="spexo-ai-stats-label"><?php esc_html_e('Remaining', 'sastra-essential-addons-for-elementor'); ?></span>
                <span class="spexo-ai-stats-value"><?php echo $remaining_display; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            </div>
        </div>
        <?php
    }


    public function render_api_key_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['openai_api_key'] ?? '';
        ?>
        <div class="spexo-ai-api-key-wrapper">
            <div class="pb-16">
                <div class="form-group-wrapper">
                    <div class="form-group">
                        <div class="spexo-woo-page-config-field">
                            <label class="spexo-woo-page-config-label" for="openai_api_key">OpenAI API Key</label>
                            <div class="spexo-woo-page-config-input-wrapper">
                                <input type="password" name="spexo_ai_options[openai_api_key]" id="spexo-openai-api-key" value="<?php echo esc_attr($value); ?>" class="spexo-woo-page-config-input spexo-ai-api-key-input form-control" data-default="" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" />
                                <button type="button" class="spexo-ai-api-key-toggle" aria-label="<?php esc_attr_e('Show/Hide API Key', 'sastra-essential-addons-for-elementor'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="spexo-ai-eye-icon">
                                        <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zM10 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                    </svg>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="spexo-ai-eye-off-icon" style="display: none;">
                                        <path d="M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm-3-5c0-1.66 1.34-3 3-3 .28 0 .55.04.81.1l-3.71 3.71c-.06-.26-.1-.53-.1-.81zm3.71 1.9c.26.06.53.1.81.1 1.66 0 3-1.34 3-3 0-.28-.04-.55-.1-.81l-3.71 3.71z" fill="currentColor"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Important Notice (Red Box) -->
            <div class="spexo-ai-warning-box">
                <div class="spexo-ai-warning-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-red-500 mt-0.5" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path>
                </svg>
                </div>
                <div class="spexo-ai-warning-content">
                    
                    <span><strong><?php esc_html_e('Credit Required : ', 'sastra-essential-addons-for-elementor'); ?></strong> <?php esc_html_e('You must top up your OpenAI account balance by at least $5. Free accounts are not supported.', 'sastra-essential-addons-for-elementor'); ?></span>
                </div>
            </div>
            
            <!-- Info Notice (Blue Box) -->
            <div class="spexo-ai-info-box">
                <div class="spexo-ai-info-icon">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="9" stroke="#3b82f6" stroke-width="2" fill="none"></circle>
                        <path d="M10 6v4M10 14h.01" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="spexo-ai-info-content">
    
                    <span><strong><?php esc_html_e('Tip : ', 'sastra-essential-addons-for-elementor'); ?></strong> <?php esc_html_e('With GPT-4o-mini, a $5 balance is enough for roughly 130,000 text generations.', 'sastra-essential-addons-for-elementor'); ?></span>
                </div>
            </div>
            
            <!-- Useful OpenAI Links -->
            <div class="spexo-ai-info-box">
                <div class="spexo-ai-info-icon">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="10" r="9" stroke="#3b82f6" stroke-width="2" fill="none"></circle>
                        <path d="M10 6v4M10 14h.01" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="spexo-ai-info-content">
                    <strong><?php esc_html_e('Useful OpenAI Links:', 'sastra-essential-addons-for-elementor'); ?></strong>
                    <ul>
                        <li><a href="https://openai.com/pricing" target="_blank" rel="noopener noreferrer"><?php esc_html_e('API Pricing', 'sastra-essential-addons-for-elementor'); ?></a></li>
                        <li><a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener noreferrer"><?php esc_html_e('API Keys', 'sastra-essential-addons-for-elementor'); ?></a></li>
                        <li><a href="https://platform.openai.com/usage" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Usage Dashboard', 'sastra-essential-addons-for-elementor'); ?></a></li>
                        <li><a href="https://platform.openai.com/account/billing" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Billing Overview', 'sastra-essential-addons-for-elementor'); ?></a></li>
                        <li><a href="https://platform.openai.com/docs/guides/rate-limits" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Rate Limits', 'sastra-essential-addons-for-elementor'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_model_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['openai_model'] ?? 'gpt-4o';
        $is_pro = tmpcoder_is_availble();
        $upgrade_link = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
        
        // Get cached models or use default
        $cached_models = get_option('spexo_ai_cached_models', []);
        $models = !empty($cached_models) ? $cached_models : [
            'gpt-4o' => 'GPT-4o',
            'gpt-4o-mini' => 'GPT-4o Mini',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo'
        ];
        ?>
        <div class="pt-16">
            <div class="form-group-wrapper pb-20">
                <div class="form-group">
                    <div class="spexo-woo-page-config-field">
                        <label><?php echo esc_html('OpenAI Model') ?></label>
                        <select name="spexo_ai_options[openai_model]" id="spexo-openai-model-select" class="spexo-woo-images-select form-control"<?php echo $is_pro ? '' : ' disabled'; ?> data-default="gpt-4o">
                            <?php foreach ($models as $key => $label) : ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="button" id="spexo-refresh-models-btn" class="button button-secondary"<?php echo ($is_pro ? '' : ' disabled') ?> >
                        <span class="dashicons dashicons-update" style="margin-right: 5px;"></span>
                        <?php echo esc_html__('List Refresh', 'sastra-essential-addons-for-elementor') ?>
                        </button>
                        <?php if ( ! $is_pro ) : ?>
                        <p class="spexo-woo-page-config-pro-note">
                            <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                            <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_image_model_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['openai_image_model'] ?? 'dall-e-3';
        $is_pro = tmpcoder_is_availble();
        $upgrade_link = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
        // Get cached models or use default
        $cached_models = get_option('spexo_ai_cached_models', []);
        $image_models = [];
        
        // Filter for image models from cached models
        if (!empty($cached_models)) {
            foreach ($cached_models as $key => $label) {
                if (strpos($key, 'dall-e-') === 0) {
                    $image_models[$key] = $label;
                }
            }
        }
        
        // Fallback to default if no cached image models
        if (empty($image_models)) {
            $image_models = [
                'dall-e-3' => 'DALL-E 3',
                'dall-e-2' => 'DALL-E 2'
            ];
        }
        ?>
        <div class="form-group-wrapper">
            <div class="form-group">
                <div class="spexo-woo-page-config-field">
                    <label><?php echo esc_html('OpenAI Image Model') ?></label>
                    <select name="spexo_ai_options[openai_image_model]" class="spexo-woo-images-select form-control"<?php echo $is_pro ? '' : ' disabled'; ?> data-default="dall-e-3">
                        <?php foreach ($image_models as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ( ! $is_pro ) : ?>
                    <p class="spexo-woo-page-config-pro-note">
                        <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                        <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_ai_buttons_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['enable_ai_buttons'] ?? true;
        ?>
        <div class="spexo-woo-config-toggle-wrapper">
			<div class="spexo-woo-config-toggle-label">
				<strong class="spexo-woo-config-toggle-title"><?php esc_html_e('AI Text Editing Buttons', 'sastra-essential-addons-for-elementor'); ?></strong>
				<span class="spexo-woo-config-toggle-description"><?php esc_html_e('Shows AI Generate buttons in text fields, textareas, and WYSIWYG editors.', 'sastra-essential-addons-for-elementor'); ?></span>
			</div>
			<div class="spexo-checkbox-toggle">
            <input type="checkbox" id="spexo-ai-buttons" name="spexo_ai_options[enable_ai_buttons]" value="1" <?php checked($value, true); ?> data-default="on" />
				<label for="spexo-ai-buttons"></label>
			</div>
		</div>
        <?php
    }

    public function render_ai_image_generation_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['enable_ai_image_generation_button'] ?? true;
        ?>
        <div class="spexo-woo-config-toggle-wrapper">
			<div class="spexo-woo-config-toggle-label">
				<strong class="spexo-woo-config-toggle-title"><?php esc_html_e('AI Image Generation Button', 'sastra-essential-addons-for-elementor'); ?></strong>
				<span class="spexo-woo-config-toggle-description"><?php esc_html_e('Shows AI Generate Image button in Elementor image controls.', 'sastra-essential-addons-for-elementor'); ?></span>
			</div>
			<div class="spexo-checkbox-toggle">
            <input type="checkbox" id="spexo-ai-image-gen" name="spexo_ai_options[enable_ai_image_generation_button]" value="1" <?php checked($value, true); ?> data-default="on" />
				<label for="spexo-ai-image-gen"></label>
			</div>
		</div>
        <?php
    }

    public function render_alt_text_button_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['enable_ai_alt_text_button'] ?? true;
        ?>
        <div class="spexo-woo-config-toggle-wrapper">
			<div class="spexo-woo-config-toggle-label">
				<strong class="spexo-woo-config-toggle-title"><?php esc_html_e('AI Alt Text Button', 'sastra-essential-addons-for-elementor'); ?></strong>
				<span class="spexo-woo-config-toggle-description"><?php esc_html_e('Show \'Generate\' button in Media Library.', 'sastra-essential-addons-for-elementor'); ?></span>
			</div>
			<div class="spexo-checkbox-toggle">
            <input type="checkbox" id="spexo-ai-alt-text-btn" name="spexo_ai_options[enable_ai_alt_text_button]" value="1" <?php checked($value, true); ?> data-default="on" />
				<label for="spexo-ai-alt-text-btn"></label>
			</div>
		</div>
        <?php
    }

    public function render_alt_text_auto_generation_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['enable_ai_alt_text_auto_generation'] ?? false;
        $is_pro = tmpcoder_is_availble();
        $upgrade_link = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
        ?>
        <div class="spexo-woo-config-toggle-wrapper">
			<div class="spexo-woo-config-toggle-label">
				<strong class="spexo-woo-config-toggle-title"><?php esc_html_e('Auto Generate Alt Text', 'sastra-essential-addons-for-elementor'); ?></strong>
				<span class="spexo-woo-config-toggle-description"><?php esc_html_e('Automatically generate alt text on upload.', 'sastra-essential-addons-for-elementor'); ?></span>
                <?php if ( ! $is_pro ) : ?>
                <p class="spexo-woo-page-config-pro-note">
                    <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                    <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                </p>
                <?php endif; ?>
			</div>
			<div class="spexo-checkbox-toggle">
                <input type="checkbox" id="spexo-ai-auto-alt-text" name="spexo_ai_options[enable_ai_alt_text_auto_generation]" value="1" <?php checked($value, true); ?><?php echo $is_pro ? '' : ' disabled'; ?> data-default="" />
				<label for="spexo-ai-auto-alt-text"></label>
			</div>
		</div>
        <?php
    }

    public function render_alt_text_interval_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['ai_alt_text_generation_interval'] ?? 60;
        $is_pro = tmpcoder_is_availble();
        $upgrade_link = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
        ?>
        <div class="pt-16">
            <div class="form-group-wrapper">
                <div class="form-group">
                    <div class="spexo-woo-page-config-field">
                        <label class="spexo-woo-page-config-label" for="tmpcoder_woo_shop_ppp"><?php esc_html_e('Alt Text Generation Interval', 'sastra-essential-addons-for-elementor'); ?></label>
                        <div class="spexo-woo-page-config-input-wrapper">
                            <input type="number" name="spexo_ai_options[ai_alt_text_generation_interval]" id="ai_alt_text_generation_interval" value="<?php echo esc_attr($value); ?>" min="10" max="3600" class="spexo-woo-page-config-input form-control-number-label form-control"<?php echo $is_pro ? '' : ' disabled'; ?> data-default="60" />
                            <span class="spexo-woo-page-config-suffix"><?php esc_html_e('Recommended:', 'sastra-essential-addons-for-elementor'); ?> 60s</span>
                        </div>
                        <?php if ( ! $is_pro ) : ?>
                        <p class="spexo-woo-page-config-pro-note">
                            <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                            <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_alt_text_detail_level_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['ai_alt_text_image_detail_level'] ?? 'low';
        $is_pro = tmpcoder_is_availble();
        $upgrade_link = TMPCODER_PURCHASE_PRO_URL.'?ref=tmpcoder-plugin-backend-settings-woo-pro#purchasepro';
        ?>
        <div class="pt-16">
            <div class="form-group-wrapper">
                <div class="form-group">
                    <div class="spexo-woo-page-config-field">
                        <div class="spexo-woo-page-config-input-wrapper">
                            <label class="spexo-woo-page-config-label" for="tmpcoder_woo_shop_ppp"><?php esc_html_e('Image Detail Level', 'sastra-essential-addons-for-elementor'); ?></label>
                            <select name="spexo_ai_options[ai_alt_text_image_detail_level]" id="ai_alt_text_image_detail_level" class="spexo-woo-images-select form-control"<?php echo $is_pro ? '' : ' disabled'; ?> data-default="low">
                                <option value="low" <?php selected($value, 'low'); ?>><?php esc_html_e('Low', 'sastra-essential-addons-for-elementor'); ?></option>
                                <option value="high" <?php selected($value, 'high'); ?>><?php esc_html_e('High', 'sastra-essential-addons-for-elementor'); ?></option>
                            </select>
                            <p class="tmpcoder-smooth-scroll-field-description"><?php esc_html_e('Level of detail for image analysis. High uses more tokens.', 'sastra-essential-addons-for-elementor'); ?></p>
                            <?php if ( ! $is_pro ) : ?>
                            <p class="spexo-woo-page-config-pro-note">
                                <span><?php esc_html_e( 'Available in Pro', 'sastra-essential-addons-for-elementor' ); ?></span>
                                <a href="<?php echo esc_url( $upgrade_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'sastra-essential-addons-for-elementor' ); ?></a>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function render_page_translator_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['enable_ai_page_translator'] ?? true;
        ?>
        <div class="spexo-woo-config-toggle-wrapper">
			<div class="spexo-woo-config-toggle-label">
				<strong class="spexo-woo-config-toggle-title"><?php esc_html_e('AI Page Translator Button', 'sastra-essential-addons-for-elementor'); ?></strong>
				<span class="spexo-woo-config-toggle-description"><?php esc_html_e('Show AI Page Translator button in Elementor editor toolbar.', 'sastra-essential-addons-for-elementor'); ?></span>
			</div>
			<div class="spexo-checkbox-toggle">
            <input type="checkbox" id="spexo-ai-page-translator" name="spexo_ai_options[enable_ai_page_translator]" value="1" <?php checked($value, true); ?> data-default="on" />
				<label for="spexo-ai-page-translator"></label>
			</div>
		</div>
        <?php
    }

    public function render_daily_limit_field() {
        $options = get_option('spexo_ai_options', []);
        $value = $options['daily_token_limit'] ?? 1000000;
        $daily_used = get_option('spexo_ai_daily_tokens_used', 0);
        
        // Calculate progress
        $progress = 0;
        $usage_percentage = 0;
        if ($value > 0) {
            $progress = min(100, ($daily_used / $value) * 100);
            $usage_percentage = min(100, ($daily_used / $value) * 100);
        }
        $progress = number_format((float) $progress, 2);
        $usage_percentage_display = number_format((float) $usage_percentage, 1);
        ?>
        <div class="spexo-ai-quota-wrapper">
            <!-- Progress Section -->
            <div class="spexo-ai-quota-progress-section">
                <div class="spexo-ai-quota-usage-header">
                <div class="spexo-ai-quota-usage-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap" aria-hidden="true"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path></svg>
                    <span class="spexo-ai-quota-usage-text">
                        <?php esc_html_e('Used Today:', 'sastra-essential-addons-for-elementor'); ?> <?php echo esc_html(number_format($daily_used)); ?> / <?php echo esc_html(number_format($value)); ?>
                    </span>
                    </div>
                </div>
                <div class="spexo-ai-quota-progress-bar">
                    <div class="spexo-ai-quota-progress" style="width: <?php echo esc_attr($progress); ?>%;"></div>
                </div>
                <div class="spexo-ai-quota-percentage-text text-xs text-right">
                    <?php echo esc_html($usage_percentage_display); ?>% <?php esc_html_e('Consumed', 'sastra-essential-addons-for-elementor'); ?>
                </div>
            </div>
            
            <!-- Input Field -->
            <div class="form-group-wrapper">
                <div class="form-group">
                    <div class="spexo-woo-page-config-field">
                        <div class="spexo-woo-page-config-input-wrapper">
                            <input type="number" name="spexo_ai_options[daily_token_limit]" id="daily_token_limit" value="<?php echo esc_attr($value); ?>" min="0" step="1000" class="spexo-woo-page-config-input form-control-number-label form-control" data-default="1000000" />
                            <span class="spexo-woo-page-config-suffix">tokens</span>
                        </div>
                        <span class="tmpcoder-smooth-scroll-field-description"><?php esc_html_e('Set to 0 for unlimited usage.', 'sastra-essential-addons-for-elementor'); ?></span>
                    </div>
                </div>
            </div>
            <!-- Token Information Box -->
            <div class="spexo-ai-token-info-box">
                <h4 class="spexo-ai-token-info-title"><?php esc_html_e('Token Information', 'sastra-essential-addons-for-elementor'); ?></h4>
                <ul class="spexo-ai-token-info-list">
                    <li><?php esc_html_e('1 token = 4 characters or 0.75 words in English', 'sastra-essential-addons-for-elementor'); ?></li>
                    <li><?php esc_html_e('A full page of text (500 words) is approximately 750 tokens', 'sastra-essential-addons-for-elementor'); ?></li>
                    <li><?php esc_html_e('Recommended daily limit: 10,000 - 50,000 tokens for moderate use', 'sastra-essential-addons-for-elementor'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    // AJAX handlers (placeholder methods - will be implemented in specific classes)
    public function handle_ai_generate_text() {
        // Will be handled by Spexo_AI_Text_Generator
        wp_die();
    }

    public function handle_ai_change_text() {
        // Will be handled by Spexo_AI_Text_Generator
        wp_die();
    }

    public function handle_ai_generate_image() {
        // Will be handled by Spexo_AI_Image_Generator
        wp_die();
    }

    public function handle_ai_check_tokens() {
        // Will be handled by Spexo_AI_Text_Generator
        wp_die();
    }

    public function handle_ai_translate_text() {
        // Will be handled by Spexo_AI_Page_Translator
        wp_die();
    }

    public function handle_ai_generate_alt_text() {
        // Will be handled by Spexo_AI_Alt_Text_Generator
        wp_die();
    }

    public function handle_ai_image_check_limits() {
        // Will be handled by Spexo_AI_Image_Generator
        wp_die();
    }

    /**
     * Handle refresh models AJAX request
     */
    public function handle_refresh_models() {
        check_ajax_referer('spexo_ai_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'sastra-essential-addons-for-elementor')], 403);
        }
        
        $options = get_option('spexo_ai_options', []);
        $api_key = $options['openai_api_key'] ?? null;
        
        if (empty($api_key)) {
            wp_send_json_error(['message' => esc_html__('API key is not set.', 'sastra-essential-addons-for-elementor')], 400);
        }
        
        // Clear models cache
        delete_option('spexo_ai_cached_models');
        
        $models = $this->fetch_openai_models($api_key);
        
        if (is_wp_error($models)) {
            wp_send_json_error(['message' => $models->get_error_message()], 500);
        }
        
        if (empty($models)) {
            wp_send_json_error(['message' => esc_html__('No models returned by API.', 'sastra-essential-addons-for-elementor')], 500);
        }
        
        // Update cached models
        update_option('spexo_ai_cached_models', $models);
        
        wp_send_json_success(['models' => $models]);
    }

    /**
     * Fetch available models from OpenAI API
     */
    private function fetch_openai_models($api_key) {
        if (empty($api_key)) {
            return new \WP_Error('missing_key', esc_html__('API key is required to fetch models.', 'sastra-essential-addons-for-elementor'));
        }
        
        $endpoint = 'https://api.openai.com/v1/models';
        $response = wp_remote_get($endpoint, [
            'headers' => ['Authorization' => 'Bearer ' . $api_key],
            'timeout' => 20,
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if ($code !== 200 || empty($data['data']) || !is_array($data['data'])) {
            $message = $data['error']['message'] ?? esc_html__('Invalid response from API.', 'sastra-essential-addons-for-elementor');
            return new \WP_Error('api_error', $message, ['status' => $code]);
        }
        
        $list = [];
        foreach ($data['data'] as $model) {
            if (isset($model['id'])) {
                $list[$model['id']] = $model['id'];
            }
        }
        
        ksort($list);
        
        if (empty($list)) {
            return new \WP_Error('no_models', esc_html__('No models found via API.', 'sastra-essential-addons-for-elementor'));
        }
        
        return $list;
    }

    /**
     * Handle AI settings posted through the general Settings tab.
     */
    public function maybe_handle_settings_tab_submission() {
        if (empty($_POST['option_page']) || 'tmpcoder-settings' !== sanitize_text_field(wp_unslash($_POST['option_page']))) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            return;
        }

        if (empty($_POST['spexo_ai_options_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['spexo_ai_options_nonce'])), 'spexo_ai_options_integration')) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            return;
        }

        if (!isset($_POST['spexo_ai_options']) || !is_array($_POST['spexo_ai_options'])) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            return;
        }

        $ai_options = array_map('wp_unslash', $_POST['spexo_ai_options']); // phpcs:ignore WordPress.Security.NonceVerification.Missing
        $sanitized  = $this->sanitize_ai_settings($ai_options);

        update_option('spexo_ai_options', $sanitized);
    }

    /**
     * Return the AI settings card markup.
     *
     * @param array $args Configuration arguments.
     *
     * @return string
     */
    public function get_ai_settings_box_html($args = []) {
        $defaults = [
            'wrap'          => true,
            'wrapper_class' => 'settings-box spexo-ai-settings-wrapper',
            'section_id'    => 'ai-settings-section',
            'context'       => 'settings-tab',
            'include_nonce' => false,
            'show_header'   => false,
            'sections'      => [],
        ];

        $args = \wp_parse_args($args, $defaults);

        ob_start();

        if ($args['wrap']) {
            printf(
                '<div id="%1$s" class="%2$s">',
                esc_attr($args['section_id']),
                esc_attr($args['wrapper_class'])
            );
        }

        ?>
        <div class="spexo-ai-settings-content-wrapper spexo-ai-settings-content-wrapper--embedded">
            <?php
            if ($args['show_header']) {
                echo $this->get_ai_header_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            ?>
            <div class="spexo-ai-settings-form spexo-ai-settings-form--embedded">
                <?php
                if ($args['include_nonce']) {
                    wp_nonce_field('spexo_ai_options_integration', 'spexo_ai_options_nonce');
                    echo '<input type="hidden" name="spexo_ai_options_present" value="1" />';
                }

                $sections = array_filter((array) $args['sections']);

                if (!empty($sections)) {
                    echo $this->get_ai_sections_subset_html($sections); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                } else {
                    echo $this->get_ai_sections_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </div>
        </div>
        <?php

        if ($args['wrap']) {
            echo '</div>';
        }

        return ob_get_clean();
    }

    /**
     * Render AI settings header markup.
     *
     * @return string
     */
    private function get_ai_header_html() {
        ob_start();
        ?>
        <div class="spexo-ai-settings-page-header">
            <div class="spexo-ai-settings-header-icon">
                <img width="32" height="32" src="<?php echo esc_url(TMPCODER_PLUGIN_URI . 'inc/modules/ai/assets/images/ai-translator.svg'); ?>" alt="<?php esc_attr_e('AI Settings', 'sastra-essential-addons-for-elementor'); ?>" />
            </div>
            <div class="spexo-ai-settings-header-text">
                <h2 class="spexo-ai-settings-title"><?php esc_html_e('AI Features Configuration', 'sastra-essential-addons-for-elementor'); ?></h2>
                <p class="spexo-ai-settings-subtitle"><?php esc_html_e('Configure OpenAI integration and AI features for Elementor editor', 'sastra-essential-addons-for-elementor'); ?></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Capture the AI settings sections output.
     *
     * @return string
     */
    private function get_ai_sections_html() {
        ob_start();
        do_settings_sections('spexo-ai-settings');
        return ob_get_clean();
    }

    /**
     * Capture only the requested AI settings sections.
     *
     * @param array $section_ids Section IDs to render.
     *
     * @return string
     */
    private function get_ai_sections_subset_html($section_ids = []) {
        if (empty($section_ids)) {
            return $this->get_ai_sections_html();
        }

        $section_ids = array_map('sanitize_key', $section_ids);
        $page        = 'spexo-ai-settings';

        global $wp_settings_sections, $wp_settings_fields;

        if (empty($wp_settings_sections[$page])) {
            return '';
        }

        ob_start();

        foreach ($wp_settings_sections[$page] as $section) {
            if (!in_array($section['id'], $section_ids, true)) {
                continue;
            }

            printf(
                '<div id="%1$s" class="spexo-ai-settings-section spexo-ai-settings-section--%1$s">',
                esc_attr($section['id'])
            );

            if (!empty($section['title'])) {
                printf('<h2>%s</h2>', esc_html($section['title']));
            }

            if (!empty($section['callback']) && is_callable($section['callback'])) {
                call_user_func($section['callback'], $section);
            }

            if (!empty($wp_settings_fields[$page][$section['id']])) {
                // Use div-based layout instead of table
                $is_openai_section = ($section['id'] === 'spexo_ai_openai_section');
                $wrapper_class = $is_openai_section ? 'spexo-ai-settings-fields-wrapper spexo-ai-openai-fields-wrapper' : 'spexo-ai-settings-fields-wrapper';
                printf('<div class="%s">', esc_attr($wrapper_class)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                foreach ((array) $wp_settings_fields[$page][$section['id']] as $field) {
                    $label = isset($field['title']) ? $field['title'] : '';
                    $field_id = $field['id'] ?? '';
                    
                    // Fields that already have their own labels (toggle switches, stats section)
                    $fields_with_own_labels = [
                        'enable_ai_buttons',
                        'enable_ai_image_generation_button',
                        'enable_ai_alt_text_button',
                        'enable_ai_alt_text_auto_generation',
                        'enable_ai_page_translator',
                    ];
                    
                    // Stats section doesn't need a field wrapper
                    if ($section['id'] === 'spexo_ai_stats_section') {
                        if (!empty($field['callback']) && is_callable($field['callback'])) {
                            call_user_func($field['callback'], $field['args'] ?? []);
                        }
                        continue;
                    }
                    
                    $needs_label = !empty($label) && !in_array($field_id, $fields_with_own_labels, true);

                    echo '<div class="spexo-ai-settings-field">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    // if ($needs_label) {
                    //     printf(
                    //         '<label class="spexo-ai-settings-field-label" for="%1$s">%2$s</label>',
                    //         esc_attr($field_id),
                    //         esc_html($label)
                    //     );
                    // }

                    echo '<div class="spexo-ai-settings-field-content">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    if (!empty($field['callback']) && is_callable($field['callback'])) {
                        call_user_func($field['callback'], $field['args'] ?? []);
                    }
                    echo '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }

                echo '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        return ob_get_clean();
    }
}

