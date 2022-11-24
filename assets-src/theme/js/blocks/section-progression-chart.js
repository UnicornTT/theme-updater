(function ($) {
    let initializeBlock = function () {
        if (!$('.section-progression-chart .js-faq-item-toggler').length) {
            return false;
        } else {
            let $alwaysOneOpen = true;

            $('.section-progression-chart .js-faq-item-toggler').on('click', function (e) {
                e.preventDefault();
                let $btn = $(this);
                let $section = $btn.parents('.section-progression-chart');
                let $accordionItem = $btn.parents('.faq-item');
                let $accordionItemCollapse = $accordionItem.find('.faq-item__body');

                if (!$accordionItem.hasClass('opened')) {
                    $section.find('.js-faq-item-toggler').attr('disabled', true);
                    $accordionItem.siblings().removeClass('opened');
                    $accordionItem.siblings().find('.faq-item__body').collapse('hide');
                    $accordionItem.addClass('opened');
                    $accordionItemCollapse.collapse('show');
                    $accordionItemCollapse.on('shown.bs.collapse', function (e) {
                        $section.find('.js-faq-item-toggler').removeAttr('disabled');
                    });
                } else {
                    if ( !$alwaysOneOpen ) {
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
    }

    // Initialize each block on page load (front end).
    $(document).ready(function () {
        initializeBlock();

        window.addEventListener('resize', function () {
            initializeBlock();
        });
    });

    // Initialize dynamic block preview (editor).
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=section-progression-chart', adminInitializeBlock( initializeBlock, '.section-progression-chart' ));
    }

})(jQuery);