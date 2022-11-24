(function($){

    class Marker {
        isCurrent = false
        icon = { url: window['sitePath'] + '/theme/img/map-pin.png' }
        iconActive = { url: window['sitePath'] + '/theme/img/map-pin-current.png' }

        constructor( mapClass, map, location, infoWindow ) {
            this.GoogleMaps = mapClass
            this.map = map
            this.infoWindow = infoWindow

            this.__renderMarker( location )
        }

        destructor() {
            this.isCurrent = false

            this.portfolioMarker.setMap( null )
            this.portfolioMarker.id = ''
            this.portfolioMarker.setIcon(null)

            google.maps.event.clearInstanceListeners( this.infoWindow )
            this.infoWindow.close()
            this.infoWindow = null
        }

        __renderMarker( portfolioLocation ) {
            const portfolioLocationID = portfolioLocation.id
            const position = new google.maps.LatLng(
                parseFloat(portfolioLocation.coordinates.lat),
                parseFloat(portfolioLocation.coordinates.lng)
            );

            this.popupLayout = decodeURIComponent( portfolioLocation.layout.replace(/\+/g, ' ') )

            this.portfolioMarker = new google.maps.Marker({
                position: position,
                map: this.map,
                icon: this.icon,
                id: portfolioLocationID,
            });

            this.category = portfolioLocation.category
        }

        /*
        * -------------- HTML
        * */
        __initMarkerSlider( id ) {
            let infowindows = $('#infowindow-'+id);

            if(!infowindows.length)
                return

            let sliderMainContainer = $(infowindows).find('.js-swiper-map-main')[0]
            let sliderThumbsContainer = $(infowindows).find('.js-swiper-map-thumbs')[0]
            let dots = $('#infowindow-'+id).find('.swiper-pagination')[0];

            $('#infowindow-'+id).find('.scrollbar-outer').scrollbar({
                ignoreMobile: true,
                ignoreOverlay: true,
                disableBodyScroll: true,
                passive: true
            });

            $(infowindows).parents('.gm-style-iw-c').addClass('active');

            if(!sliderMainContainer) {
                $('#infowindow-'+id).find('.swiper-controls').hide()
                return
            }


            if (!!sliderMainContainer.swiper)
                sliderMainContainer.swiper.destroy(true, true);

            if (!!sliderThumbsContainer.swiper)
                sliderThumbsContainer.swiper.destroy(true, true);

            let swiperThumbs = new Swiper(sliderThumbsContainer, {
                loop: false,
                slidesPerView: 4,
                speed: 1000,
                freeMode: false,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                observer: true,
                observeParents: true,
                direction: 'vertical',
                spaceBetween: 2,
                simulateTouch: true,
            });

            let swiperMain = new Swiper(sliderMainContainer, {
                loop: false,
                slidesPerView: 1,
                speed: 1000,
                observer: true,
                observeParents: true,
                rewind: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                a11y: {
                    enabled: false
                },
                simulateTouch: false,
                thumbs: {
                    swiper: swiperThumbs,
                },
                pagination: {
                    el: dots,
                    type: "fraction",
                },
                navigation: {
                    nextEl: $('#infowindow-'+id).find('.swiper-button-next')[0],
                    prevEl: $('#infowindow-'+id).find('.swiper-button-prev')[0],
                },
                on: {
                    init: function() {
                        if ( this.slides.length <= 1 )
                            $('#infowindow-'+id).find('.swiper-controls').css('display', 'none');
                    }
                }
            });
        }

        /*
        * -------------- Events
        * */
        addEvent( event, handler ) {
            google.maps.event.addListener( this.portfolioMarker, event, () => handler(this) );
        }

        /*
        * -------------- Actions
        * */
        hoverMarker() {
            this.portfolioMarker.setIcon(this.iconActive);
        }

        blurMarker() {
            if ( !this.isCurrent ) {
                this.portfolioMarker.setIcon(this.icon);
            }
        }

        showInfoWindow() {
            let tryCount = 0;

            const tryOpen = () => {
                if( tryCount > 10 ) return

                tryCount++

                this.isCurrent = true
                this.portfolioMarker.setIcon(this.iconActive);
                this.infoWindow.open({
                    anchor: this.portfolioMarker,
                    map: this.map,
                    shouldFocus: false
                });
                this.infoWindow.setContent( this.popupLayout );
                this.__initMarkerSlider(this.portfolioMarker.id);
                // this.GoogleMaps.fitBounds()

                setTimeout(() => {
                    this.__initMarkerSlider(this.portfolioMarker.id);

                    this.GoogleMaps.$sectionPortfolio.find('.gm-ui-hover-effect').on( 'click', () => {
                        this.GoogleMaps.removeCurrentMarker()
                    } )

                    if ( this.GoogleMaps.$sectionPortfolio.find('.gm-style-iw').length ) {
                        if ( !this.GoogleMaps.$sectionPortfolio.find('.gm-style-iw').hasClass( 'active' ) )
                            this.GoogleMaps.$sectionPortfolio.find('.gm-style-iw').addClass( 'active' )
                    } else {
                        setTimeout( tryOpen.bind(this), 2000 )
                    }
                }, 500);
            }

            tryOpen.apply(this)
        }

        hideInfoWindow() {
            if ( this.isCurrent && this.infoWindow ) {
                this.infoWindow.close();
                this.portfolioMarker.setIcon(this.icon)
                this.isCurrent = false
            }
        }

        hide() {
            this.portfolioMarker.setMap( null )
        }

        show() {
            this.portfolioMarker.setMap( this.GoogleMaps.map )
        }
    }


    class GoogleMaps {
        mapBackgroundDark = "#1A1E21"
        mapBackgroundLight = "#CAD2D3"
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
        firstInit = false
        allMarkers = []
		filteringMarkers = []

        currentMarker = new Proxy({ marker: null }, {
            set( target, prop, value ) {
                if ( prop === '$sectionPortfolio' ) {
                    target[prop] = value
                    return true
                }

                if ( target[prop] ) {
                    if ( value && target[prop].portfolioMarker.id === value.portfolioMarker.id )
                        return true

                    target[prop].hideInfoWindow()
                }

                if ( value ) {
                    target[prop] = value
                    target[prop].showInfoWindow()
                    this.updateHTMLSlider( target.$sectionPortfolio, target[prop] )
                } else {
                    target[prop] = value
                    this.updateHTMLSlider( target.$sectionPortfolio, target[prop] )
                }

                return true
            },
            updateHTMLSlider( $sectionPortfolio, marker ) {
                $sectionPortfolio.find('.js-change-map-project').each( function() {
                    const projectID = $(this).data('project')

                    if ( !marker ) {
                        $(this).removeClass('active')
                        return
                    }

                    if ( projectID != marker.portfolioMarker.id ) {
                        $(this).removeClass('active')
                    } else {
                        $(this).addClass('active')
                    }
                } )
            }
        })


        constructor( $parentSection ) {
            this.$sectionPortfolio = $parentSection
            this.currentMarker.$sectionPortfolio = this.$sectionPortfolio
            this.sectionId = this.$sectionPortfolio.data('id')

            if ( !window[`themeVars_${this.sectionId}`] ) return

            this.portfolioLocations = window[`themeVars_${this.sectionId}`].data
            this.location = {
                lat: parseFloat(this.portfolioLocations[0].coordinates.lat),
                lng: parseFloat(this.portfolioLocations[0].coordinates.lng),
            }

            this.$sectionPortfolio.find('.js-portfolio-map').each( this.create.bind(this) )
        }

        update() {
            this.allMarkers.forEach( marker => marker.destructor() )
            this.filteringMarkers = []
            this.currentMarker.marker = null

            this.__checkWindow()
            this.__setMarkers()

            if ( this.groupSlug ) {
                this.showByCategory( this.groupSlug )

                if ( this.currentMarkerProjectId )
                    this.showByID( this.currentMarkerProjectId, 'group' )
            } else {
                this.currentMarker.marker = null
                this.showAll()

                if ( this.currentMarkerProjectId )
                    this.showByID( this.currentMarkerProjectId, 'single' )
            }
        }

        create() {
            this.mapDomElement = document.getElementById( this.$sectionPortfolio.find('.js-portfolio-map').attr('id') )

            this.map = new google.maps.Map( this.mapDomElement, {
                styles: this.style,
                zoom: this.maxZoom,
                disableDefaultUI: true,
                draggable: true,
                center: this.location,
                clickableIcons: false,
                gestureHandling: "cooperative",
            });

            this.__checkWindow()
            this.__setMarkers()
            this.changeTheme()

            google.maps.event.addListener(this.map, "click", () => {
                this.removeCurrentMarker();
            });
        }

        __checkWindow() {
            if (window.matchMedia('screen and (min-width: 1720px)').matches) {
                this.offsetMarker = 525;
                this.offsetMarkerBottom = 200;
                this.mapPadding = {top: 0, left: 550, bottom: 0, right: 0};
                this.zoomDensety = 2;
            } else if (window.matchMedia('screen and (min-width: 1280px)').matches) {
                this.offsetMarker = 425;
                this.offsetMarkerBottom = 200;
                this.mapPadding = {top: 0, left: 550, bottom: 0, right: 0};
                this.zoomDensety = 1;
            } else if (window.matchMedia('screen and (min-width: 1025px)').matches) {
                this.offsetMarker = 361;
                this.offsetMarkerBottom = 250;
                this.mapPadding = {top: 770, left: 0, bottom: 0, right: 0};
                this.zoomDensety = 0;
            } else if (window.matchMedia('screen and (min-width: 768px)').matches) {
                this.offsetMarker = 361;
                this.offsetMarkerBottom = 250;
                this.mapPadding = {top: 950, left: 0, bottom: 0, right: 0};
                this.zoomDensety = 2;
            } else {
                this.offsetMarker = 115;
                this.offsetMarkerBottom = 350;
                this.mapPadding = {top: 800, left: 0, bottom: 0, right: 0};
                this.zoomDensety = 1;
            }

            this.infoWindow = new google.maps.InfoWindow({
                pixelOffset: new google.maps.Size(this.offsetMarker, this.offsetMarkerBottom),
            });

            window.addEventListener( 'resize', () => {
                setTimeout( this.fitBounds.bind(this), 200 )
            } )
        }

        __setMarkers() {
            this.allMarkers = []

            this.portfolioLocations.forEach( location => {
                const marker = new Marker( this, this.map, location, this.infoWindow )

                marker.addEvent( "mouseover", this.__mouseover.bind(this) )
                marker.addEvent( "mouseout", this.__mouseout.bind(this) )
                marker.addEvent( "click", this.__click.bind(this) )

                this.allMarkers.push( marker )
            } )

            this.filteringMarkers = this.allMarkers
            this.fitBounds()
        }

        /*
        * -------------- Fit Bounds
        * */
        fitBounds() {
            if ( this.filteringMarkers.length === 1 ) {
                this.fitSingle( this.filteringMarkers[0], true )
            } else {
                this.fitSeveral( this.filteringMarkers )
            }
        }

        fitSingle( marker, isNeedZoom = false ) {
            const different = 2.9566083000000035;
            const bounds = new google.maps.LatLngBounds();

            bounds.extend( marker.portfolioMarker.position );

            if ( !this.firstInit ) {
                if ( window.matchMedia('screen and (max-width: 1280px)').matches ) {
                    if ( bounds.Ha )
                        bounds.Ha.lo -= different
                }

                this.firstInit = true
            }

            this.map.panTo( bounds.getCenter() )
            this.map.panToBounds( bounds, this.mapPadding );

            if ( isNeedZoom ) {
                let needZoom = this.maxZoom - 5
                if ( this.map.zoom > needZoom )
                    this.map.setZoom( needZoom )
            }
        }

        fitSeveral( markersArray ) {
            const bounds = new google.maps.LatLngBounds();
            let infoWindowOpen = false;

            markersArray.forEach( marker => {
                if ( marker.isCurrent ) {
                    this.mapPadding.right = this.offsetMarker
                    infoWindowOpen = true
                }

                bounds.extend( marker.portfolioMarker.getPosition() );
            } )

            this.map.setCenter( bounds.getCenter() )
            this.map.fitBounds( bounds, this.mapPadding );
            this.mapPadding.right = 0

            if ( infoWindowOpen ) {
                if ( this.map.zoom > this.zoomDensety )
                    this.map.setZoom( this.map.zoom - this.zoomDensety )
            }
        }

        fitOneProject( marker, zoom ) {
            const bounds = new google.maps.LatLngBounds();

            bounds.extend( marker.portfolioMarker.position );

            this.map.panTo( bounds.getCenter() )
            this.map.panToBounds( bounds, this.mapPadding );

            this.map.setZoom( zoom )
        }

        /*
        * -------------- Marker Events
        * */
        __mouseover( marker ) {
            marker.hoverMarker()
        }

        __mouseout( marker ) {
            marker.blurMarker()
        }

        __click( marker ) {
            if ( !this.currentMarker.marker || (this.currentMarker.marker.portfolioMarker.id !== marker.portfolioMarker.id) ) {
                this.currentMarkerProjectId = marker.portfolioMarker.id
                this.currentMarker.marker = marker;
            }

            this.fitSingle( this.currentMarker.marker )
        }

        /*
        * -------------- Actions
        * */
        removeCurrentMarker() {
            this.currentMarkerProjectId = null
            this.currentMarker.marker = null
        }

        changeTheme() {
            this.themeToggler.forEach( themeModeBtn => {
                const changeStyle = () => {
                    let mapDom = $(this.mapDomElement).find('.gm-style')
                    let timeout = 500

                    if ( mapDom.length )
                        timeout = 0

                    setTimeout(() => {
                        let currentTheme = $(themeModeBtn).find('.current').data('theme');
                        mapDom = $(this.mapDomElement).find('.gm-style')

                        if (currentTheme === 'light') {
                            this.map.setOptions({ styles: this.stylesLight });
                            mapDom.css( 'background', this.mapBackgroundLight )
                        } else {
                            this.map.setOptions({ styles: this.stylesDark });
                            mapDom.css( 'background', this.mapBackgroundDark )
                        }
                    }, timeout)
                }

                changeStyle();
                themeModeBtn.addEventListener("click", changeStyle);
            } );
        }

        /*
        * -------------- Marker Visibility
        * */
        showByID( projectId, fitType = 'single' ) {
            let [ targetMarker ] = this.allMarkers.filter( marker => ( marker.portfolioMarker.id == projectId ) )

            if ( targetMarker ) {
                this.currentMarkerProjectId = projectId
                this.currentMarker.marker = targetMarker

                switch (fitType) {
                    case 'single':
                        this.fitSingle( this.currentMarker.marker, true )
                        break
                    case 'group':
                        this.fitSeveral( this.filteringMarkers )
                        break
                    case 'project':
                        this.fitOneProject( this.currentMarker.marker, 8 )
                        break
                }
            }

            return targetMarker
        }

        showByCategory( categorySlug ) {
            this.filteringMarkers = [];
			this.groupSlug = categorySlug

            this.allMarkers.forEach( marker => {
                if ( marker.category.includes( categorySlug ) ) {
                    marker.show()
					this.filteringMarkers.push(marker);
                } else {
                    marker.hide()
                }
            } )

            this.fitBounds()
        }

        showAll() {
            this.groupSlug = ''
            this.filteringMarkers = this.allMarkers
            this.allMarkers.forEach( marker => {
                marker.show()
            } )

            setTimeout(() => {
				this.fitBounds()
			}, 100)
        }
    }


    let initNavigationSliders = function( $parentSection, events ) {
        $parentSection.find('.js-portfolio-navigation-slider').each(function () {
            let $slider = $(this);

            if (!!$slider[0].swiper) {
                $slider[0].swiper.destroy(true, true);
            }

            const navigationSlider = new Swiper($slider[0], {
                slidesPerView: 1,
                slidesPerGroup: 1,
                autoHeight: true,
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


    let initializeBlock = function( isResize = false ) {
        $('.section-portfolio').each(function () {
            let $sectionPortfolio = $(this);
            let googleMaps = null

            // Section V1
            if( $sectionPortfolio.hasClass('section-portfolio--style-v1') ) {
                const sectionId = $sectionPortfolio.data('id')

                if ( window.hasOwnProperty(`googleMapInit_${sectionId}`) ) {
                    googleMaps = window[`googleMapInit_${sectionId}`]
                    googleMaps.update()
                } else {
                    googleMaps = new GoogleMaps( $sectionPortfolio )
                    window[`googleMapInit_${sectionId}`] = googleMaps
                }

                initNavigationSliders( $sectionPortfolio, function() {
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

            // Section V2
            if($sectionPortfolio.hasClass('section-portfolio--style-v2')) {
                function resizeNuvContainer() {
                    let nav = $sectionPortfolio.find('.portfolio-nav').outerHeight()
                    let subNav = $sectionPortfolio.find('.portfolio-subnav').outerHeight()

                    // console.log(nav, subNav);

                    // let heightMax = nav > subNav ? nav : subNav;
                    let heightMax = nav > 250 ? nav : 250;

                    $sectionPortfolio.find('.section-header__nav').css('height', heightMax)
                }

                // resizeNuvContainer();
                initNavigationSliders( $sectionPortfolio, function() {
                    return {
                        slideChange: function() {
                            $sectionPortfolio.find('.js-list-link').each( function() {
                                const projectID = $(this).data('project')
                                const activeSlide = $sectionPortfolio.find('.tab-pane.active').attr('id')

                                if ( projectID != activeSlide )
                                    $(this).removeClass('active')
                            } )
                        }
                    }
                } )


                window.addEventListener('resize', function () {
                    // resizeNuvContainer();
                });
            }

            // Common
            if ($sectionPortfolio.find('.js-portfolio-subnav-opened').length) {
                // Open Submenu
                $sectionPortfolio.find('.js-portfolio-subnav-opened').unbind('click').on('click', function (e) {
                    e.preventDefault();
                    let $currentCategory = $(this);
                    let currentCategoryTitle = $currentCategory.find('.link-text').text().trim();
                    let currentCategoryFilter = $currentCategory.data('filter');
                    let $portfolio = $sectionPortfolio;
                    let $portfolioNav = $currentCategory.parents('.portfolio-nav');
                    let $portfolioSubnav = $portfolioNav.siblings('.portfolio-subnav');
                    let $portfolioSubnavList = $portfolioSubnav.find('.list');

                    $portfolioSubnav.find('.portfolio-subnav__title').text(currentCategoryTitle);

                    $portfolioSubnavList.find('.list-item').each(function () {
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

                    if($portfolio.hasClass('section-portfolio--style-v2')) {
                        $portfolioSubnavList.find('.list-item.visible').first().find('.link').tab('show');
                    } else {
                        $portfolioSubnavList.find('.list-item.visible').first().find('.link').trigger('click');
                    }

                    $portfolioNav.collapse('hide');
                    $portfolioSubnav.collapse('show');

                    if ( googleMaps ) {
                        googleMaps.showByCategory( currentCategoryFilter )
                    }
                });
            }

            if ($sectionPortfolio.find('.js-portfolio-subnav-back').length) {
                // Back In Submenu
                $sectionPortfolio.find('.js-portfolio-subnav-back').unbind('click').on('click', function (e) {
                    let $portfolio = $sectionPortfolio;
                    let $portfolioNav = $portfolio.find('.portfolio-nav');
                    let $portfolioSubnav = $portfolio.find('.portfolio-subnav');

                    $portfolioNav.collapse('show');
                    $portfolioSubnav.collapse('hide');

                    $sectionPortfolio.find('.list-item .link').each(function () {
                        $(this).removeClass('active')
                    });

					$sectionPortfolio.find('.list-item .link').first().addClass('active')

                    if ( googleMaps ) {
                        googleMaps.showAll()
                        googleMaps.removeCurrentMarker()
                    }
                });
            }

            if ( $sectionPortfolio.find('a.js-change-map-project').length ) {
                // Submenu Bind Click
                $sectionPortfolio.find('a.js-change-map-project').unbind('click').on('click', function(e) {
                    e.preventDefault();
                    let $link = $(this);
                    let projectId = $link.data('project');

                    if ( googleMaps ) {
                        googleMaps.showByID( projectId, 'group' )
                    }
                });
            }

            if ( $sectionPortfolio.find('a.js-change-single-map-project').length ) {
                // Project Bind Click
                if ( googleMaps ) {
                    setTimeout( () => {
                        googleMaps.fitBounds();
                    }, 500 )
                }

                $sectionPortfolio.find('a.js-change-single-map-project').unbind('click').on('click', function(e) {
                    e.preventDefault();
                    let $link = $(this);
                    let projectId = $link.data('project');

                    if ( googleMaps ) {
                        googleMaps.showByID( projectId, 'project' )
                    }
                });
            }
        });
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();

        window.addEventListener('resize', function () {
            initializeBlock( 'resize' );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-portfolio', adminInitializeBlock( initializeBlock, '.section-portfolio' ) );
    }
})(jQuery);