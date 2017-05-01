<?php

class UIDGeneratorTest extends PHPUnit_Framework_TestCase {

	protected function tearDown() {
		// Bug: 44850
		UIDGenerator::unitTestTearDown();
		parent::tearDown();
	}

	/**
	 * Test that generated UIDs have the expected properties
	 *
	 * @dataProvider provider_testTimestampedUID
	 * @covers UIDGenerator::newTimestampedUID128
	 * @covers UIDGenerator::newTimestampedUID88
	 */
	public function testTimestampedUID( $method, $digitlen, $bits, $tbits, $hostbits ) {
		$id = call_user_func( [ 'UIDGenerator', $method ] );
		$this->assertEquals( true, ctype_digit( $id ), "UID made of digit characters" );
		$this->assertLessThanOrEqual( $digitlen, strlen( $id ),
			"UID has the right number of digits" );
		$this->assertLessThanOrEqual( $bits, strlen( Wikimedia\base_convert( $id, 10, 2 ) ),
			"UID has the right number of bits" );

		$ids = [];
		for ( $i = 0; $i < 300; $i++ ) {
			$ids[] = call_user_func( [ 'UIDGenerator', $method ] );
		}

		$lastId = array_shift( $ids );

		$this->assertSame( array_unique( $ids ), $ids, "All generated IDs are unique." );

		foreach ( $ids as $id ) {
			// Convert string to binary and pad to full length so we can
			// extract segments
			$id_bin = Wikimedia\base_convert( $id, 10, 2, $bits );
			$lastId_bin = Wikimedia\base_convert( $lastId, 10, 2, $bits );

			$timestamp_bin = substr( $id_bin, 0, $tbits );
			$last_timestamp_bin = substr( $lastId_bin, 0, $tbits );

			$this->assertGreaterThanOrEqual(
				$last_timestamp_bin,
				$timestamp_bin,
				"timestamp ($timestamp_bin) of current ID ($id_bin) >= timestamp ($last_timestamp_bin) " .
					"of prior one ($lastId_bin)" );

			$hostbits_bin = substr( $id_bin, -$hostbits );
			$last_hostbits_bin = substr( $lastId_bin, -$hostbits );

			if ( $hostbits ) {
				$this->assertEquals(
					$hostbits_bin,
					$last_hostbits_bin,
					"Host ID ($hostbits_bin) of current ID ($id_bin) is same as host ID ($last_hostbits_bin) " .
						"of prior one ($lastId_bin)." );
			}

			$lastId = $id;
		}
	}

	/**
	 * array( method, length, bits, hostbits )
	 * NOTE: When adding a new method name here please update the covers tags for the tests!
	 */
	public static function provider_testTimestampedUID() {
		return [
			[ 'newTimestampedUID128', 39, 128, 46, 48 ],
			[ 'newTimestampedUID128', 39, 128, 46, 48 ],
			[ 'newTimestampedUID88', 27, 88, 46, 32 ],
		];
	}

	/**
	 * @covers UIDGenerator::newUUIDv1
	 */
	public function testUUIDv1() {
		$ids = [];
		for ( $i = 0; $i < 100; $i++ ) {
			$id = UIDGenerator::newUUIDv1();
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-1[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!', $id ),
				"UID $id has the right format" );
			$ids[] = $id;

			$id = UIDGenerator::newRawUUIDv1();
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{12}1[0-9a-f]{3}[89ab][0-9a-f]{15}$!', $id ),
				"UID $id has the right format" );

			$id = UIDGenerator::newRawUUIDv1();
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{12}1[0-9a-f]{3}[89ab][0-9a-f]{15}$!', $id ),
				"UID $id has the right format" );
		}

		$this->assertEquals( array_unique( $ids ), $ids, "All generated IDs are unique." );
	}

	/**
	 * @covers UIDGenerator::newUUIDv4
	 */
	public function testUUIDv4() {
		$ids = [];
		for ( $i = 0; $i < 100; $i++ ) {
			$id = UIDGenerator::newUUIDv4();
			$ids[] = $id;
			$this->assertEquals( true,
				preg_match( '!^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!', $id ),
				"UID $id has the right format" );
		}

		$this->assertEquals( array_unique( $ids ), $ids, 'All generated IDs are unique.' );
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
