/**
 * Base library for MediaWiki.
 *
 * Exposed globally as `mediaWiki` with `mw` as shortcut.
 *
 * @class mw
 * @alternateClassName mediaWiki
 * @singleton
 */

( function ( $ ) {
	'use strict';

	var mw = require( './mediawiki' ),
		loader = require( './loader' );

	/* eslint-enable no-console */

	// Alias $j to jQuery for backwards compatibility
	// @deprecated since 1.23 Use $ or jQuery instead
	mw.log.deprecate( window, '$j', $, 'Use $ or jQuery instead.' );

	/**
	 * Log a message to window.console, if possible.
	 *
	 * Useful to force logging of some errors that are otherwise hard to detect (i.e., this logs
	 * also in production mode). Gets console references in each invocation instead of caching the
	 * reference, so that debugging tools loaded later are supported (e.g. Firebug Lite in IE).
	 *
	 * @private
	 * @param {string} topic Stream name passed by mw.track
	 * @param {Object} data Data passed by mw.track
	 * @param {Error} [data.exception]
	 * @param {string} data.source Error source
	 * @param {string} [data.module] Name of module which caused the error
	 */
	function logError( topic, data ) {
		/* eslint-disable no-console */
		var msg,
			e = data.exception,
			source = data.source,
			module = data.module,
			console = window.console;

		if ( console && console.log ) {
			msg = ( e ? 'Exception' : 'Error' ) + ' in ' + source;
			if ( module ) {
				msg += ' in module ' + module;
			}
			msg += ( e ? ':' : '.' );
			console.log( msg );

			// If we have an exception object, log it to the error channel to trigger
			// proper stacktraces in browsers that support it. No fallback as we have
			// no browsers that don't support error(), but do support log().
			if ( e && console.error ) {
				console.error( String( e ), e );
			}
		}
		/* eslint-enable no-console */
	}

	// Subscribe to error streams
	mw.trackSubscribe( 'resourceloader.exception', logError );
	mw.trackSubscribe( 'resourceloader.assert', logError );

	/**
	 * Fired when all modules associated with the page have finished loading.
	 *
	 * @event resourceloader_loadEnd
	 * @member mw.hook
	 */
	$( function () {
		var loading = $.grep( mw.loader.getModuleNames(), function ( module ) {
			return mw.loader.getState( module ) === 'loading';
		} );
		// We only need a callback, not any actual module. First try a single using()
		// for all loading modules. If one fails, fall back to tracking each module
		// separately via $.when(), this is expensive.
		loading = mw.loader.using( loading ).then( null, function () {
			var all = $.map( loading, function ( module ) {
				return mw.loader.using( module ).then( null, function () {
					return $.Deferred().resolve();
				} );
			} );
			return $.when.apply( $, all );
		} );
		loading.then( function () {
			mwPerformance.mark( 'mwLoadEnd' );
			mw.hook( 'resourceloader.loadEnd' ).fire();
		} );
	} );

	mw.loader = loader;
	mw.errorLogger.installGlobalHandler( window );
	// Attach to window and globally alias
	window.mw = window.mediaWiki = mw;
}( jQuery ) );
