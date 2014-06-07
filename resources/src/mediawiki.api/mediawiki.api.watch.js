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
	 * @param {string|mw.Title|string[]|mw.Title[]} pages Full page name or instance of mw.Title, or an
	 *  array thereof. If an array is passed, the return value passed to the promise will also be an
	 *  array of appropriate objects.
	 * @param {Function} [ok] Success callback (deprecated)
	 * @param {Function} [err] Error callback (deprecated)
	 * @return {jQuery.Promise}
	 * @return {Function} return.done
	 * @return {Object|Object[]} return.done.watch Object or list of objects (depends on the `pages`
	 *  parameter)
	 * @return {string} return.done.watch.title Full pagename
	 * @return {boolean} return.done.watch.watched Whether the page is now watched or unwatched
	 * @return {string} return.done.watch.message Parsed HTML of the confirmational interface message
	 */
	function doWatchInternal( pages, ok, err, addParams ) {
		// XXX: Parameter addParams is undocumented because we inherit this
		// documentation in the public method...
		var apiPromise = this.post(
			$.extend(
				{
					action: 'watch',
					titles: $.isArray( pages ) ? pages.join( '|' ) : String( pages ),
					token: mw.user.tokens.get( 'watchToken' ),
					uselang: mw.config.get( 'wgUserLanguage' )
				},
				addParams
			)
		);

		// Backwards compatibility (< MW 1.20)
		if ( ok || err ) {
			mw.track( 'mw.deprecate', 'api.cbParam' );
			mw.log.warn( 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.' );
		}

		return apiPromise
			.then( function ( data ) {
				// If a single page was given (not an array) respond with a single item as well.
				return $.isArray( pages ) ? data.watch : data.watch[0];
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
		watch: function ( pages, ok, err ) {
			return doWatchInternal.call( this, pages, ok, err );
		},
		/**
		 * Convenience method for `action=watch&unwatch=1`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		unwatch: function ( pages, ok, err ) {
			return doWatchInternal.call( this, pages, ok, err, { unwatch: 1 } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.watch
	 */

}( mediaWiki, jQuery ) );
