/**
 * This file is where we decide whether to initialise the Grade A run-time.
 *
 * - Beware: This file MUST parse without errors on even the most ancient of browsers!
 * - Beware: Do not call mwNow before the isCompatible() check.
 */

/* global mw, $VARS, $CODE */

var mwPerformance = ( window.performance && performance.mark ) ? performance : {
		mark: function () {}
	},
	// Define now() here to ensure valid comparison with mediaWikiLoadEnd (T153819).
	mwNow = ( function () {
		var perf = window.performance,
			navStart = perf && perf.timing && perf.timing.navigationStart;
		return navStart && typeof perf.now === 'function' ?
			function () { return navStart + perf.now(); } :
			function () { return Date.now(); };
	}() ),
	// eslint-disable-next-line no-unused-vars
	mediaWikiLoadStart;

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
 * - IE 10+
 * - Firefox 4+
 * - Safari 5+
 * - Opera 12.10+
 * - Mobile Safari 5.1+ (iOS 5+)
 * - Android 4.1+
 *
 * Browsers we support in our no-javascript run-time (Grade C):
 * - Chrome 1+
 * - IE 6+
 * - Firefox 3+
 * - Safari 3+
 * - Opera 10+
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
		// http://caniuse.com/#feat=es5
		// http://caniuse.com/#feat=use-strict
		// http://caniuse.com/#feat=json / https://phabricator.wikimedia.org/T141344#2784065
		( function () {
			'use strict';
			return !this && !!Function.prototype.bind && !!window.JSON;
		}() ) &&

		// http://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// http://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// http://caniuse.com/#feat=addeventlistener
		'addEventListener' in window &&

		// Hardcoded exceptions for browsers that pass the requirement but we don't want to
		// support in the modern run-time.
		// Note: Please extend the regex instead of adding new ones
		!(
			ua.match( /webOS\/1\.[0-4]|SymbianOS|Series60|NetFront|Opera Mini|S40OviBrowser|MeeGo|Android.+Glass|^Mozilla\/5\.0 .+ Gecko\/$|googleweblight/ ) ||
			ua.match( /PlayStation/i )
		)
	);
}

// Conditional script injection
( function () {
	var NORLQ, script;
	if ( !isCompatible() ) {
		// Undo class swapping in case of an unsupported browser.
		// See ResourceLoaderClientHtml::getDocumentAttributes().
		document.documentElement.className = document.documentElement.className
			.replace( /(^|\s)client-js(\s|$)/, '$1client-nojs$2' );

		NORLQ = window.NORLQ || [];
		while ( NORLQ.length ) {
			NORLQ.shift()();
		}
		window.NORLQ = {
			push: function ( fn ) {
				fn();
			}
		};

		// Clear and disable the other queue
		window.RLQ = {
			// No-op
			push: function () {}
		};

		return;
	}

	/**
	 * The $CODE and $VARS placeholders are substituted in ResourceLoaderStartUpModule.php.
	 */
	function startUp() {
		mw.config = new mw.Map( $VARS.wgLegacyJavaScriptGlobals );

		$CODE.registrations();

		mw.config.set( $VARS.configuration );

		// Must be after mw.config.set because these callbacks may use mw.loader which
		// needs to have values 'skin', 'debug' etc. from mw.config.
		// eslint-disable-next-line vars-on-top
		var RLQ = window.RLQ || [];
		while ( RLQ.length ) {
			RLQ.shift()();
		}
		window.RLQ = {
			push: function ( fn ) {
				fn();
			}
		};

		// Clear and disable the other queue
		window.NORLQ = {
			// No-op
			push: function () {}
		};
	}

	mediaWikiLoadStart = mwNow();
	mwPerformance.mark( 'mwLoadStart' );

	script = document.createElement( 'script' );
	script.src = $VARS.baseModulesUri;
	script.onload = script.onreadystatechange = function () {
		if ( !script.readyState || /loaded|complete/.test( script.readyState ) ) {
			// Clean up
			script.onload = script.onreadystatechange = null;
			script = null;
			// Callback
			startUp();
		}
	};
	document.getElementsByTagName( 'head' )[ 0 ].appendChild( script );
}() );
