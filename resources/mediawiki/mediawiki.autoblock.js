/**
 * Checks local storage and cookies to see if user has recently
 * been using a blocked account, and if such block should be sent
 * to MediaWiki in order to trigger autoblocks.
 */
( function( $, mw ) {
	var prefix = mw.config.get( 'wgCookiePrefix' )
	if(
		!$.cookie( prefix + 'BlockID' ) &&
		typeof( Storage ) !== 'undefined' &&
		localStorage.blockID
	) {
		// The block ID exists in storage, but not in the cookie.
		$.cookie( prefix + 'BlockID', localStorage.blockID );
		$.cookie( prefix + 'BlockHash', localStorage.blockHash );
	} else if( $.cookie( prefix + 'BlockID' ) !== '-1' ) {
		// The block ID exists in the cookie, but not in storage.
		localStorage.blockID = $.cookie( prefix + 'BlockID' );
		localStorage.blockHash = $.cookie( prefix + 'BlockHash' );
	}
} )( jQuery, mediaWiki );
