(function($){
    const hideScroll = () => {
        $('.section-our-process--style-v1').each(function() {
            const parent = $(this);

            setTimeout(()=>{
                const scrollX = parent.find('.scroll-x');
                const scrollTrackWidth = scrollX.find('.scroll-element_track').width();
                const scrollBarWidth = scrollX.find('.scroll-bar').width();
                $(scrollX).removeClass('hide');

                if(scrollTrackWidth === scrollBarWidth) {
                    scrollX.addClass('hide');
                }
            },1000)
        })
    }

    let initializeBlock = function() {
        //add id for tab item
        $('.section-our-process--style-v1 .js-our-process__item').each((index, item) => {
            $(item).attr('id', `js-one-process-${index}`);
        })

        $('.section-our-process--style-v1').each(function() {
            const parent = $(this);

            // calculate one block height
            parent.find('.js-one-process').each( function() {
                const target = $(this)
                target.show();
                let height = target.height();
                target.attr('data-height', height);

                if( target.hasClass('active') )
                    parent.find(`.js-our-process__content`).height(height)
                else
                    target.hide();
            } );

            /*----------------------------------------------------------------------------------*
            |                                                                                  |
                     Smooth scrolling to element our process!
            |                                                                                  |
            *----------------------------------------------------------------------------------*/

            const smoothScrollToActiveProcess = (item) => {
                const processItem = $(item);
                const processItemId = processItem.attr('id');

                if( !!processItemId ) {
                    $(`#${processItemId}`)[0].scrollIntoView({block: "nearest", inline: "center",  behavior: "smooth"});
                }
            }

            if( window.acf ) return

            // Select click event
            const targetEvents = $._data(parent.find('.js-our-process__item[data-index]')[0], 'events');
            const clickEvents = targetEvents ? targetEvents.click : false;

            if(!clickEvents && parent.length) {
                let activeTab = -1;
                let animationFinished = true;

                parent.find('.js-our-process__item[data-index]').on('click', activeOutProcess)
                parent.find('.js-our-process__item[data-index]').on('keydown', activeOutProcess)

                function activeOutProcess({type, code}) {
                    const animationDuration = 200;
                    const index = $(this).attr('data-index');
                    const newHeight = parent.find(`.js-one-process[data-index=${index}]`).attr('data-height');
                    const currentIndex = parent.find('.js-one-process.active').attr('data-index');

                    if(type === 'keydown') {
                        if(!$(this).is(":focus") || code !== 'Enter') return;
                    }
                    if(index == currentIndex || activeTab == index) return;
                    if(!animationFinished) return;

                    activeTab = index;

                    parent.find('.js-our-process__item.active').removeClass('active');
                    $(this).addClass('active');

                    parent.find('.js-one-process.active').fadeOut(animationDuration, function() {
                        animationFinished = false;
                        $(this).removeClass('active');

                        parent.find('.js-our-process__content').animate({ height: newHeight }, animationDuration, 'swing', function() {
                            parent.find(`.js-one-process[data-index=${index}]`).fadeIn(animationDuration, function() {
                                $(this).addClass('active');
                                animationFinished = true;
                            });
                        });
                    });

                    smoothScrollToActiveProcess($(this));
                }
            }
        })

        hideScroll();
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
        window.acf.addAction( 'render_block_preview/type=section-our-process', adminInitializeBlock( initializeBlock, '.section-our-process' ) );
    }

})(jQuery);