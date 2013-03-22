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
	var webkitBased;

	if ( ua === undefined ) {
		ua = navigator.userAgent;
	}

	webkitBased = ua.match( /WebKit/ );

	// MediaWiki JS or jQuery is known to have issues with:
	return !(
		// Internet Explorer < 6
		( ua.indexOf( 'MSIE' ) !== -1 && parseFloat( ua.split( 'MSIE' )[1] ) < 6 ) ||
			// Firefox < 4
			( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[1] ) < 4 ) ||
			// BlackBerry < 6
			ua.match( /BlackBerry[^\/]*\/[1-5]\./ ) ||
			// Open WebOS < 1.5
			ua.match( /webOS\/1\.[0-4]/ )
	);
}

/**
 * The startUp() function will be auto-generated and added below.
 */
