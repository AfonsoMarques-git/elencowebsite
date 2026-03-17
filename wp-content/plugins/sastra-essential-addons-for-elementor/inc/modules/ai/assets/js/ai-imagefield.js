(function($, elementor) {
    'use strict';

    // Ensure AI field localization exists
    if (!window.SpexoAiImageField) {
        return;
    }
    var data = window.SpexoAiImageField;

    // Inject Spexo branding styles for image generator buttons
    function injectImageGeneratorStyles() {
        if ($('#spexo-ai-image-generator-styles').length === 0) {
            const styles = `
                <style id="spexo-ai-image-generator-styles">
                    /* Spexo AI Image Generator Button Styles */
                    .spexo-ai-image-generate-btn {
                        background: linear-gradient(135deg, #6A1B9A, #E91E63) !important;
                        border: none !important;
                        color: white !important;
                        padding: 8px 12px !important;
                        border-radius: 6px !important;
                        font-size: 12px !important;
                        font-weight: 500 !important;
                        cursor: pointer !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        gap: 6px !important;
                        transition: all 0.3s ease !important;
                        margin: 0 !important;
                        position: relative !important;
                        z-index: 10 !important;
                        text-decoration: none !important;
                        outline: none !important;
                        box-shadow: 0 2px 8px rgba(106, 27, 154, 0.3) !important;
                    }
                    .spexo-ai-image-generate-btn:hover {
                        background: linear-gradient(135deg, #7B1FA2, #D81B60) !important;
                        box-shadow: 0 4px 16px rgba(106, 27, 154, 0.4) !important;
                        transform: translateY(-1px) !important;
                    }
                    .spexo-ai-image-generate-btn:active {
                        transform: translateY(0) !important;
                        box-shadow: 0 2px 8px rgba(106, 27, 154, 0.3) !important;
                    }
                    .spexo-ai-image-generate-btn img {
                        width: 16px !important;
                        height: 16px !important;
                        flex-shrink: 0 !important;
                    }
                    .spexo-ai-image-generate-btn span {
                        white-space: nowrap !important;
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
                    }
                    
                    /* Submit button in prompt container */
                    .spexo-ai-image-submit {
                        background: linear-gradient(135deg, #6A1B9A, #E91E63) !important;
                        color: white !important;
                        border: none !important;
                        padding: 6px 12px !important;
                        border-radius: 3px !important;
                        cursor: pointer !important;
                        transition: all 0.3s ease !important;
                        box-shadow: 0 2px 8px rgba(106, 27, 154, 0.3) !important;
                    }
                    .spexo-ai-image-submit:hover {
                        background: linear-gradient(135deg, #7B1FA2, #D81B60) !important;
                        box-shadow: 0 4px 16px rgba(106, 27, 154, 0.4) !important;
                        transform: translateY(-1px) !important;
                    }
                </style>
            `;
            $('head').append(styles);
        }
    }

    /**
     * Injects the Generate Image button and prompt UI into media controls
     * @param {jQuery} $container Elementor panel container
     */
    function injectImageControls($container) {
        // Inject Spexo branding styles first
        injectImageGeneratorStyles();
        
        $container.find(
            '.elementor-control.elementor-control-type-media, '
          + '.elementor-control.elementor-control-type-image, '
          + '.elementor-control.elementor-control-type-gallery'
        ).each(function() {
            var $ctrl = $(this);
            // Skip if already injected
            if ($ctrl.find('.spexo-ai-image-btn-wrapper').length) {
                return;
            }
            // Find the label of the media field (not nested labels like Resolution)
            var $mediaTitle = $ctrl.find('.elementor-control-field.elementor-control-media > .elementor-control-title').first();
            if (!$mediaTitle.length) {
                // fallback to any control title
                $mediaTitle = $ctrl.find('.elementor-control-title').first();
            }
            // Wrap button and prompt in container
            var $outer = $('<div class="spexo-ai-image-field-wrapper"></div>');
            // Insert button/prompt wrapper after the input wrapper if available, otherwise after the title
            var $inputWrapper = $ctrl.find('.elementor-control-input-wrapper').first();
            if ($inputWrapper.length) {
                $inputWrapper.after($outer);
            } else {
                $mediaTitle.after($outer);
            }
            var $wrapper = $('<div class="spexo-ai-image-btn-wrapper" style="margin-top:8px;"></div>');
            // Button with icon, matching AI text feature
            var $btn = $('<button type="button" class="spexo-ai-image-generate-btn elementor-button elementor-size-sm"><img src="' + data.icon_url + '" style="width:16px;height:16px;margin-right:6px;vertical-align:middle;"/><span>Generate Image with Spexo</span></button>');
            $outer.append($wrapper);
            $wrapper.append($btn);

            $btn.on('click', function(e) {
                e.preventDefault();
                // Remove existing prompt UI
                $outer.find('.spexo-ai-image-prompt-container').remove();

                // FIRST_EDIT: Check API key validity before proceeding
                var apiKeyValid = true;
                $.ajax({
                    url: SpexoAiImageField.ajax_url,
                    method: 'POST',
                    async: false,
                    dataType: 'json',
                    data: {
                        action: 'spexo_ai_image_check_limits',
                        nonce: SpexoAiImageField.generate_nonce
                    },
                    success: function(resp) {
                        apiKeyValid = resp.success && resp.data.api_key_valid === true;
                    },
                    error: function() {
                        apiKeyValid = true; // on error assume valid
                    }
                });
                if (!apiKeyValid) {
                    var settingsUrl = window.SpexoAiImageField && window.SpexoAiImageField.settings_url
                        ? window.SpexoAiImageField.settings_url
                        : '/wp-admin/admin.php?page=spexo-ai-settings';
                    var $errorMessage = $('<div class="spexo-ai-image-error-message"></div>').css({
                        background: '#e7f3fe',
                        color: '#084d7a',
                        padding: '10px 15px',
                        borderRadius: '4px',
                        border: '1px solid #b6e0fe',
                        margin: '8px 0',
                        fontSize: '13px',
                        fontWeight: '500',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between'
                    });
                    $errorMessage.html(
                        '<span>OpenAI API key is missing or invalid. Please configure your API key in AI Settings.</span>' +
                        '<a href="' + settingsUrl + '" style="color:#0073aa;text-decoration:underline;white-space:nowrap;margin-left:10px;" target="_blank">Settings</a>'
                    );
                    $wrapper.after($errorMessage).hide().fadeIn(200);
                    setTimeout(function() { $errorMessage.fadeOut(200, function() { $(this).remove(); }); }, 5000);
                    return;
                }

                // Proceed to show prompt UI
                // Create prompt UI with inline styles
                var $promptContainer = $('<div class="spexo-ai-image-prompt-container"></div>').css({margin:'8px 0'});

                $btn.prop('disabled', true);
                $btn.css('opacity', 0.5);
                
                // Prompt input field with inline styles 
                var $input = $('<input type="text" class="spexo-ai-image-prompt-input" placeholder="Enter image prompt..." style="width:100%;padding:6px 10px;border:1px solid #ccc;border-radius:4px;margin-bottom:6px;"/>');
                
                // Quality dropdown with inline styles
                var $quality = $('<select class="spexo-ai-image-quality" style="margin-right:6px;"></select>');
                
                // Resolution/Size dropdown with inline styles
                var $resolution = $('<select class="spexo-ai-image-resolution" style="margin-right:6px;"></select>');
                
                // Model selector with inline styles (Pro feature - disabled if not Pro)
                var is_pro = SpexoAiImageField.is_pro !== undefined ? SpexoAiImageField.is_pro : false;
                var $modelSelect = $('<select class="spexo-ai-image-model" style="margin-right:6px;"></select>');
                if (!is_pro) {
                    $modelSelect.prop('disabled', true).css({
                        'opacity': '0.6',
                        'cursor': 'not-allowed'
                    });
                }
                var imageModels = [
                    { value: 'dall-e-3', label: 'DALL·E 3' },
                    { value: 'gpt-image-1', label: 'GPT Image 1' }
                ];
                imageModels.forEach(function(m) {
                    $modelSelect.append('<option value="' + m.value + '">' + m.label + '</option>');
                });
                // Apply default from settings (only if Pro, otherwise force DALL-E 3)
                if (is_pro && SpexoAiImageField.image_model) {
                    $modelSelect.val(SpexoAiImageField.image_model);
                } else {
                    $modelSelect.val('dall-e-3');
                }
                // Define controls per model
                var modelControls = {
                    'dall-e-3': {
                        qualities: [
                            { value: 'hd', label: 'HD (default)' },
                            { value: 'standard', label: 'Standard' }
                        ],
                        sizes: [
                            { value: '1024x1024', label: '1024×1024 (square)' },
                            { value: '1024x1792', label: '1024×1792 (portrait)' },
                            { value: '1792x1024', label: '1792×1024 (landscape)' }
                        ]
                    },
                    'gpt-image-1': {
                        qualities: [
                            { value: 'high', label: 'High (default)' },
                            { value: 'medium', label: 'Medium' },
                            { value: 'low', label: 'Low' },
                            { value: 'auto', label: 'Auto' },
                        ],
                        sizes: [
                            { value: 'auto', label: 'Auto (default)' },
                            { value: '1024x1024', label: '1024×1024 (square)' },
                            { value: '1536x1024', label: '1536×1024 (landscape)' },
                            { value: '1024x1536', label: '1024×1536 (portrait)' }
                        ]
                    }
                };
                // Update controls based on selected model
                function updateControls() {
                    var model = $modelSelect.val();
                    var controls = modelControls[model];
                    if (controls) {
                        $quality.empty();
                        controls.qualities.forEach(function(q) {
                            $quality.append('<option value="' + q.value + '">' + q.label + '</option>');
                        });
                        $resolution.empty();
                        controls.sizes.forEach(function(s) {
                            $resolution.append('<option value="' + s.value + '">' + s.label + '</option>');
                        });
                    }
                }
                // Initial update
                updateControls();
                // Listen for model changes
                $modelSelect.on('change', updateControls);
                // Background checkbox for GPT Image 1 (only visible for GPT Image 1 model)
                var $bgCheckboxLabel = $('<label class="spexo-ai-image-bg-checkbox-label" style="margin-right:6px;margin-bottom:8px;margin-top:8px;display:none;"><input type="checkbox" class="spexo-ai-image-bg-checkbox" style="margin-right:8px;"/>Transparent background</label>');
                
                // Update show/hide of background checkbox based on model selection
                function updateBgCheckbox() {

                    var $bgRow = $bgCheckboxLabel.closest('.spexo-ai-image-field-row');

                    if ($modelSelect.val() === 'gpt-image-1') {

                        $bgCheckboxLabel.show();
                        if ($bgRow.length) {
                            $bgRow.show();
                        }
                    } else {
                        $bgCheckboxLabel.hide().find('input').prop('checked', false);
                        // setTimeout(function(){
                        //     $bgCheckboxLabel.hide();
                        // },100);
                        if ($bgRow.length) {
                            $bgRow.hide();
                        }
                    }
                }
                $modelSelect.on('change', updateBgCheckbox);
                // Call initially to set correct visibility
                updateBgCheckbox();
                // setTimeout(updateBgCheckbox, 50);
                
                // Submit button with icon 
                var $submit = $('<button type="button" class="spexo-ai-image-prompt-submit elementor-button elementor-button-primary elementor-size-sm"><img src="' + data.icon_url + '" style="width:16px;height:16px;margin-right:6px;vertical-align:middle;"/><span>Generate</span></button>').css({marginRight:'6px'});
                
                // Cancel button with cross icon 
                var $cancel = $('<button type="button" class="spexo-ai-image-prompt-cancel elementor-button elementor-button-link elementor-size-sm" title="Cancel"><span class="spexo-ai-cancel-icon">✕</span></button>');
                
                // Assemble the prompt container with proper structure 
                $promptContainer.append(
                    $input,
                    $('<div class="spexo-ai-image-field-row"></div>').append(
                        '<label class="spexo-ai-image-field-label">Quality</label>',
                        $quality
                    ),
                    $('<div class="spexo-ai-image-field-row"></div>').append(
                        '<label class="spexo-ai-image-field-label">Size</label>',
                        $resolution
                    ),
                    $('<div class="spexo-ai-image-field-row"></div>').append(
                        '<label class="spexo-ai-image-field-label">Model</label>',
                        $modelSelect
                    ),
                    $('<div class="spexo-ai-image-field-row"></div>').append($bgCheckboxLabel),
                    !is_pro ? '<p class="spexo-ai-pro-notice" style="margin: 8px 0 12px 0; padding: 8px 12px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; color: #856404; line-height: 1.5;">⚠️ <strong>Model selection</strong> is only available in the <a href="' + (SpexoAiImageField.settings_url || '') + '" target="_blank" style="color: #0073aa; text-decoration: underline;">Pro version</a>.</p>' : '',
                    $('<div class="spexo-ai-image-field-row spexo-ai-image-field-row--buttons"></div>').append(
                        $submit,
                        $cancel
                    ),
                    $('<p class="spexo-ai-image-prompt-note">Complex prompts may take up to 2 minutes to process.</p>')
                );
                $outer.append($promptContainer);
                // Mark the Generate Image button as active (container open) - 
                $btn.addClass('is-active');
                // Focus on input
                $input.focus();
                // Handle submit
                $submit.on('click', function() {
                    var prompt = $input.val().trim();
                    if (!prompt) {
                        $input.css('border-color','red'); 
                        return;
                    }
                    // Disable submit button and show processing state
                    $submit.prop('disabled', true).addClass('is-processing').find('span').text('Generating...');
                    
                    // Add pulsing animation to preview 
                    var $preview = $ctrl.find('.elementor-control-media__preview');
                    if ($preview.length) {
                        $preview.addClass('spexo-ai-field-pulsing');
                    }
                    
                    // Also animate the media wrapper area
                    var $mediaArea = $ctrl.find('.elementor-control-media__area');
                    if ($mediaArea.length) {
                        $mediaArea.addClass('spexo-ai-image-generating');
                    }
                    
                    // Animate the tools section too
                    var $mediaTools = $ctrl.find('.elementor-control-media__tools');
                    if ($mediaTools.length) {
                        $mediaTools.addClass('spexo-ai-image-generating');
                    }
                    
                    // Make the Generate Image button active
                    $btn.addClass('is-active');
                    // Prepare request data
                    // Force DALL-E 3 model if Pro license is not active
                    var selectedModel = is_pro ? $modelSelect.val() : 'dall-e-3';
                    var requestData = {
                        action: 'spexo_ai_generate_image',
                        nonce: SpexoAiImageField.generate_nonce,
                        prompt: prompt,
                        quality: $quality.val(),
                        size: $resolution.val(),
                        model: selectedModel
                    };
                    // Add background parameter for GPT Image 1 (only if Pro and GPT Image 1 selected)
                    if (is_pro && selectedModel === 'gpt-image-1' && $bgCheckboxLabel.find('input').is(':checked')) {
                        requestData.background = 'transparent';
                    }
                    // Make the request
                    $.post(SpexoAiImageField.ajax_url, requestData, function(response) {
                        if (response.success && response.data.attachment_id) {
                            var attachmentID = response.data.attachment_id;
                            var imageURL = response.data.url;
                            
                            // Get the current element being edited
                            var currentElement = elementor.getPanelView().getCurrentPageView().getOption('editedElementView');
                            if (currentElement) {
                                // Get the control name from the hidden input
                                var controlName = $ctrl.find('input[type="hidden"][data-setting]').data('setting');
                                if (controlName) {
                                    // Create the image data object
                                    var imageData = {
                                        id: attachmentID,
                                        url: imageURL
                                    };
                                    
                                    // Update the element's settings
                                    var settings = {};
                                    settings[controlName] = imageData;
                                    
                                    // Use $e.run to properly update the element
                                    $e.run('document/elements/settings', {
                                        container: currentElement.getContainer(),
                                        settings: settings
                                    });
                                    
                                    // Update the control preview
                                    if ($preview.length) {
                                        $preview.css('background-image', 'url(' + imageURL + ')');
                                    }
                                }
                            }
                            
                            // Re-enable the Generate Image button after successful generation
                            $btn.prop('disabled', false);
                            $btn.css('opacity', 1);
                        } else {
                            alert('Error generating image. ' + (response.data && response.data.message ? response.data.message : 'Unknown error occurred'));
                            // Re-enable the Generate Image button on error
                            $btn.prop('disabled', false);
                            $btn.css('opacity', 1);
                        }
                    }).fail(function(response) {
                        var errorMessage = 'Unknown error occurred';
                        if (response.responseJSON && response.responseJSON.data && response.responseJSON.data.message) {
                            errorMessage = response.responseJSON.data.message;
                        } else if (response.responseText) {
                            try {
                                var parsed = JSON.parse(response.responseText);
                                if (parsed.data && parsed.data.message) {
                                    errorMessage = parsed.data.message;
                                }
                            } catch(e) {
                                // Keep default message
                            }
                        }
                        alert('Error generating image. ' + errorMessage);
                        // Re-enable the Generate Image button on failure
                        $btn.prop('disabled', false);
                        $btn.css('opacity', 1);
                    }).always(function() {
                        // Clean up animations and UI
                        $submit.prop('disabled', false).removeClass('is-processing').find('span').text('Generate');
                        
                        // Remove all animation classes
                        if (typeof $preview !== 'undefined' && $preview.length) {
                            $preview.removeClass('spexo-ai-field-pulsing');
                        }
                        if (typeof $mediaArea !== 'undefined' && $mediaArea.length) {
                            $mediaArea.removeClass('spexo-ai-image-generating');
                        }
                        if (typeof $mediaTools !== 'undefined' && $mediaTools.length) {
                            $mediaTools.removeClass('spexo-ai-image-generating');
                        }
                        
                        // Remove active state on complete
                        $btn.removeClass('is-active');
                        
                        // Ensure the Generate Image button is always re-enabled
                        $btn.prop('disabled', false);
                        $btn.css('opacity', 1);
                        
                        // Remove prompt container after a short delay to allow user to see the result
                        setTimeout(function() {
                            $promptContainer.remove();
                        }, 500);
                    });
                });

                $cancel.on('click', function() {
                    // Remove active state when closing container
                    $btn.removeClass('is-active');
                    $promptContainer.remove();
                    $btn.prop('disabled', false);
                    $btn.css('opacity', 1);
                });
            });
        });
    }

    // Global observer: watch for any media control fields added anywhere
    var __spexoGlobalObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            Array.prototype.forEach.call(mutation.addedNodes, function(node) {
                if (node.nodeType !== 1) {
                    return;
                }
                var $node = $(node);
                // Check for media field or containing media field
                var $mediaField = $node.is('.elementor-control-field.elementor-control-media') ? $node : $node.find('.elementor-control-field.elementor-control-media');
                if (!$mediaField.length) {
                    return;
                }
                // Find the parent control wrapper and its panel
                var $ctrl = $mediaField.closest('.elementor-control.elementor-control-type-media, .elementor-control.elementor-control-type-image, .elementor-control.elementor-control-type-gallery');
                if (!$ctrl.length) {
                    return;
                }
                var $panel = $ctrl.closest('.elementor-panel');
                if ($panel.length) {
                    injectImageControls($panel);
                }
            });
        });
    });
    __spexoGlobalObserver.observe(document.body, { childList: true, subtree: true });

    // Wait for Elementor to be ready
    function waitForElementor() {
        if (typeof elementor !== 'undefined' && elementor.hooks) {
            // On widget panel open, inject image controls and watch for dynamically added controls
            elementor.hooks.addAction('panel/open_editor/widget', function(panel) {
        var $panelEl = panel.$el;
        // Initial injection after panel renders
        setTimeout(function() {
            injectImageControls($panelEl);
        }, 250);
        // Setup a MutationObserver to catch lazy-loaded controls
        var root = $panelEl[0];
        if (!root.__spexoAiObserver) {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    Array.prototype.forEach.call(mutation.addedNodes, function(node) {
                        if (node.nodeType === 1 && (node.matches('.elementor-control.elementor-control-type-media, .elementor-control.elementor-control-type-image, .elementor-control.elementor-control-type-gallery') ||
                            $(node).find('.elementor-control.elementor-control-type-media, .elementor-control.elementor-control-type-image, .elementor-control.elementor-control-type-gallery').length)) {
                            injectImageControls($panelEl);
                        }
                    });
                });
            });
            observer.observe(root, { childList: true, subtree: true });
            root.__spexoAiObserver = observer;
        }
        // Extra delayed injections to catch any late-rendered controls
        setTimeout(function() { injectImageControls($panelEl); }, 800);
        setTimeout(function() { injectImageControls($panelEl); }, 1500);
    });

    // Also monitor section changes (e.g., switching tabs)
    elementor.channels.editor.on('section:activated', function(sectionName, editor) {
        var panelView = editor.getOption('editedElementView').getContainer().panel;
        if (panelView && panelView.$el) {
            setTimeout(function() {
                injectImageControls(panelView.$el);
            }, 150);
        }
    });

            // Re-inject image controls whenever a control view is opened
            elementor.hooks.addAction('panel/open_editor/control', function(controlView) {
                var panelEl = controlView.$el.closest('.elementor-panel');
                if (panelEl.length) {
                    injectImageControls(panelEl);
                }
            });
        } else {
            // Retry after a short delay
            setTimeout(waitForElementor, 100);
        }
    }
    
    waitForElementor();

})(jQuery, window.elementor);