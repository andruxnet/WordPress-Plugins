(function( $ ) {
	$( document ).ready( function() {
		/* add a new section */
		$( document ).on( 'click', '#add-section', function() {
			var rowHtml = $( '#sections-table tbody tr:first' ).html();
			$( '#sections-table tbody' ).append( '<tr>' + rowHtml + '</tr>' );
		});

		/* delete a section */
		$( document ).on( 'click', '.delete-section', function() {
			if ( $( '.delete-section' ).size() > 1 ) {
				$( this ).closest( 'tr' ).remove();
			}
		});

		/* add a new field */
		$( document ).on( 'click', '#add-field', function() {
			var rowHtml = $( '#fields-table tbody tr:first' ).html();
			var rowNum = parseInt( $( '#fields-table tbody tr:last' ).attr( 'row-number' ) ) + 1;
			rowHtml = rowHtml.replace( /__cf-fields\[\w+\]/g, '__cf-fields[' + rowNum + ']' );
			$( '#fields-table tbody' ).append( '<tr row-number="' + rowNum + '">' + rowHtml + '</tr>' );
			$( '#fields-table tbody tr:last' ).find( 'input, select' ).each( function() {
				if ( $( this ).is( ':checkbox' ) ) {
					$( this ).prop( 'checked', false );
				}
				else if ( $( this ).is( 'select' ) && $( this ).hasClass( 'fields-box' ) ) {
					$( this ).prop( 'value', 'text' );
				}
				else {
					$( this ).prop( 'value', '' );
				}
			});
		});

		/* delete a field */
		$( document ).on( 'click', '.delete-field', function() {
			if ( $( '.delete-field' ).size() > 1 ) {
				$( this ).closest( 'tr' ).remove();
				var rowNum = 0;
				$( 'tr' ).each( function() {
					if ( $( this ).attr( 'row-number' ) ) {
						$( this ).attr( 'row-number', rowNum );
						$( this ).find( '[name^=settings]' ).each( function() {
							var fieldName = $( this ).prop( 'name' );
							fieldName = fieldName.replace( /settings\[\w+\]/g, 'settings[' + rowNum + ']' );
							$( this ).prop( 'name', fieldName );
						});
						rowNum++;
					}
				});
			}
		});

		/* show advanced field settings */
		if ( $( '#show-advanced' ).is( ':checked' ) ) {
			$( '.advanced-settings' ).fadeIn();
		}
		$( document ).on( 'click', '#show-advanced', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '.advanced-settings' ).fadeIn();
			}
			else {
				$( '.advanced-settings' ).fadeOut();
			}
		});

		/* show/hide extra input fields if enabled */
		$( '#mailing-list, #captcha' ).each( function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '#' + $( this ).prop( 'id' ) + '-text' ).fadeIn();
			}
		});
		$( document ).on( 'click', '#mailing-list, #captcha', function() {
			if ( $( this ).is( ':checked' ) ) {
				$( '#' + $( this ).prop( 'id' ) + '-text' ).fadeIn();
			}
			else {
				$( '#' + $( this ).prop( 'id' ) + '-text' ).fadeOut();
			}
		});
 
		/* grab the email address and post it on submission */
		$( document ).on( 'change', '#contact-form input[type=email]', function() {
			var email = $( this ).val();
			$( '#__cf-email-address' ).val( email );
		});
	});
})( jQuery );
