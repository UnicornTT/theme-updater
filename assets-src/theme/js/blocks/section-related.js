(function($){
    let initializeBlock = function( ) {
        if ($('.js-recent-blogs-slider').length) {
            $('.js-recent-blogs-slider').each(function () {

                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                const swiperRecentBlogs = new Swiper($slider[0], {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 0,
                    loop: true,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,
                    speed: 1000,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                    navigation: {
                        nextEl: this.querySelector('.swiper-button-next'),
                        prevEl: this.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: this.querySelector('.swiper-pagination'),
                        type: 'fraction',
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
        window.acf.addAction( 'render_block_preview/type=section-related', adminInitializeBlock( initializeBlock, '.section-related' ) );
        setTimeout(function () {
            if ($('.widgets-php .components-panel__body-toggle').length) {
                $('.widgets-php .components-panel__body-toggle').on('click', function (e) {
                    adminInitializeBlock( initializeBlock, '.section-related' )();
                });
            }
        }, 2000);
    }

})(jQuery);