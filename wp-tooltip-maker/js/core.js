jQuery( document ).ready( function( $ ) {
	$( 'a.tooltip' ).cluetip({
		fx: {
			open:'fadeIn',
			openSpeed: '1'
		},
		//ajaxCache: true,
		dropShadow: false,
		cursor: 'text',
		tracking: true,
		waitImage: true,
		showTitle: true,
		clickThrough: false,
		attribute: 'href',
		height: 'auto',
		width: 'auto',
		arrows: false
	});
});
