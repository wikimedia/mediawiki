/*!
 * If a user hits action=purge directly, submit the form
 * automatically.
 */
( function ( mw, $ ) {
	if ( mw.config.get( 'wgAction' ) !== 'purge' ) {
		// Sanity check
		return;
	}

	$( function () {
		$( '#mw-purge-submit' ).click();
	} );

}( mediaWiki, jQuery ) );
