QUnit.module( 'jquery.tablesorter.parsers', QUnit.newMwEnvironment(
	{
		beforeEach: function () {
			this.originalMonths = mw.language.months;
			mw.language.months = {
				keys: {
					names: [ 'january', 'february', 'march', 'april', 'may_long', 'june',
						'july', 'august', 'september', 'october', 'november', 'december' ],
					genitive: [ 'january-gen', 'february-gen', 'march-gen', 'april-gen', 'may-gen', 'june-gen',
						'july-gen', 'august-gen', 'september-gen', 'october-gen', 'november-gen', 'december-gen' ],
					abbrev: [ 'jan', 'feb', 'mar', 'apr', 'may', 'jun',
						'jul', 'aug', 'sep', 'oct', 'nov', 'dec' ]
				},
				names: [ 'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December' ],
				genitive: [ 'January', 'February', 'March', 'April', 'May', 'June',
					'July', 'August', 'September', 'October', 'November', 'December' ],
				abbrev: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
					'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]
			};
			mw.config.set( {
				wgPageContentLanguage: 'en',
				/* default date format of the content language */
				wgDefaultDateFormat: 'dmy',
				/* These two are important for numeric interpretations */
				wgSeparatorTransformTable: [ '', '' ],
				wgDigitTransformTable: [ '', '' ]
			} );

			/**
			 * Check how the parser recognizes and transforms the data
			 *
			 * @param {Object} assert
			 * @param {string} parserId The parser that will be tested
			 * @param {string[][]} data Array of testcases. Each testcase, array of:
			 *  - inputValue: The string value that we want to test the parser for.
			 *  - recognized: If we expect that this value's type is detectable by the parser.
			 *  - outputValue: The value the parser has converted the input to.
			 *  - msg: describing the testcase.
			 */
			this.parser = function assertParser( assert, parserId, data ) {
				var parser = $.tablesorter.getParser( parserId );
				data.forEach( function ( testcase ) {
					var extractedR = parser.is( testcase[ 0 ] );
					var extractedF = parser.format( testcase[ 0 ] );

					assert.strictEqual( extractedR, testcase[ 1 ], 'Detect: ' + testcase[ 3 ] );
					assert.strictEqual( extractedF, testcase[ 2 ], 'Sortkey: ' + testcase[ 3 ] );
				} );
			};

		},
		afterEach: function () {
			mw.language.months = this.originalMonths;
		}
	}
) );

QUnit.test( 'Textual keys', function ( assert ) {
	this.parser( assert, 'text', [
		[ 'Mars', true, 'Mars', 'Simple text' ],
		[ 'Mẘas', true, 'Mẘas', 'Non ascii character' ],
		[ 'A sentence', true, 'A sentence', 'A sentence with space chars' ]
	] );
} );

QUnit.test( 'IPv4', function ( assert ) {
	this.parser( assert, 'IPAddress', [
		// Some randomly generated fake IPs
		[ '0.0.0.0', true, 0, 'An IP address' ],
		[ '255.255.255.255', true, 255255255255, 'An IP address' ],
		[ '45.238.27.109', true, 45238027109, 'An IP address' ],
		[ '1.238.27.1', true, 1238027001, 'An IP address with small numbers' ],
		[ '238.27.1', false, 238027001, 'A malformed IP Address' ],
		[ '1', false, 1, 'A super malformed IP Address' ],
		[ 'Just text', false, -Infinity, 'A line with just text' ],
		[ '45.238.27.109Postfix', false, 45238027109, 'An IP address with a connected postfix' ],
		[ '45.238.27.109 postfix', false, 45238027109, 'An IP address with a separated postfix' ]
	] );
} );

QUnit.test( 'MDY Dates using mdy content language', function ( assert ) {
	this.parser( assert, 'date', [
		[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
		[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
		[ '1/17/2010', true, 20100117, 'Numeric middle endian date' ],
		[ '01/17/2010', true, 20100117, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010', true, 20100107, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010', true, 20100107, 'Numeric middle endian date with padding on year' ],
		[ '5.12.1990', true, 19900512, 'Numeric middle endian date with . separator' ]
	] );
} );

QUnit.test( 'MDY Dates using dmy content language', function ( assert ) {
	mw.config.set( {
		wgDefaultDateFormat: 'dmy',
		wgPageContentLanguage: 'de'
	} );
	this.parser( assert, 'date', [
		[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
		[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
		[ '1/17/2010', true, 20101701, 'Numeric middle endian date' ],
		[ '01/17/2010', true, 20101701, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010', true, 20100701, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010', true, 20100701, 'Numeric middle endian date with padding on year' ],
		[ '5.12.1990', true, 19901205, 'Numeric middle endian date with . separator' ]
	] );
} );

QUnit.test( 'Very old MDY dates', function ( assert ) {
	this.parser( assert, 'date', [
		[ 'January 19, 1400 BC', false, '99999999', 'BC' ],
		[ 'January 19, 1400BC', false, '99999999', 'Connected BC' ],
		[ 'January, 19 1400 B.C.', false, '99999999', 'B.C.' ],
		[ 'January 19, 1400 AD', false, '99999999', 'AD' ],
		[ 'January, 19 10', true, 20100119, 'AD' ],
		[ 'January, 19 1', false, '99999999', 'AD' ]
	] );
} );

QUnit.test( 'MDY Dates', function ( assert ) {
	this.parser( assert, 'date', [
		[ 'January, 19 2010', true, 20100119, 'Comma after month' ],
		[ 'January 19, 2010', true, 20100119, 'Comma after day' ],
		[ 'January/19/2010', true, 20100119, 'Forward slash separator' ],
		[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
		[ 'April 21 1991', true, 19910421, 'Space separation' ],
		[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
		[ 'December 12 \'10', true, 20101212, '' ],
		[ 'Dec 12 \'10', true, 20101212, '' ],
		[ 'Dec. 12 \'10', true, 20101212, '' ]
	] );
} );

QUnit.test( 'Clobbered Dates', function ( assert ) {
	this.parser( assert, 'date', [
		[ 'January, 19 2010 - January, 20 2010', false, '99999999', 'Date range with hyphen' ],
		[ 'January, 19 2010 — January, 20 2010', false, '99999999', 'Date range with mdash' ],
		[ 'prefixJanuary, 19 2010', false, '99999999', 'Connected prefix' ],
		[ 'prefix January, 19 2010', false, '99999999', 'Prefix' ],
		[ 'December 12 2010postfix', false, '99999999', 'ConnectedPostfix' ],
		[ 'December 12 2010 postfix', false, '99999999', 'Postfix' ],
		[ 'A simple text', false, '99999999', 'Plain text in date sort' ],
		[ '04l22l1991', false, '99999999', 'l char as separator' ],
		[ 'January\\19\\2010', false, '99999999', 'backslash as date separator' ]
	] );
} );

QUnit.test( 'MY Dates', function ( assert ) {
	this.parser( assert, 'date', [
		[ 'December 2010', false, '99999999', 'Plain month year' ],
		[ 'Dec 2010', false, '99999999', 'Abreviated month year' ],
		[ '12 2010', false, '99999999', 'Numeric month year' ]
	] );
} );

QUnit.test( 'Y Dates', function ( assert ) {
	this.parser( assert, 'date', [
		[ '2010', false, '99999999', 'Plain 4-digit year' ],
		[ '876', false, '99999999', '3-digit year' ],
		[ '76', false, '99999999', '2-digit year' ],
		[ '\'76', false, '99999999', '2-digit millenium bug year' ],
		[ '2010 BC', false, '99999999', '4-digit year BC' ]
	] );
} );

QUnit.test( 'Currency', function ( assert ) {
	this.parser( assert, 'currency', [
		[ '1.02 $', true, 1.02, '' ],
		[ '$ 3.00', true, 3, '' ],
		[ '€ 2,99', true, 299, '' ],
		[ '$ 1.00', true, 1, '' ],
		[ '$3.50', true, 3.50, '' ],
		[ '$ 1.50', true, 1.50, '' ],
		[ '€ 0.99', true, 0.99, '' ],
		[ '$ 299.99', true, 299.99, '' ],
		[ '$ 2,299.99', true, 2299.99, '' ],
		[ '$ 2,989', true, 2989, '' ],
		[ '$ 2 299.99', true, 2299.99, '' ],
		[ '$ 2 989', true, 2989, '' ],
		[ '$ 2.989', true, 2.989, '' ]
	] );
} );

QUnit.test( 'Currency with european separators', function ( assert ) {
	mw.config.set( {
		// We expect 22'234.444,22
		// Map from ascii separators => localized separators
		wgSeparatorTransformTable: [ ',\t.\t,', '\'\t,\t.' ],
		wgDigitTransformTable: [ '', '' ]
	} );
	this.parser( assert, 'currency', [
		[ '1.02 $', true, 102, '' ],
		[ '$ 3.00', true, 300, '' ],
		[ '€ 2,99', true, 2.99, '' ],
		[ '$ 1.00', true, 100, '' ],
		[ '$3.50', true, 350, '' ],
		[ '$ 1.50', true, 150, '' ],
		[ '€ 0.99', true, 99, '' ],
		[ '$ 299.99', true, 29999, '' ],
		[ '$ 2\'299,99', true, 2299.99, '' ],
		[ '$ 2,989', true, 2.989, '' ],
		[ '$ 2 299.99', true, 229999, '' ],
		[ '2 989 $', true, 2989, '' ],
		[ '299.99 $', true, 29999, '' ],
		[ '2\'299,99 $', true, 2299.99, '' ],
		[ '2,989 $', true, 2.989, '' ],
		[ '2 299.99 $', true, 229999, '' ],
		[ '2 989 $', true, 2989, '' ]
	] );
} );

// TODO add numbers sorting tests for T10115 with a different language
