/**
 * @class mw.Api.plugin.watch
 * @since 1.19
 */
( function ( mw, $ ) {

	/**
	 * @private
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
		var params,
			d = $.Deferred(),
			apiPromise;

		// Backwards compatibility (< MW 1.20)
		d.done( ok ).fail( err );

		params = {
			action: 'watch',
			titles: $.isArray( page ) ? page.join( '|' ) : String( page ),
			token: mw.user.tokens.get( 'watchToken' ),
			uselang: mw.config.get( 'wgUserLanguage' )
		};

		if ( addParams ) {
			$.extend( params, addParams );
		}

		apiPromise = this.post( params )
			.done( function ( data ) {
				d.resolve( data.watch );
			} )
			.fail( d.reject );

		return d.promise( { abort: apiPromise.abort } );
	}

	var msg = 'Use of mediawiki.api callback params is deprecated. Use the Promise instead.';
	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for `action=watch`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		watch: function ( page, ok, err ) {
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			return doWatchInternal.call( this, page ).done( ok ).fail( err );
		},
		/**
		 * Convenience method for `action=watch&unwatch=1`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		unwatch: function ( page, ok, err ) {
			if ( ok || err ) {
				mw.track( 'mw.deprecate', 'api.cbParam' );
				mw.log.warn( msg );
			}
			return doWatchInternal.call( this, page, undefined, undefined, { unwatch: 1 } ).done( ok ).fail( err );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.watch
	 */

}( mediaWiki, jQuery ) );
