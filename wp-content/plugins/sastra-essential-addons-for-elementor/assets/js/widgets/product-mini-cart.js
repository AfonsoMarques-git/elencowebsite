(function ($) {
    "use strict";

    const widgetProductMiniCart = function ($scope, $) {

        $scope.find('.tmpcoder-mini-cart').css({ "display": "none" });

        // $( document.body ).trigger( 'wc_fragment_refresh' );

        var animationSpeed = $scope.find('.tmpcoder-mini-cart-wrap').length != 0 ? $scope.find('.tmpcoder-mini-cart-wrap').data('animation') : 600;

        /* Update QTY Code in Mini cart Sidebar - start */

        $('body').on('click', '.mini-cart-plus, .mini-cart-minus', function(e){
            e.preventDefault();
            var $input = $(this).siblings('.mini-cart-qty-input');
            var qty = parseInt($input.val());
            var key = $(this).data('key');
            const $btn = $(this);
            const $wrap = $btn.closest('.mini-cart-qty-wrap');
            const $loader = $wrap.find('.mini-cart-loader');

            if ($(this).hasClass('mini-cart-plus')) {
                qty++;
            } else {
                qty = qty > 1 ? qty - 1 : 1;
            }

            $input.val(qty);
            $loader.show();

            $.post(tmpcoder_plugin_script.ajax_url, {
                action: 'tmpcoder_mini_cart_qty',
                key: key,
                qty: qty
            }, function(responce){
                $loader.hide();
                jQuery(document.body).trigger('wc_fragment_refresh');
                
                // Update cart count and subtotal
                jQuery('.tmpcoder-mini-cart-icon-count span').text(responce.data.cart_count);
                jQuery('.woocommerce-mini-cart__total .woocommerce-Price-amount').html(responce.data.cart_subtotal);
                
                // Update specific product price in mini cart
                const $cartItem = jQuery(`[data-cart_item_key="${key}"]`);
                if ($cartItem.length) {
                    $cartItem.find('span.quantity.price-right').html(responce.data.product_price);
                }

            });
        });

        if ($scope.find('#tmpcoder-mini-cart').data('update-qty')) {

            jQuery(document.body).on('wc_fragments_loaded wc_fragments_refreshed', function () {
                jQuery('.widget_shopping_cart_content .woocommerce-mini-cart-item').each(function () {
                    const $item = jQuery(this);
                    const cart_item_key = $item.data('cart_item_key');

                    // Only process items that don't already have custom quantity controls
                    if ($item.find('.mini-cart-qty-wrap').length === 0) {
                        // Replace quantity block with custom markup
                        const quantityText = $item.find('.quantity').text().trim();
                        const quantity = parseInt(quantityText.split('×')[0].trim());
                        
                        // Calculate line total (quantity × unit price)
                        let lineTotal = '';
                        if (quantityText.includes('×')) {
                            // Extract unit price from "3 × $60.00" format
                            const unitPriceText = quantityText.split('×')[1] ? quantityText.split('×')[1].trim() : '';
                            if (unitPriceText) {
                                // Extract numeric value from price string (e.g., "$60.00" -> 60.00)
                                const unitPrice = parseFloat(unitPriceText.replace(/[^0-9.-]/g, ''));
                                if (!isNaN(unitPrice) && !isNaN(quantity)) {
                                    const total = unitPrice * quantity;
                                    // Format the total with currency symbol
                                    lineTotal = unitPriceText.replace(/[0-9.-]/g, '').trim() + total.toFixed(2);
                                }
                            }
                        }
                        
                        // If calculation failed, try to get from cart fragments
                        if (!lineTotal) {
                            const cartData = jQuery('body').data('wc_cart_fragments');
                            if (cartData && cartData['div.widget_shopping_cart_content']) {
                                const tempDiv = jQuery('<div>').html(cartData['div.widget_shopping_cart_content']);
                                const tempItem = tempDiv.find(`[data-cart_item_key="${cart_item_key}"]`);
                                if (tempItem.length) {
                                    const tempQuantityText = tempItem.find('.quantity').text().trim();
                                    if (tempQuantityText.includes('×')) {
                                        const tempUnitPrice = tempQuantityText.split('×')[1] ? tempQuantityText.split('×')[1].trim() : '';
                                        const tempQuantity = parseInt(tempQuantityText.split('×')[0].trim());
                                        if (tempUnitPrice && !isNaN(tempQuantity)) {
                                            const tempPrice = parseFloat(tempUnitPrice.replace(/[^0-9.-]/g, ''));
                                            if (!isNaN(tempPrice)) {
                                                const total = tempPrice * tempQuantity;
                                                lineTotal = tempUnitPrice.replace(/[0-9.-]/g, '').trim() + total.toFixed(2);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        const customQtyHTML = `
                            <div class="tmpcoder-mini-cart-quantity">
                                <div class="mini-cart-quantity-row">
                                    <div class="mini-cart-quantity mini-cart-qty-wrap">
                                        <button class="mini-cart-minus" data-key="${cart_item_key}">-</button>
                                        <input type="number" class="mini-cart-qty-input" value="${quantity}" data-key="${cart_item_key}" min="1">
                                        <button class="mini-cart-plus" data-key="${cart_item_key}">+</button>
                                        <span class="mini-cart-loader" style="display:none;"></span>
                                    </div>
                                    <span class="quantity price-right">${lineTotal}</span>
                                </div>
                            </div>
                        `;

                        $item.find('.quantity').replaceWith(customQtyHTML);
                    }
                });
            });
        }
        
        $('body').on('change', '.mini-cart-qty-input', function(){
            var $input = $(this);
            var qty = parseInt($input.val());
            var key = $input.data('key');
            const $btn = $(this);
            const $wrap = $btn.closest('.mini-cart-qty-wrap');
            const $loader = $wrap.find('.mini-cart-loader');

            if (qty < 1) qty = 1;

            $loader.show();

            $.post(tmpcoder_plugin_script.ajax_url, {
                action: 'tmpcoder_mini_cart_qty',
                key: key,
                qty: qty
            }, function(responce){
                $loader.hide();
                jQuery(document.body).trigger('wc_fragment_refresh');
                
                // Update cart count and subtotal
                jQuery('.tmpcoder-mini-cart-icon-count span').text(responce.data.cart_count);
                jQuery('.woocommerce-mini-cart__total .woocommerce-Price-amount').html(responce.data.cart_subtotal);
                
                // Update specific product price in mini cart
                const $cartItem = jQuery(`[data-cart_item_key="${key}"]`);
                if ($cartItem.length) {
                    $cartItem.find('span.quantity.price-right').html(responce.data.product_price);
                }

            });
        });

        /* Update QTY Code in Mini cart Sidebar -  end */

        $('body').on('click', function (e) {
            if (!e.target.classList.value.includes('tmpcoder-mini-cart') && !e.target.closest('.tmpcoder-mini-cart')) {
                if ($scope.hasClass('tmpcoder-mini-cart-slide')) {
                    $scope.find('.tmpcoder-mini-cart').slideUp(animationSpeed);
                } else {
                    $scope.find('.tmpcoder-mini-cart').fadeOut(animationSpeed);
                }
            }
        });

        if ($scope.hasClass('tmpcoder-mini-cart-sidebar')) {
            if ($('#wpadminbar').length) {
                $scope.find('.tmpcoder-mini-cart').css({
                    // 'top': $('#wpadminbar').css('height'),
                    // 'height': $scope.find('.tmpcoder-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
                    'z-index': 999999
                });
            }

            closeSideBar();

            $scope.find('.tmpcoder-shopping-cart-wrap').on('click', function (e) {
                // if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
                if (!e.target.classList.value.includes('tmpcoder-shopping-cart-inner-wrap') && !e.target.closest('.tmpcoder-shopping-cart-inner-wrap')) {
                    // $scope.find('.widget_shopping_cart_content').addClass('tmpcoder-mini-cart-slide-out');
                    $scope.find('.tmpcoder-shopping-cart-inner-wrap').addClass('tmpcoder-mini-cart-slide-out');
                    $scope.find('.tmpcoder-mini-cart-slide-out').css('animation-speed', animationSpeed);
                    $scope.find('.tmpcoder-shopping-cart-wrap').fadeOut(animationSpeed);
                    $('body').removeClass('tmpcoder-mini-cart-sidebar-body');
                    setTimeout(function () {
                        // $scope.find('.widget_shopping_cart_content').removeClass('tmpcoder-mini-cart-slide-out');
                        $scope.find('.tmpcoder-shopping-cart-inner-wrap').removeClass('tmpcoder-mini-cart-slide-out');
                        $scope.find('.tmpcoder-mini-cart').css({ "display": "none" });
                    }, animationSpeed + 100);
                }
            });
        }

        if ($scope.find('.tmpcoder-mini-cart').length) {
            if ($scope.hasClass('tmpcoder-mini-cart-sidebar') || $scope.hasClass('tmpcoder-mini-cart-dropdown')) {
                $scope.find('.tmpcoder-mini-cart-toggle-btn').on('click', function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                    if ('none' === $scope.find('.tmpcoder-mini-cart').css("display")) {
                        if ($scope.hasClass('tmpcoder-mini-cart-slide')) {
                            $scope.find('.tmpcoder-mini-cart').slideDown(animationSpeed);
                        } else {
                            $scope.find('.tmpcoder-mini-cart').fadeIn(animationSpeed);
                        }
                        if ($scope.hasClass('tmpcoder-mini-cart-sidebar')) {
                            $scope.find('.tmpcoder-shopping-cart-wrap').fadeIn(animationSpeed);
                            // $scope.find('.widget_shopping_cart_content').addClass('tmpcoder-mini-cart-slide-in');
                            $scope.find('.tmpcoder-shopping-cart-inner-wrap').addClass('tmpcoder-mini-cart-slide-in');
                            $scope.find('.tmpcoder-mini-cart-slide-in').css('animation-speed', animationSpeed);
                            $('body').addClass('tmpcoder-mini-cart-sidebar-body');
                        }
                        setTimeout(function () {
                            // $scope.find('.widget_shopping_cart_content').removeClass('tmpcoder-mini-cart-slide-in');
                            $scope.find('.tmpcoder-shopping-cart-inner-wrap').removeClass('tmpcoder-mini-cart-slide-in');
                            if ($scope.hasClass('tmpcoder-mini-cart-sidebar')) {
                                $scope.find('.tmpcoder-woo-mini-cart').trigger('resize');
                            }
                        }, animationSpeed + 100);
                    } else {
                        if ($scope.hasClass('tmpcoder-mini-cart-slide')) {
                            $scope.find('.tmpcoder-mini-cart').slideUp(animationSpeed);
                        } else {
                            $scope.find('.tmpcoder-mini-cart').fadeOut(animationSpeed);
                        }
                    }
                });
            }
        }

        var mutationObserver = new MutationObserver(function (mutations) {
            if ($scope.hasClass('tmpcoder-mini-cart-sidebar')) {
                closeSideBar();

                // if ( $scope.find('.tmpcoder-mini-cart').data('close-cart-heading') ) {
                //  $scope.find('.tmpcoder-close-cart h2').text($scope.find('.tmpcoder-mini-cart').data('close-cart-heading').replace(/-/g, ' '));
                // }
            }

            $scope.find('.woocommerce-mini-cart-item').on('click', '.tmpcoder-remove-item-from-mini-cart', function () {
                $(this).closest('li').addClass('tmpcoder-before-remove-from-mini-cart');
            });
        });

        // Listen to Mini Cart Changes
        mutationObserver.observe($scope[0], {
            childList: true,
            subtree: true,
        });

        function closeSideBar() {
            $scope.find('.tmpcoder-close-cart span').on('click', function (e) {
                // $scope.find('.widget_shopping_cart_content').addClass('tmpcoder-mini-cart-slide-out');
                $scope.find('.tmpcoder-shopping-cart-inner-wrap').addClass('tmpcoder-mini-cart-slide-out');
                $scope.find('.tmpcoder-mini-cart-slide-out').css('animation-speed', animationSpeed);
                $scope.find('.tmpcoder-shopping-cart-wrap').fadeOut(animationSpeed);
                $('body').removeClass('tmpcoder-mini-cart-sidebar-body');
                setTimeout(function () {
                    // $scope.find('.widget_shopping_cart_content').removeClass('tmpcoder-mini-cart-slide-out');
                    $scope.find('.tmpcoder-shopping-cart-inner-wrap').removeClass('tmpcoder-mini-cart-slide-out');
                    $scope.find('.tmpcoder-mini-cart').css({ "display": "none" });
                }, animationSpeed + 100);
            });
        }
    }
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/tmpcoder-product-mini-cart.default",
            widgetProductMiniCart);
    });
})(jQuery);