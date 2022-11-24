(function($){
    class Marker {
        isCurrent = false
        icon = { url: window['sitePath'] + '/theme/img/map-pin.png' }
        iconActive = { url: window['sitePath'] + '/theme/img/map-pin-current.png' }

        constructor( mapClass, map, location ) {
            this.GoogleMaps = mapClass
            this.map = map
            this.renderMarker( location )
        }

        renderMarker( serviceAreasLocation ) {
            const serviceAreasLocationID = serviceAreasLocation.id
            const position = new google.maps.LatLng(
                parseFloat(serviceAreasLocation.coordinates.lat),
                parseFloat(serviceAreasLocation.coordinates.lng)
            );

            this.serviceAreaMarker = new google.maps.Marker({
                position: position,
                map: this.map,
                icon: this.icon,
                id: serviceAreasLocationID,
            });
            this.category = serviceAreasLocation.category
        }

        /*
        * -------------- Events
        * */
        addEvent( event, handler ) {
            google.maps.event.addListener( this.serviceAreaMarker, event, () => handler(this) );
        }

        /*
        * -------------- Actions
        * */
        hoverMarker() {
            this.serviceAreaMarker.setIcon(this.iconActive);
        }

        blurMarker() {
            if ( !this.isCurrent ) {
                this.serviceAreaMarker.setIcon(this.icon);
            }
        }

        setActiveIcon() {
            this.isCurrent = true
            this.serviceAreaMarker.setIcon(this.iconActive);
        }

        hideActiveIcon() {
            if ( this.isCurrent ) {
                this.serviceAreaMarker.setIcon(this.icon);
                this.isCurrent = false;
            }
        }

        hide() {
            this.serviceAreaMarker.visible = false;
        }

        show() {
            this.serviceAreaMarker.visible = true;
        }
    }
    class GoogleMaps {
        stylesDark = [
            {elementType: "geometry", stylers: [{color: "#24282A"}]},
            {elementType: "labels.text.stroke", stylers: [{color: "#1E2225"}]},
            {elementType: "labels.text.fill", stylers: [{color: "#626669"}]},
            {
                featureType: "administrative.locality",
                elementType: "labels.text.fill",
                stylers: [{color: "#626669"}],
            },
            {
                featureType: "poi",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "landscape.natural",
                elementType: "labels.icon",
                stylers: [{"visibility": "off" }]
            },
            {
                featureType: "road",
                elementType: "geometry",
                stylers: [{color: "#2B2F32"}],
            },
            {
                featureType: "road",
                elementType: "geometry.stroke",
                stylers: [{color: "#212427"}],
            },
            {
                featureType: "road",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "road.highway",
                elementType: "geometry",
                stylers: [{color: "#2B2F32"}],
            },
            {
                featureType: "road.highway",
                elementType: "geometry.stroke",
                stylers: [{color: "#212427"}],
            },
            {
                featureType: "road.highway",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "transit",
                elementType: "geometry",
                stylers: [{color: "#2B2F32"}],
            },
            {
                featureType: "transit.station",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{color: "#1A1E21"}],
            },
            {
                featureType: "water",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
        ]
        stylesLight = [
            {elementType: "geometry", stylers: [{color: "#F6F6F4"}]},
            {elementType: "labels.text.stroke", stylers: [{color: "#FFFFFF"}]},
            {elementType: "labels.text.fill", stylers: [{color: "#ACACAC"}]},
            {
                featureType: "administrative.locality",
                elementType: "labels.text.fill",
                stylers: [{color: "#959595"}],
            },
            {
                featureType: "poi",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "landscape.natural",
                elementType: "labels.icon",
                stylers: [{"visibility": "off" }]
            },
            {
                featureType: "road",
                elementType: "geometry",
                stylers: [{color: "#FFFFFF"}],
            },
            {
                featureType: "road",
                elementType: "geometry.stroke",
                stylers: [{color: "#EBF0EE"}],
            },
            {
                featureType: "road",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "road.highway",
                elementType: "geometry",
                stylers: [{color: "#FFFFFF"}],
            },
            {
                featureType: "road.highway",
                elementType: "geometry.stroke",
                stylers: [{color: "#EBF0EE"}],
            },
            {
                featureType: "road.highway",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "transit",
                elementType: "geometry",
                stylers: [{color: "#FFFFFF"}],
            },
            {
                featureType: "transit.station",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{color: "#CAD2D3"}],
            },
            {
                featureType: "water",
                elementType: "labels",
                stylers: [{visibility: "off"}],
            },
        ]
        style = $('body').hasClass('theme-dark') ? this.stylesDark : this.stylesLight
        themeToggler = document.querySelectorAll(".theme-switcher")

        maxZoom = 10
        mapPadding = null
        offsetMarker = null
        allMarkers = []
		filteringMarkers = []

        currentMarker = new Proxy({ marker: null }, {
            set( target, prop, value ) {
                if ( prop === '$sectionServiceAreas' ) {
                    target[prop] = value;
                    return true
                }

                if ( target[prop] ) {
                    if ( value && target[prop].serviceAreaMarker.id === value.serviceAreaMarker.id )
                        return true

                    target[prop].hideActiveIcon();
                }

                if ( value ) {
                    target[prop] = value;
                    target[prop].setActiveIcon();
                    this.updateHTMLSlider( target.$sectionServiceAreas, target[prop] );
                } else {
                    target[prop] = value;
                    this.updateHTMLSlider( target.$sectionServiceAreas, target[prop] );
                }

                return true
            },
            updateHTMLSlider( $sectionServiceAreas, marker ) {
                $sectionServiceAreas.find('.js-change-map-project').each( function() {
                    const projectID = $(this).data('project');

                    if ( !marker ) {
                        $(this).removeClass('active');
                        return
                    }

                    if ( projectID != marker.serviceAreaMarker.id ) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }
                } )
            }
        })

        constructor( $parentSection ) {
            this.$sectionServiceAreas = $parentSection
            this.currentMarker.$sectionServiceAreas = this.$sectionServiceAreas
            this.sectionId = this.$sectionServiceAreas.data('id')

            if ( !window[`themeVars_${this.sectionId}`] ) return

            this.serviceAreasLocations = window[`themeVars_${this.sectionId}`].data
            this.location = {
                lat: parseFloat(this.serviceAreasLocations[0].coordinates.lat),
                lng: parseFloat(this.serviceAreasLocations[0].coordinates.lng),
            }

            this.$sectionServiceAreas.find('.js-service-areas-map').each( this.create.bind(this) )
        }

        create() {
            this.serviceAreasMap = document.getElementById( this.$sectionServiceAreas.find('.js-service-areas-map').attr('id') )

            this.map = new google.maps.Map( this.serviceAreasMap, {
                styles: this.style,
                zoom: this.maxZoom,
                disableDefaultUI: true,
                gestureHandling: "cooperative",
                draggable: true,
                center: this.location,
                clickableIcons: false,
            });

            this.checkWindow()
            this.setMarkers()
            this.changeTheme()

            google.maps.event.addListener(this.map, "click", () => {
                this.removeCurrentMarker();
            });
        }

        checkWindow() {
            if (window.matchMedia('screen and (min-width: 1720px)').matches) {
                this.mapPadding = {left: 550};
            } else if (window.matchMedia('screen and (min-width: 1280px)').matches) {
                this.mapPadding = {left: 550};
            } else if (window.matchMedia('screen and (min-width: 1025px)').matches) {
                this.mapPadding = {top: 420};
            } else if (window.matchMedia('screen and (min-width: 768px)').matches) {
                this.mapPadding = {top: 950};
            } else {
                this.mapPadding = {top: 800};
            }
        }

        setMarkers() {
            this.serviceAreasLocations.forEach( location => {
                if ( $(`a.js-change-map-project[data-project="${location.id}"]`).parent().hasClass('visible') ) {
                    const marker = new Marker( this, this.map, location )

                    marker.addEvent( "mouseover", this.mouseover.bind(this) )
                    marker.addEvent( "mouseout", this.mouseout.bind(this) )
                    marker.addEvent( "click", this.click.bind(this) )

                    this.allMarkers.push( marker )
                }
            } )

            this.fitBounds(this.allMarkers)
        }

        fitSingle(marker, isNeedZoom = false) {
            const different = 0.7566083000000035;
            const bounds = new google.maps.LatLngBounds();

            bounds.extend( marker.serviceAreaMarker.position );

            if (window.matchMedia('screen and (max-width: 1280px)').matches) {
                if ( bounds.Ha.lo )
                    bounds.Ha.lo -= different
            }

			this.map.panTo(bounds.getCenter())
			this.map.panToBounds( bounds, this.mapPadding );

			if(isNeedZoom) {
				this.map.setZoom(this.maxZoom)
			}
        }

        fitSeveral(markersArray) {
            const bounds = new google.maps.LatLngBounds();

            markersArray.forEach( ({ serviceAreaMarker }) => {
                bounds.extend( serviceAreaMarker.position );
            } )

			this.map.setCenter( bounds.getCenter() )
            this.map.fitBounds( bounds, this.mapPadding );
        }

        fitBounds(markersArray) {
            if ( markersArray.length === 1 ) {
                this.fitSingle(markersArray[0], true)
            } else {
                this.fitSeveral(markersArray)
            }
        }

        /*
        * -------------- Marker Events
        * */
        mouseover( marker ) {
            marker.hoverMarker()
        }

        mouseout( marker ) {
            marker.blurMarker()
        }

        click( marker ) {
            if ( !this.currentMarker.marker || (this.currentMarker.marker.serviceAreaMarker.id !== marker.serviceAreaMarker.id) ) {
                this.currentMarker.marker = marker;
                this.fitSingle(marker, false)
            }
        }

        /*
        * -------------- Actions
        * */
        removeCurrentMarker() {
            this.currentMarker.marker = null
        }

        changeTheme() {
            this.themeToggler.forEach( themeModeBtn => {
                themeModeBtn.addEventListener("click", () => {
                    setTimeout(() => {
                        let currentTheme = $(themeModeBtn).find('.current').data('theme');
                        if (currentTheme === 'light') {
                            this.map.setOptions({
                                styles: this.stylesLight,
                            });
                        } else {
                            this.map.setOptions({
                                styles: this.stylesDark,
                            });
                        }
                    }, 0)
                });
            } );
        }

        /*
        * -------------- Marker Visibility
        * */
        showByID( projectId ) {
            let [ targetMarker ] = this.allMarkers.filter( marker => ( marker.serviceAreaMarker.id == projectId ) )

            if ( targetMarker ) {
                this.currentMarker.marker = targetMarker
            }

            return targetMarker
        }

        showByCategory( categorySlug ) {
			this.filteringMarkers = [];

            this.allMarkers.forEach( marker => {
                if ( marker.category.includes( categorySlug ) ) {
                    marker.show()
					this.filteringMarkers.push(marker);
                } else {
                    marker.hide()
                }
            } )

			this.fitBounds(this.filteringMarkers)
        }

        showAll() {
            this.allMarkers.forEach( marker => {
                marker.show()
            } )

			setTimeout(() => {
				this.fitBounds(this.allMarkers)
			}, 100)
        }
    }

    let initNavigationSliders = function( $parentSection, events ) {
        $parentSection.find('.js-service-areas-navigation-slider').each(function () {
            let $slider = $(this);

            if (!!$slider[0].swiper) {
                $slider[0].swiper.destroy(true, true);
            }

            const navigationSlider = new Swiper($slider[0], {
                slidesPerView: 1,
                slidesPerGroup: 1,
                spaceBetween: 0,
                rewind: true,
                speed: 1000,
                effect: 'fade',
                simulateTouch: false,
                fadeEffect: {
                    crossFade: true,
                },
                navigation: {
                    nextEl: $slider.find('.swiper-button-next')[0],
                    prevEl: $slider.find('.swiper-button-prev')[0],
                },
                pagination: {
                    el: '.swiper-pagination',
                    type: 'fraction',
                },
                on: events.apply( $slider )
            });
        });
    }
    
    let initializeBlock = function( ) {
        if($('.section-service-areas').length) {
            $('.section-service-areas').each(function () {
                let $sectionServiceAreas = $(this);
                let googleMaps = null;

                if( $sectionServiceAreas.hasClass('section-service-areas--style-v1') ) {
                    googleMaps = new GoogleMaps( $sectionServiceAreas )

                    initNavigationSliders( $sectionServiceAreas, function() {
                        const $slider = this
    
                        return {
                            init: function() {
                                $slider.find('.js-change-map-project').each( function() {
                                    const projectID = $(this).data('project')
    
                                    if ( $(this).hasClass('active') )
                                        googleMaps.showByID(projectID)
                                } )
                            }
                        }
                    } )
                }

                if ($sectionServiceAreas.find('.js-service-areas-subnav-opened').length) {
                    $sectionServiceAreas.find('.js-service-areas-subnav-opened').on('click', function (e) {
                        e.preventDefault();
                        let $currentCategory = $(this);
                        let currentCategoryTitle = $currentCategory.find('.link-text').text().trim();
                        let currentCategoryFilter = $currentCategory.data('filter');
                        let $servicearea = $sectionServiceAreas;
                        let $serviceareaNav = $currentCategory.parents('.service-areas-nav');
                        let $serviceareaSubnav = $serviceareaNav.siblings('.service-areas-subnav');
                        let $serviceareaSubnavList = $serviceareaSubnav.find('.list');

                        $serviceareaSubnav.find('.service-areas-subnav__title').text(currentCategoryTitle);

                        $serviceareaSubnavList.find('.list-item').each(function () {
                            let $listItem = $(this);
                            let categories = $listItem.data('filter-values').split(' ');

                            if(!categories.includes(currentCategoryFilter)) {
                                $listItem.addClass('d-none').removeClass('visible');
                            } else {
                                $listItem.addClass('visible').removeClass('d-none');
                            }
                        });

                        $currentCategory.parent().siblings().find('.link').removeClass('active');
                        $currentCategory.addClass('active');

                        if($servicearea.hasClass('section-service-areas--style-v2')) {
                            $serviceareaSubnavList.find('.list-item.visible').first().find('.link').tab('show');
                        } else {
                            $serviceareaSubnavList.find('.list-item.visible').first().find('.link').trigger('click');
                        }

                        $serviceareaNav.collapse('hide');
                        $serviceareaSubnav.collapse('show');

                        if ( googleMaps ) {
                            googleMaps.showByCategory( currentCategoryFilter )
                        }
                    });

                    if ($sectionServiceAreas.find('.js-service-areas-subnav-back').length) {
                        $sectionServiceAreas.find('.js-service-areas-subnav-back').unbind('click').on('click', function (e) {
                            let $servicearea = $sectionServiceAreas;
                            let $serviceareaNav = $servicearea.find('.service-areas-nav');
                            let $serviceareaSubnav = $servicearea.find('.service-areas-subnav');

                            $serviceareaNav.collapse('show');
                            $serviceareaSubnav.collapse('hide');

                            $sectionServiceAreas.find('.list-item .link').each(function () {
                                $(this).removeClass('active')
                            });

							$sectionServiceAreas.find('.list-item .link').first().addClass('active')
        
                            if ( googleMaps ) {
                                googleMaps.showAll()
                                googleMaps.removeCurrentMarker()
                            }
                        });
                    }
                }                

                if ( $sectionServiceAreas.find('a.js-change-map-project').length ) {
                    // Submenu Bind Click
                    $sectionServiceAreas.find('a.js-change-map-project').on('click', function(e) {
                        e.preventDefault();
                        let $link = $(this);
                        let projectId = $link.data('project');

                        if ( googleMaps ) {
                            googleMaps.showByID( projectId )
                        }
                    });
                }
            });
        }

        if ($('.section-service-areas--style-v2').length) {
            $('.section-service-areas--style-v2').each(function () {
                let $block = $(this);
                let $swiper = $block.find('.js-service-areas__slider');

                if ($swiper[0] && !!$swiper[0].swiper) {
                    $swiper[0].swiper.destroy(true, true);
                    $block.find('.js-filter').unbind();
                    $block.find('.section-service-areas__slider-nav').show();
                }

                let slidesPerView;
                let grid;
                let gap;
                let fill = 'row';
                if (window.matchMedia('screen and (min-width: 1720px)').matches) {
                    slidesPerView = 5;
                    grid = {
                        fill,
                        rows: 2
                    }
                    gap = 10;
                } else if (window.matchMedia('screen and (min-width: 1280px)').matches) {
                    slidesPerView = 5;
                    grid = {
                        fill,
                        rows: 2
                    }
                    gap = 8;
                } else if (window.matchMedia('screen and (min-width: 768px)').matches) {
                    slidesPerView = 2;
                    grid = {
                        fill,
                        rows: 3
                    }
                    gap = 20;
                } else {
                    slidesPerView = 1;
                    grid = {
                        fill,
                        rows: 3
                    }
                    gap = 20;
                }

                let swiperAreasItems = null;

                function initSwiper(){
                    swiperAreasItems = new Swiper($swiper[0], {
                        slidesPerView,
                        slidesPerGroup: slidesPerView,
                        spaceBetween: gap,
                        loopFillGroupWithBlank: true,
                        rewind: true,
                        speed: 1000,
                        navigation: {
                            nextEl: $block.find('.swiper-button-next')[0],
                            prevEl: $block.find('.swiper-button-prev')[0],
                        },
                        pagination: {
                            el: $block.find('.swiper-pagintaion')[0],
                            type: 'fraction',
                        },
                        grid,
                        observer: true
                    });
                }
                initSwiper();

                $block.find('.section-service-areas__slider-nav:not(:has(:visible))').hide();

                const hideSlide = ($item) => {
                    $item.hide(0);
                    $item.removeClass('swiper-slide');
                    $item.addClass('not-a-swiper-slide');
                }

                const showSlide = ($item) => {
                    $item.show(0);
                    $item.removeClass('not-a-swiper-slide');
                    $item.addClass('swiper-slide');
                }

				const hideSlider = (callback) => {
					$swiper.fadeTo(200, 0, callback)
				}

				const showSlider = () => {
					$swiper.fadeTo(200, 1)
				}

				//add id for tab item
                $block.find('.js-filter').each((index, item) => {
                    $(item).attr('id', `js-filter-tab-${index}`);
                })

				const smoothScrollToTabElem = (item) => {
                    const processItem = $(item);
                    const processItemId = processItem.attr('id');

                    if( !!processItemId ) {
                        $(`#${processItemId}`)[0].scrollIntoView({block: "nearest", inline: "center",  behavior: "smooth"});
                    }
                }

                let currentFilter = 'all';
                $block.find('.js-filter').on('click', function() {
                    const filter = $(this).attr('data-filter');
                    if (currentFilter == filter) return;
                    currentFilter = filter;
					hideSlider(() => {
						$block.find('.js-filter.active').removeClass('active');
						$(this).addClass('active');
						if (filter.toLowerCase() == 'all') {
							$block.find('.js-filter-item').each(function() {
								showSlide($(this));
							});
						} else {
							$block.find('.js-filter-item').each(function() {
								const filterItemData = $(this).attr('data-filter-item').split(";");
								if (filterItemData.includes(filter)) {
									showSlide($(this));
								} else {
									hideSlide($(this));
								}
							})
						}

						setTimeout(showSlider, 100);
					})

                    swiperAreasItems.updateSize();
                    swiperAreasItems.updateSlides();
                    swiperAreasItems.updateProgress();
                    swiperAreasItems.updateSlidesClasses();
                    swiperAreasItems.slideTo(0, 0);

					smoothScrollToTabElem($(this))
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
        window.acf.addAction( 'render_block_preview/type=section-service-areas', adminInitializeBlock( initializeBlock, '.section-service-areas' ) );
    }
})(jQuery);