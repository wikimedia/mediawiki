/*
 * HTMLForm enhancements:
 * Set up autocomplete fields.
 */
mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	const $autocomplete = $root.find( '.mw-htmlform-autocomplete' );
	if ( $autocomplete.length ) {
		mw.loader.using( 'jquery.suggestions', () => {
			$autocomplete.suggestions( {
				fetch: function ( val ) {
					const $el = $( this );
					$el.suggestions( 'suggestions',
						$el.data( 'autocomplete' ).filter( ( v ) => v.startsWith( val ) )
					);
				}
			} );
		} );
	}
} );
