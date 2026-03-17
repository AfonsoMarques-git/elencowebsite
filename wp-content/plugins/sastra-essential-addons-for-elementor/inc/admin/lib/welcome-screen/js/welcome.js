var welcomeScreenFunctions = {

    desabledUnusedWidget: function(){

        jQuery('.tmpcoder-btn-unused').click( function() {

            var action = 'tmpcoder_get_elementor_pages';
            var _nonce_key = welcomeScreen.ajax_nonce

            jQuery.ajax({
                url:welcomeScreen.ajax_url,
                method:'POST',
                data: 
                {
                    action: action,
                    _ajax_nonce: _nonce_key,
                },
                beforeSend: function() {
                    jQuery('.welcome-backend-loader').fadeIn();
                    jQuery('.tmpcoder-theme-welcome').css('opacity','0.5');
                },
            })
            .done( function( response ) {

                if (response.success == true)
                {
                    var currentURL = window.location.href;
                    window.location.href = TmpcodersanitizeURL(currentURL);
                    // console.log('----success----');
                    // console.log(TmpcodersanitizeURL(currentURL));
                    jQuery('.welcome-backend-loader').fadeOut();
                    jQuery('.tmpcoder-theme-welcome').css('opacity','1');   
                }
                else
                {
                    var currentURL = window.location.href;
                    window.location.href = TmpcodersanitizeURL(currentURL);
                    // console.log('----success / else----'+TmpcodersanitizeURL(currentURL));
                    jQuery('.welcome-backend-loader').fadeOut();
                    jQuery('.tmpcoder-theme-welcome').css('opacity','1');     
                }
            })
            .fail( function( error ) {
                console.log('fail');
                console.log(error);
            })
        })
    },
    setGlobalFonts: function() {
        jQuery('.set-global-fonts-btn').click(function(e) {
            e.preventDefault();
            jQuery('.tmpcoder-set-global-fonts-confirm-popup-wrap').fadeIn();
            jQuery('#tmpcoder-set-global-fonts-confirm-popup').fadeIn();
            jQuery('.tmpcoder-admin-popup').fadeIn();

        });
  
        jQuery(document).on('click', '.tmpcoder-set-global-fonts-confirm-popup-wrap .popup-close', function(e) {
            e.preventDefault();
            jQuery('.tmpcoder-set-global-fonts-confirm-popup-wrap').fadeOut();
            jQuery('#tmpcoder-set-global-fonts-confirm-popup').fadeOut();
            jQuery('.tmpcoder-admin-popup').fadeOut();
        });
    
        jQuery(document).on('click', '.tmpcoder-set-global-fonts-confirm-button', function(e) {
            e.preventDefault();
    
            var action = 'tmpcoder_set_global_fonts';
            var _nonce_key = welcomeScreen.ajax_nonce;
    
            jQuery.ajax({
                url: welcomeScreen.ajax_url,
                method: 'POST',
                data: {
                action: action,
                _ajax_nonce: _nonce_key,
                },
                beforeSend: function() {
                    jQuery('.tmpcoder-set-global-fonts-confirm-popup-wrap').fadeOut();
                    jQuery('#tmpcoder-set-global-fonts-confirm-popup').fadeOut();

                    jQuery('.set-global-fonts-popup').fadeIn();
                    jQuery('.tmpcoder-condition-popup-wrap').fadeIn();
                    jQuery('.set-global-loader').css('display', 'flex');
                    jQuery('.set-global-font-success').css('display', 'none');
                }
            })
            .done(function(response) {
                if (response.success == true) {
                    jQuery('.set-global-loader').css('display', 'none');
                    jQuery('.set-global-font-success').css('display', 'flex');
            
                    setTimeout(function() {
                        jQuery('.tmpcoder-condition-popup-wrap').fadeOut();
                    }, 1700);
                } else {
                    jQuery('.tmpcoder-condition-popup-wrap').fadeOut();
                }
            })
            .fail(function(error) {
                console.log(error);
            });
        });
    },
  
    upgradeProNotice: function(){
        jQuery('.tmpcoder-upgrade-pro-notice .tmpcoder-upgrade-pro-notice-dismiss').click( function(e) {

            $this = jQuery(this);
            $this.parent().slideUp( 700, function() {
              $this.parent().remove();
            });
            
            var action = 'tmpcoder_upgrade_pro_notice_dismiss';
            var _nonce_key = welcomeScreen.ajax_nonce;
            var activate_pro_notice = jQuery(this).hasClass('activate-pro-notice');
            var activate_theme_notice = jQuery(this).hasClass('activate-theme-notice');

            jQuery.ajax({
              url:welcomeScreen.ajax_url,
                type: 'POST',
                data: {
                    action: action,
                    nonce: _nonce_key,
                    activate_pro_notice: activate_pro_notice,
                    activate_theme_notice: activate_theme_notice,
                },
            })
            .done( function( response ) {

                if (response.success == true)
                {
                  console.log('Notice dismissed');   
                }
                else
                {
                  console.log('Failed to dismiss notice');    
                }
            })
            .fail( function( error ) {
                console.log(error);
            })
        });
    },

    settingsSmoothScroll: function(){
        jQuery('.settings-breadcrumb-nav a').on('click', function(e){
            var targetSelector = jQuery(this).attr('href');
            if (!targetSelector || targetSelector.indexOf('#') === -1) {
                return;
            }

            var hash = targetSelector.substring(targetSelector.indexOf('#'));
            var $target = jQuery(hash);
            if (!$target.length) {
                return;
            }

            e.preventDefault();
            jQuery('html, body').animate({
                scrollTop: $target.offset().top - 80
            }, 400);
        });
    }
};

jQuery( document ).ready( function() {
    welcomeScreenFunctions.desabledUnusedWidget();
    welcomeScreenFunctions.setGlobalFonts();
    welcomeScreenFunctions.upgradeProNotice();
    welcomeScreenFunctions.settingsSmoothScroll();

    // var pluginMenuRef = jQuery('#adminmenuwrap #toplevel_page_spexo-welcome');
    // console.log('pluginMenuRef', pluginMenuRef);
    // pluginMenuRef.find('.wp-submenu-wrap li').each(function(){
    //     console.log( jQuery(this).find('a').attr('href') );
    //     if ( jQuery(this).find('a').attr('href') == welcomeScreen.global_options_link ){
    //         jQuery(this).addClass('tmpcoder-global-options-menu');
    //     }
    //     else if( jQuery(this).find('a').attr('href') == welcomeScreen.widget_settings_link ){
    //         jQuery(this).addClass('tmpcoder-widgets-settings-menu');
    //     }
    //     else if( jQuery(this).find('a').attr('href') == welcomeScreen.global_settings_link ){
    //         jQuery(this).addClass('tmpcoder-intigration-settings-menu');
    //     }
    // });

    // const $elementToMove = jQuery('.tmpcoder-global-options-menu');
    // const $siblingElement = jQuery('.tmpcoder-intigration-settings-menu');
    // if ( $siblingElement.length != 0 ){
    //     $elementToMove.insertBefore($siblingElement);
    // }

});


jQuery(document).ready(function () {
    const $header = jQuery('.tmpcoder-import-demo-page > header');

    if ($header.length === 0) return;

    const checkSticky = () => {
        const rect = $header[0].getBoundingClientRect();
        if (rect.top <= 32) {
        $header.addClass('tmpcoder-prebuilt-websites-header-sticky');
        } else {
        $header.removeClass('tmpcoder-prebuilt-websites-header-sticky');
        }
    };

    jQuery(window).on('scroll', checkSticky);
    checkSticky(); // initial check

    var searchTimeout = null;  
    jQuery('.tmpcoder-search-tracking').keyup(function(e) {

        console.log('tmpcoder-search-tracking');

        if ( e.which === 13 ) {
            return false;
        }

        var val = jQuery(this).val().toLowerCase();

        if (searchTimeout != null) {
            clearTimeout(searchTimeout);
        }

        var type = jQuery(this).data('type');

        searchTimeout = setTimeout(function() {
            searchTimeout = null;
            jQuery.ajax({
                type: 'POST',
                url:welcomeScreen.ajax_url,
                data: {
                    action: 'tmpcoder_backend_search_query_results',
                    search_query: val,
                    type:type
                },
                success: function( response ) {}
            });
        }, 1000);  
    });

});