(function($){
    let initializeBlock = function() {
        const sectionServicesStyleV1 = $('.section-services--style-v1');

        if (sectionServicesStyleV1.length) {
            let horizontalScroll = undefined;

            // horizontal scroll
            $.fn.hScroll = function (amount) {
                amount = amount || 120;
                horizontalScroll = function (event) {
                    let oEvent = event.originalEvent,
                        direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta,
                        position = $(this).scrollLeft();
                    position += direction > 0 ? -amount : amount;
                    $(this).scrollLeft(position);
                    event.preventDefault();
                }
                $(this).bind("DOMMouseScroll mousewheel", horizontalScroll);
            };

            sectionServicesStyleV1.each(function () {
                const $section = $(this);

                const initTabs = () => {
                    $section.find('.tab-pane').each( function() {
                        if ( $(this).hasClass('js-initialize') )
                            $(this).removeClass('js-initialize').removeClass('js-initialize-active')
                    } )
                }

                setTimeout(function () {
                    initTabs();

                    const $sectionServicesNav = $section.find('.section-services__nav');
                    const $sectionServicesTab = $sectionServicesNav.find('.section-services__tab');
                    const $scrollContent = $sectionServicesNav.find('.scroll-content');

                    if ($scrollContent.length) { // if custom scrollbar was initialized
                        if($scrollContent[0].scrollWidth > $scrollContent[0].clientWidth) {
                            // You can pass (optionally) scrolling amount
                            $scrollContent.hScroll(60);
                        } else {
                            $scrollContent.unbind("DOMMouseScroll mousewheel", horizontalScroll);
                        }
                    } else { // if there's no custom scrollbar
                        if($sectionServicesTab[0].scrollWidth > $sectionServicesTab[0].clientWidth) {
                            // You can pass (optionally) scrolling amount
                            $sectionServicesTab.hScroll(60);
                        } else {
                            $sectionServicesTab.unbind("DOMMouseScroll mousewheel", horizontalScroll);
                        }
                    }
                }, 1000)
            });
        }

        if($('.section-services--style-v2').length) {
            $('.section-services--style-v2').each(function() {
                let $section = $(this);

                if($section.find('.js-service-accordeon-item').length) {
                    let serviceSpine = $section.find('.js-service-accordeon-item .service__spine'),
                        serviceInfo = $section.find('.js-service-accordeon-item .service__info'),
                        slideAnimationY,
                        slideAnimationX;

                    function animationSetup() {
                        if(window.matchMedia('screen and (min-width: 1280px)').matches && $section.find('.accordeon').hasClass('accordeon--horizontal')) {
                            slideAnimationX = true;
                            slideAnimationY = false;
                        } else {
                            slideAnimationX = false;
                            slideAnimationY = true;
                        }
                    }

                    animationSetup();

                    $.each(serviceSpine, function () {
                        if (slideAnimationY == true) {
                            $(this).unbind('click').on('click', function () {
                                if (!$(this).hasClass('active')) {
                                    serviceSpine.addClass('disable');
                                    serviceSpine.removeClass('active');
                                    serviceInfo.removeClass('active').slideUp().find('.service__body').removeClass('active');
                                    $(this).addClass('active').siblings('.service__info').addClass('active').stop().slideDown().find('.service__body').addClass('active');

                                    setTimeout(()=> {
                                        serviceSpine.removeClass('disable');
                                    }, 500);
                                }
                            })
                        } else if (slideAnimationX == true) {
                            $(this).unbind('click').on('click', function () {
                                if (!$(this).hasClass('active')) {
                                    serviceSpine.addClass('disable');
                                    serviceSpine.removeClass('active');
                                    $section.find('.service__info.active').toggle('slide').removeClass('active').find('.service__body').removeClass('active');
                                    $(this).addClass('active').siblings('.service__info').css('min-height', '100%').addClass('active').stop().toggle('slide').find('.service__body').addClass('active');
                                    setTimeout(()=> {
                                        serviceSpine.removeClass('disable');
                                    }, 500);
                                }
                            })
                        }
                    });
                }
            });
        }

        if($('.section-services--style-v4').length) {
            $('.section-services--style-v4').each(function() {
                let $section = $(this);

                let $slider = $section.find('.js-services-slider');
                let $thumbs = $section.find('.js-thumbs-slider');

                $section.find('.swiper-button-lock').removeClass('swiper-button-lock');
                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }
                if (!!$thumbs[0].swiper) {
                    $thumbs[0].swiper.destroy(true, true);
                }

                let direction;
                let grid;
                let loop;
                if( window.matchMedia('screen and (min-width: 1280px)').matches ) {
                    direction = 'vertical';
                    grid = {
                        fill: "column",
                        rows: 2
                    };
                    loop = false;
                } else {
                    direction = 'horizontal';
                    grid = null;

                    const numberOfSlides = $slider.find('.swiper-slide').length;

                    if (
                        window.matchMedia('screen and (min-width: 768px)').matches && numberOfSlides < 5 ||
                        window.matchMedia('screen and (min-width: 0px)').matches && numberOfSlides < 3
                    ) {
                        loop = false;
                    } else {
                        loop = true;
                    }
                }

                let slidesPerView;
                let slidesPerGroup;
                if( window.matchMedia('screen and (min-width: 768px)').matches ) {
                    slidesPerView = 4;
                    slidesPerGroup = 4;
                } else {
                    slidesPerView = 2;
                    slidesPerGroup = 2;
                }

                const swiperThumbs = new Swiper($thumbs[0], {
                    speed: 1000,
                    slidesPerView,
                    slidesPerGroup,
                    spaceBetween: 0,
                    noSwiping: true,
                    // noSwipingClass: "js-thumbs-slider",
                    direction,
                    grid,
                    loop,
                    //loopFillGroupWithBlank: true,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,

                    rewind: true,
                    slideToClickedSlide: true,

                    navigation: {
                        nextEl: $section.find('.swiper-button-next')[0],
                        prevEl: $section.find('.swiper-button-prev')[0],
                    },

                    pagination: {
                        el: $section.find('.section-services__thumbs-bullets')[0],
                        type: 'bullets',
                        bulletClass: 'section-services__thumbs-bullet',
                        bulletActiveClass: 'section-services__thumbs-bullet--active',
                        bulletElement: 'div',
                        clickable: true,
                    },
                });

                const swiperServices = new Swiper($slider[0] ,{
                    speed: 1000,
                    effect: 'fade',
                    simulateTouch: false,
                    fadeEffect: {
                        crossFade: true,
                    },
                    thumbs: {
                        swiper: swiperThumbs
                    }
                })
            })
        }

        if($('.section-services--style-v5').length) {
            $('.section-services--style-v5').each(function() {
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
        window.acf.addAction( 'render_block_preview/type=section-services', adminInitializeBlock( initializeBlock, '.section-services' ) );
    }

})(jQuery);