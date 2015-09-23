<?php

class UIDGeneratorTest extends PHPUnit_Framework_TestCase {

	protected function tearDown() {
		// Bug: 44850
		UIDGenerator::unitTestTearDown();
		parent::tearDown();
	}

	/**
	 * @dataProvider provider_testTimestampedUID
	 * @covers UIDGenerator::newTimestampedUID128
	 * @covers UIDGenerator::newTimestampedUID88
	 */
	public function testTimestampedUID( $method, $digitlen, $bits, $tbits, $hostbits ) {
		$id = call_user_func( array( 'UIDGenerator', $method ) );
		$this->assertEquals( true, ctype_digit( $id ), "UID made of digit characters" );
		$this->assertLessThanOrEqual( $digitlen, strlen( $id ),
			"UID has the right number of digits" );
		$this->assertLessThanOrEqual( $bits, strlen( wfBaseConvert( $id, 10, 2 ) ),
			"UID has the right number of bits" );

		$ids = array();
		for ( $i = 0; $i < 300; $i++ ) {
			$ids[] = call_user_func( array( 'UIDGenerator', $method ) );
		}

		$lastId = array_shift( $ids );

		$this->assertSame( array_unique( $ids ), $ids, "All generated IDs are unique." );

		foreach ( $ids as $id ) {
			$id_bin = wfBaseConvert( $id, 10, 2 );
			$lastId_bin = wfBaseConvert( $lastId, 10, 2 );

			$this->assertGreaterThanOrEqual(
				substr( $lastId_bin, 0, $tbits ),
				substr( $id_bin, 0, $tbits ),
				"New ID timestamp ($id_bin) >= prior one ($lastId_bin)." );

			if ( $hostbits ) {
				$this->assertEquals(
					substr( $id_bin, -$hostbits ),
					substr( $lastId_bin, -$hostbits ),
					"Host ID of ($id_bin) is same as prior one ($lastId_bin)." );
			}

			$lastId = $id;
		}
	}

	/**
	 * array( method, length, bits, hostbits )
	 * NOTE: When adding a new method name here please update the covers tags for the tests!
	 */
	public static function provider_testTimestampedUID() {
		return array(
			array( 'newTimestampedUID128', 39, 128, 46, 48 ),
			array( 'newTimestampedUID128', 39, 128, 46, 48 ),
			array( 'newTimestampedUID88', 27, 88, 46, 32 ),
		);
	}

	/**
	 * @covers UIDGenerator::newUUIDv4
	 */
	public function testUUIDv4() {
		for ( $i = 0; $i < 100; $i++ ) {
			$id = UIDGenerator::newUUIDv4();
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!', $id ),
				"UID $id has the right format" );
		}
	}

	/**
	 * @covers UIDGenerator::newRawUUIDv4
	 */
	public function testRawUUIDv4() {
		for ( $i = 0; $i < 100; $i++ ) {
			$id = UIDGenerator::newRawUUIDv4();
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{12}4[0-9a-f]{3}[89ab][0-9a-f]{15}$!', $id ),
				"UID $id has the right format" );
		}
	}

	/**
	 * @covers UIDGenerator::newRawUUIDv4
	 */
	public function testRawUUIDv4QuickRand() {
		for ( $i = 0; $i < 100; $i++ ) {
			$id = UIDGenerator::newRawUUIDv4( UIDGenerator::QUICK_RAND );
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{12}4[0-9a-f]{3}[89ab][0-9a-f]{15}$!', $id ),
				"UID $id has the right format" );
		}
	}

	/**
	 * @covers UIDGenerator::newSequentialPerNodeID
	 */
	public function testNewSequentialID() {
		$id1 = UIDGenerator::newSequentialPerNodeID( 'test', 32 );
		$id2 = UIDGenerator::newSequentialPerNodeID( 'test', 32 );

		$this->assertInternalType( 'float', $id1, "ID returned as float" );
		$this->assertInternalType( 'float', $id2, "ID returned as float" );
		$this->assertGreaterThan( 0, $id1, "ID greater than 1" );
		$this->assertGreaterThan( $id1, $id2, "IDs increasing in value" );
	}

	/**
	 * @covers UIDGenerator::newSequentialPerNodeIDs
	 */
	public function testNewSequentialIDs() {
		$ids = UIDGenerator::newSequentialPerNodeIDs( 'test', 32, 5 );
		$lastId = null;
		foreach ( $ids as $id ) {
			$this->assertInternalType( 'float', $id, "ID returned as float" );
			$this->assertGreaterThan( 0, $id, "ID greater than 1" );
			if ( $lastId ) {
				$this->assertGreaterThan( $lastId, $id, "IDs increasing in value" );
			}
			$lastId = $id;
		}
	}
}
