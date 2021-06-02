<?php

use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;

/**
 * @group Database
 * @group Cache
 * @covers LinkCache
 */
class LinkCacheTest extends MediaWikiIntegrationTestCase {

	private function newLinkCache( WANObjectCache $wanCache = null ) {
		if ( !$wanCache ) {
			$wanCache = new WANObjectCache( [ 'cache' => new EmptyBagOStuff() ] );
		}

		return new LinkCache(
			$this->getServiceContainer()->getTitleFormatter(),
			$wanCache,
			$this->getServiceContainer()->getNamespaceInfo(),
			$this->getServiceContainer()->getDBLoadBalancer()
		);
	}

	public function providePageAndLink() {
		return [
			[ new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL ) ],
			[ new TitleValue( NS_USER, __METHOD__ ) ],
		];
	}

	public function providePageAndLinkAndString() {
		return [
			[ new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL ) ],
			[ new TitleValue( NS_USER, __METHOD__ ) ],
			[ 'User:' . __METHOD__ ],
		];
	}

	private function getPageRow() {
		return (object)[
			'page_id' => 8,
			'page_len' => 18,
			'page_is_redirect' => 0,
			'page_latest' => 118,
			'page_content_model' => CONTENT_MODEL_TEXT,
			'page_lang' => 'xyz',
			'page_restrictions' => 'test'
		];
	}

	/**
	 * @dataProvider providePageAndLink
	 * @covers LinkCache::addGoodLinkObjFromRow()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 * @covers LinkCache::clearLink()
	 */
	public function testAddGoodLinkObjFromRow( $page ) {
		$linkCache = $this->newLinkCache();

		$row = $this->getPageRow();

		$page = new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL );
		$linkCache->addBadLinkObj( $page );
		$linkCache->addGoodLinkObjFromRow( $page, $row );

		$this->assertSame( $row->page_id, $linkCache->getGoodLinkID( $page ) );
		$this->assertFalse( $linkCache->isBadLink( $page ) );

		$this->assertSame(
			$row->page_id,
			$linkCache->getGoodLinkFieldObj( $page, 'id' )
		);
		$this->assertSame(
			$row->page_len,
			$linkCache->getGoodLinkFieldObj( $page, 'length' )
		);
		$this->assertSame(
			$row->page_is_redirect,
			$linkCache->getGoodLinkFieldObj( $page, 'redirect' )
		);
		$this->assertSame(
			$row->page_latest,
			$linkCache->getGoodLinkFieldObj( $page, 'revision' )
		);
		$this->assertSame(
			$row->page_content_model,
			$linkCache->getGoodLinkFieldObj( $page, 'model' )
		);
		$this->assertSame(
			$row->page_lang,
			$linkCache->getGoodLinkFieldObj( $page, 'lang' )
		);
		$this->assertSame(
			$row->page_restrictions,
			$linkCache->getGoodLinkFieldObj( $page, 'restrictions' )
		);

		$linkCache->clearBadLink( $page );
		$this->assertNotNull( $linkCache->getGoodLinkID( $page ) );
		$this->assertNotNull( $linkCache->getGoodLinkFieldObj( $page, 'length' ) );

		$linkCache->clearLink( $page );
		$this->assertSame( 0, $linkCache->getGoodLinkID( $page ) );
		$this->assertNull( $linkCache->getGoodLinkFieldObj( $page, 'length' ) );
	}

	/**
	 * @dataProvider providePageAndLink
	 * @covers LinkCache::addGoodLinkObj()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 */
	public function testAddGoodLinkObjWithAllParameters( $page ) {
		$linkCache = $this->newLinkCache();

		$linkCache->addGoodLinkObj(
			8,
			$page,
			18,
			0,
			118,
			CONTENT_MODEL_TEXT,
			'xyz'
		);

		$this->assertSame( 8, $linkCache->getGoodLinkID( $page ) );
		$this->assertSame( 8, $linkCache->getGoodLinkFieldObj( $page, 'id' ) );

		$this->assertSame(
			18,
			$linkCache->getGoodLinkFieldObj( $page, 'length' )
		);
		$this->assertSame(
			0,
			$linkCache->getGoodLinkFieldObj( $page, 'redirect' )
		);
		$this->assertSame(
			118,
			$linkCache->getGoodLinkFieldObj( $page, 'revision' )
		);
		$this->assertSame(
			CONTENT_MODEL_TEXT,
			$linkCache->getGoodLinkFieldObj( $page, 'model' )
		);
		$this->assertSame(
			'xyz',
			$linkCache->getGoodLinkFieldObj( $page, 'lang' )
		);
	}

	/**
	 * @covers LinkCache::addGoodLinkObj()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 */
	public function testAddGoodLinkObjWithMinimalParameters() {
		$linkCache = $this->newLinkCache();

		$page = new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL );
		$linkCache->addGoodLinkObj(
			8,
			$page
		);

		$this->assertSame( 8, $linkCache->getGoodLinkID( $page ) );
		$this->assertSame( 8, $linkCache->getGoodLinkFieldObj( $page, 'id' ) );

		$this->assertSame(
			-1,
			$linkCache->getGoodLinkFieldObj( $page, 'length' )
		);
		$this->assertSame(
			0,
			$linkCache->getGoodLinkFieldObj( $page, 'redirect' )
		);
		$this->assertSame(
			0,
			$linkCache->getGoodLinkFieldObj( $page, 'revision' )
		);
		$this->assertSame(
			null,
			$linkCache->getGoodLinkFieldObj( $page, 'model' )
		);
		$this->assertSame(
			null,
			$linkCache->getGoodLinkFieldObj( $page, 'lang' )
		);
	}

	/**
	 * @covers LinkCache::addGoodLinkObj()
	 */
	public function testAddGoodLinkObjWithInterwikiLink() {
		$linkCache = $this->newLinkCache();

		$page = new TitleValue( NS_USER, __METHOD__, '', 'acme' );
		$linkCache->addGoodLinkObj( 8, $page );

		$this->assertSame( 0, $linkCache->getGoodLinkID( $page ) );
	}

	/**
	 * @dataProvider providePageAndLink
	 * @covers LinkCache::addBadLinkObj()
	 * @covers LinkCache::isBadLink()
	 * @covers LinkCache::clearLink()
	 */
	public function testAddBadLinkObj( $key ) {
		$linkCache = $this->newLinkCache();
		$this->assertFalse( $linkCache->isBadLink( $key ) );

		$linkCache->addGoodLinkObj( 17, $key );

		$linkCache->addBadLinkObj( $key );
		$this->assertTrue( $linkCache->isBadLink( $key ) );
		$this->assertSame( 0, $linkCache->getGoodLinkID( $key ) );

		$linkCache->clearLink( $key );
		$this->assertFalse( $linkCache->isBadLink( $key ) );
	}

	/**
	 * @covers LinkCache::addBadLinkObj()
	 */
	public function testAddBadLinkObjWithInterwikiLink() {
		$linkCache = $this->newLinkCache();

		$page = new TitleValue( NS_USER, __METHOD__, '', 'acme' );
		$linkCache->addBadLinkObj( $page );

		$this->assertFalse( $linkCache->isBadLink( $page ) );
	}

	/**
	 * @covers LinkCache::addLinkObj()
	 * @covers LinkCache::getGoodLinkFieldObj
	 */
	public function testAddLinkObj() {
		$existing = $this->getExistingTestPage();
		$missing = $this->getNonexistingTestPage();

		$linkCache = $this->newLinkCache();

		$linkCache->addLinkObj( $existing );
		$linkCache->addLinkObj( $missing );

		$this->assertTrue( $linkCache->isBadLink( $missing ) );
		$this->assertFalse( $linkCache->isBadLink( $existing ) );

		$this->assertSame( $existing->getId(), $linkCache->getGoodLinkID( $existing ) );

		// Make sure nothing explodes when getting a field from a non-existing entry
		$this->assertNull( $linkCache->getGoodLinkFieldObj( $missing, 'length' ) );
	}

	/**
	 * @covers LinkCache::addLinkObj()
	 */
	public function testAddLinkObjUsesCachedInfo() {
		$existing = $this->getExistingTestPage();
		$missing = $this->getNonexistingTestPage();

		$row = $this->getPageRow();

		$linkCache = $this->newLinkCache();

		// pretend the existing page is missing, and the missing page exists
		$linkCache->addGoodLinkObjFromRow( $missing, $row );
		$linkCache->addBadLinkObj( $existing );

		// the LinkCache should use the cached info and not look into the database
		$this->assertSame( (int)$row->page_id, $linkCache->addLinkObj( $missing ) );
		$this->assertSame( 0, $linkCache->addLinkObj( $existing ) );

		// now set the "for update" flag and try again
		$linkCache->forUpdate( true );
		$this->assertSame( 0, $linkCache->addLinkObj( $missing ) );
		$this->assertSame( $existing->getId(), $linkCache->addLinkObj( $existing ) );
	}

	/**
	 * @covers LinkCache::addLinkObj()
	 * @covers LinkCache::getMutableCacheKeys()
	 */
	public function testAddLinkObjUsesWANCache() {
		// Pages in some namespaces use the WAN cache: Template, File, Category, MediaWiki
		$existing = $this->getExistingTestPage( Title::makeTitle( NS_TEMPLATE, __METHOD__ ) );

		$fakeRow = $this->getPageRow();

		$cache = new HashBagOStuff();
		$wanCache = new WANObjectCache( [ 'cache' => $cache ] );
		$linkCache = $this->newLinkCache( $wanCache );

		// load the page row into the cache
		$linkCache->addLinkObj( $existing );

		$keys = $linkCache->getMutableCacheKeys( $wanCache, $existing );
		$this->assertNotEmpty( $keys );

		foreach ( $keys as $key ) {
			$this->assertNotFalse( $wanCache->get( $key ) );
		}

		// replace real row data with fake, and assert that it gets used
		$wanCache->set( $key, $fakeRow );
		$linkCache->clearLink( $existing ); // clear local cache
		$this->assertSame( (int)$fakeRow->page_id, $linkCache->addLinkObj( $existing ) );

		// set the "for update" flag and try again
		$linkCache->forUpdate( true );
		$this->assertSame( $existing->getId(), $linkCache->addLinkObj( $existing ) );
	}

	public function testFalsyPageName() {
		$linkCache = $this->newLinkCache();

		// The stringified value is "0", which is falsy in PHP!
		$link = new TitleValue( NS_MAIN, '0' );

		$linkCache->addBadLinkObj( $link );
		$this->assertTrue( $linkCache->isBadLink( $link ) );

		$row = $this->getPageRow();
		$linkCache->addGoodLinkObjFromRow( $link, $row );
		$this->assertGreaterThan( 0, $linkCache->getGoodLinkID( $link ) );
	}

	public function testClearBadLinkWithString() {
		$linkCache = $this->newLinkCache();
		$linkCache->clearBadLink( 'Xyzzy' );
		$this->addToAssertionCount( 1 );
	}

	public function testIsBadLinkWithString() {
		$linkCache = $this->newLinkCache();
		$this->assertFalse( $linkCache->isBadLink( 'Xyzzy' ) );
	}

	public function testGetGoodLinkIdWithString() {
		$linkCache = $this->newLinkCache();
		$this->assertSame( 0, $linkCache->getGoodLinkID( 'Xyzzy' ) );
	}
}
