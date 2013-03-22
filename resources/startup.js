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
	// Blacklisted: IE < 6.0 and a bunch of mobile devices
	if ( navigator.userAgent.match(
		/^(lg-|lge-|nec-|pg-|sgh-|sie-)|(240x240|240x320|320x320|alcatel|audiovox|bada|benq|blackberry|cdm-|compal-|docomo|ericsson|hiptop|huawei|kddi-|kindle|meego|midp|mitsu|mmp\/|mobi|mot-|motor|msie [1-5]|ngm_|nintendo|opera.m|palm|panasonic|philips|phone|playstation|portalmmm|sagem-|samsung|sanyo|sec-|semc-browser|sendo|sharp|softbank|symbian|teleca|up.browser|vodafone|webos)/i
	) ) {
		if ( navigator.userAgent.match( /Android/ ) ||
			( navigator.userAgent.match( /iphone/i ) && !navigator.userAgent.match( /Opera/ ) ) )
		{
			return true;
		}
		return false;
	}
	return true;
}

/**
 * The startUp() function will be auto-generated and added below.
 */
