/**
 * Code in this file MUST work on even the most ancient of browsers!
 *
 * This file is where we decide whether to initialise the modern run-time.
 */

/* global mw, $VARS, $CODE */

// eslint-disable-next-line no-unused-vars
var mediaWikiLoadStart = ( new Date() ).getTime(),
	mwPerformance = ( window.performance && performance.mark ) ? performance : {
		mark: function () {}
	};

mwPerformance.mark( 'mwLoadStart' );

/**
 * See <https://www.mediawiki.org/wiki/Compatibility#Browsers>
 *
 * Capabilities required for modern run-time:
 * - DOM Level 4 & Selectors API Level 1
 * - HTML5 & Web Storage
 * - DOM Level 2 Events
 * - JSON
 *
 * Browsers we support in our modern run-time (Grade A):
 * - Chrome 4+
 * - IE 9+
 * - Firefox 3.5+
 * - Safari 5+
 * - Opera 10.5+
 * - Mobile Safari (iOS 4+)
 * - Android 2.0+
 *
 * Browsers we support in our no-javascript run-time (Grade C):
 * - Chrome 1+
 * - IE 6+
 * - Firefox 3+
 * - Safari 3+
 * - Opera 10+
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
		// http://caniuse.com/#feat=queryselector
		'querySelector' in document &&

		// http://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// https://blog.whatwg.org/this-week-in-html-5-episode-30
		'localStorage' in window &&

		// http://caniuse.com/#feat=addeventlistener
		'addEventListener' in window &&

		// http://caniuse.com/#feat=json
		// https://phabricator.wikimedia.org/T141344#2784065
		( window.JSON && JSON.stringify && JSON.parse ) &&

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
