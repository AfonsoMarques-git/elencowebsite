(function($, elementor){
    'use strict';

    
    // Debug check for SpexoAiTextField
    
    // Check if settings exist
    if (!window.SpexoAiTextField || typeof window.SpexoAiTextField !== 'object') {
        window.SpexoAiTextField = window.SpexoAiTextField || {
            ajax_url: ajaxurl || '',
            generate_action: 'spexo_ai_generate_text',
            generate_nonce: '',
            change_action: 'spexo_ai_change_text',
            change_nonce: '',
            settings_url: ''
        };
    }
    
    // Track active observers to avoid duplicates
    var activeObservers = [];

    // Track token usage
    var tokenUsageData = {
        initialized: false,
        dailyUsed: 0,
        dailyLimit: 0,
        limitReached: false,
        apiKeyValid: false
    };
    
    // Function to check if the token limit has been reached
    function checkTokenLimit(callback) {
        if (tokenUsageData.initialized) {
            if (callback) {
                callback({
                    limitReached: tokenUsageData.limitReached,
                    apiKeyValid: Boolean(tokenUsageData.apiKeyValid),
                });
            }
            // Return true if either the token limit is reached or the API key is invalid
            return tokenUsageData.limitReached || !tokenUsageData.apiKeyValid;
        }
        
        // Fetch current token usage data
        $.post(SpexoAiTextField.ajax_url, {
                action: 'spexo_ai_text_check_limits',
                nonce: SpexoAiTextField.generate_nonce
        }, function(response) {
            if (response.success && response.data) {
                tokenUsageData.initialized = true;
                tokenUsageData.dailyUsed = parseInt(response.data.daily_used || 0);
                tokenUsageData.dailyLimit = parseInt(response.data.daily_limit || 0);
                tokenUsageData.limitReached = response.data.limit_reached === true;
                tokenUsageData.apiKeyValid = response.data.api_key_valid === true;


                if (callback) {
                callback({
                        limitReached: tokenUsageData.limitReached,
                        apiKeyValid: tokenUsageData.apiKeyValid,
                    });
                }
            } else {
                // If there's an error, assume both token limit and API key are OK
                if (callback) {
                callback({
                        limitReached: false,
                        apiKeyValid: true,
                    });
                }
            }
        }).fail(function() {
            // On failure, assume both token limit and API key are OK
            if (callback) {
                callback({
                    limitReached: false,
                    apiKeyValid: true,
                });
            }
        });
    }

    // Update token usage data after API calls
    function updateTokenUsage(usageData) {
        if (usageData && typeof usageData === 'object') {
            tokenUsageData.initialized = true;
            tokenUsageData.dailyUsed = parseInt(usageData.daily_used || 0);
            tokenUsageData.dailyLimit = parseInt(usageData.daily_limit || 0);
            tokenUsageData.limitReached = 
                tokenUsageData.dailyLimit > 0 && tokenUsageData.dailyUsed >= tokenUsageData.dailyLimit;
                
        }
    }
    
    // Add CSS for animations directly to the document
    function injectAnimationStyles() {
        if ($('#spexo-addons-ai-animations').length === 0) {
            const animationStyles = `
                <style id="spexo-addons-ai-animations">
                    @keyframes spexoAddonsPulse {
                        0% { box-shadow: 0 0 0 0 rgba(91,3,255,0.4), inset 0 0 0 1px rgba(91,3,255,0.4); }
                        50% { box-shadow: 0 0 0 5px rgba(91,3,255,0.2), inset 0 0 0 1px rgba(91,3,255,0.6); }
                        100% { box-shadow: 0 0 0 0 rgba(91,3,255,0.1), inset 0 0 0 1px rgba(91,3,255,0.4); }
                    }
                    @keyframes spexoAddonsShine {
                        0% { background-position: -100px; }
                        40%, 100% { background-position: 140px; }
                    }
                    @keyframes spexoAddonsRotatePulse {
                        0% { transform: scale(1) rotate(0deg); filter: brightness(1); }
                        50% { transform: scale(1.15) rotate(180deg); filter: brightness(1.2) drop-shadow(0 0 3px rgba(91,3,255,0.7)); }
                        100% { transform: scale(1) rotate(360deg); filter: brightness(1); }
                    }
                    @keyframes spexoAddonsGlow {
                        0% { filter: brightness(1) drop-shadow(0 0 1px rgba(91,3,255,0.5)); }
                        50% { filter: brightness(1.3) drop-shadow(0 0 3px rgba(91,3,255,0.8)); }
                        100% { filter: brightness(1) drop-shadow(0 0 1px rgba(91,3,255,0.5)); }
                    }
                    .spexo-addons-field-pulsing {
                        animation: spexoAddonsPulse 1.5s infinite cubic-bezier(0.66, 0, 0, 1) !important;
                        border-color: #6A1B9A !important;
                    }
                    .spexo-addons-field-shine {
                        background-image: linear-gradient(90deg, 
                            rgba(106,27,154,0) 0%, 
                            rgba(106,27,154,0.2) 25%, 
                            rgba(233,30,99,0.3) 50%, 
                            rgba(106,27,154,0.2) 75%, 
                            rgba(106,27,154,0) 100%);
                        background-position: -100px;
                        background-size: 140px 100%;
                        background-repeat: no-repeat;
                        animation: spexoAddonsShine 2s infinite linear;
                    }
                    .spexo-addons-wysiwyg-active {
                        outline: 2px solid #5B03FF !important;
                        outline-offset: -2px;
                        transition: all 0.3s ease;
                    }
                    .spexo-addons-ai-buttons-wrapper {
                        display: inline-flex;
                        align-items: center;
                        gap: 12px;
                        position: relative;
                        z-index: 5;
                        transition: all 0.3s ease;
                        margin-top: 8px;
                        width: 100%;
                    }
                    .spexo-addons-ai-buttons-wrapper.is-processing .ai-generate-btn,
                    .spexo-addons-ai-buttons-wrapper.is-processing .ai-change-btn {
                        background: linear-gradient(135deg, #7B1FA2, #D81B60) !important;
                        opacity: 0.8;
                        cursor: default;
                        box-shadow: 0 2px 8px rgba(106, 27, 154, 0.3) !important;
                        pointer-events: none;
                    }
                    .spexo-addons-ai-buttons-wrapper.is-processing img {
                        filter: grayscale(100%);
                    }
                    .ai-prompt-container {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        margin: 8px 0;
                        position: relative;
                        z-index: 4;
                    }
                    .elementor-control-type-wysiwyg .ai-prompt-container {
                        margin-top: 6px;
                        margin-bottom: 10px;
                        width: 100%;
                    }
                    .ai-prompt-input {
                        flex: 1;
                        min-width: 0;
                        padding: 6px 10px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                        font-size: 12px;
                    }
                    .ai-prompt-input:focus {
                        border-color: #5B03FF;
                        box-shadow: 0 0 0 1px rgba(91,3,255,0.3);
                        outline: none;
                    }
                    .ai-prompt-examples {
                        font-size: 11px;
                        color: #6d7882;
                        margin-top: 4px;
                        line-height: 1.4;
                    }
                    .ai-prompt-examples strong {
                        color: #556068;
                        font-weight: 500;
                    }
                    .ai-prompt-cancel {
                        width: 24px;
                        height: 24px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: 1px solid #ccc;
                        background: #fff;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                        color: #555;
                    }
                    .ai-prompt-submit {
                        position: relative;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 32px;
                        height: 32px;
                        padding: 4px;
                        background: linear-gradient(135deg, #7B1FA2, #D81B60) !important;
                        border: unset;
                        border-radius: 4px;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }
                    .ai-prompt-submit.is-processing {
                        background: linear-gradient(135deg, #6A1B9A, #E91E63) !important;
                        border-color: transparent !important;
                    }
                    .ai-prompt-submit.is-processing img {
                        animation: spexoAddonsRotatePulse 2s infinite ease-in-out;
                    }
                    .ai-prompt-submit.is-processing:after {
                        content: '';
                        position: absolute;
                        top: -2px;
                        left: -2px;
                        right: -2px;
                        bottom: -2px;
                        border-radius: 6px;
                        background: transparent;
                        animation: spexoAddonsPulse 1.5s infinite cubic-bezier(0.66, 0, 0, 1);
                        z-index: -1;
                    }
                </style>
            `;
            $('head').append(animationStyles);
        }
    }

    // Function to inject AI buttons
    function injectAiButtons($container) {

        // Inject animation styles first
        injectAnimationStyles();

        // Handle standard text/textarea controls
        $container.find('.elementor-control-type-text label.elementor-control-title, .elementor-control-type-textarea label.elementor-control-title').each(function(){
            var $label = $(this);
            var $ctrlWrap = $label.closest('.elementor-control');
            // Skip CSS ID, CSS Classes, and inline-label controls
            if (
                $ctrlWrap.hasClass('elementor-control-_element_id') ||
                $ctrlWrap.hasClass('elementor-control-_css_classes') ||
                $ctrlWrap.hasClass('elementor-label-inline')
            ) {
                return;
            }
            var $input = $ctrlWrap.find('input[type="text"], textarea');        

            // Check if we already have buttons wrapper after this label        
            if (!$input.length || $label.next('.spexo-addons-ai-buttons-wrapper').length) {
                return;
            }

            createAndAttachButtons($label, $input, $ctrlWrap, true);
        });

        // Handle WYSIWYG controls
        $container.find('.elementor-control-type-wysiwyg .elementor-control-input-wrapper').each(function(){
            var $inputWrapper = $(this);
            var $ctrlWrap = $inputWrapper.closest('.elementor-control');        
            var $textarea = $ctrlWrap.find('textarea.elementor-wp-editor'); // Find the actual textarea

            // Check if we already have buttons wrapper before this input wrapper
            if (!$textarea.length || $ctrlWrap.find('.spexo-addons-ai-buttons-wrapper').length) {
                return;
            }

            // For WYSIWYG, add buttons to the control wrapper before the input wrapper
            createAndAttachButtons($ctrlWrap, $textarea, $ctrlWrap, false);     
        });
    }

    // Function to create and attach AI buttons
    function createAndAttachButtons($attachTarget, $field, $ctrlWrap, attachAfterLabel) {
        var fieldName = $field.attr('name') || $field.attr('id') || ''; // Use ID for WYSIWYG if name is not present
        var isWysiwyg = $ctrlWrap.hasClass('elementor-control-type-wysiwyg');   

        // Create wrapper div for buttons
        var $buttonsWrapper = $('<div class="spexo-addons-ai-buttons-wrapper"></div>');

        // For WYSIWYG we need different placement
        if (isWysiwyg) {
            // Find the input wrapper which contains the editor
            var $inputWrapper = $ctrlWrap.find('.elementor-control-input-wrapper');
            if (!$inputWrapper.length) return;
        }

        // Create "Generate" button
        var $btnLabel = $('<button type="button" class="ai-generate-btn ai-generate-btn--futuristic" title="AI Generate"></button>').css({
            verticalAlign: 'middle',
            padding: '4px 8px',
            fontSize: '12px',
            cursor: 'pointer',
            background: 'linear-gradient(135deg, #6A1B9A, #E91E63)',
            border: 'none',
            borderRadius: '4px',
            color: '#ffffff',
            display: 'inline-flex',
            alignItems: 'center',
            // boxShadow: '0 0 6px rgba(225,203,255,0.7), 0 0 12px rgba(91,3,255,0.5)',
            boxShadow: 'none',
            transition: 'box-shadow 0.3s ease'
        });
        $btnLabel.append(
            $('<img>').attr('src', SpexoAiTextField.icon_url).css({ marginRight: '6px', width: '16px', height: '16px', verticalAlign: 'middle' }),
            $('<span>').addClass('ai-generate-btn__label').text('AI Generate')  
        );
        $btnLabel.hover(
            function() { $(this).css('boxShadow', '0 0 8px rgba(106,27,154,0.9), 0 0 16px rgba(233,30,99,0.7)'); },
            function() { $(this).css('boxShadow', 'none'); }
        );

        // Create "Change" button
        var $changeBtn = $btnLabel.clone();
        $changeBtn.removeClass('ai-generate-btn').addClass('ai-change-btn');    
        $changeBtn.find('span.ai-generate-btn__label').text('AI Change');       
        $changeBtn.attr('title', 'AI Change');
        $changeBtn.find('img').attr('src', SpexoAiTextField.rewrite_icon_url || SpexoAiTextField.plugin_url + '/inc/modules/ai/assets/images/ai-rewrite.svg');

        // Add hover effect to the change button - cloning doesn't preserve hover handlers
        $changeBtn.hover(
            function() { $(this).css('boxShadow', '0 0 8px rgba(106,27,154,0.9), 0 0 16px rgba(233,30,99,0.7)'); },
            function() { $(this).css('boxShadow', 'none'); }
        );

        // Add buttons to wrapper
        $buttonsWrapper.append($btnLabel).append($changeBtn);

        // Add wrapper to DOM with proper placement
        if (isWysiwyg) {
            // For WYSIWYG, insert before the input wrapper
            $inputWrapper.before($buttonsWrapper);
        } else if (attachAfterLabel) {
            // For regular text/textarea, after the label
            $attachTarget.after($buttonsWrapper);
        } else {
            // Fallback (shouldn't normally happen)
            $attachTarget.before($buttonsWrapper);
        }
        

        // Attach "Generate" button click handler
        $btnLabel.on('click', function(e){
            e.preventDefault();
            var $originalBtn = $(this);
            var $buttonsWrapper = $originalBtn.closest('.spexo-addons-ai-buttons-wrapper');

            // Hide the buttons wrapper while loading
            $buttonsWrapper.addClass('is-processing');

            // Check API key validity and token limit first
            checkTokenLimit(function(status) {
                if (!status.apiKeyValid) {
                    // API key missing or invalid
                    var $errorMessage = $('<div class="spexo-addons-ai-error-message"></div>').css({
                        background: '#e7f3fe',
                        color: '#084d7a',
                        padding: '10px 15px',
                        borderRadius: '4px',
                        border: '1px solid #b6e0fe',
                        marginTop: '8px',
                        marginBottom: '8px',
                        fontSize: '13px',
                        fontWeight: '500',
                        lineHeight: '1.4',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between'
                    });
                    var settingsUrl = window.SpexoAiTextField && window.SpexoAiTextField.settings_url
                        ? window.SpexoAiTextField.settings_url
                        : '/wp-admin/admin.php?page=spexo-ai-settings';   
                    $errorMessage.html(
                        '<span>OpenAI API key is missing or invalid. Please configure your API key in AI Settings.</span>' +
                        '<a href="' + settingsUrl + '" style="color:#0073aa;text-decoration:underline;white-space:nowrap;margin-left:10px;" target="_blank">Settings</a>'
                    );
                    $errorMessage.insertAfter($buttonsWrapper).hide().fadeIn(200);
                    setTimeout(function() { $errorMessage.fadeOut(200, function() { $(this).remove(); }); }, 5000);
                    $buttonsWrapper.removeClass('is-processing');
                    return;
                }
                if (status.limitReached) {
                    // Show error if daily token limit reached
                    var $errorMessage = $('<div class="spexo-addons-ai-error-message"></div>').css({
                        background: '#ffecec',
                        color: '#d63638',
                        padding: '10px 15px',
                        borderRadius: '4px',
                        border: '1px solid #d63638',
                        marginTop: '8px',
                        marginBottom: '8px',
                        fontSize: '13px',
                        fontWeight: '500',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between'
                    });
                    var settingsUrl = window.SpexoAiTextField && window.SpexoAiTextField.settings_url
                        ? window.SpexoAiTextField.settings_url
                        : '/wp-admin/admin.php?page=spexo-ai-settings';   
                    $errorMessage.html(
                        '<span>Daily token limit reached. Please try again tomorrow or increase the limit in AI Settings.</span>' +
                        '<a href="' + settingsUrl + '" style="color:#0073aa;text-decoration:underline;white-space:nowrap;margin-left:10px;" target="_blank">Settings</a>'
                    );
                    $errorMessage.insertAfter($buttonsWrapper).hide().fadeIn(200);
                    setTimeout(function() { $errorMessage.fadeOut(200, function() { $(this).remove(); }); }, 5000);
                    $buttonsWrapper.removeClass('is-processing');
                    return;
                }
                // Continue with regular flow if preconditions met
                var $promptContainer = $('<div class="ai-prompt-container"></div>');
                var $promptInput = $('<input type="text" class="ai-prompt-input" placeholder="Enter your prompt..."/>');

                // Create examples text based on field type
                var $examplesText;
                if (isWysiwyg) {
                    $examplesText = $('<div class="ai-prompt-examples">Examples: <strong>"Write about product benefits"</strong>, <strong>"Create a FAQ section with 3 questions"</strong>, <strong>"Write a product description for beginners"</strong></div>');
                } else {
                    $examplesText = $('<div class="ai-prompt-examples">Examples: <strong>"Create a compelling headline"</strong>, <strong>"Write a call to action"</strong>, <strong>"Generate a short bio"</strong></div>');
                }

                // Create the submit button - use our CSS class for styling instead of inline styles
                var $submitBtn = $('<button type="button" class="ai-prompt-submit" title="Submit"></button>');
                // Only add the image, no inline styling
                $submitBtn.append($('<img>').attr('src', SpexoAiTextField.icon_url).css({ width: '16px', height: '16px' }));
                var $cancelBtn = $('<button type="button" class="ai-prompt-cancel" title="Cancel">✕</button>');

                // For WYSIWYG, place the prompt container after the buttons wrapper but before the editor
                if (isWysiwyg) {
                    // Find the input wrapper which contains the editor
                    var $inputWrapper = $ctrlWrap.find('.elementor-control-input-wrapper');
                    if (!$inputWrapper.length) return;

                    // Append prompt container elements and place before the editor input wrapper
                    $promptContainer.append($promptInput, $submitBtn, $cancelBtn);
                    $inputWrapper.before($promptContainer);
                    // Add examples text after the prompt container
                    $promptContainer.after($examplesText);
                    $promptContainer.hide().fadeIn(200);
                    $examplesText.hide().fadeIn(200);
                } else {
                    // For regular fields, place after the buttons wrapper      
                    $promptContainer.append($promptInput, $submitBtn, $cancelBtn)
                        .insertAfter($buttonsWrapper).hide().fadeIn(200);       
                    // Add examples text after the prompt container
                    $promptContainer.after($examplesText);
                    $examplesText.hide().fadeIn(200);
                }

                $promptInput.focus();
                $promptInput.on('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); $submitBtn.trigger('click'); } });

                $cancelBtn.on('click', function(){
                    $promptContainer.fadeOut(200, function(){
                        $(this).remove();
                        $examplesText.remove();
                        $buttonsWrapper.removeClass('is-processing');
                    });
                });

                $submitBtn.on('click', function(){
                    var userPrompt = $promptInput.val().trim();
                    if (!userPrompt) { $promptInput.css('borderColor', 'red'); return; }
                    $promptInput.prop('disabled', true);
                    // Instead of replacing with spinner, add processing class  
                    $submitBtn.prop('disabled', true).addClass('is-processing');
                    
                    // Add animation to the target field
                    if (isWysiwyg) {
                        // For WYSIWYG we need to target both the iframe and the wrapper
                        var $editorWrap = $field.closest('.wp-editor-container');
                        var $iframe = $editorWrap.find('iframe');

                        $editorWrap.addClass('spexo-addons-field-pulsing');      
                        if ($iframe.length) {
                            $iframe.addClass('spexo-addons-wysiwyg-active');     
                            // Also try to add a class to the iframe body       
                            try {
                                $($iframe[0].contentDocument.body).addClass('spexo-addons-field-shine');
                            } catch(e) {
                                console.error('Could not access iframe body', e);
                            }
                        }
                    } else {
                        // For regular text inputs and textareas
                        $field.addClass('spexo-addons-field-pulsing');
                        $field.addClass('spexo-addons-field-shine');
                    }

                    // Use consistent parameter names with the Change API
                    $.post( SpexoAiTextField.ajax_url, {
                            action: SpexoAiTextField.generate_action || 'spexo_ai_generate_text',
                            nonce: SpexoAiTextField.generate_nonce || SpexoAiTextField.nonce,
                            field_name: fieldName,
                            prompt: userPrompt, // Changed from 'value' to 'prompt' for consistency
                            editor_type: isWysiwyg ? 'wysiwyg' : 'text' // Explicitly tell backend what type of field this is
                        }, function(response){
                            if (response.success && response.data.text) {       
                                updateFieldValue($field, response.data.text, isWysiwyg, response);

                                // Update token usage data if available
                                if (response.data.usage) {
                                    updateTokenUsage(response.data.usage);      
                                }
                            } else if (response.data && response.data.message) {
                                alert(response.data.message);
                            }
                        }
                    ).always(function(){
                        // Remove animation classes
                        if (isWysiwyg) {
                            var $editorWrap = $field.closest('.wp-editor-container');
                            var $iframe = $editorWrap.find('iframe');
                            $editorWrap.removeClass('spexo-addons-field-pulsing');
                            if ($iframe.length) {
                                $iframe.removeClass('spexo-addons-wysiwyg-active');
                                try {
                                    $($iframe[0].contentDocument.body).removeClass('spexo-addons-field-shine');
                                } catch(e) {
                                    console.error('Could not access iframe body', e);
                                }
                            }
                        } else {
                            $field.removeClass('spexo-addons-field-pulsing spexo-addons-field-shine');
                        }

                        // Remove button processing state
                        $submitBtn.removeClass('is-processing');

                        // Complete and clean up the UI
                        $promptContainer.fadeOut(200, function(){
                            $(this).remove();
                            $examplesText.remove();
                            $buttonsWrapper.removeClass('is-processing');       
                        });
                    });
                });
            });
        });

        // Attach "Change" button click handler
        $changeBtn.on('click', function(e){
            e.preventDefault();
            var $originalBtn = $(this);
            var $buttonsWrapper = $originalBtn.closest('.spexo-addons-ai-buttons-wrapper');

            // Add processing class instead of hiding
            $buttonsWrapper.addClass('is-processing');

            // Check API key validity and token limit first
            checkTokenLimit(function(status) {
                if (!status.apiKeyValid) {
                    // API key missing or invalid
                    var $errorMessage = $('<div class="spexo-addons-ai-error-message"></div>').css({
                        background: '#ffecec',
                        color: '#d63638',
                        padding: '10px 15px',
                        borderRadius: '4px',
                        border: '1px solid #d63638',
                        marginTop: '8px',
                        marginBottom: '8px',
                        fontSize: '13px',
                        fontWeight: '500',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between'
                    });
                    var settingsUrl = window.SpexoAiTextField && window.SpexoAiTextField.settings_url
                        ? window.SpexoAiTextField.settings_url
                        : '/wp-admin/admin.php?page=spexo-ai-settings';   
                    $errorMessage.html(
                        '<span>OpenAI API key is missing or invalid. Please configure your API key in AI Settings.</span>' +
                        '<a href="' + settingsUrl + '" style="color:#0073aa;text-decoration:underline;white-space:nowrap;margin-left:10px;" target="_blank">Settings</a>'
                    );
                    $errorMessage.insertAfter($buttonsWrapper).hide().fadeIn(200);
                    setTimeout(function() { $errorMessage.fadeOut(200, function() { $(this).remove(); }); }, 5000);
                    $buttonsWrapper.removeClass('is-processing');
                    return;
                }
                if (status.limitReached) {
                    // Show error if daily token limit reached
                    var $errorMessage = $('<div class="spexo-addons-ai-error-message"></div>').css({
                        background: '#ffecec',
                        color: '#d63638',
                        padding: '10px 15px',
                        borderRadius: '4px',
                        border: '1px solid #d63638',
                        marginTop: '8px',
                        marginBottom: '8px',
                        fontSize: '13px',
                        fontWeight: '500',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between'
                    });
                    var settingsUrl = window.SpexoAiTextField && window.SpexoAiTextField.settings_url
                        ? window.SpexoAiTextField.settings_url
                        : '/wp-admin/admin.php?page=spexo-ai-settings';   
                    $errorMessage.html(
                        '<span>Daily token limit reached. Please try again tomorrow or increase the limit in AI Settings.</span>' +
                        '<a href="' + settingsUrl + '" style="color:#0073aa;text-decoration:underline;white-space:nowrap;margin-left:10px;" target="_blank">Settings</a>'
                    );
                    $errorMessage.insertAfter($buttonsWrapper).hide().fadeIn(200);
                    setTimeout(function() { $errorMessage.fadeOut(200, function() { $(this).remove(); }); }, 5000);
                    $buttonsWrapper.removeClass('is-processing');
                    return;
                }
                // Continue with regular flow if preconditions met
                var originalText = getFieldValue($field, isWysiwyg);

                var $promptContainer = $('<div class="ai-prompt-container"></div>');
                var $promptInput = $('<input type="text" class="ai-prompt-input" placeholder="Enter change prompt..."/>');

                // Create examples text based on field type
                var $examplesText;
                if (isWysiwyg) {
                    $examplesText = $('<div class="ai-prompt-examples">Examples: <strong>"Add 2 paragraphs about benefits"</strong>, <strong>"Add 3 paragraphs about features"</strong>, <strong>"Make content more professional"</strong></div>');
                } else {
                    $examplesText = $('<div class="ai-prompt-examples">Examples: <strong>"Make it more persuasive"</strong>, <strong>"Make it shorter and direct"</strong>, <strong>"Change tone to friendly"</strong></div>');
                }

                var $submitBtn = $('<button type="button" class="ai-prompt-submit" title="Apply"></button>');
                $submitBtn.append($('<img>').attr('src', SpexoAiTextField.icon_url).css({ width: '16px', height: '16px' }));
                var $cancelBtn = $('<button type="button" class="ai-prompt-cancel" title="Cancel">✕</button>');

                // For WYSIWYG, place the prompt container after the buttons wrapper but before the editor
                if (isWysiwyg) {
                    // Find the input wrapper which contains the editor
                    var $inputWrapper = $ctrlWrap.find('.elementor-control-input-wrapper');
                    if (!$inputWrapper.length) return;

                    // Append prompt container elements and place before the editor input wrapper
                    $promptContainer.append($promptInput, $submitBtn, $cancelBtn);
                    $inputWrapper.before($promptContainer);
                    // Add examples text after the prompt container
                    $promptContainer.after($examplesText);
                    $promptContainer.hide().fadeIn(200);
                    $examplesText.hide().fadeIn(200);
                } else {
                    // For regular fields, place after the buttons wrapper      
                    $promptContainer.append($promptInput, $submitBtn, $cancelBtn)
                        .insertAfter($buttonsWrapper).hide().fadeIn(200);       
                    // Add examples text after the prompt container
                    $promptContainer.after($examplesText);
                    $examplesText.hide().fadeIn(200);
                }

                $promptInput.focus().on('keydown', function(ev){ if(ev.key==='Enter'){ ev.preventDefault(); $submitBtn.click(); }});

                $cancelBtn.on('click', function(){
                    $promptContainer.fadeOut(200, function(){
                        $(this).remove();
                        $examplesText.remove();
                        $buttonsWrapper.removeClass('is-processing');
                    });
                });

                $submitBtn.on('click', function(){
                    var promptVal = $promptInput.val().trim();
                    if(!promptVal){ $promptInput.css('borderColor','red'); return; }
                    $promptInput.prop('disabled',true);
                    $submitBtn.prop('disabled',true).addClass('is-processing'); 

                    // Add animation to the target field
                    if (isWysiwyg) {
                        // For WYSIWYG we need to target both the iframe and the wrapper
                        var $editorWrap = $field.closest('.wp-editor-container');
                        var $iframe = $editorWrap.find('iframe');

                        $editorWrap.addClass('spexo-addons-field-pulsing');      
                        if ($iframe.length) {
                            $iframe.addClass('spexo-addons-wysiwyg-active');
                            // Also try to add a class to the iframe body       
                            try {
                                $($iframe[0].contentDocument.body).addClass('spexo-addons-field-shine');
                            } catch(e) {
                                console.error('Could not access iframe body', e);
                            }
                        }
                    } else {
                        // For regular text inputs and textareas
                        $field.addClass('spexo-addons-field-pulsing');
                        $field.addClass('spexo-addons-field-shine');
                    }

                    $.post( SpexoAiTextField.ajax_url, {
                            action: SpexoAiTextField.change_action,
                            nonce:  SpexoAiTextField.change_nonce,
                            field_name: fieldName,
                            prompt: promptVal,
                            original: originalText,
                            editor_type: isWysiwyg ? 'wysiwyg' : 'text', // Explicitly tell backend what type of field this is
                            instruction_context: 'Modify the original text based on the user instructions. If asked to add content, keep the original and expand it. If asked to change style, maintain the same information but change the tone. Return the complete modified text.' // Add clear context for the AI
                        }, function(resp){
                            if(resp.success && resp.data.text){
                                updateFieldValue($field, resp.data.text, isWysiwyg, resp);

                                // Update token usage data if available
                                if (resp.data.usage) {
                                    updateTokenUsage(resp.data.usage);
                                }
                            } else if(resp.data && resp.data.message){
                                alert(resp.data.message);
                            }
                        }
                    ).always(function(){
                        // Remove animation classes
                        if (isWysiwyg) {
                            var $editorWrap = $field.closest('.wp-editor-container');
                            var $iframe = $editorWrap.find('iframe');
                            $editorWrap.removeClass('spexo-addons-field-pulsing');
                            if ($iframe.length) {
                                $iframe.removeClass('spexo-addons-wysiwyg-active');
                                try {
                                    $($iframe[0].contentDocument.body).removeClass('spexo-addons-field-shine');
                                } catch(e) {
                                    console.error('Could not access iframe body', e);
                                }
                            }
                        } else {
                            $field.removeClass('spexo-addons-field-pulsing spexo-addons-field-shine');
                        }

                        // Remove button processing state
                        $submitBtn.removeClass('is-processing');

                        // Complete and clean up the UI
                        $promptContainer.fadeOut(200, function(){
                            $(this).remove();
                            $examplesText.remove();
                            $buttonsWrapper.removeClass('is-processing');       
                        });
                    });
                });
            });
        });
    }

    // Function to update field value (handles WYSIWYG)
    function updateFieldValue($field, value, isWysiwyg, response) {
        // Check if we need to append instead of replace
        var appendMode = response && response.data && response.data.append_mode === true;
        var originalContent = appendMode ? (response.data.original || '') : ''; 
        

        if (isWysiwyg) {
            var editorId = $field.attr('id');

            // Log for debugging

            // Check if we have a valid editor ID
            if (!editorId) {
                console.error('Spexo Addons: No editor ID found for WYSIWYG field');
                if (appendMode) {
                    // Append the new content to the original
                    $field.val(originalContent + '\n\n' + value);
                } else {
                    $field.val(value);
                }
                $field.trigger('input');
                return;
            }

            // Additional client-side cleanup for WYSIWYG
            if (value) {
                // Remove any code fence markers that might have been returned from API
                value = value.replace(/^```(?:html|HTML)?\s*/g, '');
                value = value.replace(/```\s*$/g, '');

                // Ensure proper paragraph formatting for WYSIWYG
                // Only add paragraph tags if they're not already present       
                if (!value.includes('<p>') && !value.includes('<div>')) {       
                    // First, normalize all types of line breaks
                    value = value.replace(/\r\n/g, '\n').replace(/\r/g, '\n');  

                    // Look for patterns that might indicate paragraphs
                    var paragraphDelimiter = /\n\s*\n/;

                    // If no double line breaks, look for single line breaks that might be paragraph breaks
                    if (!paragraphDelimiter.test(value) && value.includes('\n')) {
                        // Split by newlines and wrap each non-empty line in paragraph tags
                        value = value.split('\n')
                            .filter(function(para) { return para.trim().length > 0; })
                            .map(function(para) { return '<p>' + para.trim() + '</p>'; })
                            .join('');
                    } else {
                        // Split by double newlines and wrap in paragraph tags  
                        value = value.split(paragraphDelimiter).map(function(paragraph) {
                            // Handle single newlines within a paragraph as <br> tags
                            return '<p>' + paragraph.trim().replace(/\n/g, '<br>') + '</p>';
                        }).join('');
                    }
                    
                }
            }

            try {
                // Check if TinyMCE is available and the editor exists
                if (window.tinymce && tinymce.get(editorId)) {
                    var editor = tinymce.get(editorId);

                    if (!editor.isHidden()) { // Visual mode

                        if (appendMode) {
                            // Get current content and append the new content   
                            // Make sure we use the original from the server, not the current content
                            // which may have been modified by the user after the request was sent
                            // Add a proper separator between existing and new content for HTML
                            var existingContent = originalContent || '';        
                            var newContent = value || '';

                            // Only add paragraph separator if one doesn't already exist at the end of original content
                            if (existingContent && !existingContent.trim().endsWith('</p>')) {
                                existingContent = existingContent + '<p></p>';  
                            }

                            editor.setContent(existingContent + newContent);    
                        } else {
                            editor.setContent(value);
                        }

                        editor.save(); // Sync with textarea
                    } else { // Text mode

                        if (appendMode) {
                            // Append the new content to the original with proper HTML separation
                            var existingContent = originalContent || '';        
                            var newContent = value || '';

                            // For text mode, we should still preserve HTML structure
                            if (existingContent && !existingContent.trim().endsWith('</p>') &&
                                (existingContent.includes('<p>') || newContent.includes('<p>'))) {
                                existingContent = existingContent + '<p></p>';  
                            } else if (existingContent) {
                                // Simple text mode, add double line break      
                                existingContent = existingContent + '\n\n';     
                            }

                            $field.val(existingContent + newContent);
                        } else {
                            $field.val(value);
                        }
                    }
                } else {
                    // TinyMCE not available or editor not initialized

                    if (appendMode) {
                        // Append the new content to the original
                        $field.val(originalContent + '\n\n' + value);
                    } else {
                        $field.val(value);
                    }
                }
            } catch (e) {
                console.error('Spexo Addons: Error updating WYSIWYG content', e);
                // Fallback - set the textarea value directly
                if (appendMode) {
                    // Maintain HTML structure in fallback case
                    var existingContent = originalContent || '';
                    var newContent = value || '';

                    // Add paragraph separator if needed
                    if (existingContent && !existingContent.trim().endsWith('</p>') &&
                        (existingContent.includes('<p>') || newContent.includes('<p>'))) {
                        existingContent = existingContent + '<p></p>';
                    } else if (existingContent) {
                        existingContent = existingContent + '\n\n';
                    }

                    $field.val(existingContent + newContent);
                } else {
                    $field.val(value);
                }
            }
        } else {
            // Standard text field
            if (appendMode) {
                // Append the new content to the original for text fields       
                $field.val(originalContent + '\n\n' + value);
            } else {
                $field.val(value);
            }
        }

        // Trigger change events to ensure Elementor detects the change
        $field.trigger('input');
        $field.trigger('change');

        // For WYSIWYG, also try to trigger a TinyMCE change event if available 
        if (isWysiwyg && window.tinymce && tinymce.get($field.attr('id'))) {    
            try {
                tinymce.get($field.attr('id')).fire('change');
            } catch (e) {
                console.error('Spexo Addons: Error triggering TinyMCE change event', e);
            }
        }
    }

    // Function to get field value (handles WYSIWYG)
    function getFieldValue($field, isWysiwyg) {
        if (isWysiwyg) {
            var editorId = $field.attr('id');
            if (window.tinymce && tinymce.get(editorId) && !tinymce.get(editorId).isHidden()) { // Visual mode
                 return tinymce.get(editorId).getContent();
            } else { // Text mode or editor not initialized
                return $field.val();
            }
        }
        return $field.val();
    }

    // Function to setup MutationObserver to detect controls changes 
    function setupControlsObserver(panel) {
        // Observe the entire panel for any control changes (e.g., section tabs)
        var $controlsContainer = panel.$el;


        activeObservers.forEach(function(observer) { observer.disconnect(); }); 
        activeObservers = [];

        var observer = new MutationObserver(function(mutations) {
            // Use a small delay to allow Elementor to finish rendering, especially for complex controls
            setTimeout(function() {
                injectAiButtons($controlsContainer);
            }, 50);
        });

        observer.observe($controlsContainer[0], { childList: true, subtree: true });
        activeObservers.push(observer);

        // Initial injection, with a delay
        setTimeout(function() {
            injectAiButtons($controlsContainer);
        }, 150);
    }

    // Wait for Elementor to be ready
    function waitForElementor() {
        if (typeof elementor !== 'undefined' && elementor.hooks) {
    // On widget panel open, setup the observer
    elementor.hooks.addAction('panel/open_editor/widget', function(panel) {     
        setTimeout(function() { setupControlsObserver(panel); }, 250); // Increased delay for initial setup
    });
        } else {
            // Retry after a short delay
            setTimeout(waitForElementor, 100);
        }
    }
    
    waitForElementor();

    // Also monitor section changes
    function waitForElementorChannels() {
        if (typeof elementor !== 'undefined' && elementor.channels && elementor.channels.editor) {
    elementor.channels.editor.on('section:activated', function(sectionName, editor) {
        var panel = editor.getOption('editedElementView').getContainer().panel; 
        if (panel && panel.$el) {
            // When a section is activated, reinitialize observer and injection
            setTimeout(function() {
                setupControlsObserver(panel);
            }, 150); // Delay for section rendering
        }
    });
        } else {
            // Retry after a short delay
            setTimeout(waitForElementorChannels, 100);
        }
    }
    
    waitForElementorChannels();

})(jQuery, window.elementor);