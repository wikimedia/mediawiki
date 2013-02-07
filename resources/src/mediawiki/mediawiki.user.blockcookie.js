( function ( mw ) {

	// If a user has been autoblocked, a cookie is set.
	// Its value is replicated here in localStorage to guard against cookie-removal.
	// This module will only be loaded when $wgCookieSetOnAutoblock is true.
	// Ref: https://phabricator.wikimedia.org/T5233

	if ( !mw.cookie.get( 'BlockID' ) && mw.storage.get( 'blockID' ) ) {
		// The block ID exists in storage, but not in the cookie.
		mw.cookie.set( 'BlockID', mw.storage.get( 'blockID' ) );

	} else if ( parseInt( mw.cookie.get( 'BlockID' ), 10 ) > 0 && !mw.storage.get( 'blockID' ) ) {
		// The block ID exists in the cookie, but not in storage.
		// (When a block expires the cookie remains but its value is '', hence the integer check above.)
		mw.storage.set( 'blockID', mw.cookie.get( 'BlockID' ) );

	} else if ( mw.cookie.get( 'BlockID' ) === '' && mw.storage.get( 'blockID' ) ) {
		// If only the empty string is in the cookie, remove the storage value. The block is no longer valid.
		mw.storage.remove( 'blockID' );

	}

}( mediaWiki ) );
