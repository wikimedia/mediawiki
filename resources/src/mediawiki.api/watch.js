/**
 * @class mw.Api.plugin.watch
 * @since 1.19
 */
( function () {

	/**
	 * @private
	 * @static
	 * @this mw.Api
	 *
	 * @param {string|mw.Title|string[]|mw.Title[]} pages Full page name or instance of mw.Title, or an
	 *  array thereof. If an array is passed, the return value passed to the promise will also be an
	 *  array of appropriate objects.
	 * @param {Object} [addParams]
	 * @return {jQuery.Promise}
	 * @return {Function} return.done
	 * @return {Object|Object[]} return.done.watch Object or list of objects (depends on the `pages`
	 *  parameter)
	 * @return {string} return.done.watch.title Full pagename
	 * @return {boolean} return.done.watch.watched Whether the page is now watched or unwatched
	 */
	function doWatchInternal( pages, addParams ) {
		// XXX: Parameter addParams is undocumented because we inherit this
		// documentation in the public method...
		var apiPromise = this.postWithToken( 'watch',
			$.extend(
				{
					formatversion: 2,
					action: 'watch',
					titles: Array.isArray( pages ) ? pages : String( pages )
				},
				addParams
			)
		);

		return apiPromise
			.then( function ( data ) {
				// If a single page was given (not an array) respond with a single item as well.
				return Array.isArray( pages ) ? data.watch : data.watch[ 0 ];
			} )
			.promise( { abort: apiPromise.abort } );
	}

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for `action=watch`.
		 *
		 * @inheritdoc #doWatchInternal
		 * @since 1.35 - expiry parameter can be passed when
		 * Watchlist Expiry is enabled
		 */
		watch: function ( pages, expiry ) {
			return doWatchInternal.call( this, pages, { expiry: expiry } );
		},

		/**
		 * Convenience method for `action=watch&unwatch=1`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		unwatch: function ( pages ) {
			return doWatchInternal.call( this, pages, { unwatch: 1 } );
		}
	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.watch
	 */

}() );
