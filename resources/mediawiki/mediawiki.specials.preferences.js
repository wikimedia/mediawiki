/*
 * JavaScript for Special:Preferences
 */

$( '#prefsubmit' ).attr( 'id', 'prefcontrol' ).end()
$( '#preferences' )
	.addClass( 'jsprefs' )
	.before( $( '<ul id="preftoc"></ul>' ) )
	.find( '> fieldset' )
	.hide()
	.addClass( 'prefsection' )
	.find( '> legend' )
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
