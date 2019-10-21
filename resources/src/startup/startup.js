/**
 * This file is where we decide whether to initialise the Grade A run-time.
 *
 * - Beware: This file MUST parse without errors on even the most ancient of browsers!
 */
/* eslint-disable no-implicit-globals */
/* global $VARS, $CODE, RLQ:true, NORLQ:true */

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
 * @private
 * @param {string} ua User agent string
 * @return {boolean} User agent is compatible with MediaWiki JS
 */
function isCompatible( ua ) {
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

		// Hardcoded exceptions for browsers that pass the requirement but we don't
		// want to support in the modern run-time.
		//
		// Please extend the regex instead of adding new ones!
		// And add a test case to startup.test.js
		!ua.match( /MSIE 10|NetFront|Opera Mini|S40OviBrowser|MeeGo|Android.+Glass|^Mozilla\/5\.0 .+ Gecko\/$|googleweblight|PLAYSTATION|PlayStation/ )
	);
}

if ( !isCompatible( navigator.userAgent ) ) {
	// Handle Grade C
	// Undo speculative Grade A <html> class. See ResourceLoaderClientHtml::getDocumentAttributes().
	document.documentElement.className = document.documentElement.className
		.replace( /(^|\s)client-js(\s|$)/, '$1client-nojs$2' );

	// Process any callbacks for Grade C
	while ( window.NORLQ && NORLQ[ 0 ] ) {
		NORLQ.shift()();
	}
	NORLQ = {
		push: function ( fn ) {
			fn();
		}
	};

	// Clear and disable the Grade A queue
	RLQ = {
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

		$CODE.registrations();

		mw.config.set( $VARS.configuration );
		// For the current page
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
		RLQ = window.RLQ || [];
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
		while ( RLQ[ 0 ] ) {
			// Process all values gathered so far
			RLQ.push( RLQ.shift() );
		}

		// Clear and disable the Grade C queue
		NORLQ = {
			push: function () {}
		};
	}() );
}
