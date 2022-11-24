(function($){
    let initializeBlock = function( ) {
        if ($('.js-events-hero-slider').length) {
            $('.js-events-hero-slider').each(function () {
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

                const swiperEventsDefault = new Swiper($slider[0], {
                    slidesPerView: 'auto',
                    speed: 1000,
                    spaceBetween: 8,
                    loop: looping,
                    loopedSlides: 15,
                    loopAdditionalSlides: 10,
                    slideToClickedSlide: false,
                    rewind: rewind,
                    //centeredSlides: true,
                    a11y: {
                        enabled: false
                    },
                    navigation: {
                        nextEl: $slider.find('.swiper-button-next')[0],
                        prevEl: $slider.find('.swiper-button-prev')[0],
                    },
                    breakpoints: {
                        1280: {
                            spaceBetween: 15,
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
    }

    const changePositionModals = () => {
        let modalsArray = [];

        $('.section-events-hero__content .event-hero-modal').each(function () {
            const newItem = $(this)[0].cloneNode(true);
            modalsArray.push(newItem);
        });

        $('body').find('.event-hero-modal').remove();
        modalsArray.forEach((item)=>{
            $('body')[0].appendChild(item);
        })
        app.main.customScrollbars();
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();
        changePositionModals();

        window.addEventListener('resize', function () {
            initializeBlock();
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-events-hero', adminInitializeBlock( initializeBlock, '.section-events-hero' ) );
    }

})(jQuery);