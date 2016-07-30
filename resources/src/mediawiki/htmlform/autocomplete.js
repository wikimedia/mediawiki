/*
 * HTMLForm enhancements:
 * Set up autocomplete fields.
 */
( function ( mw, $ ) {

	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $autocomplete = $root.find( '.mw-htmlform-autocomplete' );
		if ( $autocomplete.length ) {
			mw.loader.using( 'jquery.suggestions', function () {
				$autocomplete.suggestions( {
					fetch: function ( val ) {
						var $el = $( this );
						$el.suggestions( 'suggestions',
							$.grep( $el.data( 'autocomplete' ), function ( v ) {
								return v.indexOf( val ) === 0;
							} )
						);
					}
				} );
			} );
		}
	} );

}( mediaWiki, jQuery ) );
