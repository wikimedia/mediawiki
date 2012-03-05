/**
 * This script provides a function which is run to evaluate whether or not to continue loading the jquery and mediawiki
 * modules. This code should work on even the most ancient of browsers, so be very careful when editing.
 */
/**
 * Returns false when run in a black-listed browser
 * 
 * This function will be deleted after it's used, so do not expand it to be generally useful beyond startup
 * 
 * jQuery has minimum requirements of:
 * * Firefox 2.0+
 * * Internet Explorer 6+
 * * Safari 3+
 * * Opera 9+
 * * Chrome 1+
 */
window.isCompatible = function() {
	// IE < 6
	if ( navigator.appVersion.indexOf( 'MSIE' ) !== -1 && parseFloat( navigator.appVersion.split( 'MSIE' )[1] ) < 6 ) {
		return false;
	}
	// TODO: Firefox < 2
	// TODO: Safari < 3
	// TODO: Opera < 9
	// TODO: Chrome < 1
	return true;
};
/**
 * The startUp() function will be generated and added here (at the bottom)
 */
