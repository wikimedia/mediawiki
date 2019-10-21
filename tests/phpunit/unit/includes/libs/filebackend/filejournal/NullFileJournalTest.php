<?php

/**
 * @coversDefaultClass NullFileJournal
 */
class NullFileJournalTest extends MediaWikiUnitTestCase {
	public function newObj() : NullFileJournal {
		return FileJournal::factory( [ 'class' => NullFileJournal::class ], '' );
	}

	/**
	 * @covers ::doLogChangeBatch
	 */
	public function testLogChangeBatch() {
		$this->assertEquals( StatusValue::newGood(), $this->newObj()->logChangeBatch( [ 1 ], '' ) );
	}

	/**
	 * @covers ::doGetCurrentPosition
	 */
	public function testGetCurrentPosition() {
		$this->assertFalse( $this->newObj()->getCurrentPosition() );
	}

	/**
	 * @covers ::doGetPositionAtTime
	 */
	public function testGetPositionAtTime() {
		$this->assertFalse( $this->newObj()->getPositionAtTime( 2 ) );
	}

	/**
	 * @covers ::doGetChangeEntries
	 */
	public function testGetChangeEntries() {
		$next = 1;
		$entries = $this->newObj()->getChangeEntries( null, 0, $next );
		$this->assertSame( [], $entries );
		$this->assertNull( $next );
	}

	/**
	 * @covers ::doPurgeOldLogs
	 */
	public function testPurgeOldLogs() {
		$this->assertEquals( StatusValue::newGood(), $this->newObj()->purgeOldLogs() );
	}
}
