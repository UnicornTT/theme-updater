(function($){
    let initializeBlock = function() {
        if ($('.js-swiper-testimonials-gallery').length) {
            if ($('.js-swiper-testimonials-gallery').length) {
                $('.js-swiper-testimonials-gallery').each(function () {

                    let $slider = $(this);

                    if (!!$slider[0].swiper) {
                        $slider[0].swiper.destroy(true, true);
                        $slider.parents('.section-testimonials__default-testimonials').find('.swiper-button-next').removeClass('swiper-button-lock').removeAttr('disabled');
                        $slider.parents('.section-testimonials__default-testimonials').find('.swiper-button-prev').removeClass('swiper-button-lock').removeAttr('disabled');
                    }

                    let slidesCount;
                    let looping;

                    if( $slider.find('.swiper-slide').length > 5 && window.matchMedia('screen and (min-width: 768px)').matches  ) {
                        $slider.addClass('swiper-has-padding');
                        slidesCount = 4;
                        looping = true;
                    } else if( $slider.find('.swiper-slide').length > 4 && window.matchMedia('screen and (max-width: 767.5px)').matches ) {
                        $slider.addClass('swiper-has-padding');
                        slidesCount = 4;
                        looping = true;
                    }  else {
                        $slider.removeClass('swiper-has-padding');
                        slidesCount = 5;
                        looping = false;
                    }

                    const swiperTestimonialsGallery = new Swiper($slider[0], {
                        slidesPerView: slidesCount - 1,
                        slidesPerGroup: 1,
                        spaceBetween: 3,
                        loop: looping,
                        loopedSlides: 6,
                        loopAdditionalSlides: 6,
                        a11y: {
                            enabled: false
                        },
                        speed: 1000,
                        slideToClickedSlide: true,
                        watchOverflow: true,
                        breakpoints: {
                            768: {
                                slidesPerView: slidesCount,
                                slidesPerGroup: 2,
                                spaceBetween: 4,
                            },
                            1280: {
                                slidesPerView: slidesCount,
                                slidesPerGroup: 2,
                                spaceBetween: 10,
                            },
                        },
                        navigation: {
                            nextEl: $slider.parents('.gallery-wrapper').find('.swiper-button-next')[0],
                            prevEl: $slider.parents('.gallery-wrapper').find('.swiper-button-prev')[0],
                        },
                    });
                });
            }
        }

        if ($('.js-swiper-testimonials-default').length) {
            $('.js-swiper-testimonials-default').each(function () {

                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let looping;

                if($slider.find('.swiper-slide').length > 1) {
                    looping = true;
                } else {
                    looping = false;
                }

                const swiperTestimonialsGallery = new Swiper($slider[0], {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 0,
                    loop: looping,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,
                    speed: 1000,
                    effect: 'fade',
                    simulateTouch: false,
                    fadeEffect: {
                        crossFade: true,
                    },
                    navigation: {
                        nextEl: $slider.parents('.section-testimonials__default-testimonials').find('.swiper-button-next')[0],
                        prevEl: $slider.parents('.section-testimonials__default-testimonials').find('.swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $slider.parents('.section-testimonials__default-testimonials').find('.swiper-pagination')[0],
                        type: 'fraction',
                    }
                });
            });
        }

        if ($('.js-swiper-testimonials-custom').length) {
            $('.js-swiper-testimonials-custom').each(function () {

                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let slidesCount = $slider.find('.swiper-slide').length;

                const swiperTestimonialsCustom = new Swiper($slider[0], {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 0,
                    loop: true,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,
                    a11y: {
                        enabled: false
                    },
                    speed: 1000,
                    effect: 'fade',
                    centeredSlides: true,
                    observer: true,
                    observeParents: true,
                    normalizeSlideIndex: true,
                    simulateTouch: false,
                    fadeEffect: {
                        crossFade: true,
                    },
                    navigation: {
                        nextEl: $slider.parents('.section-testimonials__main').find('.swiper-button-next')[0],
                        prevEl: $slider.parents('.section-testimonials__main').find('.swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $slider.parents('.section-testimonials__main').find('.swiper-pagination')[0],
                        type: 'fraction',
                    },
                    on: {
                        init: function () {
                            let swiper = this;
                            swiperTestimonialsCustomChange(swiper);
                            let $btnPrev = $(this.el).parents('.section-testimonials__main').find('.js-swiper-testimonials-custom-prev');
                            let $btnNext = $(this.el).parents('.section-testimonials__main').find('.js-swiper-testimonials-custom-next');

                            if(slidesCount > 1) {
                                $btnPrev.on('click', function (e) {
                                    swiper.slidePrev(1000);
                                });

                                $btnNext.on('click', function (e) {
                                    swiper.slideNext(1000);
                                });
                            }
                        },
                        slideChangeTransitionStart: function () {
                            swiperTestimonialsCustomChange(this);
                        },
                    }
                });

                if(slidesCount <= 1) {
                    $slider.parents('.section-testimonials__main').find('.swiper-button-next').addClass('swiper-button-lock');
                    $slider.parents('.section-testimonials__main').find('.swiper-button-prev').addClass('swiper-button-lock');
                    $slider.parents('.section-testimonials__main').find('.swiper-button-image').addClass('disabled');
                } else {
                    $slider.parents('.section-testimonials__main').find('.swiper-button-next').removeClass('swiper-button-lock');
                    $slider.parents('.section-testimonials__main').find('.swiper-button-prev').removeClass('swiper-button-lock');
                    $slider.parents('.section-testimonials__main').find('.swiper-button-image').removeClass('disabled');
                }
            });
        }

        function swiperTestimonialsCustomChange(swiper) {
            let $prev = $(swiper.el).find('.swiper-slide-prev');
            let prevAuthor = $prev.find('.testimonials-card__author').text();
            let prevImgSrc;

            if($prev.find('img').length) {
                prevImgSrc = $prev.find('img').attr('src');
            } else if($prev.find('video').length) {
                prevImgSrc = $prev.find('video').attr('poster');
            } else {
                prevImgSrc = '';
            }

            let $next = $(swiper.el).find('.swiper-slide-next');
            let nextAuthor = $next.find('.testimonials-card__author').text();
            let nextImgSrc;

            if($next.find('img').length) {
                nextImgSrc = $next.find('img').attr('src');
            } else if($next.find('video').length) {
                nextImgSrc = $next.find('video').attr('poster');
            } else {
                nextImgSrc = '';
            }

            let $btnPrev = $(swiper.el).parents('.section-testimonials__main').find('.js-swiper-testimonials-custom-prev');
            let $btnNext = $(swiper.el).parents('.section-testimonials__main').find('.js-swiper-testimonials-custom-next');

            $btnPrev.addClass('is-animating');
            $btnNext.addClass('is-animating');

            setTimeout(function() {
                $btnPrev.find('img').attr('src', prevImgSrc);
                $btnPrev.find('.swiper-button-image__author').html(prevAuthor);

                $btnNext.find('img').attr('src', nextImgSrc);
                $btnNext.find('.swiper-button-image__author').html(nextAuthor);

                $btnPrev.removeClass('is-animating');
                $btnNext.removeClass('is-animating');
            }, 500);
        }

        if ($('.js-swiper-reviews').length) {
            $('.js-swiper-reviews').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                let looping;
                let rewind;
                let slideCount = $slider.children('.swiper-wrapper').children('.swiper-slide').length;

                if( slideCount > 3 && window.matchMedia('screen and (min-width: 1025px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( slideCount > 4 && window.matchMedia('screen and (min-width: 2000px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( slideCount > 2 && window.matchMedia('screen and (max-width: 1024.5px)').matches ) {
                    looping = true;
                    rewind = false;
                }  else {
                    looping = false;
                    rewind = true;
                }

                const swiperReviews = new Swiper($slider[0], {
                    slidesPerView: 'auto',
                    slidesPerGroup: 1,
                    speed: 1000,
                    spaceBetween: 8,
                    slideToClickedSlide: true,
                    //centeredSlides: true,
                    rewind: rewind,
                    loop: looping,
                    loopedSlides: 10,
                    simulateTouch: false,
                    a11y: {
                        enabled: false
                    },
                    navigation: {
                        nextEl: $slider.find('.swiper-button-next')[0],
                        prevEl: $slider.find('.swiper-button-prev')[0],
                    },
                    breakpoints: {
                        768: {
                            spaceBetween: 12,
                        },
                        1280: {
                            spaceBetween: 24,
                        },
                        1720: {
                            spaceBetween: 30,
                        }
                    }
                });

                if($slider.find('.swiper-button-prev').hasClass('swiper-button-lock') && $slider.find('.swiper-button-next').hasClass('swiper-button-lock')) {
                    $slider.find('.swiper-controls').addClass('d-none');
                } else {
                    $slider.find('.swiper-controls').removeClass('d-none');
                }
            });
        }

        if ($('.js-swiper-reviews-gallery').length) {
            $('.js-swiper-reviews-gallery').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                const swiperReviewsGallery = new Swiper($slider[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 4,
                    slideToClickedSlide: true,
                    centeredSlides: false,
                    rewind: true,
                    a11y: {
                        enabled: false
                    },
                    mousewheel: {
                        releaseOnEdges: true,
                    },
                });
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
        window.acf.addAction( 'render_block_preview/type=section-testimonials', adminInitializeBlock( initializeBlock, '.section-testimonials' ) );
    }

})(jQuery);