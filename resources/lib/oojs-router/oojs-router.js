/*!
 * OOjs Router v0.1.0
 * https://www.mediawiki.org/wiki/OOjs
 *
 * Copyright 2011-2016 OOjs Team and other contributors.
 * Released under the MIT license
 * http://oojs-router.mit-license.org
 *
 * Date: 2016-05-05T19:27:58Z
 */
( function ( $ ) {

'use strict';

/**
 * Does hash match entry.path? If it does apply the
 * callback for the Entry object.
 *
 * @method
 * @private
 * @ignore
 * @param {string} hash string to match
 * @param {Object} entry Entry object
 * @return {boolean} Whether hash matches entry.path
 */
function matchRoute( hash, entry ) {
	var match = hash.match( entry.path );
	if ( match ) {
		entry.callback.apply( this, match.slice( 1 ) );
		return true;
	}
	return false;
}

/**
 * Provides navigation routing and location information
 *
 * @class Router
 * @mixins OO.EventEmitter
 */
function Router() {
	var self = this;
	OO.EventEmitter.call( this );
	// use an object instead of an array for routes so that we don't
	// duplicate entries that already exist
	this.routes = {};
	this.enabled = true;
	this.oldHash = this.getPath();
	this.hasPushState = window.history && window.history.pushState;

	$( window ).on( this.hasPushState ? 'popstate' : 'hashchange', function () {
		self.checkRoute();
	} );
}
OO.mixinClass( Router, OO.EventEmitter );

/**
 * Check the current route and tries to navigate to it, otherwise revert back
 * to the previous route.
 *
 * @method
 */
Router.prototype.checkRoute = function () {
	var routeEv,
		path = this.getPath();

	if ( this.enabled ) {
		routeEv = $.Event( 'route', {
			path: path
		} );
		this.emit( 'route', routeEv );

		if ( !routeEv.isDefaultPrevented() ) {
			this.loadRoute( path );
		} else {
			// if route was prevented, ignore the next hash change and revert the
			// hash to its old value
			this.enabled = false;
			this.replace( this.oldHash );
		}
	} else {
		this.enabled = true;
	}

	this.oldHash = self.getPath();
};

/**
 * Check the route and run appropriate callback if it matches.
 *
 * @param {string} [hash] Route (hash without #).
 * @method
 */
Router.prototype.loadRoute = function ( hash ) {
	hash = hash || this.getPath();

	$.each( this.routes, function ( id, entry ) {
		return !matchRoute( hash, entry );
	} );
};

/**
 * Check the current route and run appropriate callback if it matches.
 *
 * @method
 */
Router.prototype.checkRoute = function () {
	var hash = this.getPath();

	$.each( this.routes, function ( id, entry ) {
		return !matchRoute( hash, entry );
	} );
};

/**
 * Bind a specific callback to a hash-based route, e.g.
 *
 *     @example
 *     route( 'alert', function () { alert( 'something' ); } );
 *     route( /hi-(.*)/, function ( name ) { alert( 'Hi ' + name ) } );
 * Note that after defining all available routes it is up to the caller
 * to check the existing route via the checkRoute method.
 *
 * @method
 * @param {Object} path string or RegExp to match.
 * @param {Function} callback Callback to be run when hash changes to one
 * that matches.
 */
Router.prototype.route = function ( path, callback ) {
	var entry = {
		path: typeof path === 'string' ?
			new RegExp( '^' + path.replace( /[\\^$*+?.()|[\]{}]/g, '\\$&' ) + '$' )
			: path,
		callback: callback
	};
	this.routes[ entry.path ] = entry;
};

/**
 * Navigate to a specific route.
 *
 * @method
 * @param {string} path Route (hash without #).
 * @param {Object} [options]
 * @param {boolean} [options.replace] If set to `true`, the current entry in
 *   the history will be replaced, instead of creating a new one.
 */
Router.prototype.navigate = function ( path, options ) {
	// Take advantage of `pushState` when available, to clear the hash and
	// not leave `#` in the history. An entry with `#` in the history has
	// the side-effect of resetting the scroll position when navigating the
	// history.
	var href = window.location.href.replace( /#.*$/, '' ),
		url = ( path === '' ) ? href : href + '#' + path;

	options = options || {};

	if ( this.hasPushState ) {
		// To clear the hash we need to cut the hash from the URL.
		window.history[ options.replace ? 'replaceState' : 'pushState' ]( null, document.title, url );
		this.checkRoute();
	} else if ( options.replace ) {
		window.location.replace( url );
	} else {
		window.location.hash = path;
	}
};

/**
 * Triggers back on the window
 */
Router.prototype.goBack = function () {
	window.history.back();
};

/**
 * Get current path (hash).
 *
 * @method
 * @return {string} Current path.
 */
Router.prototype.getPath = function () {
	return window.location.hash.slice( 1 );
};

/**
 * Determine if current browser supports onhashchange event
 *
 * @method
 * @return {boolean}
 */
Router.prototype.isSupported = function () {
	return 'onhashchange' in window;
};

module.exports = Router;

}( jQuery ) );
