<?php

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @group Database
 * @group Cache
 * @covers LinkBatch
 */
class LinkBatchTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstructEmpty() {
		$batch = new LinkBatch();

		$this->assertTrue( $batch->isEmpty() );
		$this->assertSame( 0, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstruct() {
		$batch = new LinkBatch( [
			new TitleValue( NS_MAIN, 'Foo' ),
			new TitleValue( NS_TALK, 'Bar' ),
		] );

		$this->assertFalse( $batch->isEmpty() );
		$this->assertSame( 2, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstructEmptyWithServices() {
		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class ),
			$this->createMock( LinksMigration::class )
		);

		$this->assertTrue( $batch->isEmpty() );
		$this->assertSame( 0, $batch->getSize() );
	}

	/**
	 * @covers LinkBatch::__construct()
	 * @covers LinkBatch::getSize()
	 * @covers LinkBatch::isEmpty()
	 */
	public function testConstructWithServices() {
		$batch = new LinkBatch(
			[
				new TitleValue( NS_MAIN, 'Foo' ),
				new TitleValue( NS_TALK, 'Bar' ),
			],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class ),
			$this->createMock( LinksMigration::class )
		);

		$this->assertFalse( $batch->isEmpty() );
		$this->assertSame( 2, $batch->getSize() );
	}

	/**
	 * @param iterable<LinkTarget>|iterable<PageReference> $objects
	 *
	 * @return LinkBatch
	 * @throws Exception
	 */
	private function newLinkBatch( $objects = [] ) {
		return new LinkBatch(
			$objects,
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->getServiceContainer()->getDBLoadBalancer(),
			$this->getServiceContainer()->getLinksMigration()
		);
	}

	/**
	 * @covers LinkBatch::addObj()
	 * @covers LinkBatch::getSize()
	 */
	public function testAddObj() {
		$batch = $this->newLinkBatch(
			[
				new TitleValue( NS_MAIN, 'Foo' ),
				new PageReferenceValue( NS_USER, 'Foo', PageReference::LOCAL ),
			]
		);

		$batch->addObj( new PageReferenceValue( NS_TALK, 'Bar', PageReference::LOCAL ) );
		$batch->addObj( new TitleValue( NS_MAIN, 'Foo' ) );

		$this->assertSame( 3, $batch->getSize() );
		$this->assertCount( 3, $batch->getPageIdentities() );
	}

	/**
	 * @covers LinkBatch::add()
	 * @covers LinkBatch::getSize()
	 */
	public function testAdd() {
		$batch = $this->newLinkBatch(
			[
				new TitleValue( NS_MAIN, 'Foo' )
			]
		);

		$batch->add( NS_TALK, 'Bar' );
		$batch->add( NS_MAIN, 'Foo' );

		$this->assertSame( 2, $batch->getSize() );
		$this->assertCount( 2, $batch->getPageIdentities() );
	}

	public function testExecute() {
		$existing1 = $this->getExistingTestPage( __METHOD__ . '1' )->getTitle();
		$existing2 = $this->getExistingTestPage( __METHOD__ . '2' )->getTitle();
		$nonexisting1 = $this->getNonexistingTestPage( __METHOD__ . 'x' )->getTitle();
		$nonexisting2 = $this->getNonexistingTestPage( __METHOD__ . 'y' )->getTitle();

		$cache = $this->createMock( LinkCache::class );

		$good = [];
		$bad = [];

		$cache->expects( $this->exactly( 2 ) )
			->method( 'addGoodLinkObjFromRow' )
			->willReturnCallback( static function ( TitleValue $title, $row ) use ( &$good ) {
				$good["$title"] = $title;
			} );

		$cache->expects( $this->exactly( 2 ) )
			->method( 'addBadLinkObj' )
			->willReturnCallback( static function ( TitleValue $title ) use ( &$bad ) {
				$bad["$title"] = $title;
			} );

		$services = $this->getServiceContainer();

		$batch = new LinkBatch(
			[],
			$cache,
			// TODO: This would be even better with mocked dependencies
			$services->getTitleFormatter(),
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getDBLoadBalancer()
		);

		$batch->addObj( $existing1 );
		$batch->addObj( $existing2 );
		$batch->addObj( $nonexisting1 );
		$batch->addObj( $nonexisting2 );

		// Bad stuff, should be skipped!
		$batch->add( NS_MAIN, '_X' );
		$batch->add( NS_MAIN, 'X_' );
		$batch->add( NS_MAIN, '' );

		@$batch->execute();

		$this->assertArrayHasKey( $existing1->getTitleValue()->__toString(), $good );
		$this->assertArrayHasKey( $existing2->getTitleValue()->__toString(), $good );

		$this->assertArrayHasKey( $nonexisting1->getTitleValue()->__toString(), $bad );
		$this->assertArrayHasKey( $nonexisting2->getTitleValue()->__toString(), $bad );

		$expected = array_map(
			[ CacheKeyHelper::class, 'getKeyForPage' ],
			[ $existing1, $existing2, $nonexisting1, $nonexisting2 ]
		);

		$actual = array_map(
			[ CacheKeyHelper::class, 'getKeyForPage' ],
			$batch->getPageIdentities()
		);

		sort( $expected );
		sort( $actual );

		$this->assertEquals( $expected, $actual );
	}

	public function testDoGenderQueryWithEmptyLinkBatch() {
		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createNoOpMock( Language::class ),
			$this->createNoOpMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class )
		);

		$this->assertFalse( $batch->doGenderQuery() );
	}

	public function testDoGenderQueryWithLanguageWithoutGenderDistinction() {
		$language = $this->createMock( Language::class );
		$language->method( 'needsGenderDistinction' )->willReturn( false );

		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$language,
			$this->createNoOpMock( GenderCache::class ),
			$this->createMock( ILoadBalancer::class )
		);
		$batch->addObj(
			new TitleValue( NS_MAIN, 'Foo' )
		);

		$this->assertFalse( $batch->doGenderQuery() );
	}

	public function testDoGenderQueryWithLanguageWithGenderDistinction() {
		$language = $this->createMock( Language::class );
		$language->method( 'needsGenderDistinction' )->willReturn( true );

		$genderCache = $this->createMock( GenderCache::class );
		$genderCache->expects( $this->once() )->method( 'doLinkBatch' );

		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$language,
			$genderCache,
			$this->createMock( ILoadBalancer::class ),
			$this->createMock( LinksMigration::class )
		);
		$batch->addObj(
			new TitleValue( NS_MAIN, 'Foo' )
		);

		$this->assertTrue( $batch->doGenderQuery() );
	}

	public function provideBadObjects() {
		yield 'null' => [ null ];
		yield 'empty' => [ Title::makeTitle( NS_MAIN, '' ) ];
		yield 'bad user' => [ Title::makeTitle( NS_USER, '#12345' ) ];
		yield 'section' => [ new TitleValue( NS_MAIN, '', '#See_also' ) ];
		yield 'special' => [ new TitleValue( NS_SPECIAL, 'RecentChanges' ) ];
	}

	/**
	 * @dataProvider provideBadObjects
	 */
	public function testAddBadObj( $obj ) {
		$linkBatch = $this->newLinkBatch();
		$linkBatch->addObj( $obj );
		$linkBatch->execute();
		$this->addToAssertionCount( 1 );
	}

	public function provideBadDBKeys() {
		yield 'empty' => [ '' ];
		yield 'section' => [ '#See_also' ];
		yield 'pipe' => [ 'foo|bar' ];
	}

	/**
	 * @dataProvider provideBadDBKeys
	 */
	public function testAddBadDBKeys( $key ) {
		$linkBatch = $this->newLinkBatch();
		$linkBatch->add( NS_MAIN, $key );
		$linkBatch->execute();
		$this->addToAssertionCount( 1 );
	}
}
