"use strict";
(function ($) {

  /* Plugin Deactive Popup Js - Start */

    $(function () {

        var $document = $(document),
            $deactivationPopUp = $('.tmpcoder-deactivation-popup');


        if ($deactivationPopUp.length < 1)
            return;

        $(document).on('click', 'tr[data-slug="spexo-addons-for-elementor"] .deactivate a, tr[data-slug="spexo-addons-pro"] .deactivate a , tr[data-slug="sastra-essential-addons-for-elementor"] .deactivate a', function (event) {
            event.preventDefault();

            $deactivationPopUp.removeClass('hidden');

            if ($(this).attr('id') == 'deactivate-spexo-addons-pro') {
                $('.tmpcoder-deactivation-popup button[data-action]').addClass('tmpcoder-is-pro-addon');
            }
            else
            {
                $('.tmpcoder-deactivation-popup button[data-action]').removeClass('tmpcoder-is-pro-addon');    
            }

            var data_slug = $(this).closest('tr').attr('data-slug');
            
            $('.tmpcoder-deactivation-popup button[data-action]').attr('data-slug', data_slug);
        });

        $document.on('click', '.tmpcoder-deactivation-popup .close, .tmpcoder-deactivation-popup .dashicons,  .tmpcoder-deactivation-popup', function (event) {

            if (this === event.target) {
                $deactivationPopUp.addClass('hidden');
            }

        });

        $document.on('change', '.tmpcoder-deactivation-popup input[name][type="radio"]', function () {
            var $this = $(this);

            var value = $this.val(),
                name = $this.attr('name');

            value = typeof value === 'string' && value !== '' ? value : undefined;
            name = typeof name === 'string' && name !== '' ? name : undefined;

            if (value === undefined || name === undefined) {
                return;
            }

            var $targetedMessage = $('p[data-' + name + '="' + value + '"]'),
                $relatedSections = $this.parents('.body').find('section[data-' + name + ']'),
                $relatedMessages = $this.parents('.body').find('p[data-' + name + ']:not(p[data-' + name + '="' + value + '"])');

            $relatedMessages.addClass('hidden');
            $targetedMessage.removeClass('hidden');
            $relatedSections.removeClass('hidden');

        });

        $document.on('keyup', '.tmpcoder-deactivation-popup input[name], .tmpcoder-deactivation-popup textarea[name]', function (event) {

            var allowed = ['Enter', 'Escape'];

            if (!allowed.includes(event.key)) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (event.key === allowed[0]) {
                $('.tmpcoder-deactivation-popup [data-action="deactivation"]').click();
            } else if (event.key === allowed[1]) {
                $('.tmpcoder-deactivation-popup .close').click();
            }
        });

        $document.on('click', '.tmpcoder-deactivation-popup button[data-action]', function (event) {

            var $this = $(this),
                $optionsWrappers = $this.parents('.body').find('.options-wrap'),
                $toggle = $optionsWrappers.find('input[name][type="checkbox"]:checked, input[name][type="radio"]:checked'),
                $fields = $optionsWrappers.find('input[name], textarea[name]').not('input[type="checkbox"], input[type="radio"]');

            var data = {
                action: $this.data('action')
            };

            $this.text($this.attr('data-text'));

            var is_pro = false;
            if ($this.hasClass('tmpcoder-is-pro-addon')){
                is_pro = true;
            }

            data.action = typeof data.action === 'string' && data.action !== '' ? data.action : undefined;

            if ($toggle.length > 0) {
                $toggle.each(function () {
                    var $this = $(this),
                        value = $this.val(),
                        key = $this.attr('name');

                    if (typeof value === 'string' && value !== '' && typeof key === 'string' && key !== '') {
                        data[key] = value;
                    }
                });
            }

            if ($fields.length > 0) {
                $fields.each(function () {
                    var $this = $(this),
                        value = $this.val(),
                        key = $this.attr('name');

                    if (typeof value === 'string' && value !== '' && typeof key === 'string' && key !== '') {
                        data[key] = value;
                    }
                })
            }

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'tmpcoder_handle_feedback_action',
                    data: data,
                    is_pro:is_pro,
                    _wpnonce:SpexoAdmin._wpnonce
                },
                beforeSend: function () {
                    $this.prop('disabled', true);
                },
                error: function (error) {
                    console.log(error);
                },
                complete: function (res) {

                    $deactivationPopUp.addClass('hidden');
                    $this.prop('disabled', false);

                    console.log(res);

                    var $deactivateLink = $('tr[data-slug="'+$this.data('slug')+'"] .deactivate a');

                    if ($deactivateLink.length > 0) {
                        var deactivateUrl = $deactivateLink.attr('href');

                        if (typeof deactivateUrl === 'string' && deactivateUrl !== '') {
                            window.location.href = deactivateUrl;
                        } else {
                            window.location.reload();
                        }
                    }
                }
            });
        });
    });

    /* Plugin Deactive Popup Js - End */

    jQuery(document).on( 'click', '.tmpcoder-plugin-update-notice .notice-dismiss', function() {
        jQuery(document).find('.tmpcoder-plugin-update-notice').slideUp();
        console.log('works update dismiss');
        jQuery.post({
            url: SpexoAdmin.ajax_url,
            data: {
                nonce: SpexoAdmin._wpnonce_,
                action: 'tmpcoder_plugin_update_dismiss_notice',
            }
        });
    });

  /* Plugin Feature List Notice - end  */

    jQuery(document).on( 'click', '.tmpcoder-pro-features-notice .notice-dismiss', function() {

        jQuery('body').removeClass('tmpcoder-pro-features-body');

        jQuery(document).find('.tmpcoder-pro-features-notice-wrap').fadeOut();

        jQuery(document).find('.tmpcoder-pro-features-notice').slideUp();

        setTimeout(function(){
            jQuery(document).find('.tmpcoder-pro-features-notice').remove();
        },300);
        
        jQuery.post({
            url: SpexoAdmin.ajax_url,
            data: {
                nonce: SpexoAdmin._wpnonce_,
                action: 'tmpcoder_pro_features_dismiss_notice'
            }
        });
    });

    /* ====================================================================== */
    /* ======================== RATING NOTICE - START ======================= */
    /* ====================================================================== */

    jQuery(document).ready(function($) {

        // ---------------------------------------------------------------
        // 1. RATING LINK - Mark as Rated When User Clicks Rating Button
        // ---------------------------------------------------------------
        $(document).on('click', '.tmpcoder-rating-link', function(e) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tmpcoder_rating_already_rated',
                    nonce: SpexoAdmin._wpnonce_
                }
            });
        });

        // ---------------------------------------------------------------
        // 2. "MAYBE LATER" - Snooze for 7 Days
        // ---------------------------------------------------------------
        $(document).on('click', '.tmpcoder-maybe-later', function(e) {
            e.preventDefault();
            var $notice = $(this).closest('.tmpcoder-notice-banner');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tmpcoder_rating_maybe_later',
                    nonce: SpexoAdmin._wpnonce_
                },
                success: function(response) {
                    if (response.success) {
                        $notice.slideUp(400, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function() {
                    console.log('Error setting reminder');
                }
            });
        });

        // ---------------------------------------------------------------
        // 3. "I ALREADY DID" - Mark as Rated (Never Show Again)
        // ---------------------------------------------------------------
        $(document).on('click', '.tmpcoder-already-rated', function(e) {
            e.preventDefault();
            var $notice = $(this).closest('.tmpcoder-notice-banner');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tmpcoder_rating_already_rated',
                    nonce: SpexoAdmin._wpnonce_
                },
                success: function(response) {
                    if (response.success) {
                        $notice.slideUp(400, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function() {
                    console.log('Error marking as rated');
                }
            });
        });

        // ---------------------------------------------------------------
        // 4. "HELP ME FIRST" - Open Link + Reset Timer
        // ---------------------------------------------------------------
        $(document).on('click', '.tmpcoder-need-support', function(e) {
            var $notice = $(this).closest('.tmpcoder-notice-banner');
        
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tmpcoder_rating_need_help',
                    nonce: SpexoAdmin._wpnonce_
                },
                success: function(response) {
                    $notice.slideUp(400, function() {
                        $(this).remove();
                    });
                }
            });
        });

        // ---------------------------------------------------------------
        // 5. DISMISS BUTTON (X) - Permanent Dismissal
        // ---------------------------------------------------------------
        $(document).on('click', '.tmpcoder-rating-notice-dismiss', function(e) {
            e.preventDefault();
            var $notice = $(this).closest('.tmpcoder-notice-banner');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'tmpcoder_rating_dismiss_notice',
                    nonce: SpexoAdmin._wpnonce_
                },
                success: function(response) {
                    if (response.success) {
                        $notice.slideUp(400, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function() {
                    console.log('Error dismissing rating notice permanently');
                    // Still remove visually even if AJAX fails
                    $notice.slideUp(400, function() {
                        $(this).remove();
                    });
                }
            });
        });

    });

    /* ====================================================================== */
    /* ========================= RATING NOTICE - END ======================== */
    /* ====================================================================== */

})(jQuery);