<?php

use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;

/**
 * @group Database
 * @group Cache
 * @covers LinkCache
 */
class LinkCacheTest extends MediaWikiIntegrationTestCase {
	use LinkCacheTestTrait;

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
			[ new TitleValue( NS_USER, __METHOD__ ) ]
		];
	}

	public function providePageAndLinkAndArray() {
		return [
			[ new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL ) ],
			[ new TitleValue( NS_USER, __METHOD__ ) ],
			[ [ 'page_namespace' => NS_USER, 'page_title' => __METHOD__ ] ],
		];
	}

	private function getPageRow( $offset = 0 ) {
		return (object)[
			'page_id' => 8 + $offset,
			'page_namespace' => 0,
			'page_title' => 'Test ' . $offset,
			'page_len' => 18,
			'page_is_redirect' => 0,
			'page_latest' => 118 + $offset,
			'page_content_model' => CONTENT_MODEL_TEXT,
			'page_lang' => 'xyz',
			'page_is_new' => 0,
			'page_restrictions' => 'test',
			'page_touched' => '20200202020202',
		];
	}

	/**
	 * @dataProvider providePageAndLinkAndArray
	 * @covers LinkCache::addGoodLinkObjFromRow()
	 * @covers LinkCache::getGoodLinkRow()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 * @covers LinkCache::clearLink()
	 */
	public function testAddGoodLinkObjFromRow( $page ) {
		$linkCache = $this->newLinkCache();

		$row = $this->getPageRow();

		$dbkey = is_array( $page ) ? $page['page_title'] : $page->getDBkey();
		$ns = is_array( $page ) ? $page['page_namespace'] : $page->getNamespace();

		$linkCache->addBadLinkObj( $page );
		$linkCache->addGoodLinkObjFromRow( $page, $row );

		$this->assertEquals(
			$row,
			$linkCache->getGoodLinkRow( $ns, $dbkey )
		);

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

		$this->assertEquals(
			$row,
			$linkCache->getGoodLinkRow( $ns, $dbkey )
		);

		$linkCache->clearBadLink( $page );
		$this->assertNotNull( $linkCache->getGoodLinkID( $page ) );
		$this->assertNotNull( $linkCache->getGoodLinkFieldObj( $page, 'length' ) );

		$linkCache->clearLink( $page );
		$this->assertSame( 0, $linkCache->getGoodLinkID( $page ) );
		$this->assertNull( $linkCache->getGoodLinkFieldObj( $page, 'length' ) );
		$this->assertNull( $linkCache->getGoodLinkRow( $ns, $dbkey ) );
	}

	/**
	 * @covers LinkCache::addGoodLinkObjFromRow()
	 * @covers LinkCache::getGoodLinkRow()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 */
	public function testAddGoodLinkObjWithAllParameters() {
		$linkCache = $this->getServiceContainer()->getLinkCache();

		$page = new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL );
		$this->addGoodLinkObject( 8, $page, 18, 0, 118, CONTENT_MODEL_TEXT, 'xyz' );

		$row = $linkCache->getGoodLinkRow( $page->getNamespace(), $page->getDBkey() );
		$this->assertEquals( 8, (int)$row->page_id );
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
	 * @covers LinkCache::addGoodLinkObjFromRow()
	 * @covers LinkCache::getGoodLinkRow()
	 * @covers LinkCache::getGoodLinkID()
	 * @covers LinkCache::getGoodLinkFieldObj()
	 */
	public function testAddGoodLinkObjFromRowWithMinimalParameters() {
		$linkCache = $this->getServiceContainer()->getLinkCache();

		$page = new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL );

		$this->addGoodLinkObject( 8, $page );
		$expectedRow = [
			'page_id' => 8,
			'page_len' => -1,
			'page_is_redirect' => 0,
			'page_latest' => 0,
			'page_content_model' => null,
			'page_lang' => null,
			'page_restrictions' => null
		];

		$actualRow = (array)$linkCache->getGoodLinkRow( $page->getNamespace(), $page->getDBkey() );
		$this->assertEquals(
			$expectedRow,
			array_intersect_key( $actualRow, $expectedRow )
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
	 * @covers LinkCache::addGoodLinkObjFromRow()
	 */
	public function testAddGoodLinkObjFromRowWithInterwikiLink() {
		$linkCache = $this->getServiceContainer()->getLinkCache();

		$page = new TitleValue( NS_USER, __METHOD__, '', 'acme' );

		$this->addGoodLinkObject( 8, $page );

		$this->assertSame( 0, $linkCache->getGoodLinkID( $page ) );
	}

	/**
	 * @dataProvider providePageAndLink
	 * @covers LinkCache::addBadLinkObj()
	 * @covers LinkCache::isBadLink()
	 * @covers LinkCache::clearLink()
	 */
	public function testAddBadLinkObj( $key ) {
		$linkCache = $this->getServiceContainer()->getLinkCache();
		$this->assertFalse( $linkCache->isBadLink( $key ) );

		$this->addGoodLinkObject( 17, $key );

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
		$this->assertTrue( $linkCache->isBadLink( $missing ) );

		// Make sure nothing explodes when getting a field from a non-existing entry
		$this->assertNull( $linkCache->getGoodLinkFieldObj( $missing, 'length' ) );
	}

	/**
	 * @covers LinkCache::addLinkObj()
	 */
	public function testAddLinkObjUsesCachedInfo() {
		$existing = $this->getExistingTestPage();
		$missing = $this->getNonexistingTestPage();

		$fakeRow = $this->getPageRow( $existing->getId() + 100 );

		$linkCache = $this->newLinkCache();

		// pretend the existing page is missing, and the missing page exists
		$linkCache->addGoodLinkObjFromRow( $missing, $fakeRow );
		$linkCache->addBadLinkObj( $existing );

		// the LinkCache should use the cached info and not look into the database
		$this->assertSame( (int)$fakeRow->page_id, $linkCache->addLinkObj( $missing ) );
		$this->assertSame( 0, $linkCache->addLinkObj( $existing ) );

		// now set the "read latest" flag and try again
		$flags = IDBAccessObject::READ_LATEST;
		$this->assertSame( 0, $linkCache->addLinkObj( $missing, $flags ) );
		$this->assertSame( $existing->getId(), $linkCache->addLinkObj( $existing, $flags ) );
	}

	/**
	 * @covers LinkCache::addLinkObj()
	 * @covers LinkCache::getMutableCacheKeys()
	 */
	public function testAddLinkObjUsesWANCache() {
		// Pages in some namespaces use the WAN cache: Template, File, Category, MediaWiki
		$existing = $this->getExistingTestPage( Title::makeTitle( NS_TEMPLATE, __METHOD__ ) );

		$fakeRow = $this->getPageRow( $existing->getId() + 100 );

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

		// set the "read latest" flag and try again
		$flags = IDBAccessObject::READ_LATEST;
		$this->assertSame( $existing->getId(), $linkCache->addLinkObj( $existing, $flags ) );
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

		$this->assertSame( $row, $linkCache->getGoodLinkRow( NS_MAIN, '0' ) );
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

	public function provideInvalidPageParams() {
		return [
			'empty' => [ NS_MAIN, '' ],
			'bad chars' => [ NS_MAIN, '_|_' ],
			'empty in namspace' => [ NS_USER, '' ],
			'special' => [ NS_SPECIAL, 'RecentChanges' ],
		];
	}

	/**
	 * @dataProvider provideInvalidPageParams
	 * @covers LinkCache::getGoodLinkRow()
	 */
	public function testGetGoodLinkRowWithBadParams( $ns, $dbkey ) {
		$linkCache = $this->newLinkCache();
		$this->assertNull( $linkCache->getGoodLinkRow( $ns, $dbkey ) );
	}

	public function getRowIfExisting( $db, $ns, $dbkey, $queryOptions ) {
		if ( $dbkey === 'Existing' ) {
			return $this->getPageRow();
		}

		return null;
	}

	/**
	 * @covers LinkCache::getGoodLinkRow()
	 * @covers LinkCache::getGoodLinkFieldObj
	 */
	public function testGetGoodLinkRow() {
		$existing = new TitleValue( NS_MAIN, 'Existing' );
		$missing = new TitleValue( NS_MAIN, 'Missing' );

		$linkCache = $this->newLinkCache();
		$callback = [ $this, 'getRowIfExisting' ];

		$linkCache->getGoodLinkRow( $existing->getNamespace(), $existing->getDBkey(), $callback );
		$linkCache->getGoodLinkRow( $missing->getNamespace(), $missing->getDBkey(), $callback );

		$this->assertTrue( $linkCache->isBadLink( $missing ) );
		$this->assertFalse( $linkCache->isBadLink( $existing ) );

		$this->assertGreaterThan( 0, $linkCache->getGoodLinkID( $existing ) );
		$this->assertTrue( $linkCache->isBadLink( $missing ) );

		// Make sure nothing explodes when getting a field from a non-existing entry
		$this->assertNull( $linkCache->getGoodLinkFieldObj( $missing, 'length' ) );
	}

	/**
	 * @covers LinkCache::getGoodLinkRow()
	 */
	public function testGetGoodLinkRowUsesCachedInfo() {
		$existing = new TitleValue( NS_MAIN, 'Existing' );
		$missing = new TitleValue( NS_MAIN, 'Missing' );
		$callback = [ $this, 'getRowIfExisting' ];

		$existingRow = $this->getPageRow( 0 );
		$fakeRow = $this->getPageRow( 3 );

		$linkCache = $this->newLinkCache();

		// pretend the existing page is missing, and the missing page exists
		$linkCache->addGoodLinkObjFromRow( $missing, $fakeRow );
		$linkCache->addBadLinkObj( $existing );

		// the LinkCache should use the cached info and not look into the database
		$this->assertSame(
			$fakeRow,
			$linkCache->getGoodLinkRow( $missing->getNamespace(), $missing->getDBkey(), $callback )
		);
		$this->assertNull(
			$linkCache->getGoodLinkRow( $existing->getNamespace(), $existing->getDBkey(), $callback )
		);

		// now set the "read latest" flag and try again
		$flags = IDBAccessObject::READ_LATEST;
		$this->assertNull(
			$linkCache->getGoodLinkRow(
				$missing->getNamespace(),
				$missing->getDBkey(),
				$callback,
				$flags
			)
		);
		$this->assertEquals(
			$existingRow,
			$linkCache->getGoodLinkRow(
				$existing->getNamespace(),
				$existing->getDBkey(),
				$callback,
				$flags
			)
		);

		// pretend again that the missing page exists, but pretend even harder
		$linkCache->addGoodLinkObjFromRow( $missing, $fakeRow, IDBAccessObject::READ_LATEST );

		// the LinkCache should use the cached info and not look into the database
		$this->assertSame(
			$fakeRow,
			$linkCache->getGoodLinkRow( $missing->getNamespace(), $missing->getDBkey(), $callback )
		);

		// now set the "read latest" flag and try again
		$flags = IDBAccessObject::READ_LATEST;
		$this->assertEquals(
			$fakeRow,
			$linkCache->getGoodLinkRow(
				$missing->getNamespace(),
				$missing->getDBkey(),
				$callback,
				$flags
			)
		);
	}

	/**
	 * @covers LinkCache::getGoodLinkRow()
	 */
	public function testGetGoodLinkRowGetsIncompleteCachedInfo() {
		// Pages in some namespaces use the WAN cache: Template, File, Category, MediaWiki
		$existing = new TitleValue( NS_TEMPLATE, 'Existing' );
		$brokenRow = $this->getPageRow( 3 );
		unset( $brokenRow->page_len ); // make incomplete row

		$cache = new HashBagOStuff();
		$wanCache = new WANObjectCache( [ 'cache' => $cache ] );
		$linkCache = $this->newLinkCache( $wanCache );

		// force the incomplete row into the cache
		$keys = $linkCache->getMutableCacheKeys( $wanCache, $existing );
		$wanCache->set( $keys[0], $brokenRow );

		// check that we are not getting the broken row, but load a good row
		$callback = [ $this, 'getRowIfExisting' ];
		$row = $linkCache->getGoodLinkRow( $existing->getNamespace(), $existing->getDBkey(), $callback );

		$this->assertNotEquals( $brokenRow, $row );
	}

	/**
	 * @covers LinkCache::getGoodLinkRow()
	 * @covers LinkCache::getMutableCacheKeys()
	 */
	public function testGetGoodLinkRowUsesWANCache() {
		// Pages in some namespaces use the WAN cache: Template, File, Category, MediaWiki
		$existing = new TitleValue( NS_TEMPLATE, 'Existing' );
		$callback = [ $this, 'getRowIfExisting' ];

		$existingRow = $this->getPageRow( 0 );
		$fakeRow = $this->getPageRow( 3 );

		$cache = new HashBagOStuff();
		$wanCache = new WANObjectCache( [ 'cache' => $cache ] );
		$linkCache = $this->newLinkCache( $wanCache );

		// load the page row into the cache
		$linkCache->getGoodLinkRow( $existing->getNamespace(), $existing->getDBkey(), $callback );

		$keys = $linkCache->getMutableCacheKeys( $wanCache, $existing );
		$this->assertNotEmpty( $keys );

		foreach ( $keys as $key ) {
			$this->assertNotFalse( $wanCache->get( $key ) );
		}

		// replace real row data with fake, and assert that it gets used
		$wanCache->set( $key, $fakeRow );
		$linkCache->clearLink( $existing ); // clear local cache
		$this->assertSame(
			$fakeRow,
			$linkCache->getGoodLinkRow(
				$existing->getNamespace(),
				$existing->getDBkey(),
				$callback
			)
		);

		// set the "read latest" flag and try again
		$flags = IDBAccessObject::READ_LATEST;
		$this->assertEquals(
			$existingRow,
			$linkCache->getGoodLinkRow(
				$existing->getNamespace(),
				$existing->getDBkey(),
				$callback,
				$flags
			)
		);
	}
}
