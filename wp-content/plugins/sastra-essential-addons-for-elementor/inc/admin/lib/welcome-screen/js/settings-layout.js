(function ($) {
    'use strict';

    function SpexoSettingsLayout() {
        this.$form = $('.spexo-settings-form');
        if (!this.$form.length) {
            return;
        }

        this.$navItems = $('.spexo-settings-nav-item');
        this.$panels = $('.spexo-settings-panel');
        this.$subLinks = $('.spexo-settings-sub-link');
        this.$title = $('[data-spexo-active-title]');
        this.$description = $('[data-spexo-active-description]');
        this.$saveNotice = $('.spexo-settings-alert');
        this.initialSnapshot = this.$form.serialize();
        this.lastActiveSubpanels = {};
        this.defaultValues = {}; // Store default values for each field

        // Determine active panel: check URL hash first, then default (localStorage disabled)
        var hashPanel = this.getActivePanelFromHash();
        var defaultPanel = this.$panels.filter('.is-active').data('panel') || this.$navItems.first().data('panel');
        this.activePanel = hashPanel || defaultPanel;

        this.storeDefaultValues();
        this.ensureDefaultSubpanels();
        this.restoreActiveSection();
        this.bindEvents();
        this.bindFieldChange();
        this.initNavHover();
        this.highlightInitialSubLink();
        this.checkDirtyState();
        this.checkResetSuccess();
    }

    SpexoSettingsLayout.prototype.bindEvents = function () {
        var self = this;

        this.$navItems.on('click', '.spexo-settings-nav-toggle', function () {
            var $item = $(this).closest('.spexo-settings-nav-item');
            var targetPanel = $item.data('panel');
            self.activatePanel(targetPanel, $item);
        });

        this.$subLinks.on('click', function (event) {
            event.preventDefault();
            var $btn = $(this);
            var targetPanel = $btn.closest('.spexo-settings-nav-item').data('panel');
            var anchor = $btn.data('target');
            self.activatePanel(targetPanel, $btn.closest('.spexo-settings-nav-item'), anchor, false);
            // Update hash to include subpanel for deep linking
            if (anchor) {
                self.updateSubpanelHash(targetPanel, anchor);
            }
        });

        $('.spexo-reset-section').on('click', function (event) {
            event.preventDefault();
            self.resetCurrentSection();
        });

        // Reset All button commented out - not working properly
        /*
        $('.spexo-reset-all').on('click', function (event) {
            event.preventDefault();
            self.resetAllSections();
        });
        */

        this.$form.on('submit', function () {
            self.initialSnapshot = self.$form.serialize();
            self.checkDirtyState();
        });

    };

    SpexoSettingsLayout.prototype.bindFieldChange = function () {
        var self = this;
        this.$form.on('input change', 'input:not([type="button"]):not([type="submit"]):not([type="reset"]), select, textarea', function () {
            self.checkDirtyState();
        });
    };

    SpexoSettingsLayout.prototype.checkDirtyState = function () {
        var isDirty = this.$form.serialize() !== this.initialSnapshot;
        this.$form.toggleClass('spexo-settings-form--dirty', isDirty);
        if (this.$saveNotice && this.$saveNotice.length) {
            this.$saveNotice.toggleClass('is-visible', isDirty);
        }
        return isDirty;
    };

    SpexoSettingsLayout.prototype.activatePanel = function (panelId, $navItem, anchorId, animate) {
        if (!panelId) {
            return;
        }

        // Default animate to true if not specified
        animate = animate !== false;

        var $panel = this.getPanelById(panelId);
        this.activePanel = panelId;
        this.$panels.removeClass('is-active')
            .filter('[data-panel="' + panelId + '"]').addClass('is-active');

        // Update URL hash (localStorage persistence disabled)
        this.updateActivePanelHash(panelId);
        // this.saveActivePanelToStorage(panelId); // Disabled - always open first tab on page load

        if ($navItem && $navItem.length) {
            var self = this;
            
            if (animate) {
                // Close ALL expanded items first (including previously active ones) with animation
                this.$navItems.filter('.is-expanded').each(function () {
                    self.collapseSubmenu($(this));
                });
            } else {
                // Close ALL expanded items without animation
                this.$navItems.filter('.is-expanded').each(function () {
                    var $expandedItem = $(this);
                    $expandedItem.removeClass('is-expanded');
                    $expandedItem.children('.spexo-settings-nav-children').hide().css('display', 'none');
                });
            }
            
            this.$navItems.removeClass('is-active');
            $navItem.addClass('is-active');
            this.updateHeader($navItem);
            
            // Expand the new active item
            if ($navItem.hasClass('has-children')) {
                if (animate) {
                    this.expandSubmenu($navItem);
                } else {
                    // Expand without animation
                    $navItem.addClass('is-expanded');
                    $navItem.children('.spexo-settings-nav-children').show().css('display', 'flex');
                }
            }
        }

        // Check for subpanel in hash (e.g., #woocommerce-woo-page-config)
        var hashSubpanel = this.getSubpanelFromHash(panelId);
        // localStorage persistence disabled - use anchor, hash, or default
        var targetSubpanel = anchorId || hashSubpanel || this.lastActiveSubpanels[panelId] || this.getDefaultSubpanelId($panel);
        this.setActiveSubpanel(panelId, targetSubpanel, { scroll: !!anchorId || !!hashSubpanel });
    };

    SpexoSettingsLayout.prototype.updateHeader = function ($item) {
        if (!$item.length) {
            return;
        }

        this.$title.text($item.data('panel-title') || '');
        this.$description.text($item.data('panel-description') || '');
    };

    SpexoSettingsLayout.prototype.getPanelById = function (panelId) {
        return this.$panels.filter('[data-panel="' + panelId + '"]');
    };

    SpexoSettingsLayout.prototype.getDefaultSubpanelId = function ($panel) {
        if (!$panel || !$panel.length) {
            return null;
        }
        var $first = $panel.find('.spexo-subpanel').first();
        return $first.length ? ($first.attr('id') || $first.data('subpanel')) : null;
    };

    SpexoSettingsLayout.prototype.ensureDefaultSubpanels = function () {
        var self = this;
        this.$panels.each(function () {
            var $panel = $(this);
            var panelId = $panel.data('panel');
            var $active = $panel.find('.spexo-subpanel.is-active').first();
            if (!$active.length) {
                $active = $panel.find('.spexo-subpanel').first().addClass('is-active');
            }
            if ($active.length) {
                self.lastActiveSubpanels[panelId] = $active.attr('id') || $active.data('subpanel');
            }
        });
    };

    SpexoSettingsLayout.prototype.setActiveSubpanel = function (panelId, subpanelId, options) {
        var $panel = this.getPanelById(panelId);
        if (!$panel.length) {
            return;
        }

        var $sections = $panel.find('.spexo-subpanel');
        if (!$sections.length) {
            return;
        }

        var $target = subpanelId ? $sections.filter('#' + subpanelId + ', [data-subpanel="' + subpanelId + '"]') : $();
        if (!$target.length) {
            $target = $sections.first();
            subpanelId = $target.attr('id') || $target.data('subpanel');
        }

        // Define subpanel groups that should be shown together
        var subpanelGroups = {
            'ai-settings': {
                'ai-editor-tools': ['ai-editor-tools', 'ai-alt-text', 'ai-translation'],
                'ai-usage-quota': ['ai-usage-quota', 'ai-usage-stats']
            }
        };

        // Check if this subpanel belongs to a group
        var groupSubpanels = null;
        if (subpanelGroups[panelId] && subpanelGroups[panelId][subpanelId]) {
            groupSubpanels = subpanelGroups[panelId][subpanelId];
        }

        $sections.removeClass('is-active');
        
        if (groupSubpanels) {
            // Show all subpanels in the group
            groupSubpanels.forEach(function(groupId) {
                var $groupSubpanel = $sections.filter('#' + groupId + ', [data-subpanel="' + groupId + '"]');
                if ($groupSubpanel.length) {
                    $groupSubpanel.addClass('is-active');
                }
            });
        } else {
            // Show only the target subpanel
            $target.addClass('is-active');
        }

        this.lastActiveSubpanels[panelId] = subpanelId;
        this.highlightSubLink(panelId, subpanelId);

        // Save last active subpanel for this panel (localStorage persistence disabled)
        // try {
        //     var subpanelKey = 'spexo_settings_active_subpanel_' + panelId;
        //     localStorage.setItem(subpanelKey, subpanelId);
        // } catch (e) {
        //     // localStorage might not be available
        // }

        if (!options || options.scroll !== false) {
            this.scrollToElement($target);
        }
    };

    SpexoSettingsLayout.prototype.highlightSubLink = function (panelId, subpanelId) {
        this.$subLinks.removeClass('is-active');
        if (subpanelId) {
            var $link = this.$subLinks.filter('[data-target="' + subpanelId + '"]');
            if ($link.length) {
                $link.addClass('is-active');
                return;
            }
        }
        var $panelNav = this.$navItems.filter('[data-panel="' + panelId + '"]');
        $panelNav.find('.spexo-settings-sub-link').first().addClass('is-active');
    };

    SpexoSettingsLayout.prototype.highlightInitialSubLink = function () {
        var $initialPanel = this.$navItems.filter('[data-panel="' + this.activePanel + '"]');
        var $firstLink = $initialPanel.find('.spexo-settings-sub-link').first();
        if ($firstLink.length) {
            this.highlightSubLink(this.activePanel, $firstLink.data('target'));
        }
    };

    SpexoSettingsLayout.prototype.scrollToElement = function ($element) {
        if (!$element || !$element.length) {
            return;
        }

        // window.requestAnimationFrame(function () {
        //     var offsetTop = $element.offset().top - 100;
        //     window.scrollTo({
        //         top: offsetTop < 0 ? 0 : offsetTop,
        //         behavior: 'smooth'
        //     });
        // });
    };

    SpexoSettingsLayout.prototype.storeDefaultValues = function () {
        var self = this;
        console.log('[Reset Debug] Storing default values on page load...');
        
        // Store TRUE default values (empty/unchecked) for reset functionality
        this.$form.find('input, select, textarea').each(function () {
            var $field = $(this);
            var fieldName = $field.attr('name');
            var fieldId = $field.attr('id');
            var identifier = fieldName || fieldId;
            
            if (!identifier) {
                return;
            }

            var el = this;
            var defaultValue;

            // Check for data-default attribute first (explicit default from PHP)
            var dataDefault = $field.data('default');
            if (dataDefault !== undefined && dataDefault !== null && dataDefault !== '') {
                // Use explicit default from PHP
                defaultValue = dataDefault;
                console.log('[Reset Debug] Found data-default for', fieldName || fieldId, ':', dataDefault);
            } else if (el.type === 'checkbox') {
                // For checkboxes without data-default, check if defaultChecked is set in HTML
                // If defaultChecked exists, use it; otherwise default to unchecked
                if (el.defaultChecked !== undefined && el.defaultChecked) {
                    defaultValue = 'checked';
                } else {
                    defaultValue = 'unchecked';
                }
            } else if (el.type === 'radio') {
                // For radio, check if this one is defaultChecked
                if (el.defaultChecked !== undefined && el.defaultChecked) {
                    defaultValue = el.value;
                } else {
                    defaultValue = null;
                }
            } else {
                // For text/number/select/textarea, check defaultValue attribute
                // If defaultValue exists and is different from current value, use it
                // Otherwise default to empty
                if (el.defaultValue !== undefined && el.defaultValue !== '' && el.defaultValue !== el.value) {
                    defaultValue = el.defaultValue;
                } else {
                    defaultValue = '';
                }
            }

            // Store by identifier (use field name for arrays like name[])
            var storageKey = fieldName || fieldId;
            if (fieldName && fieldName.indexOf('[]') !== -1) {
                // For array fields (checkboxes), store by name + value + checked state
                var checkedState = (el.type === 'checkbox' && el.defaultChecked) ? '_checked' : '_unchecked';
                storageKey = fieldName + '_' + (el.value || el.id || '') + checkedState;
            }

            // Store by identifier (only if not already stored)
            if (!self.defaultValues[storageKey]) {
                self.defaultValues[storageKey] = defaultValue;
            }

            // Also store by field element for quick lookup
            $field.data('default-value', defaultValue);
            $field.data('storage-key', storageKey);
            
            // Store the TRUE default state for direct access
            // For reset, we want to clear to empty/unchecked unless there's an explicit default
            if (el.type === 'checkbox') {
                // Default to unchecked (false) for reset
                var explicitDefault = $field.data('default');
                if (explicitDefault === 'checked' || explicitDefault === true || explicitDefault === 'on') {
                    $field.data('initial-value', true);
                } else {
                    $field.data('initial-value', false);
                }
            } else if (el.type === 'radio') {
                // Default to unchecked (false) for reset
                var explicitDefault = $field.data('default');
                $field.data('initial-value', explicitDefault !== undefined ? (explicitDefault === el.value) : false);
            } else {
                // For text/number/select, default to empty unless there's data-default
                var explicitDefault = $field.data('default');
                $field.data('initial-value', explicitDefault !== undefined ? explicitDefault : '');
            }
        });
        
        console.log('[Reset Debug] Stored default values for', Object.keys(self.defaultValues).length, 'fields');
    };

    SpexoSettingsLayout.prototype.resetCurrentSection = function () {
        var self = this;
        var $panel = this.$panels.filter('.is-active');
        if (!$panel.length) {
            console.log('[Reset Debug] No active panel found');
            return;
        }

        var $activeSection = $panel.find('.spexo-subpanel.is-active');
        if (!$activeSection.length) {
            $activeSection = $panel.find('.spexo-subpanel').first();
        }

        var sectionId = $activeSection.attr('id') || $activeSection.data('subpanel') || 'unknown';
        console.log('[Reset Debug] Active section:', sectionId);
        console.log('[Reset Debug] Panel:', $panel.data('panel'));

        // Show confirmation dialog using custom popup
        var confirmMessage = typeof spexoSettingsLayout !== 'undefined' ? spexoSettingsLayout.resetConfirmTitle : 'Are you sure?';
        self.showResetConfirmPopup(confirmMessage, function(confirmed) {
            if (!confirmed) {
                console.log('[Reset Debug] User cancelled reset');
                return;
            }
            
            // Proceed with reset
            self.proceedWithReset($activeSection);
        });
    };

    SpexoSettingsLayout.prototype.proceedWithReset = function ($activeSection) {
        var self = this;
        
        // Find all fields in the active section
        var $fields = $activeSection.find('input, select, textarea');
        console.log('[Reset Debug] Found', $fields.length, 'fields in section');
        
        // Log field details
        $fields.each(function(index) {
            var $field = $(this);
            var fieldName = $field.attr('name') || $field.attr('id');
            var currentValue = $field.val();
            var initialValue = $field.data('initial-value');
            var defaultValue = $field.data('default-value');
            console.log('[Reset Debug] Field', index + ':', fieldName, '| Current:', currentValue, '| Initial:', initialValue, '| Default:', defaultValue);
        });

        // Show loader
        self.showLoader();

        // Reset only fields in the active section
        // Use setTimeout to allow UI to update
        setTimeout(function() {
            console.log('[Reset Debug] Starting field reset...');
            var resetCount = self.resetFields($fields);
            console.log('[Reset Debug] Reset', resetCount, 'fields');
            
            // After resetting values, submit the form to save defaults to database
            console.log('[Reset Debug] Submitting form to save defaults...');
            self.submitFormToSaveDefaults();
            
            // Hide loader and show success message after a delay
            setTimeout(function() {
                self.hideLoader();
                self.showSuccessMessage('Section Defaults Restored!');
                self.checkDirtyState();
            }, 500);
        }, 100);
    };

    SpexoSettingsLayout.prototype.showResetConfirmPopup = function (message, callback) {
        var self = this;
        
        // Check if popup already exists, if not create it
        var $popup = $('#spexo-reset-section-confirm-popup');
        var $popupWrap = $('.spexo-reset-confirm-popup-wrap');
        
        if (!$popup.length) {
            // Create popup wrapper if it doesn't exist
            if (!$popupWrap.length) {
                $popupWrap = $('<div class="spexo-reset-confirm-popup-wrap tmpcoder-admin-popup-wrap"></div>');
                $('body').append($popupWrap);
            }
            
            // Get localized strings
            var resetMessage = typeof spexoSettingsLayout !== 'undefined' ? spexoSettingsLayout.resetConfirmMessage : 'Resetting will lose all custom values in this section. This action cannot be undone.';
            var resetButtonText = typeof spexoSettingsLayout !== 'undefined' ? spexoSettingsLayout.resetButtonText : 'Reset Section';
            var cancelButtonText = typeof spexoSettingsLayout !== 'undefined' ? spexoSettingsLayout.cancelButtonText : 'Cancel';
            
            // Create popup HTML structure matching the import demo popup style exactly
            var popupHtml = '<div id="spexo-reset-section-confirm-popup">' +
                '<h2 class="popup-heading">' + message + '</h2>' +
                '<div class="popup-content">' +
                '<p class="popup-message">' + resetMessage + '</p>' +
                '<a class="button button-primary spexo-reset-confirm-button">' + resetButtonText + '</a>' +
                '<a class="button button-secondary spexo-reset-cancel-button">' + cancelButtonText + '</a>' +
                '</div>' +
                '</div>';
            
            $popupWrap.html(popupHtml);
            $popup = $('#spexo-reset-section-confirm-popup');
        } else {
            // Update message if popup exists
            $popup.find('.popup-heading').text(message);
        }
        
        // Show popup
        $popupWrap.fadeIn();
        $popup.fadeIn();
        
        // Handle confirm button click
        $popup.off('click', '.spexo-reset-confirm-button').on('click', '.spexo-reset-confirm-button', function(e) {
            e.preventDefault();
            self.hideResetConfirmPopup();
            if (callback) {
                callback(true);
            }
        });
        
        // Handle cancel button click
        $popup.off('click', '.spexo-reset-cancel-button').on('click', '.spexo-reset-cancel-button', function(e) {
            e.preventDefault();
            self.hideResetConfirmPopup();
            if (callback) {
                callback(false);
            }
        });
        
        // Handle click on overlay (outside popup)
        $popupWrap.off('click').on('click', function(e) {
            if ($(e.target).hasClass('spexo-reset-confirm-popup-wrap')) {
                self.hideResetConfirmPopup();
                if (callback) {
                    callback(false);
                }
            }
        });
    };

    SpexoSettingsLayout.prototype.hideResetConfirmPopup = function () {
        $('#spexo-reset-section-confirm-popup').fadeOut();
        $('.spexo-reset-confirm-popup-wrap').fadeOut();
    };

    SpexoSettingsLayout.prototype.resetAllSections = function () {
        var self = this;

        // Show confirmation dialog
        var confirmMessage = 'Are you sure? Resetting will lose all custom values in ALL sections.';
        if (!window.confirm(confirmMessage)) {
            return;
        }

        // Show loader
        self.showLoader();

        // Reset all fields in the form (only within the form, not outside)
        setTimeout(function() {
            // Only reset fields that are actually in the form
            var $formFields = self.$form.find('input, select, textarea');
            console.log('[Reset Debug] Found', $formFields.length, 'fields in form for reset all');
            
            var resetCount = self.resetFields($formFields);
            console.log('[Reset Debug] Reset', resetCount, 'fields in all sections');
            
            // After resetting values, submit the form to save defaults to database
            // Use the same approach as Reset Section - form submission works correctly
            console.log('[Reset Debug] Submitting form to save defaults...');
            self.submitFormToSaveDefaults();
            
            // Note: Form submission will reload the page, so we don't need to hide loader here
            // The page will reload with the saved defaults
        }, 100);
    };

    SpexoSettingsLayout.prototype.submitFormToSaveDefaults = function () {
        var self = this;
        
        console.log('[Reset Debug] Preparing to submit form to save defaults...');
        
        // Ensure all form fields are properly set before submission
        // Trigger change events one more time to ensure all values are synced
        this.$form.find('input, select, textarea').each(function() {
            var $field = $(this);
            if (!$field.prop('disabled')) {
                $field.trigger('change');
            }
        });
        
        // Small delay to ensure all events are processed
        setTimeout(function() {
            // Add a hidden input to track that this is a reset operation
            var $existingFlag = self.$form.find('input[name="spexo_reset_to_defaults"]');
            if (!$existingFlag.length) {
                var $resetFlag = $('<input>').attr({
                    type: 'hidden',
                    name: 'spexo_reset_to_defaults',
                    value: '1'
                });
                self.$form.append($resetFlag);
            }
            
            // Ensure _wp_http_referer is set for proper redirect back to settings page
            var $referer = self.$form.find('input[name="_wp_http_referer"]');
            if (!$referer.length) {
                // var currentUrl = window.location.href.split('?')[0]; // Get URL without query params
                var currentUrl = window.location.href; // Get URL without query params
                var $refererInput = $('<input>').attr({
                    type: 'hidden',
                    name: '_wp_http_referer',
                    value: currentUrl
                });
                self.$form.append($refererInput);
            } else {
                // Update referer to current page
                // var currentUrl = window.location.href.split('?')[0];
                var currentUrl = window.location.href;
                $referer.val(currentUrl);
            }
            
            // Submit the form - WordPress will save all values and redirect back to this page
            console.log('[Reset Debug] Form submission initiated - page will reload with saved defaults');
            self.$form[0].submit(); // Use native submit to ensure it works
        }, 100);
    };


    SpexoSettingsLayout.prototype.checkResetSuccess = function () {
        // Check if we just came from a reset operation (via URL parameter)
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('spexo_reset') === 'success') {
            this.showSuccessMessage('Section Defaults Restored!');
            // Clean up URL
            var newUrl = window.location.href;
            // var newUrl = window.location.pathname + window.location.search.replace(/[?&]spexo_reset=success/, '').replace(/^&/, '?');
            if (newUrl === window.location.pathname) {
                newUrl = window.location.pathname;
            }
            window.history.replaceState({}, '', newUrl);
        }
    };

    SpexoSettingsLayout.prototype.showLoader = function () {
        var $loader = $('.welcome-backend-loader');
        if ($loader.length) {
            $loader.fadeIn(200);
            this.$form.css('opacity', '0.5');
            this.$form.css('pointer-events', 'none');
        }
    };

    SpexoSettingsLayout.prototype.hideLoader = function () {
        var $loader = $('.welcome-backend-loader');
        if ($loader.length) {
            $loader.fadeOut(200);
            this.$form.css('opacity', '1');
            this.$form.css('pointer-events', 'auto');
        }
    };

    SpexoSettingsLayout.prototype.showSuccessMessage = function (message) {
        var self = this;
        // Use existing alert or find/create one
        var $alert = $('.spexo-settings-alert');
        if (!$alert.length) {
            // Create alert if it doesn't exist - insert at the top of the form content
            $alert = $('<div class="spexo-settings-alert" role="status" aria-live="polite"></div>');
            var $formContent = this.$form.find('.spexo-settings-content');
            if ($formContent.length) {
                $formContent.prepend($alert);
            } else {
                this.$form.prepend($alert);
            }
        }
        
        // Update message and show
        $alert.html('<span>' + message + '</span>');
        $alert.addClass('is-visible');

        // Hide after 3 seconds
        setTimeout(function() {
            $alert.removeClass('is-visible');
            setTimeout(function() {
                // Don't clear the HTML, just hide it (in case it's the dirty state alert)
                // The dirty state check will update it if needed
            }, 300);
        }, 3000);
    };

    SpexoSettingsLayout.prototype.resetFields = function ($fields) {
        var self = this;
        var radioGroups = {}; // Track radio button groups
        var resetCount = 0;

        console.log('[Reset Debug] resetFields called with', $fields.length, 'fields');

        // First pass: collect radio groups and reset other fields
        $fields.each(function () {
            var $field = $(this);
            var el = $field[0];
            var fieldName = $field.attr('name');
            var fieldId = $field.attr('id');
            var storageKey = $field.data('storage-key') || fieldName || fieldId;
            var defaultValue = $field.data('default-value');

            // Skip disabled fields (Pro features)
            if ($field.prop('disabled')) {
                console.log('[Reset Debug] Skipping disabled field:', fieldName || fieldId);
                return;
            }

            console.log('[Reset Debug] Processing field:', fieldName || fieldId, '| Type:', el.type, '| Current value:', el.value || el.checked);

            // If not stored in data attribute, get from defaultValues object
            if (defaultValue === undefined && storageKey && self.defaultValues[storageKey] !== undefined) {
                defaultValue = self.defaultValues[storageKey];
            }

            // Fallback: check HTML default attributes
            if (defaultValue === undefined) {
                if (el.type === 'checkbox') {
                    defaultValue = el.defaultChecked ? 'checked' : 'unchecked';
                } else if (el.type === 'radio') {
                    defaultValue = el.defaultChecked ? el.value : null;
                } else {
                    // For text/number/select, use defaultValue or empty
                    defaultValue = el.defaultValue || '';
                }
            }

            // Handle radio buttons - collect them for group processing
            if (el.type === 'radio' && fieldName) {
                if (!radioGroups[fieldName]) {
                    radioGroups[fieldName] = [];
                }
                radioGroups[fieldName].push({
                    element: el,
                    $field: $field,
                    defaultValue: defaultValue
                });
                return; // Process radios in second pass
            }

            // Reset checkbox
            if (el.type === 'checkbox') {
                // Get the default value from data-default attribute
                var dataDefault = $field.data('default');
                var shouldBeChecked = false;
                
                // Priority: data-default > initial-value > defaultValue
                if (dataDefault !== undefined && dataDefault !== null && dataDefault !== '') {
                    // If data-default is 'on' or 'checked', check the box
                    shouldBeChecked = (dataDefault === 'on' || dataDefault === 'checked' || dataDefault === true || dataDefault === '1');
                } else {
                    var initialChecked = $field.data('initial-value');
                    if (initialChecked !== undefined) {
                        shouldBeChecked = initialChecked;
                    } else {
                        // Fallback to defaultValue
                        shouldBeChecked = (defaultValue === 'checked' || defaultValue === true || defaultValue === 'on');
                    }
                }
                
                console.log('[Reset Debug] Resetting checkbox:', fieldName || fieldId, '| From:', el.checked, '| To:', shouldBeChecked, '| data-default:', dataDefault);
                
                // Force update by directly manipulating the DOM
                var oldChecked = el.checked;
                if (shouldBeChecked) {
                    el.checked = true;
                    el.setAttribute('checked', 'checked');
                    el.value = 'on'; // Ensure value is set for form submission
                } else {
                    el.checked = false;
                    el.removeAttribute('checked');
                    // For unchecked checkboxes, remove from form submission by removing name temporarily
                    // Actually, WordPress handles this - unchecked checkboxes aren't submitted
                }
                
                // Verify the checkbox was actually set
                if (el.checked !== shouldBeChecked) {
                    console.warn('[Reset Debug] WARNING: Checkbox not set correctly for', fieldName || fieldId);
                } else {
                    console.log('[Reset Debug] Successfully reset checkbox:', fieldName || fieldId);
                    resetCount++;
                }
                
                // Update toggle visual state if it's a toggle switch
                var $toggle = $field.closest('.spexo-toggle');
                if ($toggle.length) {
                    if (shouldBeChecked) {
                        $toggle.addClass('is-checked').removeClass('is-unchecked');
                    } else {
                        $toggle.removeClass('is-checked').addClass('is-unchecked');
                    }
                }
            } 
            // Reset text, number, select, textarea
            else if (el.type !== 'radio') {
                // Get the default value - priority: data-default > initial-value > empty
                var dataDefault = $field.data('default');
                var resetValue = '';
                
                if (dataDefault !== undefined && dataDefault !== null && dataDefault !== '') {
                    // Use explicit default from data-default attribute
                    resetValue = dataDefault;
                } else {
                    var initialValue = $field.data('initial-value');
                    if (initialValue !== undefined && initialValue !== null && initialValue !== '') {
                        resetValue = initialValue;
                    } else {
                        // No explicit default, reset to empty
                        resetValue = '';
                    }
                }
                
                console.log('[Reset Debug] Resetting field:', fieldName || fieldId, '| Type:', el.type, '| From:', el.value, '| To:', resetValue, '| initialValue:', initialValue, '| defaultValue:', defaultValue);
                
                // Force reset by directly setting the value
                var oldValue = el.value;
                
                // Convert resetValue to appropriate type
                if (el.type === 'number') {
                    // For number inputs, ensure value is a valid number or empty
                    if (resetValue === '' || resetValue === null || resetValue === undefined) {
                        resetValue = '';
                    } else {
                        resetValue = String(resetValue); // Ensure it's a string for input value
                    }
                }
                
                // For text inputs, clear and set
                if (el.type === 'text' || el.type === 'email' || el.type === 'url' || el.type === 'password') {
                    el.value = resetValue;
                    // Also update the value attribute
                    if (resetValue === '') {
                        el.removeAttribute('value');
                    } else {
                        el.setAttribute('value', resetValue);
                    }
                } else if (el.type === 'number') {
                    el.value = resetValue || '';
                    if (resetValue === '') {
                        el.removeAttribute('value');
                    } else {
                        el.setAttribute('value', resetValue);
                    }
                } else if (el.tagName === 'SELECT') {
                    // For select, use jQuery which is more reliable
                    $(el).val(resetValue);
                    // Also try setting selectedIndex if value doesn't work
                    if ($(el).val() !== resetValue && resetValue !== '') {
                        $(el).find('option').each(function(index) {
                            if ($(this).val() === resetValue) {
                                el.selectedIndex = index;
                                return false;
                            }
                        });
                    }
                } else if (el.tagName === 'TEXTAREA') {
                    el.value = resetValue;
                    // Update textContent for textarea
                    el.textContent = resetValue;
                } else {
                    // Fallback for other input types
                    el.value = resetValue;
                }
                
                // Verify the value was actually set
                var actualValue = el.value || '';
                if (actualValue !== resetValue) {
                    console.warn('[Reset Debug] WARNING: Value not set correctly for', fieldName || fieldId, '| Expected:', resetValue, '| Got:', actualValue);
                    // Try alternative method - use jQuery
                    $(el).val(resetValue);
                    actualValue = el.value || '';
                    if (actualValue !== resetValue) {
                        // Last resort: use native setAttribute and removeAttribute
                        if (resetValue === '') {
                            el.removeAttribute('value');
                            el.value = '';
                        } else {
                            el.setAttribute('value', resetValue);
                            el.value = resetValue;
                        }
                    }
                } else {
                    console.log('[Reset Debug] Successfully reset field:', fieldName || fieldId, '| New value:', el.value);
                    resetCount++;
                }
            }

            // Trigger multiple events to ensure UI updates
            $field.trigger('change').trigger('input').trigger('blur').trigger('focus').trigger('blur');
            
            // For number inputs, also trigger keyup to update any visual indicators
            if (el.type === 'number') {
                $field.trigger('keyup');
            }
        });

        // Second pass: handle radio button groups
        for (var groupName in radioGroups) {
            if (radioGroups.hasOwnProperty(groupName)) {
                var group = radioGroups[groupName];
                var defaultRadio = null;

                // Find the default radio button by checking initial values
                for (var i = 0; i < group.length; i++) {
                    var radio = group[i];
                    var initialChecked = radio.$field.data('initial-value');
                    
                    // Check if this radio was initially checked
                    if (initialChecked === true || initialChecked === 'checked') {
                        defaultRadio = radio;
                        break;
                    }
                    
                    // Fallback: check defaultValue
                    if (!defaultRadio && (radio.defaultValue === 'checked' || 
                        radio.defaultValue === true || 
                        radio.$field.data('default-value') === 'checked' ||
                        radio.$field.data('default-value') === radio.element.value)) {
                        defaultRadio = radio;
                    }
                }

                // Uncheck all radios in the group first
                for (var j = 0; j < group.length; j++) {
                    group[j].element.checked = false;
                    group[j].element.removeAttribute('checked');
                }

                // Check the default radio if found
                if (defaultRadio) {
                    defaultRadio.element.checked = true;
                    defaultRadio.element.setAttribute('checked', 'checked');
                    defaultRadio.$field.trigger('change').trigger('input');
                } else {
                    // If no default found, check the first one that was defaultChecked
                    for (var k = 0; k < group.length; k++) {
                        if (group[k].element.defaultChecked) {
                            group[k].element.checked = true;
                            group[k].element.setAttribute('checked', 'checked');
                            group[k].$field.trigger('change').trigger('input');
                            break;
                        }
                    }
                }
            }
        }

        // Update dirty state
        self.checkDirtyState();
        
        console.log('[Reset Debug] Total fields reset:', resetCount);
        return resetCount;
    };

    SpexoSettingsLayout.prototype.expandSubmenu = function ($item) {
        if (!$item || !$item.length) {
            return;
        }
        var $submenu = $item.children('.spexo-settings-nav-children');
        if ($submenu.length) {
            $item.addClass('is-expanded');
            $submenu.slideDown(200, function () {
                $(this).css('display', 'flex');
            });
        }
    };

    SpexoSettingsLayout.prototype.collapseSubmenu = function ($item) {
        if (!$item || !$item.length) {
            return;
        }
        var $submenu = $item.children('.spexo-settings-nav-children');
        if ($submenu.length) {
            $submenu.slideUp(200, function () {
                $item.removeClass('is-expanded');
                $(this).css('display', 'none');
            });
        } else {
            $item.removeClass('is-expanded');
        }
    };

    SpexoSettingsLayout.prototype.initNavHover = function () {
        var self = this;

        // Auto-expand active items on load (without animation)
        this.$navItems.filter('.is-active.has-children').each(function () {
            var $item = $(this);
            $item.addClass('is-expanded');
            $item.children('.spexo-settings-nav-children').show();
        });

        // Toggle expand/collapse on click for items with children
        // Only one section's sub-items should be visible at a time
        this.$navItems.on('click', '.spexo-settings-nav-item.has-children > .spexo-settings-nav-toggle', function (e) {
            e.stopPropagation();
            var $item = $(this).closest('.spexo-settings-nav-item');
            var isExpanded = $item.hasClass('is-expanded');
            
            // If clicking the active item, don't toggle - keep it expanded
            if ($item.hasClass('is-active')) {
                return;
            }
            
            // Close ALL expanded items first (including previously active ones)
            self.$navItems.filter('.is-expanded').removeClass('is-expanded');
            
            // Toggle the clicked item with animation
            if (isExpanded) {
                self.collapseSubmenu($item);
            } else {
                self.expandSubmenu($item);
            }
        });
    };

    // Get active panel from URL hash
    SpexoSettingsLayout.prototype.getActivePanelFromHash = function () {
        var hash = window.location.hash;
        if (hash && hash.length > 1) {
            // Remove # and check if it's a valid panel ID
            var panelId = hash.substring(1);
            // If hash contains a subpanel (e.g., #woocommerce-woo-page-config), extract just the panel part
            // Try direct match first
            var $navItem = this.$navItems.filter('[data-panel="' + panelId + '"]');
            if ($navItem.length) {
                return panelId;
            }
            // If direct match fails, try to find panel ID that the hash starts with
            var matchedPanel = null;
            var self = this;
            this.$navItems.each(function () {
                var $item = $(this);
                var itemPanelId = $item.data('panel');
                // Check if hash starts with panel ID (e.g., "woocommerce-woo-page-config" starts with "woocommerce")
                if (itemPanelId && panelId.indexOf(itemPanelId) === 0) {
                    matchedPanel = itemPanelId;
                    return false; // break
                }
            });
            return matchedPanel;
        }
        return null;
    };

    // Get subpanel ID from URL hash (e.g., #woocommerce-woo-page-config -> woo-page-config)
    SpexoSettingsLayout.prototype.getSubpanelFromHash = function (panelId) {
        var hash = window.location.hash;
        if (hash && hash.length > 1 && panelId) {
            var hashValue = hash.substring(1);
            // Check if hash starts with panel ID and contains a dash (indicating subpanel)
            if (hashValue.indexOf(panelId) === 0 && hashValue.length > panelId.length) {
                var subpanelPart = hashValue.substring(panelId.length + 1); // +1 for the dash
                // Verify this subpanel exists in the panel
                var $panel = this.getPanelById(panelId);
                if ($panel.length) {
                    var $subpanel = $panel.find('.spexo-subpanel[id="' + subpanelPart + '"], .spexo-subpanel[data-subpanel="' + subpanelPart + '"]');
                    if ($subpanel.length) {
                        return subpanelPart;
                    }
                    // Try matching by ID that contains the subpanel part
                    var matchedSubpanelId = null;
                    $panel.find('.spexo-subpanel').each(function () {
                        var $sp = $(this);
                        var spId = $sp.attr('id') || $sp.data('subpanel');
                        if (spId && spId.indexOf(subpanelPart) !== -1) {
                            matchedSubpanelId = spId;
                            return false; // break
                        }
                    });
                    if (matchedSubpanelId) {
                        return matchedSubpanelId;
                    }
                }
            }
        }
        return null;
    };

    // Get active subpanel from localStorage
    SpexoSettingsLayout.prototype.getActiveSubpanelFromStorage = function (panelId) {
        try {
            var subpanelKey = 'spexo_settings_active_subpanel_' + panelId;
            var stored = localStorage.getItem(subpanelKey);
            if (stored) {
                // Verify it's still a valid subpanel
                var $panel = this.getPanelById(panelId);
                if ($panel.length) {
                    var $subpanel = $panel.find('.spexo-subpanel[id="' + stored + '"], .spexo-subpanel[data-subpanel="' + stored + '"]');
                    if ($subpanel.length) {
                        return stored;
                    }
                }
            }
        } catch (e) {
            // localStorage might not be available
        }
        return null;
    };

    // Get active panel from localStorage
    SpexoSettingsLayout.prototype.getActivePanelFromStorage = function () {
        try {
            var stored = localStorage.getItem('spexo_settings_active_panel');
            if (stored) {
                // Verify it's still a valid panel
                var $navItem = this.$navItems.filter('[data-panel="' + stored + '"]');
                if ($navItem.length) {
                    return stored;
                }
            }
        } catch (e) {
            // localStorage might not be available
            console.warn('Could not access localStorage:', e);
        }
        return null;
    };

    // Save active panel to localStorage
    SpexoSettingsLayout.prototype.saveActivePanelToStorage = function (panelId) {
        try {
            localStorage.setItem('spexo_settings_active_panel', panelId);
        } catch (e) {
            // localStorage might not be available
            console.warn('Could not save to localStorage:', e);
        }
    };

    // Update URL hash with active panel
    SpexoSettingsLayout.prototype.updateActivePanelHash = function (panelId) {
        if (panelId && history.replaceState) {
            var newHash = '#' + panelId;
            // Only update if hash has changed
            if (window.location.hash !== newHash) {
                history.replaceState(null, '', window.location.pathname + window.location.search + newHash);
            }
        }
    };

    // Update URL hash with active panel and subpanel
    SpexoSettingsLayout.prototype.updateSubpanelHash = function (panelId, subpanelId) {
        if (panelId && subpanelId && history.replaceState) {
            var newHash = '#' + panelId + '-' + subpanelId.replace(/^.*-/, '');
            // Only update if hash has changed
            if (window.location.hash !== newHash) {
                history.replaceState(null, '', window.location.pathname + window.location.search + newHash);
            }
        }
    };

    // Restore the active section on page load
    SpexoSettingsLayout.prototype.restoreActiveSection = function () {
        if (!this.activePanel) {
            // No saved state - activate default
            var $defaultNavItem = this.$navItems.filter('.spexo-default-active').first();
            var $defaultPanel = this.$panels.filter('.spexo-default-active').first();
            if ($defaultNavItem.length && $defaultPanel.length) {
                this.activePanel = $defaultNavItem.data('panel');
                this.$navItems.removeClass('is-active');
                this.$panels.removeClass('is-active');
                $defaultNavItem.addClass('is-active');
                $defaultPanel.addClass('is-active');
                if ($defaultNavItem.hasClass('has-children')) {
                    $defaultNavItem.addClass('is-expanded');
                    $defaultNavItem.children('.spexo-settings-nav-children').show();
                }
            }
            return;
        }

        var $navItem = this.$navItems.filter('[data-panel="' + this.activePanel + '"]');
        if (!$navItem.length) {
            return;
        }

        // Remove is-active and spexo-default-active from all items and panels
        this.$navItems.removeClass('is-active spexo-default-active');
        this.$panels.removeClass('is-active spexo-default-active');
        this.$panels.find('.spexo-subpanel').removeClass('is-active spexo-default-active');

        // Activate the restored panel (without animation on initial load)
        this.activatePanel(this.activePanel, $navItem, null, false);
        
        // Remove the inline style that was used to prevent flicker
        var $inlineStyle = $('#spexo-initial-section-fix');
        if ($inlineStyle.length) {
            setTimeout(function() {
                $inlineStyle.remove();
            }, 100);
        }
    };

    // Handle button-style toggle clicks
    $(function () {
        // Handle clicks on toggle buttons
        $(document).on('click', '.spexo-toggle-button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $button = $(this);
            var $toggle = $button.closest('.spexo-toggle');
            var $checkbox = $toggle.find('input[type="checkbox"]');
            
            // Don't do anything if disabled
            if ($toggle.hasClass('spexo-toggle--disabled') || $checkbox.prop('disabled')) {
                return;
            }
            
            // Determine if we should enable or disable
            var shouldEnable = $button.hasClass('spexo-toggle-button-enable');
            
            // Update checkbox state
            $checkbox.prop('checked', shouldEnable).trigger('change');
        });
        
        // Sync button states when checkbox changes
        $(document).on('change', '.spexo-toggle input[type="checkbox"]', function() {
            var $checkbox = $(this);
            var $toggle = $checkbox.closest('.spexo-toggle');
            var isChecked = $checkbox.is(':checked');
            
            // Update button states visually (CSS handles most of this, but we ensure sync)
            $toggle.find('.spexo-toggle-button-enable').toggleClass('active', isChecked);
            $toggle.find('.spexo-toggle-button-disable').toggleClass('active', !isChecked);
        });
        
        // Initialize button states on page load
        $('.spexo-toggle input[type="checkbox"]').each(function() {
            $(this).trigger('change');
        });
        
        // WooCommerce Config expandable sub-options
        $(document).on('change', '.spexo-woo-config-main-toggle', function() {
            var $toggle = $(this);
            var $subOptions = $('.spexo-woo-config-sub-options[data-main-toggle="' + $toggle.attr('id') + '"]');
            if ($toggle.is(':checked')) {
                $subOptions.addClass('is-expanded');
            } else {
                $subOptions.removeClass('is-expanded');
            }
        });
        
        // Initialize state on page load
        $('.spexo-woo-config-main-toggle').each(function() {
            var $toggle = $(this);
            var $subOptions = $('.spexo-woo-config-sub-options[data-main-toggle="' + $toggle.attr('id') + '"]');
            if ($toggle.is(':checked')) {
                $subOptions.addClass('is-expanded');
            }
        });
        
        new SpexoSettingsLayout();
    });
})(jQuery);

