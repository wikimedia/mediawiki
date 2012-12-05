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
		for (  $i = 0; $i < 100; $i++ ) {
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
			array( 'timestampedUID128', 39, 128, 48 ),
			array( 'timestampedUID88', 27, 88, 32 ),
			array( 'timestampedUID64', 20, 64, 24 )
		);
	}
}
