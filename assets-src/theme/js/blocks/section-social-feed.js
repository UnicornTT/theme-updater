(function($){
    function getCloneCount($container) {
        const $slide = $container.find('.section-social-feed__slide-wrap');
        const slideWidth = $slide.outerWidth();
        const sliderWidth = $container.outerWidth();
        const numberOfSlides = $slide.length;
        const requiredNumberOfSlides = Math.ceil(sliderWidth / slideWidth) * 2;
        const result = Math.ceil(requiredNumberOfSlides / numberOfSlides) - 1;

        return result < 1 ? 1 : result;
    }

    function destroySlider($container) {
        $container.find('.infiniteslide_clone').remove();

        if ($container.parent().hasClass('infiniteslide_wrap')) {
            $container.unwrap();
        }
    }

    function getLoopState($container) {
        const numberOfSlider = $container.find('.swiper-slide').length;

        return numberOfSlider > 3;
    }

    function initInfinitySlider() {
		const $infiniteslider = $('.js-social-feed-infiniteslide');

		$infiniteslider.each(function () {
			const $container = $(this);

			destroySlider($container);

			$container.infiniteslide({
				'speed': 50, //speed this is px/min
				'direction': 'left', //choose  up/down/left/right
				'pauseonhover': true, //if true,stop onmouseover
				'responsive': false, //width/height recalculation on window resize. child element's width/height define %/vw/vh,this set true.
				'clone': getCloneCount($container) //if child elements are too few (elements can't "infinite"), set 2 or over.
			});
		});
	}

	function initSwiperSlider() {
		const $slider = $('.js-social-feed-slider');

		$slider.each(function () {
			console.log( 'init' )
			const $container = $(this);

			if (!!this.swiper) {
				this.swiper.destroy(true, true);
			}

			new Swiper($container[0], {
				loop: getLoopState($container),
				slidesPerView: 'auto',
				loopPreventsSlide: false,
				a11y: {
					enabled: false
				},
				navigation: {
					nextEl: $container.closest('.section-social-feed__slider-wrap').find('.swiper-button-next')[0],
					prevEl: $container.closest('.section-social-feed__slider-wrap').find('.swiper-button-prev')[0],
				},
				breakpoints: {
					0: {
						spaceBetween: 16,
					},
					768: {
						spaceBetween: 27,
					},
					1280: {
						spaceBetween: 40,
					}
				}
			});
		})
	}

    function initializeBlock() {
		initInfinitySlider();
		initSwiperSlider()
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
        window.acf.addAction( 'render_block_preview/type=section-social-feed', adminInitializeBlock( initializeBlock, '.section-social-feed' ) );
    }

})(jQuery);