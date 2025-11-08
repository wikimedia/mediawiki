<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Pager\RangeChronologicalPager;

/**
 * Test class for RangeChronologicalPagerTest logic.
 *
 * @group Pager
 * @group Database
 *
 * @author Geoffrey Mon <geofbot@gmail.com>
 */
class RangeChronologicalPagerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Pager\RangeChronologicalPager::getDateCond
	 * @dataProvider getDateCondProvider
	 */
	public function testGetDateCond( $inputYear, $inputMonth, $inputDay, $expected ) {
		$pager = $this->getMockForAbstractClass( RangeChronologicalPager::class );
		$this->assertEquals(
			$expected,
			wfTimestamp( TS_MW, $pager->getDateCond( $inputYear, $inputMonth, $inputDay ) )
		);
	}

	/**
	 * Data provider in [ input year, input month, input day, expected timestamp output ] format
	 */
	public static function getDateCondProvider() {
		return [
			[ 2016, 12, 5, '20161206000000' ],
			[ 2016, 12, 31, '20170101000000' ],
			[ 2016, 12, 1337, '20170101000000' ],
			[ 2016, 1337, 1337, '20170101000000' ],
			[ 2016, 1337, -1, '20170101000000' ],
			[ 2016, 12, 32, '20170101000000' ],
			[ 2016, 12, -1, '20170101000000' ],
			[ 2016, -1, -1, '20170101000000' ],
		];
	}

	/**
	 * @covers \MediaWiki\Pager\RangeChronologicalPager::getDateRangeCond
	 * @dataProvider getDateRangeCondProvider
	 */
	public function testGetDateRangeCond( $start, $end, $expected ) {
		$pager = $this->getMockForAbstractClass( RangeChronologicalPager::class );
		$this->assertArrayEquals( $expected, $pager->getDateRangeCond( $start, $end ) );
	}

	/**
	 * Data provider in [ start, end, [ expected output has start condition, has end cond ] ] format
	 */
	public static function getDateRangeCondProvider() {
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();

		return [
			[
				'20161201000000',
				'20161202235959',
				[
					$dbw->buildComparison( '>=', [ '' => $dbw->timestamp( '20161201000000' ) ] ),
					$dbw->buildComparison( '<', [ '' => $dbw->timestamp( '20161203000000' ) ] ),
				],
			],
			[
				'',
				'20161202235959',
				[
					$dbw->buildComparison( '<', [ '' => $dbw->timestamp( '20161203000000' ) ] ),
				],
			],
			[
				'20161201000000',
				'',
				[
					$dbw->buildComparison( '>=', [ '' => $dbw->timestamp( '20161201000000' ) ] ),
				],
			],
			[ '', '', [] ],
		];
	}

	/**
	 * @covers \MediaWiki\Pager\RangeChronologicalPager::getDateRangeCond
	 * @dataProvider getDateRangeCondInvalidProvider
	 */
	public function testGetDateRangeCondInvalid( $start, $end ) {
		$pager = $this->getMockForAbstractClass( RangeChronologicalPager::class );
		$this->assertNull( $pager->getDateRangeCond( $start, $end ) );
	}

	public static function getDateRangeCondInvalidProvider() {
		return [
			[ '-2016-12-01', '2017-12-01', ],
			[ '2016-12-01', '-2017-12-01', ],
			[ 'abcdefghij', 'klmnopqrstu', ],
		];
	}

}
