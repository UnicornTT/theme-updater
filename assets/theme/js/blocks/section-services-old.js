(function($){
    let initializeBlock = function( ) {

        if ($('.js-services-slider').length) {
            $('.js-services-slider').each(function () {
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

                const swiperServicesDefault = new Swiper($slider[0], {
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
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
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

        $('.modal-services').each(function () {
            let $modal = $(this);

            $modal.on('show.bs.modal', function (e) {
                let $sliderModalGallery = $modal.find('.js-services-gallery');
                let $sliderModalThumbs = $modal.find('.js-services-gallery-thumbs');

                let swiperModalThumbs = new Swiper($sliderModalThumbs[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 2,
                    rewind: true,
                    freeMode: true,
                    watchSlidesProgress: true,
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
                });

                $modal.on('hidden.bs.modal', function (e) {
                    swiperModalThumbs.destroy(true, true);
                    swiperModalGallery.destroy(true, true);
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
        window.acf.addAction( 'render_block_preview/type=section-services', initializeBlock );
    }

})(jQuery);