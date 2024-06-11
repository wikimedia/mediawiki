/**
 * Exposes a method for converting a messagebox to a notification.
 *
 * @module mediawiki.notification.convertmessagebox
 */

/**
 * Convert a messagebox to a notification.
 *
 * @example
 * const convertmessagebox = require( 'mediawiki.notification.convertmessagebox' );
 *
 * @method (require("mediawiki.notification.convertmessagebox"))
 * @param {Object} [options] Options
 * @param {mw.Message} [options.msg] Message key (must be loaded already)
 */
module.exports = function ( options ) {
	const $successBox = $( '.mw-notify-success' ),
		$warningBox = $( '.mw-notify-warning' ),
		$errorBox = $( '.mw-notify-error' );

	// If there is a message box and javascript is enabled, use a slick notification instead!
	let $msgBox, type;
	if ( $successBox.length ) {
		$msgBox = $successBox;
		type = 'info';
	} else if ( $warningBox.length ) {
		$msgBox = $warningBox;
		type = 'warn';
	} else if ( $errorBox.length ) {
		$msgBox = $errorBox;
		type = 'error';
	} else {
		return;
	}

	const autoHide = $msgBox.attr( 'data-mw-autohide' ) === 'true';

	// If the msg param is given, use it, otherwise use the text of the successbox
	const msg = options && options.msg || $msgBox.text();
	$msgBox.detach();

	let notif = mw.notification.notify( msg, { autoHide: autoHide, type: type } );
	if ( !autoHide ) {
		// 'change' event not reliable!
		$( document ).one( 'keydown mousedown', () => {
			if ( notif ) {
				notif.close();
				notif = null;
			}
		} );
	}
};
