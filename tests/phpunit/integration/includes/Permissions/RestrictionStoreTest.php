<?php

namespace MediaWiki\Tests\Integration\Permissions;

use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Cache\LinkCache;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageStore;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 *
 * See \MediaWiki\Tests\Unit\Permissions\RestrictionStoreTest for unit tests
 *
 * @covers \MediaWiki\Permissions\RestrictionStore
 */
class RestrictionStoreTest extends MediaWikiIntegrationTestCase {
	private const DEFAULT_RESTRICTION_TYPES = [ 'create', 'edit', 'move', 'upload' ];

	private WANObjectCache $wanCache;
	private ILoadBalancer $loadBalancer;
	private LinkCache $linkCache;
	private LinksMigration $linksMigration;
	private HookContainer $hookContainer;
	private CommentStore $commentStore;
	private PageStore $pageStore;

	/** @var array */
	private static $testPageRestrictionSource;
	/** @var array */
	private static $testPageRestrictionCascade;
	/** @var array */
	private static $testFileRestrictionSource;
	/** @var array */
	private static $testFileTarget;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->wanCache = $services->getMainWANObjectCache();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->linkCache = $services->getLinkCache();
		$this->linksMigration = $services->getLinksMigration();
		$this->hookContainer = $services->getHookContainer();
		$this->commentStore = $services->getCommentStore();
		$this->pageStore = $services->getPageStore();
	}

	public function addDBDataOnce() {
		self::$testPageRestrictionCascade =
			$this->insertPage( 'Template:RestrictionStoreTestA', 'wooooooo' );
		$this->insertPage( 'Template:RestrictionStoreTestB', '{{RestrictionStoreTestA}}' );

		self::$testPageRestrictionSource =
			$this->insertPage( 'RestrictionStoreTest_1', '{{RestrictionStoreTestB}}' );

		$this->updateRestrictions( self::$testPageRestrictionSource['title'], [ 'edit' => 'sysop' ] );

		self::$testFileTarget = $this->insertPage( 'File:RestrictionStoreTest.jpg', 'test file' );
		self::$testFileRestrictionSource =
			$this->insertPage( 'RestrictionStoreTest_File', '[[File:RestrictionStoreTest.jpg]]' );

		$this->updateRestrictions( self::$testFileRestrictionSource['title'], [ 'edit' => 'sysop' ], 1 );
	}

	private function newRestrictionStore( array $options = [] ) {
		return new RestrictionStore(
			new ServiceOptions( RestrictionStore::CONSTRUCTOR_OPTIONS, $options + [
				MainConfigNames::NamespaceProtection => [],
				MainConfigNames::RestrictionLevels => [ '', 'autoconfirmed', 'sysop' ],
				MainConfigNames::RestrictionTypes => self::DEFAULT_RESTRICTION_TYPES,
				MainConfigNames::SemiprotectedRestrictionLevels => [ 'autoconfirmed' ],
			] ),
			$this->wanCache,
			$this->loadBalancer,
			$this->linkCache,
			$this->linksMigration,
			$this->commentStore,
			$this->hookContainer,
			$this->pageStore
		);
	}

	private function updateRestrictions( $page, array $limit, int $cascade = 1 ) {
		$this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $page )
			->doUpdateRestrictions(
				$limit,
				[],
				$cascade,
				'test',
				$this->getTestSysop()->getUser()
			);
	}

	public function testGetCascadeProtectionSources() {
		$page = self::$testPageRestrictionCascade['title'];
		$pageSource = self::$testPageRestrictionSource['title'];

		[ $sources, $restrictions, $tlSources, $ilSources ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $page );
		$this->assertCount( 1, $sources );
		$this->assertCount( 1, $tlSources );
		$this->assertCount( 0, $ilSources );
		$this->assertTrue( $pageSource->isSamePageAs( $sources[$pageSource->getId()] ) );
		$this->assertArrayEquals( [ 'edit' => [ 'sysop' ] ], $restrictions );

		[ $sources, $restrictions, $tlSources, $ilSources ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $pageSource );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $tlSources );
		$this->assertCount( 0, $ilSources );
		$this->assertCount( 0, $restrictions );
	}

	public function testGetCascadeProtectionSourcesSpecialPage() {
		[ $sources, $restrictions, $tlSources, $ilSources ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( SpecialPage::getTitleFor( 'Whatlinkshere' ) );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $tlSources );
		$this->assertCount( 0, $ilSources );
		$this->assertCount( 0, $restrictions );
	}

	public function testGetCascadeProtectionSourcesFile() {
		$page = self::$testFileTarget['title'];
		$pageSource = self::$testFileRestrictionSource['title'];

		[ $sources, $restrictions, $tlSources, $ilSources ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $page );

		$this->assertCount( 1, $sources );
		$this->assertTrue( $pageSource->isSamePageAs( $sources[$pageSource->getId()] ) );
		$this->assertArrayEquals( [ 'edit' => [ 'sysop' ] ], $restrictions );
		$this->assertCount( 1, $ilSources );
		$this->assertCount( 0, $tlSources );

		[ $sources, $restrictions, $tlSources, $ilSources ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $pageSource );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $tlSources );
		$this->assertCount( 0, $ilSources );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @dataProvider provideLoadRestrictions
	 */
	public function testLoadRestrictions( $page, $expectedCacheSubmap, ?array $restrictions = null ) {
		$cacheKey = CacheKeyHelper::getKeyForPage( $page );

		if ( $restrictions ) {
			$this->updateRestrictions( $page, $restrictions );
		}

		$restrictionStore = $this->newRestrictionStore();
		$restrictionStore->loadRestrictions( $page );
		$wrapper = TestingAccessWrapper::newFromObject( $restrictionStore );
		$this->assertArraySubmapSame(
			$expectedCacheSubmap,
			$wrapper->cache[$cacheKey]
		);
	}

	public static function provideLoadRestrictions(): array {
		return [
			'Regular page with restrictions' => [
				Title::makeTitle( NS_MAIN, 'RestrictionStoreTest_1' ),
				[ 'restrictions' => [ 'edit' => [ 'sysop' ] ] ]
			],
			'Nonexistent page' => [
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
				[ 'create_protection' => null ]
			],
			'Nonexistent page with restrictions' => [
				PageIdentityValue::localIdentity( 0, NS_MAIN, 'X' ),
				[ 'create_protection' => [ 'expiry' => 'infinity' ] ],
				[ 'create' => 'sysop' ]
			],
		];
	}

	public function testLoadRestrictions_latest() {
		$pageSource = self::$testPageRestrictionSource['title'];
		$cacheKey = CacheKeyHelper::getKeyForPage( $pageSource );

		$restrictionStore = $this->newRestrictionStore();
		$restrictionStore->loadRestrictions( $pageSource );
		$wrapper = TestingAccessWrapper::newFromObject( $restrictionStore );
		$this->assertArraySubmapSame(
			[ 'restrictions' => [ 'edit' => [ 'sysop' ] ] ],
			$wrapper->cache[$cacheKey]
		);

		$this->updateRestrictions( $pageSource, [ 'move' => 'sysop' ] );
		$restrictionStore->loadRestrictions( $pageSource, IDBAccessObject::READ_LATEST );
		$this->assertArraySubmapSame(
			[ 'restrictions' => [ 'move' => [ 'sysop' ] ] ],
			$wrapper->cache[$cacheKey]
		);
	}
}
