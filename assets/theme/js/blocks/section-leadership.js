(function($){
    let initializeBlock = function() {
        if ($('.js-leadership-slider').length) {
            $('.js-leadership-slider').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let looping,
                    rewind,
                    centeredSlides,
                    customPagination,
                    $swiperSlide = $slider.find('.swiper-slide'),
                    $swiperNavigation =  $slider.siblings('.section-leadership__slider-nav'),
                    $swiperButtonPrev = $swiperNavigation.find('.swiper-button-prev'),
                    $swiperButtonNext = $swiperNavigation.find('.swiper-button-next');

                    $swiperButtonPrev.removeClass('swiper-button-lock');
                    $swiperButtonPrev.removeAttr('disabled');
                    $swiperButtonNext.removeClass('swiper-button-lock');
                    $swiperButtonNext.removeAttr('disabled');

                if( $swiperSlide.length > 1 && window.matchMedia('screen and (max-width: 767.5px)').matches ) {
                    looping = true;
                    rewind = false;
                    centeredSlides = false;
                    customPagination = false;
                } else if( $swiperSlide.length > 2 && window.matchMedia('screen and (max-width: 1024.5px)').matches ) {
                    looping = true;
                    rewind = false;
                    centeredSlides = false;
                    customPagination = false;
                } else if( $swiperSlide.length > 3 && window.matchMedia('screen and (min-width: 1025px)').matches ) {
                    looping = true;
                    rewind = false;
                    centeredSlides = false;
                    customPagination = false;
                } else {
                    looping = false;
                    rewind = true;
                    centeredSlides = true;
                    customPagination = true;
                }

                const swiperLeadership = new Swiper($slider[0], {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 20,
                    speed: 1000,
                    autoHeight: true,
                    slideToClickedSlide: true,
                    centeredSlides: false,
                    rewind: rewind,
                    loop: looping,
                    centerInsufficientSlides: centeredSlides,
                    loopAdditionalSlides: 10,
                    loopedSlides: 10,
                    simulateTouch: false,
                    a11y: {
                        enabled: false
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1025: {
                            spaceBetween: 10,
                            slidesPerView: 3,
                        },
                    },
                    navigation: {
                        nextEl: $swiperNavigation.find('.swiper-button-next')[0],
                        prevEl: $swiperNavigation.find('.swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $swiperNavigation.find('.section-leadership__slider-pagination')[0],
                        type: 'fraction',
                    },
                    on: {
                        afterInit: function() {
                            if(customPagination) {
                                setTimeout(()=> {
                                    $swiperNavigation.find('.swiper-pagination-total').text($swiperSlide.length)
                                },300)
                            }
                        }
                    }
                });
            });
        }

        let swiperLeadershipMedia,
            swiperLeadershipV2;

        if($('.js-leadership-slider-media').length) {
            $('.js-leadership-slider-media').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let looping;
                let rewind;
                let loopedSlides = 5;

                let countSlides = $slider.find('.swiper-slide').length;

                if( countSlides > 1 && window.matchMedia('screen and (max-width: 1200px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( countSlides > 1 && window.matchMedia('screen and (max-width: 2400px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( countSlides > 1 && window.matchMedia('screen and (min-width: 2400px)').matches ) {
                    looping = true;
                    rewind = false;
                    loopedSlides = 8;
                } else {
                    looping = false;
                    rewind = true;
                }

                swiperLeadershipMedia = new Swiper($slider[0], {
                    observer: true,
                    observeParents: true,
                    slidesPerView: 'auto',
                    slidesPerGroup: 1,
                    spaceBetween: 20,
                    speed: 1000,
                    autoHeight: true,
                    slideToClickedSlide: true,
                    centeredSlides: false,
                    loop: looping,
                    rewind: rewind,
                    loopedSlides: loopedSlides,
                    loopAdditionalSlides: loopedSlides,
                    loopedSlidesLimit: false,
                    a11y: {
                        enabled: false
                    },
                    on: {
                        slideChangeTransitionStart: function (swiper) {
                            $('.section-leadership--style-v2 .js-leadership-slider-media').find('.swiper-slide-active').removeClass('hide-animation');
                        },
                        slideChangeTransitionEnd: function (swiper) {
                            $('.section-leadership--style-v2 .js-leadership-slider-media').find('.swiper-slide-active').addClass('hide-animation');
                        }
                    }
                });

                if(countSlides === 1) {
                    $('.section-leadership--style-v2 .js-leadership-slider-media').find('.swiper-slide-active').addClass('hide-animation');
                }
            });
        }


        if( $('.js-leadership-slider-v2').length ) {
            $('.js-leadership-slider-v2').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let looping;
                let rewind;
                let loopedSlides = 5;

                if( $slider.find('.swiper-slide').length > 1 && window.matchMedia('screen and (max-width: 1200px)').matches ) {
                    looping = true;
                    rewind = false;
                }  else if( $slider.find('.swiper-slide').length > 1 && window.matchMedia('screen and (max-width: 2400px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( $slider.find('.swiper-slide').length > 1  && window.matchMedia('screen and (min-width: 2400px)').matches ) {
                    looping = true;
                    rewind = false;
                    loopedSlides = 8;
                } else {
                    looping = false;
                    rewind = true;
                }

                swiperLeadershipV2 = new Swiper($slider[0], {
                    observer: true,
                    observeParents: true,
                    slidesPerView: 1,
                    spaceBetween: 0,
                    speed: 1000,
                    autoHeight: true,
                    slideToClickedSlide: true,
                    centeredSlides: false,
                    loop: looping,
                    rewind: rewind,
                    loopedSlides: loopedSlides,
                    loopAdditionalSlides: loopedSlides,
                    loopedSlidesLimit: false,
                    a11y: {
                        enabled: false,
                    },
                    navigation: {
                        nextEl: $slider.find('.swiper-button-next')[0],
                        prevEl: $slider.find('.swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $slider.find('.swiper-pagination')[0],
                        type: 'fraction',
                    },
                });
            });
        }

        if($('.js-leadership-slider-v2').length && $('.js-leadership-slider-media').length) {
            swiperLeadershipMedia.controller.control = swiperLeadershipV2;
            swiperLeadershipV2.controller.control = swiperLeadershipMedia;
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
        window.acf.addAction( 'render_block_preview/type=section-leadership', adminInitializeBlock( initializeBlock, '.section-leadership' ) );
    }

})(jQuery);