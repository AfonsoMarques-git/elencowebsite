/**
 * Spexo AI Admin JavaScript
 * 
 * Handles AI settings admin page functionality
 *
 * @package Spexo_Addons
 * @since 1.0.28
 */

(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initAiAdmin();
    });

    function initAiAdmin() {
        // API Key visibility toggle
        initApiKeyToggle();
        
        // Form validation
        initFormValidation();
        
        // Settings save feedback
        initSaveFeedback();
        
        // Token usage refresh
        initTokenUsageRefresh();
        
        // Model selection handlers
        initModelSelection();
        
        // Feature toggle handlers
        initFeatureToggles();
    }

    function initApiKeyToggle() {
        var $apiKeyField = $('#spexo-openai-api-key');
        if (!$apiKeyField.length) {
            return;
        }

        var $toggle = $('.spexo-ai-api-key-toggle');
        if (!$toggle.length) {
            return;
        }

        $toggle.off('click.spexoAiToggle').on('click.spexoAiToggle', function(e) {
            e.preventDefault();
            var $field = $('#spexo-openai-api-key');
            var $eyeIcon = $(this).find('.spexo-ai-eye-icon');
            var $eyeOffIcon = $(this).find('.spexo-ai-eye-off-icon');
            var type = $field.attr('type') === 'password' ? 'text' : 'password';
            $field.attr('type', type);
            
            if (type === 'text') {
                $eyeIcon.hide();
                $eyeOffIcon.show();
            } else {
                $eyeIcon.show();
                $eyeOffIcon.hide();
            }
        });
    }

    function initFormValidation() {
        var $form = $('.spexo-ai-settings-form, [data-spexo-ai-form="true"]');
        
        $form.on('submit', function(e) {
            var isValid = true;
            var $apiKeyField = $('input[name="spexo_ai_options[openai_api_key]"]');
            var $tokenLimitField = $('input[name="spexo_ai_options[daily_token_limit]"]');
            
            // Validate API key if provided
            if ($apiKeyField.val().trim() && !isValidApiKey($apiKeyField.val())) {
                showFieldError($apiKeyField, 'Invalid API key format. OpenAI API keys should start with "sk-".');
                isValid = false;
            }
            
            // Validate token limit
            var tokenLimit = parseInt($tokenLimitField.val());
            if (isNaN(tokenLimit) || tokenLimit < 0) {
                showFieldError($tokenLimitField, 'Token limit must be a positive number.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            showSaveLoading();
        });
    }

    function isValidApiKey(apiKey) {
        // Basic validation for OpenAI API key format
        return apiKey.startsWith('sk-') && apiKey.length > 20;
    }

    function showFieldError($field, message) {
        // Remove existing error
        $field.next('.spexo-ai-field-error').remove();
        
        // Add error message
        var $error = $('<div class="spexo-ai-field-error"></div>')
            .text(message)
            .css({
                color: '#dc3545',
                fontSize: '12px',
                marginTop: '4px',
                fontWeight: '500'
            });
        
        $field.after($error);
        $field.css('border-color', '#dc3545');
        
        // Focus on the field
        $field.focus();
        
        // Remove error on input
        $field.one('input', function() {
            $error.remove();
            $field.css('border-color', '');
        });
    }

    function showSaveLoading() {
        var $submitBtn = $('.spexo-ai-settings-form .button-primary, [data-spexo-ai-form="true"] .button-primary');
        var originalText = $submitBtn.text();
        
        $submitBtn.prop('disabled', true)
            .text('Saving...')
            .addClass('spexo-ai-loading');
        
        // Re-enable after 3 seconds (in case of slow response)
        setTimeout(function() {
            $submitBtn.prop('disabled', false)
                .text(originalText)
                .removeClass('spexo-ai-loading');
        }, 3000);
    }

    function initSaveFeedback() {
        // Check for success message in URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('settings-updated') === 'true') {
            showSuccessMessage('Settings saved successfully!');
        }
        
        // Check for error message in URL
        if (urlParams.get('error') === 'true') {
            showErrorMessage('Failed to save settings. Please try again.');
        }
    }

    function showSuccessMessage(message) {
        showMessage(message, 'success');
    }

    function showErrorMessage(message) {
        showMessage(message, 'error');
    }

    function showMessage(message, type) {
        var $message = $('<div class="spexo-ai-message ' + type + '"></div>')
            .text(message)
            .css({
                position: 'fixed',
                top: '32px',
                right: '20px',
                zIndex: 999999,
                maxWidth: '400px',
                padding: '15px 20px',
                borderRadius: '6px',
                fontSize: '14px',
                fontWeight: '500',
                boxShadow: '0 4px 15px rgba(0, 0, 0, 0.2)',
                animation: 'fadeIn 0.3s ease'
            });
        
        $('body').append($message);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $message.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    function initTokenUsageRefresh() {
        var $refreshBtn = $('.spexo-ai-token-refresh');
        var refreshInterval = null;
        var lastRefreshTime = 0;
        var refreshThrottle = 10000; // Minimum 10 seconds between refreshes
        
        if ($refreshBtn.length) {
            $refreshBtn.on('click', function(e) {
                e.preventDefault();
                refreshTokenUsage();
            });
        }
        
        // Auto refresh functionality removed - not needed per reference plugin
    }

    function updateTokenUsageDisplay(data) {
        var $progressBar = $('.spexo-ai-progress');
        var $progressText = $('.spexo-ai-progress-text');
        
        var percentage = data.daily_limit > 0 ? (data.daily_used / data.daily_limit) * 100 : 0;
        percentage = Math.min(percentage, 100);
        
        $progressBar.css('width', percentage + '%');
        
        if ($progressText.length) {
            $progressText.text(data.daily_used.toLocaleString() + ' / ' + data.daily_limit.toLocaleString() + ' tokens');
        }
        
        // Update color based on usage
        if (percentage > 90) {
            $progressBar.css('background', 'linear-gradient(90deg, #dc3545 0%, #fd7e14 100%)');
        } else if (percentage > 70) {
            $progressBar.css('background', 'linear-gradient(90deg, #ffc107 0%, #fd7e14 100%)');
        } else {
            $progressBar.css('background', 'linear-gradient(90deg, #28a745 0%, #20c997 100%)');
        }
    }

    function initModelSelection() {
        var $textModel = $('select[name="spexo_ai_options[openai_model]"]');
        var $imageModel = $('select[name="spexo_ai_options[openai_image_model]"]');
        
        // Add model descriptions
        addModelDescriptions();
        
        // Handle model changes
        $textModel.on('change', function() {
            showModelInfo($(this).val(), 'text');
        });
        
        $imageModel.on('change', function() {
            showModelInfo($(this).val(), 'image');
        });
        
        // Handle refresh models button
        $('#spexo-refresh-models-btn').on('click', function() {
            refreshModels();
        });
    }

    function refreshModels() {
        var $btn = $('#spexo-refresh-models-btn');
        
        // Check if button is disabled (Pro feature)
        if ($btn.prop('disabled')) {
            return;
        }
        
        var $icon = $btn.find('.dashicons');
        var originalText = $btn.html();
        
        // Show loading state
        $btn.css('color', '#5729d9!important');
        $btn.prop('disabled', true);
        $icon.removeClass('dashicons-update').addClass('dashicons-update-alt');
        $btn.html('<span class="dashicons dashicons-update-alt" style="margin-right: 5px; animation: spin 1s linear infinite; color: #5729d9!important;"></span>Refreshing...');       
        
        // Add spin animation
        if (!$('#spexo-spin-animation').length) {
            $('head').append('<style id="spexo-spin-animation">@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>');
        }
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'spexo_ai_refresh_models',
                nonce: SpexoAiAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateModelSelects(response.data.models);
                    showNotice('Models refreshed successfully!', 'success');
                } else {
                    showNotice('Failed to refresh models: ' + (response.data.message || 'Unknown error'), 'error');
                }
            },
            error: function() {
                showNotice('Failed to refresh models. Please check your API key and try again.', 'error');
            },
            complete: function() {
                // Restore button state
                $btn.prop('disabled', false);
                $btn.html(originalText);
                $icon.removeClass('dashicons-update-alt').addClass('dashicons-update');
            }
        });
    }

    function updateModelSelects(models) {
        var $textSelect = $('#spexo-openai-model-select');
        var $imageSelect = $('select[name="spexo_ai_options[openai_image_model]"]');
        var currentTextModel = $textSelect.val();
        var currentImageModel = $imageSelect.val();
        
        // Update text models
        $textSelect.empty();
        var textModels = {};
        Object.keys(models).forEach(function(key) {
            if (key.indexOf('gpt-') === 0) {
                textModels[key] = models[key];
                $textSelect.append('<option value="' + key + '">' + models[key] + '</option>');
            }
        });
        
        // Update image models
        $imageSelect.empty();
        var imageModels = {};
        Object.keys(models).forEach(function(key) {
            if (key.indexOf('dall-e-') === 0) {
                imageModels[key] = models[key];
                $imageSelect.append('<option value="' + key + '">' + models[key] + '</option>');
            }
        });
        
        // Restore selected values if they still exist
        if (textModels[currentTextModel]) {
            $textSelect.val(currentTextModel);
        }
        if (imageModels[currentImageModel]) {
            $imageSelect.val(currentImageModel);
        }
        
        // Update descriptions
        addModelDescriptions();
    }

    function showNotice(message, type) {
        var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        var $wrap = $('.wrap').first();

        if ($wrap.length) {
            if ($wrap.find('h1').length) {
                $wrap.find('h1').first().after($notice);
            } else {
                $wrap.prepend($notice);
            }
        } else {
            $('body').append($notice);
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $notice.remove();
            });
        }, 5000);
    }

    function addModelDescriptions() {
        var textModels = {
            'gpt-4o': 'Most capable model, best for complex tasks',
            'gpt-4o-mini': 'Faster and cheaper alternative to GPT-4o',
            'gpt-4-turbo': 'High performance with extended context',
            'gpt-3.5-turbo': 'Fast and cost-effective for simple tasks'
        };
        
        var imageModels = {
            'dall-e-3': 'Highest quality image generation',
            'gpt-image-1': 'Alternative image generation model'
        };
        
        // Add descriptions after model selects
        $('select[name="spexo_ai_options[openai_model]"]').after(
            '<div class="spexo-ai-model-description" style="font-size: 12px; color: #6c757d; margin-top: 4px;"></div>'
        );
        
        $('select[name="spexo_ai_options[openai_image_model]"]').after(
            '<div class="spexo-ai-model-description" style="font-size: 12px; color: #6c757d; margin-top: 4px;"></div>'
        );
    }

    function showModelInfo(model, type) {
        var $description = $('select[name="spexo_ai_options[openai_' + type + '_model]"]').next('.spexo-ai-model-description');
        
        var descriptions = {
            text: {
                'gpt-4o': 'Most capable model, best for complex tasks',
                'gpt-4o-mini': 'Faster and cheaper alternative to GPT-4o',
                'gpt-4-turbo': 'High performance with extended context',
                'gpt-3.5-turbo': 'Fast and cost-effective for simple tasks'
            },
            image: {
                'dall-e-3': 'Highest quality image generation',
                'gpt-image-1': 'Alternative image generation model'
            }
        };
        
        if (descriptions[type] && descriptions[type][model]) {
            $description.text(descriptions[type][model]);
        }
    }

    function initFeatureToggles() {
        var $enableButtons = $('input[name="spexo_ai_options[enable_ai_buttons]"]');
        var $enableImageGen = $('input[name="spexo_ai_options[enable_ai_image_generation_button]"]');
        var $enablePageTrans = $('input[name="spexo_ai_options[enable_ai_page_translator]"]');
        
        // Handle feature dependencies
        $enableButtons.on('change', function() {
            if ($(this).is(':checked')) {
                showFeatureInfo('Text generation features will be available in Elementor editor.');
            }
        });
        
        $enableImageGen.on('change', function() {
            if ($(this).is(':checked')) {
                showFeatureInfo('Image generation features will be available in Elementor editor.');
            }
        });
        
        $enablePageTrans.on('change', function() {
            if ($(this).is(':checked')) {
                showFeatureInfo('Page translation features will be available in Elementor editor.');
            }
        });
    }

    function showFeatureInfo(message) {
        var $info = $('<div class="spexo-ai-feature-info"></div>')
            .text(message)
            .css({
                background: '#e7f3ff',
                color: '#0066cc',
                padding: '10px 15px',
                borderRadius: '6px',
                fontSize: '13px',
                marginTop: '8px',
                border: '1px solid #b3d9ff'
            });
        
        // Remove existing info
        $('.spexo-ai-feature-info').remove();
        
        // Add new info
        $('input[name="spexo_ai_options[enable_ai_page_translator]"]').closest('td').append($info);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $info.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Add CSS animations
    function addAnimations() {
        if ($('#spexo-ai-admin-animations').length === 0) {
            $('<style id="spexo-ai-admin-animations">')
                .text(`
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(-10px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    @keyframes pulse {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                        100% { transform: scale(1); }
                    }
                    .spexo-ai-loading {
                        animation: pulse 1s infinite;
                    }
                `)
                .appendTo('head');
        }
    }

    // Initialize animations
    addAnimations();

})(jQuery);
