<?php

use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\UUID\GlobalIdGenerator;

class GlobalIdGeneratorTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * Test that generated UIDs have the expected properties
	 *
	 * @dataProvider provider_testTimestampedUID
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newTimestampedUID88
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::getTimestampedID88
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newTimestampedUID128
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::getTimestampedID128
	 */
	public function testTimestampedUID( $method, $digitlen, $bits, $tbits, $hostbits ) {
		$gen = $this->getGlobalIdGenerator();

		$id = $gen->$method();
		$this->assertTrue( ctype_digit( $id ), "UID made of digit characters" );
		$this->assertLessThanOrEqual( $digitlen, strlen( $id ),
			"UID has the right number of digits" );
		$this->assertLessThanOrEqual( $bits, strlen( Wikimedia\base_convert( $id, 10, 2 ) ),
			"UID has the right number of bits" );

		$ids = [];
		for ( $i = 0; $i < 300; $i++ ) {
			$ids[] = $gen->$method();
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
	 * [ method, length, bits, hostbits ]
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
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newUUIDv1
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::getUUIDv1
	 */
	public function testUUIDv1() {
		$gen = $this->getGlobalIdGenerator();

		$ids = [];
		for ( $i = 0; $i < 100; $i++ ) {
			$id = $gen->newUUIDv1();
			$this->assertRegExp(
				'!^[0-9a-f]{8}-[0-9a-f]{4}-1[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!',
				$id,
				"UID $id has the right format"
			);
			$ids[] = $id;

			$id = $gen->newRawUUIDv1();
			$this->assertRegExp(
				'!^[0-9a-f]{12}1[0-9a-f]{3}[89ab][0-9a-f]{15}$!',
				$id,
				"UID $id has the right format"
			);

			$id = $gen->newRawUUIDv1();
			$this->assertRegExp(
				'!^[0-9a-f]{12}1[0-9a-f]{3}[89ab][0-9a-f]{15}$!',
				$id,
				"UID $id has the right format"
			);
		}

		$this->assertEquals( array_unique( $ids ), $ids, "All generated IDs are unique." );
	}

	/**
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newUUIDv4
	 */
	public function testUUIDv4() {
		$gen = $this->getGlobalIdGenerator();

		$ids = [];
		for ( $i = 0; $i < 100; $i++ ) {
			$id = $gen->newUUIDv4();
			$ids[] = $id;
			$this->assertRegExp(
				'!^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$!',
				$id,
				"UID $id has the right format"
			);
		}

		$this->assertEquals( array_unique( $ids ), $ids, 'All generated IDs are unique.' );
	}

	/**
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newRawUUIDv4
	 */
	public function testRawUUIDv4() {
		$gen = $this->getGlobalIdGenerator();

		for ( $i = 0; $i < 100; $i++ ) {
			$id = $gen->newRawUUIDv4();
			$this->assertRegExp(
				'!^[0-9a-f]{12}4[0-9a-f]{3}[89ab][0-9a-f]{15}$!',
				$id,
				"UID $id has the right format"
			);
		}

		for ( $i = 0; $i < 100; $i++ ) {
			$id = $gen->newRawUUIDv4( UIDGenerator::QUICK_RAND );
			$this->assertRegExp(
				'!^[0-9a-f]{12}4[0-9a-f]{3}[89ab][0-9a-f]{15}$!',
				$id,
				"UID $id has the right format"
			);
		}
	}

	/**
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newSequentialPerNodeID
	 */
	public function testNewSequentialID() {
		$gen = $this->getGlobalIdGenerator();

		$id1 = $gen->newSequentialPerNodeID( 'test', 32 );
		$id2 = $gen->newSequentialPerNodeID( 'test', 32 );

		$this->assertIsFloat( $id1, "ID returned as float" );
		$this->assertIsFloat( $id2, "ID returned as float" );
		$this->assertGreaterThan( 0, $id1, "ID greater than 1" );
		$this->assertGreaterThan( $id1, $id2, "IDs increasing in value" );
	}

	/**
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::newSequentialPerNodeIDs
	 * @covers \Wikimedia\UUID\GlobalIdGenerator::getSequentialPerNodeIDs
	 */
	public function testNewSequentialIDs() {
		$gen = $this->getGlobalIdGenerator();

		$ids = $gen->newSequentialPerNodeIDs( 'test', 32, 5 );
		$lastId = null;
		foreach ( $ids as $id ) {
			$this->assertIsFloat( $id, "ID returned as float" );
			$this->assertGreaterThan( 0, $id, "ID greater than 1" );
			if ( $lastId ) {
				$this->assertGreaterThan( $lastId, $id, "IDs increasing in value" );
			}
			$lastId = $id;
		}
	}

	public function provideGetTimestampFromUUIDv1() {
		yield [ '65d143b0-3c7a-11ea-b77f-2e728ce88125', '20200121181818' ];
	}

	/**
	 * @param string $uuid
	 * @param string $ts
	 * @dataProvider provideGetTimestampFromUUIDv1
	 * @covers UIDGenerator::getTimestampFromUUIDv1
	 */
	public function testGetTimestampFromUUIDv1( string $uuid, string $ts ) {
		$gen = $this->getGlobalIdGenerator();

		$this->assertEquals( $ts, $gen->getTimestampFromUUIDv1( $uuid ) );
		$this->assertEquals(
			ConvertibleTimestamp::convert( TS_ISO_8601, $ts ),
			$gen->getTimestampFromUUIDv1( $uuid, TS_ISO_8601 )
		);
	}

	public function provideGetTimestampFromUUIDv1InvalidUUIDv1() {
		yield [ 'this_is_an_invalid_uuid_v1' ];
		yield [ 'e5bb7f6b-0f28-4867-a93c-1b33b5c63adf' ]; // This is a UUIDv4
	}

	/**
	 * @param string $uuid
	 * @dataProvider provideGetTimestampFromUUIDv1InvalidUUIDv1
	 * @covers UIDGenerator::getTimestampFromUUIDv1
	 */
	public function testGetTimestampFromUUIDv1InvalidUUIDv1( string $uuid ) {
		$this->expectException( InvalidArgumentException::class );
		UIDGenerator::getTimestampFromUUIDv1( $uuid );
	}

	private function getGlobalIdGenerator() : GlobalIdGenerator {
		return new GlobalIdGenerator(
			wfTempDir(),
			new HashBagOStuff( [] ),
			function ( $command ) {
				return wfShellExec( $command );
			}
		);
	}

	protected function tearDown(): void {
		// T46850
		$gen = $this->getGlobalIdGenerator();
		$gen->unitTestTearDown();
		parent::tearDown();
	}

}
