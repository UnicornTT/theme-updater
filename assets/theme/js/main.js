var app = app || {};
var $ = jQuery;
var locationHash = window.location.hash;

if ('pushState' in history && locationHash ) {
    history.replaceState('', document.title, window.location.pathname + window.location.search);
    history.replaceState('', document.title, window.location.pathname + locationHash);
    window.scrollTo(0, 0);
    setTimeout(function() {
        window.scrollTo(0, 0);
    }, 200);
}

const debounce = (fn, timeout = 300) => {
    let timer;
    return function (...args) {
        if (timer) {
            clearTimeout(timer);
        }

        timer = setTimeout(() => {
            fn.apply(this, args);
        }, timeout);
    };
}

const MainMenuScrollHint = {
    initilize: function () {
        const self = this;
        this._findItems();
        
        if (!this.inited) {
            this.state = "";
            this.disableOffsetBottom = 15;
            this.$menu.on("scroll", this.parentScrollHandlerDebounced.bind(this));
            this.$submenu.on("scroll", this.parentScrollHandlerDebounced.bind(this));

            this.$info.find(".scroll-info").on("click", function () {
                const $menuWrapper = self.isSubmenuOpened()
                  ? $(".submenus-wrapper")
                  : $(".main-menu__menu");

                $menuWrapper.parent().animate(
                    { scrollTop: $menuWrapper.height() },
                    1000
                );
            });

            this.parentScrollHandler();
        }
        this.inited = true;
        this.windowResizeHandlerDebounced();
        this.parentScrollHandlerDebounced();
        this.debounceId = -1;

        this.isNeedScroll = true;
        this.debug = false;
    },

    _findItems: function() {
        this.$menuWrapper = $(".main-menu__inner");
        this.$menu = this.$menuWrapper.find(".main-menu__menu").parent();
        this.$submenu = this.$menuWrapper.find(".submenus-wrapper").parent();
        this.$info = this.$menuWrapper.find(".scroll-info__wrapper");

        this.$buttonWrapper = this.$menuWrapper.find(".main-menu__button-wrap");
    },

    recalc: function () {
        const distanceToBottom = this.calculateDistanceToBottom();

        if (this.debug) {
            console.log("\n\n~~~~ RECALC ~~~~~");
            console.log('scroll distance: ', this.calculateScrollDistance());
            console.log('offset: ', this.disableOffsetBottom);
            console.log('distanse to bottom: ', distanceToBottom);
            console.log("is submenu: ", this.isSubmenuOpened());
            console.log("~~~~ RECALC END ~~~~~\n");
        }

        if (distanceToBottom >= this.disableOffsetBottom) {
            this.show({ source: 'resize' });
        } else {
            this.hide({ source: "resize" });
        }
    },

    windowResizeHandler: function () {
        this.recalc();
    },

    parentScrollHandler: function () {
        this.recalc();
    },

    calculateDistanceToBottom: function () {
        const $menu = this.isSubmenuOpened() ? this.$submenu : this.$menu;
        return $menu.prop("scrollHeight") -
            $menu.height() -
            $menu.scrollTop();
    },

    calculateScrollDistance: function () {
        return this.$menu.prop("scrollHeight") - this.$menu.prop("clientHeight");
    },

    isSubmenuOpened: function () {
        return (
          this.$menuWrapper.find(".submenus").hasClass("opened") &&
          window.matchMedia("(max-width: 1279px)").matches
        );
    },

    show: function({debug = false, source = ''} = {}) {
        if(this.state == "show") return;
        if(this.debug || debug) console.log(`show from ${source}`);
        this.$info.find('.scroll-info').slideDown();
        this.state = "show";
    },

    hide: function({debug = false, source = ''} = {}) {
        if (this.state == "hide") return;
        if(this.debug || debug) console.log(`hide from ${source}`);
        this.$info.find('.scroll-info').slideUp();
        this.state = "hide";
    }
};

MainMenuScrollHint.windowResizeHandlerDebounced = debounce(
    MainMenuScrollHint.windowResizeHandler,
    1000
);

MainMenuScrollHint.parentScrollHandlerDebounced = debounce(
    MainMenuScrollHint.parentScrollHandler,
    400
);

class FontAjustment {
    constructor({el, rem, maxTitleLength, fj, fjSizeFactor = 2}) {
        if(el) this.setElement(el);
        if(rem) this.rem = rem;
        if(maxTitleLength) this.setMaxLength(maxTitleLength);
        if(fj) this.min = fj;
        this.sizeFactor = fjSizeFactor;
        this.reinit();
        this.resize();
    }

    setElement(element) {
        this._element = element;
    }

    setMaxLength(maxTitleLength) {
        this._maxTitleLength = maxTitleLength;
    }

    reinit() {
        if(!this._element.length) return;
        this._element.forEach(elem => elem.style.fontSize = null);
        // this.defaultFont = this._element.
        this.style = window.getComputedStyle(this._element[0]);
        this.fontSizeDefaultPx = parseInt(this.style.fontSize, 10);
    }
    
    resize() {
        this.fontSizePx = this.mapRange(this.fontSizeDefaultPx, 0, Math.max(this.min, this._maxTitleLength), 0, this.min);

        let result = '';
        const docFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
        const px = Math.max(this.fontSizePx, (docFontSize * this.sizeFactor));
        if(this.rem) {
            result = (px / docFontSize) + 'rem';
        } else {
            result = px + "px";
        }
        this._element.forEach(elem => elem.style.fontSize = result);
    }

    mapRange(value, low1, high1, low2, high2){
        return low2 + (high2 - low2) * (value - low1) / (high1 - low1);
    }
}

app.options = {
    breakpointMobile: 768,
    breakpointTablet: 1280,
    winW: document.documentElement.clientWidth,
    winH: window.innerHeight,
    isMobile: false,
    isTablet: false,
    isBlockEditor: $('body').hasClass('block-editor-page'),
    playersCount: 0,
    youtubeIframeAPIInit: false,
    playerInfoList: [],
    players: [],
};

app.main = {
    youtubePlayers: function () {
        $(document).ready(function() {
            /* Create players */
            $('.js-youtube-player').each(function() {
                let player = this,
                    playerID = $(player).attr('id'),
                    videoID = $(player).attr('data-video-id'),
                    modalID = $(player).parents('.modal').attr('id'),
                    videoInfo = {};

                videoInfo.id = playerID;
                videoInfo.modal = modalID;
                videoInfo.video = videoID;
                app.options.playerInfoList.push(videoInfo);
            });

            /* Modal video */
            $('.modal-video').on('shown.bs.modal', function (e) {
                let modal = $(this);
                let modalID = $(this).attr('id');

                if(modal.find('video').length) {
                    modal.find('video').first().trigger('play');
                }

                if(modal.find('.js-youtube-player').length) {
                    if(app.options.players[modalID]) {
                        app.options.players[modalID].playVideo();
                    } else {
                        return;
                    }
                }
            });

            $('.modal-video').on('hide.bs.modal', function (e) {
                let modal = $(this);
                let modalID = $(this).attr('id');

                if(modal.find('video').length) {
                    modal.find('video').first().trigger('pause');
                }

                if(modal.find('.js-youtube-player').length) {
                    if(app.options.players[modalID]) {
                        app.options.players[modalID].stopVideo();
                    } else {
                        return;
                    }
                }
            });
        });

        window.createPlayer = function createPlayer(player, video) {
            return new YT.Player(player, {
                playerVars: {
                    'playsinline': 1,
                    'origin': location.hostname
                },
                events: {
                    'onReady'() {
                        app.options.playersCount = app.options.playersCount + 1;
                    }
                },
                height: '100%',
                width: '100%',
                videoId: video
            });
        };

        window.onYouTubeIframeAPIReady = function onYouTubeIframeAPIReady() {
            if(!app.options.youtubeIframeAPIInit) {
                app.options.youtubeIframeAPIInit = true;
            }

            if( $('.js-youtube-player').length === 0 ) {
                return;
            }

            if( $('.js-youtube-player').length != app.options.playerInfoList.length || typeof YT === 'undefined' ) {
                let timerId = setTimeout(function () {
                    onYouTubeIframeAPIReady();
                }, 2000);
                return;
            } else {
                for(let i = 0; i < app.options.playerInfoList.length; i++) {
                    app.options.players[app.options.playerInfoList[i].modal] = createPlayer(app.options.playerInfoList[i].id, app.options.playerInfoList[i].video);
                }
            }
        };
    },
    successfullInitPage: function () {
        if(!app.options.youtubeIframeAPIInit) {
            onYouTubeIframeAPIReady();
        }

        if( !$('.preloader').hasClass('hide') ){
            if(!$('.js-youtube-player').length) {
                $('.preloader').addClass('hide').fadeOut(300);
                $('body').removeClass('scroll-off');

                if (locationHash) {
                    if($(locationHash).hasClass('modal') && !$(locationHash).hasClass('js-modal-form')) {
                        return;
                    } else if($(locationHash).hasClass('js-modal-form')) {
                        $(locationHash).modal('show');
                    } else {
                        let headerHeight = $('.page-header').outerHeight();
                        let distanceTop = $(locationHash)[0].offsetTop - headerHeight;
                        $('.main-wrapper').animate({scrollTop: distanceTop}, 600);
                    }
                }

                return;
            } else if($('.js-youtube-player').length === app.options.playersCount) {
                $('.preloader').addClass('hide').fadeOut(300);
                $('body').removeClass('scroll-off');

                if (locationHash) {
                    if($(locationHash).hasClass('modal') && !$(locationHash).hasClass('js-modal-form')) {
                        return;
                    } else if($(locationHash).hasClass('js-modal-form')) {
                        $(locationHash).modal('show');
                    } else {
                        let headerHeight = $('.page-header').outerHeight();
                        let distanceTop = $(locationHash)[0].offsetTop - headerHeight;
                        $('.main-wrapper').animate({scrollTop: distanceTop}, 600);
                    }
                }

                return;
            } else {
                let timerId = setTimeout(function () {
                    app.main.successfullInitPage();
                }, 2000);
                return;
            }
        } else {
            return;
        }
    },
    onLoad: function () {
        /* website loaded */
        $(window).on("load", function () {
            app.main.successfullInitPage();
        });

        /* website takes too long to load */
        setTimeout(function() {
            app.main.successfullInitPage();
        }, 10000);
    },
    themeSwitcher: function () {
        let $themeSwitchers = $('.theme-switcher');
        if ( !$themeSwitchers.length ) return false;

        // if ( $('body').hasClass('theme-light') ) {
        //     $themeSwitchers.find('.theme-switcher__item').removeClass('current').filter('[data-theme="light"]').addClass('current');
        // }

        $themeSwitchers.on('click', switchTheme);
    },
    mainMenu: function () {
        let $headerMenuBtn = $('.page-header__menu-button'),
            $menu = $('.main-menu'),
            $menuWrap = $menu.find('.main-menu__menu'),
            $menuList = $('.main-menu').find('.menu'),
            $menuInner = $menu.find('.main-menu__inner'),
            $menuOverlay = $('.main-menu-overlay'),
            $menuBack = $('.main-menu__back');

        $menuList.children('li').each(function () {
            let $item = $(this);
            let itemIndex = $item.index();
            $item.attr('id', 'main-menu-item-' + itemIndex);
        });

        $headerMenuBtn.on('click', function (e) {
            e.preventDefault();
            let $this = $(this);
            if ( $this.hasClass('active') ) {
                $menuOverlay.fadeOut();
                $this.removeClass('active');
                $menu.removeClass('active subnav-active');
                $('body').removeClass('no-scroll');
                $('.submenus').removeClass('opened');
                $('.submenus').find('.sub-menu').removeClass('show');
                $('.main-menu__back').removeClass('show');
            } else {
                $menuOverlay.fadeIn();
                $this.addClass('active');
                $menu.addClass('active');
                $('body').addClass('no-scroll');
            }
        });

        $menu.on('click', function (e) {
            if ( $(e.target).is( $menu ) ) {
                $headerMenuBtn.click();
            }
        });

        let $submenus = $menu.find('.menu>li>ul.sub-menu');

        if ( $submenus.length ) {
            let $submenusWrap = $('<div class="submenus"><div class="scrollbar-outer"><div class="submenus-wrapper"></div></div></div>');
            $menuWrap.append($submenusWrap);
            $submenus.each(function (i, item) {
                let $submenu = $(item);
                $submenu.attr('id', `sub-${$submenu.closest('li.menu-item-has-children').attr('id')}`)
                $submenu.appendTo($('.submenus-wrapper'));
            });

            $menuWrap.children('.menu').children('.menu-item').on('mouseenter', function () {
                    if( app.options.isMobile || app.options.isTablet ) {
                        return;
                    } else {
                        let $this = $(this);
                        if ( $this.hasClass('menu-item-has-children') ) {
                            $menuWrap.find('a.active').removeClass('active');
                            $this.children('a').addClass('active');
                            let id = $this.attr('id');
                            $menu.addClass('subnav-active');
                            $submenusWrap.find('.sub-menu').removeClass('show');
                            $submenusWrap.find(`#sub-${id}`).addClass('show');
                            $submenusWrap.addClass('opened');
                        } else {
                            $submenusWrap.find('.sub-menu').removeClass('show');
                            $submenusWrap.removeClass('opened');
                            $menuWrap.find('a.active').removeClass('active');
                            $menu.removeClass('subnav-active');
                            $menuBack.removeClass('show');
                        }
                    }
                }
            );

            $menuWrap.children('.menu').children('.menu-item').children('a').on('click', function (e) {
                    let $link = $(this);

                    if( app.options.isMobile || app.options.isTablet ) {
                        if( $(e.target).hasClass('parent-menu-item-arrow') ) {
                            e.preventDefault();
                            let $this = $(this).parent('li');
                            if ( $this.hasClass('menu-item-has-children') ) {
                                let id = $this.attr('id');
                                $menu.addClass('subnav-active');
                                $submenusWrap.find('.sub-menu').removeClass('show');
                                $submenusWrap.find(`#sub-${id}`).addClass('show');
                                $submenusWrap.addClass('opened');
                                $menuBack.addClass('show');
                                MainMenuScrollHint.parentScrollHandlerDebounced()
                            } else {
                                $submenusWrap.find('.sub-menu').removeClass('show');
                                $submenusWrap.removeClass('opened');
                                $menuBack.removeClass('show');
                                $menu.removeClass('subnav-active');
                            }
                        } else if($link.attr('href') == '#') {
                            e.preventDefault();
                        }
                    } else {
                        return;
                    }
                }
            );

            $menuInner.on('mouseleave', function () {
                if( app.options.isMobile || app.options.isTablet ) {
                    return;
                } else {
                    $submenusWrap.find('.sub-menu').removeClass('show');
                    $submenusWrap.removeClass('opened');
                    $menuWrap.children('.menu').children('.menu-item').children('.active').removeClass('active');
                    $menu.removeClass('subnav-active');
                }
            });

            if( $menuBack.length ) {
                $menuBack.on('click', function (e) {
                    e.preventDefault();
                    $submenusWrap.find('.sub-menu').removeClass('show');
                    $submenusWrap.removeClass('opened');
                    $menuBack.removeClass('show');
                    $menu.removeClass('subnav-active');
                    MainMenuScrollHint.parentScrollHandlerDebounced()
                });
            }
        }

    },
    hideFocusOnDesktop: function () {
        $('a, button, input[type="checkbox"], input[type="radio"]').on('mouseup, mouseleave', function() {
            $(this).blur();
        });

        $('.modal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let modal = $(this);

            modal.on('hidden.bs.modal', function (e) {
                setTimeout(function blur() {
                    button.blur();
                }, 50);
            })
        });
    },
    resizeDependent: function () {
        let vh = window.innerHeight * 0.01;
        let vw;
        if($('#main-wrapper').length) {
            vw = document.querySelector('#main-wrapper').clientWidth * 0.01;
        } else {
            vw = document.documentElement.clientWidth * 0.01;
        }
        document.documentElement.style.setProperty("--vh", `${vh}px`);
        document.documentElement.style.setProperty("--vw", `${vw}px`);

        app.options.isMobile = (window.matchMedia('screen and (max-width: 767.5px)').matches);
        app.options.isTablet = ( (window.matchMedia('screen and (max-width: 1279.5px)').matches) && (window.matchMedia('screen and (min-width: 768px)').matches) );
        app.main.disableScrollRestoration();
        app.main.horizontalScroll();

        if($('.sidebar--mobile').length && app.options.isMobile ) {
            $('.sidebar--mobile').find('.collapse-panel__body').collapse('hide');
            $('.sidebar--mobile').find('.collapse-panel__toggler').addClass('collapsed').attr('aria-expanded', false);
        }
    },
    customScrollbars: function () {
        if ($('.scrollbar-outer').length) {

            setTimeout(function () {
                $('.scrollbar-outer').scrollbar({
                    ignoreMobile: true,
                    ignoreOverlay: true,
                    // disableBodyScroll: true,
                    passive: true,
                    onUpdate:
                        MainMenuScrollHint.windowResizeHandlerDebounced.bind(
                            MainMenuScrollHint
                        ),
                });
            }, 1000);
        }
    },
    modalGalleryDefault: function () {
        if( $('.modal-gallery-default').length ) {
            $('.modal-gallery-default').each(function () {
                let $modal = $(this);

                $modal.on('show.bs.modal', function (e) {
                    let $sliderModalGallery = $modal.find('.js-modal-gallery');
                    let $slides = $sliderModalGallery.find('.swiper-slide');
                    let $relatedTargetLink = $(e.relatedTarget);
                    let relatedSlideIndex = $relatedTargetLink.parents('.swiper-slide').index();
					let loop = true;

                    if($slides.length <= 1) {
                        loop = false;
                    }

                    let swiperModalGallery = new Swiper($sliderModalGallery[0], {
                        slidesPerView: 1,
                        speed: 1000,
                        spaceBetween: 0,
                        loop: loop,
                        loopedSlides: 10,
                        loopAdditionalSlides: 10,
                        normalizeSlideIndex: false,
                        effect: 'fade',
                        fadeEffect: {
                            crossFade: true,
                        },
                        navigation: {
                            nextEl: $sliderModalGallery.parents('.modal-gallery').find('.swiper-button-next')[0],
                            prevEl: $sliderModalGallery.parents('.modal-gallery').find('.swiper-button-prev')[0],
                        },
                        pagination: {
                            el: $sliderModalGallery.parents('.modal-gallery').find('.swiper-pagination')[0],
                            type: 'fraction',
                        },
                        on: {
                            init: function () {
                                this.slideTo(relatedSlideIndex);
                            }
                        }
                    });

                    $modal.on('hidden.bs.modal', function (e) {
                        swiperModalGallery.destroy(true, true);
                    });
                });

            });
        }
    },
    smoothScrollToBlock: function () {
        if( $('a.js-scroll-link, .js-scroll-link>a').length ) {
            $('a.js-scroll-link, .js-scroll-link>a').unbind().on('click', function (e) {
                //e.preventDefault();

                let headerHeight = $('.page-header').outerHeight();

                let link = $(this),
                    target = link.attr('href').split('#')[1],
                    distanceTop = $('#'+target)[0].offsetTop - headerHeight;

                history.replaceState('', document.title, window.location.pathname + '#' + target);

                if( !!target ) {
                    $('.main-wrapper').animate({scrollTop: distanceTop}, 600);

                    if( $('.main-menu').hasClass('active') ) {
                        $('.page-header__menu-button.active').trigger('click');
                    }
                } else {
                    return;
                }
            });
        }
    },
    jobApplicationLoadFileHandler: function() {
        $('#job-application').on('shown.bs.modal', function () {
            if(!$('#job-application').attr('data-init-modal-observe')) {
                let fileLoadField = $('#job-application .file_upload-container .files_uploaded')[0],
                    config = {
                        childList: true,
                    }

                let fileLoadObserver = new MutationObserver((mutation) => {
                    if(mutation[0].addedNodes.length) {
                        $('#job-application .file_upload-container .nf-fu-fileinput-button span').text('Replace Resume');
                        $('#job-application .file_upload-container .nf-fu-fileinput-button').addClass('file-loaded');
                    } else {
                        $('#job-application .file_upload-container .nf-fu-fileinput-button span').text('Add Resume');
                        $('#job-application .file_upload-container .nf-fu-fileinput-button').removeClass('file-loaded')
                    }
                });

                fileLoadObserver.observe(fileLoadField, config);
                $('#job-application').attr('data-init-modal-observe', 'true');
            } else {
                return;
            }
        })
    },
    disableScrollRestoration: function() {
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
    },
    horizontalScroll: function () {
        if($('.js-horizontal-scroll').length) {
            let horizontalScroll = undefined;

            // horizontal scroll
            $.fn.hScroll = function (amount) {
                amount = amount || 120;
                horizontalScroll = function (event) {
                    let oEvent = event.originalEvent,
                        direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta,
                        position = $(this).scrollLeft();
                    position += direction > 0 ? -amount : amount;
                    $(this).scrollLeft(position);
                    event.preventDefault();
                }
                $(this).bind("DOMMouseScroll mousewheel", horizontalScroll);
            };

            $('.js-horizontal-scroll').each(function () {
                if($(this)[0].scrollWidth > $(this)[0].clientWidth) {
                    // You can pass (optionally) scrolling amount
                    $(this).hScroll(60);
                } else {
                    $(this).unbind("DOMMouseScroll mousewheel", horizontalScroll);
                }
            });
        }
    },
    wrapperObserver: function () {
        if($('.main-wrapper').length) {
            let targetObserver = document.getElementsByTagName('body')[0];

            const configObserver = {
                attributes: true,
                childList: false,
                subtree: false
            };

            const callbackObserver = function(mutationsList, observer) {
                for (let mutation of mutationsList) {
                    app.main.resizeDependent();
                }
            };

            const observer = new MutationObserver(callbackObserver);

            observer.observe(targetObserver, configObserver);
        }
    },
    modalFormHashes: function () {
        if($('.js-modal-form').length) {
            $('.js-modal-form').on('show.bs.modal', function () {
                let $modal = $(this);
                let modalHash = '#'+$modal.attr('id');
                history.replaceState('', document.title, window.location.pathname + modalHash);
            });

            $('.js-modal-form').on('hidden.bs.modal', function () {
                history.replaceState('', document.title, window.location.pathname);
            });
        }
    },
    nfModalReady: function () {
        $(document).on( 'nfFormReady' , function () {
            if(typeof Marionette !== 'undefined') {
                $('.section-subscribe-our-newsletters__form, .widget--form').each(function () {
                    const nfFormCont = $(this).find('.nf-form-cont')
                    const formID = nfFormCont.attr('id').match(/[1-9_]+/)[0]
                    const $formContainer = $(`#nf-form-${formID}-cont`)

                    if ( $formContainer.hasClass('.footer-email-custom') )
                        return

                    const $inputs = $formContainer.find('input:not(input[type="button"])')
                    const NewslettersFormExtend = Marionette.Object.extend( {
                        initialize() {
                            Backbone.Radio.channel( 'form-' + formID ).reply( 'maybe:submit', this.beforeSubmit, this, formID);

                            $inputs.keypress( event => {
                                if (event.which == 10 || event.which == 13) {
                                    Backbone.Radio.channel( 'form-' + formID ).request( 'maybe:submit', formID );
                                }
                            })
                        },

                        beforeSubmit( formObject ) {
                            const formModel = nfRadio.channel( 'app' ).request( 'get:form', formObject );

                            if ( formModel.getExtra('formIsOk') ) {
                                const $subButton = $formContainer.find('input[type="button"].nf-element')
                                const $buttonID = $subButton.attr('id')

                                $formContainer.find(`#${$buttonID}-wrap .nf-field-element`).addClass('nf-field-element-loading')
                                nfRadio.channel( 'form-' + formID).request( 'add:extra', 'formIsOk', false );

                                return true;
                            }

                            this.trySubmit( formModel );
                            return false;
                        },

                        trySubmit( formModel ) {
                            if ( !formModel.get( 'errors' ).length ) {
                                nfRadio.channel( 'form-' + formID).request( 'add:extra', 'formIsOk', true );
                                nfRadio.channel( 'form-' + formModel.get( 'id' ) ).request( 'submit', formModel );
                            }
                        }
                    });

                    new NewslettersFormExtend();
                })

            }
        } );
    },
    nfModalSuccess: function () {
        $(document).on('nfFormSubmitResponse', function ( event, response ) {
            event.preventDefault();
            let successPopup = $(`#modal-success-popup`);
            let otherPopups = $(`.modal.show:not(#modal-success-popup)`);
            if(successPopup.length) {
                if(otherPopups.length) {
                    otherPopups.modal('hide');
                    successPopup.find('.text-content').html(response.response.data.actions.success_message);
                    setTimeout(function(){ successPopup.modal('show'); }, 150);
                } else {
                    successPopup.find('.text-content').html(response.response.data.actions.success_message);
                    successPopup.modal('show');
                }
            }
        });
    },
    adminSettingsBlockResizer: function () {
        if($('html').hasClass('opened-sidebar')) {
            let elementWrap = $('.interface-interface-skeleton__sidebar')[0];
            let element;
            if (!elementWrap) return;
            //create box in bottom-left
            let resizer = document.createElement('button');
            resizer.className = "sidebar-resizer";
            //Append Child to Element
            elementWrap.appendChild(resizer);
            //box function onmousemove
            resizer.addEventListener('mousedown', initResize, false);

            setTimeout(function () {
                element = $(elementWrap).find('.interface-complementary-area')[0];

                $(element).find('.interface-complementary-area-header .components-button[aria-label="Close settings"]').on('click', function (e) {
                    $('html').removeClass('opened-sidebar');
                    $('.post-type-page .interface-interface-skeleton__sidebar').find('.sidebar-resizer').remove();
                });
            }, 300);

            //Window function mousemove & mouseup
            function initResize(e) {
                if(e.button === 0) {                    
                    window.addEventListener('mousemove', Resize, false);
                    window.addEventListener('mouseup', stopResize, false);
                }
            }
            //resize the element
            function Resize(e) {
                element.style.width = (document.documentElement.clientWidth - e.pageX) + 'px';
            }
            //on mouseup remove windows functions mousemove & mouseup
            function stopResize(e) {
                $(resizer).blur();
                window.removeEventListener('mousemove', Resize, false);
                window.removeEventListener('mouseup', stopResize, false);
            }
        }
    },
    ajustFont: function (){
        Array.from(document.querySelectorAll('.js-font-adjustment')).forEach(el => {
            let maxTitleLength;
            let fj = el.dataset.fjMin
            let fjSizeFactor = +el.dataset.fjSizeFactor || 2;
            let titles = el.querySelectorAll('.js-font-title');
            if(titles.length) {
                maxTitleLength = getTitleMaxLength(titles);
                const t = new FontAjustment({
                    el: titles,
                    rem: true,
                    maxTitleLength,
                    fj,
                    fjSizeFactor,
                });
            }
        })

        function getTitleMaxLength(titles) {
            let maxLength = 0;
            titles.forEach(title => {
                let titleLength = title.innerText.trim().length;
                if(titleLength > maxLength) {
                    maxLength = titleLength;
                }
            })

            return maxLength;
        }
    }
};

app.init = function () {
    if($('body').hasClass('wp-admin')) {
        $('body').addClass('theme-light');

        setTimeout(function () {
            app.main.youtubePlayers();
            app.main.onLoad();
            app.main.themeSwitcher();
            app.main.mainMenu();
            app.main.resizeDependent();
            app.main.wrapperObserver();
            app.main.customScrollbars();
            app.main.modalGalleryDefault();
            app.main.smoothScrollToBlock();
            app.main.horizontalScroll();
            app.main.disableScrollRestoration();
            app.main.jobApplicationLoadFileHandler();
            app.main.modalFormHashes();
            app.main.ajustFont();

            
            const adminOptionButton = $('.edit-post-header__settings .interface-pinned-items .components-button');
            const config = {
                attributes: true, 
                attributeOldValue: true,
                attributeFilter: ['class']
            };
            const reziserObserverCallback = (mutationList, observer) => {
                if(adminOptionButton.hasClass('is-pressed')) {
                    $('html').addClass('opened-sidebar');
                    app.main.adminSettingsBlockResizer();
                } else {
                    $('html').removeClass('opened-sidebar');
                    $('.post-type-page .interface-interface-skeleton__sidebar').find('.sidebar-resizer').remove();
                }
            };
            
            if(adminOptionButton.length) {
                reziserObserverCallback();
                const observer = new MutationObserver(reziserObserverCallback);
                observer.observe(adminOptionButton[0], config);
            } 

            /* this code block always will be in the end! */
            app.main.hideFocusOnDesktop();
            /* this code block always will be in the end! */                    

            setInterval(() => {
                app.main.ajustFont();

                if ($('.scrollbar-outer').length) {
                    $('.scrollbar-outer').each(function () {
                        if( !$(this).hasClass('scroll-wrapper') && !$(this).hasClass('scroll-content') ) {
                            $(this).scrollbar({
                                ignoreMobile: true,
                                ignoreOverlay: true,
                                // disableBodyScroll: true,
                                passive: true,
                            });
                        }
                    });
                }
            }, 1000)
        }, 2000);
    }

    app.main.youtubePlayers();
    app.main.onLoad();
    app.main.themeSwitcher();
    app.main.mainMenu();
    app.main.resizeDependent();
    app.main.wrapperObserver();
    app.main.customScrollbars();
    app.main.modalGalleryDefault();
    app.main.smoothScrollToBlock();
    app.main.horizontalScroll();
    app.main.jobApplicationLoadFileHandler();
    app.main.modalFormHashes();
    app.main.ajustFont();
    app.main.nfModalReady();

    setTimeout(function () {
        app.main.nfModalSuccess();
    }, 2000);

    /* this code block always will be in the end! */
    app.main.hideFocusOnDesktop();
    /* this code block always will be in the end! */
};


$(function() {
    app.init();
});

function initMap() {
    return;
}

window.addEventListener('resize', function () {
    this.setTimeout(() => {
        app.main.resizeDependent();
        app.main.ajustFont();
    }, 500)
});

function switchTheme(e) {
    e.preventDefault();
    let $themeSwitcher = $('.theme-switcher'),
        currentTheme = $themeSwitcher.find('.theme-switcher__item.current').attr('data-theme');

    if ( currentTheme === 'dark' ) {
        $themeSwitcher.find('.theme-switcher__item').removeClass('current').filter('[data-theme="light"]').addClass('current');
        $('body').removeClass('theme-dark').addClass('theme-light');
        document.cookie = 'theme_color_mode=light; path=/';

    } else if ( currentTheme === 'light' ) {
        $themeSwitcher.find('.theme-switcher__item').removeClass('current').filter('[data-theme="dark"]').addClass('current');
        $('body').removeClass('theme-light').addClass('theme-dark');
        document.cookie = 'theme_color_mode=dark; path=/';
    }
}

(function($){
    let initializeBlock = function( $block ) {
        MainMenuScrollHint.windowResizeHandlerDebounced();

        const $scrollContent = $(".main-menu__inner").find(".scroll-content");
        let currentMaxHeight = $scrollContent.css("max-height");
        if (currentMaxHeight) {
        currentMaxHeight = parseFloat(currentMaxHeight);
        }
        $scrollContent.css("max-height", `${currentMaxHeight + 1}px`);

    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        MainMenuScrollHint.initilize();
        initializeBlock( $(this) );
        window.addEventListener('resize', function () {
            // MainMenuScrollHint.windowResizeHandler();
            initializeBlock( $(this) );
        });
    });
})(jQuery);

(function ($){
    let replaceNameOfDate = function () {
        let fullNameDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let abbreviatedNameDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        if($('.mec-calendar-table').length > 0) {
            const currentMonth = $('.mec-calendar-table').find('.mec-month-container').filter(function() {
                const styleValue = $(this).css('display');

                return styleValue === 'block' ? true : false;
            })

            const pastEventDay =  $(currentMonth).find('.mec-table-nullday .events-list-for-day');

            if ( $(pastEventDay)[0] ) {
                pastEventDay.parent().addClass('past-day-event');
            }

            let calendarDayHead = $(currentMonth[0]).find('.mec-calendar-table-head .mec-calendar-day-head');

            calendarDayHead.each(function (index) {
                $(this).text(fullNameDays[index])
            })

            if(window.matchMedia('(max-width: 1024.5px)').matches) {
                calendarDayHead.each(function (index) {
                    $(this).text(abbreviatedNameDays[index])
                })
            }
        }
    }

    const createCustomFilter = () => {
        if($('.mec-calendar-table').length > 0) {
            $('.calendar .postform').each(function() {
                const _this = $(this),
                    selectOption = _this.find('option'),
                    selectOptionLength = selectOption.length,
                    selectedOption = selectOption.filter(':selected'),
                    duration = 300;

                _this.hide();
                _this.wrap('<div class="select"></div>');

                const filterContent = $('<div>', {
                    class: 'filter-content',
                }).insertBefore(_this);

                $('<div>', {
                    class: 'icon-filter new-icon-filter',
                    html: $('<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="M7 11V1.5L28 1L29 4.5L28.5 8.5L27.5 11L22.5 18.5V27L13 29.5V22.5V18L7 11Z" fill="#121212" fill-opacity="0.1"/>\n' +
                        '<path d="M29.5757 1.07551C29.5757 0.484339 29.0966 0.0041909 28.5043 0.0041909L7.07145 0C6.78686 0 6.51477 0.113025 6.31387 0.313802C6.11303 0.514642 6 0.78776 6 1.07132L6.00316 9.1341C6.00419 10.4104 6.46248 11.647 7.29325 12.6167L11.6595 17.7096C12.1576 18.2922 12.4328 19.035 12.4328 19.8009V28.9287C12.4328 29.2583 12.5845 29.57 12.845 29.7729C13.0355 29.9215 13.2677 30 13.5042 30C13.5911 30 13.6779 29.9896 13.7637 29.9676L22.3352 27.825C22.8123 27.7057 23.1429 27.2779 23.1429 26.7861V19.8009C23.1429 19.035 23.4223 18.2922 23.9214 17.7096L28.2856 12.6167C28.9104 11.8884 29.324 11.0088 29.4915 10.0749C29.5757 1.07551 29.5757 10.0749 29.5757 1.07551ZM26.6586 11.2234L22.2943 16.3161C21.4625 17.2858 21.0043 18.5235 21.0043 19.801V25.9493L14.5715 27.5561V19.8009C14.5715 18.5235 14.1174 17.2858 13.2856 16.316L8.92035 11.2233C8.42228 10.6405 8.144 9.89774 8.1429 9.133V2.14245L27.4286 2.14284L27.4313 6.40331C27.4313 6.41195 27.4286 6.41994 27.4286 6.42858C27.4286 6.43735 27.4313 6.44534 27.4313 6.45411L27.4328 9.13203C27.4328 9.89774 27.1577 10.6405 26.6586 11.2234Z" fill="#121212"/>\n' +
                        '</svg>\n', {
                    })
                }).appendTo(filterContent);

                const selectHead = $('<div>', {
                    class: 'new-select',
                    text: _this.children('option:selected').text()
                }).appendTo(filterContent);

                $('<div>', {
                    class: 'icon-arrow',
                    html: $('<span class="icon-wrap">\n' +
                        '<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                        '<path d="M5.27441 5.58631L9.88692 0.961018C10.038 0.809476 10.0377 0.564125 9.88614 0.412837C9.73459 0.261666 9.48911 0.262056 9.33795 0.413618L4.99998 4.7636L0.662031 0.413461C0.51086 0.261919 0.265529 0.261528 0.113967 0.41268C0.0379906 0.48852 1.89475e-06 0.587875 1.8904e-06 0.68723C1.88607e-06 0.786331 0.0377369 0.885296 0.113186 0.960998L4.72557 5.58631C4.79819 5.6593 4.89701 5.70026 4.99998 5.70026C5.10295 5.70026 5.20166 5.65918 5.27441 5.58631Z" fill="white" fill-opacity="0.4"/>\n' +
                        '</svg>\n'+
                        '</span>', {
                    })
                }).appendTo(filterContent);

                // const selectHead = _this.next('.new-select');
                $('<div>', {
                    class: 'new-select__list'
                }).insertAfter(filterContent);
                filterContent.next('.new-select__list').html('<div class="scrollbar-outer"><div class="new-select__content"></div></div>');
                filterContent.find('.scrollbar-outer').scrollbar({
                    ignoreMobile: true,
                    ignoreOverlay: true,
                    // disableBodyScroll: true,
                    passive: true,
                });


                const selectList = filterContent.next('.new-select__list');
                const selectListWrapper = filterContent.next('.new-select__list').find('.new-select__content');
                for (let i = 0; i < selectOptionLength; i++) {
                    $('<div>', {
                        class: 'new-select__item',
                        html: $('<span>', {
                            text: selectOption.eq(i).text()
                        })
                    })
                        .attr('data-value', selectOption.eq(i).val())
                        .appendTo(selectListWrapper);
                }

                const selectItem = selectList.find('.new-select__item');
                const arrowItem = $('.calendar .select .icon-arrow');
                selectList.slideUp(0);

                const changeSelectValue = () => {
                    selectList.slideUp(duration);
                    selectHead.toggleClass('on');
                    arrowItem.toggleClass('flip');

                    if ( selectHead.hasClass('on') ) {
                        selectList.slideDown(duration);

                        selectItem.on('click', function() {
                            let chooseItem = $(this).data('value');
                            const defaultSelect = $('.calendar select');

                            defaultSelect.val(chooseItem);
                            defaultSelect[0].dispatchEvent(new Event('change'));
                            selectHead.text( $(this).find('span').text() );

                            selectHead.removeClass('on');
                            arrowItem.removeClass('flip');
                            selectList.slideUp(duration);
                            selectItem.off();
                        });
                    }
                }

                filterContent.on('click', function () {
                    changeSelectValue();
                })

                $(document).mouseup(function (e) {
                    if ($('.calendar .select').has(e.target).length === 0){
                        selectHead.removeClass('on');
                        arrowItem.removeClass('flip');
                        selectList.slideUp(duration);
                    }
                });
            });
        }
    }

    const createEventCard = () => {
        const mediaQuery = window.matchMedia('(max-width: 1024.5px)');

        if($('.mec-calendar-table').length > 0 && mediaQuery.matches) {
            const currentMonth = $('.mec-calendar-table').find('.mec-month-container').filter(function() {
                const styleValue = $(this).css('display');

                return styleValue === 'block' ? true : false;
            })

            let eventDays = $(currentMonth[0]).find('.mec-calendar-day--with-events');
            let isPastDays;

            if(!eventDays.length) {
                const isPastDayWithEvent = !!$(currentMonth[0]).find('.mec-past-event').length;
                eventDays = isPastDayWithEvent ? $(currentMonth[0]).find('.mec-table-nullday .events-list-for-day').parent() : null;
                isPastDays = isPastDayWithEvent ? true : false;
            }

            const cardEvents = $('.calendar__card-events'),
                cardDate = $('.calendar__card .text-date');

            if (!!$(eventDays).length) {
                const firstItem = eventDays[0],
                    pastEventDay = $(currentMonth[0]).find('.mec-table-nullday .events-list-for-day').parent();

                const checkTypeOfDay = (day) => {
                    if(!!$(day).hasClass('mec-selected-day')) {
                        $('.calendar .calendar__card').removeClass('feature');
                        $('.calendar .calendar__card').addClass('today');
                    } else if(!!$(day).hasClass('mec-table-nullday')) {
                        $('.calendar .calendar__card').removeClass('today');
                        $('.calendar .calendar__card').removeClass('feature');
                    } else {
                        $('.calendar .calendar__card').removeClass('today');
                        $('.calendar .calendar__card').addClass('feature');
                    }
                }

                const createCardContent = (newEvent) => {
                    $('.calendar .calendar__card').fadeOut(400, () => {
                        const scheduleEvents = $(newEvent).find('.event-content-wrapper'),
                            dateOnCard = $(newEvent).find('.date-number').addClass('active').text();

                        const date = dateOnCard;
                        cardDate.text(date);
                        cardEvents.html('');

                        $(scheduleEvents).each(function () {
                            const nameEvent = $(this).find('.mec-event-title').text(),
                                idForm = $(this).find('.mec-event-title').attr('data-target'),
                                eventId = $(this).find('.mec-event-title').attr('data-event-id'),
                                timeEvent = $(this).find('.time').text();

                            $('<div>', {
                                class: 'event-item',
                                html: `<h4 class="event-item-link" data-target="${idForm}" data-toggle="modal" data-event-id="${eventId}" aria-label="modal-link">
                                ${nameEvent}
                                </h4>
        
                                <p class="event-item-time">
                                    ${timeEvent}
                                </p>`
                            }).appendTo(cardEvents);
                        })

                        checkTypeOfDay(newEvent);

                        $('.calendar .calendar__card').fadeIn(500);
                    });
                }

                eventDays.off('click');

                eventDays.on('click', (e) => {
                    const newEvent = $(e.currentTarget);
                    $(eventDays).find('.date-number').removeClass('active');
                    $(pastEventDay).find('.date-number').removeClass('active');
                    $(newEvent).find('.date-number').addClass('active');
                    createCardContent(newEvent);
                });

                if(!!pastEventDay.length && !isPastDays) {
                    pastEventDay.off('click');

                    pastEventDay.on('click', (e) => {
                        const newEvent = $(e.currentTarget);
                        pastEventDay.find('.date-number').removeClass('active');
                        eventDays.find('.date-number').removeClass('active');
                        $(newEvent).find('.date-number').addClass('active');
                        createCardContent(newEvent);
                    })
                }

                createCardContent(firstItem);
            } else {
                $('.calendar .calendar__card').fadeOut();
            }
        }
    }

    let observer;

    const waitingForNewContent = () => {
        if($('.calendar__content .mec-modal-result').length) {
            const preloader = $('.calendar__content .mec-modal-result');

            let observer = new MutationObserver(() => {
                if(preloader.hasClass('mec-month-navigator-loading')){
                    $('.calendar .calendar__card').fadeOut(300);
                    $('.mec-calendar .mec-calendar-table').slideUp();
                } else {
                    $('.mec-calendar .mec-calendar-table').slideDown();
                };

            })

            observer.observe(preloader[0], {
                childList: false,
                subtree: true,
                characterDataOldValue: true,
                attributes: true
            });
        }
    }

    const createObserver = () => {
        const calendarContent = $('.calendar__content')[0];

        const changePositionModals = () => {
            let modalsArray = [];

            $('.mec-calendar .event-modal').each(function () {
                const newItem = $(this)[0].cloneNode(true);
                modalsArray.push(newItem);
            });

            $('body').find('.event-modal').remove();
            modalsArray.forEach((item)=>{
                $('body')[0].appendChild(item);
            })
            app.main.customScrollbars();
        }

        if(!!calendarContent) {
            observer = new MutationObserver(() => {
                const currentMonth = $('.mec-calendar-table').find('.mec-month-container').filter(function() {
                    const styleValue = $(this).css('display');

                    if(styleValue === 'none') {
                        $(this)[0].remove();
                        return false;
                    }

                    return true;
                })

                changePositionModals();

                if($(currentMonth[0]).children().length > 0) {
                    createEventCard();
                    replaceNameOfDate();

                    observer.disconnect();

                    setTimeout(()=>{
                        createObserver();
                    }, 300)
                }
            });

            observer.observe(calendarContent, {
                childList: true,
                subtree: true,
                characterDataOldValue: true,
                attributes: false
            });
        }
    }

    const checkChangeOrientation = () => {
        let orientationNow = 0;
        let supportScreenOrientation = true;
        let callbackLockOrientation;

        if (!window.screen || !window.screen.orientation) {
            supportScreenOrientation = false;
        }

        if (supportScreenOrientation) {
            callbackLockOrientation = () => {
                lockOrientation({ orientation: window.screen.orientation.angle });
                orientationNow = window.screen.orientation.angle;
            };

            window.screen.orientation.addEventListener("change", callbackLockOrientation);
        } else {
            orientationNow = window.orientation;
            window.orientationchange = () => {
                lockOrientation({ orientation: window.orientation });
            };
        }

        function clearEvents() {
            if (supportScreenOrientation) {
                window.screen.orientation.removeEventListener(
                    "change",
                    callbackLockOrientation
                );
            } else {
                window.orientationchange = null;
            }
        }

        function lockOrientation({ orientation }) {
            const portrait = orientation % 180 == 0;
            replaceNameOfDate();

            setTimeout(()=>{
                // createEventCard();
                replaceNameOfDate();
            },0)
        }

        lockOrientation({ orientation: orientationNow });
    }

    $(document).ready(function(){
        replaceNameOfDate();
        createEventCard();
        checkChangeOrientation();
        createCustomFilter();
        createObserver();
        waitingForNewContent();
    });

    // Initialize dynamic block preview (editor).
})(jQuery)

function initConsoleLog() {
    console.log( 'test' );
}