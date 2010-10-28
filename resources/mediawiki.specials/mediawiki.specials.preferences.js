/*
 * JavaScript for Special:Preferences
 */

$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
$( '#preferences' )
	.addClass( 'jsprefs' )
	.before( $( '<ul id="preftoc"></ul>' ) )
	.children( 'fieldset' )
	.hide()
	.addClass( 'prefsection' )
	.children( 'legend' )
	.addClass( 'mainLegend' )
	.each( function( i ) {
		$(this).parent().attr( 'id', 'prefsection-' + i );
		if ( i === 0 ) {
			$(this).parent().show();
		}
		$( '#preftoc' ).append(
			$( '<li></li>' )
				.addClass( i === 0 ? 'selected' : null )
				.append(
					$( '<a></a>')
						.text( $(this).text() )
						.attr( 'href', '#prefsection-' + i )
						.mousedown( function( e ) {
							$(this).parent().parent().find( 'li' ).removeClass( 'selected' );
							$(this).parent().addClass( 'selected' );
							e.preventDefault();
							return false;
						} )
						.click( function( e ) {
							$( '#preferences > fieldset' ).hide();
							$( '#prefsection-' + i ).show();
							e.preventDefault();
							return false;
						} )
				)
		);
	} );

// Lame tip to let user know if its email is valid. See bug 22449
$( '#mw-input-emailaddress' )
	.keyup( function() {
		var mailtxt = $(this).val();
		if( mailtxt == '' ) {
			// mail is optional !
			$(this).removeClass( "invalid" );
			$(this).removeClass( "valid" );
			return;
		}
		if( mailtxt.match( /.+@.+\..+/ ) ) {
			$(this).addClass( "valid" );
			$(this).removeClass( "invalid" );
		} else {
			$(this).addClass( "invalid" );
			$(this).removeClass( "valid" );
		}
	} );
