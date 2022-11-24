/*(function($){
    const initNinjaForm = function($parentSection) {
        $(document).on( 'nfFormReady' , function () {
            if(typeof Marionette !== 'undefined') {
                $parentSection.find('.section-subscribe-our-newsletters__form').each(function () {
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
        } )
    }

    let initializeBlock = function() {
        const $parentSections = $(".js-section-subscribe-our-newsletters");
        if (!$parentSections.length) return;

        $parentSections.each(function() {
            initNinjaForm($(this))
        })

    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        initializeBlock();

        window.addEventListener("resize", function () {
            initializeBlock();
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=section-value-prop', adminInitializeBlock( initializeBlock, '.js-section-subscribe-our-newsletters' ) );
    }

})(jQuery);*/