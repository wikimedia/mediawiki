<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @coversDefaultClass DBFileJournal
 * @covers ::__construct
 * @covers ::getMasterDB
 * @group Database
 */
class DBFileJournalIntegrationTest extends MediaWikiIntegrationTestCase {
	public function addDBDataOnce() {
		global $IP;
		$db = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_MASTER );
		if ( $db->getType() !== 'mysql' ) {
			return;
		}
		if ( !$db->tableExists( 'filejournal' ) ) {
			$db->sourceFile( "$IP/maintenance/archives/patch-filejournal.sql" );
		}
	}

	protected function setUp() {
		parent::setUp();

		$db = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_MASTER );
		if ( $db->getType() !== 'mysql' ) {
			$this->markTestSkipped( 'No filejournal schema available for this database type' );
		}

		$this->tablesUsed[] = 'filejournal';
	}

	private function getJournal( $options = [] ) {
		return FileJournal::factory(
			$options + [ 'class' => DBFileJournal::class, 'domain' => wfWikiID() ],
			'local-backend' );
	}

	/**
	 * @covers ::doLogChangeBatch
	 */
	public function testDoLogChangeBatch_exceptionDbConnect() {
		$journal = $this->getJournal( [ 'domain' => 'no-such-domain' ] );

		$this->assertEquals(
			StatusValue::newFatal( 'filejournal-fail-dbconnect', 'local-backend' ),
			$journal->logChangeBatch( [ [] ], 'batch' ) );
	}

	/**
	 * @covers ::doLogChangeBatch
	 */
	public function testDoLogChangeBatch_exceptionDbQuery() {
		MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()->setReason( 'testing' );

		$journal = $this->getJournal();

		$this->assertEquals(
			StatusValue::newFatal( 'filejournal-fail-dbquery', 'local-backend' ),
			$journal->logChangeBatch(
				[ [ 'op' => null, 'path' => '', 'newSha1' => false ] ], 'batch' ) );
	}

	/**
	 * @covers ::doLogChangeBatch
	 * @covers ::doGetCurrentPosition
	 */
	public function testDoGetCurrentPosition() {
		$journal = $this->getJournal();

		$this->assertNull( $journal->getCurrentPosition() );

		$journal->logChangeBatch(
			[ [ 'op' => 'create', 'path' => '/path', 'newSha1' => false ] ], 'batch1' );

		$this->assertSame( '1', $journal->getCurrentPosition() );

		$journal->logChangeBatch(
			[ [ 'op' => 'create', 'path' => '/path', 'newSha1' => false ] ], 'batch2' );

		$this->assertSame( '2', $journal->getCurrentPosition() );
	}

	/**
	 * @covers ::doLogChangeBatch
	 * @covers ::doGetPositionAtTime
	 */
	public function testDoGetPositionAtTime() {
		$journal = $this->getJournal();

		$now = time();

		$this->assertFalse( $journal->getPositionAtTime( $now ) );

		ConvertibleTimestamp::setFakeTime( $now - 86400 );

		$journal->logChangeBatch(
			[ [ 'op' => 'create', 'path' => '/path', 'newSha1' => false ] ], 'batch1' );

		ConvertibleTimestamp::setFakeTime( $now - 3600 );

		$journal->logChangeBatch(
			[ [ 'op' => 'create', 'path' => '/path', 'newSha1' => false ] ], 'batch2' );

		$this->assertFalse( $journal->getPositionAtTime( $now - 86401 ) );
		$this->assertSame( '1', $journal->getPositionAtTime( $now - 86400 ) );
		$this->assertSame( '1', $journal->getPositionAtTime( $now - 3601 ) );
		$this->assertSame( '2', $journal->getPositionAtTime( $now - 3600 ) );
	}

	/**
	 * @param int $expectedStart First index expected to be returned (0-based)
	 * @param int|null $expectedCount Number of entries expected to be returned (null for all)
	 * @param string|null|false $expectedNext Expected value of $next, or false not to pass
	 * @param array $args If any third argument is present, $next will also be tested
	 * @dataProvider provideDoGetChangeEntries
	 * @covers ::doLogChangeBatch
	 * @covers ::doGetChangeEntries
	 */
	public function testDoGetChangeEntries(
		$expectedStart, $expectedCount, $expectedNext, array $args
	) {
		$journal = $this->getJournal();

		$i = 0;
		$makeExpectedEntry = function ( $op, $path, $newSha1, $batch, $time ) use ( &$i ) {
			$i++;
			return [
				'id' => (string)$i,
				'batch_uuid' => $batch,
				'backend' => 'local-backend',
				'path' => $path,
				'op' => $op ?? '',
				'new_sha1' => $newSha1 !== false ? $newSha1 : '0',
				'timestamp' => ConvertibleTimestamp::convert( TS_MW, $time ),
			];
		};

		$expectedEntries = [];

		$now = time();

		ConvertibleTimestamp::setFakeTime( $now - 3600 );
		$changes = [
			[ 'op' => 'create', 'path' => '/path1',
				'newSha1' => base_convert( sha1( 'a' ), 16, 36 ) ],
			[ 'op' => 'delete', 'path' => '/path2', 'newSha1' => false ],
			[ 'op' => 'null', 'path' => '', 'newSha1' => false ],
		];
		$this->assertEquals( StatusValue::newGood(),
			$journal->logChangeBatch( $changes, 'batch1' ) );
		foreach ( $changes as $change ) {
			$expectedEntries[] = $makeExpectedEntry(
				...array_merge( array_values( $change ), [ 'batch1', $now - 3600 ] ) );
		}

		ConvertibleTimestamp::setFakeTime( $now - 60 );
		$change = [ 'op' => 'update', 'path' => '/path1',
			'newSha1' => base_convert( sha1( 'b' ), 16, 36 ) ];
		$this->assertEquals(
		   StatusValue::newGood(), $journal->logChangeBatch( [ $change ], 'batch2' ) );
		$expectedEntries[] = $makeExpectedEntry(
			...array_merge( array_values( $change ), [ 'batch2', $now - 60 ] ) );

		if ( $expectedNext === false ) {
			$this->assertSame(
				array_slice( $expectedEntries, $expectedStart, $expectedCount ),
				$journal->getChangeEntries( ...$args )
			);
		} else {
			$next = false;
			$this->assertSame(
				array_slice( $expectedEntries, $expectedStart, $expectedCount ),
				$journal->getChangeEntries( $args[0], $args[1], $next )
			);
			$this->assertSame( $expectedNext, $next );
		}
	}

	public static function provideDoGetChangeEntries() {
		return [
			'No args' => [ 0, 4, false, [] ],
			'null' => [ 0, 4, false, [ null ] ],
			'1' => [ 0, 4, false, [ 1 ] ],
			'2' => [ 1, 3, false, [ 2 ] ],
			'4' => [ 3, 1, false, [ 4 ] ],
			'5' => [ 0, 0, false, [ 5 ] ],
			'null, 0' => [ 0, 4, null, [ null, 0 ] ],
			'1, 0' => [ 0, 4, null, [ 1, 0 ] ],
			'2, 0' => [ 1, 3, null, [ 2, 0 ] ],
			'4, 0' => [ 3, 1, null, [ 4, 0 ] ],
			'5, 0' => [ 0, 0, null, [ 5, 0 ] ],
			'1, 1' => [ 0, 1, '2', [ 1, 1 ] ],
			'1, 2' => [ 0, 2, '3', [ 1, 2 ] ],
			'1, 4' => [ 0, 4, null, [ 1, 4 ] ],
			'1, 5' => [ 0, 4, null, [ 1, 5 ] ],
			'2, 2' => [ 1, 2, '4', [ 2, 2 ] ],
			'1, 2 with no $next' => [ 0, 2, false, [ 1, 2 ] ],
		];
	}

	/**
	 * @covers ::doPurgeOldLogs
	 */
	public function testDoPurgeOldLogs_noop() {
		// If we tried to access the database, it would throw, because the domain doesn't exist
		$journal = $this->getJournal( [ 'domain' => 'no-such-domain' ] );
		$this->assertEquals( StatusValue::newGood(), $journal->purgeOldLogs() );
	}

	/**
	 * @covers ::doPurgeOldLogs
	 * @covers ::doLogChangeBatch
	 * @covers ::doGetChangeEntries
	 */
	public function testDoPurgeOldLogs() {
		$journal = $this->getJournal( [ 'ttlDays' => 1 ] );
		$now = time();

		// One day and one second ago
		ConvertibleTimestamp::setFakeTime( $now - 86401 );
		$this->assertEquals( StatusValue::newGood(), $journal->logChangeBatch(
			[ [ 'op' => 'null', 'path' => '', 'newSha1' => false ] ], 'batch1' ) );

		// One day ago exactly, won't get purged
		ConvertibleTimestamp::setFakeTime( $now - 86400 );
		$this->assertEquals( StatusValue::newGood(), $journal->logChangeBatch(
			[ [ 'op' => 'null', 'path' => '', 'newSha1' => false ] ], 'batch2' ) );

		ConvertibleTimestamp::setFakeTime( $now );
		$this->assertCount( 2, $journal->getChangeEntries() );
		$journal->purgeOldLogs();
		$this->assertCount( 1, $journal->getChangeEntries() );
	}
}
