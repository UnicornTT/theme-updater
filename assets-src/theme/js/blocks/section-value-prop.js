(function($){
	let initializeBlock = function() {

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
		window.acf.addAction( 'render_block_preview/type=section-value-prop', adminInitializeBlock( initializeBlock, '.section-value-prop' ) );
	}

})(jQuery);