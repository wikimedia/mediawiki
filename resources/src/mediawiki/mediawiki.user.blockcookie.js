( function ( mw ) {
	var wgCookiePrefix;

	// If a user has been autoblocked, two cookies are set.
	// Their values are replicated here in localStorage to guard against cookie-removal.
	// Ref: https://phabricator.wikimedia.org/T5233
	wgCookiePrefix = mw.config.get( 'wgCookiePrefix' );
	if ( !mw.cookie.get( wgCookiePrefix + 'BlockID' ) && mw.storage.get( 'blockID' ) ) {
		// The block ID exists in storage, but not in the cookie.
		mw.cookie.set( wgCookiePrefix + 'BlockID', mw.storage.get( 'blockID' ) );
	} else if ( parseInt( mw.cookie.get( wgCookiePrefix + 'BlockID' ), 10 ) > 0 ) {
		// The block ID exists in the cookie, but not in storage.
		// (When a block expires the cookie remains but its value is '', hence the integer check above.)
		mw.storage.set( 'blockID', mw.cookie.get( wgCookiePrefix + 'BlockID' ) );
	}

}( mediaWiki ) );
