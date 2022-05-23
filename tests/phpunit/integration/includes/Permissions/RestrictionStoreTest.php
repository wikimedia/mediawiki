<?php

namespace MediaWiki\Tests\Integration\Permissions;

use CommentStore;
use IDBAccessObject;
use LinkCache;
use MediaWiki\Cache\CacheKeyHelper;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageStore;
use MediaWiki\Permissions\RestrictionStore;
use MediaWikiIntegrationTestCase;
use SpecialPage;
use Title;
use WANObjectCache;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 *
 * See \MediaWiki\Tests\Unit\Permissions\RestrictionStoreTest
 * for unit tests
 *
 * @coversDefaultClass \MediaWiki\Permissions\RestrictionStore
 */
class RestrictionStoreTest extends MediaWikiIntegrationTestCase {
	private const DEFAULT_RESTRICTION_TYPES = [ 'create', 'edit', 'move', 'upload' ];

	/** @var WANObjectCache */
	private $wanCache;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LinkCache */
	private $linkCache;

	/** @var LinksMigration */
	private $linksMigration;

	/** @var HookContainer */
	private $hookContainer;

	/** @var CommentStore */
	private $commentStore;

	/** @var PageStore */
	private $pageStore;

	private static $testPageRestrictionSource;
	private static $testPageRestrictionCascade;

	protected function setUp(): void {
		parent::setUp();

		$services = $this->getServiceContainer();
		$this->wanCache = $services->getMainWANObjectCache();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->linkCache = $services->getLinkCache();
		$this->linksMigration = $services->getLinksMigration();
		$this->commentStore = $services->getCommentStore();
		$this->hookContainer = $services->getHookContainer();
		$this->pageStore = $services->getPageStore();
	}

	public function addDBDataOnce() {
		self::$testPageRestrictionCascade =
			$this->insertPage( 'Template:RestrictionStoreTestA', 'wooooooo' );
		$this->insertPage( 'Template:RestrictionStoreTestB', '{{RestrictionStoreTestA}}' );

		self::$testPageRestrictionSource =
			$this->insertPage( 'RestrictionStoreTest_1', '{{RestrictionStoreTestB}}' );

		$this->updateRestrictions( self::$testPageRestrictionSource['title'], [ 'edit' => 'sysop' ] );
	}

	private function newRestrictionStore( array $options = [] ) {
		return new RestrictionStore(
			new ServiceOptions( RestrictionStore::CONSTRUCTOR_OPTIONS, $options + [
					'NamespaceProtection' => [],
					'RestrictionLevels' => [ '', 'autoconfirmed', 'sysop' ],
					'RestrictionTypes' => self::DEFAULT_RESTRICTION_TYPES,
					'SemiprotectedRestrictionLevels' => [ 'autoconfirmed' ],
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

	/**
	 * @covers ::getCascadeProtectionSources
	 * @covers ::getCascadeProtectionSourcesInternal
	 */
	public function testGetCascadeProtectionSources() {
		$page = self::$testPageRestrictionCascade['title'];
		$pageSource = self::$testPageRestrictionSource['title'];

		[ $sources, $restrictions ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $page );
		$this->assertCount( 1, $sources );
		$this->assertTrue( $pageSource->isSamePageAs( $sources[$pageSource->getId()] ) );
		$this->assertArrayEquals( [ 'edit' => [ 'sysop' ] ], $restrictions );

		[ $sources, $restrictions ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( $pageSource );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::getCascadeProtectionSources
	 * @covers ::getCascadeProtectionSourcesInternal
	 */
	public function testGetCascadeProtectionSourcesSpecialPage() {
		[ $sources, $restrictions ] = $this->newRestrictionStore()
			->getCascadeProtectionSources( SpecialPage::getTitleFor( 'Whatlinkshere' ) );
		$this->assertCount( 0, $sources );
		$this->assertCount( 0, $restrictions );
	}

	/**
	 * @covers ::loadRestrictions
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

	public function provideLoadRestrictions(): array {
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

	/**
	 * @covers ::loadRestrictions
	 */
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
