/**
 * mediaWiki JavaScript library test suite
 *
 * Available on Special:BlankPage?action=mwutiltest&debug=true
 *
 * @author Krinkle <krinklemail@gmail.com>
 */

( function( $, mw ) {

	mw.test = {

		/* Variables */
		'$table' : null,
		// contains either a header or a test
		// test:   [ code, result, contain ]  see addTest
		// header: [ 'HEADER', escapedtitle, id ] see addHead
		'addedTests' : [],
		'headResults' : [],
		'numberOfHeader' : 0,

		/* Functions */

		/**
		* Adds a row to the test-table
		*
		* @param code String	Code of the test to be executed
		* @param result String	Expected result in 'var (vartype)' form
		* @param contain String	Important part of the result,
		*						if result is different but does contain this it will not return ERROR but PARTIALLY
		*/
		'addTest' : function( code, result, contain ) {
			if ( !contain ) {
				contain = result;
			}
			this.addedTests.push( [code, result, contain] );
			this.$table.append( '<tr class="mw-mwutiltest-test">'
				+ '<td>' + mw.html.escape( code ).replace( /  /g, '&nbsp;&nbsp;' )
				+ '</td><td>' + mw.html.escape( result ).replace( /  /g, '&nbsp;&nbsp;' )
				+ '</td><td></td><td>?</td></tr>' );
			return true;
		},

		/**
		* Adds a heading to the test-table
		*
		* @param title String	Title of the section
		*/
		'addHead' : function( title ) {
			if ( !title ) {
				return false;
			}
			var escapedtitle = mw.html.escape( title ).replace( /  /g, '&nbsp;&nbsp;' );
			this.addedTests.push( [ 'HEADER', escapedtitle, mw.test.numberOfHeader++ ] );
			this.$table.append( '<tr class="mw-mwutiltest-head" id="mw-mwutiltest-head'+mw.test.numberOfHeader+'"><th colspan="4">' + escapedtitle + '</th></tr>' );
			return true;
		},

		/* Initialisation */
		'initialised' : false,
		'init' : function() {
			if ( this.initialised === false ) {
				this.initialised = true;
				// jQuery document ready
				$( function() {
					if ( mw.config.get( 'wgCanonicalSpecialPageName' ) == 'Blankpage'
						&& mw.util.getParamValue( 'action' ) === 'mwutiltest' ) {

						// Build page
						document.title = 'mediaWiki JavaScript library test suite - ' + mw.config.get( 'wgSiteName' );
						$( '#firstHeading' ).text( 'mediaWiki JavaScript library test suite' );
						var	skinLinksText = 'Test in: ',
							skinLinks = [],
							availableSkins = mw.config.get( 'wgAvailableSkins' ),
							skincode = '';
						for ( skincode in availableSkins ) {
							skinLinks.push( mw.html.element( 'a', {
								'href': mw.util.wikiGetlink( wgPageName ) + '?action=mwutiltest&debug=true&useskin=' + encodeURIComponent( skincode )
								}, availableSkins[skincode] ) );
						}
						skinLinksText += skinLinks.join( ' | ' ) + '.';
						mw.util.$content.html(
							'<p>Below is a list of tests to confirm proper functionality of the mediaWiki JavaScript library</p>'
							+ '<p>' + skinLinksText + '</p>'
							+ '<hr />'
							+ '<table id="mw-mwutiltest-table" class="wikitable" style="white-space:break; font-family:monospace,\'Courier New\'; width:100%;">'
							+ '<tr><th>Exec</th><th>Should return</th><th>Does return</th><th>Equal ?</th></tr>'
							+ '</table>'
						);

						mw.util.addCSS(
							'#mw-mwutiltest-table tr td { padding:0 !important; }' // Override wikitable padding for <td>
						);

						mw.test.$table = $( 'table#mw-mwutiltest-table' );

						/* Populate tests */
						// Try to roughly keep the order similar to the order in the files
						// or alphabetical (depending on the context)

						/** Main modules and their aliases **/
						mw.test.addHead( 'Main modules and their aliases' );

						mw.test.addTest( 'typeof mediaWiki',
							'object (string)' );

						mw.test.addTest( 'typeof mw',
							'object (string)' );

						mw.test.addTest( 'typeof jQuery',
							'function (string)' );

						mw.test.addTest( 'typeof $',
							'function (string)' );

						/** Prototype functions added by MediaWiki **/
						mw.test.addHead( 'Prototype functions added by MediaWiki' );

						mw.test.addTest( 'typeof $.trimLeft',
							'function (string)' );

						mw.test.addTest( '$.trimLeft( "  foo bar  " )',
							'foo bar   (string)' );

						mw.test.addTest( 'typeof $.trimRight',
							'function (string)' );

						mw.test.addTest( '$.trimRight( "  foo bar  " )',
							'  foo bar (string)' );

						mw.test.addTest( 'typeof $.ucFirst',
							'function (string)' );

						mw.test.addTest( '$.ucFirst( "mediawiki" )',
							'Mediawiki (string)' );

						mw.test.addTest( 'typeof $.escapeRE',
							'function (string)' );

						mw.test.addTest( '$.escapeRE( "<!-- ([{+mW+}]) $^|?>" )',
							'<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?> (string)' ); // double escaped

						mw.test.addTest( '$.escapeRE( "ABCDEFGHIJKLMNOPQRSTUVWXYZ" )',
							'ABCDEFGHIJKLMNOPQRSTUVWXYZ (string)' );

						mw.test.addTest( '$.escapeRE( "abcdefghijklmnopqrstuvwxyz" )',
							'abcdefghijklmnopqrstuvwxyz (string)' );

						mw.test.addTest( '$.escapeRE( "0123456789" )',
							'0123456789 (string)' );

						mw.test.addTest( '$.isDomElement( document.getElementById("mw-mwutiltest-table") )',
							'true (boolean)' );

						mw.test.addTest( '$.isDomElement( document.getElementById("not-existant-id") )',
							'false (boolean)' ); // returns null

						mw.test.addTest( '$.isDomElement( document.getElementsByClassName("wikitable") )',
							'false (boolean)' ); // returns an array

						mw.test.addTest( '$.isDomElement( document.getElementsByClassName("wikitable")[0] )',
							'true (boolean)' );

						mw.test.addTest( '$.isDomElement( jQuery( "#mw-mwutiltest-table" ) )',
							'false (boolean)' ); // returns jQuery object

						mw.test.addTest( '$.isDomElement( jQuery( "#mw-mwutiltest-table" ).get(0) )',
							'true (boolean)' );

						mw.test.addTest( '$.isDomElement( document.createElement( "div" ) )',
							'true (boolean)' );

						mw.test.addTest( '$.isDomElement( {some: "thing" } )',
							'false (boolean)' );

						mw.test.addTest( 'typeof $.isEmpty',
							'function (string)' );

						mw.test.addTest( '$.isEmpty( "string" )',
							'false (boolean)' );

						mw.test.addTest( '$.isEmpty( "0" )',
							'true (boolean)' );

						mw.test.addTest( '$.isEmpty([])',
							'true (boolean)' );

						mw.test.addTest( 'typeof $.compareArray',
							'function (string)' );

						mw.test.addTest( '$.compareArray( [1, "a", [], [2, "b"] ], [1, "a", [], [2, "b"] ] )',
							'true (boolean)' );

						mw.test.addTest( '$.compareArray( [1], [2] )',
							'false (boolean)' );

						mw.test.addTest( 'typeof $.compareObject',
							'function (string)' );

						/** mediawiki.js **/
						mw.test.addHead( 'mediawiki.js' );

						mw.test.addTest( 'mw.config instanceof mw.Map',
							'true (boolean)' );

						mw.test.addTest( 'mw.config.exists()',
							'true (boolean)' );

						mw.test.addTest( 'mw.config.exists( "wgSomeName" )',
							'false (boolean)' );

						mw.test.addTest( 'mw.config.exists( ["wgCanonicalNamespace", "wgTitle"] )',
							'true (boolean)' );

						mw.test.addTest( 'mw.config.exists( ["wgSomeName", "wgTitle"] )',
							'false (boolean)' );

						mw.test.addTest( 'mw.config.get( "wgCanonicalNamespace" )',
							'Special (string)' );

						mw.test.addTest( 'var a = mw.config.get( ["wgCanonicalNamespace"] ); a.wgCanonicalNamespace',
							'Special (string)' );

						mw.test.addTest( 'typeof mw.html',
							'object (string)' );

						mw.test.addTest( 'mw.html.escape( \'<mw awesome="awesome">\' )',
							'&lt;mw awesome=&quot;awesome&quot;&gt; (string)' );

						mw.test.addTest( 'mw.html.element( "hr" )',
							'<hr/> (string)' );

						mw.test.addTest( 'mw.html.element( "img", { "src": "http://mw.org/?title=Main page&action=edit" } )',
							'<img src="http://mw.org/?title=Main page&amp;action=edit"/> (string)' );

						mw.test.addTest( 'typeof mw.loader',
							'object (string)' );

						mw.test.addTest( 'typeof mw.loader.using',
							'function (string)' );

						mw.test.addTest( 'typeof mw.Map',
							'function (string)' );

						mw.test.addTest( 'typeof mw.user',
							'object (string)' );

						mw.test.addTest( 'typeof mw.user.anonymous()',
							'boolean (string)' );

						/** mediawiki.util.js **/
						mw.test.addHead( 'mediawiki.util.js' );

						mw.test.addTest( 'typeof mw.util',
							'object (string)' );

						mw.test.addTest( 'typeof mw.util.rawurlencode',
							'function (string)' );

						mw.test.addTest( 'mw.util.rawurlencode( "Test:A & B/Here" )',
							'Test%3AA%20%26%20B%2FHere (string)' );

						mw.test.addTest( 'typeof mw.util.wikiUrlencode',
							'function (string)' );

						mw.test.addTest( 'mw.util.wikiUrlencode( "Test:A & B/Here" )',
							'Test:A_%26_B/Here (string)' );

						mw.test.addTest( 'typeof mw.util.addCSS',
							'function (string)' );

						mw.test.addTest( 'var a = mw.util.addCSS( "#mw-js-message { background-color: #AFA !important; }" ); a.disabled;',
							'false (boolean)',
							'(boolean)' );

						mw.test.addTest( 'typeof mw.util.toggleToc',
							'function (string)' );

						mw.test.addTest( 'typeof mw.util.wikiGetlink',
							'function (string)' );

						mw.test.addTest( 'typeof mw.util.getParamValue',
							'function (string)' );

						mw.test.addTest( 'mw.util.getParamValue( "action" )',
							'mwutiltest (string)' );

						mw.test.addTest( 'mw.util.getParamValue( "foo", "http://mw.org/?foo=wrong&foo=right#&foo=bad" )',
							'right (string)' );

						mw.test.addTest( 'typeof mw.util.tooltipAccessKeyPrefix',
							'string (string)' );

						mw.test.addTest( 'mw.util.tooltipAccessKeyRegexp.constructor.name',
							'RegExp (string)' );

						mw.test.addTest( 'typeof mw.util.updateTooltipAccessKeys',
							'function (string)' );

						mw.test.addTest( 'mw.util.$content instanceof jQuery',
							'true (boolean)' );

						mw.test.addTest( 'mw.util.$content.size()',
							'1 (number)' );

						mw.test.addTest( 'mw.util.isMainPage()',
							'false (boolean)' );

						mw.test.addTest( 'typeof mw.util.addPortletLink',
							'function (string)' );

						mw.test.addTest( 'typeof mw.util.addPortletLink( "p-tb", "http://mediawiki.org/wiki/ResourceLoader", "ResourceLoader", "t-rl", "More info about ResourceLoader on MediaWiki.org ", "l", "#t-specialpages" )',
							'object (string)' );

						mw.test.addTest( 'var a = mw.util.addPortletLink( "p-tb", "http://mediawiki.org/", "MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", "#t-rl" ); $(a).text();',
							'MediaWiki.org (string)' );

						mw.test.addTest( 'typeof mw.util.jsMessage',
							'function (string)' );

						mw.test.addTest( 'mw.util.jsMessage( mw.config.get( "wgSiteName" ) + " is <b>Awesome</b>." )',
							'true (boolean)' );

						// TODO: Import tests from PHPUnit test suite for user::isValidEmailAddr
						mw.test.addTest( 'mw.util.validateEmail( "" )',
							'null (object)' );

						mw.test.addTest( 'mw.util.validateEmail( "user@localhost" )',
							'true (boolean)' );

						// testEmailWithCommasAreInvalids
						mw.test.addTest( 'mw.util.validateEmail( "user,foo@example.org" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.validateEmail( "userfoo@ex,ample.org" )',
							'false (boolean)' );
						// testEmailWithHyphens
						mw.test.addTest( 'mw.util.validateEmail( "user-foo@example.org" )',
							'true (boolean)' );
						mw.test.addTest( 'mw.util.validateEmail( "userfoo@ex-ample.org" )',
							'true (boolean)' );

						// From IPTest.php IPv6
						mw.test.addTest( 'mw.util.isIPv6Address( "" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv6Address( ":fc:100::" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv6Address( "fc:100::" )',
							'true (boolean)' );
						mw.test.addTest( 'mw.util.isIPv6Address( "fc:100:a:d:1:e:ac::" )',
							'true (boolean)' );
						mw.test.addTest( 'mw.util.isIPv6Address( ":::" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv6Address( "::0:" )',
							'false (boolean)' );

						// From IPTest.php IPv4
						mw.test.addTest( 'mw.util.isIPv4Address( "" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "...." )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "abc" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "124.24.52" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( ".24.52.13" )',
							'false (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "124.24.52.13" )',
							'true (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "1.24.52.13" )',
							'true (boolean)' );
						mw.test.addTest( 'mw.util.isIPv4Address( "74.24.52.13/20" )', // Range
							'false (boolean)' );
							// @FIXME: The regex that's been in MW JS has never supported ranges but it should
							// The regex is expected to return false for that reason

						// jQuery plugins
						mw.test.addHead( 'jQuery plugins' );

						mw.test.addTest( 'typeof $.client',
							'object (string)' );

						mw.test.addTest( 'typeof $.client.profile',
							'function (string)' );

						mw.test.addTest( 'var a = $.client.profile(); typeof a.name',
							'string (string)' );

						mw.test.addTest( 'typeof $.fn.makeCollapsible',
							'function (string)' );


						// End of tests.
						mw.test.addHead( '*** End of tests ***' );

						// Run tests and compare results
						var	exec,
							result,
							resulttype,
							numberOfTests = 0,
							numberOfPasseds = 0,
							numberOfPartials = 0,
							numberOfErrors = 0,
							headNumberOfTests = 0,
							headNumberOfPasseds = 0,
							headNumberOfPartials = 0,
							headNumberOfErrors = 0,
							numberOfHeaders = 0,
							previousHeadTitle = '',
							$testrows = mw.test.$table.find( 'tr:has(td)' );

						$.each( mw.test.addedTests, function( i, item ) {

							// New header
							if( item[0] == 'HEADER' ) {

								// update current header with its tests results
								mw.test.$table.find( 'tr#mw-mwutiltest-head' + numberOfHeaders +' > th' )
									.html( previousHeadTitle + ' <span style="float:right">('
										+ 'Tests: ' + headNumberOfTests
										+ ' OK: ' + headNumberOfPasseds
										+ ' Partial: ' + headNumberOfPartials
										+ ' Error: ' + headNumberOfErrors
										+ ')</span>' );

								numberOfHeaders++;
								// Reset values for the new header;
								headNumberOfTests = 0;
								headNumberOfPasseds = 0;
								headNumberOfPartials = 0;
								headNumberOfErrors = 0;

								previousHeadTitle = item[1];

								return true;
							}

							exec = item[0];
							var shouldreturn = item[1];
							var shouldcontain = item[2];

							numberOfTests++;
							headNumberOfTests++;
							try {
								var doesReturn = eval( exec );
							} catch (e){
								mw.log ('mw.util.test> ' + e );
							}
							doesReturn = doesReturn + ' (' + typeof doesReturn + ')';
							var $thisrow = $testrows.eq( i - numberOfHeaders ); // since headers are rows as well
							$thisrow.find( '> td' ).eq(2).html( mw.html.escape( doesReturn ).replace(/  /g, '&nbsp;&nbsp;' ) );

							if ( doesReturn.indexOf( shouldcontain ) !== -1 ) {
								if ( doesReturn == shouldreturn ) {
									$thisrow.find( '> td' ).eq(3).css( 'background', '#AFA' ).text( 'OK' );
									numberOfPasseds++;
									headNumberOfPasseds++;
								} else {
									$thisrow.find( '> td' ).eq(3).css( 'background', '#FFA' ).html( '<small>PARTIALLY</small>' );
									numberOfPartials++;
									headNumberOfPartials++;
								}
							} else {
								$thisrow.css( 'background', '#FAA' ).find( '> td' ).eq(3).text( 'ERROR' );
								numberOfErrors++;
								headNumberOfErrors++;
							}

						} );
						mw.test.$table.before( '<p><strong>Ran ' + numberOfTests + ' tests. ' +
							numberOfPasseds + ' passed test(s). ' + numberOfErrors + ' error(s). ' +
							numberOfPartials + ' partially passed test(s). </p>' );

					}
				} );
			}
		}
	};

	mw.test.init();

} )(jQuery, mediaWiki);