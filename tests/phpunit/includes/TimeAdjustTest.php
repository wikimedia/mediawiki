<?php

class TimeAdjustTest extends MediaWikiLangTestCase {
	static $offset, $timezone;

	public function setUp() {
		parent::setUp();
		global $wgLocalTZoffset, $wgLocaltimezone;
		self::$offset = $wgLocalTZoffset;
		self::$timezone = $wgLocaltimezone;

		$this->iniSet( 'precision', 15 );
	}

	public function tearDown() {
		global $wgLocalTZoffset, $wgLocaltimezone;
		$wgLocalTZoffset = self::$offset;
		$wgLocaltimezone = self::$timezone;
		parent::tearDown();
	}

	/**
	 * Test offset usage for a given language::userAdjust
	 * @dataProvider dataUserAdjustWithOffset
	 */
	function testUserAdjustWithOffset( $inputDate, $offset, $expectedDate ) {
		global $wgLocalTZoffset, $wgLocaltimezone, $wgContLang;

		$wgContLang = $en = Language::factory( 'en' );

		$wgLocaltimezone = 'DummyTimezoneSoUserAdjustWillUseTzOffset';
		$wgLocalTZoffset = $offset;

		$this->assertEquals(
			strval( $expectedDate ),
			strval( $en->userAdjust( $inputDate, '' ) ),
			"User adjust {$inputDate} by {$offset} minutes should give {$expectedDate}"
		);
	}

	function dataUserAdjustWithOffset() {
		#  Collection of parameters for Language_t_Offset.
		# Format: date to be formatted, localTZoffset value, expected date
		return array(
			array( 20061231235959,   0, 20061231235959 ),
			array( 20061231235959,   5, 20070101000459 ),
			array( 20061231235959,  15, 20070101001459 ),
			array( 20061231235959,  60, 20070101005959 ),
			array( 20061231235959,  90, 20070101012959 ),
			array( 20061231235959, 120, 20070101015959 ),
			array( 20061231235959, 540, 20070101085959 ),
			array( 20061231235959,  -5, 20061231235459 ),
			array( 20061231235959, -30, 20061231232959 ),
			array( 20061231235959, -60, 20061231225959 ),
		);
	}

	/**
	 * Test timezone usage for a given language::userAdjust
	 * @dataProvider dataUserAdjustWithTimezone
	 */
	function testUserAdjustWithTimezone( $inputDate, $timezone, $expectedDate ) {
		global $wgLocalTZoffset, $wgLocaltimezone;

		$wgContLang = $en = Language::factory( 'en' );

		$wgLocaltimezone = $timezone;
		$wgLocalTZoffset = 0;

		$this->assertEquals(
			strval( $expectedDate ),
			strval( $en->userAdjust( $inputDate, '' ) ),
			"User adjust {$inputDate} with timezone {$timezone} should give {$expectedDate}"
		);
	}

	function dataUserAdjustWithTimezone() {
		return array(
			array( 20111028233711, 'Europe/Warsaw', 20111029013711 ),
			array( 20111108205929, 'Europe/Warsaw', 20111108215929 ),
		);
	}

}
