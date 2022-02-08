<?php

use MediaWiki\Page\PageReferenceValue;

/**
 * @group Database
 * @group Cache
 * @covers BacklinkCache
 */
class BacklinkCacheTest extends MediaWikiIntegrationTestCase {
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
		WikiPage::factory( self::$backlinkCacheTest['title'] )->doUpdateRestrictions(
			[ 'edit' => 'sysop' ],
			[],
			$cascade,
			'test',
			$this->getTestSysop()->getUser()
		);
	}

	public function provideCasesForHasLink() {
		return [
			[ true, 'BacklinkCacheTest_1', 'pagelinks' ],
			[ false, 'BacklinkCacheTest_2', 'pagelinks' ],
			[ true, 'Image:test.png', 'imagelinks' ]
		];
	}

	/**
	 * @dataProvider provideCasesForHasLink
	 * @covers BacklinkCache::hasLinks
	 */
	public function testHasLink( bool $expected, string $title, string $table, string $msg = '' ) {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$this->assertEquals( $expected, $backlinkCache->hasLinks( $table ), $msg );
	}

	public function provideCasesForGetNumLinks() {
		return [
			[ 4, 'BacklinkCacheTest_1', 'pagelinks' ],
			[ 1, 'BacklinkCacheTest_1', 'pagelinks', 1 ],
			[ 0, 'BacklinkCacheTest_2', 'pagelinks' ],
			[ 1, 'Image:test.png', 'imagelinks' ],
		];
	}

	/**
	 * @dataProvider provideCasesForGetNumLinks
	 * @covers BacklinkCache::getNumLinks
	 */
	public function testGetNumLinks( int $numLinks, string $title, string $table, $max = INF ) {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$this->assertEquals( $numLinks, $backlinkCache->getNumLinks( $table, $max ) );
	}

	public function provideCasesForGetLinks() {
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
	 * @covers BacklinkCache::getNumLinks
	 */
	public function testGetLinks(
		array $expectedTitles, string $title, string $table, $startId = false, $endId = false, $max = INF
	) {
		$startId = $startId ? Title::newFromText( $startId )->getId() : false;
		$endId = $endId ? Title::newFromText( $endId )->getId() : false;
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$titlesArray = iterator_to_array( $backlinkCache->getLinks( $table, $startId, $endId, $max ) );
		$this->assertSame( count( $expectedTitles ), count( $titlesArray ) );
		for ( $i = 0; $i < count( $titlesArray ); $i++ ) {
			$this->assertEquals( $expectedTitles[$i], $titlesArray[$i]->getDbKey() );
		}
	}

	/**
	 * @dataProvider provideCasesForGetLinks
	 * @covers BacklinkCache::getLinkPages
	 */
	public function testGetLinkPages(
		array $expectedTitles, string $title, string $table, $startId = false, $endId = false, $max = INF
	) {
		$startId = $startId ? Title::newFromText( $startId )->getId() : false;
		$endId = $endId ? Title::newFromText( $endId )->getId() : false;
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( $title ) );
		$titlesArray = iterator_to_array( $backlinkCache->getLinkPages( $table, $startId, $endId, $max ) );
		$this->assertSame( count( $expectedTitles ), count( $titlesArray ) );
		for ( $i = 0; $i < count( $titlesArray ); $i++ ) {
			$this->assertEquals( $expectedTitles[$i], $titlesArray[$i]->getDbKey() );
		}
	}

	/**
	 * @covers BacklinkCache::partition
	 */
	public function testPartition() {
		$this->db->insert( 'templatelinks', [
			[ 'tl_from' => 56890, 'tl_from_namespace' => 0, 'tl_namespace' => 0, 'tl_title' => 'BLCTest1234' ],
			[ 'tl_from' => 56891, 'tl_from_namespace' => 0, 'tl_namespace' => 0, 'tl_title' => 'BLCTest1234' ],
			[ 'tl_from' => 56892, 'tl_from_namespace' => 0, 'tl_namespace' => 0, 'tl_title' => 'BLCTest1234' ],
			[ 'tl_from' => 56893, 'tl_from_namespace' => 0, 'tl_namespace' => 0, 'tl_title' => 'BLCTest1234' ],
			[ 'tl_from' => 56894, 'tl_from_namespace' => 0, 'tl_namespace' => 0, 'tl_title' => 'BLCTest1234' ],
		] );
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( 'BLCTest1234' ) );
		$partition = $backlinkCache->partition( 'templatelinks', 2 );
		$this->assertArrayEquals( [
			[ false, 56891 ],
			[ 56892, 56893 ],
			[ 56894, false ]
		], $partition );
	}

	/**
	 * @covers BacklinkCache::getCascadeProtectedLinks
	 */
	public function testGetCascadeProtectedLinks() {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( 'Template:BacklinkCacheTestA' ) );
		$iterator = $backlinkCache->getCascadeProtectedLinks();
		$array = iterator_to_array( $iterator );
		$this->assertCount( 1, $array );
		$this->assertTrue( self::$backlinkCacheTest['title']->isSamePageAs( $array[0] ) );
	}

	/**
	 * @covers BacklinkCache::getCascadeProtectedLinkPages
	 */
	public function testGetCascadeProtectedLinkPages() {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::newFromText( 'Template:BacklinkCacheTestA' ) );
		$iterator = $backlinkCache->getCascadeProtectedLinkPages();
		$array = iterator_to_array( $iterator );
		$this->assertCount( 1, $array );
		$this->assertTrue( self::$backlinkCacheTest['title']->isSamePageAs( $array[0] ) );
	}

	/**
	 * @covers BacklinkCache::get
	 */
	public function testGet() {
		$page = PageReferenceValue::localReference( NS_CATEGORY, "kittens" );
		$cache = BacklinkCache::get( $page );
		$this->assertTrue( $cache->getPage()->isSamePageAs( $page ) );
	}

}
