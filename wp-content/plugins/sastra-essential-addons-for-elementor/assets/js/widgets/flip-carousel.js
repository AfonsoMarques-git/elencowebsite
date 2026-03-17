(function ($) {
    "use strict";

    const widgetFlipCarousel = function ($scope, $) {
        var flipsterSettings = JSON.parse($scope.find('.tmpcoder-flip-carousel').attr('data-settings'));

        $scope.find('.tmpcoder-flip-carousel').css('opacity', 1);

        $scope.find('.tmpcoder-flip-carousel').flipster({
            itemContainer: 'ul',
            itemSelector: 'li',
            fadeIn: 400,
            start: flipsterSettings.starts_from_center === 'yes' ? 'center' : 0,
            style: flipsterSettings.carousel_type,
            loop: flipsterSettings.loop === 'yes' ? true : false,
            autoplay: flipsterSettings.autoplay === 'no' ? false : flipsterSettings.autoplay_milliseconds,
            pauseOnHover: flipsterSettings.pause_on_hover === 'yes' ? true : false,
            click: flipsterSettings.play_on_click === 'yes' ? true : false,
            scrollwheel: flipsterSettings.play_on_scroll === 'yes' ? true : false,
            touch: true,
            nav: 'true' === flipsterSettings.pagination_position ? true : flipsterSettings.pagination_position ? flipsterSettings.pagination_position : false,
            spacing: flipsterSettings.spacing,
            buttons: 'custom',
            buttonPrev: flipsterSettings.button_prev,
            buttonNext: flipsterSettings.button_next
        });

        var paginationButtons = $scope.find('.tmpcoder-flip-carousel').find('.flipster__nav__item').find('.flipster__nav__link');

        paginationButtons.each(function () {
            $(this).text(parseInt($(this).text()) + 1);
        });
    }
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/tmpcoder-flip-carousel.default",
            widgetFlipCarousel);
    });
})(jQuery);