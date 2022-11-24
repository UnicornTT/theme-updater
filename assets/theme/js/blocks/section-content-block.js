(function($){
    let initializeBlock = function() {
        if ($('.js-content-block-slider').length) {
            $('.js-content-block-slider').each(function () {
                const $this = this;
                let $sliderContentInfo = $($this).find('.js-content-slider-info');
                let $sliderContentThumbs = $($this).find('.js-content-slider-cards');

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }

                let sliderContentThumbs = new Swiper($sliderContentThumbs[0], {
                    slidesPerView: 'auto',
                    slidesPerGroup: 1,
                    speed: 1000,
                    spaceBetween: 1,
                    centerInsufficientSlides: false,
                    slideToClickedSlide: true,
                    rewind: true,
                    a11y: {
                        enabled: false
                    },
                    breakpoints: {
                        1025: {
                            direction: 'horizontal',
                        },
                        768: {
                            direction: 'vertical',
                        },
                        320: {
                            direction: 'horizontal',
                        }
                    },
                    on: {
                        init: function() {
                            let activeSlide = $($this).find('.js-content-slider-cards .swiper-slide-active');
                            activeSlide.addClass('slide-active');
                        },
                        click: function(swiper, event) {
                            let $swiperSlideClicked = $(event.target).closest('.js-content-slider-cards .swiper-slide');
                            if($swiperSlideClicked.length) {
                                let activeIndex = $swiperSlideClicked.attr('data-slide-index');
                                sliderContentThumbs.slideTo(activeIndex, 1000);
                            }
                        }
                    }
                });

                let sliderContentInfo = new Swiper($sliderContentInfo[0], {
                    slidesPerView: 1,
                    speed: 1000,
                    spaceBetween: 1,
                    effect: 'fade',
                    simulateTouch: false,
                    fadeEffect: {
                        crossFade: true,
                    },
                    rewind: true,
                    thumbs: {
                        swiper: sliderContentThumbs,
                    },
                    on: {
                        resize: function() {
                            setActiveSlideClass(0);
                            sliderContentThumbs.slideTo(0, 1000);
                        },
                        activeIndexChange: function(swiper) {
                            let activeIndex = swiper.activeIndex;
                            setActiveSlideClass(activeIndex);
                            sliderContentThumbs.slideTo(activeIndex, 1000);
                        }
                    },
                });

                function setActiveSlideClass(dataIndex) {
                    let cardsSlider = $($this).find('.js-content-slider-cards');
                    let $allSlides = cardsSlider.find('.swiper-slide');
                    let $activeSlide = cardsSlider.find(`.swiper-slide[data-slide-index="${dataIndex}"]`);

                    $allSlides.each(function(index, element) {
                        if( element.classList.contains('slide-active') ) {
                            element.classList.remove('slide-active')
                        }
                    });

                    $activeSlide.addClass('slide-active');
                }
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
        window.acf.addAction( 'render_block_preview/type=section-content-block', adminInitializeBlock( initializeBlock, '.section-content-block' ) );
    }

})(jQuery);