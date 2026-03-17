( function( $ ) {

	"use strict";

	// Modal Popups
	var TmpcoderLibraryTmpls = {

		sectionIndex: null,
		contentID: 0,
		logoURL: TmpcoderLibFrontJs.logo_url,

		init: function() {
			window.elementor.on( 'preview:loaded', TmpcoderLibraryTmpls.previewLoaded );
		},

		lazyloder: function(previewIframe){
		  	previewIframe.find('.tmpcoder-lazyload-image').each(function(img) {
	    		jQuery(this).attr('src',jQuery(this).attr('data-src'));
		  	});
		},

		previewLoaded: function() {
			var previewIframe = window.elementor.$previewContents,
				addNewSection = previewIframe.find( '.elementor-add-new-section' ),
				libraryButton = '<div id="tmpcoder-library-btn" class="elementor-add-section-area-button" style="background:url('+ TmpcoderLibraryTmpls.logoURL +') no-repeat center center / contain;"></div>';

			// Add Library Button
            var elementorAddSection = $("#tmpl-elementor-add-section"),
            	elementorAddSectionText = elementorAddSection.text();
            elementorAddSectionText = elementorAddSectionText.replace('<div class="elementor-add-section-drag-title', libraryButton +'<div class="elementor-add-section-drag-title');
            elementorAddSection.text(elementorAddSectionText);

			$( previewIframe ).on( 'click', '.elementor-editor-section-settings .elementor-editor-element-add', function() {
				var addNewSectionWrap = $(this).closest( '.elementor-top-section' ).prev( '.elementor-add-section' ),
					modelID = $(this).closest( '.elementor-top-section' ).data( 'model-cid' );

				// Add Library Button
				if ( 0 == addNewSectionWrap.find( '#tmpcoder-library-btn' ).length ) {
					setTimeout( function() {
						addNewSectionWrap.find( '.elementor-add-new-section' ).prepend( libraryButton );
					}, 110 );
				}

				// Set Section Index
				if ( window.elementor.elements.models.length ) {
					$.each( window.elementor.elements.models, function( index, model ) {
						if ( modelID === model.cid ) {
							TmpcoderLibraryTmpls.sectionIndex = index;
						}
					});
				}

				TmpcoderLibraryTmpls.contentID++;
			});

			// Open Popup Predefined Styles
			// TODO: Experiment Disable Library opening for Pupups
			// if ( 'tmpcoder-popups' === window.elementor.config.document.type ) {
			// 	setTimeout(function() {
			// 		if ( 0 === previewIframe.find('.elementor-section-wrap.ui-sortable').children().length ) {
			// 			previewIframe.find('#tmpcoder-library-btn').trigger('click');
			// 		}
			// 	}, 2000);
			// }

			// Popup
			previewIframe.on( 'click', '#tmpcoder-library-btn', function() {

				// Render Popup
				TmpcoderLibraryTmpls.renderPopup( previewIframe );

				// Render Content
				/*if ( 'tmpcoder-popups' === window.elementor.config.document.type && undefined === previewIframe.find('#tmpcoder-library-btn').attr('data-filter') ) {
					TmpcoderLibraryTmpls.renderPopupContent( previewIframe, 'popups' );
				} else*/
				if ( 'wp-post' === window.elementor.config.document.type && undefined === previewIframe.find('#tmpcoder-library-btn').attr('data-filter') ) {
					TmpcoderLibraryTmpls.renderPopupContent( previewIframe, 'sections' );
					
				} else if ( undefined !== previewIframe.find('#tmpcoder-library-btn').attr('data-filter') ) {
					previewIframe.find('.tmpcoder-tplib-header').find('li').removeClass( 'tmpcoder-tplib-active-tab' );
					previewIframe.find('.tmpcoder-tplib-header').find('li[data-tab="blocks"]').addClass( 'tmpcoder-tplib-active-tab' );
					TmpcoderLibraryTmpls.renderPopupContent( previewIframe, 'blocks' );
				
				} 
				else {
					//TmpcoderLibraryTmpls.renderPopupContent( previewIframe, 'pages' );
					TmpcoderLibraryTmpls.renderPopupContent( previewIframe, 'blocks' );
					previewIframe.find('.tmpcoder-tplib-header').find('li[data-tab="blocks"]').trigger('click');
					previewIframe.find('.tmpcoder-tplib-header').find('li[data-tab="blocks"]').addClass( 'tmpcoder-tplib-active-tab' );
				}

				// Filter Content
				$(document).on( 'tmpcoder-filter-popup-content', function() {
					var filter = previewIframe.find('#tmpcoder-library-btn').attr('data-filter');
					previewIframe.find( '.tmpcoder-tplib-sidebar ul li[data-filter="'+ filter +'"]' ).trigger('click');
				});

			});
		},

		renderPopup: function( previewIframe ) {
			// Render
			if ( previewIframe.find( '.tmpcoder-tplib-popup' ).length == 0 ) {
				var headerNavigation  = '';

				headerNavigation += '<li data-tab="blocks">Blocks</li>';

				if ( 'wp-post' === window.elementor.config.document.type ) {
					// headerNavigation += '<li data-tab="pages">Pages</li>';
					headerNavigation += '<li data-tab="sections" class="tmpcoder-tplib-active-tab">Sections</li>';
				} else {
					// headerNavigation += '<li data-tab="pages" class="tmpcoder-tplib-active-tab">Pages</li>';
					headerNavigation += '<li data-tab="sections" class="tmpcoder-tplib-active-tab">Sections</li>';
				}

				if ( 'tmpcoder-popups' === window.elementor.config.document.type ) {
					if ( undefined === previewIframe.find('#tmpcoder-library-btn').attr('data-filter') ) {
						var headerNavigation = '\
						<li data-tab="sections" class="tmpcoder-tplib-active-tab">Sections</li>\
						<li data-tab="blocks">Blocks</li>';
					} else {
						var headerNavigation = '\
						<li data-tab="sections">Sections</li>\
						<li data-tab="blocks" class="tmpcoder-tplib-active-tab">Blocks</li>';
					}
				}

				previewIframe.find( 'body' ).append( '\
					<div class="tmpcoder-tplib-popup-overlay">\
						<div class="tmpcoder-tplib-popup">\
							<div class="tmpcoder-tplib-header elementor-clearfix">\
								<div class="tmpcoder-tplib-logo"><span class="tmpcoder-library-icon" style="background:url('+ TmpcoderLibraryTmpls.logoURL +') no-repeat center center / contain;">RE</span>Library</div>\
								<div class="tmpcoder-tplib-back" data-tab="">\
									<i class="eicon-chevron-left"></i> <span>Back to Library</span>\
								</div>\
								<ul>'+ headerNavigation +'</ul>\
								<span class="tmpcoder-tplib-insert-template"><i class="eicon-file-download"></i> <span>Insert</span></span>\
								<div class="tmpcoder-tplib-close"><i class="eicon-close"></i></div>\
							</div>\
							<div class="tmpcoder-tplib-content-wrap elementor-clearfix">\
							</div>\
							<div class="tmpcoder-tplib-preview-wrap"></div>\
						</div>\
					</div>\
				' );
			}
			
			// Show Overlay
			$e.run( 'panel/close' );
			$('#tmpcoder-template-settings-notification').hide();
			previewIframe.find('html').css('overflow','hidden');
			previewIframe.find('.tmpcoder-tplib-preview-wrap iframe').remove();
			previewIframe.find( '.tmpcoder-tplib-popup-overlay' ).show();

			// Close
			previewIframe.find( '.tmpcoder-tplib-close' ).on( 'click', function() {
				$e.run( 'panel/open' );
				$('#tmpcoder-template-settings-notification').show();
				previewIframe.find('html').css('overflow','auto');
				
				previewIframe.find( '.tmpcoder-tplib-popup-overlay' ).fadeOut( 'fast', function() {
					previewIframe.find('.tmpcoder-tplib-popup-overlay').remove();
					previewIframe.find('#tmpcoder-library-btn').removeAttr('data-filter');
				});
			});

			// Render Content
			previewIframe.find('.tmpcoder-tplib-header').find('li').on( 'click', function() {
				previewIframe.find( '.tmpcoder-tplib-header' ).find('li').removeClass( 'tmpcoder-tplib-active-tab' );
				$(this).addClass( 'tmpcoder-tplib-active-tab' );

				// Render Tab Content
				previewIframe.find('.tmpcoder-tplib-content-wrap').html('');
				TmpcoderLibraryTmpls.renderPopupContent( previewIframe, $(this).data('tab') );
			});

			// Close Preview
			previewIframe.find( '.tmpcoder-tplib-back' ).on( 'click', function() {
				$(this).hide();
				previewIframe.find('.tmpcoder-tplib-close i').css('border-left', '0');
				previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').hide();
				previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').removeClass('tmpcoder-tplib-insert-pro').removeClass('tmpcoder-tplib-insert-woo');
				previewIframe.find( '.tmpcoder-tplib-preview-wrap' ).hide();
				previewIframe.find( '.tmpcoder-tplib-preview-wrap' ).html('');
				previewIframe.find('.tmpcoder-tplib-popup').css('overflow','hidden');

				previewIframe.find( '.tmpcoder-tplib-logo' ).show();
				previewIframe.find( '.tmpcoder-tplib-header ul li' ).show();
				previewIframe.find( '.tmpcoder-tplib-content-wrap' ).show();
			});	
		},

		renderPopupContent: function( previewIframe, tab, scrolling = false ) {
			TmpcoderLibraryTmpls.renderPopupLoader( previewIframe );
			
			// AJAX Data
			var data = {
				action: 'tmpcoder_render_library_templates_'+ tab
			};

			// Update via AJAX
			$.post(ajaxurl, data, function( response ) {
				previewIframe.find('.tmpcoder-tplib-content-wrap').html('');
				previewIframe.find('.tmpcoder-tplib-content-wrap').html( response );

				setTimeout(function(){
					TmpcoderLibraryTmpls.lazyloder(previewIframe);
				},2000)

			// Render Preview
			}).always(function() {
				// Template Preview
				TmpcoderLibraryTmpls.templateGridPreview(previewIframe);

				// Filters
				previewIframe.find( '.tmpcoder-tplib-filters-list ul li' ).on( 'click', function() {
					var current = $(this).attr( 'data-filter' );

					// Show/Hide
					if ( 'all' === current ) {
						previewIframe.find( '.tmpcoder-tplib-template' ).parent().show();
					} else {
						previewIframe.find( '.tmpcoder-tplib-template' ).parent().hide();
						previewIframe.find( '.tmpcoder-tplib-template[data-filter="'+ current +'"]' ).parent().show();
					}

					previewIframe.find('.tmpcoder-tplib-filters h3 span').attr('data-filter', current).text($(this).text());

					// Sub Filters - TODO: Enable When Pro is Integrated
					// if ( -1 !== current.indexOf('grid') ) {
					// 	previewIframe.find( '.tmpcoder-tplib-sub-filters' ).show();
					// } else {
					// 	previewIframe.find( '.tmpcoder-tplib-sub-filters' ).hide();
					// }

					TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );
				});

				// Sub Filters
				previewIframe.find( '.tmpcoder-tplib-sub-filters ul li' ).on( 'click', function() {
					var current = $(this).attr( 'data-sub-filter' ),
						parentFilter = previewIframe.find('.tmpcoder-tplib-filters h3 span').attr('data-filter');

					// Active Class
					previewIframe.find( '.tmpcoder-tplib-sub-filters ul li' ).removeClass( 'tmpcoder-tplib-activ-filter' );
					$(this).addClass( 'tmpcoder-tplib-activ-filter' );

					// Show/Hide
					if ( 'all' === current ) {
						previewIframe.find( '.tmpcoder-tplib-template[data-filter="'+ parentFilter +'"]' ).parent().show();
					} else {
						previewIframe.find( '.tmpcoder-tplib-template' ).parent().hide();
						previewIframe.find( '.tmpcoder-tplib-template[data-filter="'+ parentFilter +'"][data-sub-filter="'+ current +'"]' ).parent().show();
					}

					TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );
				});

				// Price Filter
				previewIframe.find( '.tmpcoder-tplib-price-list ul li' ).on( 'click', function() {
					// Get Search Query
					var search = previewIframe.find('.tmpcoder-tplib-search input').val(),
						searchAttr = '' === search ? '' : '[data-title*="'+ search +'"]';

					// Reset

					// Set Active
					previewIframe.find('.tmpcoder-tplib-price').attr('data-filter', $(this).data('filter'));
					previewIframe.find('.tmpcoder-tplib-price').find('h3 span').text( $(this).text() );

					if ( 'free' === $(this).data('filter') ) {
						previewIframe.find('.tmpcoder-tplib-template-wrap'+ searchAttr).show();
						previewIframe.find('.tmpcoder-tplib-pro-wrap').hide();
					} else if ( 'pro' === $(this).data('filter') ) {
						previewIframe.find('.tmpcoder-tplib-template-wrap').hide();
						previewIframe.find('.tmpcoder-tplib-pro-wrap'+ searchAttr).show();
					} else {
						previewIframe.find('.tmpcoder-tplib-template-wrap'+ searchAttr).show();
					}

					// previewIframe.find('.tmpcoder-tplib-template-wrap'+ searchAttr).show();

					TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );
				});

				// Search
				var searchTimeout = null;

				previewIframe.find( '.tmpcoder-tplib-sidebar input' ).on( 'keyup', function() { // .tmpcoder-tplib-search
					var val = $(this).val().toLowerCase();

					if (searchTimeout != null) {
						clearTimeout(searchTimeout);
					}

					searchTimeout = setTimeout(function() {
						searchTimeout = null;

						if ( 'pages' === tab ) {
							TmpcoderLibraryTmpls.templateGridSearch(previewIframe, val);

							TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );

							/* elementorCommon.ajax.addRequest( 'tmpcoder_templates_library_search_data', {
								data: {
									search_query: val,
								},
								success: function() {
									// console.log(val);
								}
							}); */
						} else if ( 'blocks' === tab ) {
							if ( '' !== val ) {
								previewIframe.find('.tmpcoder-tplib-template-wrap').hide();
								previewIframe.find('.tmpcoder-tplib-template-wrap[data-title*="'+ val +'"]').show();
							} else {
								previewIframe.find('.tmpcoder-tplib-template-wrap').show();
							}

							TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );

							 elementorCommon.ajax.addRequest( 'tmpcoder_backend_search_query_results_func', {
								data: {
									search_query: val,
									type:3
								},
								success: function() {
									// console.log(val);
								}
							}); 
						} else if ( 'sections' === tab ) {
							if ( '' !== val ) {
								previewIframe.find('.tmpcoder-tplib-template-wrap').hide();
								previewIframe.find('.tmpcoder-tplib-template-wrap[data-title*="'+ val +'"]').show();
							} else {
								previewIframe.find('.tmpcoder-tplib-template-wrap').show();
							}

							TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );

							 elementorCommon.ajax.addRequest( 'tmpcoder_backend_search_query_results_func', {
								data: {
									search_query: val,
									type:4
								},
								success: function() {
									// console.log(val);
								}
							}); 
						}
					}, 1000);
				});

				// Import Template
				TmpcoderLibraryTmpls.templateGridImport(previewIframe);

				$(document).trigger('tmpcoder-filter-popup-content');

				function showHideLibraryFilters( selector ) {
					previewIframe.find('.tmpcoder-tplib-'+ selector).on('click', function(){
						if ( '0' == previewIframe.find('.tmpcoder-tplib-'+ selector +'-list').css('opacity') ) {
							previewIframe.find('.tmpcoder-tplib-'+ selector +'-list').css({
								'opacity' : '1',
								'visibility' : 'visible'
							});
						} else {
							previewIframe.find('.tmpcoder-tplib-'+ selector +'-list').css({
								'opacity' : '0',
								'visibility' : 'hidden'
							});
						}
					});
	
					previewIframe.on('click', function(){
						if ( '1' == previewIframe.find('.tmpcoder-tplib-'+ selector +'-list').css('opacity') ) {
							previewIframe.find('.tmpcoder-tplib-'+ selector +'-list').css({
								'opacity' : '0',
								'visibility' : 'hidden'
							});
						}
					});
				}

				showHideLibraryFilters('filters');
				showHideLibraryFilters('price');

				TmpcoderLibraryTmpls.renderPopupGrid( previewIframe );

			}); // end always

			setTimeout(function(){
				TmpcoderLibraryTmpls.renderTemplatesGridContent(previewIframe, tab);
			}, 2000);
			
		},

		renderTemplatesGridContent: function(previewIframe, tab, page = 1,) {
			var data = {
				action: 'tmpcoder_render_library_templates_pages_grid_items',
				page: page,
                nonce: TmpcoderLibFrontJs.nonce,
			};

			let isPagesActive = previewIframe.find('.tmpcoder-tplib-header ul li[data-tab="pages"]').hasClass('tmpcoder-tplib-active-tab');

			$.post(ajaxurl, data, function(response) {

                if ( tab == 'blocks' ){

                }

				if ( !isPagesActive ) return;
				
				previewIframe.find('.tmpcoder-tplib-template-gird-inner').append(response);


				var loading = ('' === response) ? false : true;

				if (loading) {
					var searchVal = previewIframe.find('.tmpcoder-tplib-sidebar input').val().toLowerCase();
					TmpcoderLibraryTmpls.templateGridSearch(previewIframe, searchVal);
					TmpcoderLibraryTmpls.renderPopupGrid(previewIframe);
					TmpcoderLibraryTmpls.templateGridPreview(previewIframe);
					TmpcoderLibraryTmpls.templateGridImport(previewIframe);

					// Proceed to the next iteration
					setTimeout(function() {
						TmpcoderLibraryTmpls.renderTemplatesGridContent(previewIframe, tab, page + 1);

					}, 1000);
				} else {
					previewIframe.find('.tmpcoder-tplib-template-gird-loading').fadeOut();
				}
			});
		},

		templateGridSearch: function( previewIframe, value ) {
            var price = previewIframe.find('.tmpcoder-tplib-price').attr( 'data-filter' ),
                priceAttr = 'mixed' === price ? '' : '[data-price*="'+ price +'"]';
			if ( '' !== value || ('' === value && priceAttr != '') ) {
				previewIframe.find('.tmpcoder-tplib-template-wrap').hide();
				previewIframe.find('.tmpcoder-tplib-template-wrap[data-title*="'+ value +'"]'+ priceAttr ).show();
			} else {
				previewIframe.find('.tmpcoder-tplib-template-wrap').show();
			}
		},

		templateGridPreview: function( previewIframe ) {
			previewIframe.find( '.tmpcoder-tplib-template-media' ).on( 'click', function() {
				let activeTab = previewIframe.find('.tmpcoder-tplib-active-tab').attr('data-tab');

				var module = $(this).parent().data('filter'),
					template = $(this).parent().data('slug'),
					kitID = $(this).parent().data('kit'),
					previewUrl = 'sections' !== activeTab ? TmpcoderLibFrontJs.demos_url + $(this).parent().data('preview-url') : $(this).parent().find('img').attr('src'),
					previewType = $(this).parent().data('preview-type'),
					proRefferal = '';

				if ( $(this).closest('.tmpcoder-tplib-pro-wrap').length ) {
					proRefferal = '-pro';
					previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').addClass('tmpcoder-tplib-insert-pro');
				}

				if ( $(this).closest('.tmpcoder-tplib-woo-wrap').length ) {
					proRefferal = '-pro';
					previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').addClass('tmpcoder-tplib-insert-woo');
				}
				
				previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').attr('data-filter', module).attr('data-slug', template).attr('data-kit', kitID);

				previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').html($(this).parent().find('.tmpcoder-tplib-insert-template').html());

				previewIframe.find('.tmpcoder-tplib-close i').css('border-left', '1px solid #e8e8e8');

				// Hide Extra
				previewIframe.find('.tmpcoder-tplib-logo').hide();
				previewIframe.find('.tmpcoder-tplib-header ul li').hide();
				previewIframe.find('.tmpcoder-tplib-back').show();
				previewIframe.find('.tmpcoder-tplib-header').find('.tmpcoder-tplib-insert-template').show();
				
				// Load Iframe
				previewIframe.find('.tmpcoder-tplib-content-wrap').hide();
				previewIframe.find('.tmpcoder-tplib-preview-wrap').show();

				if ( 'iframe' == previewType ) {
					
					previewIframe.find('.tmpcoder-tplib-preview-wrap').html( '<div class="tmpcoder-tplib-iframe"><iframe src="'+ previewUrl +'?ref=rea-plugin-library-preview'+ proRefferal +'"></iframe></div>' );
					previewIframe.find('.tmpcoder-tplib-popup').css('overflow','hidden');
				} else {
					previewIframe.find('.tmpcoder-tplib-preview-wrap').html( '<div class="tmpcoder-tplib-image"><img src="'+ previewUrl +'"></div>' );

					// Enable Scroll for Image Previews
					previewIframe.find('.tmpcoder-tplib-popup').css('overflow','scroll');
				}
			});
		},

		templateGridImport: function( previewIframe ) {
			previewIframe.find( '.tmpcoder-tplib-insert-template' ).off('click');
			previewIframe.find( '.tmpcoder-tplib-insert-template' ).on( 'click', function() {
				var module = ( $(this).parent().hasClass('tmpcoder-tplib-header') ) ? $(this).attr( 'data-filter' ) : $(this).closest( '.tmpcoder-tplib-template' ).attr( 'data-filter' ),
					template = ( $(this).parent().hasClass('tmpcoder-tplib-header') ) ? $(this).attr( 'data-slug' ) : $(this).closest( '.tmpcoder-tplib-template' ).attr( 'data-slug' ),
					kitID = ( $(this).parent().hasClass('tmpcoder-tplib-header') ) ? $(this).attr( 'data-kit' ) : $(this).closest( '.tmpcoder-tplib-template' ).attr( 'data-kit' ),
					sectionSlug = ( $(this).parent().hasClass('tmpcoder-tplib-header') ) ? $(this).attr( 'data-filter' ) : $(this).closest( '.tmpcoder-tplib-template' ).attr( 'data-filter' ),
					activeTab = previewIframe.find('.tmpcoder-tplib-active-tab').attr('data-tab');

				// Is Woo Template and Woo is not Active
				if ( $(this).closest('.tmpcoder-tplib-template-wrap').hasClass('tmpcoder-tplib-woo-wrap') || $(this).hasClass('tmpcoder-tplib-insert-woo') ) {
					alert('In order to import this Template, Please activate WooCommerce plugin.');
					return;
				}

				// Popup Templates
				if ( 'tmpcoder-popups' === window.elementor.config.document.type && 'popups' === activeTab ) {
					module = 'popups/'+ module;
				}

				// Purchase Page
				if ( $(this).hasClass('tmpcoder-tplib-insert-pro') ) {
					var href = window.location.href,
						adminUrl = href.substring(0, href.indexOf('/wp-admin')+9);

					var url = 'https://spexoaddons.com/?ref=rea-plugin-library-'+ module +'-upgrade-pro#purchasepro';

					url = TmpcodersanitizeURL(url); 
					window.open(url, '_blank');
					return;
				}

				previewIframe.find('.tmpcoder-tplib-content-wrap').show();
				previewIframe.find('.tmpcoder-tplib-preview-wrap').hide();
				TmpcoderLibraryTmpls.renderPopupLoader( previewIframe );
				
				// Template Slug
				template = template.includes('-zzz') ? template.replace('-zzz', '') : template;
				template = 'pages' === activeTab ? template : module +'/'+ template;
				sectionSlug = 'sections' === activeTab ? sectionSlug : '';

				// AJAX Data
				var data = {
					action: 'tmpcoder_import_library_template',
					nonce: TmpcoderLibFrontJs.nonce,
					slug: template,
					kit: kitID,
					section: sectionSlug,
				};

				// Update via AJAX
				$.post(ajaxurl, data, function(response) {
					if ( 0 == response ) {
						location.reload();
						return;
					}

					var importFile = response.substring( 0, response.length-1 ),
						importFile = JSON.parse( importFile );

						console.log(importFile);

					importFile.content[0].id += TmpcoderLibraryTmpls.contentID;

					// Insert Template
					window.elementor.getPreviewView().addChildModel( importFile.content, { at: TmpcoderLibraryTmpls.sectionIndex } );

					// Import Page Settings
					if ( 'undefined' !== typeof importFile.page_settings && undefined !== kitID ) {
						elementor.settings.page.model.set( importFile.page_settings );
					}

					// Popups
					if ( 'tmpcoder-popups' === window.elementor.config.document.type && 'popups' === activeTab ) {
						var defaults = {
							popup_trigger: 'load',
							popup_show_again_delay: '0',
							popup_load_delay: '1',
							popup_display_as: 'modal',
							popup_width: {
								unit: 'px',
								size: '650'
							},
							popup_height: 'auto',
							popup_align_hr: 'center',
							popup_align_vr: 'center',
							popup_content_align: 'flex-start',
							popup_animation: 'fadeIn',
							popup_animation_duration: '1',
							popup_overlay_display: 'yes',
							popup_close_button_display: 'yes',
							popup_container_bg_background: 'classic',
							popup_container_bg_position: 'center center',
							popup_container_bg_repeat: 'no-repeat',
							popup_container_bg_size: 'cover',
							popup_overlay_bg_background: 'classic',
							popup_container_padding: {
								isLinked: true,
								unit: 'px',
								top: 20,
								right: 20,
								bottom: 20,
								left: 20,
							},
							popup_container_radius: {
								isLinked: true,
								unit: 'px',
								top: 0,
								right: 0,
								bottom: 0,
								left: 0,
							}
						};
						// Reset Defaults
						elementor.settings.page.model.set(defaults);

						// Import Page Settings
						elementor.settings.page.model.set( importFile.page_settings );
					}

					// Fix Update Button
					window.elementor.panel.$el.find('#elementor-panel-footer-saver-publish button').removeClass('elementor-disabled');
					window.elementor.panel.$el.find('#elementor-panel-footer-saver-options button').removeClass('elementor-disabled');

					// Reset Section Index
					TmpcoderLibraryTmpls.sectionIndex = null;

					// Close Library
					$e.run( 'panel/open' );
					$('#tmpcoder-template-settings-notification').show();
					previewIframe.find('html').css('overflow','auto');
					previewIframe.find( '.tmpcoder-tplib-popup-overlay' ).fadeOut( 'fast', function() {
						previewIframe.find('.tmpcoder-tplib-popup-overlay').remove();
						previewIframe.find('#tmpcoder-library-btn').removeAttr('data-filter');
					});
				}).success(function() {
					let block = 'blocks' === activeTab ? template : '',
						section = 'sections' === activeTab ? template : '';

						console.log(section);
					// Run Import Finished 
					elementorCommon.ajax.addRequest( 'tmpcoder_library_template_import_finished', {
						data: {
							template: template,
							kit: kitID,
							block: block,
							section: section//tzz
						},
						success: function() {
							// console.log(block);
						}
					});
				});
			});
		},

		renderPopupLoader: function( previewIframe ) {
			previewIframe.find( '.tmpcoder-tplib-content-wrap' ).prepend('<div class="tmpcoder-tplib-loader"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>');
		},

		renderPopupGrid: function( previewIframe ) {
			let activeTab = previewIframe.find('.tmpcoder-tplib-active-tab').attr('data-tab'),
				gColumns = 'sections' === activeTab ? 4 : 5;

			// Run Macy
			var macy = Macy({
				container: previewIframe.find('.tmpcoder-tplib-template-gird-inner')[0],
				waitForImages: true,
				margin: 30,
				columns: gColumns,
				breakAt: {
					1450: 4,
					940: 3,
					520: 2,
					400: 1
				}
			});

			setTimeout(function(){
				macy.recalculate(true);
			}, 300 );

			setTimeout(function(){
				macy.recalculate(true);
			}, 600 );

		}

	};

	$( window ).on( 'elementor:init', TmpcoderLibraryTmpls.init );

}( jQuery ) );