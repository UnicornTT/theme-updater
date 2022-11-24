(function($){
    let winWdth = $(window).width();

    let initializeBlock = function() {

        if (!$('.section-our-history').length) {
            return false;
        } else {
            $('.section-our-history').each(function () {
                let $slider = $(this).find('.js-our-history-slider');
                let $rolldate = $(this).find('.js-slider-rolldate');

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }
                if (!!$rolldate[0].swiper) {
                    $rolldate[0].swiper.destroy(true, true);
                }

                let looping;
                let rewind;
                let direction;

                if( $slider.find('.swiper-slide').length > 2 ) {
                    looping = true;
                    rewind = false;
                } else {
                    looping = false;
                    rewind = true;
                }

                if( window.matchMedia('screen and (min-width: 1280px)').matches ) {
                    direction = 'vertical';
                } else {
                    direction = 'horizontal';
                }

                const swiperHistory = new Swiper($slider[0], {
                    speed: 1000,
                    effect: 'fade',
                    slidesPerView: 1,
                    spaceBetween: 0,
                    fadeEffect: {
                        crossFade: true,
                    },
                    rewind: rewind,
                    loop: looping,
                    loopedSlides: 10,
                    simulateTouch: false,
                });

                const swiperRolldate = new Swiper($rolldate[0], {
                    speed: 1000,
                    slidesPerView: 5,
                    //spaceBetween: 36,
                    spaceBetween: 0,
                    direction: direction,
                    rewind: rewind,
                    loop: looping,
                    loopedSlides: 10,
                    slideToClickedSlide: true,
                    centeredSlides: true,
                    centerInsufficientSlides: false,
                    navigation: {
                        nextEl: $slider.parents('.section-our-history').find('.swiper-button-next')[0],
                        prevEl: $slider.parents('.section-our-history').find('.swiper-button-prev')[0],
                    },
                });

                swiperHistory.on('slideChange', function () {
                    let activeIndex = swiperHistory.activeIndex;
                    swiperRolldate.slideTo(activeIndex);
                });

                swiperRolldate.on('slideChange', function () {
                    let activeIndex = swiperRolldate.activeIndex;
                    swiperHistory.slideTo(activeIndex);
                });
            });
        }
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();

        window.addEventListener('resize', function () {
            if(winWdth != $(window).width()) {
                initializeBlock();
                winWdth = $(window).width();
            }
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-our-history', adminInitializeBlock( initializeBlock, '.section-our-history' ) );
    }

})(jQuery);