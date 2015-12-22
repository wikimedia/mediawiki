<?php

/**
 * Test class for ReverseChronologicalPagerTest methods.
 *
 * @group Pager
 *
 * @author Geoffrey Mon <geofbot@gmail.com>
 */
class ReverseChronologicalPagerTest extends MediaWikiLangTestCase {

    /** @const String used to test if mOffset is changed */
    const UNCHANGED_OFFSET = 'This should be intact after $this->getDateCond.';

	/**
	 * @covers ReverseChronologicalPager::getDateCond
	 */
	public function testGetDateCond() {
		$pager = $this->getMockForAbstractClass( 'ReverseChronologicalPager' );

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

        // Test invalid month
        $pager->getDateCond( 2006, -1 );
        $this->assertEquals( $pager->mOffset, '20070101000000' );

        // Test invalid day
        $pager->getDateCond( 2006, 6, 1337 );
        $this->assertEquals( $pager->mOffset, '20060701000000' );

        // Check if function returns on invalid year and month
        $pager->mOffset = self::UNCHANGED_OFFSET;
        $pager->getDateCond( -1337, 42 );
        $this->assertEquals( $pager->mOffset, self::UNCHANGED_OFFSET );
	}

}

