<?php

namespace MediaWiki\Tests\Storage;

use ExternalStoreAccess;
use ExternalStoreFactory;
use HashBagOStuff;
use InvalidArgumentException;
use LoadBalancer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobAccessException;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiIntegrationTestCase;
use TitleValue;
use WANObjectCache;
use Wikimedia\AtEase\AtEase;

/**
 * @covers \MediaWiki\Storage\SqlBlobStore
 * @group Database
 */
class SqlBlobStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @param WANObjectCache|null $cache
	 * @param ExternalStoreAccess|null $extStore
	 *
	 * @return SqlBlobStore
	 */
	public function getBlobStore(
		WANObjectCache $cache = null,
		ExternalStoreAccess $extStore = null
	) {
		$services = MediaWikiServices::getInstance();

		$store = new SqlBlobStore(
			$services->getDBLoadBalancer(),
			$extStore ?: $services->getExternalStoreAccess(),
			$cache ?: $services->getMainWANObjectCache()
		);

		return $store;
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getCompressBlobs()
	 * @covers \MediaWiki\Storage\SqlBlobStore::setCompressBlobs()
	 */
	public function testGetSetCompressRevisions() {
		$store = $this->getBlobStore();
		$this->assertFalse( $store->getCompressBlobs() );
		$store->setCompressBlobs( true );
		$this->assertTrue( $store->getCompressBlobs() );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getLegacyEncoding()
	 * @covers \MediaWiki\Storage\SqlBlobStore::getLegacyEncodingConversionLang()
	 * @covers \MediaWiki\Storage\SqlBlobStore::setLegacyEncoding()
	 */
	public function testGetSetLegacyEncoding() {
		$store = $this->getBlobStore();
		$this->assertFalse( $store->getLegacyEncoding() );
		$store->setLegacyEncoding( 'foo' );
		$this->assertSame( 'foo', $store->getLegacyEncoding() );

		$this->hideDeprecated( SqlBlobStore::class . '::getLegacyEncodingConversionLang' );
		$this->assertNull( $store->getLegacyEncodingConversionLang() );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getCacheExpiry()
	 * @covers \MediaWiki\Storage\SqlBlobStore::setCacheExpiry()
	 */
	public function testGetSetCacheExpiry() {
		$store = $this->getBlobStore();
		$this->assertSame( 604800, $store->getCacheExpiry() );
		$store->setCacheExpiry( 12 );
		$this->assertSame( 12, $store->getCacheExpiry() );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getUseExternalStore()
	 * @covers \MediaWiki\Storage\SqlBlobStore::setUseExternalStore()
	 */
	public function testGetSetUseExternalStore() {
		$store = $this->getBlobStore();
		$this->assertFalse( $store->getUseExternalStore() );
		$store->setUseExternalStore( true );
		$this->assertTrue( $store->getUseExternalStore() );
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
			// Using a TitleValue object as it has a getText method (which is needed)
			serialize( new TitleValue( 0, 'HHJJDDFF' ) ),
			[ 'object' ],
			'HHJJDDFF',
		];
		yield '(no legacy encoding), serialized object in with object & gzip flag returns string' => [
			false,
			// Using a TitleValue object as it has a getText method (which is needed)
			gzdeflate( serialize( new TitleValue( 0, '8219JJJ840' ) ) ),
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
			serialize( new TitleValue( 0, iconv( 'utf-8', 'ISO-8859-1', "3®Àþ3" ) ) ),
			[ 'object' ],
			'3®Àþ3',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object & gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( serialize( new TitleValue( 0, iconv( 'utf-8', 'ISO-8859-1', "2®Àþ2" ) ) ) ),
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
	 * @covers \MediaWiki\Storage\SqlBlobStore::decompressData
	 *
	 * @param string|bool $legacyEncoding
	 * @param mixed $data
	 * @param array $flags
	 * @param mixed $expected
	 */
	public function testDecompressData( $legacyEncoding, $data, $flags, $expected ) {
		$store = $this->getBlobStore();

		if ( $legacyEncoding ) {
			$store->setLegacyEncoding( $legacyEncoding );
		}

		$this->assertSame(
			$expected,
			$store->decompressData( $data, $flags )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::decompressData
	 */
	public function testDecompressData_InvalidArgumentException() {
		$store = $this->getBlobStore();

		$this->expectException( InvalidArgumentException::class );
		$store->decompressData( false, [] );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::compressData
	 */
	public function testCompressRevisionTextUtf8() {
		$store = $this->getBlobStore();
		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertFalse( strpos( $row->old_flags, 'gzip' ) !== false,
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::compressData
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$store = $this->getBlobStore();
		$store->setCompressBlobs( true );
		$this->checkPHPExtension( 'zlib' );

		$row = (object)[ 'old_text' => "Wiki est l'\xc3\xa9cole superieur !" ];
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertTrue( strpos( $row->old_flags, 'utf-8' ) !== false,
			"Flags should contain 'utf-8'" );
		$this->assertTrue( strpos( $row->old_flags, 'gzip' ) !== false,
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
	}

	public function provideBlobs() {
		yield [ '' ];
		yield [ 'someText' ];
		yield [ "söme\ntäxt" ];
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobKnownBad() {
		$store = $this->getBlobStore();
		$this->assertSame( '', $store->getBlob( 'bad:lost?bug=T12345' ) );
	}

	/**
	 * @param string $blob
	 * @dataProvider provideBlobs
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobSimpleRoundtrip( $blob ) {
		$store = $this->getBlobStore();
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlobBatch
	 */
	public function testSimpleStorageGetBlobBatchSimpleEmpty() {
		$store = $this->getBlobStore();
		$this->assertArrayEquals(
			[],
			$store->getBlobBatch( [] )->getValue()
		);
	}

	/**
	 * @param string $blob
	 * @dataProvider provideBlobs
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlobBatch
	 */
	public function testSimpleStorageGetBlobBatchSimpleRoundtrip( $blob ) {
		$store = $this->getBlobStore();
		$addresses = [
			$store->storeBlob( $blob ),
			$store->storeBlob( $blob . '1' )
		];
		$this->assertArrayEquals(
			array_combine( $addresses, [ $blob, $blob . '1' ] ),
			$store->getBlobBatch( $addresses )->getValue()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlobBatch
	 */
	public function testCachingConsistency() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$store = $this->getBlobStore( $cache );

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

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStorageNonExistentBlob() {
		$this->expectException( BlobAccessException::class );
		$store = $this->getBlobStore();
		$store->getBlob( 'tt:this_will_not_exist' );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlobBatch
	 */
	public function testSimpleStorageNonExistentBlobBatch() {
		$store = $this->getBlobStore();
		$result = $store->getBlobBatch( [
				'tt:this_will_not_exist',
				'tt:0',
				'tt:-1',
				'tt:1000',
				'bla:1001'
		] );
		$resultBlobs = $result->getValue();
		$expected = [
			'tt:this_will_not_exist' => null,
			'tt:0' => null,
			'tt:-1' => null,
			'tt:1000' => null,
			'bla:1001' => null
		];

		ksort( $expected );
		ksort( $resultBlobs );
		$this->assertSame( $expected, $resultBlobs );

		$this->assertSame( [
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Bad blob address: tt:this_will_not_exist'
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Bad blob address: tt:0'
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Bad blob address: tt:-1'
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Unknown blob address schema: bla'
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Unable to fetch blob at tt:1000'
				]
			]
		], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlobBatch
	 */
	public function testSimpleStoragePartialNonExistentBlobBatch() {
		$store = $this->getBlobStore();
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
		$this->assertSame( [
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					'Bad blob address: tt:this_will_not_exist_too'
				]
			],
		], $result->getErrors() );
	}

	/**
	 * @dataProvider provideBlobs
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobSimpleRoundtripWindowsLegacyEncoding( $blob ) {
		$store = $this->getBlobStore();
		$store->setLegacyEncoding( 'windows-1252' );
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	/**
	 * @dataProvider provideBlobs
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobSimpleRoundtripWindowsLegacyEncodingGzip( $blob ) {
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );
		$store = $this->getBlobStore();
		$store->setLegacyEncoding( 'windows-1252' );
		$store->setCompressBlobs( true );
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

	public function provideGetTextIdFromAddress() {
		yield [ 'tt:17', 17 ];
		yield [ 'xy:17', null ];
		yield [ 'xy:xyzzy', null ];
	}

	/**
	 * @dataProvider provideGetTextIdFromAddress
	 */
	public function testGetTextIdFromAddress( $address, $textId ) {
		$store = $this->getBlobStore();
		$this->assertSame( $textId, $store->getTextIdFromAddress( $address ) );
	}

	public function provideGetTextIdFromAddressInvalidArgumentException() {
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
		$store = $this->getBlobStore();
		$store->getTextIdFromAddress( $address );
	}

	public function testMakeAddressFromTextId() {
		$this->assertSame( 'tt:17', SqlBlobStore::makeAddressFromTextId( 17 ) );
	}

	public function providerSplitBlobAddress() {
		yield [ 'tt:123', 'tt', '123', [] ];
		yield [ 'bad:foo?x=y', 'bad', 'foo', [ 'x' => 'y' ] ];
		yield [ 'http://test.com/foo/bar?a=b', 'http', 'test.com/foo/bar', [ 'a' => 'b' ] ];
	}

	/**
	 * @dataProvider providerSplitBlobAddress
	 *
	 * @param $address
	 * @param $schema
	 * @param $id
	 * @param $parameters
	 */
	public function testSplitBlobAddress( $address, $schema, $id, $parameters ) {
		$this->assertSame( 'tt:17', SqlBlobStore::makeAddressFromTextId( 17 ) );
	}

	public function provideExpandBlob() {
		yield 'Generic test' => [
			'This is a goat of revision text.',
			'old_flags' => '',
			'old_text' => 'This is a goat of revision text.',
		];
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 * @dataProvider provideExpandBlob
	 */
	public function testExpandBlob( $expected, $flags, $raw ) {
		$blobStore = $this->getBlobStore();
		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public function provideExpandBlobWithZlibExtension() {
		yield 'Generic gzip test' => [
			'This is a small goat of revision text.',
			'old_flags' => 'gzip',
			'old_text' => gzdeflate( 'This is a small goat of revision text.' ),
		];
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 * @dataProvider provideExpandBlobWithZlibExtension
	 */
	public function testGetRevisionWithZlibExtension( $expected, $flags, $raw ) {
		$this->checkPHPExtension( 'zlib' );
		$blobStore = $this->getBlobStore();
		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public function provideExpandBlobWithZlibExtension_badData() {
		yield 'Generic gzip test' => [
			'old_flags' => 'gzip',
			'old_text' => 'DEAD BEEF',
		];
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 * @dataProvider provideExpandBlobWithZlibExtension_badData
	 */
	public function testGetRevisionWithZlibExtension_badData( $flags, $raw ) {
		$this->checkPHPExtension( 'zlib' );
		$blobStore = $this->getBlobStore();

		AtEase::suppressWarnings();
		$this->assertFalse(
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
		AtEase::restoreWarnings();
	}

	public function provideExpandBlobWithLegacyEncoding() {
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
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 * @dataProvider provideExpandBlobWithLegacyEncoding
	 */
	public function testGetRevisionWithLegacyEncoding( $expected, $encoding, $flags, $raw ) {
		$blobStore = $this->getBlobStore();
		$blobStore->setLegacyEncoding( $encoding );

		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public function provideExpandBlobWithGzipAndLegacyEncoding() {
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
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 * @dataProvider provideExpandBlobWithGzipAndLegacyEncoding
	 */
	public function testGetRevisionWithGzipAndLegacyEncoding( $expected, $encoding, $flags, $raw ) {
		$this->checkPHPExtension( 'zlib' );

		$blobStore = $this->getBlobStore();
		$blobStore->setLegacyEncoding( $encoding );

		$this->assertEquals(
			$expected,
			$blobStore->expandBlob( $raw, explode( ',', $flags ) )
		);
	}

	public function provideTestGetRevisionText_returnsDecompressedTextFieldWhenNotExternal() {
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
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 */
	public function testGetRevisionText_returnsDecompressedTextFieldWhenNotExternal(
		$flags,
		$raw,
		$expected
	) {
		$blobStore = $this->getBlobStore();
		$this->assertSame( $expected, $blobStore->expandBlob( $raw, $flags ) );
	}

	public function provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts() {
		yield 'Just some text' => [ 'someNonUrlText' ];
		yield 'No second URL part' => [ 'someProtocol://' ];
	}

	/**
	 * @dataProvider provideTestGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 */
	public function testGetRevisionText_external_returnsFalseWhenNotEnoughUrlParts(
		$text
	) {
		$blobStore = $this->getBlobStore();
		$this->assertFalse(
			$blobStore->expandBlob(
				$text,
				[ 'external' ]
			)
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 */
	public function testGetRevisionText_external_noOldId() {
		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);
		$blobStore = $this->getBlobStore();
		$this->assertSame(
			'AAAABBAAA',
			$blobStore->expandBlob(
				'ForTesting://cluster1/12345',
				[ 'external' , 'gzip' ]
			)
		);
	}

	private function getWANObjectCache() {
		return new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::expandBlob
	 */
	public function testGetRevisionText_external_oldId() {
		$cache = $this->getWANObjectCache();
		$this->setService( 'MainWANObjectCache', $cache );

		$this->setService(
			'ExternalStoreFactory',
			new ExternalStoreFactory( [ 'ForTesting' ], [ 'ForTesting://cluster1' ], 'test-id' )
		);

		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$access = MediaWikiServices::getInstance()->getExternalStoreAccess();

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

}
