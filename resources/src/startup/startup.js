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
 * Browsers that pass these checks get served our modern run-time. This includes all Grade A
 * browsers, and some Grade C and Grade X browsers.
 *
 * The following browsers are known to pass these checks:
 * - Chrome 73+
 * - Edge 79+
 * - Firefox 78+
 * - Safari 12.1+
 * - Mobile Safari on iOS 12.2+
 * - Android 5.0 (upto Chrome 95)
 *
 * @private
 * @return {boolean} User agent is compatible with MediaWiki JS
 */
function isCompatible() {
	return !!(
		// Ensure DOM Level 4 features (including Selectors API).
		//
		// https://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// Ensure HTML 5 features (including Web Storage API)
		//
		// https://caniuse.com/#feat=namevalue-storage
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// Ensure ES2019 runtime API
		//
		// ES2018 RegExp.prototype.dotAll
		// https://caniuse.com/mdn-javascript_builtins_regexp_dotall
		// Chrome 62+, Edge 79+, Firefox 78+, Safari 11.1+, iOS 11.3+
		//
		// ES2019 Object.fromEntries
		// https://caniuse.com/mdn-javascript_builtins_object_fromentries
		// Chrome 73+, Edge 79+, Firefox 63+, Safari 12.1+, iOS 12.2+
		//
		// Test `/./.dotAll` false instead `/./s.dotAll` true, because the latter would
		// throw a syntax error for flag "s" in older browsers and defeat our purpose,
		// whereas the former safely returns undefined.
		// Run `/./.foo` (undefined) or `/./q` (error) to observe this in current browsers.
		/./.dotAll === false &&
		'fromEntries' in Object &&

		// Ensure ES2019 grammar and syntax support
		//
		// ES2018 Async Generator Functions
		// (Firefox 55 implemented it, Firefox 57 enabled it in stable)
		// https://caniuse.com/mdn-javascript_builtins_asyncgenerator
		// https://caniuse.com/wf-async-generators
		// Chrome 63+, Edge 79+, Firefox 57+, Safari 12+, iOS 12+
		//
		// ES2019 Optional catch binding
		// https://caniuse.com/mdn-javascript_statements_try_catch_optional_catch_binding
		// Chrome 66+, Edge 79+, Firefox 58+, Safari 11.1+, iOS 11.3+
		//
		// Based on Benjamin De Cock's snippet here:
		// https://gist.github.com/bendc/d7f3dbc83d0f65ca0433caf90378cd95
		( function () {
			try {
				// eslint-disable-next-line no-new, no-new-func
				new Function( 'try { async function* x() {} } catch {}' );
				return true;
			} catch ( e ) {
				return false;
			}
		}() )
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
	 * The $CODE placeholder is substituted in StartUpModule.php.
	 */
	( function () {
		/* global mw */
		var queue;

		$CODE.registrations();

		// First set page-specific config needed by mw.loader (wgUserName)
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
