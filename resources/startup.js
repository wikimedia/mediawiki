/**
 * This script provides a function which is run to evaluate whether or not to
 * continue loading the jquery and mediawiki modules. This code should work on
 * even the most ancient of browsers, so be very careful when editing.
 */
/**
 * Returns false when run in a black-listed browser
 *
 * This function will be deleted after it's used, so do not expand it to be
 * generally useful beyond startup
 *
 * jQuery has minimum requirements of:
 * * Internet Explorer 6.0+
 * * Firefox 3.6+
 * * Safari 5.0+
 * * Opera 11+
 * * Chrome
 */
function isCompatible() {
	// IE < 6.0
	if ( navigator.appVersion.indexOf( 'MSIE' ) !== -1
		&& parseFloat( navigator.appVersion.split( 'MSIE' )[1] ) < 6 )
	{
		return false;
	}
	// @todo FIXME: Firefox < 3.6
	// @todo FIXME: Safari < 5.0
	// @todo FIXME: Opera < 11
	return true;
}
/**
 * The startUp() function will be generated and added here (at the bottom)
 */
