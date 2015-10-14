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
	 * @cfg {jQuery} options.$msgBoxEl The jQuery object for the successbox.
	 * @cfg {string} [options.msg] Message key for the notification (needs to be already loaded in JS).
	 * @cfg {jQuery} [options.$closeEl] The jQuery object of the element which, when clicked/changed,
	 *  closes the notification.
	 * @cfg {string} [options.closeSeconds] How many seconds to wait, before to close the notification. (Only
	 *  observed, if options.closeSelector isn't set)
	 */
	mw.notification.convertsuccessbox = function ( options ) {
		var notif, msg, autoHide;

		if ( options.$msgBoxEl === undefined ) {
			mw.log.warn( 'Convert successbox: No message box given.' );
			return;
		}

		$.extend( options, {
			closeSeconds: 5
		} );

		// If there is a success box and javascript is enabled, use a slick notification instead!
		if ( options.$msgBoxEl.length ) {
			autoHide = options.$closeEl === undefined;

			// if the msg param is given, use it, instead use the text of the successbox
			msg = options.msg ? mw.msg( options.msg ) : options.$msgBoxEl.text();

			notif = mw.notification.notify( msg, { autoHide: autoHide, autoHideSeconds: options.closeSeconds } );
			if ( !autoHide ) {
				// 'change' event not reliable!
				options.$closeEl.one( 'change keydown mousedown', function () {
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
			options.$msgBoxEl.remove();
		}
	};

}( mediaWiki, jQuery ) );
