(function($){
    let initializeBlock = function( ) {
        if($('.section-service-hero--style-v2').length) {
            $('.section-service-hero--style-v2').each(function() {
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

        if($('.section-service-hero--style-v4').length) {
            $('.section-service-hero--style-v4').each(function() {
                let $section = $(this);

                let $slider = $section.find('.js-service-hero-slider');
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
                        fill: "row",
                        rows: 2
                    };
                    loop = false;
                } else {
                    direction = 'horizontal';
                    grid = null;
                    loop = true;
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
                    loopFillGroupWithBlank: true,

                    rewind: true,
                    slideToClickedSlide: true,

                    navigation: {
                        nextEl: $section.find('.swiper-button-next')[0],
                        prevEl: $section.find('.swiper-button-prev')[0],
                    },

                    pagination: {
                        el: $section.find('.section-service-hero__thumbs-bullets')[0],
                        type: 'bullets',
                        bulletClass: 'section-service-hero__thumbs-bullet',
                        bulletActiveClass: 'section-service-hero__thumbs-bullet--active',
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
        window.acf.addAction( 'render_block_preview/type=section-service-hero', initializeBlock );
    }

})(jQuery);