( function ( $, mw ) {
	/**
	 * This module tests the input/output capabilities of the parsers of tablesorter.
	 * It does not test actual sorting.
	 */

	var text, ipv4,
		simpleMDYDatesInMDY, DMYDates, YMDDates, BCDates,
		complexMDYDates, clobberedDates, MYDates, YDates, ISODates,
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
				genitive: [ 'January', 'February', 'March', 'd\'abril', 'במאי', 'June',
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
			wgDefaultDateFormat: 'mdy',
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
	 *		inputValue: The string value that we want to test the parser for
	 *		recognized: If we expect that this value's type is detectable by the parser
	 *		outputValue: The value the parser has converted the input to
	 *		msg: describing the testcase
	 * @param {function($table)} callback something to do before we start the testcase
	 */
	function parserTest( msg, parserId, data, callback ) {
		QUnit.test( msg, function ( assert ) {
			var extractedR, extractedF, parser;

			if ( callback !== undefined ) {
				callback();
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
		[ 'Mars', true, 'mars', 'Simple text' ],
		[ 'Mẘas', true, 'mẘas', 'Non ascii character' ],
		[ 'A sentence', true, 'a sentence', 'A sentence with space chars' ]
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
		[ '45.238.27.109 postfix', false, 45238027109, 'An IP address with a seperated postfix' ]
	];
	parserTest( 'IPv4', 'IPAddress', ipv4 );

	/* eslint-disable no-multi-spaces */
	simpleMDYDatesInMDY = [
		[ 'January 7, 2010',	true, 20100107, 'Long middle endian date' ],
		[ 'Jan. 7, 2010',	true, 20100107, 'Short middle endian date with .' ],
		[ '1/7/10',		true, 20100107, 'Numeric middle endian date' ],
		[ '01/7/2010',		true, 20100107, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010',		true, 20100107, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010',		true, 20100107, 'Numeric middle endian date with padding on year' ],
		[ '1.7.2010',		true, 20100107, 'Numeric middle endian date with . separator' ]
	];
	parserTest( 'MDY Dates using mdy content language', 'date', simpleMDYDatesInMDY );

	DMYDates = [
		[ 'January 7, 2010',	true, 20100107, 'Long middle endian date' ],
		[ 'Jan. 7, 2010',	true, 20100107, 'Short middle endian date with .' ],
		// DMY
		[ '1/7/10',		true, 20100701, 'Numeric middle endian date' ],
		[ '01/7/2010',		true, 20100701, 'Numeric middle endian date with padding on month' ],
		[ '01/07/2010',		true, 20100701, 'Numeric middle endian date with padding on day' ],
		[ '01/07/0010',		true, 20100701, 'Numeric middle endian date with padding on year' ],
		[ '1.7.2010',		true, 20100701, 'Numeric middle endian date with . separator' ],
		[ 'pre1/7/10pos',	true, 20100701, 'Numeric dmy: Pre- and postfix' ],
		[ '01. Jul. 2010',	true, 20100701, 'Named dmy' ],
		[ '01 במאי 2010',	true, 20100501, 'Month with non latin characters (he)' ],
		[ '~1 Jul 10pos',	true, 20100701, 'Named dmy: Pre- and postfix' ],
		[ '1. July',		true,      701, 'Named dm' ],
		[ 'pre1. July pos',	true,      701, 'Named dm: Pre- and postfix' ],
		[ '12 2010',		false, 20101200, 'Numeric month year' ]
	];
	parserTest( 'DMY Dates and MDY Dates using dmy content language', 'date', DMYDates, function () {
		mw.config.set( {
			wgDefaultDateFormat: 'dmy',
			wgPageContentLanguage: 'de'
		} );
	} );

	YMDDates = [
		[ '2010. Jan. 7',	true,  20100107, 'Named ymd date' ],
		[ '~2010 Jan. 7pos',	true,  20100107, 'Named ymd: Pre- and postfix' ],
		[ '2010.1.7',		true,  20100107, 'Numeric ymd date' ],
		[ 'pre2010.1.7pos',	true,  20100107, 'Numeric ymd: Pre- and postfix' ],
		[ '2010 Dec',		true,  20101200, 'Year named month' ],
		[ '~2010 Dec pos',	true,  20101200, 'Named ym: Pre- and postfix' ],
		[ '2010 12',		false, 20101200, 'Numeric ym date' ],
		[ 'pre2010 12pos',	false, 20101200, 'Numeric ym: Pre- and postfix' ]
	];
	parserTest( 'YMD Dates', 'date', YMDDates, function () {
		mw.config.set( {
			wgDefaultDateFormat: 'ymd',
			wgPageContentLanguage: 'hu'
		} );
	} );

	complexMDYDates = [
		[ 'January, 19 2010',	true, 20100119, 'Comma after month' ],
		[ 'January 19, 2010',	true, 20100119, 'Comma after day' ],
		[ 'January/19/2010',	true, 20100119, 'Forward slash separator' ],
		[ '04 22 1991',		true, 19910422, 'Month with 0 padding' ],
		[ 'April 22 1991',	true, 19910422, 'Space separation mdy' ],
		[ 'd\'abril 22 1991',	true, 19910422, 'Month name with \' (language br, ca)' ],
		[ 'במאי 22 1991',	true, 19910522, 'Month name with non latin characters (he)' ],
		[ '22 April 1991',	true, 19910422, 'Space separation dmy' ],
		[ 'Dec-6-10',		true, 20101206, 'Separator: -' ],
		[ 'Dec.\xa06\n10',	true, 20101206, 'Separator: &nbsp; and <br>' ],
		[ 'pre Jan 7, 2010pos',	true, 20100107, 'Named mdy: Pre- and postfix' ],
		[ 'pre1/7/2010pos',	true, 20100107, 'Numeric mdy: Pre- and postfix' ],
		[ '12 6 29',		true, 20291206, 'Year < 30' ],
		[ '12 6 30',		true, 19301206, 'Year ≥ 30' ],
		[ 'Dec 31',		true,     1231, 'Month day' ],
		[ 'pre Dec 31pos',	true,     1231, 'md: Pre- and postfix' ]
	];
	parserTest( 'MDY Dates', 'date', complexMDYDates );

	clobberedDates = [
		[ 'January, 19 2010 - January, 20 2010',	true, 20100119, 'Date range with hyphen' ],
		[ 'January, 19 2010 — January, 20 2010',	true, 20100119, 'Date range with mdash' ],
		[ 'pre January, 19 2010',	true, 20100119, 'Prefix' ],
		[ 'January 19 20100',		true, 20100119, 'Year > 4 digits' ],
		[ 'January 19 2010pos',		true, 20100119, 'ConnectedPostfix' ],
		[ 'January 19 2010 pos',	true, 20100119, 'Postfix' ],
		[ 'pre19 Jan 2010pos',		true, 20100119, 'dmy: Pre- and postfix ' ],
		[ 'pre1 19 2010pos',		true, 20100119, 'Only digits: Pre- and postfix' ],
		[ 'January',				true,      100, 'Only month' ],
		// false written dates
		[ 'A simple text',		false, '99999999', 'Plain text in date sort' ],
		[ '04l22l1991',			false,    40000, 'l char as separator' ],
		[ 'January\\19\\2010',	false,   190000, 'Backslash as date separator' ],
		[ 'December 6 \'10',	true,      1206, 'Separator: \'' ],
		[ 'Ajan, 19 2010',		false, 20101900, 'Month in words 1' ],
		[ 'Jana, 19 2010',		false, 20101900, 'Month in words 2' ],
		[ 'May19 2010',			false, 20101900, 'Missing space' ]
	];
	parserTest( 'Clobbered Dates', 'date', clobberedDates );

	MYDates = [
		[ 'December 2010',	true, 20101200, 'Named month year' ],
		[ 'Dec 2010',		true, 20101200, 'Abreviated month year' ],
		[ 'December 32',	true,   321200, 'Named month year > 31' ],
		[ 'December 31',	true,     1231, 'Plain month year ≤ 31 is month day' ],
		[ '~ Dec 2010pos',	true, 20101200, 'Named my: Pre- and postfix' ],
		[ '12 2010',		false, 20101200, 'Numeric month year' ],
		[ 'pre12 2010pos',	false, 20101200, 'Numeric my: Pre- and postfix' ]
	];
	parserTest( 'MY Dates', 'date', MYDates );

	YDates = [
		[ '20100',		false, 201000000, 'Year > 4 digits' ],
		[ '2010',		false,  20100000, '4-digit year' ],
		[ '876',		false,   8760000, '3-digit year' ],
		[ '76',			false,    760000, '2-digit year' ],
		[ '\'76',		false,    760000, '2-digit millenium bug year' ],
		[ 'pre10post',	false,    100000, 'Pre- and postfix' ]
	];
	parserTest( 'Y Dates', 'date', YDates );

	BCDates = [
		[ 'January 19, 1 BC',		true,      -9881, 'BC' ],
		[ 'January 19, 1400BC',		true,  -13999881, 'Connected BC' ],
		[ 'January 19, 1400 BCEra',	true,  -13999881, 'Extended BC' ],
		[ 'January, 19 1400 B.C.',	true,  -13999881, 'mdy B.C.' ],
		[ 'January, 19 1400 B.-C.',	true,  -13999881, 'mdy B.-C.' ],
		[ '19. January 1400 b. c.',	true,  -13999881, 'dmy b. c.' ],
		[ 'Jan., 19 1400 B.\xa0C.',	true,  -13999881, 'mdy B.&nbsp;C.' ],
		[ 'January 14 BC',			true,    -139900, 'Named month year' ],
		[ '01/19/1400 BC',			true,  -13999881, 'Only digits BC' ],
		[ '01/19/14 BC',			true,    -139881, 'Only digits BC, 2 digit year' ],
		[ '20100 BC',				false, -201000000, 'Only year, > 4 digits' ],
		[ '1400 BC - 1300 BC',		false, -14000000, 'Date range BC' ],
		[ '1400—1300 BC',			false, -14000000, 'Date range BC abbreviated' ],
		[ '1400 to 1300 BC',		false, -14000000, 'Date range BC abbreviated with words' ],
		[ '1400 BC',				false, -14000000, 'Only year, 4 digit' ],
		[ '14BC',					false,   -140000, 'Only year, 2 digit, connected BC' ],
		[ '1 BC',					false,    -10000, 'Only year, 1 digit' ],
		[ 'BC January 14',			true,    -139900, 'BC before named month year' ],
		[ 'BC 01/19/1400',			true,  -13999881, 'BC before only digits' ],
		[ 'BC 1400 — 1300',			false, -14000000, 'BC before date range' ],
		[ 'BC 1400',				false, -14000000, 'BC before year, 4 digit' ],
		[ 'BC14',					false,   -140000, 'Connected BC before year, 2 digit' ],
		[ 'B.c.öň 1',				false,    -10000, 'Extended BC before year, 1 digit (tk)' ],
		[ 'B. C. 1',				false,    -10000, 'B. C. before year, 1 digit (hu)' ],
		[ 'B.C.ա. 1',				false,    -10000, 'B.C.ա. before year, 1 digit (hy)' ],
		[ '-1400',					false, -14000000, 'Negative year, 4 digit (eo, oc, gl)' ],
		[ '-1',						false,    -10000, 'Negative year, 1 digit' ],
		[ '\u22121',				false,    -10000, 'Negative year, 1 digit (minus sign \u2212)' ],
		[ '1',						false,     10000, 'Year, 1 digit' ],
		[ '1400',					false,  14000000, 'Year, 4 digit' ],
		[ '1400 AD',				false,	14000000, 'AD or other postfix' ],
		[ 'January 32 AD',			true,	  320100, 'AD written month year 32' ],
		[ 'January 19 100 AD',		true,	 1000119, 'AD with day, year ≥ 100, correct' ],
		// false parsed date
		[ 'January 14 AD',			true,	     114, 'AD written month (year 14), is day 14' ],
		[ 'January 19 1 AD',		true,	20010119, 'AD with day, year < 30, 1 is 2001' ],
		[ '19 January 30 AD',		true,	19300119, 'AD with day, year ≥ 30, 30 is 1930' ]
	];
	parserTest( 'BC dates with 2 chars (B.C.)', 'date', BCDates, function () {
		mw.language.setData( mw.config.get( 'wgPageContentLanguage' ), 'yearBC', 'B.C.' );
	} );

	BCDates = [
		[ 'January 19, 1400 avJC',		true, -13999881, 'avJC (et, fi)' ],
		[ 'January 19, 1400avJC',		true, -13999881, 'Connected avJC' ],
		[ 'January 19, 1400 av J Chr',	true, -13999881, 'Extended avJC' ],
		[ 'January, 19 1400 av.J.C.',	true, -13999881, 'mdy av.J.C.' ],
		[ 'January, 19 1400 av. J.-C.',	true, -13999881, 'mdy av. J.-C. (fr, bg, lt)' ],
		[ '19. January 1400 av. J. C.',	true, -13999881, 'dmy av. J. C. (bs)' ]
	];
	parserTest( 'BC dates with 4 chars (av.J.C.)', 'date', BCDates, function () {
		mw.language.setData( mw.config.get( 'wgPageContentLanguage' ), 'yearBC', 'av.J.C.' );
	} );

	BCDates = [
		[ 'January 19, 1400 до н. э.',	true, -13999881, 'до н. э. (ru)' ]
	];
	parserTest( 'BC dates with non latin chars (до н. э.)', 'date', BCDates, function () {
		mw.language.setData( mw.config.get( 'wgPageContentLanguage' ), 'yearBC', 'до н. э.' );
	} );

	BCDates = [
		[ 'January 19, 1400 ईसा पूर्व',	true, -13999881, 'ईसा पूर्व (hi)' ]
	];
	parserTest( 'BC dates with non latin chars (ईसा पूर्व)', 'date', BCDates, function () {
		mw.language.setData( mw.config.get( 'wgPageContentLanguage' ), 'yearBC', 'ईसा पूर्व' );
	} );
	/* eslint-enable no-multi-spaces */

	ISODates = [
		[ '',		false,	-Infinity, 'Not a date' ],
		[ '2000',	false,	946684800000, 'Plain 4-digit year' ],
		[ '2000-01',	true,	946684800000, 'Year with month' ],
		[ '2000-01-01',	true,	946684800000, 'Year with month and day' ],
		[ '2000-13-01',	false,	978307200000, 'Non existant month' ],
		[ '2000-01-32',	true,	949363200000, 'Non existant day' ],
		[ '2000-01-01T12:30:30',	true, 946729830000, 'Date with a time' ],
		[ '2000-01-01T12:30:30Z',	true, 946729830000, 'Date with a UTC+0 time' ],
		[ '2000-01-01T24:30:30Z',	true, 946773030000, 'Date with invalid hours' ],
		[ '2000-01-01T12:60:30Z',	true, 946728000000, 'Date with invalid minutes' ],
		[ '2000-01-01T12:30:61Z',	true, 946729800000, 'Date with invalid amount of seconds, drops seconds' ],
		[ '2000-01-01T23:59:59Z',	true, 946771199000, 'Edges of time' ],
		[ '2000-01-01T12:30:30.111Z',	true, 946729830111, 'Date with milliseconds' ],
		[ '2000-01-01T12:30:30.11111Z',	true, 946729830111, 'Date with too high precision' ],
		[ '2000-01-01T12:30:30,111Z',	true, 946729830111, 'Date with milliseconds and , separator' ],
		[ '2000-01-01T12:30:30+01:00',	true, 946726230000, 'Date time in UTC+1' ],
		[ '2000-01-01T12:30:30+01:30',	true, 946724430000, 'Date time in UTC+1:30' ],
		[ '2000-01-01T12:30:30-01:00',	true, 946733430000, 'Date time in UTC-1' ],
		[ '2000-01-01T12:30:30-01:30',	true, 946735230000, 'Date time in UTC-1:30' ],
		[ '2000-01-01T12:30:30.111+01:00', true, 946726230111, 'Date time and milliseconds in UTC+1' ],
		[ '2000-01-01Postfix', true, 946684800000, 'Date with appended postfix' ],
		[ '2000-01-01 Postfix', true, 946684800000, 'Date with separate postfix' ],
		[ '2 Postfix',	false, -62104060800000, 'One digit with separate postfix' ],
		[ 'ca. 2',		false, -62104060800000, 'Three digit with separate prefix' ],
		[ '~200',		false, -55855785600000, 'Three digit with appended prefix' ],
		[ 'ca. 200[1]',	false, -55855785600000, 'Three digit with separate prefix and postfix' ],
		[ '2000-11-31',	true,	975628800000, '31 days in 30 day month' ],
		[ '50-01-01',	true,	-60589296000000, 'Year with just two digits' ],
		[ '2',			false,	-62104060800000, 'Year with one digit' ],
		[ '02-01',		true,	-62104060800000, 'Year with one digit and leading zero' ],
		[ ' 2-01',		true,	-62104060800000, 'Year with one digit and leading space' ],
		[ '-2-10',		true,	-62206704000000, 'Year BC with month' ],
		[ '-9999',		false,	-377705116800000, 'max. Year BC' ],
		[ '+9999-12',	true,	253399622400000, 'max. Date with +sign' ],
		[ '2000-01-01 12:30:30Z',	true, 946729830000, 'Date and time with no T marker' ],
		[ '2000-01-01T12:30:60Z',	true, 946729860000, 'Date with leap second' ],
		[ '2000-01-01T12:30:30-23:59',	true, 946816170000, 'Date time in UTC-23:59' ],
		[ '2000-01-01T12:30:30+23:59',	true, 946643490000, 'Date time in UTC+23:59' ],
		[ '2000-01-01T123030+0100',	true,	946726230000, 'Time without separators' ],
		[ '20000101T123030+0100',	false,	946726230000, 'All without separators' ]
	];
	parserTest( 'ISO Dates', 'isoDate', ISODates );

	currencyData = [
		[ '1.02 $',	true, 1.02, '' ],
		[ '$ 3.00',	true, 3, '' ],
		[ '€ 2,99',	true, 299, '' ],
		[ '$ 1.00',	true, 1, '' ],
		[ '$3.50',	true, 3.50, '' ],
		[ '$ 1.50',	true, 1.50, '' ],
		[ '€ 0.99',	true, 0.99, '' ],
		[ '$ 299.99',	true, 299.99, '' ],
		[ '$ 2,299.99',	true, 2299.99, '' ],
		[ '$ 2,989',	true, 2989, '' ],
		[ '$ 2 299.99',	true, 2299.99, '' ],
		[ '$ 2 989',	true, 2989, '' ],
		[ '$ 2.989',	true, 2.989, '' ]
	];
	parserTest( 'Currency', 'currency', currencyData );

	transformedCurrencyData = [
		[ '1.02 $',	true, 102, '' ],
		[ '$ 3.00',	true, 300, '' ],
		[ '€ 2,99',	true, 2.99, '' ],
		[ '$ 1.00',	true, 100, '' ],
		[ '$3.50',	true, 350, '' ],
		[ '$ 1.50',	true, 150, '' ],
		[ '€ 0.99',	true, 99, '' ],
		[ '$ 299.99',	true, 29999, '' ],
		[ '$ 2\'299,99',	true, 2299.99, '' ],
		[ '$ 2,989',	true, 2.989, '' ],
		[ '$ 2 299.99',	true, 229999, '' ],
		[ '2 989 $',	true, 2989, '' ],
		[ '299.99 $',	true, 29999, '' ],
		[ '2\'299,99 $',	true, 2299.99, '' ],
		[ '2,989 $',	true, 2.989, '' ],
		[ '2 299.99 $',	true, 229999, '' ],
		[ '2 989 $',	true, 2989, '' ]
	];
	parserTest( 'Currency with european separators', 'currency', transformedCurrencyData, function () {
		mw.config.set( {
			// We expect 22'234.444,22
			// Map from ascii separators => localized separators
			wgSeparatorTransformTable: [ ',	.	,', '\'	,	.' ],
			wgDigitTransformTable: [ '', '' ]
		} );
	} );

	// TODO add numbers sorting tests for T10115 with a different language

}( jQuery, mediaWiki ) );
