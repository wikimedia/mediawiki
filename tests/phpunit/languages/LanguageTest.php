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

	/**
	 * @dataProvider provideFormattableTimes
	 */
	function testFormatTimePeriod( $seconds, $format, $expected, $desc ) {
		$this->assertEquals( $expected, $this->lang->formatTimePeriod( $seconds, $format ), $desc );
	}

	function provideFormattableTimes() {
		return array(
			array(
				9.45,
				array(),
				'9.5 s',
				'formatTimePeriod() rounding (<10s)'
			),
			array(
				9.45,
				array( 'noabbrevs' => true ),
				'9.5 seconds',
				'formatTimePeriod() rounding (<10s)'
			),
			array(
				9.95,
				array(),
				'10 s',
				'formatTimePeriod() rounding (<10s)'
			),
			array(
				9.95,
				array( 'noabbrevs' => true ),
				'10 seconds',
				'formatTimePeriod() rounding (<10s)'
			),
			array(
				59.55,
				array(),
				'1 min 0 s',
				'formatTimePeriod() rounding (<60s)'
			),
			array(
				59.55,
				array( 'noabbrevs' => true ),
				'1 minute 0 seconds',
				'formatTimePeriod() rounding (<60s)'
			),
			array(
				119.55,
				array(),
				'2 min 0 s',
				'formatTimePeriod() rounding (<1h)'
			),
			array(
				119.55,
				array( 'noabbrevs' => true ),
				'2 minutes 0 seconds',
				'formatTimePeriod() rounding (<1h)'
			),
			array(
				3599.55,
				array(),
				'1 h 0 min 0 s',
				'formatTimePeriod() rounding (<1h)'
			),
			array(
				3599.55,
				array( 'noabbrevs' => true ),
				'1 hour 0 minutes 0 seconds',
				'formatTimePeriod() rounding (<1h)'
			),
			array(
				7199.55,
				array(),
				'2 h 0 min 0 s',
				'formatTimePeriod() rounding (>=1h)'
			),
			array(
				7199.55,
				array( 'noabbrevs' => true ),
				'2 hours 0 minutes 0 seconds',
				'formatTimePeriod() rounding (>=1h)'
			),
			array(
				7199.55,
				'avoidseconds',
				'2 h 0 min',
				'formatTimePeriod() rounding (>=1h), avoidseconds'
			),
			array(
				7199.55,
				array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ),
				'2 hours 0 minutes',
				'formatTimePeriod() rounding (>=1h), avoidseconds'
			),
			array(
				7199.55,
				'avoidminutes',
				'2 h 0 min',
				'formatTimePeriod() rounding (>=1h), avoidminutes'
			),
			array(
				7199.55,
				array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ),
				'2 hours 0 minutes',
				'formatTimePeriod() rounding (>=1h), avoidminutes'
			),
			array(
				172799.55,
				'avoidseconds',
				'48 h 0 min',
				'formatTimePeriod() rounding (=48h), avoidseconds'
			),
			array(
				172799.55,
				array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ),
				'48 hours 0 minutes',
				'formatTimePeriod() rounding (=48h), avoidseconds'
			),
			array(
				259199.55,
				'avoidminutes',
				'3 d 0 h',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			),
			array(
				259199.55,
				array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ),
				'3 days 0 hours',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			),
			array(
				176399.55,
				'avoidseconds',
				'2 d 1 h 0 min',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			),
			array(
				176399.55,
				array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ),
				'2 days 1 hour 0 minutes',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			),
			array(
				176399.55,
				'avoidminutes',
				'2 d 1 h',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			),
			array(
				176399.55,
				array( 'avoid' => 'avoidminutes', 'noabbrevs' => true ),
				'2 days 1 hour',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			),
			array(
				259199.55,
				'avoidseconds',
				'3 d 0 h 0 min',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			),
			array(
				259199.55,
				array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ),
				'3 days 0 hours 0 minutes',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			),
			array(
				172801.55,
				'avoidseconds',
				'2 d 0 h 0 min',
				'formatTimePeriod() rounding, (>48h), avoidseconds'
			),
			array(
				172801.55,
				array( 'avoid' => 'avoidseconds', 'noabbrevs' => true ),
				'2 days 0 hours 0 minutes',
				'formatTimePeriod() rounding, (>48h), avoidseconds'
			),
			array(
				176460.55,
				array(),
				'2 d 1 h 1 min 1 s',
				'formatTimePeriod() rounding, recursion, (>48h)'
			),
			array(
				176460.55,
				array( 'noabbrevs' => true ),
				'2 days 1 hour 1 minute 1 second',
				'formatTimePeriod() rounding, recursion, (>48h)'
			),
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



	/**
	 * @dataProvider provideFormatDuration
	 */
	function testFormatDuration( $duration, $expected, $intervals = array() ) {
		$this->assertEquals(
			$expected,
			$this->lang->formatDuration( $duration, $intervals ),
			"formatDuration('$duration'): $expected"
		);
	}

	function provideFormatDuration() {
		return array(
			array(
				0,
				'0 seconds',
			),
			array(
				1,
				'1 second',
			),
			array(
				2,
				'2 seconds',
			),
			array(
				60,
				'1 minute',
			),
			array(
				2 * 60,
				'2 minutes',
			),
			array(
				3600,
				'1 hour',
			),
			array(
				2 * 3600,
				'2 hours',
			),
			array(
				24 * 3600,
				'1 day',
			),
			array(
				2 * 86400,
				'2 days',
			),
			array(
				365.25 * 86400, // 365.25 * 86400 = 31557600
				'1 year',
			),
			array(
				2 * 31557600,
				'2 years',
			),
			array(
				10 * 31557600,
				'1 decade',
			),
			array(
				20 * 31557600,
				'2 decades',
			),
			array(
				100 * 31557600,
				'1 century',
			),
			array(
				200 * 31557600,
				'2 centuries',
			),
			array(
				1000 * 31557600,
				'1 millennium',
			),
			array(
				2000 * 31557600,
				'2 millennia',
			),
			array(
				9001,
				'2 hours, 30 minutes and 1 second'
			),
			array(
				3601,
				'1 hour and 1 second'
			),
			array(
				31557600 + 2 * 86400 + 9000,
				'1 year, 2 days, 2 hours and 30 minutes'
			),
			array(
				42 * 1000 * 31557600 + 42,
				'42 millennia and 42 seconds'
			),
			array(
				60,
				'60 seconds',
				array( 'seconds' ),
			),
			array(
				61,
				'61 seconds',
				array( 'seconds' ),
			),
			array(
				1,
				'1 second',
				array( 'seconds' ),
			),
			array(
				31557600 + 2 * 86400 + 9000,
				'1 year, 2 days and 150 minutes',
				array( 'years', 'days', 'minutes' ),
			),
			array(
				42,
				'0 days',
				array( 'years', 'days' ),
			),
			array(
				31557600 + 2 * 86400 + 9000,
				'1 year, 2 days and 150 minutes',
				array( 'minutes', 'days', 'years' ),
			),
			array(
				42,
				'0 days',
				array( 'days', 'years' ),
			),
		);
	}

	/**
	 * @dataProvider provideCheckTitleEncodingData
	 */
	function testCheckTitleEncoding( $s ) {
		$this->assertEquals(
			$s,
			$this->lang->checkTitleEncoding($s),
			"checkTitleEncoding('$s')"
		);
	}

	function provideCheckTitleEncodingData() {
		return array (
			array( "" ),
			array( "United States of America" ), // 7bit ASCII
			array( rawurldecode( "S%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e" ) ),
			array(
				rawurldecode(
					"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn"
				)
			),
			// The following two data sets come from bug 36839. They fail if checkTitleEncoding uses a regexp to test for
			// valid UTF-8 encoding and the pcre.recursion_limit is low (like, say, 1024). They succeed if checkTitleEncoding
			// uses mb_check_encoding for its test.
			array(
				rawurldecode(
					"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn%7C"
						. "Catherine%20Willows%7CDavid%20Hodges%7CDavid%20Phillips%7CGil%20Grissom%7CGreg%20Sanders%7CHodges%7C"
						. "Internet%20Movie%20Database%7CJim%20Brass%7CLady%20Heather%7C"
						. "Les%20Experts%20(s%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e)%7CLes%20Experts%20:%20Manhattan%7C"
						. "Les%20Experts%20:%20Miami%7CListe%20des%20personnages%20des%20Experts%7C"
						. "Liste%20des%20%C3%A9pisodes%20des%20Experts%7CMod%C3%A8le%20discussion:Palette%20Les%20Experts%7C"
						. "Nick%20Stokes%7CPersonnage%20de%20fiction%7CPersonnage%20fictif%7CPersonnage%20de%20fiction%7C"
						. "Personnages%20r%C3%A9currents%20dans%20Les%20Experts%7CRaymond%20Langston%7CRiley%20Adams%7C"
						. "Saison%201%20des%20Experts%7CSaison%2010%20des%20Experts%7CSaison%2011%20des%20Experts%7C"
						. "Saison%2012%20des%20Experts%7CSaison%202%20des%20Experts%7CSaison%203%20des%20Experts%7C"
						. "Saison%204%20des%20Experts%7CSaison%205%20des%20Experts%7CSaison%206%20des%20Experts%7C"
						. "Saison%207%20des%20Experts%7CSaison%208%20des%20Experts%7CSaison%209%20des%20Experts%7C"
						. "Sara%20Sidle%7CSofia%20Curtis%7CS%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e%7CWallace%20Langham%7C"
						. "Warrick%20Brown%7CWendy%20Simms%7C%C3%89tats-Unis"
				),
			),
			array(
				rawurldecode(
					"Mod%C3%A8le%3AArrondissements%20homonymes%7CMod%C3%A8le%3ABandeau%20standard%20pour%20page%20d'homonymie%7C"
						. "Mod%C3%A8le%3ABatailles%20homonymes%7CMod%C3%A8le%3ACantons%20homonymes%7C"
						. "Mod%C3%A8le%3ACommunes%20fran%C3%A7aises%20homonymes%7CMod%C3%A8le%3AFilms%20homonymes%7C"
						. "Mod%C3%A8le%3AGouvernements%20homonymes%7CMod%C3%A8le%3AGuerres%20homonymes%7CMod%C3%A8le%3AHomonymie%7C"
						. "Mod%C3%A8le%3AHomonymie%20bateau%7CMod%C3%A8le%3AHomonymie%20d'%C3%A9tablissements%20scolaires%20ou"
						. "%20universitaires%7CMod%C3%A8le%3AHomonymie%20d'%C3%AEles%7CMod%C3%A8le%3AHomonymie%20de%20clubs%20sportifs%7C"
						. "Mod%C3%A8le%3AHomonymie%20de%20comt%C3%A9s%7CMod%C3%A8le%3AHomonymie%20de%20monument%7C"
						. "Mod%C3%A8le%3AHomonymie%20de%20nom%20romain%7CMod%C3%A8le%3AHomonymie%20de%20parti%20politique%7C"
						. "Mod%C3%A8le%3AHomonymie%20de%20route%7CMod%C3%A8le%3AHomonymie%20dynastique%7C"
						. "Mod%C3%A8le%3AHomonymie%20vid%C3%A9oludique%7CMod%C3%A8le%3AHomonymie%20%C3%A9difice%20religieux%7C"
						. "Mod%C3%A8le%3AInternationalisation%7CMod%C3%A8le%3AIsom%C3%A9rie%7CMod%C3%A8le%3AParonymie%7C"
						. "Mod%C3%A8le%3APatronyme%7CMod%C3%A8le%3APatronyme%20basque%7CMod%C3%A8le%3APatronyme%20italien%7C"
						. "Mod%C3%A8le%3APatronymie%7CMod%C3%A8le%3APersonnes%20homonymes%7CMod%C3%A8le%3ASaints%20homonymes%7C"
						. "Mod%C3%A8le%3ATitres%20homonymes%7CMod%C3%A8le%3AToponymie%7CMod%C3%A8le%3AUnit%C3%A9s%20homonymes%7C"
						. "Mod%C3%A8le%3AVilles%20homonymes%7CMod%C3%A8le%3A%C3%89difices%20religieux%20homonymes"
				)
			)
		);
	}

	/**
	 * @dataProvider provideRomanNumeralsData
	 */
	function testRomanNumerals( $num, $numerals ) {
		$this->assertEquals(
			$numerals,
			Language::romanNumeral( $num ),
			"romanNumeral('$num')"
		);
	}

	function provideRomanNumeralsData() {
		return array(
			array( 1, 'I' ),
			array( 2, 'II' ),
			array( 3, 'III' ),
			array( 4, 'IV' ),
			array( 5, 'V' ),
			array( 6, 'VI' ),
			array( 7, 'VII' ),
			array( 8, 'VIII' ),
			array( 9, 'IX' ),
			array( 10, 'X' ),
			array( 20, 'XX' ),
			array( 30, 'XXX' ),
			array( 40, 'XL' ),
			array( 49, 'XLIX' ),
			array( 50, 'L' ),
			array( 60, 'LX' ),
			array( 70, 'LXX' ),
			array( 80, 'LXXX' ),
			array( 90, 'XC' ),
			array( 99, 'XCIX' ),
			array( 100, 'C' ),
			array( 200, 'CC' ),
			array( 300, 'CCC' ),
			array( 400, 'CD' ),
			array( 500, 'D' ),
			array( 600, 'DC' ),
			array( 700, 'DCC' ),
			array( 800, 'DCCC' ),
			array( 900, 'CM' ),
			array( 999, 'CMXCIX' ),
			array( 1000, 'M' ),
			array( 1989, 'MCMLXXXIX' ),
			array( 2000, 'MM' ),
			array( 3000, 'MMM' ),
			array( 4000, 'MMMM' ),
			array( 5000, 'MMMMM' ),
			array( 6000, 'MMMMMM' ),
			array( 7000, 'MMMMMMM' ),
			array( 8000, 'MMMMMMMM' ),
			array( 9000, 'MMMMMMMMM' ),
			array( 9999, 'MMMMMMMMMCMXCIX'),
			array( 10000, 'MMMMMMMMMM' ),
		);
	}
}

