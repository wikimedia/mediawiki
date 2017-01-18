(

/**
 * If a user has been autoblocked, a cookie is set.
 * Its value is replicated here in localStorage to guard against cookie-removal.
 * The localStorage item will be removed after $wgAutoblockExpiry (default 86400 seconds).
 * This module will only be loaded when $wgCookieSetOnAutoblock is true.
 * Ref: T5233
 *
 * This used to just store the block ID, but for T152952 was changed to also store an expiry time in order that the
 * localStorage data be removed after $wgAutoblockExpiry (default 86400 seconds).
 */
function ( mw ) {
	var expiry, cookieVal, storageValue, storedObject, storedExpiry;

	// Calculate the expiry time (add milliseconds to current time).
	expiry = Date.now() + ( mw.config.get( 'wgAutoblockExpiry' ) * 1000 );

	//cookieVal = mw.cookie.get( 'BlockID' );
	console.log( document.cookie );

	// There are three parts to this next bit:
	// 1. cookie doesn't exist and there's nothing in localStorage;
	// 2. cookie does exist and there's nothing in localStorage; and
	// 3. cookie is blank and there is something in localStorage.

	if ( !mw.cookie.get( 'BlockID' ) && mw.storage.get( 'blockID' ) ) {
		// The block ID exists in storage, but the cookie doesn't exist, so we re-create the cookie.
		// The cookie value is either just the whole stored value, or just the 'data' element if it exists.
		storageValue = mw.storage.get( 'BlockID' );
		storedObject = JSON.parse( storageValue );
		if ( ! "data" in storedObject ) {
			// If there's no 'data' property, this must be an old-style localStorage item, so we give it an expiry date.
			storedObject = { data: storageValue, expiry: expiry }
		}
		storedExpiry = new Date( storedObject.expiry );
		if ( storedExpiry >= new Date() ) {
			// localStorage data hasn't expired, so use it to recreate the cookie.
			mw.cookie.set( 'BlockID', storedObject.data );
		}

	} else if ( mw.cookie.get( 'BlockID' ) !== null && !mw.storage.get( 'blockID' ) ) {
		// The block ID exists in the cookie, but not in storage.
		// (When a block expires the cookie remains but its value is ''.)
		cookieVal = mw.cookie.get( 'BlockID' );
		storedObject = { data: cookieVal, expiry: expiry };
		mw.storage.set( 'blockID', JSON.stringify( storedObject ) );

	} else if ( mw.cookie.get( 'BlockID' ) === '' && mw.storage.get( 'blockID' ) ) {
		// If only the empty string is in the cookie, remove the storage value. The block is no longer valid.
		mw.storage.remove( 'blockID' );

	}

}( mediaWiki )

);
