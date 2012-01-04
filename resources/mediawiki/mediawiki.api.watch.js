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
		 * @param err {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		watch: function( page, success, err ) {
			var params, ok;
			params = {
				action: 'watch',
				title: String( page ),
				token: mw.user.tokens.get( 'watchToken' ),
				uselang: mw.config.get( 'wgUserLanguage' )
			};
			ok = function( data ) {
				success( data.watch );
			};
			return this.post( params, { ok: ok, err: err } );
		},
		/**
		 * Convinience method for 'action=watch&unwatch='.
		 *
		 * @param page {String|mw.Title} Full page name or instance of mw.Title
		 * @param success {Function} callback to which the watch object will be passed
		 * watch object contains 'title' (full page name), 'unwatched' (boolean) and
		 * 'message' (parsed HTML of the 'removedwatchtext' message).
		 * @param err {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		unwatch: function( page, success, err ) {
			var params, ok;
			params = {
				action: 'watch',
				unwatch: 1,
				title: String( page ),
				token: mw.user.tokens.get( 'watchToken' ),
				uselang: mw.config.get( 'wgUserLanguage' )
			};
			ok = function( data ) {
				success( data.watch );
			};
			return this.post( params, { ok: ok, err: err } );
		}

	} );

} )( jQuery, mediaWiki );
