<?php

namespace MediaWiki\Tests\Storage;

use InvalidArgumentException;
use Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use stdClass;
use TitleValue;

/**
 * @covers \MediaWiki\Storage\SqlBlobStore
 * @group Database
 */
class SqlBlobStoreTest extends MediaWikiTestCase {

	/**
	 * @return SqlBlobStore
	 */
	public function getBlobStore( $legacyEncoding = false, $compressRevisions = false ) {
		$services = MediaWikiServices::getInstance();

		$store = new SqlBlobStore(
			$services->getDBLoadBalancer(),
			$services->getMainWANObjectCache()
		);

		if ( $compressRevisions ) {
			$store->setCompressBlobs( $compressRevisions );
		}
		if ( $legacyEncoding ) {
			$store->setLegacyEncoding( $legacyEncoding, Language::factory( 'en' ) );
		}

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
		$this->assertNull( $store->getLegacyEncodingConversionLang() );
		$en = Language::factory( 'en' );
		$store->setLegacyEncoding( 'foo', $en );
		$this->assertSame( 'foo', $store->getLegacyEncoding() );
		$this->assertSame( $en, $store->getLegacyEncodingConversionLang() );
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
		yield '(no legacy encoding), empty in empty out' => [ false, 'A', [], 'A' ];
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
		$store = $this->getBlobStore( $legacyEncoding );
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

		$this->setExpectedException( InvalidArgumentException::class );
		$store->decompressData( false, [] );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::compressData
	 */
	public function testCompressRevisionTextUtf8() {
		$store = $this->getBlobStore();
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
	}

	/**
	 * @covers \MediaWiki\Storage\SqlBlobStore::compressData
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$store = $this->getBlobStore( false, true );
		$this->checkPHPExtension( 'zlib' );

		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = $store->compressData( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertTrue( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
	}

	public function provideBlobs() {
		yield [ '' ];
		yield [ 'someText' ];
		yield [ "sammansättningar" ];
	}

	/**
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
	 * @dataProvider provideBlobs
	 * @covers \MediaWiki\Storage\SqlBlobStore::storeBlob
	 * @covers \MediaWiki\Storage\SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobSimpleRoundtripWindowsLegacyEncoding( $blob ) {
		$store = $this->getBlobStore( 'windows-1252' );
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
		$store = $this->getBlobStore( 'windows-1252', true );
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
		yield [ 'tt:-17' ];
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
		$this->setExpectedException( InvalidArgumentException::class );
		$store = $this->getBlobStore();
		$store->getTextIdFromAddress( $address );
	}

	public function testMakeAddressFromTextId() {
		$this->assertSame( 'tt:17', SqlBlobStore::makeAddressFromTextId( 17 ) );
	}

}
