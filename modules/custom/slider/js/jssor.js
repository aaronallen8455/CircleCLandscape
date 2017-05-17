/**
 * Created by Aaron Allen on 5/16/2017.
 */

!(function ($) {
    'use strict';

    var jssor_1_SlideshowTransitions = [
        {$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Opacity:2},
        {$Duration:1000,$Delay:30,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Assembly:2049,$Easing:$Jease$.$OutQuad},
        {$Duration:1000,$Delay:80,$Cols:8,$Rows:4,$Clip:15,$SlideOut:true,$Easing:$Jease$.$OutQuad},
        {$Duration:800,y:1,$Delay:80,$Cols:12,$Formation:$JssorSlideshowFormations$.$FormationStraight,$Assembly:513,$Easing:{$Top:$Jease$.$InCubic,$Opacity:$Jease$.$OutQuad},$Opacity:2},
        {$Duration:400,$Delay:100,$Cols:10,$Clip:2,$Formation:$JssorSlideshowFormations$.$FormationStraight},
        {$Duration:600,x:-1,y:1,$Delay:30,$Cols:8,$Rows:4,$Formation:$JssorSlideshowFormations$.$FormationStraightStairs,$Easing:{$Left:$Jease$.$InQuart,$Top:$Jease$.$InQuart,$Opacity:$Jease$.$Linear},$Opacity:2}
    ];

    var jssor_1_options = {
        $AutoPlay: 1,
        $SlideshowOptions: {
            $Class: $JssorSlideshowRunner$,
            $Transitions: jssor_1_SlideshowTransitions
        },
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,
            $ChanceToShow: 1
        },
        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$,
            $ChanceToShow: 1
        }
    };

    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

    /*responsive code begin*/
    /*remove responsive code if you don't want the slider scales while window resizing*/
    function ScaleSlider() {
        var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            jssor_1_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    /*responsive code end*/
})(jQuery);