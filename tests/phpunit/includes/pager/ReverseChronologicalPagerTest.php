<?php

/**
 * Test class for ReverseChronologicalPagerTest methods.
 *
 * @group Pager
 *
 * @author Geoffrey Mon <geofbot@gmail.com>
 */
class ReverseChronologicalPagerTest extends MediaWikiLangTestCase {

	/**
	 * @covers ReverseChronologicalPager::getDateCond
	 */
	public function testGetDateCond() {
		$pager = $this->getMockForAbstractClass( 'ReverseChronologicalPager' );
		$timestamp = MWTimestamp::getInstance();
		$db = wfGetDB( DB_MASTER );

		$currYear = $timestamp->format( 'Y' );
		$currMonth = $timestamp->format( 'n' );

		// Test that getDateCond sets and returns mOffset
		$this->assertEquals( $pager->getDateCond( 2006, 6 ), $pager->mOffset );

		// Test year and month
		$pager->getDateCond( 2006, 6 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20060701000000' ) );

		// Test year, month, and day
		$pager->getDateCond( 2006, 6, 5 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20060606000000' ) );

		// Test month overflow into the next year
		$pager->getDateCond( 2006, 12 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20070101000000' ) );

		// Test day overflow to the next month
		$pager->getDateCond( 2006, 6, 30 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20060701000000' ) );

		// Test invalid month (should use end of year)
		$pager->getDateCond( 2006, -1 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20070101000000' ) );

		// Test invalid day (should use end of month)
		$pager->getDateCond( 2006, 6, 1337 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20060701000000' ) );

		// Test last day of year
		$pager->getDateCond( 2006, 12, 31 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20070101000000' ) );

		// Test invalid day that overflows to next year
		$pager->getDateCond( 2006, 12, 32 );
		$this->assertEquals( $pager->mOffset, $db->timestamp( '20070101000000' ) );

		// Test month past current month (should use previous year)
		if ( $currMonth < 5 ) {
			$pager->getDateCond( -1, 5 );
			$this->assertEquals( $pager->mOffset, $db->timestamp( $currYear - 1 . '0601000000' ) );
		}
		if ( $currMonth < 12 ) {
			$pager->getDateCond( -1, 12 );
			$this->assertEquals( $pager->mOffset, $db->timestamp( $currYear . '0101000000' ) );
		}
	}
}

