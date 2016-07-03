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
		return [
			[
				9.45,
				[],
				'9.5 s',
				'formatTimePeriod() rounding (<10s)'
			],
			[
				9.45,
				[ 'noabbrevs' => true ],
				'9.5 seconds',
				'formatTimePeriod() rounding (<10s)'
			],
			[
				9.95,
				[],
				'10 s',
				'formatTimePeriod() rounding (<10s)'
			],
			[
				9.95,
				[ 'noabbrevs' => true ],
				'10 seconds',
				'formatTimePeriod() rounding (<10s)'
			],
			[
				59.55,
				[],
				'1 min 0 s',
				'formatTimePeriod() rounding (<60s)'
			],
			[
				59.55,
				[ 'noabbrevs' => true ],
				'1 minute 0 seconds',
				'formatTimePeriod() rounding (<60s)'
			],
			[
				119.55,
				[],
				'2 min 0 s',
				'formatTimePeriod() rounding (<1h)'
			],
			[
				119.55,
				[ 'noabbrevs' => true ],
				'2 minutes 0 seconds',
				'formatTimePeriod() rounding (<1h)'
			],
			[
				3599.55,
				[],
				'1 h 0 min 0 s',
				'formatTimePeriod() rounding (<1h)'
			],
			[
				3599.55,
				[ 'noabbrevs' => true ],
				'1 hour 0 minutes 0 seconds',
				'formatTimePeriod() rounding (<1h)'
			],
			[
				7199.55,
				[],
				'2 h 0 min 0 s',
				'formatTimePeriod() rounding (>=1h)'
			],
			[
				7199.55,
				[ 'noabbrevs' => true ],
				'2 hours 0 minutes 0 seconds',
				'formatTimePeriod() rounding (>=1h)'
			],
			[
				7199.55,
				'avoidseconds',
				'2 h 0 min',
				'formatTimePeriod() rounding (>=1h), avoidseconds'
			],
			[
				7199.55,
				[ 'avoid' => 'avoidseconds', 'noabbrevs' => true ],
				'2 hours 0 minutes',
				'formatTimePeriod() rounding (>=1h), avoidseconds'
			],
			[
				7199.55,
				'avoidminutes',
				'2 h 0 min',
				'formatTimePeriod() rounding (>=1h), avoidminutes'
			],
			[
				7199.55,
				[ 'avoid' => 'avoidminutes', 'noabbrevs' => true ],
				'2 hours 0 minutes',
				'formatTimePeriod() rounding (>=1h), avoidminutes'
			],
			[
				172799.55,
				'avoidseconds',
				'48 h 0 min',
				'formatTimePeriod() rounding (=48h), avoidseconds'
			],
			[
				172799.55,
				[ 'avoid' => 'avoidseconds', 'noabbrevs' => true ],
				'48 hours 0 minutes',
				'formatTimePeriod() rounding (=48h), avoidseconds'
			],
			[
				259199.55,
				'avoidminutes',
				'3 d 0 h',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			],
			[
				259199.55,
				[ 'avoid' => 'avoidminutes', 'noabbrevs' => true ],
				'3 days 0 hours',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			],
			[
				176399.55,
				'avoidseconds',
				'2 d 1 h 0 min',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			],
			[
				176399.55,
				[ 'avoid' => 'avoidseconds', 'noabbrevs' => true ],
				'2 days 1 hour 0 minutes',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			],
			[
				176399.55,
				'avoidminutes',
				'2 d 1 h',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			],
			[
				176399.55,
				[ 'avoid' => 'avoidminutes', 'noabbrevs' => true ],
				'2 days 1 hour',
				'formatTimePeriod() rounding (>48h), avoidminutes'
			],
			[
				259199.55,
				'avoidseconds',
				'3 d 0 h 0 min',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			],
			[
				259199.55,
				[ 'avoid' => 'avoidseconds', 'noabbrevs' => true ],
				'3 days 0 hours 0 minutes',
				'formatTimePeriod() rounding (>48h), avoidseconds'
			],
			[
				172801.55,
				'avoidseconds',
				'2 d 0 h 0 min',
				'formatTimePeriod() rounding, (>48h), avoidseconds'
			],
			[
				172801.55,
				[ 'avoid' => 'avoidseconds', 'noabbrevs' => true ],
				'2 days 0 hours 0 minutes',
				'formatTimePeriod() rounding, (>48h), avoidseconds'
			],
			[
				176460.55,
				[],
				'2 d 1 h 1 min 1 s',
				'formatTimePeriod() rounding, recursion, (>48h)'
			],
			[
				176460.55,
				[ 'noabbrevs' => true ],
				'2 days 1 hour 1 minute 1 second',
				'formatTimePeriod() rounding, recursion, (>48h)'
			],
		];
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
		$this->assertEquals(
			"泰乐菌...",
			$this->getLang()->truncate( "泰乐菌素123456789", 11, '...', false ),
			'truncate does not chop Unicode characters in half'
		);
		$this->assertEquals(
			"\n泰乐菌...",
			$this->getLang()->truncate( "\n泰乐菌素123456789", 12, '...', false ),
			'truncate does not chop Unicode characters in half if there is a preceding newline'
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
			$this->getLang()->truncateHtml( $input, $len, $ellipsis )
		);
	}

	/**
	 * @return array Format is ($len, $ellipsis, $input, $expected)
	 */
	public static function provideHTMLTruncateData() {
		return [
			[ 0, 'XXX', "1234567890", "XXX" ],
			[ 8, 'XXX', "1234567890", "12345XXX" ],
			[ 5, 'XXXXXXXXXXXXXXX', '1234567890', "1234567890" ],
			[ 2, '***',
				'<p><span style="font-weight:bold;"></span></p>',
				'<p><span style="font-weight:bold;"></span></p>',
			],
			[ 2, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			],
			[ 2, '***',
				'<p><span style="font-weight:bold;">&nbsp;23456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			],
			[ 3, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">***</span></p>',
			],
			[ 4, '***',
				'<p><span style="font-weight:bold;">123456789</span></p>',
				'<p><span style="font-weight:bold;">1***</span></p>',
			],
			[ 5, '***',
				'<tt><span style="font-weight:bold;">123456789</span></tt>',
				'<tt><span style="font-weight:bold;">12***</span></tt>',
			],
			[ 6, '***',
				'<p><a href="www.mediawiki.org">123456789</a></p>',
				'<p><a href="www.mediawiki.org">123***</a></p>',
			],
			[ 6, '***',
				'<p><a href="www.mediawiki.org">12&nbsp;456789</a></p>',
				'<p><a href="www.mediawiki.org">12&nbsp;***</a></p>',
			],
			[ 7, '***',
				'<small><span style="font-weight:bold;">123<p id="#moo">456</p>789</span></small>',
				'<small><span style="font-weight:bold;">123<p id="#moo">4***</p></span></small>',
			],
			[ 8, '***',
				'<div><span style="font-weight:bold;">123<span>4</span>56789</span></div>',
				'<div><span style="font-weight:bold;">123<span>4</span>5***</span></div>',
			],
			[ 9, '***',
				'<p><table style="font-weight:bold;"><tr><td>123456789</td></tr></table></p>',
				'<p><table style="font-weight:bold;"><tr><td>123456789</td></tr></table></p>',
			],
			[ 10, '***',
				'<p><font style="font-weight:bold;">123456789</font></p>',
				'<p><font style="font-weight:bold;">123456789</font></p>',
			],
		];
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
		return [
			[ 'fr', 'two-letter code' ],
			[ 'fr-latn', 'two-letter code with lower case script code' ],
			[ 'fr-Latn-FR', 'two-letter code with title case script code and uppercase country code' ],
			[ 'fr-Latn-419', 'two-letter code with title case script code and region number' ],
			[ 'fr-FR', 'two-letter code with uppercase' ],
			[ 'ax-TZ', 'Not in the registry, but well-formed' ],
			[ 'fr-shadok', 'two-letter code with variant' ],
			[ 'fr-y-myext-myext2', 'non-x singleton' ],
			[ 'fra-Latn', 'ISO 639 can be 3-letters' ],
			[ 'fra', 'three-letter language code' ],
			[ 'fra-FX', 'three-letter language code with country code' ],
			[ 'i-klingon', 'grandfathered with singleton' ],
			[ 'I-kLINgon', 'tags are case-insensitive...' ],
			[ 'no-bok', 'grandfathered without singleton' ],
			[ 'i-enochian', 'Grandfathered' ],
			[ 'x-fr-CH', 'private use' ],
			[ 'es-419', 'two-letter code with region number' ],
			[ 'en-Latn-GB-boont-r-extended-sequence-x-private', 'weird, but well-formed' ],
			[ 'ab-x-abc-x-abc', 'anything goes after x' ],
			[ 'ab-x-abc-a-a', 'anything goes after x, including several non-x singletons' ],
			[ 'i-default', 'grandfathered' ],
			[ 'abcd-Latn', 'Language of 4 chars reserved for future use' ],
			[ 'AaBbCcDd-x-y-any-x', 'Language of 5-8 chars, registered' ],
			[ 'de-CH-1901', 'with country and year' ],
			[ 'en-US-x-twain', 'with country and singleton' ],
			[ 'zh-cmn', 'three-letter variant' ],
			[ 'zh-cmn-Hant', 'three-letter variant and script' ],
			[ 'zh-cmn-Hant-HK', 'three-letter variant, script and country' ],
			[ 'xr-p-lze', 'Extension' ],
		];
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
		return [
			[ 'f', 'language too short' ],
			[ 'f-Latn', 'language too short with script' ],
			[ 'xr-lxs-qut', 'variants too short' ], # extlangS
			[ 'fr-Latn-F', 'region too short' ],
			[ 'a-value', 'language too short with region' ],
			[ 'tlh-a-b-foo', 'valid three-letter with wrong variant' ],
			[
				'i-notexist',
				'grandfathered but not registered: invalid, even if we only test well-formedness'
			],
			[ 'abcdefghi-012345678', 'numbers too long' ],
			[ 'ab-abc-abc-abc-abc', 'invalid extensions' ],
			[ 'ab-abcd-abc', 'invalid extensions' ],
			[ 'ab-ab-abc', 'invalid extensions' ],
			[ 'ab-123-abc', 'invalid extensions' ],
			[ 'a-Hant-ZH', 'short language with valid extensions' ],
			[ 'a1-Hant-ZH', 'invalid character in language' ],
			[ 'ab-abcde-abc', 'invalid extensions' ],
			[ 'ab-1abc-abc', 'invalid characters in extensions' ],
			[ 'ab-ab-abcd', 'invalid order of extensions' ],
			[ 'ab-123-abcd', 'invalid order of extensions' ],
			[ 'ab-abcde-abcd', 'invalid extensions' ],
			[ 'ab-1abc-abcd', 'invalid characters in extensions' ],
			[ 'ab-a-b', 'extensions too short' ],
			[ 'ab-a-x', 'extensions too short, even with singleton' ],
			[ 'ab--ab', 'two separators' ],
			[ 'ab-abc-', 'separator in the end' ],
			[ '-ab-abc', 'separator in the beginning' ],
			[ 'abcd-efg', 'language too long' ],
			[ 'aabbccddE', 'tag too long' ],
			[ 'pa_guru', 'A tag with underscore is invalid in strict mode' ],
			[ 'de-f', 'subtag too short' ],
		];
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
		return [
			[ 'fr', true, 'Two letters, minor case' ],
			[ 'EN', false, 'Two letters, upper case' ],
			[ 'tyv', true, 'Three letters' ],
			[ 'tokipona', true, 'long language code' ],
			[ 'be-tarask', true, 'With dash' ],
			[ 'be-x-old', true, 'With extension (two dashes)' ],
			[ 'be_tarask', false, 'Reject underscores' ],
		];
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
		return [
			[ 'fr', 'simple code' ],
			[ 'bat-smg', 'an MW legacy tag' ],
			[ 'sgs', 'an internal standard MW name, for which a legacy tag is used externally' ],
		];
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
		return [
			[ 'mw', 'non-existent two-letter code' ],
			[ 'foo"<bar', 'very invalid language code' ],
		];
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
		return [
			[
				'xiY',
				'20111212000000',
				'1390', // note because we're testing English locale we get Latin-standard digits
				'1390',
				'Iranian calendar full year'
			],
			[
				'xiy',
				'20111212000000',
				'90',
				'90',
				'Iranian calendar short year'
			],
			[
				'o',
				'20120101235000',
				'2011',
				'2011',
				'ISO 8601 (week) year'
			],
			[
				'W',
				'20120101235000',
				'52',
				'52',
				'Week number'
			],
			[
				'W',
				'20120102235000',
				'1',
				'1',
				'Week number'
			],
			[
				'o-\\WW-N',
				'20091231235000',
				'2009-W53-4',
				'2009-W53-4',
				'leap week'
			],
			// What follows is mostly copied from
			// https://www.mediawiki.org/wiki/Help:Extension:ParserFunctions#.23time
			[
				'Y',
				'20120102090705',
				'2012',
				'2012',
				'Full year'
			],
			[
				'y',
				'20120102090705',
				'12',
				'12',
				'2 digit year'
			],
			[
				'L',
				'20120102090705',
				'1',
				'1',
				'Leap year'
			],
			[
				'n',
				'20120102090705',
				'1',
				'1',
				'Month index, not zero pad'
			],
			[
				'N',
				'20120102090705',
				'01',
				'01',
				'Month index. Zero pad'
			],
			[
				'M',
				'20120102090705',
				'Jan',
				'Jan',
				'Month abbrev'
			],
			[
				'F',
				'20120102090705',
				'January',
				'January',
				'Full month'
			],
			[
				'xg',
				'20120102090705',
				'January',
				'January',
				'Genitive month name (same in EN)'
			],
			[
				'j',
				'20120102090705',
				'2',
				'2',
				'Day of month (not zero pad)'
			],
			[
				'd',
				'20120102090705',
				'02',
				'02',
				'Day of month (zero-pad)'
			],
			[
				'z',
				'20120102090705',
				'1',
				'1',
				'Day of year (zero-indexed)'
			],
			[
				'D',
				'20120102090705',
				'Mon',
				'Mon',
				'Day of week (abbrev)'
			],
			[
				'l',
				'20120102090705',
				'Monday',
				'Monday',
				'Full day of week'
			],
			[
				'N',
				'20120101090705',
				'7',
				'7',
				'Day of week (Mon=1, Sun=7)'
			],
			[
				'w',
				'20120101090705',
				'0',
				'0',
				'Day of week (Sun=0, Sat=6)'
			],
			[
				'N',
				'20120102090705',
				'1',
				'1',
				'Day of week'
			],
			[
				'a',
				'20120102090705',
				'am',
				'am',
				'am vs pm'
			],
			[
				'A',
				'20120102120000',
				'PM',
				'PM',
				'AM vs PM'
			],
			[
				'a',
				'20120102000000',
				'am',
				'am',
				'AM vs PM'
			],
			[
				'g',
				'20120102090705',
				'9',
				'9',
				'12 hour, not Zero'
			],
			[
				'h',
				'20120102090705',
				'09',
				'09',
				'12 hour, zero padded'
			],
			[
				'G',
				'20120102090705',
				'9',
				'9',
				'24 hour, not zero'
			],
			[
				'H',
				'20120102090705',
				'09',
				'09',
				'24 hour, zero'
			],
			[
				'H',
				'20120102110705',
				'11',
				'11',
				'24 hour, zero'
			],
			[
				'i',
				'20120102090705',
				'07',
				'07',
				'Minutes'
			],
			[
				's',
				'20120102090705',
				'05',
				'05',
				'seconds'
			],
			[
				'U',
				'20120102090705',
				'1325495225',
				'1325462825',
				'unix time'
			],
			[
				't',
				'20120102090705',
				'31',
				'31',
				'Days in current month'
			],
			[
				'c',
				'20120102090705',
				'2012-01-02T09:07:05+00:00',
				'2012-01-02T09:07:05+09:00',
				'ISO 8601 timestamp'
			],
			[
				'r',
				'20120102090705',
				'Mon, 02 Jan 2012 09:07:05 +0000',
				'Mon, 02 Jan 2012 09:07:05 +0900',
				'RFC 5322'
			],
			[
				'e',
				'20120102090705',
				'UTC',
				'Asia/Seoul',
				'Timezone identifier'
			],
			[
				'I',
				'19880602090705',
				'0',
				'1',
				'DST indicator'
			],
			[
				'O',
				'20120102090705',
				'+0000',
				'+0900',
				'Timezone offset'
			],
			[
				'P',
				'20120102090705',
				'+00:00',
				'+09:00',
				'Timezone offset with colon'
			],
			[
				'T',
				'20120102090705',
				'UTC',
				'KST',
				'Timezone abbreviation'
			],
			[
				'Z',
				'20120102090705',
				'0',
				'32400',
				'Timezone offset in seconds'
			],
			[
				'xmj xmF xmn xmY',
				'20120102090705',
				'7 Safar 2 1433',
				'7 Safar 2 1433',
				'Islamic'
			],
			[
				'xij xiF xin xiY',
				'20120102090705',
				'12 Dey 10 1390',
				'12 Dey 10 1390',
				'Iranian'
			],
			[
				'xjj xjF xjn xjY',
				'20120102090705',
				'7 Tevet 4 5772',
				'7 Tevet 4 5772',
				'Hebrew'
			],
			[
				'xjt',
				'20120102090705',
				'29',
				'29',
				'Hebrew number of days in month'
			],
			[
				'xjx',
				'20120102090705',
				'Tevet',
				'Tevet',
				'Hebrew genitive month name (No difference in EN)'
			],
			[
				'xkY',
				'20120102090705',
				'2555',
				'2555',
				'Thai year'
			],
			[
				'xoY',
				'20120102090705',
				'101',
				'101',
				'Minguo'
			],
			[
				'xtY',
				'20120102090705',
				'平成24',
				'平成24',
				'nengo'
			],
			[
				'xrxkYY',
				'20120102090705',
				'MMDLV2012',
				'MMDLV2012',
				'Roman numerals'
			],
			[
				'xhxjYY',
				'20120102090705',
				'ה\'תשע"ב2012',
				'ה\'תשע"ב2012',
				'Hebrew numberals'
			],
			[
				'xnY',
				'20120102090705',
				'2012',
				'2012',
				'Raw numerals (doesn\'t mean much in EN)'
			],
			[
				'[[Y "(yea"\\r)]] \\"xx\\"',
				'20120102090705',
				'[[2012 (year)]] "x"',
				'[[2012 (year)]] "x"',
				'Various escaping'
			],

		];
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
		return [
			[
				0,
				"0 bytes",
				"Zero bytes"
			],
			[
				1024,
				"1 KB",
				"1 kilobyte"
			],
			[
				1024 * 1024,
				"1 MB",
				"1,024 megabytes"
			],
			[
				1024 * 1024 * 1024,
				"1 GB",
				"1 gigabyte"
			],
			[
				pow( 1024, 4 ),
				"1 TB",
				"1 terabyte"
			],
			[
				pow( 1024, 5 ),
				"1 PB",
				"1 petabyte"
			],
			[
				pow( 1024, 6 ),
				"1 EB",
				"1,024 exabyte"
			],
			[
				pow( 1024, 7 ),
				"1 ZB",
				"1 zetabyte"
			],
			[
				pow( 1024, 8 ),
				"1 YB",
				"1 yottabyte"
			],
			// How big!? THIS BIG!
		];
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
		return [
			[
				0,
				"0 bps",
				"0 bits per second"
			],
			[
				999,
				"999 bps",
				"999 bits per second"
			],
			[
				1000,
				"1 kbps",
				"1 kilobit per second"
			],
			[
				1000 * 1000,
				"1 Mbps",
				"1 megabit per second"
			],
			[
				pow( 10, 9 ),
				"1 Gbps",
				"1 gigabit per second"
			],
			[
				pow( 10, 12 ),
				"1 Tbps",
				"1 terabit per second"
			],
			[
				pow( 10, 15 ),
				"1 Pbps",
				"1 petabit per second"
			],
			[
				pow( 10, 18 ),
				"1 Ebps",
				"1 exabit per second"
			],
			[
				pow( 10, 21 ),
				"1 Zbps",
				"1 zetabit per second"
			],
			[
				pow( 10, 24 ),
				"1 Ybps",
				"1 yottabit per second"
			],
			[
				pow( 10, 27 ),
				"1,000 Ybps",
				"1,000 yottabits per second"
			],
		];
	}

	/**
	 * @dataProvider provideFormatDuration
	 * @covers Language::formatDuration
	 */
	public function testFormatDuration( $duration, $expected, $intervals = [] ) {
		$this->assertEquals(
			$expected,
			$this->getLang()->formatDuration( $duration, $intervals ),
			"formatDuration('$duration'): $expected"
		);
	}

	public static function provideFormatDuration() {
		return [
			[
				0,
				'0 seconds',
			],
			[
				1,
				'1 second',
			],
			[
				2,
				'2 seconds',
			],
			[
				60,
				'1 minute',
			],
			[
				2 * 60,
				'2 minutes',
			],
			[
				3600,
				'1 hour',
			],
			[
				2 * 3600,
				'2 hours',
			],
			[
				24 * 3600,
				'1 day',
			],
			[
				2 * 86400,
				'2 days',
			],
			[
				// ( 365 + ( 24 * 3 + 25 ) / 400 ) * 86400 = 31556952
				( 365 + ( 24 * 3 + 25 ) / 400.0 ) * 86400,
				'1 year',
			],
			[
				2 * 31556952,
				'2 years',
			],
			[
				10 * 31556952,
				'1 decade',
			],
			[
				20 * 31556952,
				'2 decades',
			],
			[
				100 * 31556952,
				'1 century',
			],
			[
				200 * 31556952,
				'2 centuries',
			],
			[
				1000 * 31556952,
				'1 millennium',
			],
			[
				2000 * 31556952,
				'2 millennia',
			],
			[
				9001,
				'2 hours, 30 minutes and 1 second'
			],
			[
				3601,
				'1 hour and 1 second'
			],
			[
				31556952 + 2 * 86400 + 9000,
				'1 year, 2 days, 2 hours and 30 minutes'
			],
			[
				42 * 1000 * 31556952 + 42,
				'42 millennia and 42 seconds'
			],
			[
				60,
				'60 seconds',
				[ 'seconds' ],
			],
			[
				61,
				'61 seconds',
				[ 'seconds' ],
			],
			[
				1,
				'1 second',
				[ 'seconds' ],
			],
			[
				31556952 + 2 * 86400 + 9000,
				'1 year, 2 days and 150 minutes',
				[ 'years', 'days', 'minutes' ],
			],
			[
				42,
				'0 days',
				[ 'years', 'days' ],
			],
			[
				31556952 + 2 * 86400 + 9000,
				'1 year, 2 days and 150 minutes',
				[ 'minutes', 'days', 'years' ],
			],
			[
				42,
				'0 days',
				[ 'days', 'years' ],
			],
		];
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
		return [
			[ "" ],
			[ "United States of America" ], // 7bit ASCII
			[ rawurldecode( "S%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e" ) ],
			[
				rawurldecode(
					"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn"
				)
			],
			// The following two data sets come from bug 36839. They fail if checkTitleEncoding uses a regexp to test for
			// valid UTF-8 encoding and the pcre.recursion_limit is low (like, say, 1024). They succeed if checkTitleEncoding
			// uses mb_check_encoding for its test.
			[
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
			],
			[
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
			]
		];
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
		return [
			[ 1, 'I' ],
			[ 2, 'II' ],
			[ 3, 'III' ],
			[ 4, 'IV' ],
			[ 5, 'V' ],
			[ 6, 'VI' ],
			[ 7, 'VII' ],
			[ 8, 'VIII' ],
			[ 9, 'IX' ],
			[ 10, 'X' ],
			[ 20, 'XX' ],
			[ 30, 'XXX' ],
			[ 40, 'XL' ],
			[ 49, 'XLIX' ],
			[ 50, 'L' ],
			[ 60, 'LX' ],
			[ 70, 'LXX' ],
			[ 80, 'LXXX' ],
			[ 90, 'XC' ],
			[ 99, 'XCIX' ],
			[ 100, 'C' ],
			[ 200, 'CC' ],
			[ 300, 'CCC' ],
			[ 400, 'CD' ],
			[ 500, 'D' ],
			[ 600, 'DC' ],
			[ 700, 'DCC' ],
			[ 800, 'DCCC' ],
			[ 900, 'CM' ],
			[ 999, 'CMXCIX' ],
			[ 1000, 'M' ],
			[ 1989, 'MCMLXXXIX' ],
			[ 2000, 'MM' ],
			[ 3000, 'MMM' ],
			[ 4000, 'MMMM' ],
			[ 5000, 'MMMMM' ],
			[ 6000, 'MMMMMM' ],
			[ 7000, 'MMMMMMM' ],
			[ 8000, 'MMMMMMMM' ],
			[ 9000, 'MMMMMMMMM' ],
			[ 9999, 'MMMMMMMMMCMXCIX' ],
			[ 10000, 'MMMMMMMMMM' ],
		];
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
		return [
			[ -1, -1 ],
			[ 0, 0 ],
			[ 1, "א'" ],
			[ 2, "ב'" ],
			[ 3, "ג'" ],
			[ 4, "ד'" ],
			[ 5, "ה'" ],
			[ 6, "ו'" ],
			[ 7, "ז'" ],
			[ 8, "ח'" ],
			[ 9, "ט'" ],
			[ 10, "י'" ],
			[ 11, 'י"א' ],
			[ 14, 'י"ד' ],
			[ 15, 'ט"ו' ],
			[ 16, 'ט"ז' ],
			[ 17, 'י"ז' ],
			[ 20, "כ'" ],
			[ 21, 'כ"א' ],
			[ 30, "ל'" ],
			[ 40, "מ'" ],
			[ 50, "נ'" ],
			[ 60, "ס'" ],
			[ 70, "ע'" ],
			[ 80, "פ'" ],
			[ 90, "צ'" ],
			[ 99, 'צ"ט' ],
			[ 100, "ק'" ],
			[ 101, 'ק"א' ],
			[ 110, 'ק"י' ],
			[ 200, "ר'" ],
			[ 300, "ש'" ],
			[ 400, "ת'" ],
			[ 500, 'ת"ק' ],
			[ 800, 'ת"ת' ],
			[ 1000, "א' אלף" ],
			[ 1001, "א'א'" ],
			[ 1012, "א'י\"ב" ],
			[ 1020, "א'ך'" ],
			[ 1030, "א'ל'" ],
			[ 1081, "א'פ\"א" ],
			[ 2000, "ב' אלפים" ],
			[ 2016, "ב'ט\"ז" ],
			[ 3000, "ג' אלפים" ],
			[ 4000, "ד' אלפים" ],
			[ 4904, "ד'תתק\"ד" ],
			[ 5000, "ה' אלפים" ],
			[ 5680, "ה'תר\"ף" ],
			[ 5690, "ה'תר\"ץ" ],
			[ 5708, "ה'תש\"ח" ],
			[ 5720, "ה'תש\"ך" ],
			[ 5740, "ה'תש\"ם" ],
			[ 5750, "ה'תש\"ן" ],
			[ 5775, "ה'תשע\"ה" ],
		];
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
		return [
			[ 'plural', 0, [
				'singular', 'plural'
			] ],
			[ 'explicit zero', 0, [
				'0=explicit zero', 'singular', 'plural'
			] ],
			[ 'explicit one', 1, [
				'singular', 'plural', '1=explicit one',
			] ],
			[ 'singular', 1, [
				'singular', 'plural', '0=explicit zero',
			] ],
			[ 'plural', 3, [
				'0=explicit zero', '1=explicit one', 'singular', 'plural'
			] ],
			[ 'explicit eleven', 11, [
				'singular', 'plural', '11=explicit eleven',
			] ],
			[ 'plural', 12, [
				'singular', 'plural', '11=explicit twelve',
			] ],
			[ 'plural', 12, [
				'singular', 'plural', '=explicit form',
			] ],
			[ 'other', 2, [
				'kissa=kala', '1=2=3', 'other',
			] ],
			[ '', 2, [
				'0=explicit zero', '1=explicit one',
			] ],
		];
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
		return [
			[ '2 hours', '2 hours', 'simple data from ipboptions' ],
			[ 'indefinite', 'infinite', 'infinite from ipboptions' ],
			[ 'indefinite', 'infinity', 'alternative infinite from ipboptions' ],
			[ 'indefinite', 'indefinite', 'another alternative infinite from ipboptions' ],
			[ [ 'formatDuration', 1023 * 60 * 60 ], '1023 hours', 'relative' ],
			[ [ 'formatDuration', -1023 ], '-1023 seconds', 'negative relative' ],
			[ [ 'formatDuration', 0 ], 'now', 'now' ],
			[
				[ 'timeanddate', '20120102070000' ],
				'2012-1-1 7:00 +1 day',
				'mixed, handled as absolute'
			],
			[ [ 'timeanddate', '19910203040506' ], '1991-2-3 4:05:06', 'absolute' ],
			[ [ 'timeanddate', '19700101000000' ], '1970-1-1 0:00:00', 'absolute at epoch' ],
			[ [ 'timeanddate', '19691231235959' ], '1969-12-31 23:59:59', 'time before epoch' ],
			[ 'dummy', 'dummy', 'return garbage as is' ],
		];
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
		return [
			[ 'de', 377.01 ],
			[ 'fa', 334 ],
			[ 'fa', 382.772 ],
			[ 'ar', 1844 ],
			[ 'lzh', 3731 ],
			[ 'zh-classical', 7432 ]
		];
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
		return [
			[ -1, '-1' ],
			[ 10, '10' ],
			[ 100, '100' ],
			[ 1000, '1,000' ],
			[ 10000, '10,000' ],
			[ 100000, '100,000' ],
			[ 1000000, '1,000,000' ],
			[ -1.0001, '-1.0001' ],
			[ 1.0001, '1.0001' ],
			[ 10.0001, '10.0001' ],
			[ 100.0001, '100.0001' ],
			[ 1000.0001, '1,000.0001' ],
			[ 10000.0001, '10,000.0001' ],
			[ 100000.0001, '100,000.0001' ],
			[ 1000000.0001, '1,000,000.0001' ],
			[ '200000000000000000000', '200,000,000,000,000,000,000' ],
			[ '-200000000000000000000', '-200,000,000,000,000,000,000' ],
		];
	}

	/**
	 * @covers Language::listToText
	 */
	public function testListToText() {
		$lang = $this->getLang();
		$and = $lang->getMessageFromDB( 'and' );
		$s = $lang->getMessageFromDB( 'word-separator' );
		$c = $lang->getMessageFromDB( 'comma-separator' );

		$this->assertEquals( '', $lang->listToText( [] ) );
		$this->assertEquals( 'a', $lang->listToText( [ 'a' ] ) );
		$this->assertEquals( "a{$and}{$s}b", $lang->listToText( [ 'a', 'b' ] ) );
		$this->assertEquals( "a{$c}b{$and}{$s}c", $lang->listToText( [ 'a', 'b', 'c' ] ) );
		$this->assertEquals( "a{$c}b{$c}c{$and}{$s}d", $lang->listToText( [ 'a', 'b', 'c', 'd' ] ) );
	}

	/**
	 * @dataProvider provideIsSupportedLanguage
	 * @covers Language::isSupportedLanguage
	 */
	public function testIsSupportedLanguage( $code, $expected, $comment ) {
		$this->assertEquals( $expected, Language::isSupportedLanguage( $code ), $comment );
	}

	public static function provideIsSupportedLanguage() {
		return [
			[ 'en', true, 'is supported language' ],
			[ 'fi', true, 'is supported language' ],
			[ 'bunny', false, 'is not supported language' ],
			[ 'FI', false, 'is not supported language, input should be in lower case' ],
		];
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
		return [
			[ 'zh-cn', 'zh', 'zh is the parent language of zh-cn' ],
			[ 'zh', 'zh', 'zh is defined as the parent language of zh, '
				. 'because zh converter can convert zh-cn to zh' ],
			[ 'zh-invalid', null, 'do not be fooled by arbitrarily composed language codes' ],
			[ 'en-gb', null, 'en does not have converter' ],
			[ 'en', null, 'en does not have converter. Although FakeConverter '
					. 'handles en -> en conversion but it is useless' ],
		];
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
		return [
			[
				'zh',
				[
					'文件' => NS_FILE,
					'檔案' => NS_FILE,
				],
			],
		];
	}
}
