( function () {
	/**
	 * This module tests the input/output capabilities of the parsers of tablesorter.
	 * It does not test actual sorting.
	 */

	var text, ipv4,
		simpleMDYDatesInMDY, simpleMDYDatesInDMY, oldMDYDates, complexMDYDates, clobberedDates, MYDates, YDates, ISODates,
		currencyData, transformedCurrencyData;

	QUnit.module( 'jquery.tablesorter.parsers', QUnit.newMwEnvironment( {
		setup: function () {
			this.liveMonths = mw.language.months;
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
		},
		teardown: function () {
			mw.language.months = this.liveMonths;
		},
		config: {
			wgPageContentLanguage: 'en',
			/* default date format of the content language */
			wgDefaultDateFormat: 'dmy',
			/* These two are important for numeric interpretations */
			wgSeparatorTransformTable: [ '', '' ],
			wgDigitTransformTable: [ '', '' ]
		}
	} ) );

	/**
	 * For a value, check if the parser recognizes it and how it transforms it
	 *
	 * @param {string} msg text to pass on to qunit describing the test case
	 * @param {string[]} parserId of the parser that will be tested
	 * @param {string[][]} data Array of testcases. Each testcase, array of
	 *      inputValue: The string value that we want to test the parser for
	 *      recognized: If we expect that this value's type is detectable by the parser
	 *      outputValue: The value the parser has converted the input to
	 *      msg: describing the testcase
	 * @param {Function} beforeTest Something to do before we start the testcase
	 */
	function parserTest( msg, parserId, data, beforeTest ) {
		QUnit.test( msg, function ( assert ) {
			var extractedR, extractedF, parser;

			if ( beforeTest !== undefined ) {
				beforeTest();
			}

			parser = $.tablesorter.getParser( parserId );
			data.forEach( function ( testcase ) {
				extractedR = parser.is( testcase[ 0 ] );
				extractedF = parser.format( testcase[ 0 ] );

				assert.strictEqual( extractedR, testcase[ 1 ], 'Detect: ' + testcase[ 3 ] );
				assert.strictEqual( extractedF, testcase[ 2 ], 'Sortkey: ' + testcase[ 3 ] );
			} );

		} );
	}

	text = [
		[ 'Mars', true, 'Mars', 'Simple text' ],
		[ 'Mẘas', true, 'Mẘas', 'Non ascii character' ],
		[ 'A sentence', true, 'A sentence', 'A sentence with space chars' ]
	];
	parserTest( 'Textual keys', 'text', text );

	ipv4 = [
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
	];
	parserTest( 'IPv4', 'IPAddress', ipv4 );

	simpleMDYDatesInMDY = [
		[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
		[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
		[ '1/17/2010', true, 20100117, 'Numeric middle endian date' ],
		[ '01/17/2010', true, 20100117, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010', true, 20100107, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010', true, 20100107, 'Numeric middle endian date with padding on year' ],
		[ '5.12.1990', true, 19900512, 'Numeric middle endian date with . separator' ]
	];
	parserTest( 'MDY Dates using mdy content language', 'date', simpleMDYDatesInMDY );

	simpleMDYDatesInDMY = [
		[ 'January 17, 2010', true, 20100117, 'Long middle endian date' ],
		[ 'Jan 17, 2010', true, 20100117, 'Short middle endian date' ],
		[ '1/17/2010', true, 20101701, 'Numeric middle endian date' ],
		[ '01/17/2010', true, 20101701, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010', true, 20100701, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010', true, 20100701, 'Numeric middle endian date with padding on year' ],
		[ '5.12.1990', true, 19901205, 'Numeric middle endian date with . separator' ]
	];
	parserTest( 'MDY Dates using dmy content language', 'date', simpleMDYDatesInDMY, function () {
		mw.config.set( {
			wgDefaultDateFormat: 'dmy',
			wgPageContentLanguage: 'de'
		} );
	} );

	oldMDYDates = [
		[ 'January 19, 1400 BC', false, '99999999', 'BC' ],
		[ 'January 19, 1400BC', false, '99999999', 'Connected BC' ],
		[ 'January, 19 1400 B.C.', false, '99999999', 'B.C.' ],
		[ 'January 19, 1400 AD', false, '99999999', 'AD' ],
		[ 'January, 19 10', true, 20100119, 'AD' ],
		[ 'January, 19 1', false, '99999999', 'AD' ]
	];
	parserTest( 'Very old MDY dates', 'date', oldMDYDates );

	complexMDYDates = [
		[ 'January, 19 2010', true, 20100119, 'Comma after month' ],
		[ 'January 19, 2010', true, 20100119, 'Comma after day' ],
		[ 'January/19/2010', true, 20100119, 'Forward slash separator' ],
		[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
		[ 'April 21 1991', true, 19910421, 'Space separation' ],
		[ '04 22 1991', true, 19910422, 'Month with 0 padding' ],
		[ 'December 12 \'10', true, 20101212, '' ],
		[ 'Dec 12 \'10', true, 20101212, '' ],
		[ 'Dec. 12 \'10', true, 20101212, '' ]
	];
	parserTest( 'MDY Dates', 'date', complexMDYDates );

	clobberedDates = [
		[ 'January, 19 2010 - January, 20 2010', false, '99999999', 'Date range with hyphen' ],
		[ 'January, 19 2010 — January, 20 2010', false, '99999999', 'Date range with mdash' ],
		[ 'prefixJanuary, 19 2010', false, '99999999', 'Connected prefix' ],
		[ 'prefix January, 19 2010', false, '99999999', 'Prefix' ],
		[ 'December 12 2010postfix', false, '99999999', 'ConnectedPostfix' ],
		[ 'December 12 2010 postfix', false, '99999999', 'Postfix' ],
		[ 'A simple text', false, '99999999', 'Plain text in date sort' ],
		[ '04l22l1991', false, '99999999', 'l char as separator' ],
		[ 'January\\19\\2010', false, '99999999', 'backslash as date separator' ]
	];
	parserTest( 'Clobbered Dates', 'date', clobberedDates );

	MYDates = [
		[ 'December 2010', false, '99999999', 'Plain month year' ],
		[ 'Dec 2010', false, '99999999', 'Abreviated month year' ],
		[ '12 2010', false, '99999999', 'Numeric month year' ]
	];
	parserTest( 'MY Dates', 'date', MYDates );

	YDates = [
		[ '2010', false, '99999999', 'Plain 4-digit year' ],
		[ '876', false, '99999999', '3-digit year' ],
		[ '76', false, '99999999', '2-digit year' ],
		[ '\'76', false, '99999999', '2-digit millenium bug year' ],
		[ '2010 BC', false, '99999999', '4-digit year BC' ]
	];
	parserTest( 'Y Dates', 'date', YDates );

	ISODates = [
		[ '', false, -Infinity, 'Not a date' ],
		[ '2000', false, 946684800000, 'Plain 4-digit year' ],
		[ '2000-01', true, 946684800000, 'Year with month' ],
		[ '2000-01-01', true, 946684800000, 'Year with month and day' ],
		[ '2000-13-01', false, 978307200000, 'Non existant month' ],
		[ '2000-01-32', true, 949363200000, 'Non existant day' ],
		[ '2000-01-01T12:30:30', true, 946729830000, 'Date with a time' ],
		[ '2000-01-01T12:30:30Z', true, 946729830000, 'Date with a UTC+0 time' ],
		[ '2000-01-01T24:30:30Z', true, 946773030000, 'Date with invalid hours' ],
		[ '2000-01-01T12:60:30Z', true, 946728000000, 'Date with invalid minutes' ],
		[ '2000-01-01T12:30:61Z', true, 946729800000, 'Date with invalid amount of seconds, drops seconds' ],
		[ '2000-01-01T23:59:59Z', true, 946771199000, 'Edges of time' ],
		[ '2000-01-01T12:30:30.111Z', true, 946729830111, 'Date with milliseconds' ],
		[ '2000-01-01T12:30:30.11111Z', true, 946729830111, 'Date with too high precision' ],
		[ '2000-01-01T12:30:30,111Z', true, 946729830111, 'Date with milliseconds and , separator' ],
		[ '2000-01-01T12:30:30+01:00', true, 946726230000, 'Date time in UTC+1' ],
		[ '2000-01-01T12:30:30+01:30', true, 946724430000, 'Date time in UTC+1:30' ],
		[ '2000-01-01T12:30:30-01:00', true, 946733430000, 'Date time in UTC-1' ],
		[ '2000-01-01T12:30:30-01:30', true, 946735230000, 'Date time in UTC-1:30' ],
		[ '2000-01-01T12:30:30.111+01:00', true, 946726230111, 'Date time and milliseconds in UTC+1' ],
		[ '2000-01-01Postfix', true, 946684800000, 'Date with appended postfix' ],
		[ '2000-01-01 Postfix', true, 946684800000, 'Date with separate postfix' ],
		[ '2 Postfix', false, -62104060800000, 'One digit with separate postfix' ],
		[ 'ca. 2', false, -62104060800000, 'Three digit with separate prefix' ],
		[ '~200', false, -55855785600000, 'Three digit with appended prefix' ],
		[ 'ca. 200[1]', false, -55855785600000, 'Three digit with separate prefix and postfix' ],
		[ '2000-11-31', true, 975628800000, '31 days in 30 day month' ],
		[ '50-01-01', true, -60589296000000, 'Year with just two digits' ],
		[ '2', false, -62104060800000, 'Year with one digit' ],
		[ '02-01', true, -62104060800000, 'Year with one digit and leading zero' ],
		[ ' 2-01', true, -62104060800000, 'Year with one digit and leading space' ],
		[ '-2-10', true, -62206704000000, 'Year BC with month' ],
		[ '-9999', false, -377705116800000, 'max. Year BC' ],
		[ '+9999-12', true, 253399622400000, 'max. Date with +sign' ],
		[ '2000-01-01 12:30:30Z', true, 946729830000, 'Date and time with no T marker' ],
		[ '2000-01-01T12:30:60Z', true, 946729860000, 'Date with leap second' ],
		[ '2000-01-01T12:30:30-23:59', true, 946816170000, 'Date time in UTC-23:59' ],
		[ '2000-01-01T12:30:30+23:59', true, 946643490000, 'Date time in UTC+23:59' ],
		[ '2000-01-01T123030+0100', true, 946726230000, 'Time without separators' ],
		[ '20000101T123030+0100', false, 946726230000, 'All without separators' ]
	];
	parserTest( 'ISO Dates', 'isoDate', ISODates );

	currencyData = [
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
	];
	parserTest( 'Currency', 'currency', currencyData );

	transformedCurrencyData = [
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
	];
	parserTest( 'Currency with european separators', 'currency', transformedCurrencyData, function () {
		mw.config.set( {
			// We expect 22'234.444,22
			// Map from ascii separators => localized separators
			wgSeparatorTransformTable: [ ',\t.\t,', '\'\t,\t.' ],
			wgDigitTransformTable: [ '', '' ]
		} );
	} );

	// TODO add numbers sorting tests for T10115 with a different language

}() );
