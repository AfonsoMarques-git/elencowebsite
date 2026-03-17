(function ($) {
    const QuickView = {
        quickViewAddMarkup: function ($scope, jq) {
            const popupMarkup = `
                <div style="display: none" class="tmpcoder-woocommerce-popup-view tmpcoder-product-popup tmpcoder-product-zoom-in woocommerce">
                    <div class="tmpcoder-product-modal-bg"></div>
                    <div class="tmpcoder-popup-details-render tmpcoder-woo-slider-popup">
                        <div class="tmpcoder-preloader"></div>
                    </div>
                </div>`;
            if (!jq('body > .tmpcoder-woocommerce-popup-view').length) {
                jq('body').prepend(popupMarkup);
            }
        },

        openPopup: function ($scope, $) {
            $(document).on("click", ".open-popup-link", function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $this = $(this);
                const quickview_setting = $this.data('quickview-setting');
                if (!quickview_setting) return;

                const popup_view = $(".tmpcoder-woocommerce-popup-view");
                popup_view.find(".tmpcoder-popup-details-render").html('<div class="tmpcoder-preloader"></div>');
                popup_view.addClass("tmpcoder-product-popup-ready").removeClass("tmpcoder-product-modal-removing").show();

                $.ajax({
                    url: localize.ajaxurl,
                    type: "post",
                    data: {
                        action: "tmpcoder_product_quickview_popup",
                        ...quickview_setting,
                        security: localize.nonce,
                    },
                    success: function (response) {
                        if (response.success) {
                            const product_preview = $(response.data);
                            const popup_details = product_preview.find(".tmpcoder-product-popup-details");
                            popup_details.find(".variations_form").wc_variation_form();

                            const popup_view_render = popup_view.find(".tmpcoder-popup-details-render");
                            popup_view_render.html(popup_details);
                            popup_view_render.addClass(`elementor-${quickview_setting.page_id}`);
                            popup_view_render.children().addClass(`elementor-element elementor-element-${quickview_setting.widget_id}`);

                            popup_details.css("height", popup_details.height() > 400 ? "75vh" : "auto");

                            setTimeout(() => {
                                const setHeight = popup_view.find('.woocommerce-product-gallery__image').height();
                                $('body').prepend(`<style class="tmpcoder-quick-view-dynamic-css">
                                    .woocommerce-product-gallery .flex-viewport { height: ${setHeight}px; }
                                </style>`);
                                popup_view.find(".woocommerce-product-gallery").wc_product_gallery();
                                popup_view.find('.tmpcoder-product-image-wrap').css('background', 'none');
                            }, 500);

                            setTimeout(() => $('.tmpcoder-quick-view-dynamic-css').remove(), 1500);
                        }
                    }
                });
            });
        },

        closePopup: function ($scope, jq) {
            jq(document).on("click", ".tmpcoder-product-popup-close", function (event) {
                event.stopPropagation();
                QuickView.remove_product_popup(jq);
            });
            jq(document).on("click", function (event) {
                if (!event.target.closest(".tmpcoder-product-popup-details")) {
                    QuickView.remove_product_popup(jq);
                }
            });
        },

        singlePageAddToCartButton: function ($scope, $) {
            $(document).on("click", ".tmpcoder-woo-slider-popup .product .single_add_to_cart_button:not(.wc-variation-selection-needed)", function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const $this = $(this);
                let product_id = $this.val();
                const form = $this.closest("form.cart");
                const variation_id = form.find('input[name="variation_id"]').val() || "";
                const quantity = form.find('input[name="quantity"]').val();
                let items = form.hasClass("grouped_form") ? form.serializeArray() : [];
                const product_data = [];

                if (form.hasClass("variations_form")) {
                    product_id = form.find('input[name="product_id"]').val();
                }

                if (items.length > 0) {
                    items.forEach(item => {
                        const p_id = parseInt(item.name.replace(/[^\d]/g, ""), 10);
                        if (item.name.indexOf("quantity[") >= 0 && item.value && p_id > 0) {
                            product_data.push({ product_id: p_id, quantity: item.value, variation_id: 0 });
                        }
                    });
                } else {
                    product_data.push({ product_id, quantity, variation_id });
                }

                $this.removeClass("tmpcoder-addtocart-added").addClass("tmpcoder-addtocart-loading");

                $.ajax({
                    url: localize.ajaxurl,
                    type: "post",
                    data: {
                        action: "tmpcoder_product_add_to_cart",
                        product_data,
                        tmpcoder_add_to_cart_nonce: localize.nonce,
                        cart_item_data: form.serializeArray()
                    },
                    success: function (response) {
                        if (response.success) {
                            $(document.body).trigger("wc_fragment_refresh");
                            $this.removeClass("tmpcoder-addtocart-loading").addClass("tmpcoder-addtocart-added");
                            if (localize.cart_redirectition === 'yes') {
                                window.location.href = localize.cart_page_url;
                            }
                        }
                    }
                });
            });
        },

        preventStringInNumberField: function ($scope, $) {
            $(document).on("keypress", ".tmpcoder-product-details-wrap input[type=number]", function (e) {
                const isValid = /^[0-9]+$/.test(String.fromCharCode(e.which || e.keyCode));
                return isValid;
            });
        },

        remove_product_popup: function (jq) {
            const selector = jq(".tmpcoder-product-popup.tmpcoder-product-zoom-in.tmpcoder-product-popup-ready");
            selector.addClass("tmpcoder-product-modal-removing").removeClass("tmpcoder-product-popup-ready");
            selector.find('.tmpcoder-popup-details-render').html('');
        }
    };

    // For YITH Ajax Filters: Force rerun
    $(document).on('click', '.yith-wcan-filters', function () {
        window.forceFullyRun = true;
    });

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default",
            QuickView.quickViewAddMarkup);
        elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default",
            QuickView.openPopup);
        elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default",
            QuickView.closePopup);
        elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default",
            QuickView.singlePageAddToCartButton);
        elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default",
            QuickView.preventStringInNumberField);
    })
})(jQuery);
