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

	$( window ).on( 'popstate', function () {
		self.emit( 'popstate' );
	} );

	$( window ).on( 'hashchange', function () {
		self.emit( 'hashchange' );
	} );

	this.on( 'hashchange', function () {
		// ev.originalEvent.newURL is undefined on Android 2.x
		var routeEv;

		if ( self.enabled ) {
			routeEv = $.Event( 'route', {
				path: self.getPath()
			} );
			self.emit( 'route', routeEv );

			if ( !routeEv.isDefaultPrevented() ) {
				self.checkRoute();
			} else {
				// if route was prevented, ignore the next hash change and revert the
				// hash to its old value
				self.enabled = false;
				self.navigate( self.oldHash );
			}
		} else {
			self.enabled = true;
		}

		self.oldHash = self.getPath();
	} );
}
OO.mixinClass( Router, OO.EventEmitter );

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
 * @param {string} path string with a route (hash without #).
 */
Router.prototype.navigate = function ( path ) {
	var history = window.history;
	// Take advantage of `pushState` when available, to clear the hash and
	// not leave `#` in the history. An entry with `#` in the history has
	// the side-effect of resetting the scroll position when navigating the
	// history.
	if ( path === '' && history && history.pushState ) {
		// To clear the hash we need to cut the hash from the URL.
		path = window.location.href.replace( /#.*$/, '' );
		history.pushState( null, document.title, path );
		this.checkRoute();
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
 * Navigate to the previous route. This is a wrapper for window.history.back
 *
 * @method
 * @return {jQuery.Deferred}
 */
Router.prototype.back = function () {
	var deferredRequest = $.Deferred(),
		self = this,
		timeoutID;

	this.once( 'popstate', function () {
		clearTimeout( timeoutID );
		deferredRequest.resolve();
	} );

	this.goBack();

	// If for some reason (old browser, bug in IE/windows 8.1, etc) popstate doesn't fire,
	// resolve manually. Since we don't know for sure which browsers besides IE10/11 have
	// this problem, it's better to fall back this way rather than singling out browsers
	// and resolving the deferred request for them individually.
	// See https://connect.microsoft.com/IE/feedback/details/793618/history-back-popstate-not-working-as-expected-in-webview-control
	// Give browser a few ms to update its history.
	timeoutID = setTimeout( function () {
		self.off( 'popstate' );
		deferredRequest.resolve();
	}, 50 );

	return deferredRequest;
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
