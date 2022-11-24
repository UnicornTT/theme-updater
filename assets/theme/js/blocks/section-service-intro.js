(function($){
    let initializeBlock = function( ) {

        if($('.js-section-service-intro') && $('.js-section-service-intro-thumbs')) {            

            let $sliderServiceIntro = $('.js-section-service-intro');
            let $sliderServiceIntroThumbs = $('.js-section-service-intro-thumbs');

            let sliderServiceIntroThumbs = new Swiper($sliderServiceIntroThumbs[0], {
                slidesPerView: 'auto',
                speed: 1000,
                spaceBetween: 4,
                centerInsufficientSlides: true,
                slideToClickedSlide: true,
                rewind: true,
                a11y: {
                    enabled: false
                },
                breakpoints: {
                    768: {
                        spaceBetween: 10,
                    },
                },
                on: {
                    init: function(sliderServiceIntroThumbs) {
                        if(window.matchMedia('(min-width: 1280px)').matches) {
                            sliderServiceIntroThumbs.changeDirection('vertical')
                        } else {
                            sliderServiceIntroThumbs.changeDirection('horizontal')
                        }
                    },
                    resize: function(sliderServiceIntroThumbs) {
                        if(window.matchMedia('(min-width: 1280px').matches) {
                            sliderServiceIntroThumbs.changeDirection('vertical')
                        } else {
                            sliderServiceIntroThumbs.changeDirection('horizontal')
                        }
                    },
                    click: function(swiper, event) {
                        let $swiperSlideClicked = $(event.target).closest('.js-section-service-intro-thumbs .swiper-slide');
                        if($swiperSlideClicked.length) {
                            let activeIndex = $swiperSlideClicked.attr('data-slide-index');
                            sliderServiceIntroThumbs.slideTo(activeIndex, 1000);
                        }
                    }
                },
             });

            let sliderServiceIntro = new Swiper($sliderServiceIntro[0], {
                slidesPerView: 1,
                speed: 1000,
                spaceBetween: 0,
                rewind: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                thumbs: {
                    swiper: sliderServiceIntroThumbs,
                },
                breakpoints: {
                    1280: {
                        spaceBetween: 10,
                    },
                },
                on: {
                    activeIndexChange: function(swiper) {
                        let activeIndex = swiper.activeIndex;
                        sliderServiceIntroThumbs.slideTo(activeIndex, 1000);
                    }
                },
            });
        }
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();

        window.addEventListener('resize', function () {
            initializeBlock();
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-service-intro', adminInitializeBlock( initializeBlock, '.section-intro' ) );
    }

})(jQuery);