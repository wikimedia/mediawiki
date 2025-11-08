<?php

use MediaWiki\Title\Title;

/**
 * @group Database
 * @group Cache
 * @covers \MediaWiki\Cache\BacklinkCache
 */
class BacklinkCacheTest extends MediaWikiIntegrationTestCase {
	/** @var array */
	private static $backlinkCacheTest;

	public function addDBDataOnce() {
		$this->insertPage( 'Template:BacklinkCacheTestA', 'wooooooo' );
		$this->insertPage( 'Template:BacklinkCacheTestB', '{{BacklinkCacheTestA}}' );

		self::$backlinkCacheTest = $this->insertPage( 'BacklinkCacheTest_1', '{{BacklinkCacheTestB}}' );
		$this->insertPage( 'BacklinkCacheTest_2', '[[BacklinkCacheTest_1]] [[Image:test.png]]' );
		$this->insertPage( 'BacklinkCacheTest_3', '[[BacklinkCacheTest_1]]' );
		$this->insertPage( 'BacklinkCacheTest_4', '[[BacklinkCacheTest_1]]' );
		$this->insertPage( 'BacklinkCacheTest_5', '[[BacklinkCacheTest_1]]' );

		$cascade = 1;
		$this->getServiceContainer()->getWikiPageFactory()->newFromTitle( self::$backlinkCacheTest['title'] )->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'test',
			$this->getTestSysop()->getUser()
		);
	}

	public static function provideCasesForHasLink() {
		return [
			[ true, 'BacklinkCacheTest_1', 'pagelinks' ],
			[ false, 'BacklinkCacheTest_2', 'pagelinks' ],
			[ true, 'Image:test.png', 'imagelinks' ]
		];
	}

	/**
	 * @dataProvider provideCasesForHasLink
	 * @covers \MediaWiki\Cache\BacklinkCache::hasLinks
	 */
	public function testHasLink( bool $expected, string $title, string $table, string $msg = '' ) {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$this->assertEquals( $expected, $backlinkCache->hasLinks( $table ), $msg );
	}

	public static function provideCasesForGetNumLinks() {
		return [
			[ 4, 'BacklinkCacheTest_1', 'pagelinks' ],
			[ 0, 'BacklinkCacheTest_2', 'pagelinks' ],
			[ 1, 'Image:test.png', 'imagelinks' ],
		];
	}

	/**
	 * @dataProvider provideCasesForGetNumLinks
	 * @covers \MediaWiki\Cache\BacklinkCache::getNumLinks
	 */
	public function testGetNumLinks( int $numLinks, string $title, string $table ) {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$this->assertEquals( $numLinks, $backlinkCache->getNumLinks( $table ) );
	}

	public static function provideCasesForGetLinks() {
		return [
			[
				[ 'BacklinkCacheTest_2', 'BacklinkCacheTest_3', 'BacklinkCacheTest_4', 'BacklinkCacheTest_5' ],
				'BacklinkCacheTest_1',
				'pagelinks'
			],
			[
				[ 'BacklinkCacheTest_4', 'BacklinkCacheTest_5' ],
				'BacklinkCacheTest_1',
				'pagelinks',
				'BacklinkCacheTest_4'
			],
			[
				[ 'BacklinkCacheTest_2', 'BacklinkCacheTest_3' ],
				'BacklinkCacheTest_1',
				'pagelinks',
				false,
				'BacklinkCacheTest_3'
			],
			[
				[ 'BacklinkCacheTest_3', 'BacklinkCacheTest_4' ],
				'BacklinkCacheTest_1',
				'pagelinks',
				'BacklinkCacheTest_3',
				'BacklinkCacheTest_4'
			],
			[ [ 'BacklinkCacheTest_2' ], 'BacklinkCacheTest_1', 'pagelinks', false, false, 1 ],
			[ [], 'BacklinkCacheTest_2', 'pagelinks' ],
			[ [ 'BacklinkCacheTest_2' ], 'Image:test.png', 'imagelinks' ],
		];
	}

	/**
	 * @dataProvider provideCasesForGetLinks
	 * @covers \MediaWiki\Cache\BacklinkCache::getLinkPages
	 */
	public function testGetLinkPages(
		array $expectedTitles, string $title, string $table, $startId = false, $endId = false, $max = INF
	) {
		$startId = $startId ? Title::newFromText( $startId )->getId() : false;
		$endId = $endId ? Title::newFromText( $endId )->getId() : false;
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$titlesArray = iterator_to_array( $backlinkCache->getLinkPages( $table, $startId, $endId, $max ) );
		$this->assertSameSize( $expectedTitles, $titlesArray );
		$numOfTitles = count( $titlesArray );
		for ( $i = 0; $i < $numOfTitles; $i++ ) {
			$this->assertEquals( $expectedTitles[$i], $titlesArray[$i]->getDbKey() );
		}
	}

	/**
	 * @covers \MediaWiki\Cache\BacklinkCache::partition
	 */
	public function testPartition() {
		$targetId = $this->getServiceContainer()->getLinkTargetLookup()->acquireLinkTargetId(
			Title::makeTitle( NS_MAIN, 'BLCTest1234' ),
			$this->getDb()
		);
		$targetRow = [
			'tl_target_id' => $targetId,
		];
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'templatelinks' )
			->rows( [
				[ 'tl_from' => 56890, 'tl_from_namespace' => 0 ] + $targetRow,
				[ 'tl_from' => 56891, 'tl_from_namespace' => 0 ] + $targetRow,
				[ 'tl_from' => 56892, 'tl_from_namespace' => 0 ] + $targetRow,
				[ 'tl_from' => 56893, 'tl_from_namespace' => 0 ] + $targetRow,
				[ 'tl_from' => 56894, 'tl_from_namespace' => 0 ] + $targetRow,
			] )
			->caller( __METHOD__ )
			->execute();
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::makeTitle( NS_MAIN, 'BLCTest1234' ) );
		$partition = $backlinkCache->partition( 'templatelinks', 2 );
		$this->assertArrayEquals( [
			[ false, 56891 ],
			[ 56892, 56893 ],
			[ 56894, false ]
		], $partition );
	}

}
