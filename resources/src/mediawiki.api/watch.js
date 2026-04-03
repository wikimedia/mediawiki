/**
 * @typedef {Object} mw.Api.WatchedPage
 * @property {string} title Full page name
 * @property {boolean} watched Whether the page is now watched (true) or unwatched (false)
 */

( function () {

	/**
	 * @private
	 *
	 * @param {string|mw.Title|string[]|mw.Title[]} pages Full page name or instance of mw.Title, or an
	 *  array thereof. If an array is passed, the return value passed to the promise will also be an
	 *  array of appropriate objects.
	 * @param {Object} [addParams]
	 * @return {jQuery.Promise<mw.Api.WatchedPage|mw.Api.WatchedPage[]>}
	 */
	function doWatchInternal( pages, addParams ) {
		// XXX: Parameter addParams is undocumented because we inherit this
		// documentation in the public method...
		const apiPromise = this.postWithToken( 'watch',
			Object.assign(
				{
					formatversion: 2,
					action: 'watch',
					titles: Array.isArray( pages ) ? pages : String( pages )
				},
				addParams
			)
		);

		return apiPromise
			.then(
				// If a single page was given (not an array) respond with a single item as well.
				( data ) => Array.isArray( pages ) ? data.watch : data.watch[ 0 ]
			)
			.promise( { abort: apiPromise.abort } );
	}

	Object.assign( mw.Api.prototype, /** @lends mw.Api.prototype */ {
		/**
		 * Convenience method for `action=watch`.
		 *
		 * @method
		 * @since 1.35 - expiry parameter can be passed when Watchlist Expiry is enabled
		 * @param {string|mw.Title|string[]|mw.Title[]} pages Full page name or instance of mw.Title, or an
		 *  array thereof. If an array is passed, the return value passed to the promise will also be an
		 *  array of appropriate objects.
		 * @param {string} [expiry] When the page should expire from the watchlist. If omitted, the
		 *  page will not expire.
		 * @return {jQuery.Promise<mw.Api.WatchedPage|mw.Api.WatchedPage[]>} A promise that resolves
		 *  with an object (or array of objects) describing each page that was passed in and its
		 *  current watched/unwatched status.
		 */
		watch: function ( pages, expiry ) {
			return doWatchInternal.call( this, pages, { expiry: expiry } );
		},

		/**
		 * Convenience method for `action=watch&unwatch=1`.
		 *
		 * @method
		 * @param {string|mw.Title|string[]|mw.Title[]} pages Full page name or instance of mw.Title, or an
		 *  array thereof. If an array is passed, the return value passed to the promise will also be an
		 *  array of appropriate objects.
		 * @return {jQuery.Promise<mw.Api.WatchedPage|mw.Api.WatchedPage[]>} A promise that resolves
		 *  with an object (or array of objects) describing each page that was passed in and its
		 *  current watched/unwatched status.
		 */
		unwatch: function ( pages ) {
			return doWatchInternal.call( this, pages, { unwatch: 1 } );
		}
	} );

}() );
