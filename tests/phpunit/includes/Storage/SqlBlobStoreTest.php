<?php

namespace MediaWiki\Tests\Storage;

use ConcatenatedGzipHistoryBlob;
use ExternalStoreFactory;
use InvalidArgumentException;
use MediaWiki\Storage\BadBlobException;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiIntegrationTestCase;
use StatusValue;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @group Database
 * @covers \MediaWiki\Storage\SqlBlobStore
 */
class SqlBlobStoreTest extends MediaWikiIntegrationTestCase {

	public function createBlobStore(): SqlBlobStore {
		$services = $this->getServiceContainer();
		return new SqlBlobStore(
			$services->getDBLoadBalancer(),
			$services->getExternalStoreAccess(),
			$services->getMainWANObjectCache()
		);
	}

	public function testGetSetCompressRevisions() {
		$store = $this->createBlobStore();
		$this->assertFalse( $store->getCompressBlobs() );
		$store->setCompressBlobs( true );
		$this->assertTrue( $store->getCompressBlobs() );
	}

	public function testGetSetLegacyEncoding() {
		$store = $this->createBlobStore();
		$this->assertFalse( $store->getLegacyEncoding() );
		$store->setLegacyEncoding( 'foo' );
		$this->assertSame( 'foo', $store->getLegacyEncoding() );
	}

	public function testGetSetCacheExpiry() {
		$store = $this->createBlobStore();
		$this->assertSame( 604800, $store->getCacheExpiry() );
		$store->setCacheExpiry( 12 );
		$this->assertSame( 12, $store->getCacheExpiry() );
	}

	public function testGetSetUseExternalStore() {
		$store = $this->createBlobStore();
		$this->assertFalse( $store->getUseExternalStore() );
		$store->setUseExternalStore( true );
		$this->assertTrue( $store->getUseExternalStore() );
	}

	private function makeObjectBlob( $text ) {
		$obj = new ConcatenatedGzipHistoryBlob();
		$obj->setText( $text );
		return serialize( $obj );
	}

	public function provideDecompress() {
		yield '(no legacy encoding), empty in empty out' => [ false, '', [], '' ];
		yield '(no legacy encoding), string in string out' => [ false, 'A', [], 'A' ];
		yield '(no legacy encoding), error flag -> false' => [ false, 'X', [ 'error' ], false ];
		yield '(no legacy encoding), string in with gzip flag returns string' => [
			// gzip string below generated with gzdeflate( 'AAAABBAAA' )
			false, "sttttr\002\022\000", [ 'gzip' ], 'AAAABBAAA',
		];
		yield '(no legacy encoding), string in with object flag returns false' => [
			// gzip string below generated with serialize( 'JOJO' )
			false, "s:4:\"JOJO\";", [ 'object' ], false,
		];

		yield '(no legacy encoding), serialized object in with object flag returns string' => [
			false,
			$this->makeObjectBlob( 'HHJJDDFF' ),
			[ 'object' ],
			'HHJJDDFF',
		];
		yield '(no legacy encoding), serialized object in with object & gzip flag returns string' => [
			false,
			gzdeflate( $this->makeObjectBlob( '8219JJJ840' ) ),
			[ 'object', 'gzip' ],
			'8219JJJ840',
		];
		yield '(ISO-8859-1 encoding), string in string out' => [
			'ISO-8859-1',
			iconv( 'utf-8', 'ISO-8859-1', "1®Àþ1" ),
			[],
			'1®Àþ1',
		];
		yield '(ISO-8859-1 encoding), serialized object in with gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( iconv( 'utf-8', 'ISO-8859-1', "4®Àþ4" ) ),
			[ 'gzip' ],
			'4®Àþ4',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object flags returns string' => [
			'ISO-8859-1',
			$this->makeObjectBlob( iconv( 'utf-8', 'ISO-8859-1', "3®Àþ3" ) ),
			[ 'object' ],
			'3®Àþ3',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object & gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( $this->makeObjectBlob( iconv( 'utf-8', 'ISO-8859-1', "2®Àþ2" ) ) ),
			[ 'gzip', 'object' ],
			'2®Àþ2',
		];
		yield 'T184749 (windows-1252 encoding), string in string out' => [
			'windows-1252',
			iconv( 'utf-8', 'windows-1252', "sammansättningar" ),
			[],
			'sammansättningar',
		];
		yield 'T184749 (windows-1252 encoding), string in string out with gzip' => [
			'windows-1252',
			gzdeflate( iconv( 'utf-8', 'windows-1252', "sammansättningar" ) ),
			[ 'gzip' ],
			'sammansättningar',
		];
	}

	/**
	 * @dataProvider provideDecompress
	 * @param string|bool $legacyEncoding
	 * @param mixed $data
	 * @param array $flags
	 * @param mixed $expected
	 */
	public function testDecompressData( $legacyEncoding, $data, $flags, $expected ) {
		$store = $this->createBlobStore();

		if ( $legacyEncoding ) {
			$store->setLegacyEncoding( $legacyEncoding );
		}

		$this->assertSame(
			$expected,
			$store->decompressData( $data, $flags )
		);
	}

	public function testCompressRevisionTextUtf8() {
		$store = $this->createBlobStore();
		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertStringContainsString( 'utf-8', $row->old_flags,
			"Flags should contain 'utf-8'" );
		$this->assertStringNotContainsString( 'gzip', $row->old_flags,
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
	}

	/**
	 * @requires extension zlib
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$store = $this->createBlobStore();
		$store->setCompressBlobs( true );

		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertStringContainsString( 'utf-8', $row->old_flags,
			"Flags should contain 'utf-8'" );
		$this->assertStringContainsString( 'gzip', $row->old_flags,
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
	}

	public static function provideBlobs() {
		yield [ '' ];
		yield [ 'someText' ];
		yield [ "söme\ntäxt" ];
	}

	public function testSimpleStoreGetBlobKnownBad() {
		$store = $this->createBlobStore();
		$this->expectException( BadBlobException::class );
		$store->getBlob( 'bad:lost?bug=T12345' );
	}

	/**
	 * @param string $blob
	 * @dataProvider provideBlobs
	 */
	public function testSimpleStoreGetBlobSimpleRoundtrip( $blob ) {
		$store = $this->createBlobStore();
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	public function testSimpleStorageGetBlobBatchSimpleEmpty() {
		$store = $this->createBlobStore();
		$this->assertArrayEquals(
			[],
			$store->getBlobBatch( [] )->getValue()
		);
	}

	/**
	 * @param string $blob
	 * @dataProvider provideBlobs
	 */
	public function testSimpleStorageGetBlobBatchSimpleRoundtrip( $blob ) {
		$store = $this->createBlobStore();
		$addresses = [
			$store->storeBlob( $blob ),
			$store->storeBlob( $blob . '1' )
		];
		$this->assertArrayEquals(
			array_combine( $addresses, [ $blob, $blob . '1' ] ),
			$store->getBlobBatch( $addresses )->getValue()
		);
	}

	public function testCachingConsistency() {
		$store = $this->createBlobStore();

		$addrA = $store->storeBlob( 'A' );
		$addrB = $store->storeBlob( 'B' );
		$addrC = $store->storeBlob( 'C' );
		$addrD = $store->storeBlob( 'D' );
		$addrX = 'tt:0';

		$dataZ = "söme\ntäxt!";
		$addrZ = $store->storeBlob( $dataZ );

		$this->assertArrayEquals(
			[ $addrA => 'A', $addrC => 'C', $addrX => null ],
			$store->getBlobBatch( [ $addrA, $addrC, $addrX ] )->getValue(),
			false, true
		);

		$this->assertEquals( 'A', $store->getBlob( $addrA ) );
		$this->assertEquals( 'B', $store->getBlob( $addrB ) );
		$this->assertEquals( 'C', $store->getBlob( $addrC ) );

		$this->assertArrayEquals(
			[ $addrB => 'B', $addrC => 'C', $addrD => 'D' ],
			$store->getBlobBatch( [ $addrB, $addrC, $addrD ] )->getValue(),
			false, true
		);

		$this->assertEquals( $dataZ, $store->getBlob( $addrZ ) );

		$this->assertArrayEquals(
			[ $addrA => 'A', $addrZ => $dataZ ],
			$store->getBlobBatch( [ $addrA, $addrZ ] )->getValue(),
			false, true
		);
	}

	public function testSimpleStorageNonExistentBlob() {
		$this->expectException( BlobAccessException::class );
		$store = $this->createBlobStore();
		$store->getBlob( 'tt:this_will_not_exist' );
	}

	public function testSimpleStorageNonExistentBlobBatch() {
		$store = $this->createBlobStore();
		$result = $store->getBlobBatch( [
			'tt:this_will_not_exist',
			'tt:0',
			'tt:-1',
			'tt:10000',
			'bla:1001'
		] );
		$resultBlobs = $result->getValue();
		$expected = [
			'tt:this_will_not_exist' => null,
			'tt:0' => null,
			'tt:-1' => null,
			'tt:10000' => null,
			'bla:1001' => null
		];

		ksort( $expected );
		ksort( $resultBlobs );
		$this->assertSame( $expected, $resultBlobs );

		$this->assertStatusMessagesExactly(
			StatusValue::newGood()
				->warning( 'internalerror', 'Bad blob address: tt:this_will_not_exist. Use findBadBlobs.php to remedy.' )
				->warning( 'internalerror', 'Bad blob address: tt:0. Use findBadBlobs.php to remedy.' )
				->warning( 'internalerror', 'Bad blob address: tt:-1. Use findBadBlobs.php to remedy.' )
				->warning( 'internalerror', 'Unknown blob address schema: bla. Use findBadBlobs.php to remedy.' )
				->warning( 'internalerror', 'Unable to fetch blob at tt:10000. Use findBadBlobs.php to remedy.' ),
			$result
		);
	}

	public function testSimpleStoragePartialNonExistentBlobBatch() {
		$store = $this->createBlobStore();
		$address = $store->storeBlob( 'test_data' );
		$result = $store->getBlobBatch( [ $address, 'tt:this_will_not_exist_too' ] );
		$resultBlobs = $result->getValue();
		$expected = [
			$address => 'test_data',
			'tt:this_will_not_exist_too' => null
		];

		ksort( $expected );
		ksort( $resultBlobs );
		$this->assertSame( $expected, $resultBlobs );
		$this->assertStatusMessagesExactly(
			StatusValue::newGood()
				->warning( 'internalerror', 'Bad blob address: tt:this_will_not_exist_too. Use findBadBlobs.php to remedy.' ),
			$result
		);
	}

	/**
	 * @dataProvider provideBlobs
	 */
	public function testSimpleStoreGetBlobSimpleRoundtripWindowsLegacyEncoding( $blob ) {
		$store = $this->createBlobStore();
		$store->setLegacyEncoding( 'windows-1252' );
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	/**
	 * @dataProvider provideBlobs
	 */
	public function testSimpleStoreGetBlobSimpleRoundtripWindowsLegacyEncodingGzip( $blob ) {
		// FIXME: fails under postgres - T298692
		$this->markTestSkippedIfDbType( 'postgres' );
		$store = $this->createBlobStore();
		$store->setLegacyEncoding( 'windows-1252' );
		$store->setCompressBlobs( true );
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	public static function provideGetTextIdFromAddress() {
		yield [ 'tt:17', 17 ];
		yield [ 'xy:17', null ];
		yield [ 'xy:xyzzy', null ];
	}

	/**
	 * @dataProvider provideGetTextIdFromAddress
	 */
	public function testGetTextIdFromAddress( $address, $textId ) {
		$store = $this->createBlobStore();
		$this->assertSame( $textId, $store->getTextIdFromAddress( $address ) );
	}

	public static function provideGetTextIdFromAddressInvalidArgumentException() {
		yield [ 'tt:xy' ];
		yield [ 'tt:0' ];
		yield [ 'tt:' ];
		yield [ 'xy' ];
		yield [ '' ];
	}

	/**
	 * @dataProvider provideGetTextIdFromAddressInvalidArgumentException
	 */
	public function testGetTextIdFromAddressInvalidArgumentException( $address ) {
		$this->expectException( InvalidArgumentException::class );
		$store = $this->createBlobStore();
		$store->getTextIdFromAddress( $address );
	}

	public function testMakeAddressFromTextId() {
		$this->assertSame( 'tt:17', SqlBlobStore::makeAddressFromTextId( 17 ) );
	}

	public static function providerSplitBlobAddress() {
		yield [ 'tt:123', 'tt', '123', [] ];
		yield [ 'bad:foo?x=y', 'bad', 'foo', [ 'x' => 'y' ] ];
		yield [ 'http://test.com/foo/bar?a=b', 'http', 'test.com/foo/bar', [ 'a' => 'b' ] ];
	}

	/**
	 * @dataProvider providerSplitBlobAddress
	 */
	public function testSplitBlobAddress( $address, $schema, $id, $parameters ) {
		$this->assertSame( 'tt:17', SqlBlobStore::makeAddressFromTextId( 17 ) );
	}

	public static function provideExpandBlob() {
		yield 'Generic test' => [
			'This is a goat of revision text.',
			'old_flags' => '',
			'old_text' => 'This is a goat of revision text.',
		];
	}

	/**
	 * @dataProvider provideExpandBlob
	 */
	public function testExpandBlob( $expected, $flags, $raw ) {
		$blobStore = $this->createBlobStore();
		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public static function provideExpandBlobWithZlibExtension() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			'old_flags' => 'gzip',
			'old_text' => gzdeflate( 'This is a small goat of revision text.' ),
		];
	}

	/**
	 * @dataProvider provideExpandBlobWithZlibExtension
	 * @requires extension zlib
	 */
	public function testGetRevisionWithZlibExtension( $expected, $flags, $raw ) {
		$blobStore = $this->createBlobStore();
		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public static function provideExpandBlobWithZlibExtension_badData() {
		yield 'Generic gzip test' => [
			'old_flags' => 'gzip',
			'old_text' => 'DEAD BEEF',
		];
	}

	/**
	 * @dataProvider provideExpandBlobWithZlibExtension_badData
	 * @requires extension zlib
	 */
	public function testGetRevisionWithZlibExtension_badData( $flags, $raw ) {
		$blobStore = $this->createBlobStore();

		$this->assertFalse(
			@$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public static function provideExpandBlobWithLegacyEncoding() {
		yield 'Utf8Native' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			'old_flags' => 'utf-8',
			'old_text' => "Wiki est l'\xc3\xa9cole superieur !",
		];
		yield 'Utf8Legacy' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			'old_flags' => '',
			'old_text' => "Wiki est l'\xe9cole superieur !",
		];
	}

	/**
	 * @dataProvider provideExpandBlobWithLegacyEncoding
	 */
	public function testGetRevisionWithLegacyEncoding( $expected, $encoding, $flags, $raw ) {
		$blobStore = $this->createBlobStore();
		$blobStore->setLegacyEncoding( $encoding );

		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public static function provideExpandBlobWithGzipAndLegacyEncoding() {
		/**
		 * WARNING!
		 * Do not set the external flag!
		 * Otherwise, getRevisionText will hit the live database (if ExternalStore is enabled)!
		 */
		yield 'Utf8NativeGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			'old_flags' => 'gzip,utf-8',
			'old_text' => gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" ),
		];
		yield 'Utf8LegacyGzip' => [
			"Wiki est l'\xc3\xa9cole superieur !",
			'iso-8859-1',
			'old_flags' => 'gzip',
			'old_text' => gzdeflate( "Wiki est l'\xe9cole superieur !" ),
		];
	}

	/**
	 * @dataProvider provideExpandBlobWithGzipAndLegacyEncoding
	 * @requires extension zlib
	 */
	public function testGetRevisionWithGzipAndLegacyEncoding( $expected, $encoding, $flags, $raw ) {
		$blobStore = $this->createBlobStore();
		$blobStore->setLegacyEncoding( $encoding );

		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public static function provideTestGetRevisionText_returnsDecompressedTextFieldWhenNotExternal() {
		yield 'Just text' => [
			'old_flags' => '',
			'old_text' => 'SomeText',
			'SomeText'
		];
		// gzip string below generated with gzdeflate( 'AAAABBAAA' )
		yield 'gzip text' => [
			'old_flags' => 'gzip',
			'old_text' => "sttttr\002\022\000",
			'AAAABBAAA'
		];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_returnsDecompressedTextFieldWhenNotExternal
	 */
	public function testGetRevisionText_returnsDecompressedTextFieldWhenNotExternal(
		$flags,
		$raw,
		$expected
	) {
		$blobStore = $this->createBlobStore();
		$this->assertSame( $expected, $blobStore->expandBlob( $raw, $flags ) );
	}

	public static function provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts() {
		yield 'Just some text' => [ 'someNonUrlText' ];
		yield 'No second URL part' => [ 'someProtocol://' ];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts
	 */
	public function testGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts(
		$text
	) {
		$blobStore = $this->createBlobStore();
		$this->assertFalse(
			$blobStore->expandBlob(
				$text,
				[ 'external' ]
			)
		);
	}

	public function testGetRevisionText_external_noOldId() {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);
		$blobStore = $this->createBlobStore();
		$this->assertSame(
			'AAAABBAAA',
			$blobStore->expandBlob(
				'ForTesting://cluster1/12345',
				[ 'external', 'gzip' ]
			)
		);
	}

	public function testGetRevisionText_external_oldId() {
		$cache = $this->getServiceContainer()->getMainWANObjectCache();

		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$lb = $this->createMock( LoadBalancer::class );
		$access = $this->getServiceContainer()->getExternalStoreAccess();

		$blobStore = new SqlBlobStore( $lb, $access, $cache );

		$this->assertSame(
			'AAAABBAAA',
			$blobStore->expandBlob(
				'ForTesting://cluster1/12345',
				'external,gzip',
				'tt:7777'
			)
		);

		$cacheKey = $cache->makeGlobalKey(
			'SqlBlobStore-blob',
			$lb->getLocalDomainID(),
			'tt:7777'
		);
		$this->assertSame( 'AAAABBAAA', $cache->get( $cacheKey ) );
	}

	public function testGetRevisionText_external_oldId_direct_access() {
		$cache = $this->getServiceContainer()->getMainWANObjectCache();

		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$lb = $this->createMock( LoadBalancer::class );
		$access = $this->getServiceContainer()->getExternalStoreAccess();

		$blobStore = new SqlBlobStore( $lb, $access, $cache );

		$this->assertSame(
			'AAAABBAAA',
			$blobStore->getBlob( 'es:ForTesting://cluster1/12345?flags=external,gzip' )
		);

		$cacheKey = $cache->makeGlobalKey(
			'SqlBlobStore-blob',
			$lb->getLocalDomainID(),
			// See ExternalStoreForTesting for the path
			'es:ForTesting://cluster1/12345?flags=external,gzip'
		);
		$this->assertSame( 'AAAABBAAA', $cache->get( $cacheKey ) );
	}

	public static function provideTestGetRevisionText_external_oldId_direct_store() {
		yield 'no compression' => [ false ];
		yield 'compression' => [ true ];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_external_oldId_direct_store
	 */
	public function testGetRevisionText_external_oldId_direct_store( bool $compression ) {
		$cache = $this->getServiceContainer()->getMainWANObjectCache();

		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$lb = $this->createMock( LoadBalancer::class );
		$access = $this->getServiceContainer()->getExternalStoreAccess();

		$blobStore = new SqlBlobStore( $lb, $access, $cache );
		$blobStore->setUseExternalStore( true );
		$blobStore->setCompressBlobs( $compression );
		$id = $blobStore->storeBlob( 'A very unique text' );
		$this->assertStringStartsWith( 'es:ForTesting://cluster1/', $id );

		$this->assertSame(
			'A very unique text',
			$blobStore->getBlob( $id )
		);

		$cacheKey = $cache->makeGlobalKey(
			'SqlBlobStore-blob',
			$lb->getLocalDomainID(),
			$id
		);
		$this->assertSame( 'A very unique text', $cache->get( $cacheKey ) );
	}
}
