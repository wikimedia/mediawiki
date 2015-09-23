<?php

class LanguageTest extends LanguageClassesTestCase {
	/**
	 * @covers Language::convertDoubleWidth
	 * @covers Language::normalizeForSearch
	 */
	public function testLanguageConvertDoubleWidthToSingleWidth() {
		$this->assertEquals(
			"0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
			$this->getLang()->normalizeForSearch(
				"０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
			),
			'convertDoubleWidth() with the full alphabet and digits'
		);
	}

	/**
	 * @dataProvider provideFormattableTimes
	 * @covers Language::formatTimePeriod
	 */
	public function testFormatTimePeriod( $seconds, $format, $expected, $desc ) {
		$this->assertEquals( $expected, $this->getLang()->formatTimePeriod( $seconds, $format ), $desc );
	}

	public static function provideFormattableTimes() {
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

	/**
	 * @covers Language::truncate
	 */
	public function testTruncate() {
		$this->assertEquals(
			"XXX",
			$this->getLang()->truncate( "1234567890", 0, 'XXX' ),
			'truncate prefix, len 0, small ellipsis'
		);

		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncate( "1234567890", 8, 'XXX' ),
			'truncate prefix, small ellipsis'
		);

		$this->assertEquals(
			"123456789",
			$this->getLang()->truncate( "123456789", 5, 'XXXXXXXXXXXXXXX' ),
			'truncate prefix, large ellipsis'
		);

		$this->assertEquals(
			"XXX67890",
			$this->getLang()->truncate( "1234567890", -8, 'XXX' ),
			'truncate suffix, small ellipsis'
		);

		$this->assertEquals(
			"123456789",
			$this->getLang()->truncate( "123456789", -5, 'XXXXXXXXXXXXXXX' ),
			'truncate suffix, large ellipsis'
		);
		$this->assertEquals(
			"123XXX",
			$this->getLang()->truncate( "123                ", 9, 'XXX' ),
			'truncate prefix, with spaces'
		);
		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncate( "12345            8", 11, 'XXX' ),
			'truncate prefix, with spaces and non-space ending'
		);
		$this->assertEquals(
			"XXX234",
			$this->getLang()->truncate( "1              234", -8, 'XXX' ),
			'truncate suffix, with spaces'
		);
		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncate( "1234567890", 5, 'XXX', false ),
			'truncate without adjustment'
		);
	}

	/**
	 * @dataProvider provideHTMLTruncateData
	 * @covers Language::truncateHTML
	 */
	public function testTruncateHtml( $len, $ellipsis, $input, $expected ) {
		// Actual HTML...
		$this->assertEquals(
			$expected,
			$this->getLang()->truncateHTML( $input, $len, $ellipsis )
		);
	}

	/**
	 * @return array Format is ($len, $ellipsis, $input, $expected)
	 */
	public static function provideHTMLTruncateData() {
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
	 * Test Language::isWellFormedLanguageTag()
	 * @dataProvider provideWellFormedLanguageTags
	 * @covers Language::isWellFormedLanguageTag
	 */
	public function testWellFormedLanguageTag( $code, $message = '' ) {
		$this->assertTrue(
			Language::isWellFormedLanguageTag( $code ),
			"validating code $code $message"
		);
	}

	/**
	 * The test cases are based on the tests in the GaBuZoMeu parser
	 * written by Stéphane Bortzmeyer <bortzmeyer@nic.fr>
	 * and distributed as free software, under the GNU General Public Licence.
	 * http://www.bortzmeyer.org/gabuzomeu-parsing-language-tags.html
	 */
	public static function provideWellFormedLanguageTags() {
		return array(
			array( 'fr', 'two-letter code' ),
			array( 'fr-latn', 'two-letter code with lower case script code' ),
			array( 'fr-Latn-FR', 'two-letter code with title case script code and uppercase country code' ),
			array( 'fr-Latn-419', 'two-letter code with title case script code and region number' ),
			array( 'fr-FR', 'two-letter code with uppercase' ),
			array( 'ax-TZ', 'Not in the registry, but well-formed' ),
			array( 'fr-shadok', 'two-letter code with variant' ),
			array( 'fr-y-myext-myext2', 'non-x singleton' ),
			array( 'fra-Latn', 'ISO 639 can be 3-letters' ),
			array( 'fra', 'three-letter language code' ),
			array( 'fra-FX', 'three-letter language code with country code' ),
			array( 'i-klingon', 'grandfathered with singleton' ),
			array( 'I-kLINgon', 'tags are case-insensitive...' ),
			array( 'no-bok', 'grandfathered without singleton' ),
			array( 'i-enochian', 'Grandfathered' ),
			array( 'x-fr-CH', 'private use' ),
			array( 'es-419', 'two-letter code with region number' ),
			array( 'en-Latn-GB-boont-r-extended-sequence-x-private', 'weird, but well-formed' ),
			array( 'ab-x-abc-x-abc', 'anything goes after x' ),
			array( 'ab-x-abc-a-a', 'anything goes after x, including several non-x singletons' ),
			array( 'i-default', 'grandfathered' ),
			array( 'abcd-Latn', 'Language of 4 chars reserved for future use' ),
			array( 'AaBbCcDd-x-y-any-x', 'Language of 5-8 chars, registered' ),
			array( 'de-CH-1901', 'with country and year' ),
			array( 'en-US-x-twain', 'with country and singleton' ),
			array( 'zh-cmn', 'three-letter variant' ),
			array( 'zh-cmn-Hant', 'three-letter variant and script' ),
			array( 'zh-cmn-Hant-HK', 'three-letter variant, script and country' ),
			array( 'xr-p-lze', 'Extension' ),
		);
	}

	/**
	 * Negative test for Language::isWellFormedLanguageTag()
	 * @dataProvider provideMalformedLanguageTags
	 * @covers Language::isWellFormedLanguageTag
	 */
	public function testMalformedLanguageTag( $code, $message = '' ) {
		$this->assertFalse(
			Language::isWellFormedLanguageTag( $code ),
			"validating that code $code is a malformed language tag - $message"
		);
	}

	/**
	 * The test cases are based on the tests in the GaBuZoMeu parser
	 * written by Stéphane Bortzmeyer <bortzmeyer@nic.fr>
	 * and distributed as free software, under the GNU General Public Licence.
	 * http://www.bortzmeyer.org/gabuzomeu-parsing-language-tags.html
	 */
	public static function provideMalformedLanguageTags() {
		return array(
			array( 'f', 'language too short' ),
			array( 'f-Latn', 'language too short with script' ),
			array( 'xr-lxs-qut', 'variants too short' ), # extlangS
			array( 'fr-Latn-F', 'region too short' ),
			array( 'a-value', 'language too short with region' ),
			array( 'tlh-a-b-foo', 'valid three-letter with wrong variant' ),
			array(
				'i-notexist',
				'grandfathered but not registered: invalid, even if we only test well-formedness'
			),
			array( 'abcdefghi-012345678', 'numbers too long' ),
			array( 'ab-abc-abc-abc-abc', 'invalid extensions' ),
			array( 'ab-abcd-abc', 'invalid extensions' ),
			array( 'ab-ab-abc', 'invalid extensions' ),
			array( 'ab-123-abc', 'invalid extensions' ),
			array( 'a-Hant-ZH', 'short language with valid extensions' ),
			array( 'a1-Hant-ZH', 'invalid character in language' ),
			array( 'ab-abcde-abc', 'invalid extensions' ),
			array( 'ab-1abc-abc', 'invalid characters in extensions' ),
			array( 'ab-ab-abcd', 'invalid order of extensions' ),
			array( 'ab-123-abcd', 'invalid order of extensions' ),
			array( 'ab-abcde-abcd', 'invalid extensions' ),
			array( 'ab-1abc-abcd', 'invalid characters in extensions' ),
			array( 'ab-a-b', 'extensions too short' ),
			array( 'ab-a-x', 'extensions too short, even with singleton' ),
			array( 'ab--ab', 'two separators' ),
			array( 'ab-abc-', 'separator in the end' ),
			array( '-ab-abc', 'separator in the beginning' ),
			array( 'abcd-efg', 'language too long' ),
			array( 'aabbccddE', 'tag too long' ),
			array( 'pa_guru', 'A tag with underscore is invalid in strict mode' ),
			array( 'de-f', 'subtag too short' ),
		);
	}

	/**
	 * Negative test for Language::isWellFormedLanguageTag()
	 * @covers Language::isWellFormedLanguageTag
	 */
	public function testLenientLanguageTag() {
		$this->assertTrue(
			Language::isWellFormedLanguageTag( 'pa_guru', true ),
			'pa_guru is a well-formed language tag in lenient mode'
		);
	}

	/**
	 * Test Language::isValidBuiltInCode()
	 * @dataProvider provideLanguageCodes
	 * @covers Language::isValidBuiltInCode
	 */
	public function testBuiltInCodeValidation( $code, $expected, $message = '' ) {
		$this->assertEquals( $expected,
			(bool)Language::isValidBuiltInCode( $code ),
			"validating code $code $message"
		);
	}

	public static function provideLanguageCodes() {
		return array(
			array( 'fr', true, 'Two letters, minor case' ),
			array( 'EN', false, 'Two letters, upper case' ),
			array( 'tyv', true, 'Three letters' ),
			array( 'tokipona', true, 'long language code' ),
			array( 'be-tarask', true, 'With dash' ),
			array( 'be-x-old', true, 'With extension (two dashes)' ),
			array( 'be_tarask', false, 'Reject underscores' ),
		);
	}

	/**
	 * Test Language::isKnownLanguageTag()
	 * @dataProvider provideKnownLanguageTags
	 * @covers Language::isKnownLanguageTag
	 */
	public function testKnownLanguageTag( $code, $message = '' ) {
		$this->assertTrue(
			(bool)Language::isKnownLanguageTag( $code ),
			"validating code $code - $message"
		);
	}

	public static function provideKnownLanguageTags() {
		return array(
			array( 'fr', 'simple code' ),
			array( 'bat-smg', 'an MW legacy tag' ),
			array( 'sgs', 'an internal standard MW name, for which a legacy tag is used externally' ),
		);
	}

	/**
	 * @covers Language::isKnownLanguageTag
	 */
	public function testKnownCldrLanguageTag() {
		if ( !class_exists( 'LanguageNames' ) ) {
			$this->markTestSkipped( 'The LanguageNames class is not available. '
				. 'The CLDR extension is probably not installed.' );
		}

		$this->assertTrue(
			(bool)Language::isKnownLanguageTag( 'pal' ),
			'validating code "pal" an ancient language, which probably will '
				. 'not appear in Names.php, but appears in CLDR in English'
		);
	}

	/**
	 * Negative tests for Language::isKnownLanguageTag()
	 * @dataProvider provideUnKnownLanguageTags
	 * @covers Language::isKnownLanguageTag
	 */
	public function testUnknownLanguageTag( $code, $message = '' ) {
		$this->assertFalse(
			(bool)Language::isKnownLanguageTag( $code ),
			"checking that code $code is invalid - $message"
		);
	}

	public static function provideUnknownLanguageTags() {
		return array(
			array( 'mw', 'non-existent two-letter code' ),
			array( 'foo"<bar', 'very invalid language code' ),
		);
	}

	/**
	 * Test too short timestamp
	 * @expectedException MWException
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDateTooShortTimestamp() {
		$this->getLang()->sprintfDate( 'xiY', '1234567890123' );
	}

	/**
	 * Test too long timestamp
	 * @expectedException MWException
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDateTooLongTimestamp() {
		$this->getLang()->sprintfDate( 'xiY', '123456789012345' );
	}

	/**
	 * Test too short timestamp
	 * @expectedException MWException
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDateNotAllDigitTimestamp() {
		$this->getLang()->sprintfDate( 'xiY', '-1234567890123' );
	}

	/**
	 * @dataProvider provideSprintfDateSamples
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDate( $format, $ts, $expected, $msg ) {
		$ttl = null;
		$this->assertEquals(
			$expected,
			$this->getLang()->sprintfDate( $format, $ts, null, $ttl ),
			"sprintfDate('$format', '$ts'): $msg"
		);
		if ( $ttl ) {
			$dt = new DateTime( $ts );
			$lastValidTS = $dt->add( new DateInterval( 'PT' . ( $ttl - 1 ) . 'S' ) )->format( 'YmdHis' );
			$this->assertEquals(
				$expected,
				$this->getLang()->sprintfDate( $format, $lastValidTS, null ),
				"sprintfDate('$format', '$ts'): TTL $ttl too high (output was different at $lastValidTS)"
			);
		} else {
			// advance the time enough to make all of the possible outputs different (except possibly L)
			$dt = new DateTime( $ts );
			$newTS = $dt->add( new DateInterval( 'P1Y1M8DT13H1M1S' ) )->format( 'YmdHis' );
			$this->assertEquals(
				$expected,
				$this->getLang()->sprintfDate( $format, $newTS, null ),
				"sprintfDate('$format', '$ts'): Missing TTL (output was different at $newTS)"
			);
		}
	}

	/**
	 * sprintfDate should always use UTC when no zone is given.
	 * @dataProvider provideSprintfDateSamples
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDateNoZone( $format, $ts, $expected, $ignore, $msg ) {
		$oldTZ = date_default_timezone_get();
		$res = date_default_timezone_set( 'Asia/Seoul' );
		if ( !$res ) {
			$this->markTestSkipped( "Error setting Timezone" );
		}

		$this->assertEquals(
			$expected,
			$this->getLang()->sprintfDate( $format, $ts ),
			"sprintfDate('$format', '$ts'): $msg"
		);

		date_default_timezone_set( $oldTZ );
	}

	/**
	 * sprintfDate should use passed timezone
	 * @dataProvider provideSprintfDateSamples
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDateTZ( $format, $ts, $ignore, $expected, $msg ) {
		$tz = new DateTimeZone( 'Asia/Seoul' );
		if ( !$tz ) {
			$this->markTestSkipped( "Error getting Timezone" );
		}

		$this->assertEquals(
			$expected,
			$this->getLang()->sprintfDate( $format, $ts, $tz ),
			"sprintfDate('$format', '$ts', 'Asia/Seoul'): $msg"
		);
	}

	public static function provideSprintfDateSamples() {
		return array(
			array(
				'xiY',
				'20111212000000',
				'1390', // note because we're testing English locale we get Latin-standard digits
				'1390',
				'Iranian calendar full year'
			),
			array(
				'xiy',
				'20111212000000',
				'90',
				'90',
				'Iranian calendar short year'
			),
			array(
				'o',
				'20120101235000',
				'2011',
				'2011',
				'ISO 8601 (week) year'
			),
			array(
				'W',
				'20120101235000',
				'52',
				'52',
				'Week number'
			),
			array(
				'W',
				'20120102235000',
				'1',
				'1',
				'Week number'
			),
			array(
				'o-\\WW-N',
				'20091231235000',
				'2009-W53-4',
				'2009-W53-4',
				'leap week'
			),
			// What follows is mostly copied from
			// https://www.mediawiki.org/wiki/Help:Extension:ParserFunctions#.23time
			array(
				'Y',
				'20120102090705',
				'2012',
				'2012',
				'Full year'
			),
			array(
				'y',
				'20120102090705',
				'12',
				'12',
				'2 digit year'
			),
			array(
				'L',
				'20120102090705',
				'1',
				'1',
				'Leap year'
			),
			array(
				'n',
				'20120102090705',
				'1',
				'1',
				'Month index, not zero pad'
			),
			array(
				'N',
				'20120102090705',
				'01',
				'01',
				'Month index. Zero pad'
			),
			array(
				'M',
				'20120102090705',
				'Jan',
				'Jan',
				'Month abbrev'
			),
			array(
				'F',
				'20120102090705',
				'January',
				'January',
				'Full month'
			),
			array(
				'xg',
				'20120102090705',
				'January',
				'January',
				'Genitive month name (same in EN)'
			),
			array(
				'j',
				'20120102090705',
				'2',
				'2',
				'Day of month (not zero pad)'
			),
			array(
				'd',
				'20120102090705',
				'02',
				'02',
				'Day of month (zero-pad)'
			),
			array(
				'z',
				'20120102090705',
				'1',
				'1',
				'Day of year (zero-indexed)'
			),
			array(
				'D',
				'20120102090705',
				'Mon',
				'Mon',
				'Day of week (abbrev)'
			),
			array(
				'l',
				'20120102090705',
				'Monday',
				'Monday',
				'Full day of week'
			),
			array(
				'N',
				'20120101090705',
				'7',
				'7',
				'Day of week (Mon=1, Sun=7)'
			),
			array(
				'w',
				'20120101090705',
				'0',
				'0',
				'Day of week (Sun=0, Sat=6)'
			),
			array(
				'N',
				'20120102090705',
				'1',
				'1',
				'Day of week'
			),
			array(
				'a',
				'20120102090705',
				'am',
				'am',
				'am vs pm'
			),
			array(
				'A',
				'20120102120000',
				'PM',
				'PM',
				'AM vs PM'
			),
			array(
				'a',
				'20120102000000',
				'am',
				'am',
				'AM vs PM'
			),
			array(
				'g',
				'20120102090705',
				'9',
				'9',
				'12 hour, not Zero'
			),
			array(
				'h',
				'20120102090705',
				'09',
				'09',
				'12 hour, zero padded'
			),
			array(
				'G',
				'20120102090705',
				'9',
				'9',
				'24 hour, not zero'
			),
			array(
				'H',
				'20120102090705',
				'09',
				'09',
				'24 hour, zero'
			),
			array(
				'H',
				'20120102110705',
				'11',
				'11',
				'24 hour, zero'
			),
			array(
				'i',
				'20120102090705',
				'07',
				'07',
				'Minutes'
			),
			array(
				's',
				'20120102090705',
				'05',
				'05',
				'seconds'
			),
			array(
				'U',
				'20120102090705',
				'1325495225',
				'1325462825',
				'unix time'
			),
			array(
				't',
				'20120102090705',
				'31',
				'31',
				'Days in current month'
			),
			array(
				'c',
				'20120102090705',
				'2012-01-02T09:07:05+00:00',
				'2012-01-02T09:07:05+09:00',
				'ISO 8601 timestamp'
			),
			array(
				'r',
				'20120102090705',
				'Mon, 02 Jan 2012 09:07:05 +0000',
				'Mon, 02 Jan 2012 09:07:05 +0900',
				'RFC 5322'
			),
			array(
				'e',
				'20120102090705',
				'UTC',
				'Asia/Seoul',
				'Timezone identifier'
			),
			array(
				'I',
				'19880602090705',
				'0',
				'1',
				'DST indicator'
			),
			array(
				'O',
				'20120102090705',
				'+0000',
				'+0900',
				'Timezone offset'
			),
			array(
				'P',
				'20120102090705',
				'+00:00',
				'+09:00',
				'Timezone offset with colon'
			),
			array(
				'T',
				'20120102090705',
				'UTC',
				'KST',
				'Timezone abbreviation'
			),
			array(
				'Z',
				'20120102090705',
				'0',
				'32400',
				'Timezone offset in seconds'
			),
			array(
				'xmj xmF xmn xmY',
				'20120102090705',
				'7 Safar 2 1433',
				'7 Safar 2 1433',
				'Islamic'
			),
			array(
				'xij xiF xin xiY',
				'20120102090705',
				'12 Dey 10 1390',
				'12 Dey 10 1390',
				'Iranian'
			),
			array(
				'xjj xjF xjn xjY',
				'20120102090705',
				'7 Tevet 4 5772',
				'7 Tevet 4 5772',
				'Hebrew'
			),
			array(
				'xjt',
				'20120102090705',
				'29',
				'29',
				'Hebrew number of days in month'
			),
			array(
				'xjx',
				'20120102090705',
				'Tevet',
				'Tevet',
				'Hebrew genitive month name (No difference in EN)'
			),
			array(
				'xkY',
				'20120102090705',
				'2555',
				'2555',
				'Thai year'
			),
			array(
				'xoY',
				'20120102090705',
				'101',
				'101',
				'Minguo'
			),
			array(
				'xtY',
				'20120102090705',
				'平成24',
				'平成24',
				'nengo'
			),
			array(
				'xrxkYY',
				'20120102090705',
				'MMDLV2012',
				'MMDLV2012',
				'Roman numerals'
			),
			array(
				'xhxjYY',
				'20120102090705',
				'ה\'תשע"ב2012',
				'ה\'תשע"ב2012',
				'Hebrew numberals'
			),
			array(
				'xnY',
				'20120102090705',
				'2012',
				'2012',
				'Raw numerals (doesn\'t mean much in EN)'
			),
			array(
				'[[Y "(yea"\\r)]] \\"xx\\"',
				'20120102090705',
				'[[2012 (year)]] "x"',
				'[[2012 (year)]] "x"',
				'Various escaping'
			),

		);
	}

	/**
	 * @dataProvider provideFormatSizes
	 * @covers Language::formatSize
	 */
	public function testFormatSize( $size, $expected, $msg ) {
		$this->assertEquals(
			$expected,
			$this->getLang()->formatSize( $size ),
			"formatSize('$size'): $msg"
		);
	}

	public static function provideFormatSizes() {
		return array(
			array(
				0,
				"0 bytes",
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
				"1 gigabyte"
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
	 * @covers Language::formatBitrate
	 */
	public function testFormatBitrate( $bps, $expected, $msg ) {
		$this->assertEquals(
			$expected,
			$this->getLang()->formatBitrate( $bps ),
			"formatBitrate('$bps'): $msg"
		);
	}

	public static function provideFormatBitrate() {
		return array(
			array(
				0,
				"0 bps",
				"0 bits per second"
			),
			array(
				999,
				"999 bps",
				"999 bits per second"
			),
			array(
				1000,
				"1 kbps",
				"1 kilobit per second"
			),
			array(
				1000 * 1000,
				"1 Mbps",
				"1 megabit per second"
			),
			array(
				pow( 10, 9 ),
				"1 Gbps",
				"1 gigabit per second"
			),
			array(
				pow( 10, 12 ),
				"1 Tbps",
				"1 terabit per second"
			),
			array(
				pow( 10, 15 ),
				"1 Pbps",
				"1 petabit per second"
			),
			array(
				pow( 10, 18 ),
				"1 Ebps",
				"1 exabit per second"
			),
			array(
				pow( 10, 21 ),
				"1 Zbps",
				"1 zetabit per second"
			),
			array(
				pow( 10, 24 ),
				"1 Ybps",
				"1 yottabit per second"
			),
			array(
				pow( 10, 27 ),
				"1,000 Ybps",
				"1,000 yottabits per second"
			),
		);
	}

	/**
	 * @dataProvider provideFormatDuration
	 * @covers Language::formatDuration
	 */
	public function testFormatDuration( $duration, $expected, $intervals = array() ) {
		$this->assertEquals(
			$expected,
			$this->getLang()->formatDuration( $duration, $intervals ),
			"formatDuration('$duration'): $expected"
		);
	}

	public static function provideFormatDuration() {
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
				// ( 365 + ( 24 * 3 + 25 ) / 400 ) * 86400 = 31556952
				( 365 + ( 24 * 3 + 25 ) / 400.0 ) * 86400,
				'1 year',
			),
			array(
				2 * 31556952,
				'2 years',
			),
			array(
				10 * 31556952,
				'1 decade',
			),
			array(
				20 * 31556952,
				'2 decades',
			),
			array(
				100 * 31556952,
				'1 century',
			),
			array(
				200 * 31556952,
				'2 centuries',
			),
			array(
				1000 * 31556952,
				'1 millennium',
			),
			array(
				2000 * 31556952,
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
				31556952 + 2 * 86400 + 9000,
				'1 year, 2 days, 2 hours and 30 minutes'
			),
			array(
				42 * 1000 * 31556952 + 42,
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
				31556952 + 2 * 86400 + 9000,
				'1 year, 2 days and 150 minutes',
				array( 'years', 'days', 'minutes' ),
			),
			array(
				42,
				'0 days',
				array( 'years', 'days' ),
			),
			array(
				31556952 + 2 * 86400 + 9000,
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
	 * @covers Language::checkTitleEncoding
	 */
	public function testCheckTitleEncoding( $s ) {
		$this->assertEquals(
			$s,
			$this->getLang()->checkTitleEncoding( $s ),
			"checkTitleEncoding('$s')"
		);
	}

	public static function provideCheckTitleEncodingData() {
		// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
		return array(
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
		// @codingStandardsIgnoreEnd
	}

	/**
	 * @dataProvider provideRomanNumeralsData
	 * @covers Language::romanNumeral
	 */
	public function testRomanNumerals( $num, $numerals ) {
		$this->assertEquals(
			$numerals,
			Language::romanNumeral( $num ),
			"romanNumeral('$num')"
		);
	}

	public static function provideRomanNumeralsData() {
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
			array( 9999, 'MMMMMMMMMCMXCIX' ),
			array( 10000, 'MMMMMMMMMM' ),
		);
	}

	/**
	 * @dataProvider provideHebrewNumeralsData
	 * @covers Language::hebrewNumeral
	 */
	public function testHebrewNumeral( $num, $numerals ) {
		$this->assertEquals(
			$numerals,
			Language::hebrewNumeral( $num ),
			"hebrewNumeral('$num')"
		);
	}

	public static function provideHebrewNumeralsData() {
		return array(
			array( -1, -1 ),
			array( 0, 0 ),
			array( 1, "א'" ),
			array( 2, "ב'" ),
			array( 3, "ג'" ),
			array( 4, "ד'" ),
			array( 5, "ה'" ),
			array( 6, "ו'" ),
			array( 7, "ז'" ),
			array( 8, "ח'" ),
			array( 9, "ט'" ),
			array( 10, "י'" ),
			array( 11, 'י"א' ),
			array( 14, 'י"ד' ),
			array( 15, 'ט"ו' ),
			array( 16, 'ט"ז' ),
			array( 17, 'י"ז' ),
			array( 20, "כ'" ),
			array( 21, 'כ"א' ),
			array( 30, "ל'" ),
			array( 40, "מ'" ),
			array( 50, "נ'" ),
			array( 60, "ס'" ),
			array( 70, "ע'" ),
			array( 80, "פ'" ),
			array( 90, "צ'" ),
			array( 99, 'צ"ט' ),
			array( 100, "ק'" ),
			array( 101, 'ק"א' ),
			array( 110, 'ק"י' ),
			array( 200, "ר'" ),
			array( 300, "ש'" ),
			array( 400, "ת'" ),
			array( 500, 'ת"ק' ),
			array( 800, 'ת"ת' ),
			array( 1000, "א' אלף" ),
			array( 1001, "א'א'" ),
			array( 1012, "א'י\"ב" ),
			array( 1020, "א'ך'" ),
			array( 1030, "א'ל'" ),
			array( 1081, "א'פ\"א" ),
			array( 2000, "ב' אלפים" ),
			array( 2016, "ב'ט\"ז" ),
			array( 3000, "ג' אלפים" ),
			array( 4000, "ד' אלפים" ),
			array( 4904, "ד'תתק\"ד" ),
			array( 5000, "ה' אלפים" ),
			array( 5680, "ה'תר\"ף" ),
			array( 5690, "ה'תר\"ץ" ),
			array( 5708, "ה'תש\"ח" ),
			array( 5720, "ה'תש\"ך" ),
			array( 5740, "ה'תש\"ם" ),
			array( 5750, "ה'תש\"ן" ),
			array( 5775, "ה'תשע\"ה" ),
		);
	}

	/**
	 * @dataProvider providePluralData
	 * @covers Language::convertPlural
	 */
	public function testConvertPlural( $expected, $number, $forms ) {
		$chosen = $this->getLang()->convertPlural( $number, $forms );
		$this->assertEquals( $expected, $chosen );
	}

	public static function providePluralData() {
		// Params are: [expected text, number given, [the plural forms]]
		return array(
			array( 'plural', 0, array(
				'singular', 'plural'
			) ),
			array( 'explicit zero', 0, array(
				'0=explicit zero', 'singular', 'plural'
			) ),
			array( 'explicit one', 1, array(
				'singular', 'plural', '1=explicit one',
			) ),
			array( 'singular', 1, array(
				'singular', 'plural', '0=explicit zero',
			) ),
			array( 'plural', 3, array(
				'0=explicit zero', '1=explicit one', 'singular', 'plural'
			) ),
			array( 'explicit eleven', 11, array(
				'singular', 'plural', '11=explicit eleven',
			) ),
			array( 'plural', 12, array(
				'singular', 'plural', '11=explicit twelve',
			) ),
			array( 'plural', 12, array(
				'singular', 'plural', '=explicit form',
			) ),
			array( 'other', 2, array(
				'kissa=kala', '1=2=3', 'other',
			) ),
			array( '', 2, array(
				'0=explicit zero', '1=explicit one',
			) ),
		);
	}

	/**
	 * @covers Language::embedBidi()
	 */
	public function testEmbedBidi() {
		$lre = "\xE2\x80\xAA"; // U+202A LEFT-TO-RIGHT EMBEDDING
		$rle = "\xE2\x80\xAB"; // U+202B RIGHT-TO-LEFT EMBEDDING
		$pdf = "\xE2\x80\xAC"; // U+202C POP DIRECTIONAL FORMATTING
		$lang = $this->getLang();
		$this->assertEquals(
			'123',
			$lang->embedBidi( '123' ),
			'embedBidi with neutral argument'
		);
		$this->assertEquals(
			$lre . 'Ben_(WMF)' . $pdf,
			$lang->embedBidi( 'Ben_(WMF)' ),
			'embedBidi with LTR argument'
		);
		$this->assertEquals(
			$rle . 'יהודי (מנוחין)' . $pdf,
			$lang->embedBidi( 'יהודי (מנוחין)' ),
			'embedBidi with RTL argument'
		);
	}

	/**
	 * @covers Language::translateBlockExpiry()
	 * @dataProvider provideTranslateBlockExpiry
	 */
	public function testTranslateBlockExpiry( $expectedData, $str, $desc ) {
		$lang = $this->getLang();
		if ( is_array( $expectedData ) ) {
			list( $func, $arg ) = $expectedData;
			$expected = $lang->$func( $arg );
		} else {
			$expected = $expectedData;
		}
		$this->assertEquals( $expected, $lang->translateBlockExpiry( $str ), $desc );
	}

	public static function provideTranslateBlockExpiry() {
		return array(
			array( '2 hours', '2 hours', 'simple data from ipboptions' ),
			array( 'indefinite', 'infinite', 'infinite from ipboptions' ),
			array( 'indefinite', 'infinity', 'alternative infinite from ipboptions' ),
			array( 'indefinite', 'indefinite', 'another alternative infinite from ipboptions' ),
			array( array( 'formatDuration', 1023 * 60 * 60 ), '1023 hours', 'relative' ),
			array( array( 'formatDuration', -1023 ), '-1023 seconds', 'negative relative' ),
			array( array( 'formatDuration', 0 ), 'now', 'now' ),
			array(
				array( 'timeanddate', '20120102070000' ),
				'2012-1-1 7:00 +1 day',
				'mixed, handled as absolute'
			),
			array( array( 'timeanddate', '19910203040506' ), '1991-2-3 4:05:06', 'absolute' ),
			array( array( 'timeanddate', '19700101000000' ), '1970-1-1 0:00:00', 'absolute at epoch' ),
			array( array( 'timeanddate', '19691231235959' ), '1969-12-31 23:59:59', 'time before epoch' ),
			array( 'dummy', 'dummy', 'return garbage as is' ),
		);
	}

	/**
	 * @dataProvider parseFormattedNumberProvider
	 */
	public function testParseFormattedNumber( $langCode, $number ) {
		$lang = Language::factory( $langCode );

		$localisedNum = $lang->formatNum( $number );
		$normalisedNum = $lang->parseFormattedNumber( $localisedNum );

		$this->assertEquals( $number, $normalisedNum );
	}

	public function parseFormattedNumberProvider() {
		return array(
			array( 'de', 377.01 ),
			array( 'fa', 334 ),
			array( 'fa', 382.772 ),
			array( 'ar', 1844 ),
			array( 'lzh', 3731 ),
			array( 'zh-classical', 7432 )
		);
	}

	/**
	 * @covers Language::commafy()
	 * @dataProvider provideCommafyData
	 */
	public function testCommafy( $number, $numbersWithCommas ) {
		$this->assertEquals(
			$numbersWithCommas,
			$this->getLang()->commafy( $number ),
			"commafy('$number')"
		);
	}

	public static function provideCommafyData() {
		return array(
			array( -1, '-1' ),
			array( 10, '10' ),
			array( 100, '100' ),
			array( 1000, '1,000' ),
			array( 10000, '10,000' ),
			array( 100000, '100,000' ),
			array( 1000000, '1,000,000' ),
			array( -1.0001, '-1.0001' ),
			array( 1.0001, '1.0001' ),
			array( 10.0001, '10.0001' ),
			array( 100.0001, '100.0001' ),
			array( 1000.0001, '1,000.0001' ),
			array( 10000.0001, '10,000.0001' ),
			array( 100000.0001, '100,000.0001' ),
			array( 1000000.0001, '1,000,000.0001' ),
			array( '200000000000000000000', '200,000,000,000,000,000,000' ),
			array( '-200000000000000000000', '-200,000,000,000,000,000,000' ),
		);
	}

	/**
	 * @covers Language::listToText
	 */
	public function testListToText() {
		$lang = $this->getLang();
		$and = $lang->getMessageFromDB( 'and' );
		$s = $lang->getMessageFromDB( 'word-separator' );
		$c = $lang->getMessageFromDB( 'comma-separator' );

		$this->assertEquals( '', $lang->listToText( array() ) );
		$this->assertEquals( 'a', $lang->listToText( array( 'a' ) ) );
		$this->assertEquals( "a{$and}{$s}b", $lang->listToText( array( 'a', 'b' ) ) );
		$this->assertEquals( "a{$c}b{$and}{$s}c", $lang->listToText( array( 'a', 'b', 'c' ) ) );
		$this->assertEquals( "a{$c}b{$c}c{$and}{$s}d", $lang->listToText( array( 'a', 'b', 'c', 'd' ) ) );
	}

	/**
	 * @dataProvider provideIsSupportedLanguage
	 * @covers Language::isSupportedLanguage
	 */
	public function testIsSupportedLanguage( $code, $expected, $comment ) {
		$this->assertEquals( $expected, Language::isSupportedLanguage( $code ), $comment );
	}

	public static function provideIsSupportedLanguage() {
		return array(
			array( 'en', true, 'is supported language' ),
			array( 'fi', true, 'is supported language' ),
			array( 'bunny', false, 'is not supported language' ),
			array( 'FI', false, 'is not supported language, input should be in lower case' ),
		);
	}

	/**
	 * @dataProvider provideGetParentLanguage
	 * @covers Language::getParentLanguage
	 */
	public function testGetParentLanguage( $code, $expected, $comment ) {
		$lang = Language::factory( $code );
		if ( is_null( $expected ) ) {
			$this->assertNull( $lang->getParentLanguage(), $comment );
		} else {
			$this->assertEquals( $expected, $lang->getParentLanguage()->getCode(), $comment );
		}
	}

	public static function provideGetParentLanguage() {
		return array(
			array( 'zh-cn', 'zh', 'zh is the parent language of zh-cn' ),
			array( 'zh', 'zh', 'zh is defined as the parent language of zh, '
				. 'because zh converter can convert zh-cn to zh' ),
			array( 'zh-invalid', null, 'do not be fooled by arbitrarily composed language codes' ),
			array( 'en-gb', null, 'en does not have converter' ),
			array( 'en', null, 'en does not have converter. Although FakeConverter '
					. 'handles en -> en conversion but it is useless' ),
		);
	}

	/**
	 * @dataProvider provideGetNamespaceAliases
	 * @covers Language::getNamespaceAliases
	 */
	public function testGetNamespaceAliases( $languageCode, $subset ) {
		$language = Language::factory( $languageCode );
		$aliases = $language->getNamespaceAliases();
		foreach ( $subset as $alias => $nsId ) {
			$this->assertEquals( $nsId, $aliases[$alias] );
		}
	}

	public static function provideGetNamespaceAliases() {
		// TODO: Add tests for NS_PROJECT_TALK and GenderNamespaces
		return array(
			array(
				'zh',
				array(
					'文件' => NS_FILE,
					'檔案' => NS_FILE,
				),
			),
		);
	}
}
