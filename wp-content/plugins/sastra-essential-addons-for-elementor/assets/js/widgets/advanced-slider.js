(function ($) {
    "use strict";

    const widgetAdvancedSlider = function ($scope, $) {

        var $advancedSlider = $scope.find('.tmpcoder-advanced-slider'),
            sliderData = $advancedSlider.data('slick'),
            videoBtnSize = $advancedSlider.data('video-btn-size');

        // Slider Columns
        var sliderClass = $scope.attr('class'),

            sliderColumnsDesktop = sliderClass.match(/tmpcoder-adv-slider-columns-\d/) ? +sliderClass.match(/tmpcoder-adv-slider-columns-\d/).join().slice(-1) : 2,
            sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? +sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
            sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? +sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
            sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? +sliderClass.match(/columns--tablet\d/).join().slice(-1) : 1,
            sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? +sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
            sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? +sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
            sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? +sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,

            sliderSlidesToScroll = +(sliderClass.match(/tmpcoder-adv-slides-to-scroll-\d/).join().slice(-1)),
            dataSlideEffect = $advancedSlider.attr('data-slide-effect');

        $advancedSlider.slick({
            appendArrows: $scope.find('.tmpcoder-slider-controls'),
            appendDots: $scope.find('.tmpcoder-slider-dots'),
            customPaging: function (slider, i) {
                var slideNumber = (i + 1),
                    totalSlides = slider.slideCount;
                return '<span class="tmpcoder-slider-dot"></span>';
            },
            slidesToShow: sliderColumnsDesktop,
            responsive: [
                {
                    breakpoint: 10000,
                    settings: {
                        slidesToShow: sliderColumnsWideScreen,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsWideScreen && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 2399,
                    settings: {
                        slidesToShow: sliderColumnsDesktop,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsDesktop && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 1221,
                    settings: {
                        slidesToShow: sliderColumnsLaptop,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsLaptop && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: sliderColumnsTabletExtra,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsTabletExtra && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: sliderColumnsTablet,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsTablet && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 880,
                    settings: {
                        slidesToShow: sliderColumnsMobileExtra,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsMobileExtra && 'fade' === dataSlideEffect) ? true : false
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: sliderColumnsMobile,
                        slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
                        fade: (1 == sliderColumnsMobile && 'fade' === dataSlideEffect) ? true : false
                    }
                }
            ],
        });

        $(document).ready(function () {

            $scope.find('.slick-current').addClass('tmpcoder-slick-visible');

            var maxHeight = -1;

            // TMPCODER INFO -  needs condition check if there are any images
            if ($scope.find('.tmpcoder-slider-img').length !== 0) {

                // Wait for the first image to load
                $scope.find('.slick-current img').on('load', function() {
                    // Set the height of the slider container based on the first image
                    $scope.find('.tmpcoder-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                });

                // $scope.find('.tmpcoder-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());

                $scope.find('.tmpcoder-slider-arrow').on('click', function () {
                    console.log('works resize');
                    $scope.find('.tmpcoder-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                });

                $(window).smartresize(function () {
                    $scope.find('.tmpcoder-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                });
            }
        });

        function sliderVideoSize() {

            // var sliderWidth = $advancedSlider.find('.tmpcoder-slider-item').outerWidth(),
            //  sliderHeight = $advancedSlider.find('.tmpcoder-slider-item').outerHeight(),
            //  sliderRatio = sliderWidth / sliderHeight,
            //  iframeRatio = (16/9),
            //  iframeHeight,
            //  iframeWidth,
            //  iframeTopDistance = 0,
            //  iframeLeftDistance = 0;

            // if ( sliderRatio > iframeRatio ) {
            //  iframeWidth = sliderWidth;
            //  iframeHeight = iframeWidth / iframeRatio;
            //  iframeTopDistance = '-'+ ( iframeHeight - sliderHeight ) / 2 +'px';
            // } else {
            //  iframeHeight = sliderHeight;
            //  iframeWidth = iframeHeight * iframeRatio;
            //  iframeLeftDistance = '-'+ ( iframeWidth - sliderWidth ) / 2 +'px';
            // }

            // $advancedSlider.find('iframe').css({
            //  'display': 'block',
            //  'width': iframeWidth +'px',
            //  'height': iframeHeight +'px',
            //  'max-width': 'none',
            //  'position': 'absolute',
            //  'left': iframeLeftDistance +'',
            //  'top': iframeTopDistance +'',
            //  'text-align': 'inherit',
            //  'line-height':'0px',
            //  'border-width': '0px',
            //  'margin': '0px',
            //  'padding': '0px',
            // });

            $advancedSlider.find('iframe').attr('width', $scope.find('.tmpcoder-slider-item').width());
            $advancedSlider.find('iframe').attr('height', $scope.find('.tmpcoder-slider-item').height());

            var viewportWidth = $(window).outerWidth();

            var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
            var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
            var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
            var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
            var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
            var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

            var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

            [...$scope[0].classList].forEach(className => {
                if (className.startsWith('tmpcoder-slider-video-icon-size-')) {
                    $scope[0].classList.remove(className);
                }
            });

            // Mobile
            if (MobileResp >= viewportWidth && activeBreakpoints.mobile != null) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.mobile);
                // Mobile Extra
            } else if (MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.mobile_extra);
                // Tablet
            } else if (TabletResp >= viewportWidth && activeBreakpoints.tablet != null) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.tablet);
                // Tablet Extra
            } else if (TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.tablet_extra);
                // Laptop
            } else if (LaptopResp >= viewportWidth && activeBreakpoints.laptop != null) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.laptop);
                // Desktop
            } else if (wideScreenResp > viewportWidth) {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.desktop);
            } else {
                $scope.addClass('tmpcoder-slider-video-icon-size-' + videoBtnSize.widescreen);
            }
            // tmpcoder-slider-video-icon-size-
        }

        $(window).on('load resize', function () {
            sliderVideoSize();
        });

        $(document).ready(function () {
            // Handler when all assets (including images) are loaded
            if ($scope.find('.tmpcoder-advanced-slider').length) {
                $scope.find('.tmpcoder-advanced-slider').css('opacity', 1);
                autoplayVideo();
            }
        });

        function autoplayVideo() {
            $advancedSlider.find('.slick-current').each(function () {

                var videoSrc = $(this).find('.tmpcoder-slider-item').attr('data-video-src'),
                    videoAutoplay = $(this).find('.tmpcoder-slider-item').attr('data-video-autoplay');

                if ($(this).find('.tmpcoder-slider-video').length !== 1 && videoAutoplay === 'yes') {
                    if (videoSrc.includes('vimeo') || videoSrc.includes('youtube')) {
                        if (sliderColumnsDesktop == 1) {
                            // $(this).find('.tmpcoder-cv-inner').prepend('<div class="tmpcoder-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
                            $(this).find('.tmpcoder-cv-inner').prepend('<div class="tmpcoder-slider-video"><iframe src="' + videoSrc + '"  frameborder="0" allow="autoplay" allowfullscreen></iframe></div>');
                        } else {
                            $(this).find('.tmpcoder-cv-container').prepend('<div class="tmpcoder-slider-video"><iframe src="' + videoSrc + '" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
                        }
                        sliderVideoSize();
                    } else {
                        var videoMute = $(this).find('.tmpcoder-slider-item').attr('data-video-mute');
                        var videoControls = $(this).find('.tmpcoder-slider-item').attr('data-video-controls');
                        var videoLoop = $(this).find('.tmpcoder-slider-item').attr('data-video-loop');

                        $(this).find('.tmpcoder-cv-inner').prepend('<div class="tmpcoder-slider-video tmpcoder-custom-video"><video autoplay ' + videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' + 'src="' + videoSrc + '" width="100%" height="100%"></video></div>');

                        $advancedSlider.find('video').attr('width', $scope.find('.tmpcoder-slider-item').width());
                        $advancedSlider.find('video').attr('height', $scope.find('.tmpcoder-slider-item').height());
                    }

                    // TMPCODER INFO -  remove condition if not necessary
                    if ($(this).find('.tmpcoder-slider-content')) {
                        $(this).find('.tmpcoder-slider-content').fadeOut(300);
                    }
                }
            });
        }

        function slideAnimationOff() {
            if (sliderColumnsDesktop == 1) {
                $advancedSlider.find('.tmpcoder-slider-item').not('.slick-active').find('.tmpcoder-slider-animation').removeClass('tmpcoder-animation-enter');
            }
        }

        function slideAnimationOn() {
            $advancedSlider.find('.slick-active').find('.tmpcoder-slider-content').fadeIn(0);
            $advancedSlider.find('.slick-cloned').find('.tmpcoder-slider-content').fadeIn(0);
            $advancedSlider.find('.slick-current').find('.tmpcoder-slider-content').fadeIn(0);
            if (sliderColumnsDesktop == 1) {
                $advancedSlider.find('.slick-active').find('.tmpcoder-slider-animation').addClass('tmpcoder-animation-enter');
            }
        }

        slideAnimationOn();

        $advancedSlider.on('click', '.tmpcoder-slider-video-btn', function () {

            var currentSlide = $(this).closest('.slick-slide'),
                videoSrc = currentSlide.find('.tmpcoder-slider-item').attr('data-video-src');

            console.log(videoSrc);

            console.log(currentSlide, videoSrc);

            var allowFullScreen = '';

            if (videoSrc.includes('youtube')) {
                videoSrc += "&autoplay=1"; // Tell YouTube to autoplay
                allowFullScreen = 'allowfullscreen="allowfullscreen"';
            } else if (videoSrc.includes('vimeo')) {
                allowFullScreen = 'allowfullscreen';
            } else {
                var videoMute = currentSlide.find('.tmpcoder-slider-item').attr('data-video-mute');
                var videoControls = currentSlide.find('.tmpcoder-slider-item').attr('data-video-controls');
                var videoLoop = currentSlide.find('.tmpcoder-slider-item').attr('data-video-loop');

                if (currentSlide.find('.tmpcoder-slider-video').length !== 1) {
                    currentSlide.find('.tmpcoder-cv-container').prepend('<div class="tmpcoder-slider-video tmpcoder-custom-video"><video ' + videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' + 'src="' + videoSrc + '" width="100%" height="100%"></video></div>');

                    $advancedSlider.find('video').attr('width', $scope.find('.tmpcoder-slider-item').width());
                    $advancedSlider.find('video').attr('height', $scope.find('.tmpcoder-slider-item').height());

                    currentSlide.find('.tmpcoder-slider-content').fadeOut(300);

                    currentSlide.find('video')[0].play();
                }
                return;
            }

            if (currentSlide.find('.tmpcoder-slider-video').length !== 1) {
                // currentSlide.find('.tmpcoder-cv-container').prepend('<div class="tmpcoder-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"></iframe></div>');
                currentSlide.find('.tmpcoder-cv-container').prepend('<div class="tmpcoder-slider-video"><iframe src="' + videoSrc + '" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"' + allowFullScreen + '></iframe></div>');

                sliderVideoSize();
                currentSlide.find('.tmpcoder-slider-content').fadeOut(300);
            }

        });

        $advancedSlider.on({
            beforeChange: function () {
                $advancedSlider.find('.tmpcoder-slider-item').not('.slick-active').find('.tmpcoder-slider-video').remove();
                $advancedSlider.find('.tmpcoder-animation-enter').find('.tmpcoder-slider-content').fadeOut(300);
                slideAnimationOff();
            },
            afterChange: function (event, slick, currentSlide) {
                slideAnimationOn();
                autoplayVideo();
                $scope.find('.slick-slide').removeClass('tmpcoder-slick-visible');
                $scope.find('.slick-current').addClass('tmpcoder-slick-visible');
                $scope.find('.slick-current').nextAll().slice(0, sliderColumnsDesktop - 1).addClass('tmpcoder-slick-visible');
                $scope.find('.tmpcoder-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
            }
        });

        // Adjust Horizontal Pagination
        if ($scope.find('.slick-dots').length && $scope.hasClass('tmpcoder-slider-dots-horizontal')) {
            // Calculate Width
            var dotsWrapWidth = $scope.find('.slick-dots li').outerWidth() * $scope.find('.slick-dots li').length - parseInt($scope.find('.slick-dots li span').css('margin-right'), 10);

            // on Load
            if ($scope.find('.slick-dots').length) {
                $scope.find('.slick-dots').css('width', dotsWrapWidth);
            }

            // on Resize
            $(window).smartresize(function () {
                setTimeout(function () {
                    // Calculate Width
                    var dotsWrapWidth = $scope.find('.slick-dots li').outerWidth() * $scope.find('.slick-dots li').length - parseInt($scope.find('.slick-dots li span').css('margin-right'), 10);

                    // Set Width
                    $scope.find('.slick-dots').css('width', dotsWrapWidth);
                }, 300);
            });
        }

    }
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/tmpcoder-advanced-slider.default",
            widgetAdvancedSlider);
    });
})(jQuery);