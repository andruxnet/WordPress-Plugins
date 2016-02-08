jQuery( document ).ready( function( $ ) {
	$( '.add-hook' ).click( function() {
		var hookData = $( this ).closest( 'table' ).find( '.hook-data' ).filter( function() { return this.value != '';} );
		var $this = $( this );

		if ( hookData.length < 4 )
			return false;

		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: 'action=ajax_add_hook&' + hookData.serialize(),
			success: function( response ) {
				$this.closest( 'table' ).next().find( 'table' ).append( response );
			}
		});
	});
});