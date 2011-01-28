/**
 * mediaWiki JavaScript library test suite
 *
 * Available on Special:BlankPage?action=mwutiltest&debug=true
 *
 * @author Krinkle <krinklemail@gmail.com>
 */

( function( $, mw ) {

	mediaWiki.test = {

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
			escapedtitle = mw.html.escape( title ).replace( /  /g, '&nbsp;&nbsp;' );
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
						var	skinLinksText = 'Test in: ';
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

						// Override wikitable padding for <td>
						mw.util.addCSS( '#mw-mwutiltest-table tr td { padding:0 !important; }' );

						mw.test.$table = $( 'table#mw-mwutiltest-table' );

						/* Populate tests */
						// Try to roughly keep the order similar to the order in the files
						// or alphabetical (depending on the context)

						// Main modules and their aliases
						mw.test.addHead( 'Main modules and their aliases' );

						mw.test.addTest( 'typeof mediaWiki',
							'object (string)' );

						mw.test.addTest( 'typeof mw',
							'object (string)' );

						mw.test.addTest( 'typeof jQuery',
							'function (string)' );

						mw.test.addTest( 'typeof $',
							'function (string)' );

						// Prototype functions added by MediaWiki
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

						mw.test.addTest( '$.escapeRE( ".st{e}$st" )',
							'\\.st\\{e\\}\\$st (string)' );

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

						// mediawiki.js
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

						mw.test.addTest( 'mw.config.get( "wgTitle" )',
							'BlankPage (string)' );

						mw.test.addTest( 'var a = mw.config.get( ["wgTitle"] ); a.wgTitle',
							'BlankPage (string)' );

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

						// mediawiki.util.js
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

						mw.test.addTest( 'var a = mw.util.addCSS( ".plainlinks { color:green; }" ); a.disabled;',
							'false (boolean)',
							'(boolean)' );

						mw.test.addTest( 'typeof mw.util.wikiGetlink',
							'function (string)' );

						mw.test.addTest( 'typeof mw.util.getParamValue',
							'function (string)' );

						mw.test.addTest( 'mw.util.getParamValue( "action" )',
							'mwutiltest (string)' );

						mw.test.addTest( 'mw.util.getParamValue( "foo", "http://mw.org/?foo=wrong&foo=right#&foo=bad" )',
							'right (string)' );

						mw.test.addTest( 'mw.util.tooltipAccessKeyRegexp.constructor.name',
							'RegExp (string)' );

						mw.test.addTest( 'typeof mw.util.updateTooltipAccessKeys',
							'function (string)' );

						mw.test.addTest( 'mw.util.$content instanceof jQuery',
							'true (boolean)' );

						mw.test.addTest( 'mw.util.$content.size()',
							'1 (number)' );

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
								mw.test.$table.find( 'tr#mw-mwutiltest-head' + ( numberOfHeaders ) +' > th' )
									.html( previousHeadTitle + ' <span style="float:right">('
										+ 'T: ' + headNumberOfTests
										+ ' ok: ' + headNumberOfPasseds
										+ ' partial: ' + headNumberOfPartials
										+ ' err: ' + headNumberOfErrors	
										+ ')</span>' );

								numberOfHeaders++;
								// Reset values for the new header;
								headNumberOfTests = 0;
								headNumberOfPasseds = 0;
								headNumberOfPartials = 0;
								headNumberOfErrors = 0;
								
								previousHeadTitle = mw.test.addedTests[i][1];
								
								return true;
							}

							exec = item[0];
							shouldreturn = item[1];
							shouldcontain = item[2];
							
							numberOfTests++;
							headNumberOfTests++;
							doesReturn = eval( exec );
							doesReturn = doesReturn + ' (' + typeof doesReturn + ')';
							$thisrow = $testrows.eq( i - numberOfHeaders ); // since headers are rows as well
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
								$thisrow.find( '> td' ).eq(3).css( 'background', '#FAA' ).text( 'ERROR' );
								numberOfErrors++;
								headNumberOfErrors++;
							}

						} );
						mw.test.$table.before( '<p><strong>Ran ' + numberOfTests + ' tests. ' +
							numberOfPasseds + ' passed test(s). ' + numberOfErrors + ' error(s). ' +
							numberOfPartials + ' partially passed test(s). </p>' );

						// hide all tests. TODO hide only OK?
						mw.test.$table.find( '.mw-mwutiltest-test' ).hide();
						// clickable header to show/hide the tests
						mw.test.$table.find( '.mw-mwutiltest-head' ).click(function() {
							$(this).nextUntil( '.mw-mwutiltest-head' ).toggle();
						});
					}
				} );
			}
		}
	};

	mediaWiki.test.init();

} )(jQuery, mediaWiki);
