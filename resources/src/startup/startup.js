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
 * - ECMAScript 5
 * - DOM Level 4 (including Selectors API)
 * - HTML5 (including Web Storage API)
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
 * Browsers we support in our no-JavaScript, basic run-time (Grade C):
 * - Chrome 1+
 * - IE 8+
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
 * Other browsers that pass the check are considered unknown (Grade X).
 *
 * @private
 * @param {string} ua User agent string
 * @return {boolean} User agent is compatible with MediaWiki JS
 */
function isCompatible( ua ) {
	return !!(
		// https://caniuse.com/#feat=es5
		// https://caniuse.com/#feat=use-strict
		( function () {
			'use strict';
			return !this && Function.prototype.bind;
		}() ) &&

		// https://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// https://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// Force certain browsers into Basic mode, even if they pass the check.
		//
		// Some of the below are "remote browsers", where the webpage is actually
		// rendered remotely in a capable browser (cloud service) by the vendor,
		// with the client app receiving a graphical representation through a
		// format that is not HTML/CSS. These get a better user experience if
		// we turn JavaScript off, to avoid triggering JavaScript calls, which
		// either don't work or require a roundtrip to the server with added
		// latency. Note that remote browsers are sometimes referred to as
		// "proxy browsers", but that term is also conflated with browsers
		// that accelerate or compress web pages through a "proxy", where
		// client-side JS would generally be okay.
		//
		// Remember:
		//
		// - Add new entries on top, and document why and since when.
		// - Please extend the regex instead of adding new ones, for performance.
		// - Add a test case to startup.test.js.
		//
		// Forced into Basic mode:
		//
		// - MSIE 10: Bugs (since 2018, T187869).
		//   Low traffic. Reduce support cost by no longer having to workaround
		//   bugs in its JavaScript APIs.
		//
		// - UC Mini "Speed Mode": Improve UX, save data (since 2016, T147369).
		//   Does not have an obvious user agent, other than ending with an
		//   incomplete `Gecko/` token.
		//
		// - Google Web Light: Bugs, save data (since 2016, T152602).
		//   Proxy breaks most JavaScript.
		//
		// - MeeGo: Bugs (since 2015, T97546).
		//
		// - Opera Mini: Improve UX, save data. (since 2013, T49572).
		//   It is a remote browser.
		//
		// - Ovi Browser: Improve UX, save data (since 2013, T57600).
		//   It is a remote browser. UA contains "S40OviBrowser".
		//
		// - Google Glass: Improve UX (since 2013, T58008).
		//   Run modern browser engine, but limited UI is better served when
		//   content is expand by default, requiring little interaction.
		//
		// - NetFront: Unsupported by jQuery (since 2013, commit c46fc74).
		// - PlayStation: Unsupported by jQuery (since 2013, commit c46fc74).
		//
		!ua.match( /MSIE 10|NetFront|Opera Mini|S40OviBrowser|MeeGo|Android.+Glass|^Mozilla\/5\.0 .+ Gecko\/$|googleweblight|PLAYSTATION|PlayStation/ )
	);
}

if ( !isCompatible( navigator.userAgent ) ) {
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

		// For the current page
		mw.config.set( window.RLCONF || {} ); // mw.loader needs wgCSPNonce and wgUserName
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
