/**
 * @class mw.notification.convertmessagebox
 */
( function ( mw, $ ) {
	'use strict';

	/**
	 * Checks, if a messagebox (.mw-notify-success, .mw-notify-warning, .mw-notify-error) has content
	 * and converts it into a MediaWiki notification with the text of the box or a given
	 * message key. If no further params are set, the notification will automatically hide after 5
	 * seconds, or when the user clicks the element.
	 *
	 * @param {Object} options Options for convertmessagebox
	 * @param {mw.Message} [options.msg] Message key for the notification (needs to be already loaded in JS).
	 * @param {boolean} [options.autoHide] Should the notification automatically hidden after a specific amount of
	 *  seconds? Defaults to true.
	 * @param {number} [options.closeSeconds] How many seconds to wait, before to close the notification.
	 */
	mw.notification.convertmessagebox = function ( options ) {
		var $successBox = $( '.mw-notify-success' ),
			$warningBox = $( '.mw-notify-warning' ),
			$errorBox = $( '.mw-notify-error' ),
			autoHide, notif, msg, $msgBox;

		options = options || {};

		// If there is a message box and javascript is enabled, use a slick notification instead!
		if ( $successBox.length ) {
			$msgBox = $successBox;
			options.type = 'info';
		} else if ( $warningBox.length ) {
			$msgBox = $warningBox;
			options.type = 'warn';
		} else if ( $errorBox.length ) {
			$msgBox = $errorBox;
			options.type = 'error';
		} else {
			return;
		}

		autoHide = $msgBox.attr( 'data-mw-autohide' ) === 'true';

		options = $.extend( {
			closeSeconds: 5,
			type: 'info'
		}, options );

		// if the msg param is given, use it, instead use the text of the successbox
		msg = options.msg ? options.msg : $msgBox.text();
		$msgBox.detach();

		notif = mw.notification.notify( msg, { autoHide: autoHide, autoHideSeconds: options.closeSeconds, type: options.type } );
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
