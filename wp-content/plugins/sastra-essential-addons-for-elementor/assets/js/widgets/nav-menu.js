(function ($) {
    "use strict";

    const widgetNavMenu = function ($scope, $) {
        var $navMenu = $scope.find('.tmpcoder-nav-menu-container'),
            $mobileNavMenu = $scope.find('.tmpcoder-mobile-nav-menu-container');

        // Menu
        var subMenuFirst = $navMenu.find('.tmpcoder-nav-menu > li.menu-item-has-children'),
            subMenuDeep = $navMenu.find('.tmpcoder-sub-menu li.menu-item-has-children');

        if ($scope.find('.tmpcoder-mobile-toggle').length) {
            $scope.find('a').on('click', function () {
                if (this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)) {
                    $scope.find('.tmpcoder-mobile-toggle').trigger('click');
                }
            });
        }

        if ($navMenu.attr('data-trigger') === 'click') {
            // First Sub
            subMenuFirst.children('a').on('click', function (e) {
                var currentItem = $(this).parent(),
                    childrenSub = currentItem.children('.tmpcoder-sub-menu');

                // Reset
                subMenuFirst.not(currentItem).removeClass('tmpcoder-sub-open');
                if ($navMenu.hasClass('tmpcoder-nav-menu-horizontal') || ($navMenu.hasClass('tmpcoder-nav-menu-vertical') && $scope.hasClass('tmpcoder-sub-menu-position-absolute'))) {
                    subMenuAnimation(subMenuFirst.children('.tmpcoder-sub-menu'), false);
                }

                if (!currentItem.hasClass('tmpcoder-sub-open')) {
                    e.preventDefault();
                    currentItem.addClass('tmpcoder-sub-open');
                    subMenuAnimation(childrenSub, true);
                } else {
                    currentItem.removeClass('tmpcoder-sub-open');
                    subMenuAnimation(childrenSub, false);
                }
            });

            // Deep Subs
            subMenuDeep.on('click', function (e) {
                var currentItem = $(this),
                    childrenSub = currentItem.children('.tmpcoder-sub-menu');

                // Reset
                if ($navMenu.hasClass('tmpcoder-nav-menu-horizontal')) {
                    subMenuAnimation(subMenuDeep.find('.tmpcoder-sub-menu'), false);
                }

                if (!currentItem.hasClass('tmpcoder-sub-open')) {
                    e.preventDefault();
                    currentItem.addClass('tmpcoder-sub-open');
                    subMenuAnimation(childrenSub, true);

                } else {
                    currentItem.removeClass('tmpcoder-sub-open');
                    subMenuAnimation(childrenSub, false);
                }
            });

            // Reset Subs on Document click
            $(document).mouseup(function (e) {
                if (!subMenuFirst.is(e.target) && subMenuFirst.has(e.target).length === 0) {
                    subMenuFirst.not().removeClass('tmpcoder-sub-open');
                    subMenuAnimation(subMenuFirst.children('.tmpcoder-sub-menu'), false);
                }
                if (!subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0) {
                    subMenuDeep.removeClass('tmpcoder-sub-open');
                    subMenuAnimation(subMenuDeep.children('.tmpcoder-sub-menu'), false);
                }
            });
        } else {
            // Mouse Over
            subMenuFirst.on('mouseenter', function () {
                if ($navMenu.hasClass('tmpcoder-nav-menu-vertical') && $scope.hasClass('tmpcoder-sub-menu-position-absolute')) {
                    $navMenu.find('li').not(this).children('.tmpcoder-sub-menu').hide();
                    // BUGFIX: when menu is vertical and absolute positioned, lvl2 depth sub menus wont show properly on hover
                }

                subMenuAnimation($(this).children('.tmpcoder-sub-menu'), true);
            });

            // Deep Subs
            subMenuDeep.on('mouseenter', function () {
                subMenuAnimation($(this).children('.tmpcoder-sub-menu'), true);
            });


            // Mouse Leave
            if ($navMenu.hasClass('tmpcoder-nav-menu-horizontal')) {
                subMenuFirst.on('mouseleave', function () {
                    subMenuAnimation($(this).children('.tmpcoder-sub-menu'), false);
                });

                subMenuDeep.on('mouseleave', function () {
                    subMenuAnimation($(this).children('.tmpcoder-sub-menu'), false);
                });
            } else {

                $navMenu.on('mouseleave', function () {
                    subMenuAnimation($(this).find('.tmpcoder-sub-menu'), false);
                });
            }
        }


        // Mobile Menu
        var mobileMenu = $mobileNavMenu.find('.tmpcoder-mobile-nav-menu');

        // Toggle Button
        $mobileNavMenu.find('.tmpcoder-mobile-toggle').on('click', function () {
            $(this).toggleClass('tmpcoder-mobile-toggle-fx');

            if (!$(this).hasClass('tmpcoder-mobile-toggle-open')) {
                $(this).addClass('tmpcoder-mobile-toggle-open');

                if ($(this).find('.tmpcoder-mobile-toggle-text').length) {
                    $(this).children().eq(0).hide();
                    $(this).children().eq(1).show();
                }
            } else {
                $(this).removeClass('tmpcoder-mobile-toggle-open');
                $(this).trigger('focusout');

                if ($(this).find('.tmpcoder-mobile-toggle-text').length) {
                    $(this).children().eq(1).hide();
                    $(this).children().eq(0).show();
                }
            }

            // Show Menu
            $(this).parent().next().stop().slideToggle();

            // Fix Width
            fullWidthMobileDropdown();
        });

        // Sub Menu Class
        mobileMenu.find('.sub-menu').removeClass('tmpcoder-sub-menu').addClass('tmpcoder-mobile-sub-menu');

        // Sub Menu Dropdown
        mobileMenu.find('.menu-item-has-children').children('a').on('click', function (e) {
            var parentItem = $(this).closest('li');

            // Toggle
            if (!parentItem.hasClass('tmpcoder-mobile-sub-open')) {
                e.preventDefault();
                parentItem.addClass('tmpcoder-mobile-sub-open');
                parentItem.children('.tmpcoder-mobile-sub-menu').first().stop().slideDown();
            } else {
                parentItem.removeClass('tmpcoder-mobile-sub-open');
                parentItem.children('.tmpcoder-mobile-sub-menu').first().stop().slideUp();
            }
        });

        // Run Functions
        fullWidthMobileDropdown();

        // Run Functions on Resize
        $(window).smartresize(function () {
            fullWidthMobileDropdown();
        });

        // Full Width Dropdown
        function fullWidthMobileDropdown() {
            if (!$scope.hasClass('tmpcoder-mobile-menu-full-width') || !$scope.closest('.elementor-column').length) {
                return;
            }

            var eColumn = $scope.closest('.elementor-column'),
                mWidth = $scope.closest('.elementor-top-section').outerWidth() - 2 * mobileMenu.offset().left,
                mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

            mobileMenu.css({
                'width': mWidth + 'px',
                'left': - mPosition + 'px'
            });
        }

        // Sub Menu Animation
        function subMenuAnimation(selector, show) {
            if (show === true) {
                if ($scope.hasClass('tmpcoder-sub-menu-fx-slide')) {
                    selector.stop().slideDown();
                } else {
                    selector.stop().fadeIn();
                }
            } else {
                if ($scope.hasClass('tmpcoder-sub-menu-fx-slide')) {
                    selector.stop().slideUp();
                } else {
                    selector.stop().fadeOut();
                }
            }
        }
    }
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tmpcoder-nav-menu.default', widgetNavMenu);
    });
})(jQuery);