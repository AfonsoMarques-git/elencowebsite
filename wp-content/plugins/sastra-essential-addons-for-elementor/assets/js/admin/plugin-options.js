jQuery(document).ready(function( $ ) {
	"use strict";

	// Condition Selects
	var globalS  = '.target_rule-condition',
		archiveS = '.archives-condition-select',
		singleS  = '.singles-condition-select',
		inputIDs = '.tmpcoder-condition-input-ids';

	// Condition Popup
	var conditionPupup = $( '.tmpcoder-condition-popup-wrap' );

	// Current Tab
	var currentTab = $('.tmpcoder-nav-tab-wrapper .nav-tab-active').attr( 'data-title' );
		if ( currentTab ) {
			currentTab = currentTab.trim().toLowerCase(),
			currentTab = currentTab.replace(' ', '_');
		}

	/*
	** Get Active Filter -------------------------
	*/
	function getActiveFilter() {

		var type = currentTab.replace(' ', '_');

		if ( $('.template-filters').length > 0 ) {
			type = $('.template-filters .active-filter').last().attr('data-class');
			type = type.substring( 0, type.length - 1);
		}
		return type;
	}

	/*
	** Render User Template -------------------------
	*/
	function renderUserTemplate( type, title, slug, id ) {
		var html = '';

		html += '<li>';
			html += '<h3 class="tmpcoder-title">'+ title +'</h3>';
			html += '<div class="tmpcoder-action-buttons">';
				html += '<span class="tmpcoder-template-conditions button-primary" data-slug="'+ slug +'">Manage Conditions</span>';
				html += '<a href="post.php?post='+ id +'&action=elementor" class="tmpcoder-edit-template button-primary">Edit Template</a>';
				html += '<span class="tmpcoder-delete-template button-primary" data-slug="'+ slug +'" data-warning="Are you sure you want to delete this template?"><span class="dashicons dashicons-trash"></span></span>';
			html += '</div>';
		html += '</li>';

		// Render
		$( '.tmpcoder-my-templates-list.tmpcoder-'+ getActiveFilter() +'-templates-list' ).prepend( html );

		if ( $('.tmpcoder-empty-templates-message').length ) {
			$('.tmpcoder-empty-templates-message').remove();
		}

		// Run Functions
		changeTemplateConditions();
		deleteTemplate();
	}

	/*
	** Create User Template -------------------------
	*/
	function createUserTemplate() {
		// Get Template Library
		var library = 'type_global_template' === getActiveFilter() ? 'elementor_library' : TmpcoderPluginOptions.post_type;
		// Get Template Title

		var title = $('.tmpcoder-user-template-title').val();
		
		// Get Template Slug
		var slug = 'user-'+ getActiveFilter() +'-'+ title.replace( /\W+/g, '-' ).toLowerCase();

		if ( 'elementor_library' === library ) {
			slug = getActiveFilter() +'-'+ title.replace( /\W+/g, '-' ).toLowerCase();
		}

		add_loader('create_btn');

		// AJAX Data
		var data = {
			action: 'tmpcoder_create_template',
			nonce: TmpcoderPluginOptions.nonce,
			user_template_library: library,
			user_template_title: title,
			user_template_slug: slug,
			user_template_type: getActiveFilter(),
		};

		// Create Template
		$.post(ajaxurl, data, function(response) {
			// Close Popup
			remove_loader('create_btn');

			var id = response.substring( 0, response.length - 1 );
			if (!id) {
				$('.tmpcoder-create-template').before('<p class="tmpcoder-fill-out-the-title"><em>'+TmpcoderPluginOptions.valid_name_msg+'</em></p>');
				$('.tmpcoder-fill-out-the-title').css('margin-top', '4px');
				$('.tmpcoder-fill-out-the-title em').css({'color': '#ff3333', 'font-size': 'smaller'});
				$('.tmpcoder-fill-out-the-title').fadeOut(3000);
				return false;
			}
			
			$('.tmpcoder-user-template-popup-wrap').fadeOut();

			// Open Conditions
			setTimeout(function() {
				// Get Template ID

				if ( 'type_global_template' === currentTab.replace( /\W+/g, '-' ).toLowerCase() ) {
					var url = TmpcoderPluginOptions.admin_url+'post.php?post='+ id +'&action=elementor';
					url = TmpcodersanitizeURL(url);
					window.location.href = url;
					return;
				}

				// Set Template Slug & ID
				$('.tmpcoder-save-conditions').attr('data-slug', slug).attr('data-id', id);

				// Render Template
				renderUserTemplate(getActiveFilter(),$('.tmpcoder-user-template-title').val(), slug, id);

				if ( $('.tmpcoder-no-templates').length ) {
					$('.tmpcoder-no-templates').hide();
				}

				// Open Popup
				// openConditionsPopup( slug );
				openConditionsPopup( id );
				conditionPupup.addClass( 'editor-redirect' );
			}, 500);
		});
	}

	function add_loader(type){
		if (type == 'create_btn')
		{
			var create_btn_text = $('.tmpcoder-create-template').text();
			$('.tmpcoder-create-template').text(create_btn_text + ' . . .');
			$('.tmpcoder-create-template').css('opacity','0.5');
			$('.tmpcoder-create-template').css('pointer-events','none');
		}

		if (type == 'save_btb')
		{
			var save_btn_text = $('.tmpcoder-save-conditions').text();
			$('.tmpcoder-save-conditions').text(save_btn_text+' . . .');
			$('.tmpcoder-save-conditions').css('opacity','0.5');
			$('.tmpcoder-save-conditions').css('pointer-events','none');
		}
	}

	function remove_loader(type){
		if (type == 'create_btn')
		{
			$('.tmpcoder-create-template').text('Create Template');
			$('.tmpcoder-create-template').css('opacity','1');
			$('.tmpcoder-create-template').removeAttr('style');
		}
		if (type == 'save_btb')
		{
			$('.tmpcoder-save-conditions').text('SAVE CONDITIONS');
			$('.tmpcoder-save-conditions').css('opacity','1');
			$('.tmpcoder-save-conditions').removeAttr('style');
		}
	}

	// Open Popup
	$('.tmpcoder-user-template').on( 'click', function() {
		if ( $(this).find('div').length ) {
			alert('Please Install/Activate WooCommerce!');
			return;
		}

		$('.tmpcoder-user-template-title').val('');
		$('.tmpcoder-user-template-popup-wrap').fadeIn();
	});

	// Close Popup
	$('.tmpcoder-user-template-popup').find('.close-popup').on( 'click', function() {
		$('.tmpcoder-user-template-popup-wrap').fadeOut();
	});

	// Create - Click
	$('.tmpcoder-create-template').on( 'click', function() {
		if ( '' === $('.tmpcoder-user-template-title').val() ) {
			$('.tmpcoder-user-template-title').css('border-color', 'red');
			if ( $('.tmpcoder-fill-out-the-title').length < 1 ) {
				$('.tmpcoder-create-template').before('<p class="tmpcoder-fill-out-the-title"><em>Please fill the Title field.</em></p>');
				$('.tmpcoder-fill-out-the-title').css('margin-top', '4px');
				$('.tmpcoder-fill-out-the-title em').css({'color': '#ff3333', 'font-size': 'smaller'});
			}
		} else {
			$('.tmpcoder-user-template-title').removeAttr('style');
			$('.tmpcoder-create-template + p').remove();

			// Create Template
			createUserTemplate();
		}
	});

	// Create - Enter Key
	$('.tmpcoder-user-template-title').keypress(function(e) {
		if ( e.which == 13 ) {
			e.preventDefault();
			createUserTemplate();
		}
	});


	/*
	** Reset Template -------------------------
	*/
	function deleteTemplate() {
		$('.tmpcoder-delete-template').on('click', function () {
			var deleteButton = $(this);
			var slug = deleteButton.data('slug');
			var nonce = deleteButton.data('nonce');
	
			// Store data in popup
			$('.tmpcoder-delete-template-confirm-popup-wrap')
				.data('slug', slug)
				.data('nonce', nonce)
				.data('button', deleteButton)
				.fadeIn();
		});
	
		// Cancle delete template
		$('.tmpcoder-delete-template-popup').find('.tmpcoder-delete-template-confirm-popup-close').on( 'click', function() {
			$('.tmpcoder-delete-template-confirm-popup-wrap').fadeOut();
		});
	
		// Confirm delete template
		$('.tmpcoder-delete-template-confirm-button').on('click', function () {
			var popup = $('.tmpcoder-delete-template-confirm-popup-wrap');
			var slug = popup.data('slug');
			var nonce = popup.data('nonce');
			var deleteButton = popup.data('button');
			
			// Get Template Library
			var library = 'type_global_template' === getActiveFilter() ? 'elementor_library' : TmpcoderPluginOptions.post_type;
	
			deleteButton.closest('li').css({
				opacity: '0.5',
				pointerEvents: 'none'
			});
	
			var data = {
				action: 'tmpcoder_delete_template',
				template_slug: slug,
				template_library: library,
				nonce: nonce,
			};
	
			// Delete via AJAX
			$.post(ajaxurl, data, function () {
				deleteButton.closest('li').remove();
	
				setTimeout(function () {
					if ($('.tmpcoder-my-templates-list li').length === 0) {
						$('.tmpcoder-my-templates-list').append('<li class="tmpcoder-no-templates">You don\'t have any templates yet!</li>');
					}
				}, 500);
			});
	
			// Delete associated Conditions
			if ( 'type_global_template' !== getActiveFilter() ) {
				var conditions = JSON.parse($( '#tmpcoder_'+ currentTab +'_conditions' ).val());
				delete conditions[slug];
	
				// Set Conditions
				$('#tmpcoder_'+ currentTab +'_conditions').val( JSON.stringify(conditions) );
	
				// AJAX Data
				var saveData = {
					action: 'tmpcoder_save_template_conditions',
					nonce: TmpcoderPluginOptions.nonce,
				};
				saveData['tmpcoder_' + currentTab + '_conditions'] = JSON.stringify(conditions);
	
				$.post(ajaxurl, saveData);
			}
	
			// Close popup
			popup.fadeOut();
		});
	}

	deleteTemplate();

	/*
	** Condition Popup -------------------------
	*/
	// Open Popup
	function changeTemplateConditions() {

		$( '.tmpcoder-template-conditions' ).on( 'click', function() {
			var template = $(this).attr('data-slug');
			var template_conditions = $(this).attr('data-conditions');

			// Set Template Slug
			$( '.tmpcoder-save-conditions' ).attr( 'data-slug', template );

			// Open Popup
			var current_object = $(this);
			openConditionsPopup( template, template_conditions,current_object );
		});		
	}

	changeTemplateConditions();

	// Close Popup
	conditionPupup.find('.close-popup').on( 'click', function() {
		conditionPupup.fadeOut();
	});

	/*
	** Popup: Clone Conditions -------------------------
	*/
	function popupCloneConditions() {
		// Clone
		$('.tmpcoder-conditions-wrap').append( '<div class="tmpcoder-conditions">'+ $('.tmpcoder-conditions-sample').html() +'</div>' );

		// Add Tab Class
		// why removing and adding again ?
		$('.tmpcoder-target-rule-condition').removeClass( 'tmpcoder-tab-'+ currentTab ).addClass( 'tmpcoder-tab-'+ currentTab );
		var clone = $('.tmpcoder-target-rule-condition').last();

		// Reset Extra
		clone.find('select').not(':first-child').hide();

		// Entrance Animation
		clone.hide().fadeIn();

		// Hide Extra Options
		var currentFilter = $('.template-filters .active-filter').attr('data-class');

		
		if (clone.hasClass('tmpcoder-tab-product_single')) {
			setTimeout(function() {
				clone.find('.tmpcoder-condition-input-ids').each(function() {
					if ( !($(this).val()) ) {
						$(this).val('all').show();
					}
				});
			}, 600);
		}

		if ( 'blog-posts' === currentFilter || 'custom-posts' === currentFilter ) {
			clone.find('.singles-condition-select').children(':nth-child(1),:nth-child(2),:nth-child(3)').remove();
			clone.find('.tmpcoder-condition-input-ids').val('all').show();
		} else if ( 'woocommerce-products' === currentFilter ) {
			clone.find('.singles-condition-select').children().filter(function() {
				return 'product' !== $(this).val()
			}).remove();
			clone.find('.tmpcoder-condition-input-ids').val('all').show();
		} else if ( '404-pages' === currentFilter ) {
			clone.find('.singles-condition-select').children().filter(function() {
				return 'page_404' !== $(this).val()
			}).remove();
		} else if ( 'blog-archives' === currentFilter || 'custom-archives' === currentFilter ) {
			clone.find('.archives-condition-select').children().filter(function() {
				return 'products' == $(this).val() || 'product_cat' == $(this).val() || 'product_tag' == $(this).val();
			}).remove();
		} else if ( 'woocommerce-archives' === currentFilter ) {
			clone.find('.archives-condition-select').children().filter(function() {
				return 'products' !== $(this).val() && 'product_cat' !== $(this).val() && 'product_tag' !== $(this).val();
			}).remove();
		}
	}

	/*
	** Popup: Add Conditions -------------------------
	*/
	function popupAddConditions() {
		$( '.tmpcoder-add-conditions' ).on( 'click', function() {
			// Clone

			popupCloneConditions();

			// Reset
			$('.tmpcoder-conditions').last().find('input').hide();//tmp -maybe remove

			// Show on Canvas
			if ( 'type_header' === currentTab || 'type_footer' === currentTab ) {
				$('.tmpcoder-canvas-condition').show();
			}

			// Run Functions
			popupDeleteConditions();
			popupMainConditionSelect();
			popupSubConditionSelect();
		});
	}

	popupAddConditions();

	/*
	** Popup: Set Conditions -------------------------
	*/
	function popupSetConditions( template ) {
		var conditions = $( '#tmpcoder_'+ currentTab +'_conditions' ).val();

		if (conditions != undefined)
		{
			conditions = '' !== conditions ? JSON.parse(conditions) : {};
		}
		// Reset
		$('.tmpcoder-conditions').remove();

		// Setup Conditions
		if ( conditions[template] != undefined && conditions[template].length > 0 ) {
			// Clone
			for (var i = 0; i < conditions[template].length; i++) {
				popupCloneConditions();
				$( '.tmpcoder-conditions' ).find('select').hide();
			}

			// Set
			if ( $('.tmpcoder-conditions').length ) {
				$('.tmpcoder-conditions').each( function( index ) {
					var path = conditions[template][index].split( '/' );

					for (var s = 0; s < path.length; s++) {
						if ( s === 0 ) {
							$(this).find(globalS).val(path[s]).trigger('change');
							$(this).find('.'+ path[s] +'s-condition-select').show();
						} else if ( s === 1 ) {
							path[s-1] = 'product_archive' === path[s-1] ? 'archive' : path[s-1];
							$(this).find('.'+ path[s-1] +'s-condition-select').val(path[s]).trigger('change');
						} else if ( s === 2 ) {
							$(this).find(inputIDs).val(path[s]).trigger('keyup').show();
						}
					}
				});
			}
		}

		// Set Show on Canvas Switcher value
		var conditionsBtn = $('.tmpcoder-template-conditions[data-slug='+ template +']');

		if ( 'true' === conditionsBtn.attr('data-show-on-canvas') ) {
			$('.tmpcoder-canvas-condition').find('input[type=checkbox]').attr('checked', 'checked');
		} else {
			$('.tmpcoder-canvas-condition').find('input[type=checkbox]').removeAttr('checked');
		}
	}

	/*
	** Popup: Open -------------------------
	*/
	function openConditionsPopup( template, template_conditions, current_object ) {

        if (!current_object){
        	var id = template;
        }
        else
        {
        	var id = current_object.attr('data-id');
        }

        $('.tmpcoder-save-conditions').attr('data-id', id);
        var layout_type = $('.tmpcoder-layout-tabs .nav-tab-active').attr('data-title');
		
		// AJAX Data
		var data = {
			action: 'tmpcoder_select_popup_conditions',
			nonce: TmpcoderPluginOptions.nonce,
			template_id: id,
            layout_type: layout_type,
		};		

		jQuery.ajax({
	        url:ajaxurl,
	        method:'POST',
	        data:data,
	        beforeSend: function() {
	       		$('.tmpcoder-options-row-content').html($('.popup-loader-html').html());
				$('.tmpcoder-options-row-content .welcome-backend-loader').fadeIn();
				$('.tmpcoder-options-row-content .welcome-backend-loader img').css('position','absolute');
				$('.tmpcoder-options-row-content').css('height','100px');
				$('.tmpcoder-save-conditions').css('pointer-events','none');
	        }
	    })
	    .done( function( response ) {
	       	$('.tmpcoder-options-row-content').html(response);
			$('.target_rule-add-exclusion-rule').addClass('tmpcoder-hidden');
			window.cloneCondition();
			window.deleteFunction();
			window.targetField();
			$('.tmpcoder-save-conditions').removeAttr('style');
	    })
	    .fail( function( error ) {
	        console.log(error);
	    })

		// Open Popup
		conditionPupup.fadeIn(); 
	}

	/*
	** Popup: Delete Conditions -------------------------------
	*/
	function popupDeleteConditions() {
		$( '.tmpcoder-delete-template-conditions' ).on( 'click', function() {
			var current = $(this).parent(),
				conditions = $( '#tmpcoder_'+ currentTab +'_conditions' ).val();
				conditions = '' !== conditions ? JSON.parse(conditions) : {};

			// Update Conditions
			$('#tmpcoder_'+ currentTab +'_conditions').val( JSON.stringify( removeConditions( conditions, getConditionsPath(current) ) ) );

			// Remove Conditions
			current.fadeOut( 500, function() {
				$(this).remove();

				// Show on Canvas
				if ( 0 === $('.tmpcoder-conditions').length ) {
					$('.tmpcoder-canvas-condition').hide();
				}
			});
		});
	}


	/*
	** Popup: Condition Selection -------
	*/
	// General Condition Select
	function popupMainConditionSelect() {
		$(globalS).on( 'change', function() {
			var current = $(this).parent();

			// Reset
			// current.find(archiveS).hide();
			// current.find(singleS).hide();
			// current.find(inputIDs).hide();

			// Show
			current.find( '.'+ $(this).val() +'s-condition-select' ).show();

		});
	}

	// Sub Condition Select
	function popupSubConditionSelect() {
		$('.archives-condition-select, .singles-condition-select, .target_rule-condition').on( 'change', function() {
			var current = $(this).parent(),
				selected = $( 'option:selected', this ),
				value = $(this).val();

			// Show Custom ID input
			if ( selected.hasClass('custom-ids') || selected.hasClass('custom-type-ids') ) {
				current.find(inputIDs).val('all').trigger('keyup').show();
			} else {
				current.find(inputIDs).hide();
			}

			console.log(value);

			// Show/Hide Expert Notice
			if ( 0 === value.indexOf('pro-') ) {
				$('.tmpcoder-expert-notice').show();
			} else {
				$('.tmpcoder-expert-notice').hide();
			}
		});
	}

	// Show on Canvas Switcher
	function showOnCanvasSwitcher() {
		$('.tmpcoder-canvas-condition input[type=checkbox]').on('change', function() {
			$('.tmpcoder-template-conditions[data-slug='+ $('.tmpcoder-save-conditions').attr('data-slug') +']').attr('data-show-on-canvas', $(this).prop('checked'));
		});
	}

	/*
	** Remove Conditions --------------------------
	*/
	function removeConditions( conditions, path ) {
		var data = [];

		// Get Templates
		$('.tmpcoder-template-conditions').each(function() {
			data.push($(this).attr('data-slug'))
		});

		// Loop
		for ( var key in conditions ) {
			if ( conditions.hasOwnProperty(key) ) {
				// Remove Duplicate
				for (var i = 0; i < conditions[key].length; i++) {
					if ( path == conditions[key][i] ) {
						if ( 'popup' !== getActiveFilter() ) {
							conditions[key].splice(i, 1);
						}
					}
				};

				// Clear Database
				if ( data.indexOf(key) === -1 ) {
					delete conditions[key];
				}
			}
		}

		return conditions;
	}

	/*
	** Get Conditions Path -------------------------
	*/
	function getConditionsPath( current ) {
		var path = '';

		// Selects
		var global = 'none' !== current.find(globalS).css('display') ?  current.find(globalS).val() : currentTab,
			archive = current.find(archiveS).val(),
			single = current.find(singleS).val(),
			customIds = current.find(inputIDs);

		if ( 'archive' === global || 'product_archive' === global ) {
			if ( 'none' !== customIds.css('display') ) {
				path = global +'/'+ archive +'/'+ customIds.val();
			} else {
				path = global +'/'+ archive;
			}
		} else if ( 'single' === global || 'product_single' === global ) {
			if ( 'none' !== customIds.css('display') ) {
				path = global +'/'+ single +'/'+ customIds.val();
			} else {
				path = global +'/'+ single;
			}
		} else {
			path = 'global';
		}

		return path;
	}

	/*
	** Get Conditions -------------------------
	*/

	function getConditions( template, conditions ) {
		// Conditions
		conditions = ('' === conditions || '[]' === conditions) ? {} : JSON.parse(conditions);
		conditions[template] = [];
		var includeArr = [];

		$('.target_rule-condition').each( function(index) {
			includeArr.push($(this).val());
		});

		return includeArr;
	}


	/*
	** Save Conditions -------------------------
	*/

	function saveConditions() {
		$( '.tmpcoder-save-conditions' ).on( 'click', function() {
			var proActive = (1 === $('.tmpcoder-my-templates-list').data('pro')) ? true : false;

			// Current Template
			var template = $(this).attr('data-slug'),
				TemplateID = $(this).attr('data-id');

			// Get Conditions
			var conditions = getConditions( template, $( '#tmpcoder_'+ currentTab +'_conditions' ).val() );

			// Don't save if not active
			if (conditions != '') {

				if ( !proActive ) {
					
					if ( "basic-global" != conditions || 'undefined' == typeof conditions) {
						alert('Please select "Entire Site" to continue! Mutiple and custom conditions are fully supported in the Pro version.');
						return;
					}
				}
			}


			// Set Conditions
			$('#tmpcoder_'+ currentTab +'_conditions').val( JSON.stringify(conditions) );

            var specific_condition = [];
            $('select.target_rule-specific-page').each(function(){
                specific_condition.push( $(this).val() );
            });

			// AJAX Data
			var data = {
				action: 'tmpcoder_save_template_conditions',
				nonce: TmpcoderPluginOptions.nonce,
				template: template
			};
			
			// data['bsf-target-rules-location[rule]'] = conditions;
            data['bsf-target-rules-location'] = {};
            data['bsf-target-rules-location']['rule'] = [];
            data['bsf-target-rules-location']['rule'] = conditions;
            data['bsf-target-rules-location']['specific'] = [];
            data['bsf-target-rules-location']['specific'] = specific_condition;
            data['bsf-target-rules-location'] = JSON.stringify(data['bsf-target-rules-location']);

			data['tmpcoder_'+ currentTab +'_conditions'] = conditions;

			add_loader('save_btb');

			// Save Conditions
			$.post(ajaxurl, data, function(response) {
				// Close Popup
				conditionPupup.fadeOut();
				remove_loader('save_btb');

				$('#current-layout-'+TemplateID).attr('data-conditions', JSON.stringify(conditions));
                $('#current-layout-'+TemplateID).attr('data-specific', JSON.stringify(specific_condition));

				// Set Active Class
				for ( var key in conditions ) {
					if ( conditions[key] && 0 !== conditions[key].length ) {
						$('.tmpcoder-delete-template[data-slug="'+ key +'"]').closest('li').addClass('tmpcoder-active-conditions-template');
					} else {
						$('.tmpcoder-delete-template[data-slug="'+ key +'"]').closest('li').removeClass('tmpcoder-active-conditions-template');
					}
				}

				// Redirect User to Editor
				if ( conditionPupup.hasClass('editor-redirect') ) {
					var url = TmpcoderPluginOptions.admin_url+'post.php?post='+ TemplateID +'&action=elementor';
					url = TmpcodersanitizeURL(url);
					window.location.href = url;
					return;
				}
			});
		});		
	}
	
	saveConditions();

	/*
	** Highlight Templates with Active Conditions --------
	*/

	if ( $('body').hasClass('sastra-elementor-addon_page_tmpcoder-theme-builder') || $('body').hasClass('sastra-elementor-addon_page_tmpcoder-popups') ) {
		if ( currentTab && 'my_templates' !== currentTab ) {
			var conditions = $( '#tmpcoder_'+ currentTab +'_conditions' ).val(),
				conditions = ('' === conditions || '[]' === conditions) ? {} : JSON.parse(conditions);

			for ( var key in conditions ) {
				$('.tmpcoder-delete-template[data-slug="'+ key +'"]').closest('li').addClass('tmpcoder-active-conditions-template');
			}
		}
	}

	/*
	** Save Options with Ajax -------------------------
	*/
	$('.tmpcoder-settings-page form, .spexo-settings-page form, .spexo-settings-form').submit(function () {
		var settings =  $(this).serialize();

		$('.welcome-backend-loader').fadeIn();
		$('.tmpcoder-theme-welcome').css('opacity','0.5');

		$.post( 'options.php', settings ).error(function() {
			// alert('error');
			$('.welcome-backend-loader').fadeOut();
			$('.tmpcoder-theme-welcome').css('opacity','1');
		}).success(function() {
			$('.welcome-backend-loader').fadeOut();
			$('.tmpcoder-theme-welcome').css('opacity','1');
			$('.tmpcoder-settings-saved').stop().fadeIn(500).delay(1000).fadeOut(1000); 
		});

		return false;    
	});

	$('.tmpcoder-element').find('input').on( 'change', function() {
		$('.tmpcoder-settings-page form, .spexo-settings-page form, .spexo-settings-form').submit();
	});

	/*
	** Elements Toggle -------------------------
	*/
	$(".tmpcoder-btn-group").on( "click", '.tmpcoder-btn', function () {

		$('.tmpcoder-btn-group input').trigger('click');

        var $btn = $(this), isChecked = $btn.hasClass("tmpcoder-btn-enable");

        if (!$btn.hasClass("active")) {
            $(".tmpcoder-btn-group .tmpcoder-btn").removeClass("active");
            $btn.addClass("active");
        }

        if (isChecked) {
            $(".tmpcoder-btn-group .tmpcoder-btn-unused").removeClass("dimmed");
            $('.tmpcoder-element').find('input').prop( 'checked', true );
        } else {
            $(".tmpcoder-btn-group .tmpcoder-btn-unused").addClass("dimmed");
            $('.tmpcoder-element').find('input').prop( 'checked', false );
        }
        $('.tmpcoder-settings-page form').submit();
    });

	
	/*
	** Image Upload Option -----------------------
	*/
	$('body').on( 'click', '.tmpcoder-setting-custom-img-upload button', function(e){
		e.preventDefault();

		var button = $(this);

		if ( ! button.find('img').length ) {
			var custom_uploader = wp.media({
				title: 'Insert image',
				library : {
					uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
					type : 'image'
				},
				button: {
					text: 'Use this image' // button label text
				},
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();

				button.find('i').remove();
				button.prepend('<img src="' + attachment.url + '">');
				button.find('span').text('Remove Image');

				$('#tmpcoder_wl_plugin_logo').val(attachment.id);
			}).open();
		} else {
			button.find('img').remove();
			button.prepend('<i class="dashicons dashicons-cloud-upload"></i>');
			button.find('span').text('Upload Image');

			$('#tmpcoder_wl_plugin_logo').val('');
		}
	
	});

	/*
	** Elements Search --------------------------
	*/
	var searchTimeout = null;  
	$('.tmpcoder-widgets-search').find('input').keyup(function(e) {
		if ( e.which === 13 ) {
			return false;
		}

		var val = $(this).val().toLowerCase();

		if (searchTimeout != null) {
			clearTimeout(searchTimeout);
		}

		searchTimeout = setTimeout(function() {
			searchTimeout = null;
			let visibleElements = 'none';
			
			// Reset
			$('.tmpcoder-widgets-not-found').hide();
			$('.submit').show();

			if ( '' !== val ) {
				$('.tmpcoder-elements, .tmpcoder-element-box-inner, .tmpcoder-elements-heading').hide();
				$('.tmpcoder-widgets-not-found').hide();
			} else {
				$('.tmpcoder-elements, .tmpcoder-element, .tmpcoder-elements-heading').show();
				$('.tmpcoder-elements-filters li').first().trigger('click');
			}

			$('.tmpcoder-element-box-inner').each(function(){
				let title = $(this).find('h3').text().toLowerCase();

				if ( -1 !== title.indexOf(val) ) {
					$(this).show();
					$(this).parent().show();
					visibleElements = 'visible';
				}
			});

			if ( 'none' === visibleElements ) {
				$('.tmpcoder-widgets-not-found').css('display', 'flex');
				$('.submit').hide();
			}

			jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'tmpcoder_backend_search_query_results',
                    search_query: val,
                    type:1
                },
                success: function( response ) {}
            });

		}, 1000);  
	});

	/*
	** Elements Filters -----------------------------------
	*/
	$('.tmpcoder-elements-filters li').on('click', function() {
		let filter = $(this).data('filter');

		$('.tmpcoder-elements-toggle').hide();
		$('.tmpcoder-elements-filters li').removeClass('tmpcoder-active-filter');
		$(this).addClass('tmpcoder-active-filter');

		if ( 'all' === filter ) {
			$('.tmpcoder-elements, .tmpcoder-elements-heading').show();
			$('.tmpcoder-elements-toggle').show();
		} else if ( 'creative' === filter ) {
			$('.tmpcoder-elements, .tmpcoder-elements-heading').hide();
			$('.tmpcoder-elements-general').show();
			$('.tmpcoder-elements-general').prev('.tmpcoder-elements-heading').show();
		} else if ( 'theme' === filter ) {
			$('.tmpcoder-elements, .tmpcoder-elements-heading').hide();
			$('.tmpcoder-elements-theme').show();
			$('.tmpcoder-elements-theme').prev('.tmpcoder-elements-heading').show();
		} else if ( 'extensions' === filter ) {
			$('.tmpcoder-elements, .tmpcoder-elements-heading').hide();
			$('.tmpcoder-elements-extensions').show();
			$('.tmpcoder-elements-extensions').prev('.tmpcoder-elements-heading').show();
		} 
		else {
			$('.tmpcoder-elements, .tmpcoder-elements-heading').hide();
			$('.tmpcoder-elements-woo').show();
			$('.tmpcoder-elements-woo').prev('.tmpcoder-elements-heading').show();
		}
	});


}); // end dom ready