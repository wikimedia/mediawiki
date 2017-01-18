( function ( mw ) {
	'use strict';

	// If a user has been autoblocked, a cookie is set.
	// Its value is replicated here in localStorage to guard against cookie-removal.
	// The localStorage item expires after $wgAutoblockExpiry (default 86400 seconds).
	// This module will only be loaded when $wgCookieSetOnAutoblock is true.
	// Ref: T5233
	//
	// The localStorage value used to be only the block ID, but for T152952 it was changed to also store an expiry time
	// in order that the localStorage data be removed after $wgAutoblockExpiry. After an old-style localStorage item is
	// updated to have an expiry date, it can actually live for another day after the expiry of the cookie; this will
	// not result in the recreation of a block though.
	//
	// Note that the cookie's name has an uppercase 'B' but the storage name has a lowercase 'b'.
	mw.requestIdleCallback( function () {
		var expiry, cookieValue, storedValue, storedObject;

		// Calculate the expiry time in seconds since the epoch.
		expiry = ( mw.now() / 1000 ) + mw.config.get( 'wgAutoblockExpiry' );

		// Add expiry time to an old-style localStorage value.
		storedValue = mw.storage.get( 'blockID' );
		if ( storedValue ) {
			storedObject = JSON.parse( storedValue );
			if ( storedObject.expiry === undefined ) {
				// If there's no 'expiry' property, this must be an old-style localStorage item, so we give it an expiry
				// date and save it. Also set storedValue here to we don't have to re-read the storage value below. 
				storedObject = { data: storedValue, expiry: expiry };
				storedValue = JSON.stringify( storedObject );
				mw.storage.set( 'blockID', storedValue );
			}
		}

		// There are two parts to this next bit:
		// 1. there's no cookie and there is something in localStorage; and
		// 2. there is a cookie and there's nothing in localStorage.

		cookieValue = mw.cookie.get( 'BlockID' ); 
		if ( !cookieValue && storedValue ) {
			// The block ID exists in storage, but the cookie doesn't exist, so we re-create the cookie.
			// The storedValue variable at this point always has the 'data' and 'expiry' properties.
			storedObject = JSON.parse( storedValue );
			if ( storedObject.expiry >= ( mw.now() / 1000 ) ) {
				// localStorage data hasn't expired, so use it to recreate the cookie.
				mw.cookie.set( 'BlockID', storedObject.data, { expires: storedObject.expiry } );
			} else {
				// localStorage has expired, so we delete it.
				mw.storage.remove( 'blockID' );
			}

		} else if ( cookieValue && !storedValue ) {
			// The block ID exists in the cookie, but not in storage.
			storedObject = { data: cookieValue, expiry: expiry };
			mw.storage.set( 'blockID', JSON.stringify( storedObject ) );

		}
	} );

}( mediaWiki ) );
