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
	 * @cfg {number} [options.closeSeconds] How many seconds to wait, before to close the notification. (Only
	 *  observed, if options.autoHide true)
	 */
	mw.notification.convertsuccessbox = function ( options ) {
		var $msgBox = $( '.mw-notify-success-js' ),
			notif, msg;

		// If there is a success box and javascript is enabled, use a slick notification instead!
		if ( !$msgBox.length ) {
			return;
		}

		$.extend( options, {
			closeSeconds: 5,
			autoHide: true
		} );

		// if the msg param is given, use it, instead use the text of the successbox
		msg = options.msg ? options.msg : $msgBox.text();

		notif = mw.notification.notify( msg, { autoHide: options.autoHide, autoHideSeconds: options.closeSeconds } );
		if ( !options.autoHide ) {
			// 'change' event not reliable!
			$( document ).one( 'keydown mousedown', function () {
				if ( notif ) {
					notif.close();
					notif = null;
				}
			} );
		}

		// Remove now-unnecessary success=1 querystring to prevent reappearance of notification on reload
		if ( history.replaceState ) {
			history.replaceState( {}, document.title, location.href.replace( /&?success=1/, '' ) );
		}
		$msgBox.remove();
	};

}( mediaWiki, jQuery ) );
