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

		// Events
		window.addEventListener( 'popstate', () => {
			this.emit( 'popstate' );
		} );

		window.addEventListener( 'hashchange', () => {
			this.emit( 'hashchange' );
		} );

		this.connect( this, { hashchange: 'onRouterHashChange' } );
	}

	/* Events */

	/**
	 * @event module:mediawiki.router#popstate
	 */

	/**
	 * @event module:mediawiki.router#hashchange
	 */

	/**
	 * Event fired whenever the hash changes.
	 *
	 * @event module:mediawiki.router#route
	 * @param {jQuery.Event} routeEvent
	 */

	/* Methods */

	/**
	 * Handle hashchange events emitted by ourselves
	 *
	 * @param {HashChangeEvent} [event] Hash change event, if triggered by native event
	 */
	onRouterHashChange() {
		if ( this.enabled ) {
			// event.originalEvent.newURL is undefined on Android 2.x
			const routeEvent = $.Event( 'route', {
				path: this.getPath()
			} );
			this.emit( 'route', routeEvent );

			if ( !routeEvent.isDefaultPrevented() ) {
				this.checkRoute();
			} else {
				// if route was prevented, ignore the next hash change and revert the
				// hash to its old value
				this.enabled = false;
				this.navigate( this.oldHash, true );
			}
		} else {
			this.enabled = true;
		}

		this.oldHash = this.getPath();
	}

	/**
	 * Check the current route and run appropriate callback if it matches.
	 */
	checkRoute() {
		const hash = this.getPath();

		for ( const id in this.registry ) {
			const entry = this.registry[ id ];
			const match = hash.match( entry.path );
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
		this.addRoute.apply( this, arguments );
	}

	/**
	 * Navigate to a specific route.
	 *
	 * @param {string} title Title of new page
	 * @param {Object} options
	 * @param {string} options.path e.g. '/path/' or '/path/#foo'
	 * @param {boolean} options.useReplaceState Set replaceStateState to use pushState when you want to
	 *  avoid long history queues.
	 */
	navigateTo( title, options ) {
		const oldHash = this.getPath();
		if ( options.useReplaceState ) {
			history.replaceState( null, title, options.path );
		} else {
			history.pushState( null, title, options.path );
		}
		if ( this.getPath() !== oldHash ) {
			// history.replaceState/pushState doesn't trigger a hashchange event
			this.onRouterHashChange();
		}
	}

	/**
	 * Navigate to a specific 'hash fragment' route.
	 *
	 * @deprecated Use {@link module:mediawiki.router#navigateTo} instead
	 * @param {string} path String with a route (hash without #).
	 * @param {boolean} [fromHashchange] (Internal) The navigate call originated
	 * form a hashchange event, so don't emit another one.
	 */
	navigate( path, fromHashchange ) {
		// Take advantage of `pushState` when available, to clear the hash and
		// not leave `#` in the history. An entry with `#` in the history has
		// the side-effect of resetting the scroll position when navigating the
		// history.
		if ( path === '' ) {
			// To clear the hash we need to cut the hash from the URL.
			path = window.location.href.replace( /#.*$/, '' );
			history.pushState( null, document.title, path );
			if ( !fromHashchange ) {
				// history.pushState doesn't trigger a hashchange event
				this.onRouterHashChange();
			} else {
				this.checkRoute();
			}
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
		// eslint-disable-next-line prefer-const
		let timeoutID;
		const deferred = $.Deferred();

		this.once( 'popstate', () => {
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
		timeoutID = setTimeout( () => {
			this.off( 'popstate' );
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

	/**
	 * Allow tests to reset, so that event handlers don't leak and routes don't pile up.
	 *
	 * @ignore
	 */
	resetForTest() {
		if ( window.QUnit ) {
			// Reset OO.EventEmitter
			this.bindings = {};
			// Reset OO.Registry
			this.registry = {};
			// Restore the one from the constructor
			this.enabled = true;
			this.connect( this, { hashchange: 'onRouterHashChange' } );
		}
	}
}

OO.Router = Router;
module.exports = new Router();
