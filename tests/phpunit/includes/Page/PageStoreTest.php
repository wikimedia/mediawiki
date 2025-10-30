<?php
namespace MediaWiki\Tests\Page;

use Exception;
use InvalidArgumentException;
use LinkCacheTestTrait;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageStore;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Stats\Metrics\MetricInterface;
use Wikimedia\Stats\StatsFactory;

/**
 * @group Database
 */
class PageStoreTest extends MediaWikiIntegrationTestCase {

	use MockTitleTrait;
	use LinkCacheTestTrait;

	private StatsFactory $statsFactory;
	private MetricInterface $linkCacheAccesses;

	protected function setUp(): void {
		parent::setUp();
		$this->statsFactory = StatsFactory::newNull();

		$this->linkCacheAccesses = $this->statsFactory->getCounter( 'pagestore_linkcache_accesses_total' );
	}

	/**
	 * @param array $options
	 * @param array $params
	 *
	 * @return PageStore
	 * @throws Exception
	 */
	private function getPageStore( $options = [], $params = [] ) {
		$services = $this->getServiceContainer();

		$serviceOptions = new ServiceOptions(
			PageStore::CONSTRUCTOR_OPTIONS,
			$options + [
				MainConfigNames::LanguageCode => $services->getContentLanguageCode()->toString(),
				MainConfigNames::PageLanguageUseDB => true,
			]
		);

		return new PageStore(
			$serviceOptions,
			$params['dbLoadBalancer'] ?? $services->getDBLoadBalancer(),
			$services->getNamespaceInfo(),
			$services->getTitleParser(),
			array_key_exists( 'linkCache', $params )
				? $params['linkCache']
				: $services->getLinkCache(),
			$this->statsFactory,
			$params['wikiId'] ?? WikiAwareEntity::LOCAL
		);
	}

	private function assertSamePage( PageIdentity $expected, PageIdentity $actual ) {
		// NOTE: Leave it to the caller to compare the wiki IDs. $expected may be local
		//       even if $actual belongs to a (pretend) sister site.
		$expWiki = $expected->getWikiId();
		$actWiki = $actual->getWikiId();

		$this->assertSame( $expected->getId( $expWiki ), $actual->getId( $actWiki ) );
		$this->assertSame( $expected->getNamespace(), $actual->getNamespace() );
		$this->assertSame( $expected->getDBkey(), $actual->getDBkey() );

		if ( $expected instanceof PageRecord ) {
			$this->assertInstanceOf( PageRecord::class, $actual );

			/** @var PageRecord $actual */
			$this->assertSame( $expected->getLatest( $expWiki ), $actual->getLatest( $actWiki ) );
			$this->assertSame( $expected->getLanguage(), $actual->getLanguage() );
			$this->assertSame( $expected->getTouched(), $actual->getTouched() );
		}
	}

	/**
	 * Test that we get a PageIdentity for a link referencing a non-existing page
	 * @covers \MediaWiki\Page\PageStore::getPageForLink
	 */
	public function testGetPageForLink_nonexisting() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$pageStore = $this->getPageStore();

		$page = $pageStore->getPageForLink( $nonexistingPage->getTitle() );

		$this->assertTrue( $page->canExist() );
		$this->assertFalse( $page->exists() );

		$this->assertSamePage( $nonexistingPage->getTitle(), $page );
	}

	/**
	 * Test that we get a PageRecord for a link to an existing page.
	 * @covers \MediaWiki\Page\PageStore::getPageForLink
	 */
	public function testGetPageForLink_existing() {
		$existingPage = $this->getExistingTestPage();
		$pageStore = $this->getPageStore();

		$page = $pageStore->getPageForLink( $existingPage->getTitle() );

		$this->assertTrue( $page->exists() );
		$this->assertInstanceOf( PageRecord::class, $page );
		$this->assertSamePage( $existingPage->toPageRecord(), $page );
	}

	/**
	 * Test that getPageForLink() can get a PageIdentity from another wiki
	 * @covers \MediaWiki\Page\PageStore::getPageForLink
	 */
	public function testGetPageForLink_crossWiki() {
		$wikiId = $this->getDb()->getDomainID(); // pretend sister site

		$nonexistingPage = $this->getNonexistingTestPage();
		$pageStore = $this->getPageStore( [], [ 'wikiId' => $wikiId, 'linkCache' => null ] );

		$page = $pageStore->getPageForLink( $nonexistingPage->getTitle() );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $nonexistingPage->getTitle(), $page );
	}

	/**
	 * Test that getPageForLink() maps NS_MEDIA to NS_FILE
	 * @covers \MediaWiki\Page\PageStore::getPageForLink
	 */
	public function testGetPageForLink_media() {
		$link = new TitleValue( NS_MEDIA, 'Test913847659234.jpg' );
		$pageStore = $this->getPageStore();

		$page = $pageStore->getPageForLink( $link );

		$this->assertTrue( $page->canExist() );
		$this->assertSame( NS_FILE, $page->getNamespace() );
		$this->assertSame( $link->getDBkey(), $page->getDBkey() );
	}

	public static function provideInvalidLinks() {
		yield 'section link' => [ new TitleValue( NS_MAIN, '', '#References' ) ];
		yield 'special page' => [ new TitleValue( NS_SPECIAL, 'Test' ) ];
		yield 'interwiki link' => [ new TitleValue( NS_MAIN, 'Test', '', 'acme' ) ];
	}

	/**
	 * Test that getPageForLink() throws InvalidArgumentException when presented with
	 * a link that does not refer to a proper page.
	 *
	 * @dataProvider provideInvalidLinks
	 * @covers \MediaWiki\Page\PageStore::getPageForLink
	 */
	public function testGetPageForLink_invalid( $link ) {
		$pageStore = $this->getPageStore();

		$this->expectException( InvalidArgumentException::class );
		$pageStore->getPageForLink( $link );
	}

	/**
	 * Test that we get a PageRecord for an existing page by name
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_existing() {
		$existingPage = $this->getExistingTestPage();
		$ns = $existingPage->getNamespace();
		$dbkey = $existingPage->getDBkey();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$this->assertSame( $page->getId(), $linkCache->getGoodLinkID( $page ) );
	}

	/**
	 * Test that we get a PageRecord for an existing page by name
	 * with no LinkCache provided.
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_existing_noLinkCache() {
		$existingPage = $this->getExistingTestPage();
		$ns = $existingPage->getNamespace();
		$dbkey = $existingPage->getDBkey();

		$pageStore = $this->getPageStore( [], [ 'linkCache' => null ] );
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$this->assertSame( $page->getId(), $linkCache->getGoodLinkID( $page ) );
		$this->assertSame( 0, $this->linkCacheAccesses->getSampleCount() );
	}

	/**
	 * Test that we get null if we look up a non-existing page by name
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_nonexisting() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$ns = $nonexistingPage->getNamespace();
		$dbkey = $nonexistingPage->getDBkey();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertNull( $page );

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$this->assertTrue( $linkCache->isBadLink( $nonexistingPage ) );
	}

	/**
	 * Test that we get null if we look up a page known to be not existing,
	 * without hitting the database.
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_nonexisting_cached() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$ns = $nonexistingPage->getNamespace();
		$dbkey = $nonexistingPage->getDBkey();

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$linkCache->addBadLinkObj( $nonexistingPage );

		$mockLB = $this->createNoOpMock( LoadBalancer::class );
		$pageStore = $this->getPageStore( [], [ 'doLoadBalancer' => $mockLB ] );
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertNull( $page );
		$this->assertTrue( $linkCache->isBadLink( $nonexistingPage ) );
		$this->assertSame( 1, $this->linkCacheAccesses->getSampleCount() );
	}

	/**
	 * Test that we get a PageRecord from a cached row
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_cachedFullRow() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$ns = $nonexistingPage->getNamespace();
		$dbkey = $nonexistingPage->getDBkey();

		$row = (object)[
			'page_id' => 8,
			'page_namespace' => $ns,
			'page_title' => $dbkey,
			'page_is_redirect' => 0,
			'page_is_new' => 1,
			'page_touched' => '12345',
			'page_links_updated' => '12345',
			'page_latest' => 118,
			'page_len' => 155,
			'page_content_model' => CONTENT_FORMAT_TEXT,
			'page_lang' => 'xyz',
		];

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$linkCache->addGoodLinkObjFromRow( $nonexistingPage, $row );

		$mockLB = $this->createNoOpMock( LoadBalancer::class );

		$pageStore = $this->getPageStore( [], [ 'doLoadBalancer' => $mockLB ] );
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertSame( $row->page_id, $page->getId() );
		$this->assertSame( $row->page_namespace, $page->getNamespace() );
		$this->assertSame( $row->page_title, $page->getDBkey() );
		$this->assertSame( $row->page_latest, $page->getLatest() );
		$this->assertSame( 1, $this->linkCacheAccesses->getSampleCount() );
	}

	/**
	 * Test that we get a PageRecord when an incomplete row exists in the cache
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_cachedFakeRow() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$ns = $nonexistingPage->getNamespace();
		$dbkey = $nonexistingPage->getDBkey();

		$linkCache = $this->getServiceContainer()->getLinkCache();
		$linkCache->clearLink( $nonexistingPage );

		// Has all fields needed by LinkCache, but not all fields needed by PageStore.
		// This may happen when legacy code injects rows directly into LinkCache.
		$this->addGoodLinkObject( 8, $nonexistingPage );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertSame( 8, $page->getId() );
		$this->assertSame( $nonexistingPage->getNamespace(), $page->getNamespace() );
		$this->assertSame( $nonexistingPage->getDBkey(), $page->getDBkey() );
		$this->assertSame( 1, $this->linkCacheAccesses->getSampleCount() );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getPageByText
	 */
	public function testGetPageByText_existing() {
		$existingPage = $this->getExistingTestPage();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByText( $existingPage->getTitle()->getPrefixedText() );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );

		$page = $pageStore->getExistingPageByText( $existingPage->getTitle()->getPrefixedText() );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getPageByText
	 */
	public function testGetPageByText_nonexisting() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByText( $nonexistingPage->getTitle()->getPrefixedText() );
		$this->assertFalse( $page->exists() );
		$this->assertTrue( $nonexistingPage->isSamePageAs( $page ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getExistingPageByText
	 */
	public function testGetExistingPageByText_existing() {
		$existingPage = $this->getExistingTestPage();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getExistingPageByText( $existingPage->getTitle()->getPrefixedText() );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getExistingPageByText
	 */
	public function testGetExistingPageByText_nonexisting() {
		$nonexistingPage = $this->getNonexistingTestPage();
		$pageStore = $this->getPageStore();
		$page = $pageStore->getExistingPageByText( $nonexistingPage->getTitle()->getPrefixedText() );
		$this->assertNull( $page );
	}

	/**
	 * Configure the load balancer to route queries for the "foreign" domain to the test DB.
	 *
	 * @param string $wikiId
	 */
	private function setDomainAlias( $wikiId ) {
		$dbLoadBalancer = $this->getServiceContainer()->getDBLoadBalancer();
		$dbLoadBalancer->setDomainAliases( [ $wikiId => $dbLoadBalancer->getLocalDomainID() ] );
	}

	/**
	 * Test that we get a PageRecord from another wiki by name
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_crossWiki() {
		$wikiId = 'acme';
		$this->setDomainAlias( $wikiId );

		$existingPage = $this->getExistingTestPage();
		$ns = $existingPage->getNamespace();
		$dbkey = $existingPage->getDBkey();

		$pageStore = $this->getPageStore( [], [ 'wikiId' => $wikiId, 'linkCache' => null ] );
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	public static function provideGetPageByName_invalid() {
		yield 'empty title' => [ NS_MAIN, '' ];
		yield 'spaces in title' => [ NS_MAIN, 'Foo Bar' ];
		yield 'special page' => [ NS_SPECIAL, 'Test' ];
		yield 'media link' => [ NS_MEDIA, 'Test' ];
	}

	/**
	 * Test that getPageByName() throws InvalidArgumentException when presented with
	 * a link that does not refer to a proper page.
	 *
	 * @dataProvider provideGetPageByName_invalid
	 * @covers \MediaWiki\Page\PageStore::getPageByName
	 */
	public function testGetPageByName_invalid( $ns, $dbkey ) {
		$pageStore = $this->getPageStore();

		$this->expectException( InvalidArgumentException::class );
		$pageStore->getPageByName( $ns, $dbkey );
	}

	public static function provideInvalidTitleText() {
		yield 'empty' => [ '' ];
		yield 'section' => [ '#foo' ];
		yield 'autoblock' => [ 'User:#12345' ];
		yield 'special' => [ 'Special:RecentChanges' ];
		yield 'invalid' => [ 'foo|bar' ];
	}

	/**
	 * @dataProvider provideInvalidTitleText
	 * @covers \MediaWiki\Page\PageStore::getPageByText
	 */
	public function testGetPageByText_invalid( $text ) {
		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByText( $text );
		$this->assertNull( $page );
	}

	/**
	 * @dataProvider provideInvalidTitleText
	 * @covers \MediaWiki\Page\PageStore::getExistingPageByText
	 */
	public function testGetExistingPageByText_invalid( $text ) {
		$pageStore = $this->getPageStore();
		$page = $pageStore->getExistingPageByText( $text );
		$this->assertNull( $page );
	}

	/**
	 * Test that we get a PageRecord for an existing page by id
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_existing() {
		$existingPage = $this->getExistingTestPage();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageById( $existingPage->getId() );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * Test that we get null if we look up a non-existing page by id
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_nonexisting() {
		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageById( 813451092 );

		$this->assertNull( $page );
	}

	/**
	 * Test that we get a PageRecord from another wiki by id
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_crossWiki() {
		$wikiId = 'acme';
		$this->setDomainAlias( $wikiId );

		$existingPage = $this->getExistingTestPage();

		$pageStore = $this->getPageStore( [], [ 'wikiId' => $wikiId, 'linkCache' => null ] );
		$page = $pageStore->getPageById( $existingPage->getId() );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * Test that we can correctly emulate the page_lang field.
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_noLanguage() {
		$existingPage = $this->getExistingTestPage();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageById( $existingPage->getId() );

		$this->assertNull( $page->getLanguage() );
	}

	public static function provideGetPageById_invalid() {
		yield 'zero' => [ 0 ];
		yield 'negative' => [ -1 ];
	}

	/**
	 * Test that getPageById() throws InvalidArgumentException for bad IDs.
	 *
	 * @dataProvider provideGetPageById_invalid
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_invalid( $id ) {
		$pageStore = $this->getPageStore();

		$this->expectException( InvalidArgumentException::class );
		$pageStore->getPageById( $id );
	}

	/**
	 * Test that we get a PageRecord for an existing page by id
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_existing() {
		$existingPage = $this->getExistingTestPage();
		$identity = $existingPage->getTitle()->toPageIdentity();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByReference( $identity );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * Test that we get a PageRecord from cached data even if we pass in a
	 * PageIdentity that provides a page ID (T296063#7520023).
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_cached() {
		$title = $this->makeMockTitle( __METHOD__, [ 'id' => 23 ] );
		$this->addGoodLinkObject( 23, $title );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByReference( $title );

		$this->assertNotNull( $page );
		$this->assertSame( 23, $page->getId() );
	}

	/**
	 * Test that we get null if we look up a page with ID 0
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_knowNonexisting() {
		$nonexistingPage = PageIdentityValue::localIdentity( 0, NS_MAIN, 'Test' );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByReference( $nonexistingPage );

		$this->assertNull( $page );
	}

	/**
	 * Test that we get null if we look up a page with an ID that does not exist
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_notFound() {
		$nonexistingPage = PageIdentityValue::localIdentity( 523478562, NS_MAIN, 'Test' );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByReference( $nonexistingPage );

		$this->assertNull( $page );
	}

	/**
	 * Test that getPageByIdentity() returns any ExistingPageRecord unchanged
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_PageRecord() {
		$existingPage = $this->getExistingTestPage();
		$rec = $existingPage->toPageRecord();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByReference( $rec );

		$this->assertSame( $rec, $page );
	}

	/**
	 * Test that we get a PageRecord from another wiki by id
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_crossWiki() {
		$wikiId = 'acme';
		$this->setDomainAlias( $wikiId );

		$existingPage = $this->getExistingTestPage();

		$identity = new PageIdentityValue(
			$existingPage->getId(),
			$existingPage->getNamespace(),
			$existingPage->getDBkey(),
			$wikiId
		);

		$pageStore = $this->getPageStore( [], [ 'wikiId' => $wikiId, 'linkCache' => null ] );
		$page = $pageStore->getPageByReference( $identity );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	public static function provideGetPageByIdentity_invalid() {
		yield 'section' => [
			[ '', [ 'fragment' => 'See also' ] ],
			InvalidArgumentException::class
		];
		yield 'special' => [
			[ 'Blankpage', [ 'namespace' => NS_SPECIAL ] ],
			InvalidArgumentException::class
		];
		yield 'interwiki' => [
			[ 'Foo', [ 'interwiki' => 'acme' ] ],
			InvalidArgumentException::class
		];

		$identity = new PageIdentityValue( 7, NS_MAIN, 'Test', 'acme' );
		yield 'cross-wiki' => [ $identity, PreconditionException::class ];
	}

	/**
	 * Test that getPageByIdentity() throws InvalidArgumentException for bad IDs.
	 *
	 * @dataProvider provideGetPageByIdentity_invalid
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByIdentity_invalid( $identity, $exception ) {
		if ( is_array( $identity ) ) {
			$identity = $this->makeMockTitle( ...$identity );
		}
		$pageStore = $this->getPageStore();

		$this->expectException( $exception );
		$pageStore->getPageByReference( $identity );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::newPageRecordFromRow
	 * @covers \MediaWiki\Page\PageStore::getSelectFields
	 */
	public function testNewPageRecordFromRow() {
		$existingPage = $this->getExistingTestPage();
		$pageStore = $this->getPageStore();

		$row = $this->getDb()->newSelectQueryBuilder()
			->select( $pageStore->getSelectFields() )
			->from( 'page' )
			->where( [ 'page_id' => $existingPage->getId() ] )
			->fetchRow();

		$rec = $pageStore->newPageRecordFromRow( $row );
		$this->assertSamePage( $existingPage, $rec );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::newSelectQueryBuilder
	 */
	public function testNewSelectQueryBuilder() {
		$existingPage = $this->getExistingTestPage();

		$wikiId = 'acme';
		$this->setDomainAlias( $wikiId );

		$pageStore = $this->getPageStore( [], [ 'wikiId' => $wikiId, 'linkCache' => null ] );

		$rec = $pageStore->newSelectQueryBuilder()
			->wherePageIds( $existingPage->getId() )
			->fetchPageRecord();

		$this->assertSame( $wikiId, $rec->getWikiId() );
		$this->assertSamePage( $existingPage, $rec );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::newSelectQueryBuilder
	 */
	public function testNewSelectQueryBuilder_passDatabase() {
		$pageStore = $this->getPageStore();

		// Test that the provided DB connection is used.
		$db = $this->createMock( IDatabase::class );
		$db->expects( $this->atLeastOnce() )->method( 'selectRow' )->willReturn( false );

		$pageStore->newSelectQueryBuilder( $db )
			->fetchPageRecord();
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::newSelectQueryBuilder
	 */
	public function testNewSelectQueryBuilder_passFlags() {
		// Test that the provided DB connection is used.
		$db = $this->createMock( IDatabase::class );
		$db->expects( $this->atLeastOnce() )->method( 'selectRow' )->willReturn( false );

		// Test that the load balancer is asked for a master connection
		$lb = $this->createMock( LoadBalancer::class );
		$lb->expects( $this->atLeastOnce() )
			->method( 'getConnection' )
			->with( DB_PRIMARY )
			->willReturn( $db );

		$pageStore = $this->getPageStore(
			[
				MainConfigNames::LanguageCode => 'qxx',
				MainConfigNames::PageLanguageUseDB => true,
			],
			[ 'dbLoadBalancer' => $lb ]
		);

		$pageStore->newSelectQueryBuilder( IDBAccessObject::READ_LATEST )
			->fetchPageRecord();
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages() {
		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->overrideConfigValue(
			MainConfigNames::NamespacesWithSubpages,
			[ $title->getNamespace() => true ]
		);

		$existingSubpageA = $this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$existingSubpageB = $this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$notQuiteSubpageTitle = $title->getPrefixedDBkey() . 'X'; // no slash!
		$this->getExistingTestPage( $notQuiteSubpageTitle );

		$pageStore = $this->getPageStore();

		$subpages = iterator_to_array( $pageStore->getSubpages( $title, 100 ) );

		$this->assertCount( 2, $subpages );
		$this->assertTrue( $existingSubpageA->isSamePageAs( $subpages[0] ) );
		$this->assertTrue( $existingSubpageB->isSamePageAs( $subpages[1] ) );

		// make sure the limit works as well
		$this->assertCount( 1, iterator_to_array( $pageStore->getSubpages( $title, 1 ) ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages_disabled() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [] );

		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$pageStore = $this->getPageStore();
		$this->assertCount( 0, $pageStore->getSubpages( $title, 100 ) );
	}

	/**
	 * See T295931. If removing TitleExists hook, remove this test.
	 *
	 * @covers \MediaWiki\Page\PageStore::getPageByReference
	 */
	public function testGetPageByReferenceTitleExistsHook() {
		$this->setTemporaryHook( 'TitleExists', static function ( $title, &$exists ) {
			$exists = true;
		} );
		$this->assertNull(
			$this->getPageStore()->getPageByReference(
				Title::newFromText( __METHOD__ )
			)
		);
	}

}
