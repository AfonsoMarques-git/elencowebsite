jQuery(document).ready(function( $ ) {
	"use strict";

    var TmpcoderMegaMenuSettings = {

        getLiveSettings: TmpcoderMegaMenuSettingsData.settingsData,

        init: function() {
            TmpcoderMegaMenuSettings.initSettingsButtons();
        },

        initSettingsButtons: function() {
            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $(this),
                    id = TmpcoderMegaMenuSettings.getNavItemId($this),
                    depth = TmpcoderMegaMenuSettings.getNavItemDepth($this);
                    
                // Settings Button
                $this.append('<div class="tmpcoder-mm-settings-btn" data-id="'+ id +'" data-depth="'+ depth +'"><span>S</span>Mega Menu</div>');
            });
            
            // Open Popup
            $('.tmpcoder-mm-settings-btn').on( 'click', TmpcoderMegaMenuSettings.openSettingsPopup );
        },

        openSettingsPopup: function() {
            // Set Settings
            TmpcoderMegaMenuSettings.setSettings( $(this) );

            // Show Popup
            $('.tmpcoder-mm-settings-popup-wrap').fadeIn();

            // Close Temmplate Editor Popup
            TmpcoderMegaMenuSettings.closeTemplateEditorPopup();

            // Menu Width
            TmpcoderMegaMenuSettings.initMenuWidthToggle();

            // Mobile Content
            TmpcoderMegaMenuSettings.initMobileContentToggle();

            // Color Pickers
            TmpcoderMegaMenuSettings.initColorPickers();

            // Icon Picker
            TmpcoderMegaMenuSettings.initIconPicker();

            // Close Settings Popup
            TmpcoderMegaMenuSettings.closeSettingsPopup();

            // Save Settings
            TmpcoderMegaMenuSettings.saveSettings( $(this) );

            // Edit Menu Button
            TmpcoderMegaMenuSettings.initEditMenuButton( $(this) );

            // Set Tite
            $('.tmpcoder-mm-popup-title').find('span').text( $(this).closest('li').find('.menu-item-title').text() );
        },

        closeSettingsPopup: function() {
            $('.tmpcoder-mm-settings-close-popup-btn').on('click', function() {
                $('.tmpcoder-mm-settings-popup-wrap').fadeOut();
            });

            $('.tmpcoder-mm-settings-popup-wrap').on('click', function(e) {
                if(e.target !== e.currentTarget) return;
                $(this).fadeOut();
            });

            // Unbind Click
            $('.tmpcoder-save-mega-menu-btn').off('click');
            $('.tmpcoder-edit-mega-menu-btn').off('click');
        },

        initEditMenuButton: function( selector ) {
            $('.tmpcoder-edit-mega-menu-btn').on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth');

                TmpcoderMegaMenuSettings.createOrEditMenuTemplate(id, depth);
            });
        },

		createOrEditMenuTemplate: function(id, depth) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'tmpcoder_create_mega_menu_template',
                    nonce: TmpcoderMegaMenuSettingsData.nonce,
                    item_id: id,
                    item_depth: depth
				},
				success: function( response ) {
                    console.log(response.data['edit_link']);
                    TmpcoderMegaMenuSettings.openTemplateEditorPopup(response.data['edit_link']);
				}
			});
		},

        openTemplateEditorPopup: function( editorLink ) {
            $('.tmpcoder-mm-editor-popup-wrap').fadeIn();

            if ( !$('.tmpcoder-mm-editor-popup-iframe').find('iframe').length ) {
                $('.tmpcoder-mm-editor-popup-iframe').append('<iframe src="'+ editorLink +'" width="100%" height="100%"></iframe>');
            }

            // $('body').css('overflow','hidden');
        },

        closeTemplateEditorPopup: function() {
            $('.tmpcoder-mm-editor-close-popup-btn').on('click', function() {
                $('.tmpcoder-mm-editor-popup-wrap').fadeOut();
                setTimeout(function() {
                    $('.tmpcoder-mm-editor-popup-iframe').find('iframe').remove();
                    // $('body').css('overflow','visible');
                }, 1000);
            });
        },

        initColorPickers: function() {
            $('.tmpcoder-mm-setting-color').find('input').wpColorPicker();

            // Fix Color Picker
            if ( $('.tmpcoder-mm-setting-color').length ) {
                $('.tmpcoder-mm-setting-color').find('.wp-color-result-text').text('Select Color');
                $('.tmpcoder-mm-setting-color').find('.wp-picker-clear').val('Clear');
            }
        },

        initIconPicker: function() {
            $('#tmpcoder_mm_icon_picker').iconpicker();

            // Bind iconpicker events to the element
            $('#tmpcoder_mm_icon_picker').on('iconpickerSelected', function(event) {
                $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                $('.tmpcoder-mm-setting-icon div span:last-child').addClass('tmpcoder-mm-active-icon');
                $('.tmpcoder-mm-setting-icon div span:last-child i').removeAttr('class');
                $('.tmpcoder-mm-setting-icon div span:last-child i').addClass(event.iconpickerValue);
            });

            // Bind iconpicker events to the element
            $('#tmpcoder_mm_icon_picker').on('iconpickerHide', function(event) {
                setTimeout(function() {
                    if ( 'tmpcoder-mm-active-icon' == $('.tmpcoder-mm-setting-icon div span:first-child').attr('class') ) {
                        $('#tmpcoder_mm_icon_picker').val('')
                    }

                    $('.tmpcoder-mm-settings-wrap').removeAttr('style');
                },100);
            });

            $('.tmpcoder-mm-setting-icon div span:first-child').on('click', function() {
                $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                $(this).addClass('tmpcoder-mm-active-icon');
            });

            $('.tmpcoder-mm-setting-icon div span:last-child').on('click', function() {
                $('#tmpcoder_mm_icon_picker').focus();
                $('.tmpcoder-mm-settings-wrap').css('overflow', 'hidden');
            });
        },

        saveSettings: function( selector ) {
            var $saveButton = $('.tmpcoder-save-mega-menu-btn');

            // Reset
            $saveButton.text('Save');

            $saveButton.on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth'),
                    settings = TmpcoderMegaMenuSettings.getSettings();

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'tmpcoder_save_mega_menu_settings',
                        nonce: TmpcoderMegaMenuSettingsData.nonce,
                        item_id: id,
                        item_depth: depth,
                        item_settings: JSON.stringify(settings),
                    },
                    success: function( response ) {
                        $saveButton.text('Saved');
                        $saveButton.append('<span class="dashicons dashicons-yes"></span>');

                        setTimeout(function() {
                            $saveButton.find('.dashicons').remove();
                            $saveButton.text('Save');
                            $saveButton.blur();
                        }, 1000);

                        // Update Settings
                        TmpcoderMegaMenuSettings.getLiveSettings[id] = settings;
                    }
                });
            });
            
        },

        getSettings: function() {
            var settings = {};

            $('.tmpcoder-mm-setting').each(function() {
                var $this = $(this),
                    checkbox = $this.find('input[type="checkbox"]'),
                    select = $this.find('select'),
                    number = $this.find('input[type="number"]'),
                    text = $this.find('input[type="text"]');

                // Checkbox
                if ( checkbox.length ) {
                    let id = checkbox.attr('id');
                    settings[id] = checkbox.prop('checked') ? 'true' : 'false';
                }

                // Select
                if ( select.length ) {
                    let id = select.attr('id');
                    settings[id] = select.val();
                }
                
                // Multi Value
                // if ( $this.hasClass('tmpcoder-mm-setting-radius') ) {
                //     let multiValue = [],
                //         id = $this.find('input').attr('id');

                //     $this.find('input').each(function() {
                //         multiValue.push($(this).val());
                //     });

                //     settings[id] = multiValue;
                // }

                // Number
                if ( number.length ) {
                    let id = number.attr('id');
                    settings[id] = number.val();
                }
                
                // Text
                if ( text.length ) {
                    let id = text.attr('id');

                    if ( 'tmpcoder_mm_icon_picker' !== id ) {
                        settings[id] = text.val();
                    } else {
                        let icon_class = $('.tmpcoder-mm-setting-icon div span.tmpcoder-mm-active-icon').find('i').attr('class');
                        settings[id] = 'fas fa-ban' !== icon_class ? icon_class : '';
                    }
                }
            });

            return settings;
        },

		getNavItemId: function( item ) {
			var id = item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},

		getNavItemDepth: function( item ) {
			var depthClass = item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			} else {
                return depthClass[0].replace( 'menu-item-depth-', '' );
            }
		},

        initMenuWidthToggle: function() {
            var select = $('#tmpcoder_mm_width'),
                option = $('#tmpcoder_mm_custom_width').closest('.tmpcoder-mm-setting');
            
            if ( 'custom' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'custom' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        initMobileContentToggle: function() {
            var select = $('#tmpcoder_mm_mobile_content'),
                option = $('#tmpcoder_mm_render').closest('.tmpcoder-mm-setting');
            
            if ( 'mega' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'mega' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        setSettings: function( selector ) {
            var id = selector.attr('data-id'),
                settings = TmpcoderMegaMenuSettings.getLiveSettings[id];

            if ( ! $.isEmptyObject(settings) ) {
                // General
                if ( 'true' == settings['tmpcoder_mm_enable'] ) {
                    $('#tmpcoder_mm_enable').prop( 'checked', true );
                } else {
                    $('#tmpcoder_mm_enable').prop( 'checked', false );
                }
                $('#tmpcoder_mm_position').val(settings['tmpcoder_mm_position']).trigger('change');
                $('#tmpcoder_mm_width').val(settings['tmpcoder_mm_width']).trigger('change');
                $('#tmpcoder_mm_custom_width').val(settings['tmpcoder_mm_custom_width']);
                $('#tmpcoder_mm_render').val(settings['tmpcoder_mm_render']).trigger('change');
                $('#tmpcoder_mm_mobile_content').val(settings['tmpcoder_mm_mobile_content']).trigger('change');

                // Icon
                if ( '' !== settings['tmpcoder_mm_icon_picker'] ) {
                    $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:last-child').addClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').addClass(settings['tmpcoder_mm_icon_picker']);
                } else {
                    $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:first-child').addClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#tmpcoder_mm_icon_color').val(settings['tmpcoder_mm_icon_color']).trigger('keyup');
                $('#tmpcoder_mm_icon_size').val(settings['tmpcoder_mm_icon_size']);

                // Badge
                $('#tmpcoder_mm_badge_text').val(settings['tmpcoder_mm_badge_text']);
                $('#tmpcoder_mm_badge_color').val(settings['tmpcoder_mm_badge_color']).trigger('keyup');
                $('#tmpcoder_mm_badge_bg_color').val(settings['tmpcoder_mm_badge_bg_color']).trigger('keyup');
                if ( 'true' == settings['tmpcoder_mm_badge_animation'] ) {
                    $('#tmpcoder_mm_badge_animation').prop( 'checked', true );
                } else {
                    $('#tmpcoder_mm_badge_animation').prop( 'checked', false );
                }

            // Default Values
            } else {
                // General
                $('#tmpcoder_mm_enable').prop( 'checked', false );
                $('#tmpcoder_mm_position').val('default').trigger('change');
                $('#tmpcoder_mm_width').val('default').trigger('change');
                $('#tmpcoder_mm_custom_width').val('600');
                $('#tmpcoder_mm_render').val('default').trigger('change');
                $('#tmpcoder_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['tmpcoder_mm_icon_picker'] ) {
                    $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:first-child').addClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#tmpcoder_mm_icon_color').val('').trigger('change');
                $('#tmpcoder_mm_icon_size').val('');

                // Badge
                $('#tmpcoder_mm_badge_text').val('');
                $('#tmpcoder_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#tmpcoder_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#tmpcoder_mm_badge_animation').prop( 'checked', false );
            }

            if ( 'false' === $('.tmpcoder-mm-settings-wrap').attr('data-pro-active') ) {
                $('#tmpcoder_mm_render').val('default').trigger('change');
                $('#tmpcoder_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['tmpcoder_mm_icon_picker'] ) {
                    $('.tmpcoder-mm-setting-icon div span').removeClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:first-child').addClass('tmpcoder-mm-active-icon');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.tmpcoder-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#tmpcoder_mm_icon_color').val('').trigger('change');
                $('#tmpcoder_mm_icon_size').val('');

                // Badge
                $('#tmpcoder_mm_badge_text').val('');
                $('#tmpcoder_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#tmpcoder_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#tmpcoder_mm_badge_animation').prop( 'checked', false );
            }
        }
    }

    // Init
    TmpcoderMegaMenuSettings.init();
});