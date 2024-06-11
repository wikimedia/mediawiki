/*!
 * Inline script for ResourceLoader\ClientHtml.php.
 *
 * This is tested via an exported function that takes `document` and `$VARS`.
 * See also QUnitTestResources.php.
 *
 * Like startup.js, this file is enforced by ESLint to be ES3-syntax compatible and
 * must degrade gracefully in older browsers.
 *
 * Use of ES5 (e.g. forEach) or ES6 methods (not syntax) is safe within the cookie conditional.
 */
/* global $VARS */
( function () {
	var className = $VARS.jsClass;
	var cookie = document.cookie.match( /(?:^|; )__COOKIE_PREFIX__mwclientpreferences=([^;]+)/ );
	if ( cookie ) {
		// The comma is escaped by mw.cookie.set
		cookie[ 1 ].split( '%2C' ).forEach( function ( pref ) {
			// To avoid misuse and to allow emergency shut-off, classes are only set when a matching
			// class for the same key already set. For new features, the default class must be set
			// a couple of weeks before the feature toggle is deployed, to give time for the
			// CDN/HTML cache to roll over.
			//
			// Regex explanation:
			// 1. `\w+`, match the "-value" suffix, this is equivalent to [a-zA-Z0-9_].
			//     This is stripped from the desired class to create a match for a current class.
			// 2. `[^\w-]`, any non-alphanumeric characters. This should never match but is
			//     stripped to ensure regex safety by keeping it simple (no need to escape).
			// 3. Match an existing class name as follows:
			//    * (^| ) = start of string or space
			//    * -clientpref- = enforce present of this literal string
			//    * ( |$) = end of string or space
			//
			// Replacement examples:
			// * vector-feature-foo-clientpref-2 -> vector-feature-foo-clientpref-4
			// * mw-foo-clientpref-enabled       -> mw-foo-clientpref-disabled
			// * mw-display-clientpref-dark      -> mw-display-clientpref-light
			className = className.replace(

				new RegExp( '(^| )' + pref.replace( /-clientpref-\w+$|[^\w-]+/g, '' ) + '-clientpref-\\w+( |$)' ),
				'$1' + pref + '$2'
			);
		} );
	}
	document.documentElement.className = className;
}() );
