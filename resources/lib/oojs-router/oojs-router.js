/*!
 * OOjs Router v0.2.0
 * https://www.mediawiki.org/wiki/OOjs_Router
 *
 * Copyright 2011-2019 OOjs Team and other contributors.
 * Released under the MIT license
 * http://oojs.mit-license.org
 *
 * Date: 2019-02-06T21:26:01Z
 */
( function ( $ ) {

'use strict';

/**
 * Provides navigation routing and location information
 *
 * @class OO.Router
 * @extends OO.Registry
 */
OO.Router = function OoRouter() {
	var router = this;

	// Parent constructor
	OO.Router.parent.call( this );

	this.enabled = true;
	this.oldHash = this.getPath();

	$( window ).on( 'popstate', function () {
		router.emit( 'popstate' );
	} );

	$( window ).on( 'hashchange', function () {
		router.emit( 'hashchange' );
	} );

	this.on( 'hashchange', function () {
		// event.originalEvent.newURL is undefined on Android 2.x
		var routeEvent;

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
};

/* Inheritance */

OO.inheritClass( OO.Router, OO.Registry );

/* Events */

/**
 * @event popstate
 */

/**
 * @event hashchange
 */

/**
 * @event route
 * @param {jQuery.Event} routeEvent
 */

/* Static Methods */

/**
 * Determine if current browser supports this router
 *
 * @return {boolean} The browser is supported
 */
OO.Router.static.isSupported = function () {
	return 'onhashchange' in window;
};

/* Methods */

/**
 * Check the current route and run appropriate callback if it matches.
 */
OO.Router.prototype.checkRoute = function () {
	var id, entry, match,
		hash = this.getPath();

	for ( id in this.registry ) {
		entry = this.registry[ id ];
		match = hash.match( entry.path );
		if ( match ) {
			entry.callback.apply( this, match.slice( 1 ) );
			return;
		}
	}
};

/**
 * Bind a specific callback to a hash-based route, e.g.
 *
 *     @example
 *     addRoute( 'alert', function () { alert( 'something' ); } );
 *     addRoute( /hi-(.*)/, function ( name ) { alert( 'Hi ' + name ) } );
 *
 * Note that after defining all available routes it is up to the caller
 * to check the existing route via the checkRoute method.
 *
 * @param {string|RegExp} path Path to match, string or regular expression
 * @param {Function} callback Callback to be run when hash changes to one
 * that matches.
 */
OO.Router.prototype.addRoute = function ( path, callback ) {
	var entry = {
		path: typeof path === 'string' ?
			new RegExp( '^' + path.replace( /[\\^$*+?.()|[\]{}]/g, '\\$&' ) + '$' ) :
			path,
		callback: callback
	};
	this.register( entry.path.toString(), entry );
};

/**
 * @deprecated Use #addRoute
 */
OO.Router.prototype.route = OO.Router.prototype.addRoute;

/**
 * Navigate to a specific route.
 *
 * @param {string} title of new page
 * @param {Object} options
 * @param {string} options.path e.g. '/path/' or '/path/#foo'
 * @param {boolean} options.useReplaceState Set replaceStateState to use pushState when you want to
 *   avoid long history queues.
 */
OO.Router.prototype.navigateTo = function ( title, options ) {
	if ( options.useReplaceState ) {
		history.replaceState( null, title, options.path );
	} else {
		history.pushState( null, title, options.path );
	}
};

/**
 * Navigate to a specific ''hash fragment'' route.
 *
 * @param {string} path String with a route (hash without #).
 * @deprecated use navigateTo instead
 */
OO.Router.prototype.navigate = function ( path ) {
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
};

/**
 * Navigate to the previous route. This is a wrapper for window.history.back
 *
 * @return {jQuery.Promise} Promise which resolves when the back navigation is complete
 */
OO.Router.prototype.back = function () {
	var timeoutID,
		router = this,
		deferred = $.Deferred();

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
};

/**
 * Get current path (hash).
 *
 * @return {string} Current path.
 */
OO.Router.prototype.getPath = function () {
	return window.location.hash.slice( 1 );
};

/**
 * @deprecated Use static method
 */
OO.Router.prototype.isSupported = OO.Router.static.isSupported;

if ( typeof module !== 'undefined' && module.exports ) {
	module.exports = OO.Router;
}

}( jQuery ) );
