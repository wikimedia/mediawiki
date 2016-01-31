/**
 * @class mw.notification.convertsuccessbox
 */
( function ( mw, $ ) {
	'use strict';

	/**
	 * Checks, if the successbox has content and converts it into a MediaWiki notification with the
	 * text of the successbox or a given message key. If no further params are set, the notification
	 * will automatically hit after 5 seconds, otherwise when the user clicks the $closeEl element.
	 *
	 * @cfg {Object} options Options for convertsuccessbox
	 * @cfg {mw.Message} [options.msg] Message key for the notification (needs to be already loaded in JS).
	 * @cfg {boolean} [options.autoHide] Should the notification automatically hidden after a specific amount of
	 *  seconds? Defaults to true.
	 * @cfg {number} [options.closeSeconds] How many seconds to wait, before to close the notification.
	 */
	mw.notification.convertsuccessbox = function ( options ) {
		var $msgBox = $( '.mw-notify-success' ),
			autoHide = $msgBox.data( 'mw-autohide' ),
			notif, msg;

		options = options || {};

		// If there is a success box and javascript is enabled, use a slick notification instead!
		if ( !$msgBox.length ) {
			return;
		}

		$.extend( options, {
			closeSeconds: 5
		} );

		// if the msg param is given, use it, instead use the text of the successbox
		msg = options.msg ? options.msg : $msgBox.text();

		notif = mw.notification.notify( msg, { autoHide: autoHide, autoHideSeconds: options.closeSeconds, type: 'warn' } );
		if ( !autoHide ) {
			// 'change' event not reliable!
			$( document ).one( 'keydown mousedown', function () {
				if ( notif ) {
					notif.close();
					notif = null;
				}
			} );
		}
	};

}( mediaWiki, jQuery ) );
