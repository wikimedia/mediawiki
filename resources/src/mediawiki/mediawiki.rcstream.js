( function ( mw ) {
	'use strict';

	mw.rcstream = {
		/**
		 * Helper to connect to the RCStream instance
		 *
		 * @param {Function} callback to call upon receiving new data
		 * @param {string} [wiki] domain name of wiki to connect to, defaults to current wiki
		 */
		'listen': function( callback, wiki ) {
			/* global io */
			var socket = io.connect( mw.config.get( 'wgRCStreamHost' ) );

			if ( wiki === undefined ) {
				// Default to this wiki
				wiki = mw.config( 'wgServerName' );
			}

			socket.on( 'connect', function() {
				socket.emit( 'subscribe', wiki );
			} );

			socket.on( 'change', callback );
		}
	};
}( mediaWiki ) );
