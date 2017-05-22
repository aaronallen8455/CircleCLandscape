/**
 * Created by Aaron Allen on 5/21/2017.
 */
(function ($) {
    'use strict';

    var toggleEle = $('.nav-toggle'),
        htmlEle = $('html'),
        swipeArea = $('.responsive-navigation');

    toggleEle.click(toggle);

    swipeArea.on('touchstart', swipe);

    function toggle (e) {
        // open or close the menu
        if (htmlEle.hasClass('nav-open')) {
            htmlEle.removeClass('nav-open');
            setTimeout(function () {
                htmlEle.removeClass('nav-before-open');
            }, 300);
        } else {
            htmlEle.addClass('nav-before-open');
            setTimeout(function () {
                htmlEle.addClass('nav-open');
            }, 42);
        }
    }

    function swipe (e) {
        var xCoord = e.originalEvent.touches[0].clientX;

        // close the menu on swipe left
        swipeArea.on('touchmove', function (e) {
            var x = e.originalEvent.touches[0].clientX;
            if (xCoord - x >= 20) {
                swipeArea.off('touchmove');
                toggle();
            } else if (x > xCoord) {
                xCoord = x;
            }
        });

        swipeArea.on('touchend', function () {
            swipeArea.off('touchmove');
        });
    }
})(jQuery);