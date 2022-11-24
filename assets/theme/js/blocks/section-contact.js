(function($){
    let initializeSubmitButtonAnimation = function () {
        $(document).on( 'nfFormReady' , function () {
            if(typeof Marionette !== 'undefined') {
                const $parentSections = $(".js-section-contact-us");
                if (!$parentSections.length) return;

                $parentSections.each(function () {
                    $(this).find('.section-contact-us__form').each(function () {
                        const nfFormCont = $(this).find('.nf-form-cont')
                        const formID = nfFormCont.attr('id').match(/[1-9_]+/)[0]
                        const $formContainer = $(`#nf-form-${formID}-cont`)

                        if ( $formContainer.hasClass('.footer-email-custom') )
                            return

                        const $inputs = $formContainer.find('input:not(input[type="button"])')
                        const NewslettersFormExtend = Marionette.Object.extend( {
                            initialize: function() {
                                Backbone.Radio.channel( 'form-' + formID ).reply( 'maybe:submit', this.beforeSubmit, this, formID);

                                $inputs.keypress( event => {
                                    if (event.which == 10 || event.which == 13) {
                                        Backbone.Radio.channel( 'form-' + formID ).request( 'maybe:submit', formID );
                                    }
                                })
                            },

                            beforeSubmit: function( formObject ) {
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

                            trySubmit: function( formModel ) {
                                if ( !formModel.get( 'errors' ).length ) {
                                    nfRadio.channel( 'form-' + formID).request( 'add:extra', 'formIsOk', true );
                                    nfRadio.channel( 'form-' + formModel.get( 'id' ) ).request( 'submit', formModel );
                                }
                            }
                        });

                        new NewslettersFormExtend();
                    })
                });
            }
        } )
    }

    let initializeBlock = function() {
        if (!$('.section-contact-us .js-faq-item-toggler').length) {
            return false;
        } else {

            $('.section-contact-us .js-faq-item-toggler').on('click', function (e) {
                e.preventDefault();
                let $btn = $(this);
                let $section = $btn.parents('.section-contact-us');
                let $accordionItem = $btn.parents('.faq-item');
                let $accordionItemCollapse = $accordionItem.find('.faq-item__body');

                if(!$accordionItem.hasClass('opened')) {
                    $section.find('.js-faq-item-toggler').attr('disabled', true);
                    $accordionItem.siblings().removeClass('opened');
                    $accordionItem.siblings().find('.faq-item__body').collapse('hide');
                    $accordionItem.addClass('opened');
                    $accordionItemCollapse.collapse('show');
                    $accordionItemCollapse.on('shown.bs.collapse', function (e) {
                        $section.find('.js-faq-item-toggler').removeAttr('disabled');
                    });
                } else {
                    if($('.section-contact-us .faq-item.opened').length > 1) {
                        $section.find('.js-faq-item-toggler').attr('disabled', true);
                        $accordionItem.siblings().removeClass('opened');
                        $accordionItem.siblings().find('.faq-item__body').collapse('hide');
                        $accordionItem.removeClass('opened');
                        $accordionItemCollapse.collapse('hide');
                        $accordionItemCollapse.on('hidden.bs.collapse', function (e) {
                            $section.find('.js-faq-item-toggler').removeAttr('disabled');
                        });
                    }
                }
            });
        }

        initializeSubmitButtonAnimation();
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
        window.acf.addAction( 'render_block_preview/type=section-contact', adminInitializeBlock( initializeBlock, '.js-section-contact-us' ) );
    }

})(jQuery);