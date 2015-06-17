/**
 * This script provides a function which is run to evaluate whether or not to
 * continue initialising the MediaWiki client-side environment.
 *
 * This code MUST work on even the most ancient of browsers, ES5 or HTML5.
 *
 * The isCompatible() function returns false for Grade C supported browsers.
 *
 * This function should be kept minimal and should only to be used by the Startup module.
 *
 * See also:
 * - https://www.mediawiki.org/wiki/Compatibility#Browsers
 * - https://jquery.com/browser-support/
 */
/*jshint unused: false */

var mediaWikiLoadStart = ( new Date() ).getTime();

function isCompatible( win ) {
	if ( !win ) {
		win = window;
	}
	var ua = window.navigator.userAgent;

	return !!(
		// HTML5 Storage
		//
		// Source:
		// * http://caniuse.com/#feat=namevalue-storage
		// * https://developer.blackberry.com/html5/apis/v1_0/localstorage.html
		//
		// Rejects:
		// * Internet Explorer < 8, Firefox < 2, Safari < 4, Opera < 11
		// * Opera Mini, Blackberry < 6
		'localStorage' in window && (
			// Rejects: Firefox < 3
			( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[1] ) < 3 ) ||
			// Rejects: Opera 10-12 (Opera <= 9 had a different UA, rejected by feature test)
			( ua.indexOf( 'Opera/' ) !== -1 && ua.indexOf( 'Version/' ) !== -1 && parseFloat( ua.split( 'Version/' )[1] ) < 12 ) ||
			// Rejects: Open WebOS < 1.5
			ua.match( /webOS\/1\.[0-4]/ ) ||
			// Rejects: Anything PlayStation based.
			ua.match( /PlayStation/i ) ||
			// Rejects: Any Symbian based browsers
			ua.match( /SymbianOS|Series60/ ) ||
			// Rejects: Any NetFront based browser
			ua.match( /NetFront/ ) ||
			// Rejects: Nokia's Ovi Browser
			ua.match( /S40OviBrowser/ ) ||
			// Rejects: MeeGo's browser
			ua.match( /MeeGo/ ) ||
			// Rejects: Google Glass browser (groks JS but UI is too limited)
			( ua.match( /Glass/ ) && ua.match( /Android/ ) )
		)
	);
}

/**
 * The startUp() function will be auto-generated and added below.
 */
