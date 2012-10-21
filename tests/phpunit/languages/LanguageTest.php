<?php

class LanguageTest extends MediaWikiTestCase {

	/**
	 * @var Language
	 */
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'en' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	function testLanguageConvertDoubleWidthToSingleWidth() {
		$this->assertEquals(
			"0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
			$this->lang->normalizeForSearch(
				"０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
			),
			'convertDoubleWidth() with the full alphabet and digits'
		);
	}

	/** @dataProvider provideFormattableTimes */
	function testFormatTimePeriod( $seconds, $format, $expected, $desc ) {
		$this->assertEquals( $expected, $this->lang->formatTimePeriod( $seconds, $format ), $desc );
	}

	function provideFormattableTimes() {
		return array(
			array( 9.45, array(), '9.5 s', 'formatTimePeriod() rounding (<10s)' ),
			array( 9.45, array( 'noabbrevs' => true ), '9.5 seconds', 'formatTimePeriod() rounding (<10s)' ),
			array( 9.95, array(), '10 s', 'formatTimePeriod() rounding (<10s)' ),
			array( 9.95, array( 'noabbrevs' => true ), '10 seconds', 'formatTimePeriod() rounding (<10s)' ),
			array( 59.55, array(), '1 min 0 s', 'formatTimePeriod() rounding (<60s)' ),
			array( 59.55, array( 'noabbrevs' => true ), '1 minute 0 seconds', 'formatTimePeriod() rounding (<60s)' ),
			array( 119.55, array(), '2 min 0 s', 'formatTimePeriod() rounding (<1h)' ),
			array( 119.55, array( 'noabbrevs' => true ), '2 minutes 0 seconds', 'formatTimePeriod() rounding (<1h)' ),
			array( 3599.55, array(), '1 h 0 min 0 s', 'formatTimePeriod() rounding (<1h)' ),
			array( 3599.55, array( 'noabbrevs' => true ), '1 hour 0 minutes 0 seconds', 'formatTimePeriod() rounding (<1h)' ),
			array( 7199.55, array(), '2 h 0 min 0 s', 'formatTimePeriod() rounding (>=1h)' ),
			array( 7199.55, array( 'noabbrevs' => true ), '2 hours 0 minutes 0 seconds', 'formatTimePeriod() rounding (>=1h)' ),
			array( 7199.55, 'avoidseconds', '2 h 0 min', 'formatTimePeriod() rounding (>=1h), avoidseconds' ),
			array( 7199.55, array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ), '2 hours 0 minutes', 'formatTimePeriod() rounding (>=1h), avoidseconds' ),
			array( 7199.55, 'avoidminutes', '2 h 0 min', 'formatTimePeriod() rounding (>=1h), avoidminutes' ),
			array( 7199.55, array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ), '2 hours 0 minutes', 'formatTimePeriod() rounding (>=1h), avoidminutes' ),
			array( 172799.55, 'avoidseconds', '48 h 0 min', 'formatTimePeriod() rounding (=48h), avoidseconds' ),
			array( 172799.55, array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ), '48 hours 0 minutes', 'formatTimePeriod() rounding (=48h), avoidseconds' ),
			array( 259199.55, 'avoidminutes', '3 d 0 h', 'formatTimePeriod() rounding (>48h), avoidminutes' ),
			array( 259199.55, array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ), '3 days 0 hours', 'formatTimePeriod() rounding (>48h), avoidminutes' ),
			array( 176399.55, 'avoidseconds', '2 d 1 h 0 min', 'formatTimePeriod() rounding (>48h), avoidseconds' ),
			array( 176399.55, array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ), '2 days 1 hour 0 minutes', 'formatTimePeriod() rounding (>48h), avoidseconds' ),
			array( 176399.55, 'avoidminutes', '2 d 1 h', 'formatTimePeriod() rounding (>48h), avoidminutes' ),
			array( 176399.55, array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ), '2 days 1 hour', 'formatTimePeriod() rounding (>48h), avoidminutes' ),
			array( 259199.55, 'avoidseconds', '3 d 0 h 0 min', 'formatTimePeriod() rounding (>48h), avoidseconds' ),
			array( 259199.55, array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ), '3 days 0 hours 0 minutes', 'formatTimePeriod() rounding (>48h), avoidseconds' ),
			array( 172801.55, 'avoidseconds', '2 d 0 h 0 min', 'formatTimePeriod() rounding, (>48h), avoidseconds' ),
			array( 172801.55, array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ), '2 days 0 hours 0 minutes', 'formatTimePeriod() rounding, (>48h), avoidseconds' ),
			array( 176460.55, array(), '2 d 1 h 1 min 1 s', 'formatTimePeriod() rounding, recursion, (>48h)' ),
			array( 176460.55, array( 'noabbrevs' => true ), '2 days 1 hour 1 minute 1 second', 'formatTimePeriod() rounding, recursion, (>48h)' ),
		);

	}

	function testTruncate() {
		$this->assertEquals(
			"XXX",
			$this->lang->truncate( "1234567890", 0, 'XXX' ),
			'truncate prefix, len 0, small ellipsis'
		);

		$this->assertEquals(
			"12345XXX",
			$this->lang->truncate( "1234567890", 8, 'XXX' ),
			'truncate prefix, small ellipsis'
		);

		$this->assertEquals(
			"123456789",
			$this->lang->truncate( "123456789", 5, 'XXXXXXXXXXXXXXX' ),
			'truncate prefix, large ellipsis'
		);

		$this->assertEquals(
			"XXX67890",
			$this->lang->truncate( "1234567890", -8, 'XXX' ),
			'truncate suffix, small ellipsis'
		);

		$this->assertEquals(
			"123456789",
			$this->lang->truncate( "123456789", -5, 'XXXXXXXXXXXXXXX' ),
			'truncate suffix, large ellipsis'
		);
	}

	/**
	* @dataProvider provideHTMLTruncateData()
	*/
	function testTruncateHtml( $len, $ellipsis, $input, $expected ) {
		// Actual HTML...
		$this->assertEquals(
			$expected,
			$this->lang->truncateHTML( $input, $len, $ellipsis )
		);
	}

	/**
	 * Array format is ($len, $ellipsis, $input, $expected)
	 */
	function provideHTMLTruncateData() {
		return array(
			array( 0, 'XXX', "1234567890", "XXX" ),
			array( 8, 'XXX', "1234567890", "12345XXX" ),
			array( 5, 'XXXXXXXXXXXXXXX', '1234567890', "1234567890" ),
			array( 2, '***',
				'<p><span style="font-weight:bold;"></span></p>',
				'<p><span style="font-weight:bold;"></span></p>',
			),
			array( 2, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			),
			array( 2, '***',
				'<p><span style="font-weight:bold;">&nbsp;23456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			),
			array( 3, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			),
			array( 4, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">1***</span></p>',
			),
			array( 5, '***',
				'<tt><span style="font-weight:bold;">123456789</span></tt>',
				'<tt><span style="font-weight:bold;">12***</span></tt>',
			),
			array( 6, '***',
				'<p><a href="www.mediawiki.org">123456789</a></p>',
				'<p><a href="www.mediawiki.org">123***</a></p>',
			),
			array( 6, '***',
				'<p><a href="www.mediawiki.org">12&nbsp;456789</a></p>',
				'<p><a href="www.mediawiki.org">12&nbsp;***</a></p>',
			),
			array( 7, '***',
				'<small><span style="font-weight:bold;">123<p id="#moo">456</p>789</span></small>',
				'<small><span style="font-weight:bold;">123<p id="#moo">4***</p></span></small>',
			),
			array( 8, '***',
				'<div><span style="font-weight:bold;">123<span>4</span>56789</span></div>',
				'<div><span style="font-weight:bold;">123<span>4</span>5***</span></div>',
			),
			array( 9, '***',
				'<p><table style="font-weight:bold;"><tr><td>123456789</td></tr></table></p>',
				'<p><table style="font-weight:bold;"><tr><td>123456789</td></tr></table></p>',
			),
			array( 10, '***',
				'<p><font style="font-weight:bold;">123456789</font></p>',
				'<p><font style="font-weight:bold;">123456789</font></p>',
			),
		);
	}

	/**
	 * Test Language::isValidBuiltInCode()
	 * @dataProvider provideLanguageCodes
	 */
	function testBuiltInCodeValidation( $code, $message = '' ) {
		$this->assertTrue(
			(bool) Language::isValidBuiltInCode( $code ),
			"validating code $code $message"
		);
	}

	function testBuiltInCodeValidationRejectUnderscore() {
		$this->assertFalse(
			(bool) Language::isValidBuiltInCode( 'be_tarask' ),
			"reject underscore in language code"
		);
	}

	function provideLanguageCodes() {
		return array(
			array( 'fr'       , 'Two letters, minor case' ),
			array( 'EN'       , 'Two letters, upper case' ),
			array( 'tyv'      , 'Three letters' ),
			array( 'tokipona'   , 'long language code' ),
			array( 'be-tarask', 'With dash' ),
			array( 'Zh-classical', 'Begin with upper case, dash' ),
			array( 'Be-x-old', 'With extension (two dashes)' ),
		);
	}

	/**
	 * @dataProvider provideSprintfDateSamples
	 */
	function testSprintfDate( $format, $ts, $expected, $msg ) {
		$this->assertEquals(
			$expected,
			$this->lang->sprintfDate( $format, $ts ),
			"sprintfDate('$format', '$ts'): $msg"
		);
	}
	/**
	 * bug 33454. sprintfDate should always use UTC.
	 * @dataProvider provideSprintfDateSamples
	 */
	function testSprintfDateTZ( $format, $ts, $expected, $msg ) {
		$oldTZ = date_default_timezone_get();
		$res = date_default_timezone_set( 'Asia/Seoul' );
		if ( !$res ) {
			$this->markTestSkipped( "Error setting Timezone" );
		}

		$this->assertEquals(
			$expected,
			$this->lang->sprintfDate( $format, $ts ),
			"sprintfDate('$format', '$ts'): $msg"
		);

		date_default_timezone_set( $oldTZ );
	}

	function provideSprintfDateSamples() {
		return array(
			array(
				'xiY',
				'20111212000000',
				'1390', // note because we're testing English locale we get Latin-standard digits
				'Iranian calendar full year'
			),
			array(
				'xiy',
				'20111212000000',
				'90',
				'Iranian calendar short year'
			),
			array(
				'o',
				'20120101235000',
				'2011',
				'ISO 8601 (week) year'
			),
			array(
				'W',
				'20120101235000',
				'52',
				'Week number'
			),
			array(
				'W',
				'20120102235000',
				'1',
				'Week number'
			),
			array(
				'o-\\WW-N',
				'20091231235000',
				'2009-W53-4',
				'leap week'
			),
			// What follows is mostly copied from http://www.mediawiki.org/wiki/Help:Extension:ParserFunctions#.23time
			array(
				'Y',
				'20120102090705',
				'2012',
				'Full year'
			),
			array(
				'y',
				'20120102090705',
				'12',
				'2 digit year'
			),
			array(
				'L',
				'20120102090705',
				'1',
				'Leap year'
			),
			array(
				'n',
				'20120102090705',
				'1',
				'Month index, not zero pad'
			),
			array(
				'N',
				'20120102090705',
				'01',
				'Month index. Zero pad'
			),
			array(
				'M',
				'20120102090705',
				'Jan',
				'Month abbrev'
			),
			array(
				'F',
				'20120102090705',
				'January',
				'Full month'
			),
			array(
				'xg',
				'20120102090705',
				'January',
				'Genitive month name (same in EN)'
			),
			array(
				'j',
				'20120102090705',
				'2',
				'Day of month (not zero pad)'
			),
			array(
				'd',
				'20120102090705',
				'02',
				'Day of month (zero-pad)'
			),
			array(
				'z',
				'20120102090705',
				'1',
				'Day of year (zero-indexed)'
			),
			array(
				'D',
				'20120102090705',
				'Mon',
				'Day of week (abbrev)'
			),
			array(
				'l',
				'20120102090705',
				'Monday',
				'Full day of week'
			),
			array(
				'N',
				'20120101090705',
				'7',
				'Day of week (Mon=1, Sun=7)'
			),
			array(
				'w',
				'20120101090705',
				'0',
				'Day of week (Sun=0, Sat=6)'
			),
			array(
				'N',
				'20120102090705',
				'1',
				'Day of week'
			),
			array(
				'a',
				'20120102090705',
				'am',
				'am vs pm'
			),
			array(
				'A',
				'20120102120000',
				'PM',
				'AM vs PM'
			),
			array(
				'a',
				'20120102000000',
				'am',
				'AM vs PM'
			),
			array(
				'g',
				'20120102090705',
				'9',
				'12 hour, not Zero'
			),
			array(
				'h',
				'20120102090705',
				'09',
				'12 hour, zero padded'
			),
			array(
				'G',
				'20120102090705',
				'9',
				'24 hour, not zero'
			),
			array(
				'H',
				'20120102090705',
				'09',
				'24 hour, zero'
			),
			array(
				'H',
				'20120102110705',
				'11',
				'24 hour, zero'
			),
			array(
				'i',
				'20120102090705',
				'07',
				'Minutes'
			),
			array(
				's',
				'20120102090705',
				'05',
				'seconds'
			),
			array(
				'U',
				'20120102090705',
				'1325495225',
				'unix time'
			),
			array(
				't',
				'20120102090705',
				'31',
				'Days in current month'
			),
			array(
				'c',
				'20120102090705',
				'2012-01-02T09:07:05+00:00',
				'ISO 8601 timestamp'
			),
			array(
				'r',
				'20120102090705',
				'Mon, 02 Jan 2012 09:07:05 +0000',
				'RFC 5322'
			),
			array(
				'xmj xmF xmn xmY',
				'20120102090705',
				'7 Safar 2 1433',
				'Islamic'
			),
			array(
				'xij xiF xin xiY',
				'20120102090705',
				'12 Dey 10 1390',
				'Iranian'
			),
			array(
				'xjj xjF xjn xjY',
				'20120102090705',
				'7 Tevet 4 5772',
				'Hebrew'
			),
			array(
				'xjt',
				'20120102090705',
				'29',
				'Hebrew number of days in month'
			),
			array(
				'xjx',
				'20120102090705',
				'Tevet',
				'Hebrew genitive month name (No difference in EN)'
			),
			array(
				'xkY',
				'20120102090705',
				'2555',
				'Thai year'
			),
			array(
				'xoY',
				'20120102090705',
				'101',
				'Minguo'
			),
			array(
				'xtY',
				'20120102090705',
				'平成24',
				'nengo'
			),
			array(
				'xrxkYY',
				'20120102090705',
				'MMDLV2012',
				'Roman numerals'
			),
			array(
				'xhxjYY',
				'20120102090705',
				'ה\'תשע"ב2012',
				'Hebrew numberals'
			),
			array(
				'xnY',
				'20120102090705',
				'2012',
				'Raw numerals (doesn\'t mean much in EN)'
			),
			array(
				'[[Y "(yea"\\r)]] \\"xx\\"',
				'20120102090705',
				'[[2012 (year)]] "x"',
				'Various escaping'
			),

		);
	}

	/**
	 * @dataProvider provideFormatSizes
	 */
	function testFormatSize( $size, $expected, $msg ) {
		$this->assertEquals(
			$expected,
			$this->lang->formatSize( $size ),
			"formatSize('$size'): $msg"
		);
	}

	function provideFormatSizes() {
		return array(
			array(
				0,
				"0 B",
				"Zero bytes"
			),
			array(
				1024,
				"1 KB",
				"1 kilobyte"
			),
			array(
				1024 * 1024,
				"1 MB",
				"1,024 megabytes"
			),
			array(
				1024 * 1024 * 1024,
				"1 GB",
				"1 gigabytes"
			),
			array(
				pow( 1024, 4 ),
				"1 TB",
				"1 terabyte"
			),
			array(
				pow( 1024, 5 ),
				"1 PB",
				"1 petabyte"
			),
			array(
				pow( 1024, 6 ),
				"1 EB",
				"1,024 exabyte"
			),
			array(
				pow( 1024, 7 ),
				"1 ZB",
				"1 zetabyte"
			),
			array(
				pow( 1024, 8 ),
				"1 YB",
				"1 yottabyte"
			),
			// How big!? THIS BIG!
		);
	}

	/**
	 * @dataProvider provideFormatBitrate
	 */
	function testFormatBitrate( $bps, $expected, $msg ) {
		$this->assertEquals(
			$expected,
			$this->lang->formatBitrate( $bps ),
			"formatBitrate('$bps'): $msg"
		);
	}

	function provideFormatBitrate() {
		return array(
			array(
				0,
				"0bps",
				"0 bits per second"
			),
			array(
				999,
				"999bps",
				"999 bits per second"
			),
			array(
				1000,
				"1kbps",
				"1 kilobit per second"
			),
			array(
				1000 * 1000,
				"1Mbps",
				"1 megabit per second"
			),
			array(
				pow( 10, 9 ),
				"1Gbps",
				"1 gigabit per second"
			),
			array(
				pow( 10, 12 ),
				"1Tbps",
				"1 terabit per second"
			),
			array(
				pow( 10, 15 ),
				"1Pbps",
				"1 petabit per second"
			),
			array(
				pow( 10, 18 ),
				"1Ebps",
				"1 exabit per second"
			),
			array(
				pow( 10, 21 ),
				"1Zbps",
				"1 zetabit per second"
			),
			array(
				pow( 10, 24 ),
				"1Ybps",
				"1 yottabit per second"
			),
			array(
				pow( 10, 27 ),
				"1,000Ybps",
				"1,000 yottabits per second"
			),
		);
	}
}
