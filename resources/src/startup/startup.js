/**
 * This file is where we decide whether to initialise the modern support browser run-time.
 *
 * - Beware: This file MUST parse without errors on even the most ancient of browsers!
 */
/* eslint-disable no-implicit-globals */
/* global $CODE, RLQ:true, NORLQ:true */

/**
 * See <https://www.mediawiki.org/wiki/Compatibility#Browsers>
 *
 * Capabilities required for modern run-time:
 * - ECMAScript 2015 (a.k.a. ES6)
 * - DOM Level 4 (including Selectors API)
 * - HTML5 (including Web Storage API)
 *
 * Browsers we support in our modern run-time (Grade A):
 * - Chrome 21+
 * - Edge 79+
 * - Opera 38+
 * - Firefox 54+
 * - Safari 11.1+
 * - Mobile Safari 11.2+ (iOS 11+)
 * - Android 5.0+
 *
 * Browsers we support in our no-JavaScript, basic run-time (Grade C):
 * - Chrome 1+
 * - Opera 15+
 * - IE 8+
 * - Firefox 3+
 * - Safari 3+
 * - Mobile Safari 5.0+ (iOS 4+)
 * - Android 2.0+
 * - Opera Mini (Extreme mode)
 * - UC Mini (Speed mode)
 *
 * Other browsers that pass the check are considered unknown (Grade X).
 *
 * @private
 * @return {boolean} User agent is compatible with MediaWiki JS
 */
function isCompatible() {
	return !!(
		// First, check DOM4 and HTML5 features for faster-fail
		// https://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// https://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// Now, check whether the browser supports ES6.
		//
		// ES6 Promise, this rejects most unsupporting browsers.
		// https://caniuse.com/promises
		//
		// ES6 Promise.finally, this rejects Safari 10 and iOS 10.
		// https://caniuse.com/promise-finally
		// eslint-disable-next-line es-x/no-promise, es-x/no-promise-prototype-finally, dot-notation
		typeof Promise === 'function' && Promise.prototype[ 'finally' ] &&
		// ES6 Arrow Functions (with default params), this ensures genuine syntax
		// support in the engine, not just API coverage.
		// https://caniuse.com/arrow-functions
		//
		// Based on Benjamin De Cock's snippet here:
		// https://gist.github.com/bendc/d7f3dbc83d0f65ca0433caf90378cd95
		( function () {
			try {
				// eslint-disable-next-line no-new, no-new-func
				new Function( '(a = 0) => a' );
				return true;
			} catch ( e ) {
				return false;
			}
		}() ) &&
		// ES6 RegExp.prototype.flags, this rejects Android 4.4.4 and MSEdge <= 18,
		// which was the last Edge Legacy version (MSEdgeHTML-based).
		// eslint-disable-next-line es-x/no-regexp-prototype-flags
		/./g.flags === 'g'
	);
}

if ( !isCompatible() ) {
	// Handle basic supported browsers (Grade C).
	// Undo speculative modern (Grade A) root CSS class `<html class="client-js">`.
	// See ResourceLoaderClientHtml::getDocumentAttributes().
	document.documentElement.className = document.documentElement.className
		.replace( /(^|\s)client-js(\s|$)/, '$1client-nojs$2' );

	// Process any callbacks for basic support (Grade C).
	while ( window.NORLQ && NORLQ[ 0 ] ) {
		NORLQ.shift()();
	}
	NORLQ = {
		push: function ( fn ) {
			fn();
		}
	};

	// Clear and disable the modern (Grade A) queue.
	RLQ = {
		push: function () {}
	};
} else {
	// Handle modern (Grade A).

	if ( window.performance && performance.mark ) {
		performance.mark( 'mwStartup' );
	}

	// This embeds mediawiki.js, which defines 'mw' and 'mw.loader'.
	$CODE.defineLoader();

	/**
	 * The $CODE placeholder is substituted in ResourceLoaderStartUpModule.php.
	 */
	( function () {
		/* global mw */
		var queue;

		$CODE.registrations();

		// First set page-specific config needed by mw.loader (wgCSPNonce, wgUserName)
		mw.config.set( window.RLCONF || {} );
		mw.loader.state( window.RLSTATE || {} );
		mw.loader.load( window.RLPAGEMODULES || [] );

		// Process RLQ callbacks
		//
		// The code in these callbacks could've been exposed from load.php and
		// requested client-side. Instead, they are pushed by the server directly
		// (from ResourceLoaderClientHtml and other parts of MediaWiki). This
		// saves the need for additional round trips. It also allows load.php
		// to remain stateless and sending personal data in the HTML instead.
		//
		// The HTML inline script lazy-defines the 'RLQ' array. Now that we are
		// processing it, replace it with an implementation where 'push' actually
		// considers executing the code directly. This is to ensure any late
		// arrivals will also be processed. Late arrival can happen because
		// startup.js is executed asynchronously, concurrently with the streaming
		// response of the HTML.
		queue = window.RLQ || [];
		// Replace RLQ with an empty array, then process the things that were
		// in RLQ previously. We have to do this to avoid an infinite loop:
		// non-function items are added back to RLQ by the processing step.
		RLQ = [];
		RLQ.push = function ( fn ) {
			if ( typeof fn === 'function' ) {
				fn();
			} else {
				// If the first parameter is not a function, then it is an array
				// containing a list of required module names and a function.
				// Do an actual push for now, as this signature is handled
				// later by mediawiki.base.js.
				RLQ[ RLQ.length ] = fn;
			}
		};
		while ( queue[ 0 ] ) {
			// Process all values gathered so far
			RLQ.push( queue.shift() );
		}

		// Clear and disable the basic (Grade C) queue.
		NORLQ = {
			push: function () {}
		};
	}() );
}
