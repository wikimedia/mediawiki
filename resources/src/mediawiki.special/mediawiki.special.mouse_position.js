( function ( mw, $ ) {
	$( function () {
		$( '#wpCreateaccount' ).click( function ( e_event ) {
			var Coordinates = {
				x: 'e_event'.clientX,
				y: 'e_event'.clientY
			};
			mw.track( 'mediawiki.special.mouse_postion.js', Coordinates );
		} );
	} )
}( mw, jQuery ) );