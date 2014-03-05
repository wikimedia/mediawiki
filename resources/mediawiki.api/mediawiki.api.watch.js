/**
 * @class mw.Api.plugin.watch
 * @since 1.19
 */
( function ( mw, $ ) {

	/**
	 * @private
	 * @static
	 * @context mw.Api
	 *
	 * @param {string|mw.Title|string[]|mw.Title[]} page Full page name or instance of mw.Title or array of pages
	 * @param {Function} [ok] Success callback (deprecated)
	 * @param {Function} [err] Error callback (deprecated)
	 * @return {jQuery.Promise}
	 * @return {Function} return.done
	 * @return {Object} return.done.watch
	 * @return {string} return.done.watch.title Full pagename
	 * @return {boolean} return.done.watch.watched
	 * @return {string} return.done.watch.message Parsed HTML of the confirmational interface message
	 */
	function doWatchInternal( page, ok, err, addParams ) {
		// XXX: Parameter addParams is undocumented because we inherit this
		// documentation in the public method..
		var apiPromise = this.post(
			$.extend(
				{
					action: 'watch',
					titles: $.isArray( page ) ? page.join( '|' ) : String( page ),
					token: mw.user.tokens.get( 'watchToken' ),
					uselang: mw.config.get( 'wgUserLanguage' )
				},
				addParams
			)
		);

		if ( ok || err ) {
			mw.track( 'mw.deprecate', 'api.cbParam' );
			mw.log.warn( 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.' );
		}

		return apiPromise
			.then( function ( data ) {
				return data.watch;
			} )
			.done( ok )
			.fail( err )
			.promise( { abort: apiPromise.abort } );
	}

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for `action=watch`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		watch: function ( page, ok, err ) {
			return doWatchInternal.call( this, page, ok, err );
		},
		/**
		 * Convenience method for `action=watch&unwatch=1`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		unwatch: function ( page, ok, err ) {
			return doWatchInternal.call( this, page, ok, err, { unwatch: 1 } );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.watch
	 */

}( mediaWiki, jQuery ) );
