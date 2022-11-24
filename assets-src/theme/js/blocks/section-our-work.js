(function($){
    let initializeBlock = function( ) {

        if ($('.js-our-work-coverflow').length) {
            $('.js-our-work-coverflow').each(function () {
                let $slider = $(this);
                let slider = this;

                if (!!slider.swiper) {
                    slider.swiper.destroy(true, true);
                }

                let sliderCount = $slider.find('.swiper-slide').length;

                const swiperWorks = new Swiper($slider[0], {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    spaceBetween: 4,
                    loop: true,
                    loopedSlides: 15,
                    loopAdditionalSlides: 15,
                    speed: 1200,
                    //autoHeight: true,
                    slideToClickedSlide: true,
                    a11y: {
                        enabled: false
                    },
                    coverflowEffect: {
                        rotate: 0,
                        stretch: 0,
                        depth: 0,
                        modifier: 0,
                        slideShadows: false
                    },
                    breakpoints: {
                        1280: {
                            spaceBetween: 10,
                        },
                        1720: {
                            spaceBetween: 20,
                        }
                    },
                    navigation: {
                        nextEl: $slider.siblings('.swiper-controls').find('.swiper-button-next')[0],
                        prevEl: $slider.siblings('.swiper-controls').find('.swiper-button-prev')[0],
                    },
                });

                if( sliderCount < 2 ) {
                    slider.swiper.disable();
                    $slider.siblings('.swiper-controls').find('.swiper-button-next').addClass('swiper-button-lock');
                    $slider.siblings('.swiper-controls').find('.swiper-button-prev').addClass('swiper-button-lock');
                    $slider.find('.swiper-slide').removeClass('swiper-slide-duplicate-active');
                }
            });
        }

        if ($('.js-our-work-slider').length) {
            $('.js-our-work-slider').each(function () {
                let $slider = $(this);

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }

                let looping;
                let rewind;

                if( $slider.find('.swiper-slide').length > 3 && window.matchMedia('screen and (min-width: 1025px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( $slider.find('.swiper-slide').length > 4 && window.matchMedia('screen and (min-width: 2000px)').matches ) {
                    looping = true;
                    rewind = false;
                } else if( $slider.find('.swiper-slide').length > 2 && window.matchMedia('screen and (max-width: 1024.5px)').matches ) {
                    looping = true;
                    rewind = false;
                }  else {
                    looping = false;
                    rewind = true;
                }

                const swiperWorksDefault = new Swiper($slider[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 8,
                    rewind: rewind,
                    loop: looping,
                    loopedSlides: 15,
                    loopAdditionalSlides: 10,
                    slideToClickedSlide: false,
                    //centeredSlides: true,
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

        $('.modal-our-work').each(function () {
            let $modal = $(this);

            $modal.on('show.bs.modal', function (e) {
                let $sliderModalGallery = $modal.find('.js-our-work-gallery');
                let $sliderModalThumbs = $modal.find('.js-our-work-gallery-thumbs');

                let swiperModalThumbs = new Swiper($sliderModalThumbs[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 2,
                    rewind: true,
                    a11y: {
                        enabled: false
                    },
                    on: {
                        click: function(swiper, event) {
                            let $swiperSlideClicked = $(event.target).closest('.js-our-work-gallery-thumbs .swiper-slide');
                            if($swiperSlideClicked.length) {
                                let activeIndex = $swiperSlideClicked.attr('data-slide-index');
                                swiperModalThumbs.slideTo(activeIndex, 1000);
                            }
                        }
                    }
                });

                let swiperModalGallery = new Swiper($sliderModalGallery[0], {
                    slidesPerView: 1,
                    speed: 1000,
                    spaceBetween: 0,
                    rewind: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                    thumbs: {
                        swiper: swiperModalThumbs,
                    },
                    on: {
                        slideChange: function () {
                            if($sliderModalGallery.find('video').length) {
                                $sliderModalGallery.find('video').each(function () {
                                    $(this).first().trigger('pause');
                                    $(this).first()[0].currentTime = 0;
                                });
                            }

                            if($sliderModalGallery.find('iframe').length) {
                                $sliderModalGallery.find('iframe').each(function () {
                                    let iframeSrc = $(this).attr('src');
                                    $(this).attr('src', '');
                                    $(this).attr('src', iframeSrc);
                                });
                            }
                        },
                        activeIndexChange: function(swiper) {
                            let activeIndex = swiper.activeIndex;
                            swiperModalThumbs.slideTo(activeIndex, 1000);
                        }
                    }
                });

                $modal.on('hidden.bs.modal', function (e) {
                    swiperModalThumbs.destroy(true, true);
                    swiperModalGallery.destroy(true, true);

                    if($modal.find('video').length) {
                        $modal.find('video').each(function () {
                            $(this).first().trigger('pause');
                            $(this).first()[0].currentTime = 0;
                        });
                    }

                    if($modal.find('iframe').length) {
                        $modal.find('iframe').each(function () {
                            let iframeSrc = $(this).attr('src');
                            $(this).attr('src', '');
                            $(this).attr('src', iframeSrc);
                        });
                    }
                });
            });
        });
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
        window.acf.addAction( 'render_block_preview/type=section-our-work', adminInitializeBlock( initializeBlock, '.section-our-work' ) );
    }

})(jQuery);