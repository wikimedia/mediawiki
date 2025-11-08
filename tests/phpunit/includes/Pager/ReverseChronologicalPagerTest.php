<?php

use MediaWiki\Pager\ReverseChronologicalPager;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\TestingAccessWrapper;

/**
 * Test class for ReverseChronologicalPagerTest methods.
 *
 * @group Pager
 * @group Database
 *
 * @author Geoffrey Mon <geofbot@gmail.com>
 */
class ReverseChronologicalPagerTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Pager\ReverseChronologicalPager::getDateCond
	 * @dataProvider provideGetDateCond
	 */
	public function testGetDateCond( $params, $expected ) {
		$pager = $this->getMockForAbstractClass( ReverseChronologicalPager::class );
		$pagerWrapper = TestingAccessWrapper::newFromObject( $pager );

		$pager->getDateCond( ...$params );
		$this->assertEquals( $pagerWrapper->endOffset, $this->getDb()->timestamp( $expected ) );
	}

	/**
	 * Data provider in description => [ [ param1, ... ], expected output ] format
	 */
	public static function provideGetDateCond() {
		yield 'Test year and month' => [
			[ 2006, 6 ], '20060701000000'
		];
		yield 'Test year, month, and day' => [
			[ 2006, 6, 5 ], '20060606000000'
		];
		yield 'Test month overflow into the next year' => [
			[ 2006, 12 ], '20070101000000'
		];
		yield 'Test day overflow to the next month' => [
			[ 2006, 6, 30 ], '20060701000000'
		];
		yield 'Test invalid month (should use end of year)' => [
			[ 2006, -1 ], '20070101000000'
		];
		yield 'Test invalid day (should use end of month)' => [
			[ 2006, 6, 1337 ], '20060701000000'
		];
		yield 'Test last day of year' => [
			[ 2006, 12, 31 ], '20070101000000'
		];
		yield 'Test invalid day that overflows to next year' => [
			[ 2006, 12, 32 ], '20070101000000'
		];
		yield '3-digit year, T287621' => [
			[ 720, 1, 5 ], '07200106000000'
		];
		yield 'Y2K38' => [
			[ 2042, 1, 5 ], '20420106000000'
		];
	}

	/**
	 * @covers \MediaWiki\Pager\ReverseChronologicalPager::getDateCond
	 */
	public function testGetDateCondSpecial() {
		$pager = $this->getMockForAbstractClass( ReverseChronologicalPager::class );
		$pagerWrapper = TestingAccessWrapper::newFromObject( $pager );
		$timestamp = MWTimestamp::getInstance();
		$db = $this->getDb();

		$currYear = $timestamp->format( 'Y' );
		$currMonth = $timestamp->format( 'n' );

		// Test that getDateCond sets and returns offset
		$this->assertEquals( $pager->getDateCond( 2006, 6 ), $pagerWrapper->endOffset );

		// Test month past current month (should use previous year)
		if ( $currMonth < 5 ) {
			$pager->getDateCond( -1, 5 );
			$this->assertEquals( $pagerWrapper->endOffset, $db->timestamp( $currYear - 1 . '0601000000' ) );
		}
		if ( $currMonth < 12 ) {
			$pager->getDateCond( -1, 12 );
			$this->assertEquals( $pagerWrapper->endOffset, $db->timestamp( $currYear . '0101000000' ) );
		}
	}
}
