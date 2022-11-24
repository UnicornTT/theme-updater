(function($){
    function getCloneCount($container) {
        const $slide = $container.find('.slide');
        const slideWidth = $slide.outerWidth();
        const sliderWidth = $container.outerWidth();
        const numberOfSlides = $slide.length;
        const requiredNumberOfSlides = Math.ceil(sliderWidth / slideWidth) * 2;
        const result = Math.ceil(requiredNumberOfSlides / numberOfSlides) - 1;

        return result < 1 ? 1 : result;
    }

    function destroySlider($container) {
        $container.find('.infiniteslide_clone').remove();

        if ($container.parent().hasClass('infiniteslide_wrap')) {
            $container.unwrap();
        }
    }

    function initializeBlock() {
        const $infiniteslider = $('.js-partners-infiniteslide');

        $infiniteslider.each(function () {
            const $container = $(this);

            destroySlider($container);
            $container.removeAttr('style');

            function sliderCall() {
                $container.infiniteslide({
                    'speed': 50, //speed this is px/min
                    'direction': 'left', //choose  up/down/left/right
                    'pauseonhover': true, //if true,stop onmouseover
                    'responsive': false, //width/height recalculation on window resize. child element's width/height define %/vw/vh,this set true.
                    'clone': getCloneCount($container) + 2 //if child elements are too few (elements can't "infinite"), set 2 or over.
                });
            }

            if(window.matchMedia('screen and (min-width: 1920px)').matches) {
                if ($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (140 * 2)) {
                    sliderCall();
                }
            } else {
                if(window.matchMedia('screen and (max-width: 1920px)').matches) {
                    if($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (340 * 2)) {
                        sliderCall();
                    }
                } else if(window.matchMedia('screen and (max-width: 1719.5px)').matches) {
                    if($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (224 * 2)) {
                        sliderCall();
                    }
                } else if(window.matchMedia('screen and (max-width: 1279.5px)').matches) {
                    if($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (140 * 2)) {
                        console.log($container.outerWidth(), $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (140 * 2))
                        sliderCall();
                    }
                } else if(window.matchMedia('screen and (max-width: 1024.5px)').matches) {
                    if($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (74 * 2)) {
                        sliderCall();
                    }
                } else if(window.matchMedia('screen and (max-width: 767.5px)').matches) {
                    if($container.outerWidth() <= $container.find('.slide').length * $container.find('.slide:first-child').outerWidth() + (55 * 2)) {
                        sliderCall();
                    }
                }
            }
        });

        //if (isNotFirstCall) return;
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();

        window.addEventListener('resize', function () {
            //initializeBlock(true);
            initializeBlock();
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-partners', adminInitializeBlock( initializeBlock, '.section-partners' ) );
    }

})(jQuery);