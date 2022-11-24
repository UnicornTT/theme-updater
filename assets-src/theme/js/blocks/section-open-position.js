(function($){
    let initializeBlock = function() {
		class VacancyPopupControl {
			constructor( section ) {
				this.section = $(section);
				this.sectionId = section.attr('id');
				this.sectionVacanciesData = window[`themeVars_${this.sectionId}`].data;
				this.buttons = this.section.find('.position-card__button');
				this.popup = $('#modal-open-position');
				this.popupButton = this.popup.find('.open-position-modal-info__button')
				this.listeners = [];
				this.currentId = null;
				this.newId = null;
				this.decodeHTML();
				this.addListeners();
			}

			decodeHTML() {
				this.sectionVacanciesData.forEach((elem) => {
					elem.general = decodeURIComponent( elem.general.replace(/\+/g, ' ') );
					
					for(let i = 0; i < elem.specifications.length; i++) {
						let content = elem.specifications[i].specification_content;
						elem.specifications[i].specification_content = decodeURIComponent( content.replace(/\+/g, ' ') )
					}
				})
			}

			addListeners() {
				this.buttons.each((i, button) => {
					let $button = $(button)
					let callback = this.openPopup.bind(this)
					$button.on('click', callback)
					this.listeners.push({'elem': $button, 'func': callback})
				})
			}

			removeListeners() {
				this.listeners.forEach((listener) => {
					listener['elem'].off('click', listener['func'])
				})
			}

			openPopup(e) {
				this.newId = $(e.currentTarget).attr('data-position-id')
				if(this.currentId !== this.newId) {
					this.changePopupContent()
				}
				this.popup.modal('show')
				this.currentId = this.newId;
			}

			changePopupContent() {
				let popupTitle = this.popup.find('.open-position-modal-info__title h3'),
					popupGeneral = this.popup.find('.open-position-modal-info__general'),
					popupSpecifications = this.popup.find('.open-position-modal-info__specifications'),
					data;

				for(let i = 0; i < this.sectionVacanciesData.length; i++) {
					if(this.newId === this.sectionVacanciesData[i].id) {
						data = this.sectionVacanciesData[i]
						break;
					}
				}

				popupTitle.empty()
				popupGeneral.empty()
				popupSpecifications.empty()
				
				popupTitle.append(data.title)
				popupGeneral.append(data.general)
				this.popupButton.attr('data-position-id', data.vacancy_id)
				this.popupButton.attr('data-position-name', data.title)

				if( !data.specifications || !data.specifications.length ) {
					popupSpecifications.hide();
				} else {
					popupSpecifications.show();
				}

				data.specifications.forEach(item => {
					let block = this.createSpecificationBlock(item.specification_title, item.specification_content);
					popupSpecifications.append(block);
				})
			}

			openJobApplicationPopup() {

			}

			createSpecificationBlock(title, content) {
				let block = '' +
					'<div class="open-position-modal-info__specification">' +
						`<div class="open-position-modal-info__specification-title">${title}</div>` +
						`<div class="open-position-modal-info__specification-content text-content">${content}</div>` +
					'</div>';

				return block;
			}
		}

        if ($('.js-open-position-slider-v1').length) {
            $('.js-open-position-slider-v1').each(function () {
                let $slider = $(this);

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }

				let $swiperSlide = $slider.find('.swiper-slide'),
					$swiperNavigation = $slider.siblings('.section-open-position__header'),
					$swiperButtonPrev = $swiperNavigation.find('.swiper-button-prev'),
					$swiperButtonNext = $swiperNavigation.find('.swiper-button-next'),
					loop = true,
					customPagination = false,
					popupControls;

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
				} else if ( $swiperSlide.length <= 4 && window.matchMedia('screen and (min-width: 1280px)').matches ) {
					loop = false;
					customPagination = true;
				}

                const swiperOpenPositionV1 = new Swiper($slider[0], {
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
                        1280: {
                            spaceBetween: 20,
                            slidesPerView: 4,
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
							popupControls = new VacancyPopupControl($slider.parents('.section-open-position'))
						},
						beforeDestroy: function() {
							popupControls.removeListeners()
						}
					}
                });
            });
        }

        if ($('.js-open-position-slider-v2').length) {
            $('.js-open-position-slider-v2').each(function () {
                let $slider = $(this);

                if (!!this.swiper) {
                    this.swiper.destroy(true, true);
                }

				let $swiperSlide = $slider.find('.swiper-slide'),
					$swiperNavigation = $slider.siblings('.section-open-position__slider-nav'),
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

                const swiperOpenPositionV1 = new Swiper($slider[0], {
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
                            spaceBetween: 20,
                            slidesPerView: 3,
                        },
                    },
                    navigation: {
                        nextEl: $swiperButtonNext[0],
                        prevEl: $swiperButtonPrev[0],
                    },
                    pagination: {
                        el: $swiperNavigation.find('.slider-pagination')[0],
                        type: 'fraction',
                    },
					on: {
						afterInit: function() {
							if(customPagination) {
								$swiperNavigation.find('.slider-pagination .swiper-pagination-total').text($swiperSlide.length)
							}
							popupControls = new VacancyPopupControl($slider.parents('.section-open-position'))
						},
						beforeDestroy: function() {
							popupControls.removeListeners()
						}
					}
                });
            });
        }
    }

	function openJobApplication () {
		$('#modal-open-position .open-position-modal-info__button').on('click', function(e) {
			e.preventDefault();
			let $button = $(this);
			let $modalParent = $button.parents('.modal');
			let modalTarget = $button.attr('data-target');
			let positionName = $button.attr('data-position-name');

			$(modalTarget).find('.vacancy-popup-position-field').val(positionName);
			
			$modalParent.modal('hide');

			setTimeout(function () {
				$(modalTarget).modal('show');
			}, 600);
		})
	}

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();
		openJobApplication();

        if (window.matchMedia('(min-width: 768px)').matches) {
            window.addEventListener('resize', function () {
                initializeBlock();
            });
        }
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-open-position', adminInitializeBlock( initializeBlock, '.section-open-position' ) );
    }

})(jQuery);