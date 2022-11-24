(function($){
    let initializeBlock = function() {
        if( $('.js-section-careers').length ) {
            $('.js-section-careers').each(function () {
                let $this = $(this);
                let $sliderCareers = $this;
                let slidesCount = $sliderCareers.find('.swiper-slide').length;
                let looping = true;

                if (slidesCount < 2) {
                    looping = false;
                }

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }

                let sliderCareers = new Swiper($sliderCareers[0], {
                    slidesPerView: 1,
                    speed: 1000,
                    effect: 'fade',
                    spaceBetween: 0,
                    fadeEffect: {
                        crossFade: true,
                    },
                    loop: looping,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,
                    navigation: {
                        nextEl: $this.closest('.section-careers__slider-wrapper').find('.section-careers__slider-nav .swiper-button-next')[0],
                        prevEl: $this.closest('.section-careers__slider-wrapper').find('.section-careers__slider-nav .swiper-button-prev')[0],
                    },
                    pagination: {
                        el: $this.closest('.section-careers__slider-wrapper').find('.section-careers__slider-nav .section-careers__slider-pagination')[0],
                        type: 'fraction',
                    },
                });
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
        window.acf.addAction( 'render_block_preview/type=section-careers', adminInitializeBlock( initializeBlock, '.section-careers' ) );
    }

})(jQuery);