/**
 * mediaWiki.util Test Suite
 *
 * Available on "/Special:BlankPage?action=mwutiltest&debug=true")
 *
 * @author Krinkle <krinklemail@gmail.com>
 */

(function ($, mw) {

	mediaWiki.test = {

		/* Variables */
		'$table' : null,
		'addedTests' : [],

		/* Functions */

		/**
		* Adds a row to the test-table
		*
		* @param String code	Code of the test to be executed
		* @param String result	Expected result in 'var (vartype)' form
		* @param String contain	Important part of the result, if result is different but does contain this it will not return ERROR but PARTIALLY
		*/
		'addTest' : function( code, result, contain ) {
			if (!contain) {
				contain = result;
			}
			this.addedTests.push([code, result, contain]);
			this.$table.append('<tr><td>' + mw.html.escape(code).replace(/  /g, '&nbsp;&nbsp;') + '</td><td>' + mw.html.escape(result).replace(/  /g, '&nbsp;&nbsp;') + '<td></td></td><td>?</td></tr>');
		},

		/* Initialisation */
		'initialised' : false,
		'init' : function () {
			if (this.initialised === false) {
				this.initialised = true;
				$(function () {
					if (wgCanonicalSpecialPageName == 'Blankpage' && mw.util.getParamValue('action') === 'mwutiltest') {

						// Build page
						document.title = 'mediaWiki.util JavaScript Test - ' + wgSiteName;
						$('#firstHeading').text('mediaWiki.util JavaScript Test');
						mw.util.$content.html(
							'<p>Below is a list of tests to confirm proper functionality of the mediaWiki.util functions</p>' +
							'<hr />' +
							'<table id="mw-mwutiltest-table" class="wikitable sortable" style="white-space:break; font-family:monospace,\'Courier New\'">' +
							'<tr><th>Exec</th><th>Should return</th><th>Does return</th><th>Equal ?</th></tr>' +
							'</table>'
						);
						mw.test.$table = $('table#mw-mwutiltest-table');

						// Populate tests
						mw.test.addTest('typeof $.trimLeft',
							'function (string)');
						mw.test.addTest('$.trimLeft(\'  foo bar  \')',
							'foo bar   (string)');
						mw.test.addTest('typeof $.trimRight',
							'function (string)');
						mw.test.addTest('$.trimRight(\'  foo bar  \')',
							'  foo bar (string)');
						mw.test.addTest('typeof $.compareArray',
							'function (string)');
						mw.test.addTest('$.compareArray( [1, "a", [], [2, \'b\'] ], [1, \'a\', [], [2, "b"] ] )',
							'true (boolean)');
						mw.test.addTest('$.compareArray( [1], [2] )',
							'false (boolean)');
						mw.test.addTest('4',
							'4 (number)');
						mw.test.addTest('typeof mediaWiki',
							'object (string)');
						mw.test.addTest('typeof mw',
							'object (string)');
						mw.test.addTest('typeof mw.util',
							'object (string)');
						mw.test.addTest('typeof mw.html',
							'object (string)');
						mw.test.addTest('typeof $.ucFirst',
							'function (string)');
						mw.test.addTest('$.ucFirst( \'mediawiki\' )',
							'Mediawiki (string)');
						mw.test.addTest('typeof $.escapeRE',
							'function (string)');
						mw.test.addTest('$.escapeRE( \'.st{e}$st\' )',
							'\\.st\\{e\\}\\$st (string)');
						mw.test.addTest('typeof $.fn.checkboxShiftClick',
							'function (string)');
						mw.test.addTest('typeof mw.util.rawurlencode',
							'function (string)');
						mw.test.addTest('mw.util.rawurlencode( \'Test: A&B/Here\' )',
							'Test%3A%20A%26B%2FHere (string)');
						mw.test.addTest('typeof mw.util.wikiGetlink',
							'function (string)');
						mw.test.addTest('typeof mw.util.getParamValue',
							'function (string)');
						mw.test.addTest('mw.util.getParamValue( \'action\' )',
							'mwutiltest (string)');
						mw.test.addTest('mw.util.getParamValue( \'foo\', \'http://mw.org/?foo=wrong&foo=right#&foo=bad\' )',
							'right (string)');
						mw.test.addTest('mw.util.tooltipAccessKeyRegexp.constructor.name',
							'RegExp (string)');
						mw.test.addTest('typeof mw.util.updateTooltipAccessKeys',
							'function (string)');
						mw.test.addTest('typeof mw.util.addPortletLink',
							'function (string)');
						mw.test.addTest('typeof mw.util.addPortletLink( "p-tb", "http://mediawiki.org/", "MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", "#t-print" )',
							'object (string)');
						mw.test.addTest('a = mw.util.addPortletLink( "p-tb", "http://mediawiki.org/", "MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", "#t-print" ); $(a).text();',
							'MediaWiki.org (string)');
						mw.test.addTest('mw.html.element( \'hr\' )',
							'<hr/> (string)');
						mw.test.addTest('mw.html.element( \'img\', { \'src\': \'http://mw.org/?title=Main page&action=edit\' } )',
							'<img src="http://mw.org/?title=Main page&amp;action=edit"/> (string)');

						// Run tests and compare results
						var	exec,
							result,
							resulttype,
							numberoftests = 0,
							numberofpasseds = 0,
							numberofpartials = 0,
							numberoferrors = 0,
							$testrows;
						$testrows = mw.test.$table.find('tr');
						$.each(mw.test.addedTests, (function ( i ) {
								numberoftests++;

								exec = mw.test.addedTests[i][0];
								shouldreturn = mw.test.addedTests[i][1];
								shouldcontain = mw.test.addedTests[i][2];
								doesreturn = eval(exec);
								doesreturn = doesreturn + ' (' + typeof doesreturn + ')';
								$thisrow = $testrows.eq(i + 1);
								$thisrow.find('> td').eq(2).text(doesreturn);

								if (doesreturn.indexOf(shouldcontain) !== -1) {
									if (doesreturn == shouldreturn){
										$thisrow.find('> td').eq(3).css('background', '#EFE').text('OK');
										numberofpasseds++;
									} else {
										$thisrow.find('> td').eq(3).css('background', '#FFE').html('<small>PARTIALLY</small>');
										numberofpartials++;
									}
								} else {
									$thisrow.find('> td').eq(3).css('background', '#FEE').text('ERROR');
									numberoferrors++;
								}

							})
						);
						mw.test.$table.before('<p><strong>Ran ' + numberoftests + ' tests. ' + numberofpasseds + ' passed test(s). ' + numberoferrors + ' error(s). ' + numberofpartials + ' partially passed test(s). </p>');

					}
				});
			}
		}
	};

	mediaWiki.test.init();

})(jQuery, mediaWiki);
