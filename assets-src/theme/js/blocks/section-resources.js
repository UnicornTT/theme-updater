(function($){
    let ajax_more_posts = undefined;
    let ajax_filterd_resources = undefined;
    let currentEl = undefined;
    let currentCategory = 'all';
    let currentTags = [];

    let getFilteredResources = function(currentEl){
        if(ajax_filterd_resources) { ajax_filterd_resources.abort(); }

        var filterContainer = $(currentEl).parents('.section-resources__controls').find('.filter-results-block')[0];
        var filterTagsList = $($(filterContainer).find('.filter-tags-list')[0]);

        $(currentEl).parents('.section-resources').find('.select').addClass('disabled');
        $(currentEl).parents('.section-resources').find('.dropdown').addClass('disabled');

        currentTags = [];
        filterTagsList.children().each(function(){
            currentTags.push($(this).attr('tag-id'));
        });

        var formData = {
            'categoryId': currentCategory,
            'tags': currentTags
        }

        ajax_filterd_resources = $.ajax({
            url : themeVars.ajaxurl + 'get_filtered_resources',
            data : formData,
            type : 'POST',
            dataType: 'html',
            beforeSend : function (){
                $(currentEl).parents('.section-resources').find('.section-resources__grid-wrapper').addClass('loader-visible');
                $(currentEl).parents('.section-resources').find('.section-resources__grid').removeAttr('style');
            },
        }).done(function(response) {
            currentEl
                .parents('.section-resources__main')
                .find('.section-resources__grid')
                .empty()
                .append(response);
            ajax_filterd_resources = undefined;
            var height = $(currentEl).parents('.section-resources').find('.section-resources__grid')[0].scrollHeight;
            $(currentEl).parents('.section-resources').find('.section-resources__grid').css('max-height', height);
            $(currentEl).parents('.section-resources').find('.section-resources__grid-wrapper').removeClass('loader-visible');
            $(currentEl).parents('.section-resources').find('.select').removeClass('disabled');
            $(currentEl).parents('.section-resources').find('.dropdown').removeClass('disabled');
        }).error(function(jqXHR, textStatus, errorThrown) {
            ajax_filterd_resources = undefined;
            //console.log( jqXHR + " :: " + textStatus + " :: " + errorThrown );
        });
    }

    const createCustomFilter = () => {
        let defaultSelect = $('.section-resources.section-resources--style-v1 .resources-categories-select');

        defaultSelect.each(function() {
                const _this = $(this),
                    selectOption = _this.find('option'),
                    selectOptionLength = selectOption.length,
                    selectedOption = selectOption.filter(':selected'),
                    duration = 300;

                if(!_this.parent().hasClass('init')) {
                    //_this.hide();
                    //_this.wrap('<div class="select"></div>');

                    //const filterContent = $('<div>', {
                    //    class: 'filter-content-resource',
                    //}).insertBefore(_this);
                    const filterContent = _this.siblings('.filter-content-resource')

                    //const selectHead = $('<div>', {
                    //    class: 'new-select',
                    //    text: `Categories: ${_this.children('option:selected').text()}`
                    //}).appendTo(filterContent);
                    const selectHead = filterContent.find('.new-select');

                    //$('<div>', {
                    //    class: 'icon-arrow',
                    //    html: $('<span class="icon-wrap">\n' +
                    //        ' <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                    //        '<path d="M12 4.92896L19.0711 12L12 19.0711" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>\n' +
                    //        '</svg>\n'+
                    //        '</span>', {
                    //    })
                    //}).appendTo(filterContent);

                    //$('<div>', {
                    //    class: 'new-select__list'
                    //}).insertAfter(filterContent);

                    const selectList = filterContent.next('.new-select__list');
                    /*for (let i = 0; i < selectOptionLength; i++) {
                        $('<div>', {
                            class: 'new-select__item',
                            html: $('<span>', {
                                text: selectOption.eq(i).text()
                            })
                        })
                            .attr('data-value', selectOption.eq(i).val())
                            .appendTo(selectList);
                    }*/

                    const selectItem = selectList.find('.new-select__item');
                    const arrowItem = selectHead.siblings('.icon-arrow');
                    selectList.slideUp(0);

                    const changeSelectValue = () => {
                        selectList.slideUp(duration);
                        selectHead.toggleClass('on');
                        arrowItem.toggleClass('flip');

                        if ( selectHead.hasClass('on') ) {
                            selectList.slideDown(duration);

                            selectItem.on('click', function() {
                                let chooseItem = $(this).data('value');

                                defaultSelect.val(chooseItem);
                                defaultSelect[0].dispatchEvent(new Event('change'));
                                selectHead.text( `Categories: ${$(this).find('span').text()}` );

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
                        if (_this.parent('.select').has(e.target).length === 0){
                            selectHead.removeClass('on');
                            arrowItem.removeClass('flip');
                            selectList.slideUp(duration);
                        }
                    });

                    _this.parent().addClass('init');
                }
            });
    }

    let initializeBlock = function() {
        if($('.section-resources--style-v1').length > 0) {
            createCustomFilter();
        }

        $('.section-resources--style-v1').each(function() {
            var height = $(this).find('.section-resources__grid')[0].scrollHeight;
            $(this).find('.section-resources__grid').css('max-height', height);
        });

        if ($('.js-recent-blogs-slider').length) {
            $('.js-recent-blogs-slider').each(function () {

                let $slider = $(this);

                if (!!$slider[0].swiper) {
                    $slider[0].swiper.destroy(true, true);
                }

                const swiperRecentBlogs = new Swiper($slider[0], {
                    slidesPerView: 1,
                    slidesPerGroup: 1,
                    spaceBetween: 0,
                    loop: true,
                    loopedSlides: 10,
                    loopAdditionalSlides: 10,
                    speed: 1000,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true,
                    },
                    navigation: {
                        nextEl: this.querySelector('.swiper-button-next'),
                        prevEl: this.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: this.querySelector('.swiper-pagination'),
                        type: 'fraction',
                    }
                });
            });
        }

        if( $('.load_more_posts').length > 0 ){
            var max_pages = $('.load_more_posts').attr('data-max-pages');
            var more_button_text = $('.load_more_posts').text();
            var posts = JSON.parse(themeVars.posts);
            var paged = posts.paged == 0 ? posts.paged + 2 : posts.paged + 1;

            $('.load_more_posts').click(function(event){
                event.preventDefault();

                const $button = $(this);
                const requestUrl = $button.attr("href");

                if (ajax_more_posts) { return; }

                var button = $(this),
                    data = {
                        'query': themeVars.posts,
                        'page' : paged
                    };

                    ajax_more_posts = $.ajax({ // you can also use $.post here
                        url : themeVars.ajaxurl + 'load_more_resources', // AJAX handler
                        data : data,
                        type : 'POST',
                        dataType: 'text',
                        beforeSend : function ( xhr ) {
                            button.text('Loading...');
                        },
                        success : function( data ){
                            ajax_more_posts = undefined;
                            button.text( more_button_text );

                            if( data ) {
                                $('#resources-grid').append(data); // insert new posts

                                if ( paged == max_pages )
                                    button.remove(); // if last page, remove the button

                                paged++;

                                $button.attr("href", $button.attr("href").replace(/\/page\/[0-9]?/, '/page/'+paged));
                                // you can also fire the "post-load" event here if you use a plugin that requires it
                                // $( document.body ).trigger( 'post-load' );
                            } else {
                                button.remove(); // if no data, remove the button as well
                            }
                        },
                        complete: function () {
                            window.history.pushState({}, "", requestUrl);
                        },
                        error : function( jqXHR, textStatus, errorThrown ){
                            ajax_more_posts = undefined;
                            button.text( more_button_text );
                            console.log( jqXHR + " :: " + textStatus + " :: " + errorThrown );
                        }
                    });
            });
        }

        if( $('.resources-categories-select').length > 0 ) {
            $('.resources-categories-select').change(function(){
                currentCategory = this.value.toString();
                getFilteredResources($(this));
            });
        }

        if( $('.js-resources-tag-select').length > 0 ) {
            $('.js-resources-tag-select').click(function(){
                var currentTag = $(this);
                currentTag.addClass('dropdown-item--selected');

                var tagId = $(this).data('tag-id');
                var tagName = $(this).data('tag-name');
                var filterContainer = $(this).parents('.section-resources__controls').find('.filter-results-block')[0];
                var tagTemplate = $($(filterContainer).find('.template .filter-tag')[0]);
                var filterTagsList = $(filterContainer).find('.filter-tags-list')[0];

                var existingTag = false;

                currentTags = [];
                $(filterTagsList).children().each(function(){
                    var itemTagId = $(this).attr('tag-id');
                    if(itemTagId == tagId){
                        existingTag = true;
                    }
                });

                if(!existingTag){
                    tagTemplate
                        .clone()
                        .attr('tag-id', tagId)
                        .appendTo(filterTagsList)
                        .find('.filter-tag__text')
                        .text(tagName).next().click(function(){
                            $(this).parent().remove();
                            currentTag.removeClass('dropdown-item--selected');
                            getFilteredResources(currentTag);
                        });

                    getFilteredResources($(this));
                }
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
        window.acf.addAction( 'render_block_preview/type=section-resources', adminInitializeBlock( initializeBlock, '.section-resources' ) );
    }

})(jQuery);
