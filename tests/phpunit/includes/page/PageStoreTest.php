<?php
namespace MediaWiki\Tests\Page;

use Exception;
use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\PageStore;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;
use TitleValue;
use Wikimedia\Assert\PreconditionException;

/**
 * @group Database
 */
class PageStoreTest extends MediaWikiIntegrationTestCase {

	use MockTitleTrait;

	protected function setUp(): void {
		$this->tablesUsed[] = 'page';
	}

	/**
	 * @param array $options
	 * @param bool $wikiId
	 *
	 * @return PageStore
	 * @throws Exception
	 */
	private function getPageStore( $options = [], $wikiId = false ) {
		$services = $this->getServiceContainer();

		$serviceOptions = new ServiceOptions(
			PageStore::CONSTRUCTOR_OPTIONS,
			$options + [
				'LanguageCode' => $services->getContentLanguage()->getCode(),
				'PageLanguageUseDB' => true
			]
		);

		return new PageStore(
			$serviceOptions,
			$services->getDBLoadBalancer(),
			$wikiId
		);
	}

	/**
	 * @param PageIdentity $expected
	 * @param PageIdentity $actual
	 */
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
		$wikiId = $this->db->getDomainID(); // pretend sister site

		$nonexistingPage = $this->getNonexistingTestPage();
		$pageStore = $this->getPageStore( [], $wikiId );

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

	public function provideInvalidLinks() {
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

		$pageStore = $this->getPageStore( [], $wikiId );
		$page = $pageStore->getPageByName( $ns, $dbkey );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	public function provideGetPageByName_invalid() {
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

		$pageStore = $this->getPageStore( [], $wikiId );
		$page = $pageStore->getPageById( $existingPage->getId() );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * Test that we can correctly emulate the page_lang field.
	 * @covers \MediaWiki\Page\PageStore::getPageById
	 */
	public function testGetPageById_emulateLanguage() {
		$existingPage = $this->getExistingTestPage();

		$options = [
			'PageLanguageUseDB' => false,
			'LanguageCode' => 'xyz'
		];
		$pageStore = $this->getPageStore( $options );
		$page = $pageStore->getPageById( $existingPage->getId() );

		$this->assertSame( 'xyz', $page->getLanguage() );
	}

	public function provideGetPageById_invalid() {
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
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
	 */
	public function testGetPageByIdentity_existing() {
		$existingPage = $this->getExistingTestPage();
		$identity = $existingPage->getTitle()->toPageIdentity();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByIdentity( $identity );

		$this->assertTrue( $page->exists() );
		$this->assertSamePage( $existingPage, $page );
	}

	/**
	 * Test that we get null if we look up a page with ID 0
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
	 */
	public function testGetPageByIdentity_knowNonexisting() {
		$nonexistingPage = new PageIdentityValue( 0, NS_MAIN, 'Test', PageIdentity::LOCAL );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByIdentity( $nonexistingPage );

		$this->assertNull( $page );
	}

	/**
	 * Test that we get null if we look up a page with an ID that does not exist
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
	 */
	public function testGetPageByIdentity_notFound() {
		$nonexistingPage = new PageIdentityValue( 523478562, NS_MAIN, 'Test', PageIdentity::LOCAL );

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByIdentity( $nonexistingPage );

		$this->assertNull( $page );
	}

	/**
	 * Test that getPageByIdentity() returns any ExistingPageRecord unchanged
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
	 */
	public function testGetPageByIdentity_PageRecord() {
		$existingPage = $this->getExistingTestPage();
		$rec = $existingPage->toPageRecord();

		$pageStore = $this->getPageStore();
		$page = $pageStore->getPageByIdentity( $rec );

		$this->assertSame( $rec, $page );
	}

	/**
	 * Test that we get a PageRecord from another wiki by id
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
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

		$pageStore = $this->getPageStore( [], $wikiId );
		$page = $pageStore->getPageByIdentity( $identity );

		$this->assertSame( $wikiId, $page->getWikiId() );
		$this->assertSamePage( $existingPage, $page );
	}

	public function provideGetPageByIdentity_invalid() {
		yield 'section' => [
			$this->makeMockTitle( '', [ 'fragment' => 'See also' ] ),
			InvalidArgumentException::class
		];
		yield 'special' => [
			$this->makeMockTitle( 'Blankpage', [ 'namespace' => NS_SPECIAL ] ),
			InvalidArgumentException::class
		];
		yield 'interwiki' => [
			$this->makeMockTitle( 'Foo', [ 'interwiki' => 'acme' ] ),
			InvalidArgumentException::class
		];

		$identity = new PageIdentityValue( 7, NS_MAIN, 'Test', 'acme' );
		yield 'cross-wiki' => [ $identity, PreconditionException::class ];
	}

	/**
	 * Test that getPageByIdentity() throws InvalidArgumentException for bad IDs.
	 *
	 * @dataProvider provideGetPageByIdentity_invalid
	 * @covers \MediaWiki\Page\PageStore::getPageByIdentity
	 */
	public function testGetPageByIdentity_invalid( $identity, $exception ) {
		$pageStore = $this->getPageStore();

		$this->expectException( $exception );
		$pageStore->getPageByIdentity( $identity );
	}

}
