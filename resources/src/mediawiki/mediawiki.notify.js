/**
 * @class mw.plugin.notify
 */
( function ( mw, $ ) {
	'use strict';

	/**
	 * @see mw.notification#notify
	 * @param message
	 * @param options
	 * @return {jQuery.Promise}
	 */
	mw.notify = function ( message, options ) {
		var d = $.Deferred();
		// Don't bother loading the whole notification system if we never use it.
		mw.loader.using( 'mediawiki.notification', function () {
			// Call notify with the notification the user requested of us.
			d.resolve( mw.notification.notify( message, options ) );
		}, d.reject );
		return d.promise();
	};

	/**
	 * @class mw
	 * @mixins mw.plugin.notify
	 */

}( mediaWiki, jQuery ) );
