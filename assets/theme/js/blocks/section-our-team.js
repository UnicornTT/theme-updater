(function($){
    let initializeBlock = function() {
        if( $('.js-section-our-team').length && $('.js-section-our-team-thumbs').length ) {
            $('.js-section-our-team').each(function () {
                let $this = $(this);
                let $sliderOurTeam = $this;
                let $sliderOurTeamThumbs = $this.siblings('.js-section-our-team-thumbs');

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }
    
                let sliderOurTeamThumbs= new Swiper($sliderOurTeamThumbs[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 10,
                    rewind: true,
                    centerInsufficientSlides: true,
                    slideToClickedSlide: true,
                    a11y: {
                        enabled: false,
                    },
                    on: {
                        init: function(sliderOurTeamThumbs) {
                            if(window.matchMedia('(min-width: 1280px)').matches) {
                                sliderOurTeamThumbs.changeDirection('vertical')
                            } else {
                                sliderOurTeamThumbs.changeDirection('horizontal')
                            }
                        },
                        resize: function(sliderOurTeamThumbs) {
                            if(window.matchMedia('(min-width: 1280px').matches) {
                                sliderOurTeamThumbs.changeDirection('vertical')
                            } else {
                                sliderOurTeamThumbs.changeDirection('horizontal')
                            }
                        },
                        click: function(swiper, event) {
                            let $swiperSlideClicked = $(event.target).closest('.js-section-our-team-thumbs .swiper-slide');
                            if($swiperSlideClicked.length) {
                                let activeIndex = $swiperSlideClicked.attr('data-slide-index');
                                sliderOurTeamThumbs.slideTo(activeIndex, 1000);
                            }
                        }
                    },
                 });
    
                let sliderOurTeam = new Swiper($sliderOurTeam[0], {
                    slidesPerView: 1,
                    speed: 1000,
                    spaceBetween: 10,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                    rewind: true,
                    thumbs: {
                        swiper: sliderOurTeamThumbs,
                    },
                    navigation: {
                        nextEl: $this.closest('.section-our-team__media-wrapper').find('.section-our-team__slider-nav .swiper-button-next')[0],
                        prevEl: $this.closest('.section-our-team__media-wrapper').find('.section-our-team__slider-nav .swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $this.closest('.section-our-team__media-wrapper').find('.section-our-team__slider-nav .section-our-team__slider-pagination')[0],
                        type: 'fraction',
                    },
                    on: {
                        activeIndexChange: function(swiper) {
                            let activeIndex = swiper.activeIndex;
                            sliderOurTeamThumbs.slideTo(activeIndex, 1000);
                        }
                    },
                });
            })
        }

        if ($('.js-team-multiple-slider').length) {
            $('.js-team-multiple-slider').each(function () {
                let $slider = $(this);
                let $thumbs = $slider.siblings('.section-our-team__slider-team-bullets-wrapper').find('.js-team-bullets');

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                    $thumbs[0].swiper.destroy(true, true);
                }

                const teamBulletsSlider = new Swiper($thumbs[0], {
                    slidesPerView: 'auto',
                    slidesPerGroup: 1,
                    speed: 1000,
                    spaceBetween: 10,
                    centerInsufficientSlides: true,
                    rewind: true,
                    //slideToClickedSlide: true,
                    simulateTouch: false,
                    mousewheel: true,
                    a11y: {
                        enabled: false
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1025: {
                            slidesPerView: 3,
                        },
                        1280: {
                            slidesPerView: 4,
                        },
                        1440: {
                            slidesPerView: 5,
                        },
                    },
                });

                const teamMultipleSlider = new Swiper($slider[0], {
                    slidesPerView: 1,
                    speed: 1000,
                    spaceBetween: 10,
                    allowTouchMove: false,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                    rewind: true,
                    thumbs: teamBulletsSlider,
                    autoHeight: true,
                    navigation: {
                        nextEl: $thumbs.siblings('.section-our-team__slider-team-bullets-navigation').find('.swiper-button-next')[0],
                        prevEl: $thumbs.siblings('.section-our-team__slider-team-bullets-navigation').find('.swiper-button-prev')[0],
                    },                    
                    on: {
                        activeIndexChange: function(teamMultipleSlider) {
                            let activeIndex = teamMultipleSlider.activeIndex;
                            let $swiperSlideClicked =  $thumbs.find('.swiper-slide').eq(activeIndex);
                            $swiperSlideClicked.siblings().removeClass('swiper-slide-thumb-active');
                            $swiperSlideClicked.addClass('swiper-slide-thumb-active');
                            teamBulletsSlider.slideTo(activeIndex, 1000);
                        }
                    },
                });

                $thumbs.find('.swiper-slide').removeClass('swiper-slide-thumb-active');
                $thumbs.find('.swiper-slide').eq('0').addClass('swiper-slide-thumb-active');

                $thumbs.find('.swiper-slide').on('click', function(event) {
                    let $swiperSlideClicked = $(this);
                    if($swiperSlideClicked.length) {
                        let activeIndex = $swiperSlideClicked.index();
                        $swiperSlideClicked.siblings().removeClass('swiper-slide-thumb-active');
                        $swiperSlideClicked.addClass('swiper-slide-thumb-active');
                        teamBulletsSlider.slideTo(activeIndex, 1000);
                        teamMultipleSlider.slideTo(activeIndex, 1000);
                    }
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
        window.acf.addAction( 'render_block_preview/type=section-our-team', adminInitializeBlock( initializeBlock, '.section-our-team' ) );
    }

})(jQuery);