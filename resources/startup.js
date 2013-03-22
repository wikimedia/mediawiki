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
	return isCompatibleUserAgent( navigator.userAgent );
}

function isCompatibleUserAgent( userAgent ) {
	// IE < 6.0
	if ( userAgent.indexOf( 'MSIE' ) !== -1
		&& parseFloat( userAgent.split( 'MSIE' )[1] ) < 6 )
	{
		return false;
	}
	if ( isMobileDevice( userAgent ) ) {
		return /Android|Firefox|iPhone|iPad|MSIE (8|9|1\d)\.|Opera Mobi|Samsung|WebKit|Wii/i.test( userAgent )
			&& !/Opera Mini|Series60|webOS/i.test( userAgent );

	}
	return true;
}

function isMobileDevice( userAgent ) {
	var patterns = [
		'mobi',
		'phone',
		'android',
		'ipod',
		'webos',
		'palm',
		'opera.m',
		'semc-browser',
		'playstation',
		'nintendo',
		'blackberry',
		'bada',
		'meego',
		'vodafone',
		'docomo',
		'samsung',
		'alcatel',
		'motor',
		'huawei',
		'audiovox',
		'philips',
		'mot-',
		'cdm-',
		'sagem-',
		'htc[-_]',
		'ngm_',
		'mmp\/',
		'up.browser',
		'symbian',
		'midp',
		'kindle',
		'softbank',
		'sec-',
		'240x240',
		'240x320',
		'320x320',
		'ericsson',
		'panasonic',
		'hiptop',
		'portalmmm',
		'kddi-',
		'benq',
		'compal-',
		'sanyo',
		'sharp',
		'teleca',
		'mitsu',
		'sendo'
	];
	var patternsStart = [
		'lg-',
		'sie-',
		'nec-',
		'lge-',
		'sgh-',
		'pg-'
	];
	var regex = new RegExp( '^(' + patternsStart.join( '|' ) + ')|(' + patterns.join( '|' ) + ')', 'i' );
	return regex.test( userAgent );
}

/**
 * The startUp() function will be auto-generated and added below.
 */
