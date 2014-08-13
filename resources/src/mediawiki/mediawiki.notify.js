/**
 * @class mw.plugin.notify
 */
( function ( mw ) {
	'use strict';

	/**
	 * @see mw.notification#notify
	 * @param message
	 * @param options
	 * @return {jQuery.Promise}
	 */
	mw.notify = function ( message, options ) {
		// Don't bother loading the whole notification system if we never use it.
		return mw.loader.using( 'mediawiki.notification' )
			.then( function () {
				// Call notify with the notification the user requested of us.
				return mw.notification.notify( message, options );
			} );
	};

	/**
	 * @class mw
	 * @mixins mw.plugin.notify
	 */

}( mediaWiki ) );
