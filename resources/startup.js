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
 * MediaWiki & jQuery compatibility:
 * - Internet Explorer 6.0+
 * - Firefox 10+
 * - Safari 5.0+
 * - Opera 11+
 * - Chrome
 * - Blackberry 5+
 * - Palm webOS 1.4+
 */

/*jshint unused: false */
function isCompatible( userAgent ) {
	var webkitBased;

	if ( typeof userAgent === 'undefined' ) {
		userAgent = navigator.userAgent;
	}

	webkitBased = userAgent.match( /WebKit/ );

	// IE < 6.0
	if ( userAgent.indexOf( 'MSIE' ) !== -1
		&& parseFloat( userAgent.split( 'MSIE' )[1] ) < 6 )
	{
		return false;
	// Mobile support based loosely on A grade browsers in http://jquerymobile.com/gbs/
	} else if ( userAgent.match( /BlackBerry[^\/]*\/[1-5]\./ ) ) {
		return false;
	} else if ( userAgent.match( /SymbianOS/ ) || userAgent.match( /Series60/ ) ) {
		return false;
	} else if ( userAgent.match( /webOS\/1\.[0-4]/ ) ) {
		return false;
	} else if ( userAgent.match( /Opera Mini/ ) ) {
		return false;
	// jQuery does not list as a supported browser [1]
	} else if ( userAgent.match( /NetFront/ ) && !webkitBased ) {
		return false;
	// jQuery  does not list as a supported browser  [1]
	} else if ( userAgent.match( /PlayStation/i ) ) {
		return false;
	// jQuery does not list as a supported browser [1]
	} else if ( userAgent.match( /SEMC-Browser/ ) ) {
		return false;
	}

	/*
	 [1] Based on http://jquery.com/browser-support/ and jQuery mobile support
	 Note that these can be reviewed at a future date if necessary
	*/
	return true;
}

/**
 * The startUp() function will be auto-generated and added below.
 */
