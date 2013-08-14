/**
 * This script provides a function which is run to evaluate whether or not to
 * continue loading the jquery and mediawiki modules. This code should work on
 * even the most ancient of browsers, so be very careful when editing.
 */

/**
 * Returns false when run in a black-listed browser
 *
 * This function will be deleted after it's used, so do not expand it to be
 * generally useful beyond startup.
 *
 * See also:
 * - https://www.mediawiki.org/wiki/Compatibility#Browser
 * - http://jquerymobile.com/gbs/
 * - http://jquery.com/browser-support/
 */

/*jshint unused: false */
function isCompatible( ua ) {
	if ( ua === undefined ) {
		ua = navigator.userAgent;
	}

	// MediaWiki JS or jQuery is known to have issues with:
	return !(
		// Internet Explorer < 6
		( ua.indexOf( 'MSIE' ) !== -1 && parseFloat( ua.split( 'MSIE' )[1] ) < 6 ) ||
		// Firefox < 3
		( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[1] ) < 3 ) ||
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
		ua.match( /S40OviBrowser/ )
	);
}

/**
 * The startUp() function will be auto-generated and added below.
 */
