<?php

/**
 * @coversDefaultClass NullFileJournal
 */
class NullFileJournalTest extends MediaWikiUnitTestCase {
	/**
	 * @covers ::doLogChangeBatch
	 */
	public function testLogChangeBatch() {
		$this->assertEquals( StatusValue::newGood(),
			( new NullFileJournal )->logChangeBatch( [ 1 ], '' ) );
	}

	/**
	 * @covers ::doGetCurrentPosition
	 */
	public function testGetCurrentPosition() {
		$this->assertFalse( ( new NullFileJournal )->getCurrentPosition() );
	}

	/**
	 * @covers ::doGetPositionAtTime
	 */
	public function testGetPositionAtTime() {
		$this->assertFalse( ( new NullFileJournal )->getPositionAtTime( 2 ) );
	}

	/**
	 * @covers ::doGetChangeEntries
	 */
	public function testGetChangeEntries() {
		$next = 1;
		$entries = ( new NullFileJournal )->getChangeEntries( null, 0, $next );
		$this->assertSame( [], $entries );
		$this->assertNull( $next );
	}

	/**
	 * @covers ::doPurgeOldLogs
	 */
	public function testPurgeOldLogs() {
		$this->assertEquals( StatusValue::newGood(), ( new NullFileJournal )->purgeOldLogs() );
	}
}
