<?php

require_once __DIR__ . '/TestFileJournal.php';

use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @coversDefaultClass FileJournal
 */
class FileJournalTest extends MediaWikiUnitTestCase {
	private function newObj( $options = [], $backend = '' ) {
		return FileJournal::factory(
			$options + [ 'class' => TestFileJournal::class ],
			$backend
		);
	}

	/**
	 * @covers ::factory
	 */
	public function testConstructor_backend() {
		$this->assertSame( 'some_backend', $this->newObj( [], 'some_backend' )->getBackend() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::factory
	 */
	public function testConstructor_ttlDays() {
		$this->assertSame( 42, $this->newObj( [ 'ttlDays' => 42 ] )->getTtlDays() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::factory
	 */
	public function testConstructor_noTtlDays() {
		$this->assertSame( false, $this->newObj()->getTtlDays() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::factory
	 */
	public function testConstructor_nullTtlDays() {
		$this->assertSame( false, $this->newObj( [ 'ttlDays' => null ] )->getTtlDays() );
	}

	/**
	 * @covers ::factory
	 */
	public function testFactory_invalidClass() {
		$this->setExpectedException( UnexpectedValueException::class,
			'Expected instance of FileJournal, got stdClass' );

		FileJournal::factory( [ 'class' => 'stdclass' ], '' );
	}

	/**
	 * @covers ::getTimestampedUUID
	 */
	public function testGetTimestampedUUID() {
		$obj = FileJournal::factory( [ 'class' => 'NullFileJournal' ], '' );
		$uuids = [];
		for ( $i = 0; $i < 10; $i++ ) {
			$time1 = time();
			$uuid = $obj->getTimestampedUUID();
			$time2 = time();
			$this->assertRegexp( '/^[0-9a-z]{31}$/', $uuid );
			$this->assertArrayNotHasKey( $uuid, $uuids );
			$uuids[$uuid] = true;

			// Now test that the timestamp portion is as expected.
			$time = ConvertibleTimestamp::convert( TS_UNIX, Wikimedia\base_convert(
				substr( $uuid, 0, 9 ), 36, 10 ) );

			$this->assertGreaterThanOrEqual( $time1, $time );
			$this->assertLessThanOrEqual( $time2, $time );
		}
	}

	/**
	 * @covers ::logChangeBatch
	 */
	public function testLogChangeBatch() {
		$this->assertEquals(
			StatusValue::newGood( 'Logged' ), $this->newObj()->logChangeBatch( [ 1 ], '' ) );
	}

	/**
	 * @covers ::logChangeBatch
	 */
	public function testLogChangeBatch_empty() {
		$this->assertEquals( StatusValue::newGood(), $this->newObj()->logChangeBatch( [], '' ) );
	}

	/**
	 * @covers ::getCurrentPosition
	 */
	public function testGetCurrentPosition() {
		$this->assertEquals( 613, $this->newObj()->getCurrentPosition() );
	}

	/**
	 * @covers ::getPositionAtTime
	 */
	public function testGetPositionAtTime() {
		$this->assertEquals( 248, $this->newObj()->getPositionAtTime( 0 ) );
	}

	/**
	 * @dataProvider provideGetChangeEntries
	 * @covers ::getChangeEntries
	 * @param int|null $start
	 * @param int $limit
	 * @param string|null $expectedNext
	 * @param string[] $expectedReturn Expected id's of returned values
	 */
	public function testGetChangeEntries( $start, $limit, $expectedNext, array $expectedReturn ) {
		$expectedReturn = array_map(
			function ( $val ) {
				return [ 'id' => $val ];
			}, $expectedReturn
		);
		$next = "Different from $expectedNext";
		$ret = $this->newObj()->getChangeEntries( $start, $limit, $next );
		$this->assertSame( $expectedNext, $next );
		$this->assertSame( $expectedReturn, $ret );
	}

	public static function provideGetChangeEntries() {
		return [
			[ null, 0, null, [ 1, 2, 3 ] ],
			[ null, 1, 2, [ 1 ] ],
			[ null, 2, 3, [ 1, 2 ] ],
			[ null, 3, null, [ 1, 2, 3 ] ],
			[ 1, 0, null, [ 1, 2, 3 ] ],
			[ 1, 2, 3, [ 1, 2 ] ],
			[ 1, 1, 2, [ 1 ] ],
			[ 2, 2, null, [ 2, 3 ] ],
		];
	}

	/**
	 * @covers ::purgeOldLogs
	 */
	public function testPurgeOldLogs() {
		$obj = $this->newObj();
		$this->assertFalse( $obj->getPurged() );
		$obj->purgeOldLogs();
		$this->assertTrue( $obj->getPurged() );
	}
}
