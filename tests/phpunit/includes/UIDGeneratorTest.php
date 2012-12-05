<?php

class UIDGeneratorTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provider_testTimestampedUID
	 */
	public function testTimestampedUID( $method, $digitlen, $bits, $hostbits ) {
		$id = call_user_func( array( 'UIDGenerator', $method ) );
		$this->assertEquals( true, ctype_digit( $id ), "UID made of digit characters" );
		$this->assertLessThanOrEqual( $digitlen, strlen( $id ),
			"UID has the right number of digits" );
		$this->assertLessThanOrEqual( $bits, strlen( wfBaseConvert( $id, 10, 2 ) ),
			"UID has the right number of bits" );

		$lastId = $id;
		if ( $hostbits ) {
			$lastHost = substr( wfBaseConvert( $lastId, 10, 2, $bits ), -$hostbits );
		}
		for (  $i = 0; $i < 300; $i++ ) {
			$id = call_user_func( array( 'UIDGenerator', $method ) );
			$this->assertGreaterThan( $lastId, $id,
				"New ID ($id) greater than prior one ($lastId)." );
			$lastId = $id;
			if ( $hostbits ) {
				$host = substr( wfBaseConvert( $id, 10, 2, $bits ), -$hostbits );
				$this->assertEquals( $lastHost, $host, "UID has same host ID" );
				$lastHost = $host;
			}
		}
	}

	/**
	 * array( method, length, bits, hostbits )
	 */
	public static function provider_testTimestampedUID() {
		return array(
			array( 'newTimestampedUID128', 39, 128, 48 ),
			array( 'newTimestampedUID88', 27, 88, 32 ),
		);
	}

	public function testUUIDv4() {
		$id = UIDGenerator::newUUIDv4();

		$this->assertEquals( true, preg_match( '!^[0-9a-f]{32}$!', $id ),
			"UID $id has the right format" );

		$id = UIDGenerator::newUUIDv4( 'raw' );
		$this->assertEquals( true, preg_match( '!^[0-9a-f]{32}$!', $id ),
			"UID $id has the right format" );

		$id = UIDGenerator::newUUIDv4( 'pretty' );
		$this->assertEquals( true,
			preg_match( '!^[0-9a-f]{8}(-[0-9a-f]{4}){3}-[0-9a-f]{12}$!', $id ),
			"UID $id has the right format" );
	}
}
