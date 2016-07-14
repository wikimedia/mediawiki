<?php
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\Sql\TextTableBlobStore;

/**
 * @group Database
 *
 * @covers MediaWiki\Storage\Sql\TextTableBlobStore
 */
class TextTableBlobStoreTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();

		$this->tablesUsed[] = 'text';
	}

	/**
	 * @param int $id
	 * @return object|bool
	 */
	private function selectTextRow( $id ) {
		return $this->db->selectRow( 'text', '*', [ 'old_id' => $id ], __METHOD__ );
	}

	/**
	 * @param int $id
	 */
	private function deleteTextRow( $id ) {
		$this->db->delete( 'text', [ 'old_id' => $id ], __METHOD__ );
	}

	private function newBlobStore() {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		return new TextTableBlobStore( $lb );
	}

	/**
	 * @covers TextTableBlobStore::storeData
	 * @covers TextTableBlobStore::loadData
	 */
	public function testStoreData() {
		$text = 'just some text data';

		$blobStore = $this->newBlobStore();
		$address = $blobStore->storeData( $text );
		$this->assertRegExp( '/^\d+$/', $address );

		// DB layer
		$row = $this->selectTextRow( $address );
		$this->assertInternalType( 'object', $row );
		$this->assertSame( $address, $row->old_id );
		$this->assertSame( $text, TextTableBlobStore::getRevisionText( $row ) );

		// round trip
		$loadedData = $blobStore->loadData( $address );
		$this->assertSame( $text, $loadedData );
	}

	/**
	 * @covers TextTableBlobStore::getHints
	 */
	public function testGetHints() {
		$blobStore = $this->newBlobStore();
		$address = $blobStore->storeData( 'TEST', [ 'foo' => 'bar' ] );

		// we currently have no expectations about the available hints
		$hints = $blobStore->getHints( $address );
		$this->assertInternalType( 'array', $hints );
	}

	///////////////////////////////////////////////////////////////////////////////////////

	/**
	 * @covers TextTableBlobStore::setCacheExpiry
	 */
	public function testSetCacheExpiry() {
		$blobStore = $this->newBlobStore();

		// enable caching by setting cache expiry
		$blobStore->setCacheExpiry( 100 );
		$address = $blobStore->storeData( 'TEST' );

		// trigger caching by loading the blob, then remove the blob from the table
		$blobStore->loadData( $address );
		$this->deleteTextRow( $address );

		// we should still be able to load the blob from the local cache
		$loadedData = $blobStore->loadData( $address );
		$this->assertSame( 'TEST', $loadedData );
	}

	/**
	 * @covers TextTableBlobStore::setCacheExpiry
	 */
	public function testSetCacheCapacity() {
		$blobStore = $this->newBlobStore();

		// enable caching by setting cache expiry
		$blobStore->setCacheExpiry( 1 );
		$blobStore->setCacheCapacity( 1 );
		$address = $blobStore->storeData( 'TEST' );

		// trigger caching by loading the blob, then remove the blob from the table
		$blobStore->loadData( $address );
		$this->deleteTextRow( $address );

		// increase cache capacity to trigger re-creation of the local cache
		$blobStore->setCacheCapacity( 3 );

		// we should still be able to load the blob from the local cache
		$loadedData = $blobStore->loadData( $address );
		$this->assertSame( 'TEST', $loadedData );
	}

	/**
	 * @covers TextTableBlobStore::setWanCache
	 */
	public function testSetWanCache() {
		$blobStore = $this->newBlobStore();
		$address = '7';

		// Note: can't mock final methods in WANObjectCache
		$wanCache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		// Ugly, we have to know the internal cache key here. Better mock?
		$key = wfMemcKey( 'revisiontext', 'textid', $address );
		$wanCache->set( $key, '*cached*' );

		// enable WAN caching
		$blobStore->setCacheExpiry( 100 );
		$blobStore->setWanCache( $wanCache );

		// we should be able to load the blob from the WAN cache
		$loadedData = $blobStore->loadData( $address );
		$this->assertSame( '*cached*', $loadedData );
	}

	/**
	 * @covers TextTableBlobStore::setWanCache
	 */
	public function testSetExternalStore() {
		$this->setMwGlobals( 'wgExternalStores', [ 'TextTableBlobStoreTest' ] );

		$blobStore = $this->newBlobStore();
		$blobStore->setExternalStore( 'TextTableBlobStoreTest://clusterX' );
		$address = $blobStore->storeData( 'NOPE' );

		// DB layer: $text should NOT be in old_text
		$row = $this->selectTextRow( $address );
		$this->assertInternalType( 'object', $row );
		$this->assertSame( $address, $row->old_id );
		$this->assertContains( 'TextTableBlobStoreTest://clusterX/23', $row->old_text );
		$this->assertContains( 'external', $row->old_flags );
		$this->assertNotContains( 'NOPE', $row->old_text );

		// we should be able to load the blob from the external store
		$loadedData = $blobStore->loadData( $address );
		$this->assertSame( '*external*', $loadedData );
	}

	///////////////////////////////////////////////////////////////////////////////////////

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionText() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = 'This is a bunch of revision text.';
		$this->assertSame(
			'This is a bunch of revision text.',
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionTextGzip() {
		$this->checkPHPExtension( 'zlib' );

		$row = new stdClass;
		$row->old_flags = 'gzip';
		$row->old_text = gzdeflate( 'This is a bunch of revision text.' );
		$this->assertSame(
			'This is a bunch of revision text.',
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionTextUtf8Native() {
		$row = new stdClass;
		$row->old_flags = 'utf-8';
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$this->setMwGlobals( 'wgLegacyEncoding', 'iso-8859-1' );
		$this->assertSame(
			"Wiki est l'\xc3\xa9cole superieur !",
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionTextUtf8Legacy() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = "Wiki est l'\xe9cole superieur !";
		$this->setMwGlobals( 'wgLegacyEncoding', 'iso-8859-1' );
		$this->assertSame(
			"Wiki est l'\xc3\xa9cole superieur !",
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionTextUtf8NativeGzip() {
		$this->checkPHPExtension( 'zlib' );

		$row = new stdClass;
		$row->old_flags = 'gzip,utf-8';
		$row->old_text = gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" );
		$this->setMwGlobals( 'wgLegacyEncoding', 'iso-8859-1' );
		$this->assertSame(
			"Wiki est l'\xc3\xa9cole superieur !",
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::getRevisionText
	 */
	public function testGetRevisionTextUtf8LegacyGzip() {
		$this->checkPHPExtension( 'zlib' );

		$row = new stdClass;
		$row->old_flags = 'gzip';
		$row->old_text = gzdeflate( "Wiki est l'\xe9cole superieur !" );
		$this->setMwGlobals( 'wgLegacyEncoding', 'iso-8859-1' );
		$this->assertSame(
			"Wiki est l'\xc3\xa9cole superieur !",
			TextTableBlobStore::getRevisionText( $row ) );
	}

	/**
	 * @covers TextTableBlobStore::compressRevisionText
	 * @covers TextTableBlobStore::decompressRevisionText
	 */
	public function testCompressRevisionTextUtf8() {
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = TextTableBlobStore::compressRevisionText( $row->old_text );
		$this->assertContains( 'utf-8', $row->old_flags,
		                   "Flags should contain 'utf-8'" );
		$this->assertNotContains( 'gzip', $row->old_flags,
		                       "Flags should not contain 'gzip'" );
		$this->assertSame( "Wiki est l'\xc3\xa9cole superieur !",
		                     $row->old_text, "Direct check" );
		$this->assertSame( "Wiki est l'\xc3\xa9cole superieur !",
		                     TextTableBlobStore::getRevisionText( $row ), "getRevisionText" );
	}

	/**
	 * @covers TextTableBlobStore::compressRevisionText
	 * @covers TextTableBlobStore::decompressRevisionText
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$this->checkPHPExtension( 'zlib' );
		$this->setMwGlobals( 'wgCompressRevisions', true );

		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = TextTableBlobStore::compressRevisionText( $row->old_text );
		$this->assertContains( 'utf-8', $row->old_flags,
		                   "Flags should contain 'utf-8'" );
		$this->assertContains( 'gzip', $row->old_flags,
		                   "Flags should contain 'gzip'" );
		$this->assertSame( "Wiki est l'\xc3\xa9cole superieur !",
		                     gzinflate( $row->old_text ), "Direct check" );
		$this->assertSame( "Wiki est l'\xc3\xa9cole superieur !",
		                     TextTableBlobStore::getRevisionText( $row ), "getRevisionText" );
	}

}

/**
 * Mock implementation of ExternalStore for testing.
 * Can be removed once we have proper wiring and injection for ExternalStore implementations.
 */
class ExternalStoreTextTableBlobStoreTest extends ExternalStoreMedium {

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * Fetch data from given external store URL
	 *
	 * @param string $url An external store URL
	 * @return string|bool The text stored or false on error
	 * @throws MWException
	 */
	public function fetchFromURL( $url ) {
		return '*external*';
	}

	/**
	 * Insert a data item into a given location
	 *
	 * @param string $location The location name
	 * @param string $data The data item
	 * @return string|bool The URL of the stored data item, or false on error
	 * @throws MWException
	 */
	public function store( $location, $data ) {
		$this->data = $data;
		return 'TextTableBlobStoreTest://clusterX/23';
	}
}
