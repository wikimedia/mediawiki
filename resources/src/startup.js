/**
 * This script provides a function which is run to evaluate whether or not to
 * continue loading jQuery and the MediaWiki modules. This code should work on
 * even the most ancient of browsers, so be very careful when editing.
 */
/*jshint unused: false, evil: true */
/*globals mw, RLQ: true, NORLQ: true, $VARS, $CODE, performance */

var mediaWikiLoadStart = ( new Date() ).getTime(),

	mwPerformance = ( window.performance && performance.mark ) ? performance : {
		mark: function () {}
	};

mwPerformance.mark( 'mwLoadStart' );

/**
 * Returns false for Grade C supported browsers.
 *
 * This function should only be used by the Startup module.
 * Do not expand it to be generally useful beyond this usecase.
 *
 * See also:
 * - https://www.mediawiki.org/wiki/Compatibility#Browsers
 * - https://jquery.com/browser-support/
 *
 * Passes in:
 * - IE9+
 * - Edge
 * - Firefox 3.5+
 * - Chrome
 * - Android 2.0+
 * - Safari 4+
 * - Mobile Safari (iOS1+)
 * - Opera 10.5+
 */
function isCompatible( str ) {
	var ua = str || navigator.userAgent;

	// Don't activate the JavaScript pipeline for users with browsers having
	// outdated or limited JavaScript engines.
	return !!(
		// http://caniuse.com/#feat=queryselector
		// Rejects: IE < 8
		'querySelector' in document

		// http://caniuse.com/#feat=namevalue-storage
		// https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		// Rejects: IE < 8, Firefox < 3.5, Safari < 4, Opera < 10.5
		//          Opera Mini, Blackberry < 6
		&& 'localStorage' in window

		// http://caniuse.com/#feat=addeventlistener
		// Rejects: IE < 9
		&& 'addEventListener' in window

		// Reject:
		// - WebOS < 1.5
		// - PlayStation
		// - Symbian-based browsers
		// - NetFront-based browser
		// - Opera Mini
		// - Nokia's Ovi Browser
		// - MeeGo's browser
		// - Google Glass
		&& !(
			ua.match( /webOS\/1\.[0-4]/ ) ||
			ua.match( /PlayStation/i ) ||
			ua.match( /SymbianOS|Series60|NetFront|Opera Mini|S40OviBrowser|MeeGo/ ) ||
			( ua.match( /Glass/ ) && ua.match( /Android/ ) )
		)
	);
}

// Conditional script injection
( function () {
	var NORLQ, script;
	if ( !isCompatible() ) {
		// Undo class swapping in case of an unsupported browser.
		// See OutputPage::getHeadScripts().
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
