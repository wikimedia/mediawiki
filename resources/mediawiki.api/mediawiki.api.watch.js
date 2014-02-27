/**
 * @class mw.Api.plugin.watch
 * @since 1.19
 */
( function ( mw, $ ) {

	/**
	 * @private
	 * @context mw.Api
	 *
	 * @param {String|mw.Title} page Full page name or instance of mw.Title
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
			title: String( page ),
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

	$.extend( mw.Api.prototype, {
		/**
		 * Convenience method for `action=watch`.
		 *
		 * @inheritdoc #doWatchInternal
		 */
		watch: function ( page, ok, err ) {
			var msg = 'MWDeprecationWarning: Use of "ok" and "err" on watch is deprecated. Use .done() and .fail() instead.';
			if ( ok ) {
				mw.track( 'mw.deprecate', 'ok' );
				mw.log.warn( msg );
			}
			if ( err ) {
				mw.track( 'mw.deprecate', 'err' );
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
			var msg = 'MWDeprecationWarning: Use of "ok" and "err" on unwatch is deprecated. Use .done() and .fail() instead.';
			if ( ok ) {
				mw.track( 'mw.deprecate', 'ok' );
				mw.log.warn( msg );
			}
			if ( err ) {
				mw.track( 'mw.deprecate', 'err' );
				mw.log.warn( msg );
			}
			return doWatchInternal.call( this, page, null, null, { unwatch: 1 } ).done( ok ).fail( err );
		}

	} );

	/**
	 * @class mw.Api
	 * @mixins mw.Api.plugin.watch
	 */

}( mediaWiki, jQuery ) );
