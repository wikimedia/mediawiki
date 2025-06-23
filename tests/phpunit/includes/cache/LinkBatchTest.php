<?php

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkBatch;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @group Database
 * @group Cache
 * @covers \MediaWiki\Cache\LinkBatch
 */
class LinkBatchTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\Cache\LinkBatch::__construct()
	 * @covers \MediaWiki\Cache\LinkBatch::getSize()
	 * @covers \MediaWiki\Cache\LinkBatch::isEmpty()
	 */
	public function testConstructEmptyWithServices() {
		$batch = new LinkBatch(
			[],
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		$this->assertTrue( $batch->isEmpty() );
		$this->assertSame( 0, $batch->getSize() );
	}

	/**
	 * @covers \MediaWiki\Cache\LinkBatch::__construct()
	 * @covers \MediaWiki\Cache\LinkBatch::getSize()
	 * @covers \MediaWiki\Cache\LinkBatch::isEmpty()
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
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
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
			$this->getServiceContainer()->getConnectionProvider(),
			$this->getServiceContainer()->getLinksMigration(),
			$this->getServiceContainer()->getTempUserDetailsLookup(),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
	}

	/**
	 * @covers \MediaWiki\Cache\LinkBatch::addObj()
	 * @covers \MediaWiki\Cache\LinkBatch::getSize()
	 */
	public function testAddObj() {
		$batch = $this->newLinkBatch(
			[
				new TitleValue( NS_MAIN, 'Foo' ),
				PageReferenceValue::localReference( NS_USER, 'Foo' ),
			]
		);

		$batch->addObj( PageReferenceValue::localReference( NS_TALK, 'Bar' ) );
		$batch->addObj( new TitleValue( NS_MAIN, 'Foo' ) );

		$this->assertSame( 3, $batch->getSize() );
		$this->assertCount( 3, $batch->getPageIdentities() );
	}

	/**
	 * @covers \MediaWiki\Cache\LinkBatch::add()
	 * @covers \MediaWiki\Cache\LinkBatch::getSize()
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
			$services->getConnectionProvider(),
			$services->getLinksMigration(),
			$services->getTempUserDetailsLookup(),
			LoggerFactory::getInstance( 'LinkBatch' )
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
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
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
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
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
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( LinksMigration::class ),
			$this->createNoOpMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
		$batch->addObj(
			new TitleValue( NS_MAIN, 'Foo' )
		);

		$this->assertTrue( $batch->doGenderQuery() );
	}

	public static function provideBadObjects() {
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

	public static function provideBadDBKeys() {
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

	public function testAddUser(): void {
		$users = [
			new UserIdentityValue( 1, 'TestUser' ),
			new UserIdentityValue( 2, 'OtherUser' ),
		];

		$tempUserDetailsLookup = $this->createMock( TempUserDetailsLookup::class );
		$tempUserDetailsLookup->expects( $this->once() )
			->method( 'preloadExpirationStatus' )
			->with( [
				'TestUser' => new UserIdentityValue( 1, 'TestUser' ),
				'OtherUser' => new UserIdentityValue( 2, 'OtherUser' ),
			] );

		$services = $this->getServiceContainer();

		$batch = new LinkBatch(
			[],
			$services->getLinkCache(),
			$services->getTitleFormatter(),
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getConnectionProvider(),
			$services->getLinksMigration(),
			$tempUserDetailsLookup,
			LoggerFactory::getInstance( 'LinkBatch' )
		);

		foreach ( $users as $user ) {
			$batch->addUser( $user );
		}

		$batch->execute();

		$pagesByNamespace = [];
		foreach ( $batch->getPageIdentities() as $page ) {
			$pagesByNamespace[$page->getNamespace()][] = $page->getDBkey();
		}

		$this->assertCount( 2, $pagesByNamespace );
		$this->assertSame( [ 'TestUser', 'OtherUser' ], $pagesByNamespace[NS_USER] );
		$this->assertSame( [ 'TestUser', 'OtherUser' ], $pagesByNamespace[NS_USER_TALK] );
	}
}
