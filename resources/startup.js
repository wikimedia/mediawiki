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
 * - http://jquery.com/browser-support/
 *
 * MediaWiki & jQuery compatibility:
 * - Chrome
 * - Firefox 10+
 * - Safari 5.0+
 * - Opera 11+
 * - Internet Explorer 6.0+
 */

/*jshint unused: false */
function isCompatible( ua ) {
	if ( ua === undefined ) {
		ua = navigator.userAgent;
	}

	if (
		// Internet Explorer < 6
		( ua.indexOf( 'MSIE' ) !== -1 && parseFloat( ua.split( 'MSIE' )[1] ) < 6 ) ||
			// Firefox < 10
			( ua.indexOf( 'Firefox/' ) !== -1 && parseFloat( ua.split( 'Firefox/' )[1] ) < 10 )
	) {
		return false;
	}

	return true;
}

/**
 * The startUp() function will be auto-generated and added below.
 */
