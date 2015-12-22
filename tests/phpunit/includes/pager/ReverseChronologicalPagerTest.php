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

		// Test that getDateCond sets and returns mOffset
		$this->assertEquals( $pager->getDateCond( 2006 ), $pager->mOffset );

		// Test year
		$pager->getDateCond( 2006, -1 );
		$this->assertEquals( $pager->mOffset, '20070101000000' );

		// Test year and month
		$pager->getDateCond( 2006, 6 );
		$this->assertEquals( $pager->mOffset, '20060701000000' );

		// Test year, month, and day
		$pager->getDateCond( 2006, 6, 5 );
		$this->assertEquals( $pager->mOffset, '20060606000000' );

		// Test month overflow into the next year
		$pager->getDateCond( 2006, 12 );
		$this->assertEquals( $pager->mOffset, '20070101000000' );

		// Test day overflow to the next month
		$pager->getDateCond( 2006, 6, 30 );
		$this->assertEquals( $pager->mOffset, '20060701000000' );

		// Test invalid year
		$currYearTimestamp = intval( date( 'Y' ) ) + 1 . '0101000000';
		$pager->getDateCond( -1337 );
		$this->assertEquals( $pager->mOffset, $currYearTimestamp );

		// Test invalid month
		$pager->getDateCond( 2006, -1 );
		$this->assertEquals( $pager->mOffset, '20070101000000' );

		// Test invalid day
		$pager->getDateCond( 2006, 6, 1337 );
		$this->assertEquals( $pager->mOffset, '20060701000000' );
	}

}

