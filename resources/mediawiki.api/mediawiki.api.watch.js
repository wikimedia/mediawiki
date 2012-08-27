/**
 * Additional mw.Api methods to assist with (un)watching wiki pages.
 * @since 1.19
 */
( function ( mw, $ ) {

	/**
	 * @context {mw.Api}
	 */
	function doWatchInternal( page, success, err, addParams ) {
		var params = {
			action: 'watch',
			title: String( page ),
			token: mw.user.tokens.get( 'watchToken' ),
			uselang: mw.config.get( 'wgUserLanguage' )
		};
		function ok( data ) {
			success( data.watch );
		}
		if ( addParams ) {
			$.extend( params, addParams );
		}
		return this.post( params, { ok: ok, err: err } );
	}

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=watch'.
		 *
		 * @param page {String|mw.Title} Full page name or instance of mw.Title
		 * @param success {Function} Callback to which the watch object will be passed.
		 * Watch object contains properties 'title' (full pagename), 'watched' (boolean) and
		 * 'message' (parsed HTML of the 'addedwatchtext' message).
		 * @param err {Function} Error callback (optional)
		 * @return {jqXHR}
		 */
		watch: function ( page, success, err ) {
			return doWatchInternal.call( this, page, success, err );
		},
		/**
		 * Convinience method for 'action=watch&unwatch=1'.
		 *
		 * @param page {String|mw.Title} Full page name or instance of mw.Title
		 * @param success {Function} Callback to which the watch object will be passed.
		 * Watch object contains properties 'title' (full pagename), 'watched' (boolean) and
		 * 'message' (parsed HTML of the 'removedwatchtext' message).
		 * @param err {Function} Error callback (optional)
		 * @return {jqXHR}
		 */
		unwatch: function ( page, success, err ) {
			return doWatchInternal.call( this, page, success, err, { unwatch: 1 } );
		}

	} );

}( mediaWiki, jQuery ) );
