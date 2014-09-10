/**
 * This script provides a function which is run to evaluate whether or not to
 * continue loading jQuery and the MediaWiki modules. This code should work on
 * even the most ancient of browsers, so be very careful when editing.
 */

var mediaWikiLoadStart = ( new Date() ).getTime();

/**
 * Returns false for Grade C supported browsers.
 *
 * This function should only be used by the Startup module, do not expand it to
 * be generally useful beyond startup.
 *
 * See also:
 * - https://www.mediawiki.org/wiki/Compatibility#Browsers
 * - https://jquery.com/browser-support/
 */

/*jshint unused: false */
function isCompatible( ua ) {
	if ( ua === undefined ) {
		ua = navigator.userAgent;
	}

	// Browsers with outdated or limited JavaScript engines get the no-JS experience
	return !(
		// Internet Explorer < 8
		( ua.indexOf( 'MSIE' ) !== -1 && parseFloat( ua.split( 'MSIE' )[1] ) < 8 ) ||
		// Firefox < 3
		( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[1] ) < 3 ) ||
		// Opera < 12
		( ua.indexOf( 'Opera/' ) !== -1 && ( ua.indexOf( 'Version/' ) === -1 ?
			// "Opera/x.y"
			parseFloat( ua.split( 'Opera/' )[1] ) < 10 :
			// "Opera/9.80 ... Version/x.y"
			parseFloat( ua.split( 'Version/' )[1] ) < 12
		) ) ||
		// "Mozilla/0.0 ... Opera x.y"
		( ua.indexOf( 'Opera ' ) !== -1 && parseFloat( ua.split( ' Opera ' )[1] ) < 10 ) ||
		// BlackBerry < 6
		ua.match( /BlackBerry[^\/]*\/[1-5]\./ ) ||
		// Open WebOS < 1.5
		ua.match( /webOS\/1\.[0-4]/ ) ||
		// Anything PlayStation based.
		ua.match( /PlayStation/i ) ||
		// Any Symbian based browsers
		ua.match( /SymbianOS|Series60/ ) ||
		// Any NetFront based browser
		ua.match( /NetFront/ ) ||
		// Opera Mini, all versions
		ua.match( /Opera Mini/ ) ||
		// Nokia's Ovi Browser
		ua.match( /S40OviBrowser/ ) ||
		// Google Glass browser groks JS but UI is too limited
		( ua.match( /Glass/ ) && ua.match( /Android/ ) )
	);
}

/**
 * The startUp() function will be auto-generated and added below.
 */
