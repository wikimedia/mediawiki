/*
 * mediaWiki Debug Test Suit.
 * Available on "/Special:BlankPage?action=mwutiltest&debug=true")
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
		'addTest' : function (code, result, contain) {
			if (!contain) {
				contain = result;
			}
			this.addedTests.push([code, result, contain]);
			this.$table.append('<tr><td>' + mw.util.htmlEscape(code) + '</td><td>' + mw.util.htmlEscape(result) + '<td></td></td><td>?</td></tr>');
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
							'<table id="mw-mwutiltest-table" class="wikitable sortable"><tr><th>Exec</th><th>Should return</th><th>Does return</th><th>Equal ?</th></tr></table>'
						);
						mw.test.$table = $('table#mw-mwutiltest-table');

						// Populate tests
						mw.test.addTest('typeof String.prototype.trim',
							'function (string)');
						mw.test.addTest('typeof String.prototype.trimLeft',
							'function (string)');
						mw.test.addTest('typeof String.prototype.trimRight',
							'function (string)');
						mw.test.addTest('typeof Array.prototype.compare',
							'function (string)');
						mw.test.addTest('typeof Array.prototype.indexOf',
							'function (string)');
						mw.test.addTest('4',
							'4 (number)');
						mw.test.addTest('typeof mediaWiki',
							'object (string)');
						mw.test.addTest('typeof mw',
							'object (string)');
						mw.test.addTest('typeof mw.util',
							'object (string)');
						mw.test.addTest('typeof String.prototype.ucFirst',
							'function (string)');
						mw.test.addTest('\'mediawiki\'.ucFirst()',
							'Mediawiki (string)');
						mw.test.addTest('typeof $.fn.enableCheckboxShiftClick',
							'function (string)');
						mw.test.addTest('typeof mw.util.rawurlencode',
							'function (string)');
						mw.test.addTest('mw.util.rawurlencode(\'Test: A&B/Here\')',
							'Test%3A%20A%26B%2FHere (string)');
						mw.test.addTest('typeof mw.util.getWikilink',
							'function (string)');
						mw.test.addTest('typeof mw.util.getParamValue',
							'function (string)');
						mw.test.addTest('mw.util.getParamValue(\'action\')',
							'mwutiltest (string)');
						mw.test.addTest('typeof mw.util.htmlEscape',
							'function (string)');
						mw.test.addTest('mw.util.htmlEscape(\'<a href="http://mw.org/?a=b&c=d">link</a>\')',
							'&lt;a href="http://mw.org/?a=b&amp;c=d"&gt;link&lt;/a&gt; (string)');
						mw.test.addTest('typeof mw.util.htmlUnescape',
							'function (string)');
						mw.test.addTest('mw.util.htmlUnescape(\'&lt;a href="http://mw.org/?a=b&amp;c=d"&gt;link&lt;/a&gt;\')',
							'<a href="http://mw.org/?a=b&c=d">link</a> (string)');
						mw.test.addTest('typeof mw.util.tooltipAccessKeyRegexp',
							'function (string)');
						mw.test.addTest('typeof mw.util.updateTooltipAccessKeys',
							'function (string)');
						mw.test.addTest('typeof mw.util.addPortletLink',
							'function (string)');
						mw.test.addTest('typeof mw.util.addPortletLink("p-tb", "http://mediawiki.org/", "MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", "#t-print")',
							'object (string)');
						mw.test.addTest('a = mw.util.addPortletLink("p-tb", "http://mediawiki.org/", "MediaWiki.org", "t-mworg", "Go to MediaWiki.org ", "m", "#t-print"); if(a){ a.outerHTML; }',
							'<li id="t-mworg"><span><a href="http://mediawiki.org/" accesskey="m" title="Go to MediaWiki.org  [ctrl-alt-m]">MediaWiki.org</a></span></li> (string)',
							'href="http://mediawiki.org/"');

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
						$.each(mw.test.addedTests, (function (i) {
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