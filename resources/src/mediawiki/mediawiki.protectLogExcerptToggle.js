/**
 *
 */
( function ( mw, $ ) {
	"use strict";

var logHandler = {
	/**
	 * Toggles log excerpts
	 */
	toggleLog: function () {
		var	hideMsg, showMsg,
			log = $( '#mw-protectlogexcerpt' ),
			toggle = $( '#mw-protectlogtoggle' );

		if ( log.length && toggle.length ) {
			hideMsg = mw.msg( 'log-toggle-protect-hide' );
			showMsg = mw.msg( 'log-toggle-protect-show' );

			if ( log.css( 'display' ) === 'none' ) {
				log.show();
				toggle.children( 'a' ).text( hideMsg );
			} else {
				log.hide();
				toggle.children( 'a' ).text( showMsg );
			}
		}
	},

	/* Startup function */
	init: function () {

		// Enables log detail box and toggle
		var toggle = $( '#mw-protectlogtoggle' );
		if ( toggle.length ) {
			toggle.css( 'display', 'inline' ); // show toggle control
			$( '#mw-protectlogexcerpt' ).hide(); // hide log excerpt
		}
		toggle.children( 'a' ).click( logHandler.toggleLog );
	}
};

// Perform some onload events:
$( logHandler.init );

}( mediaWiki, jQuery ) );
