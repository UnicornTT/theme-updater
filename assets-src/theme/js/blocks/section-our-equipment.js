(function($){
    let initializeBlock = function() {
        if( $('.js-our-equipment-slider').length ) {
            $('.js-our-equipment-slider').each(function () {
                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
				}
				
				let $swiperSlide = $slider.find('.swiper-slide'),
					$swiperNavigation = $slider.siblings('.section-our-equipment__header'),
					$swiperButtonPrev = $swiperNavigation.find('.swiper-button-prev'),
					$swiperButtonNext = $swiperNavigation.find('.swiper-button-next'),
					loop = true,
					customPagination = false;

					$swiperButtonPrev.removeClass('swiper-button-lock')
					$swiperButtonPrev.removeAttr('disabled')
					$swiperButtonNext.removeClass('swiper-button-lock')
					$swiperButtonNext.removeAttr('disabled')

                if ( $swiperSlide.length <= 1 && window.matchMedia('screen and (max-width: 767px)').matches ) {
					loop = false;
					customPagination = true;
				} else if ( $swiperSlide.length <= 2 && window.matchMedia('screen and (min-width: 768px)').matches ) {
					loop = false;
					customPagination = true;
				} else if ( $swiperSlide.length <= 3 && window.matchMedia('screen and (min-width: 1025px)').matches ) {
					loop = false;
					customPagination = true;
				}

                const swiperEquipment = new Swiper($slider[0], {
					slidesPerView: 1,
					slidesPerGroup: 1,
					spaceBetween: 0,
					centeredSlides: false,
                    loop: loop,
                    speed: 400,
                    autoHeight: true,
                    slideToClickedSlide: false,
                    a11y: {
                        enabled: false
                    },
                    simulateTouch: false,
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1025: {
                            spaceBetween: 10,
                            slidesPerView: 3,
                        },
                    },
                    navigation: {
                        nextEl: $swiperButtonNext[0],
                        prevEl: $swiperButtonPrev[0],
                    },
                    pagination: {
                        el: $swiperNavigation.find('.swiper-pagination')[0],
                        type: 'fraction',
                    },
					on: {
						afterInit: function() {
							if(customPagination) {
								$swiperNavigation.find('.swiper-pagination .swiper-pagination-total').text($swiperSlide.length)
							}
						}
					}
                });
            });
        }

        if( $('.js-open-equipment-video').length ) {
            $('.js-open-equipment-video').on('click', function (e) {
                e.preventDefault();
                let $button = $(this);
                let $modalParent = $button.parents('.modal');
                let modalTarget = $button.data('target');

                $modalParent.modal('hide');

                setTimeout(function () {
                    $(modalTarget).modal('show');
                }, 600);
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
        window.acf.addAction( 'render_block_preview/type=section-our-equipment', adminInitializeBlock( initializeBlock, '.section-our-equipment' ) );
    }

})(jQuery);