'use strict';

/**
 * Provide navigation routing and location information.
 *
 * A router responds to hashchange and popstate events.
 *
 * OOjs Router Copyright 2011-2024 OOjs Team and other contributors.
 * Released under the MIT license
 * http://oojs.mit-license.org
 *
 * @author Ed Sanders <esanders@wikimedia.org>
 * @author James D. Forrester <jforrester@wikimedia.org>
 * @author Jon Robson <jdlrobson@gmail.com>
 * @author Kunal Mehta <legoktm@member.fsf.org>
 * @author MarcoAurelio <maurelio@tools.wmflabs.org>
 * @author Prateek Saxena <prtksxna@gmail.com>
 * @author Timo Tijhof <krinkle@fastmail.com>
 *
 * @exports mediawiki.router
 */
class Router extends OO.Registry {

	/**
	 * Create an instance of a router that responds to hashchange and popstate events.
	 */
	constructor() {
		// Parent constructor
		super();

		this.enabled = true;
		this.oldHash = this.getPath();

		const router = this;
		window.addEventListener( 'popstate', function () {
			router.emit( 'popstate' );
		} );

		window.addEventListener( 'hashchange', function () {
			router.emit( 'hashchange' );
		} );

		this.on( 'hashchange', function () {
			// event.originalEvent.newURL is undefined on Android 2.x
			let routeEvent;

			if ( router.enabled ) {
				routeEvent = $.Event( 'route', {
					path: router.getPath()
				} );
				router.emit( 'route', routeEvent );

				if ( !routeEvent.isDefaultPrevented() ) {
					router.checkRoute();
				} else {
					// if route was prevented, ignore the next hash change and revert the
					// hash to its old value
					router.enabled = false;
					router.navigate( router.oldHash );
				}
			} else {
				router.enabled = true;
			}

			router.oldHash = router.getPath();
		} );
	}

	/* Events */

	/**
	 * @event module:mediawiki.router#popstate
	 */

	/**
	 * @event module:mediawiki.router#hashchange
	 */

	/**
	 * @event module:mediawiki.router#route
	 * @param {jQuery.Event} routeEvent
	 */

	/* Methods */

	/**
	 * Check the current route and run appropriate callback if it matches.
	 */
	checkRoute() {
		const hash = this.getPath();

		let id, entry, match;

		for ( id in this.registry ) {
			entry = this.registry[ id ];
			match = hash.match( entry.path );
			if ( match ) {
				entry.callback.apply( this, match.slice( 1 ) );
				return;
			}
		}
	}

	/**
	 * Bind a specific callback to a hash-based route.
	 *
	 * ```
	 * addRoute( 'alert', function () { alert( 'something' ); } );
	 * addRoute( /hi-(.*)/, function ( name ) { alert( 'Hi ' + name ) } );
	 * ```
	 *
	 * Note that after defining all available routes it is up to the caller
	 * to check the existing route via the checkRoute method.
	 *
	 * @param {string|RegExp} path Path to match, string or regular expression
	 * @param {Function} callback Callback to be run when hash changes to one
	 *  that matches.
	 */
	addRoute( path, callback ) {
		const entry = {
			path: typeof path === 'string' ?
				// eslint-disable-next-line security/detect-non-literal-regexp
				new RegExp( '^' + path.replace( /[\\^$*+?.()|[\]{}]/g, '\\$&' ) + '$' ) :
				path,
			callback: callback
		};
		this.register( entry.path.toString(), entry );
	}

	/**
	 * @deprecated Use {@link module:mediawiki.router#addRoute} instead.
	 */
	route() {
		return this.addRoute.apply( this, arguments );
	}

	/**
	 * Navigate to a specific route.
	 *
	 * @param {string} title of new page
	 * @param {Object} options
	 * @param {string} options.path e.g. '/path/' or '/path/#foo'
	 * @param {boolean} options.useReplaceState Set replaceStateState to use pushState when you want to
	 *  avoid long history queues.
	 */
	navigateTo( title, options ) {
		if ( options.useReplaceState ) {
			history.replaceState( null, title, options.path );
		} else {
			history.pushState( null, title, options.path );
		}
	}

	/**
	 * Navigate to a specific 'hash fragment' route.
	 *
	 * @deprecated Use {@link module:mediawiki.router#navigateTo} instead
	 * @param {string} path String with a route (hash without #).
	 */
	navigate( path ) {
		// Take advantage of `pushState` when available, to clear the hash and
		// not leave `#` in the history. An entry with `#` in the history has
		// the side-effect of resetting the scroll position when navigating the
		// history.
		if ( path === '' ) {
			// To clear the hash we need to cut the hash from the URL.
			path = window.location.href.replace( /#.*$/, '' );
			history.pushState( null, document.title, path );
			this.checkRoute();
		} else {
			window.location.hash = path;
		}
	}

	/**
	 * Navigate to the previous route. This is a wrapper for window.history.back.
	 *
	 * @return {jQuery.Promise} Promise which resolves when the back navigation is complete
	 */
	back() {
		const router = this;
		// eslint-disable-next-line prefer-const
		let timeoutID;
		const deferred = $.Deferred();

		this.once( 'popstate', function () {
			clearTimeout( timeoutID );
			deferred.resolve();
		} );

		window.history.back();

		// If for some reason (old browser, bug in IE/windows 8.1, etc) popstate doesn't fire,
		// resolve manually. Since we don't know for sure which browsers besides IE10/11 have
		// this problem, it's better to fall back this way rather than singling out browsers
		// and resolving the deferred request for them individually.
		// See https://connect.microsoft.com/IE/feedback/details/793618/history-back-popstate-not-working-as-expected-in-webview-control
		// Give browser a few ms to update its history.
		timeoutID = setTimeout( function () {
			router.off( 'popstate' );
			deferred.resolve();
		}, 50 );

		return deferred.promise();
	}

	/**
	 * Get current path (hash).
	 *
	 * @return {string} Current path.
	 */
	getPath() {
		return window.location.hash.slice( 1 );
	}

	/**
	 * Whether the current browser supports 'hashchange' events.
	 *
	 * @deprecated No longer needed
	 * @return {boolean} Always true
	 */
	isSupported() {
		return true;
	}
}

OO.Router = Router;
module.exports = new Router();
