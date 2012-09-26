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
 */

/*jshint unused: false */
function isCompatible() {
	// IE < 6.0
	if ( navigator.appVersion.indexOf( 'MSIE' ) !== -1
		&& parseFloat( navigator.appVersion.split( 'MSIE' )[1] ) < 6 )
	{
		return false;
	}
	return true;
}

/**
 * The startUp() function will be auto-generated and added below.
 */
