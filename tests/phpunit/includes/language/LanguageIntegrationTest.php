<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Language
 * @covers \MediaWiki\Language\Language
 * @covers \MediaWiki\Languages\LanguageNameUtils
 */
class LanguageIntegrationTest extends LanguageClassesTestCase {
	use DummyServicesTrait;
	use LanguageNameUtilsTestTrait;

	private function newLanguage( $class = Language::class, $code = 'en' ) {
		// Needed to support the setMwGlobals calls for the various tests, but this should
		// probably be changed to have the configuration injected into this method instead
		// at some point
		$config = $this->getServiceContainer()->getMainConfig();
		return new $class(
			$code,
			$this->createNoOpMock( NamespaceInfo::class ),
			$this->createNoOpMock( LocalisationCache::class ),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( LanguageFallback::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createHookContainer(),
			$config
		);
	}

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );
	}

	public function testLanguageConvertDoubleWidthToSingleWidth() {
		$this->assertSame(
			"0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
			$this->getLang()->normalizeForSearch(
				"０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
			),
			'convertDoubleWidth() with the full alphabet and digits'
		);
	}

	/**
	 * @dataProvider provideFormattableTimes
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
				'avoidhours',
				'3 d',
				'formatTimePeriod() rounding (>48h), avoidhours'
			],
			[
				259199.55,
				[ 'avoid' => 'avoidhours', 'noabbrevs' => true ],
				'3 days',
				'formatTimePeriod() rounding (>48h), avoidhours'
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

	public function testTruncateForDatabase() {
		$this->assertEquals(
			"XXX",
			$this->getLang()->truncateForDatabase( "1234567890", 0, 'XXX' ),
			'truncate prefix, len 0, small ellipsis'
		);

		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncateForDatabase( "1234567890", 8, 'XXX' ),
			'truncate prefix, small ellipsis'
		);

		$this->assertSame(
			"123456789",
			$this->getLang()->truncateForDatabase( "123456789", 5, 'XXXXXXXXXXXXXXX' ),
			'truncate prefix, large ellipsis'
		);

		$this->assertEquals(
			"XXX67890",
			$this->getLang()->truncateForDatabase( "1234567890", -8, 'XXX' ),
			'truncate suffix, small ellipsis'
		);

		$this->assertSame(
			"123456789",
			$this->getLang()->truncateForDatabase( "123456789", -5, 'XXXXXXXXXXXXXXX' ),
			'truncate suffix, large ellipsis'
		);
		$this->assertEquals(
			"123XXX",
			$this->getLang()->truncateForDatabase( "123                ", 9, 'XXX' ),
			'truncate prefix, with spaces'
		);
		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncateForDatabase( "12345            8", 11, 'XXX' ),
			'truncate prefix, with spaces and non-space ending'
		);
		$this->assertEquals(
			"XXX234",
			$this->getLang()->truncateForDatabase( "1              234", -8, 'XXX' ),
			'truncate suffix, with spaces'
		);
		$this->assertEquals(
			"12345XXX",
			$this->getLang()->truncateForDatabase( "1234567890", 5, 'XXX', false ),
			'truncate without adjustment'
		);
		$this->assertEquals(
			"泰乐菌...",
			$this->getLang()->truncateForDatabase( "泰乐菌素123456789", 11, '...', false ),
			'truncate does not chop Unicode characters in half'
		);
		$this->assertEquals(
			"\n泰乐菌...",
			$this->getLang()->truncateForDatabase( "\n泰乐菌素123456789", 12, '...', false ),
			'truncate does not chop Unicode characters in half if there is a preceding newline'
		);
	}

	/**
	 * @dataProvider provideTruncateData
	 */
	public function testTruncateForVisual(
		$expected, $string, $length, $ellipsis = '...', $adjustLength = true
	) {
		$this->assertEquals(
			$expected,
			$this->getLang()->truncateForVisual( $string, $length, $ellipsis, $adjustLength )
		);
	}

	/**
	 * @return array Format is ($expected, $string, $length, $ellipsis, $adjustLength)
	 */
	public static function provideTruncateData() {
		return [
			[ "XXX", "тестирам да ли ради", 0, "XXX" ],
			[ "testnXXX", "testni scenarij", 8, "XXX" ],
			[ "حالة اختبار", "حالة اختبار", 5, "XXXXXXXXXXXXXXX" ],
			[ "XXXедент", "прецедент", -8, "XXX" ],
			[ "XXപിൾ", "ആപ്പിൾ", -5, "XX" ],
			[ "神秘XXX", "神秘                ", 9, "XXX" ],
			[ "ΔημιουργXXX", "Δημιουργία           Σύμπαντος", 11, "XXX" ],
			[ "XXXの家です", "地球は私たちの唯               の家です", -8, "XXX" ],
			[ "زندگیXXX", "زندگی زیباست", 6, "XXX", false ],
			[ "ცხოვრება...", "ცხოვრება არის საოცარი", 8, "...", false ],
			[ "\nທ່ານ...", "\nທ່ານບໍ່ຮູ້ຫນັງສື", 5, "...", false ],
		];
	}

	/**
	 * @dataProvider provideHTMLTruncateData
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
			[ 10, '***',
				'<p><font style="font-weight:bold;">123456789</font',
				'<p><font style="font-weight:bold;">123456789</font</p>',
			],
		];
	}

	/**
	 * Test too short timestamp
	 */
	public function testSprintfDateTooShortTimestamp() {
		$this->expectException( InvalidArgumentException::class );
		$this->getLang()->sprintfDate( 'xiY', '1234567890123' );
	}

	/**
	 * Test too long timestamp
	 */
	public function testSprintfDateTooLongTimestamp() {
		$this->expectException( InvalidArgumentException::class );
		$this->getLang()->sprintfDate( 'xiY', '123456789012345' );
	}

	/**
	 * Test too short timestamp
	 */
	public function testSprintfDateNotAllDigitTimestamp() {
		$this->expectException( InvalidArgumentException::class );
		$this->getLang()->sprintfDate( 'xiY', '-1234567890123' );
	}

	/**
	 * @dataProvider provideSprintfDateSamples
	 */
	public function testSprintfDate( $format, $ts, $expected, $msg ) {
		$ttl = null;
		$this->assertSame(
			$expected,
			$this->getLang()->sprintfDate( $format, $ts, null, $ttl ),
			"sprintfDate('$format', '$ts'): $msg"
		);
		if ( $ttl ) {
			$dt = new DateTime( $ts );
			$lastValidTS = $dt->add( new DateInterval( 'PT' . ( $ttl - 1 ) . 'S' ) )->format( 'YmdHis' );
			$this->assertSame(
				$expected,
				$this->getLang()->sprintfDate( $format, $lastValidTS, null ),
				"sprintfDate('$format', '$ts'): TTL $ttl too high (output was different at $lastValidTS)"
			);
		} else {
			// advance the time enough to make all of the possible outputs different (except possibly L)
			$dt = new DateTime( $ts );
			$newTS = $dt->add( new DateInterval( 'P1Y1M8DT13H1M1S' ) )->format( 'YmdHis' );
			$this->assertSame(
				$expected,
				$this->getLang()->sprintfDate( $format, $newTS, null ),
				"sprintfDate('$format', '$ts'): Missing TTL (output was different at $newTS)"
			);
		}
	}

	/**
	 * sprintfDate should always use UTC when no zone is given.
	 * @dataProvider provideSprintfDateSamples
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

	/**
	 * sprintfDate should only calculate a TTL if the caller is going to use it.
	 */
	public function testSprintfDateNoTtlIfNotNeeded() {
		$noTtl = 'unused'; // Value used to represent that the caller didn't pass a variable in.
		$ttl = null;
		$this->getLang()->sprintfDate( 'YmdHis', wfTimestampNow(), null, $noTtl );
		$this->getLang()->sprintfDate( 'YmdHis', wfTimestampNow(), null, $ttl );

		$this->assertSame(
			'unused',
			$noTtl,
			'If the caller does not set the $ttl variable, do not compute it.'
		);
		$this->assertIsInt( $ttl, 'TTL should have been computed.' );
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
				'01',
				'01',
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
				'1',
				'1',
				'Day of the week'
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
				'xkY',
				'19410101090705',
				'2484',
				'2484',
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
				'18660101000000',
				'西暦1866',
				'西暦1866',
				'nengo - before meiji'
			],
			[
				'xtY',
				'18670101000000',
				'西暦1867',
				'西暦1867',
				'nengo - before meiji'
			],
			[
				'xtY',
				'18721231235959',
				'西暦1872',
				'西暦1872',
				'nengo - meiji, but Lunisolar calendar'
			],
			[
				'xtY',
				'18730101000000',
				'明治6',
				'明治6',
				'nengo - meiji 6th'
			],
			[
				'xtY',
				'19120729235959',
				'明治45',
				'明治45',
				'nengo - meiji 45th last day'
			],
			[
				'xtY',
				'19120730000000',
				'大正元',
				'大正元',
				'nengo - taisho first day'
			],
			[
				'xtY',
				'19130101000000',
				'大正2',
				'大正2',
				'nengo - taisho 2nd'
			],
			[
				'xtY',
				'19261224235959',
				'大正15',
				'大正15',
				'nengo - taisho last day'
			],
			[
				'xtY',
				'19261225000000',
				'昭和元',
				'昭和元',
				'nengo - first day of Showa'
			],
			[
				'xtY',
				'19270101000000',
				'昭和2',
				'昭和2',
				'nengo - second year of Showa'
			],
			[
				'xtY',
				'19890107235959',
				'昭和64',
				'昭和64',
				'nengo - last day of Showa'
			],
			[
				'xtY',
				'19890108000000',
				'平成元',
				'平成元',
				'nengo - first day of Heisei'
			],
			[
				'xtY',
				'19900101000000',
				'平成2',
				'平成2',
				'nengo - second year of Heisei'
			],
			[
				'xtY',
				'20190430235959',
				'平成31',
				'平成31',
				'nengo - last day of Heisei'
			],
			[
				'xtY',
				'20190501000000',
				'令和元',
				'令和元',
				'nengo - first day of Reiwa'
			],
			[
				'xtY',
				'20200501000000',
				'令和2',
				'令和2',
				'nengo - second year of Reiwa'
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
				"1 megabyte"
			],
			[
				1024 * 1024 * 1024,
				"1 GB",
				"1 gigabyte"
			],
			[
				1024 ** 4,
				"1 TB",
				"1 terabyte"
			],
			[
				1024 ** 5,
				"1 PB",
				"1 petabyte"
			],
			[
				1024 ** 6,
				"1 EB",
				"1 exabyte"
			],
			[
				1024 ** 7,
				"1 ZB",
				"1 zettabyte"
			],
			[
				1024 ** 8,
				"1 YB",
				"1 yottabyte"
			],
			[
				1024 ** 9,
				"1 RB",
				"1 ronnabyte"
			],
			[
				1024 ** 10,
				"1 QB",
				"1 quettabyte"
			],
			[
				1024 ** 11,
				"1,024 QB",
				"1,024 quettabytes"
			],
			// How big!? THIS BIG!
		];
	}

	/**
	 * @dataProvider provideFormatBitrate
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
				10 ** 9,
				"1 Gbps",
				"1 gigabit per second"
			],
			[
				10 ** 12,
				"1 Tbps",
				"1 terabit per second"
			],
			[
				10 ** 15,
				"1 Pbps",
				"1 petabit per second"
			],
			[
				10 ** 18,
				"1 Ebps",
				"1 exabit per second"
			],
			[
				10 ** 21,
				"1 Zbps",
				"1 zettabit per second"
			],
			[
				10 ** 24,
				"1 Ybps",
				"1 yottabit per second"
			],
			[
				10 ** 27,
				"1 Rbps",
				"1 ronnabits per second"
			],
			[
				10 ** 30,
				"1 Qbps",
				"1 quettabit per second"
			],
			[
				10 ** 33,
				"1,000 Qbps",
				"1,000 quettabits per second"
			],
		];
	}

	/**
	 * @dataProvider provideFormatDuration
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
				365.2425 * 24 * 3600 / 12,
				'1 month',
				[ 'months', 'days' ]
			],
			[
				365.2425 * 24 * 3600 / 12 * 2,
				'2 months',
				[ 'months', 'days' ]
			],
			[
				( 365.2425 * 24 * 3600 / 12 * 2 ) + 24 * 3600,
				'2 months and 1 day',
				[ 'months', 'days' ]
			],
			[
				// ( 365 + ( 24 * 3 + 25 ) / 400 ) * 86400 = 31556952
				( 365 + ( 24 * 3 + 25 ) / 400.0 ) * 86400,
				'1 year',
				[ 'months', 'years' ]
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
			[
				( new DateTime( '2025-05-03 20:00:00' ) )->getTimestamp() - ( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				'11 months',
				[ 'months' ],
			],
			[
				( new DateTime( '2025-05-03 20:00:00' ) )->getTimestamp() - ( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				'11 months, 30 days, 4 hours, 39 minutes and 54 seconds',
				[ 'years', 'months', 'days', 'hours', 'minutes', 'seconds' ],
			],
		];
	}

	/**
	 * @dataProvider provideFormatDurationBetweenTimestamps
	 */
	public function testFormatDurationBetweenTimestamps(
		int $timestamp1,
		int $timestamp2,
		?int $precision,
		string $expected
	): void {
		$this->assertSame(
			$expected,
			$this->getLang()->formatDurationBetweenTimestamps( $timestamp1, $timestamp2, $precision )
		);
		$this->assertSame(
			$expected,
			$this->getLang()->formatDurationBetweenTimestamps( $timestamp2, $timestamp1, $precision )
		);
	}

	public static function provideFormatDurationBetweenTimestamps(): array {
		return [
			// most test cases ported from provideFormatDuration()
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'0 seconds',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 20:00:01' ) )->getTimestamp(),
				null,
				'1 second',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 20:00:02' ) )->getTimestamp(),
				null,
				'2 seconds',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 20:01:00' ) )->getTimestamp(),
				null,
				'1 minute',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 20:02:00' ) )->getTimestamp(),
				null,
				'2 minutes',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 21:00:00' ) )->getTimestamp(),
				null,
				'1 hour',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-03 22:00:00' ) )->getTimestamp(),
				null,
				'2 hours',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-04 20:00:00' ) )->getTimestamp(),
				null,
				'1 day',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-05-05 20:00:00' ) )->getTimestamp(),
				null,
				'2 days',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-06-03 20:00:00' ) )->getTimestamp(),
				2,
				'1 month',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-07-03 20:00:00' ) )->getTimestamp(),
				2,
				'2 months',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-07-04 20:00:00' ) )->getTimestamp(),
				2,
				'2 months and 1 day',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2025-05-03 20:00:00' ) )->getTimestamp(),
				2,
				'1 year',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2026-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'2 years',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2034-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'1 decade',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2044-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'2 decades',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2124-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'1 century',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2224-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'2 centuries',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '3024-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'1 millennium',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '4024-05-03 20:00:00' ) )->getTimestamp(),
				null,
				'2 millennia',
			],
			[
				0,
				9001,
				null,
				'2 hours, 30 minutes and 1 second',
			],
			[
				0,
				3601,
				null,
				'1 hour and 1 second',
			],
			[
				( new DateTime( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2025-05-05 22:30:00' ) )->getTimestamp(),
				null,
				'1 year, 2 days, 2 hours and 30 minutes',
			],
			[
				( new DateTimeImmutable( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTimeImmutable() )->setDate( 44024, 05, 03 )->setTime( 20, 0, 42 )->getTimestamp(),
				null,
				'42 millennia and 42 seconds',
			],
			[
				0,
				60,
				null,
				'1 minute',
			],
			[
				0,
				61,
				null,
				'1 minute and 1 second',
			],
			[
				( new DateTimeImmutable( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTimeImmutable() )->setDate( 2025, 05, 05 )->setTime( 22, 30, 0 )->getTimestamp(),
				null,
				'1 year, 2 days, 2 hours and 30 minutes',
			],
			[
				( new DateTimeImmutable( '2024-05-03 20:00:00' ) )->getTimestamp(),
				( new DateTimeImmutable( '2024-10-09 20:15:37' ) )->getTimestamp(),
				1,
				'5 months',
			],
			[
				( new DateTime( '2022-01-01 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2022-01-01 12:30:00' ) )->getTimestamp(),
				2,
				'2 hours and 30 minutes',
			],
			[
				( new DateTime( '2022-01-01 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2022-01-02 12:30:00' ) )->getTimestamp(),
				3,
				'1 day, 2 hours and 30 minutes',
			],
			[
				( new DateTime( '2022-01-01 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2022-01-01 10:30:27' ) )->getTimestamp(),
				1,
				'30 minutes',
			],
			[
				( new DateTime( '2024-05-03 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2025-05-03 10:00:00' ) )->getTimestamp(),
				6,
				'1 year',
			],
			[
				( new DateTime( '2024-01-28 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-03-01 10:00:00' ) )->getTimestamp(),
				4,
				'1 month and 2 days',
			],
			[
				( new DateTime( '2023-01-28 10:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-03-01 10:00:00' ) )->getTimestamp(),
				6,
				'1 month and 1 day',
			],
			[
				( new DateTime( '2023-01-29 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-02-28 20:00:00' ) )->getTimestamp(),
				6,
				'30 days',
			],
			[
				( new DateTime( '2023-01-29 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-03-01 20:00:00' ) )->getTimestamp(),
				6,
				'1 month',
			],
			[
				( new DateTime( '2023-01-30 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-03-01 20:00:00' ) )->getTimestamp(),
				6,
				'30 days',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-03-01 20:00:00' ) )->getTimestamp(),
				6,
				'29 days',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 20:00:01' ) )->getTimestamp(),
				6,
				'1 second',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 20:00:02' ) )->getTimestamp(),
				6,
				'2 seconds',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 20:01:00' ) )->getTimestamp(),
				6,
				'1 minute',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 20:02:00' ) )->getTimestamp(),
				6,
				'2 minutes',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 21:00:00' ) )->getTimestamp(),
				6,
				'1 hour',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-01-31 22:00:00' ) )->getTimestamp(),
				6,
				'2 hours',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-02-01 20:00:00' ) )->getTimestamp(),
				6,
				'1 day',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-02-02 20:00:00' ) )->getTimestamp(),
				6,
				'2 days',
			],
			[
				( new DateTime( '2023-03-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-04-31 20:00:00' ) )->getTimestamp(),
				6,
				'1 month',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2023-03-31 20:00:00' ) )->getTimestamp(),
				6,
				'2 months',
			],
			[
				( new DateTime( '2023-01-31 20:00:00' ) )->getTimestamp(),
				( new DateTime( '2024-01-31 20:00:00' ) )->getTimestamp(),
				6,
				'1 year',
			],
			[
				( new DateTime( '2023-01-27 15:00:00' ) )->getTimestamp(),
				( new DateTime( '2025-04-31 20:06:00' ) )->getTimestamp(),
				5,
				'2 years, 3 months, 4 days, 5 hours and 6 minutes',
			],
			[
				( new DateTime( '2023-01-31 15:00:00' ) )->getTimestamp(),
				( new DateTime( '3025-04-31 20:06:07' ) )->getTimestamp(),
				7,
				'1 millennium, 2 years, 3 months, 5 hours, 6 minutes and 7 seconds',
			],
			[
				( new DateTime( '2023-01-28 20:00:00' ) )->getTimestamp(),
				( new DateTime( '4030-05-31 22:01:14' ) )->getTimestamp(),
				9,
				'2 millennia, 7 years, 4 months, 3 days, 2 hours, 1 minute and 14 seconds',
			],
		];
	}

	/**
	 * Check interval across a DST boundary in the system default timezone
	 * (regression test)
	 */
	public function testFormatDurationBetweenTimestampsAcrossDST() {
		$oldTz = date_default_timezone_get();
		date_default_timezone_set( 'Australia/Melbourne' );
		try {
			$ts1 = wfTimestamp( TS_UNIX, '20250115001810' );
			$ts2 = wfTimestamp( TS_UNIX, '20250415001810' );
			$result = $this->getLang()->formatDurationBetweenTimestamps( $ts1, $ts2 );
			$this->assertSame( '3 months', $result );
		} finally {
			date_default_timezone_set( $oldTz );
		}
	}

	/**
	 * @dataProvider provideCheckTitleEncodingData
	 */
	public function testCheckTitleEncoding( $s ) {
		$this->assertEquals(
			$s,
			$this->getLang()->checkTitleEncoding( $s ),
			"checkTitleEncoding('$s')"
		);
	}

	public static function provideCheckTitleEncodingData() {
		return [
			[ "" ],
			[ "United States of America" ], // 7bit ASCII
			[ rawurldecode( "S%C3%A9rie%20t%C3%A9l%C3%A9vis%C3%A9e" ) ],
			[
				rawurldecode(
					"Acteur%7CAlbert%20Robbins%7CAnglais%7CAnn%20Donahue%7CAnthony%20E.%20Zuiker%7CCarol%20Mendelsohn"
				)
			],
			// The following two data sets come from T38839. They fail if checkTitleEncoding uses a regexp to test for
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
		// phpcs:enable
	}

	/**
	 * @dataProvider provideRomanNumeralsData
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

	public function testEmbedBidi() {
		$lre = "\u{202A}"; // U+202A LEFT-TO-RIGHT EMBEDDING
		$rle = "\u{202B}"; // U+202B RIGHT-TO-LEFT EMBEDDING
		$pdf = "\u{202C}"; // U+202C POP DIRECTIONAL FORMATTING
		$lang = $this->getLang();
		$this->assertSame(
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
	 * @dataProvider provideTranslateBlockExpiry
	 */
	public function testTranslateBlockExpiry( $expectedData, $str, $now, $desc ) {
		$lang = $this->getLang();
		if ( is_array( $expectedData ) ) {
			$func = array_shift( $expectedData );
			$expected = $lang->$func( ...$expectedData );
		} else {
			$expected = $expectedData;
		}
		// HACK:
		date_default_timezone_set( 'UTC' );
		$this->assertSame( $expected, $lang->translateBlockExpiry( $str, null, $now ), $desc );
	}

	public static function provideTranslateBlockExpiry() {
		return [
			[ '2 hours', '2 hours', 0, 'simple data from ipboptions' ],
			[ 'indefinite', 'infinite', 0, 'infinite from ipboptions' ],
			[ 'indefinite', 'infinity', 0, 'alternative infinite from ipboptions' ],
			[ 'indefinite', 'indefinite', 0, 'another alternative infinite from ipboptions' ],
			[ [ 'formatDurationBetweenTimestamps', 0, 1023 * 60 * 60 ], '1023 hours', 0, 'relative' ],
			[ [ 'formatDurationBetweenTimestamps', 0, -1023 ], '-1023 seconds', 0, 'negative relative' ],
			[
				[ 'formatDurationBetweenTimestamps', 665553906, 665553906 + ( 1023 * 60 * 60 ) ],
				'1023 hours',
				wfTimestamp( TS_UNIX, '1991-02-03 04:05:06' ),
				'relative with initial timestamp'
			],
			[ [ 'formatDurationBetweenTimestamps', 0, 0 ], 'now', 0, 'now' ],
			[
				[ 'timeanddate', '20120102070000' ],
				'2012-1-1 7:00 +1 day',
				0,
				'mixed, handled as absolute'
			],
			[ [ 'timeanddate', '19910203040506' ], '1991-2-3 4:05:06', 0, 'absolute' ],
			[ [ 'timeanddate', '19700101000000' ], '1970-1-1 0:00:00', 0, 'absolute at epoch' ],
			[ [ 'timeanddate', '19691231235959' ], '1969-12-31 23:59:59', 0, 'time before epoch' ],
			[
				[ 'timeanddate', '19910910000000' ],
				'10 september',
				wfTimestamp( TS_UNIX, '19910203040506' ),
				'partial'
			],
			[ 'dummy', 'dummy', 0, 'return garbage as is' ],
			'Relative timestamp that causes negative number from strtotime' => [
				'-0.000000000000000001 seconds',
				'-0.000000000000000001 seconds',
				wfTimestamp( TS_UNIX, '20200524200807' ),
				'Relative timestamp that fails to be parsed by strtotime should be returned without modification'
			],
		];
	}

	/**
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum(
		$translateNumerals, $langCode, $number, $noSeparators, $expected
	) {
		$this->hideDeprecated( 'Language::formatNum with a non-numeric string' );
		$this->overrideConfigValue( MainConfigNames::TranslateNumerals, $translateNumerals );
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( $langCode );
		if ( $noSeparators ) {
			$formattedNum = $lang->formatNumNoSeparators( $number );
		} else {
			$formattedNum = $lang->formatNum( $number );
		}
		$this->assertIsString( $formattedNum );
		$this->assertEquals( $expected, $formattedNum );
	}

	public static function provideFormatNum() {
		return [
			[ true, 'en', 100, false, '100' ],
			[ true, 'en', 101, true, '101' ],
			[ false, 'en', 103, false, '103' ],
			[ false, 'en', 104, true, '104' ],
			[ true, 'en', '105', false, '105' ],
			[ true, 'en', '106', true, '106' ],
			[ false, 'en', '107', false, '107' ],
			[ false, 'en', '108', true, '108' ],
			[ true, 'en', -1, false, '−1' ],
			[ true, 'en', 10, false, '10' ],
			[ true, 'en', 100, false, '100' ],
			[ true, 'en', 1000, false, '1,000' ],
			[ true, 'en', 10000, false, '10,000' ],
			[ true, 'en', 100000, false, '100,000' ],
			[ true, 'en', 1000000, false, '1,000,000' ],
			[ true, 'en', -1.001, false, '−1.001' ],
			[ true, 'en', 1.001, false, '1.001' ],
			[ true, 'en', 10.0001, false, '10.0001' ],
			[ true, 'en', 100.001, false, '100.001' ],
			[ true, 'en', 1000.001, false, '1,000.001' ],
			[ true, 'en', 10000.001, false, '10,000.001' ],
			[ true, 'en', 100000.001, false, '100,000.001' ],
			[ true, 'en', 1000000.0001, false, '1,000,000.0001' ],
			[ true, 'en', -1.0001, false, '−1.0001' ],
			[ true, 'en', '200000000000000000000', false, '200,000,000,000,000,000,000' ],
			[ true, 'en', '-200000000000000000000', false, '−200,000,000,000,000,000,000' ],
			[ true, 'en', '1.23e10', false, '12,300,000,000' ],
			[ true, 'en', 1.23e10, false, '12,300,000,000' ],
			[ true, 'en', '1.23E-01', false, '0.123' ],
			[ true, 'en', 1.23e-1, false, '0.123' ],
			[ true, 'en', 0.0, false, '0' ],
			[ true, 'en', -0.0, false, '−0' ],
			[ true, 'en', INF, false, '∞' ],
			[ true, 'en', -INF, false, '−∞' ],
			[ true, 'en', NAN, false, 'Not a Number' ],
			[ true, 'kn', '1050', false, '೧,೦೫೦' ],
			[ true, 'kn', '1060', true, '೧೦೬೦' ],
			[ false, 'kn', '1070', false, '1,070' ],
			[ false, 'kn', '1080', true, '1080' ],
			[ true, 'kn', '.1090', false, '.೧೦೯೦' ],

			// Make sure non-numeric strings are not destroyed
			[ false, 'en', 'The number is 1234', false, 'The number is 1,234' ],
			[ false, 'en', '1234 is the number', false, '1,234 is the number' ],
			[ false, 'de', '.', false, '.' ],
			[ false, 'de', ',', false, ',' ],

			/** @see https://phabricator.wikimedia.org/T237467 */
			[ false, 'kn', "೭\u{FFFD}0", false, "೭\u{FFFD}0" ],
			[ false, 'kn', "-೭\u{FFFD}0", false, "-೭\u{FFFD}0" ],
			[ false, 'kn', "-1೭\u{FFFD}0", false, "−1೭\u{FFFD}0" ],

			/** @see https://phabricator.wikimedia.org/T267614 */
			[ false, 'ar', "1", false, "1" ],
			[ false, 'ar', "1234.5", false, "1٬234٫5" ],
			[ true, 'ar', "1", false, "١" ],
			[ true, 'ar', "1234.5", false, "١٬٢٣٤٫٥" ],

			// Test minimumGroupingDigits > 1
			[ false, 'pl', 1, false, '1' ],
			[ false, 'pl', 100, false, '100' ],
			[ false, 'pl', 1000, false, '1000' ],
			[ false, 'pl', 10000, false, "10\u{00A0}000" ],
			[ false, 'pl', 1000000, false, "1\u{00A0}000\u{00A0}000" ],
			[ false, 'pl', '1000.1', false, "1000,1" ],
		];
	}

	/**
	 * @dataProvider parseFormattedNumberProvider
	 */
	public function testParseFormattedNumber( $langCode, $number ) {
		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( $langCode );

		$localisedNum = $lang->formatNum( $number );
		$normalisedNum = $lang->parseFormattedNumber( $localisedNum );

		$this->assertEquals( $number, $normalisedNum );
	}

	public static function parseFormattedNumberProvider() {
		return [
			[ 'de', 377.01 ],
			[ 'fa', 334 ],
			[ 'fa', 382.772 ],
			[ 'ar', 1844 ],
			[ 'lzh', 3731 ],
			[ 'zh-classical', 7432 ],
			[ 'en', 1234.567 ],
			[ 'en', 0.0 ],
			[ 'en', -0.0 ],
			[ 'en', INF ],
			[ 'en', -INF ],
			[ 'en', NAN ],
		];
	}

	public function testListToText() {
		$lang = $this->getLang();
		$and = $lang->getMessageFromDB( 'and' );
		$s = $lang->getMessageFromDB( 'word-separator' );
		$c = $lang->getMessageFromDB( 'comma-separator' );

		$this->assertSame( '', $lang->listToText( [] ) );
		$this->assertEquals( 'a', $lang->listToText( [ 'a' ] ) );
		$this->assertEquals( "a{$and}{$s}b", $lang->listToText( [ 'a', 'b' ] ) );
		$this->assertEquals( "a{$c}b{$and}{$s}c", $lang->listToText( [ 'a', 'b', 'c' ] ) );
		$this->assertEquals( "a{$c}b{$c}c{$and}{$s}d", $lang->listToText( [ 'a', 'b', 'c', 'd' ] ) );
	}

	/**
	 * Example of the real localisation files being loaded.
	 *
	 * This might be a bit cumbersome to maintain long-term,
	 * but still valueable to have as integration test.
	 */
	public function testGetNamespaceAliasesReal() {
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'zh' );
		$aliases = $language->getNamespaceAliases();
		$this->assertSame( NS_FILE, $aliases['文件'] );
		$this->assertSame( NS_FILE, $aliases['檔案'] );
	}

	public function testGetNamespaceAliasesFullLogic() {
		$hooks = $this->createHookContainer( [
			'Language::getMessagesFileName' => static function ( $code, &$file ) {
				$file = __DIR__ . '/../../data/messages/Messages_' . $code . '.php';
			}
		] );
		$langNameUtils = $this->getDummyLanguageNameUtils( [ 'hookContainer' => $hooks ] );

		$this->overrideConfigValue( MainConfigNames::NamespaceAliases, [
			'Mouse' => NS_SPECIAL,
		] );
		$this->setService( 'LanguageNameUtils', $langNameUtils );

		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'x-bar' );

		$this->assertEquals(
			[
				// from x-bar
				'Cat' => NS_FILE,
				'Cat_toots' => NS_FILE_TALK,
				// inherited from x-foo
				'Dog' => NS_USER,
				'Dog_woofs' => NS_USER_TALK,
				// add from site configuration
				'Mouse' => NS_SPECIAL,
			],
			$language->getNamespaceAliases()
		);
	}

	public function testEquals() {
		$languageFactory = $this->getServiceContainer()->getLanguageFactory();
		$en1 = $languageFactory->getLanguage( 'en' );
		$en2 = $languageFactory->getLanguage( 'en' );
		$en3 = $this->newLanguage();
		$this->assertTrue( $en1->equals( $en2 ), 'en1 equals en2' );
		$this->assertTrue( $en2->equals( $en3 ), 'en2 equals en3' );
		$this->assertTrue( $en3->equals( $en1 ), 'en3 equals en1' );

		$fr = $languageFactory->getLanguage( 'fr' );
		$this->assertFalse( $en1->equals( $fr ), 'en not equals fr' );

		$ar1 = $languageFactory->getLanguage( 'ar' );
		$ar2 = $this->newLanguage( LanguageAr::class, 'ar' );
		$this->assertTrue( $ar1->equals( $ar2 ), 'ar equals ar' );
	}

	/**
	 * @dataProvider provideUcfirst
	 */
	public function testUcfirst( $orig, $expected, $desc, $overrides = false ) {
		$lang = $this->newLanguage();
		if ( is_array( $overrides ) ) {
			$this->overrideConfigValue(
				MainConfigNames::OverrideUcfirstCharacters,
				$overrides
			);
		}
		$this->assertSame( $expected, $lang->ucfirst( $orig ), $desc );
	}

	public static function provideUcfirst() {
		return [
			[ 'alice', 'Alice', 'simple ASCII string', false ],
			[ 'århus', 'Århus', 'unicode string', false ],
			// overrides do not affect ASCII characters
			[ 'foo', 'Foo', 'ASCII is not overridden', [ 'f' => 'b' ] ],
			// but they do affect non-ascii ones
			[ 'èl', 'Ll', 'Non-ASCII is overridden', [ 'è' => 'L' ] ],
			[ 'ვიკიპედია', 'ვიკიპედია', 'Georgian case is preserved', false ],
		];
	}

	// The following methods are for LanguageNameUtilsTestTrait

	private function isSupportedLanguage( string $code ): bool {
		return $this->getServiceContainer()->getLanguageNameUtils()->isSupportedLanguage( $code );
	}

	private function isValidCode( string $code ): bool {
		return $this->getServiceContainer()->getLanguageNameUtils()->isValidCode( $code );
	}

	private function isValidBuiltInCode( string $code ): bool {
		return $this->getServiceContainer()->getLanguageNameUtils()->isValidBuiltInCode( $code );
	}

	private function isKnownLanguageTag( string $code ): bool {
		return $this->getServiceContainer()->getLanguageNameUtils()->isKnownLanguageTag( $code );
	}

	protected function setLanguageTemporaryHook( string $hookName, $handler ): void {
		$this->setTemporaryHook( $hookName, $handler );
	}

	protected function clearLanguageHook( string $hookName ): void {
		$this->clearHook( $hookName );
	}

	/**
	 * Call getLanguageName() and getLanguageNames() using the Language static methods.
	 *
	 * @param array $options To set globals for testing Language
	 * @param string $expected
	 * @param string $code
	 * @param mixed ...$otherArgs Optionally, pass $inLanguage and/or $include.
	 */
	private function assertGetLanguageNames( array $options, string $expected, string $code, ?string ...$otherArgs ): void {
		if ( $options ) {
			$this->overrideConfigValues( $options );
		}

		$langNameUtils = $this->getServiceContainer()->getLanguageNameUtils();
		$this->assertSame( $expected,
			$langNameUtils->getLanguageNames( ...$otherArgs )[strtolower( $code )] ?? '' );
		$this->assertSame( $expected, $langNameUtils->getLanguageName( $code, ...$otherArgs ) );
	}

	private function getLanguageNames( ?string ...$args ): array {
		return $this->getServiceContainer()->getLanguageNameUtils()->getLanguageNames( ...$args );
	}

	private function getLanguageName( ?string ...$args ): string {
		return $this->getServiceContainer()->getLanguageNameUtils()->getLanguageName( ...$args );
	}

	private function getFileName( string ...$args ): string {
		return MediaWikiServices::getInstance()->getLanguageNameUtils()->getFileName( ...$args );
	}

	private function getMessagesFileName( string $code ): string {
		return MediaWikiServices::getInstance()->getLanguageNameUtils()->getMessagesFileName( $code );
	}

	private function getJsonMessagesFileName( string $code ): string {
		return MediaWikiServices::getInstance()->getLanguageNameUtils()->getJsonMessagesFileName( $code );
	}

	/**
	 * @todo This really belongs in the cldr extension's tests.
	 */
	public function testCldr() {
		$this->markTestSkippedIfExtensionNotLoaded( 'CLDR' );

		$languageNameUtils = $this->getServiceContainer()->getLanguageNameUtils();

		// "pal" is an ancient language, which probably will not appear in Names.php, but appears in
		// CLDR in English
		$this->assertTrue( $languageNameUtils->isKnownLanguageTag( 'pal' ) );

		$this->assertSame( 'allemand', $languageNameUtils->getLanguageName( 'de', 'fr' ) );
	}

	/**
	 * @dataProvider provideGetNamespaces
	 */
	public function testGetNamespaces( string $langCode, array $config, array $expected ) {
		$services = $this->getServiceContainer();
		$langClass = Language::class . ucfirst( $langCode );
		if ( !class_exists( $langClass ) ) {
			$langClass = Language::class;
		}
		$config += [
			MainConfigNames::MetaNamespace => 'Project',
			MainConfigNames::MetaNamespaceTalk => false,
			MainConfigNames::ExtraNamespaces => [],
		];
		$nsInfo = new NamespaceInfo(
			new ServiceOptions( NamespaceInfo::CONSTRUCTOR_OPTIONS, $config, $services->getMainConfig() ),
			$services->getHookContainer(),
			ExtensionRegistry::getInstance()->getAttribute( 'ExtensionNamespaces' ),
			ExtensionRegistry::getInstance()->getAttribute( 'ImmovableNamespaces' )
		);
		/** @var Language $lang */
		$lang = new $langClass(
			$langCode,
			$nsInfo,
			$services->getLocalisationCache(),
			$this->createNoOpMock( LanguageNameUtils::class ),
			$this->createNoOpMock( LanguageFallback::class ),
			$this->createNoOpMock( LanguageConverterFactory::class ),
			$this->createMock( HookContainer::class ),
			new MultiConfig( [ new HashConfig( $config ), $services->getMainConfig() ] )
		);
		$namespaces = $lang->getNamespaces();
		$this->assertArraySubmapSame( $expected, $namespaces );
	}

	public static function provideGetNamespaces() {
		$enNamespaces = [
			NS_MEDIA            => 'Media',
			NS_SPECIAL          => 'Special',
			NS_MAIN             => '',
			NS_TALK             => 'Talk',
			NS_USER             => 'User',
			NS_USER_TALK        => 'User_talk',
			NS_FILE             => 'File',
			NS_FILE_TALK        => 'File_talk',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
			NS_TEMPLATE         => 'Template',
			NS_TEMPLATE_TALK    => 'Template_talk',
			NS_HELP             => 'Help',
			NS_HELP_TALK        => 'Help_talk',
			NS_CATEGORY         => 'Category',
			NS_CATEGORY_TALK    => 'Category_talk',
		];
		$ukNamespaces = [
			NS_MEDIA            => 'Медіа',
			NS_SPECIAL          => 'Спеціальна',
			NS_TALK             => 'Обговорення',
			NS_USER             => 'Користувач',
			NS_USER_TALK        => 'Обговорення_користувача',
			NS_FILE             => 'Файл',
			NS_FILE_TALK        => 'Обговорення_файлу',
			NS_MEDIAWIKI        => 'MediaWiki',
			NS_MEDIAWIKI_TALK   => 'Обговорення_MediaWiki',
			NS_TEMPLATE         => 'Шаблон',
			NS_TEMPLATE_TALK    => 'Обговорення_шаблону',
			NS_HELP             => 'Довідка',
			NS_HELP_TALK        => 'Обговорення_довідки',
			NS_CATEGORY         => 'Категорія',
			NS_CATEGORY_TALK    => 'Обговорення_категорії',
		];
		return [
			'Default configuration' => [
				'en',
				[],
				$enNamespaces + [
					NS_PROJECT => 'Project',
					NS_PROJECT_TALK => 'Project_talk',
				],
			],
			'Custom project NS + extra' => [
				'en',
				[
					MainConfigNames::MetaNamespace => 'Wikipedia',
					MainConfigNames::ExtraNamespaces => [
						100 => 'Borderlands',
						101 => 'Borderlands_talk',
					],
				],
				$enNamespaces + [
					NS_PROJECT => 'Wikipedia',
					NS_PROJECT_TALK => 'Wikipedia_talk',
					100 => 'Borderlands',
					101 => 'Borderlands_talk',
				],
			],
			'Custom project NS and talk + extra' => [
				'en',
				[
					MainConfigNames::MetaNamespace => 'Wikipedia',
					MainConfigNames::MetaNamespaceTalk => 'Wikipedia_drama',
					MainConfigNames::ExtraNamespaces => [
						100 => 'Borderlands',
						101 => 'Borderlands_talk',
					],
				],
				$enNamespaces + [
					NS_PROJECT => 'Wikipedia',
					NS_PROJECT_TALK => 'Wikipedia_drama',
					100 => 'Borderlands',
					101 => 'Borderlands_talk',
				],
			],
			'Ukrainian default' => [
				'uk',
				[],
				$ukNamespaces + [
					NS_MAIN => '',
					NS_PROJECT => 'Project',
					NS_PROJECT_TALK => 'Обговорення_Project',
				],
			],
			'Ukrainian custom NS' => [
				'uk',
				[
					MainConfigNames::MetaNamespace => 'Вікіпедія',
				],
				$ukNamespaces + [
					NS_MAIN => '',
					NS_PROJECT => 'Вікіпедія',
					NS_PROJECT_TALK => 'Обговорення_Вікіпедії',
				],
			],
		];
	}

	public function testGetGroupName() {
		$lang = $this->getLang();
		$groupName = $lang->getGroupName( 'bot' );
		$this->assertSame( 'Bots', $groupName );
	}

	public function testGetGroupMemberName() {
		$lang = $this->getLang();
		$user = new UserIdentityValue( 1, 'user' );
		$groupMemberName = $lang->getGroupMemberName( 'bot', $user );
		$this->assertSame( 'bot', $groupMemberName );

		$lang = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' );
		$groupMemberName = $lang->getGroupMemberName( 'bot', $user );
		$this->assertSame( '(group-bot-member: user)', $groupMemberName );
	}

	public function testMsg() {
		$lang = TestingAccessWrapper::newFromObject( $this->getLang() );
		$this->assertSame( 'Line 1:', $lang->msg( 'lineno', '1' )->text() );
	}

	public function testBlockDurations() {
		$lang = $this->getLang();
		$durations = $lang->getBlockDurations();

		$this->assertContains( 'other', $durations );
		$this->assertContains( 'infinite', $durations );
		$this->assertContains( '1 day', $durations );
	}

	public function testGetJsDateFormats() {
		$lang = $this->getLang();
		$result = $lang->getJsDateFormats();
		$this->assertSame(
			[
				'options' => [
					'numberingSystem' => 'latn',
					'hour' => '2-digit',
					'hour12' => false,
					'minute' => '2-digit',
					'day' => 'numeric',
					'month' => 'long',
					'year' => 'numeric',
				],
				'pattern' => '{hour}:{minute}, {day} {mwMonth} {year}'
			],
			$result['dmy both']
		);
	}

}
