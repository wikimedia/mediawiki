<?php

class TimeAdjustTest extends PHPUnit_Framework_TestCase {
	static $offset;

	public function setUp() {
		global $wgLocalTZoffset;
		self::$offset = $wgLocalTZoffset;

		$this->iniSet( 'precision', 15 );
	}

	public function tearDown() {
		global $wgLocalTZoffset;
		$wgLocalTZoffset = self::$offset;
	}

	# Test offset usage for a given language::userAdjust
	function testUserAdjust() {
		global $wgLocalTZoffset, $wgContLang, $wgUser;

		$wgContLang = $en = Language::factory( 'en' );

		#  Collection of parameters for Language_t_Offset.
		# Format: date to be formatted, localTZoffset value, expected date
		$userAdjust_tests = array(
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

		foreach ( $userAdjust_tests as $data ) {
			$wgLocalTZoffset = $data[1];

			$this->assertEquals(
				strval( $data[2] ),
				strval( $en->userAdjust( $data[0], '' ) ),
				"User adjust {$data[0]} by {$data[1]} minutes should give {$data[2]}"
			);
		}
	}
}
