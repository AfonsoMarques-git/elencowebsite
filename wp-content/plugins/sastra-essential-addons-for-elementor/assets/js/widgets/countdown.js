(function ($) {
    "use strict";

    /* -------- Countdown Timer ------- */

    const Countdown = function ($scope, $) {

        var countDownWrap = $scope.find('.tmpcoder-countdown-wrap'),
            countDownInterval = null,
            dataInterval = countDownWrap.data('interval'),
            dataShowAgain = countDownWrap.data('show-again'),
            endTime = null,
            originalDurationMs = null, // Store the FULL configured duration for restart
            isEvergreen = false,
            widgetID = null,
            evergreenSettings = null;
        console.log(countDownWrap);
        // Normalize dataInterval to number
        dataInterval = parseFloat(dataInterval) || 0;

        // Calculate original duration in milliseconds
        // For evergreen: dataInterval is duration in seconds
        // For due-date: dataInterval is Unix timestamp in seconds
        if ('evergreen' === countDownWrap.data('type')) {
            isEvergreen = true;
            originalDurationMs = dataInterval * 1000; // Convert seconds to milliseconds
        } else {
            // For due-date, calculate from the timestamp
            var dueDateTimestamp = dataInterval * 1000; // Convert to milliseconds
            var nowTimestamp = (new Date()).getTime();
            originalDurationMs = Math.max(dueDateTimestamp - nowTimestamp, 0);
        }

        // Evergreen End Time
        if (isEvergreen) {
            var evergreenDate = new Date();
            widgetID = $scope.attr('data-id');
            evergreenSettings = JSON.parse(localStorage.getItem('TmpcoderCountDownSettings')) || {};

            // End Time - normalize to timestamp (number)
            var storedEndTime = null;
            if (evergreenSettings.hasOwnProperty(widgetID)) {
                if (Object.keys(evergreenSettings).length === 0 || dataInterval !== evergreenSettings[widgetID].interval) {
                    // Interval changed, create new timer
                    endTime = evergreenDate.getTime() + (dataInterval * 1000);
                } else {
                    // Use stored endTime, but ensure it's a valid timestamp
                    storedEndTime = evergreenSettings[widgetID].endTime;
                    if (storedEndTime && typeof storedEndTime === 'number' && storedEndTime > 0) {
                        endTime = storedEndTime;
                    } else {
                        // Invalid stored time, create new
                        endTime = evergreenDate.getTime() + (dataInterval * 1000);
                    }
                }
            } else {
                endTime = evergreenDate.getTime() + (dataInterval * 1000);
            }

            // Check if timer has expired beyond show-again delay
            var nowTs = evergreenDate.getTime();
            if (endTime + parseFloat(dataShowAgain || 0) < nowTs) {
                endTime = nowTs + (dataInterval * 1000);
            }

            // Settings
            evergreenSettings[widgetID] = {
                interval: dataInterval,
                endTime: endTime // Store as timestamp (number)
            };

            // Save Settings in Browser
            localStorage.setItem('TmpcoderCountDownSettings', JSON.stringify(evergreenSettings));
        } else {
            // Due-date timer: dataInterval is Unix timestamp in seconds
            endTime = dataInterval * 1000; // Convert to milliseconds (timestamp)
        }

        // Ensure endTime is a valid number (timestamp in milliseconds)
        if (isNaN(endTime) || endTime <= 0 || typeof endTime !== 'number') {
            // Fallback: set endTime to 1 hour from now if invalid
            var fallbackNow = (new Date()).getTime();
            endTime = fallbackNow + (60 * 60 * 1000);
            originalDurationMs = 60 * 60 * 1000;
        }
        
        // Final validation: ensure originalDurationMs is valid
        if (!originalDurationMs || isNaN(originalDurationMs) || originalDurationMs <= 0) {
            if (isEvergreen && dataInterval) {
                originalDurationMs = dataInterval * 1000;
            } else {
                originalDurationMs = 60 * 60 * 1000; // 1 hour fallback
            }
        }

        // Start CountDown
        if (!editorCheck()) { //tmp
        }
        // Init on Load
        initCountDown();

        // Start CountDown
        countDownInterval = setInterval(initCountDown, 1000);

        function initCountDown() {
            // Ensure endTime is a number (timestamp in milliseconds)
            var endTimeTs = (typeof endTime === 'number') ? endTime : (endTime instanceof Date ? endTime.getTime() : parseFloat(endTime) || 0);
            var nowTs = (new Date()).getTime();
            var timeLeft = endTimeTs - nowTs;
            
            // Validate timeLeft to prevent NaN
            if (isNaN(timeLeft)) {
                timeLeft = 0;
            }
            
            var dataActions = countDownWrap.data('actions');
            var shouldRestart = dataActions && Object.prototype.hasOwnProperty.call(dataActions, 'restart-timer');

            // If timer expired and restart is enabled, restart immediately without showing zeros
            if (timeLeft < 0 && shouldRestart && !editorCheck()) {
                clearInterval(countDownInterval);
                restartTimer();
                return;
            }

            var numbers = {
                days: Math.floor(timeLeft / (1000 * 60 * 60 * 24)),
                hours: Math.floor((timeLeft / (1000 * 60 * 60)) % 24),
                minutes: Math.floor((timeLeft / 1000 / 60) % 60),
                seconds: Math.floor((timeLeft / 1000) % 60)
            };

            // Validate numbers to prevent NaN
            if (isNaN(numbers.days) || isNaN(numbers.hours) || isNaN(numbers.minutes) || isNaN(numbers.seconds)) {
                numbers = {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                };
            }

            if (numbers.days < 0 || numbers.hours < 0 || numbers.minutes < 0 || numbers.seconds < 0) {
                numbers = {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                };
            }

            $scope.find('.tmpcoder-countdown-number').each(function () {
                var itemType = $(this).attr('data-item');
                var number = numbers[itemType];

                // Ensure number is valid
                if (isNaN(number)) {
                    number = 0;
                }

                if (1 === number.toString().length) {
                    number = '0' + number;
                }

                $(this).text(number);

                // Labels
                var labels = $(this).next();

                if (labels.length) {
                    if (!$(this).hasClass('tmpcoder-countdown-seconds')) {
                        var labelText = labels.data('text');

                        if (labelText && typeof labelText === 'object') {
                            if ('01' == number) {
                                labels.text(labelText.singular);
                            } else {
                                labels.text(labelText.plural);
                            }
                        }
                    }
                }
            });

            // Stop Counting (only if restart is not enabled)
            if (timeLeft < 0 && !shouldRestart) {
                clearInterval(countDownInterval);

                // Actions
                expiredActions();
            }
        }

        function restartTimer() {
            // Remove any previously injected message
            $scope.find('.tmpcoder-countdown-message').remove();
            // Ensure timer is visible again
            countDownWrap.show();
            
            // Re-arm endTime with FULL original configured duration
            var nowTs = (new Date()).getTime();
            var restartDurationMs;
            
            // Always use the original configured duration for restart
            if (originalDurationMs && originalDurationMs > 0) {
                restartDurationMs = originalDurationMs;
            } else if (isEvergreen && dataInterval) {
                // Fallback for evergreen: use configured interval
                restartDurationMs = dataInterval * 1000;
            } else {
                // Last resort fallback: use 1 hour
                restartDurationMs = 60 * 60 * 1000;
            }
            
            // Set new endTime as timestamp (number)
            endTime = nowTs + restartDurationMs;
            
            // For evergreen timers, update localStorage with new endTime
            if (isEvergreen && widgetID) {
                if (!evergreenSettings) {
                    evergreenSettings = JSON.parse(localStorage.getItem('TmpcoderCountDownSettings')) || {};
                }
                evergreenSettings[widgetID] = {
                    interval: dataInterval,
                    endTime: endTime // Store as timestamp (number)
                };
                localStorage.setItem('TmpcoderCountDownSettings', JSON.stringify(evergreenSettings));
            }
            
            // Restart the countdown interval immediately
            countDownInterval = setInterval(initCountDown, 1000);
            // Call immediately to update display right away
            initCountDown();
        }

        function expiredActions() {
            var dataActions = countDownWrap.data('actions');

            if (!editorCheck()) {
                // If restart requested, do it first and skip other actions
                if (dataActions && Object.prototype.hasOwnProperty.call(dataActions, 'restart-timer')) {
                    restartTimer();
                    return;
                }

                if (dataActions.hasOwnProperty('hide-timer')) {
                    countDownWrap.hide();
                }

                if (dataActions.hasOwnProperty('hide-element')) {
                    $(dataActions['hide-element']).hide();
                }

                if (dataActions.hasOwnProperty('message')) {
                    if (!$scope.find('.tmpcoder-countdown-message').length) {
                        console.log('message1');
                        // Sanitize message to prevent XSS
                        var sanitizedMessage = sanitizeHTMLContent(dataActions['message']);
                        countDownWrap.after('<div class="tmpcoder-countdown-message">' + sanitizedMessage + '</div>');
                    }
                }

                if (dataActions.hasOwnProperty('redirect')) {
                    window.location.href = TmpcodersanitizeURL(dataActions['redirect']);
                }

                if (dataActions.hasOwnProperty('load-template')) {
                    countDownWrap.next('.elementor').show();
                }
            }
        }
    }

    // Add this helper function to sanitize HTML content
    const sanitizeHTMLContent = function(html) {
        // Create a temporary DOM element
        var tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Remove all script tags
        var scripts = tempDiv.getElementsByTagName('script');
        while(scripts.length > 0) {
            scripts[0].parentNode.removeChild(scripts[0]);
        }

        // Remove all iframe tags
        var iframes = tempDiv.getElementsByTagName('iframe');
        while(iframes.length > 0) {
            iframes[0].parentNode.removeChild(iframes[0]);
        }
        
        // Find all elements to remove potential malicious attributes
        var allElements = tempDiv.getElementsByTagName('*');
        for (var i = 0; i < allElements.length; i++) {
            // Remove event handler attributes
            var attrs = allElements[i].attributes;
            for (var j = attrs.length - 1; j >= 0; j--) {
                var attrName = attrs[j].name;
                // Remove all on* event handlers
                if (attrName.substring(0, 2) === 'on') {
                    allElements[i].removeAttribute(attrName);
                }
                // Remove javascript: URLs
                if (attrName === 'href' || attrName === 'src') {
                    var value = attrs[j].value;
                    if (value.toLowerCase().indexOf('javascript:') === 0) {
                        allElements[i].removeAttribute(attrName);
                    }
                }
            }
        }
        
        return tempDiv.innerHTML;
    }

    /* -------- Countdown Timer End ------- */

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tmpcoder-countdown.default', Countdown);
    });
})(jQuery);

