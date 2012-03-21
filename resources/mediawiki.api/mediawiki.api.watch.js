/**
 * Additional mw.Api methods to assist with (un)watching wiki pages.
 * @since 1.19
 */
( function( $, mw ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=watch'.
		 *
		 * @param page {String|mw.Title} Full page name or instance of mw.Title
		 * @param success {Function} callback to which the watch object will be passed
		 * watch object contains 'title' (full page name), 'watched' (boolean) and
		 * 'message' (parsed HTML of the 'addedwatchtext' message).
		 * @param _unwatch {Boolean} Internally used to re-use this logic for unwatch(),
		 * do not use outside this module.
		 * @param err {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		watch: function( page, success, err, _unwatch ) {
			var params, ok;
			params = {
				action: 'watch',
				title: String( page ),
				token: mw.user.tokens.get( 'watchToken' ),
				uselang: mw.config.get( 'wgUserLanguage' )
			};
			if ( _unwatch ) {
				params.unwatch = 1;
			}
			ok = function( data ) {
				success( data.watch );
			};
			return this.post( params, { ok: ok, err: err } );
		},
		/**
		 * Convinience method for 'action=watch&unwatch=1'.
		 *
		 * @param page {String|mw.Title} Full page name or instance of mw.Title
		 * @param success {Function} callback to which the watch object will be passed
		 * watch object contains 'title' (full page name), 'unwatched' (boolean) and
		 * 'message' (parsed HTML of the 'removedwatchtext' message).
		 * @param err {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		unwatch: function( page, success, err ) {
			return this.watch( page, success, err, true );
		}

	} );

} )( jQuery, mediaWiki );
