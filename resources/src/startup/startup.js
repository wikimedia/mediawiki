/**
 * This file is where we decide whether to initialise the Grade A run-time.
 *
 * - Beware: This file MUST parse without errors on even the most ancient of browsers!
 */
/* eslint-disable no-implicit-globals, vars-on-top, no-unmodified-loop-condition */
/* global $VARS, $CODE */

/**
 * See <https://www.mediawiki.org/wiki/Compatibility#Browsers>
 *
 * Capabilities required for modern run-time:
 * - ECMAScript 5
 * - DOM Level 4 & Selectors API Level 1
 * - HTML5 & Web Storage
 * - DOM Level 2 Events
 *
 * Browsers we support in our modern run-time (Grade A):
 * - Chrome 13+
 * - IE 11+
 * - Firefox 4+
 * - Safari 5+
 * - Opera 15+
 * - Mobile Safari 6.0+ (iOS 6+)
 * - Android 4.1+
 *
 * Browsers we support in our no-javascript run-time (Grade C):
 * - Chrome 1+
 * - IE 6+
 * - Firefox 3+
 * - Safari 3+
 * - Opera 15+
 * - Mobile Safari 5.0+ (iOS 4+)
 * - Android 2.0+
 * - WebOS < 1.5
 * - PlayStation
 * - Symbian-based browsers
 * - NetFront-based browser
 * - Opera Mini
 * - Nokia's Ovi Browser
 * - MeeGo's browser
 * - Google Glass
 * - UC Mini (speed mode on)
 *
 * Other browsers that pass the check are considered Grade X.
 *
 * @param {string} [str] User agent, defaults to navigator.userAgent
 * @return {boolean} User agent is compatible with MediaWiki JS
 */
function isCompatible( str ) {
	var ua = str || navigator.userAgent;
	return !!(
		// https://caniuse.com/#feat=es5
		// https://caniuse.com/#feat=use-strict
		// https://caniuse.com/#feat=json / https://phabricator.wikimedia.org/T141344#2784065
		( function () {
			'use strict';
			return !this && Function.prototype.bind && window.JSON;
		}() ) &&

		// https://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// https://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// https://caniuse.com/#feat=addeventlistener
		'addEventListener' in window &&

		// Hardcoded exceptions for browsers that pass the requirement but we don't want to
		// support in the modern run-time.
		// Note: Please extend the regex instead of adding new ones
		!ua.match( /MSIE 10|webOS\/1\.[0-4]|SymbianOS|Series60|NetFront|Opera Mini|S40OviBrowser|MeeGo|Android.+Glass|^Mozilla\/5\.0 .+ Gecko\/$|googleweblight|PLAYSTATION|PlayStation/ )
	);
}

if ( !isCompatible() ) {
	// Handle Grade C
	// Undo speculative Grade A <html> class. See ResourceLoaderClientHtml::getDocumentAttributes().
	document.documentElement.className = document.documentElement.className
		.replace( /(^|\s)client-js(\s|$)/, '$1client-nojs$2' );

	// Process any callbacks for Grade C
	while ( window.NORLQ && window.NORLQ[ 0 ] ) {
		window.NORLQ.shift()();
	}
	window.NORLQ = {
		push: function ( fn ) {
			fn();
		}
	};

	// Clear and disable the Grade A queue
	window.RLQ = {
		push: function () {}
	};
} else {
	// Handle Grade A

	if ( window.performance && performance.mark ) {
		performance.mark( 'mwStartup' );
	}

	// This embeds mediawiki.js, which defines 'mw' and 'mw.loader'.
	$CODE.defineLoader();

	/**
	 * The $CODE and $VARS placeholders are substituted in ResourceLoaderStartUpModule.php.
	 */
	( function () {
		/* global mw */
		mw.config = new mw.Map( $VARS.wgLegacyJavaScriptGlobals );

		$CODE.registrations();

		mw.config.set( $VARS.configuration );

		// Process callbacks for Grade A
		var queue = window.RLQ;
		// Replace RLQ placeholder from ResourceLoaderClientHtml with an implementation
		// that executes simple callbacks, but continues to store callbacks that require
		// modules.
		window.RLQ = [];
		/* global RLQ */
		RLQ.push = function ( fn ) {
			if ( typeof fn === 'function' ) {
				fn();
			} else {
				// This callback requires a module, handled in mediawiki.base.
				RLQ[ RLQ.length ] = fn;
			}
		};
		while ( queue && queue[ 0 ] ) {
			// Re-use our new push() method
			RLQ.push( queue.shift() );
		}

		// Clear and disable the Grade C queue
		window.NORLQ = {
			push: function () {}
		};
	}() );
}
