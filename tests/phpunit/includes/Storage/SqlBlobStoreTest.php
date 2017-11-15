<?php

namespace MediaWiki\Tests\Storage;

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
			$store->setCompressRevisions( $compressRevisions );
		}
		if ( $legacyEncoding ) {
			$store->setLegacyEncoding( $legacyEncoding, Language::factory( 'en' ) );
		}

		return $store;
	}

	/**
	 * @covers SqlBlobStore::getCompressRevisions()
	 * @covers SqlBlobStore::setCompressRevisions()
	 */
	public function testGetSetCompressRevisions() {
		$store = $this->getBlobStore();
		$this->assertFalse( $store->getCompressRevisions() );
		$store->setCompressRevisions( true );
		$this->assertTrue( $store->getCompressRevisions() );
	}

	/**
	 * @covers SqlBlobStore::getLegacyEncoding()
	 * @covers SqlBlobStore::getLegacyEncodingConversionLang()
	 * @covers SqlBlobStore::setLegacyEncoding()
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
	 * @covers SqlBlobStore::getCacheExpiry()
	 * @covers SqlBlobStore::setCacheExpiry()
	 */
	public function testGetSetCacheExpiry() {
		$store = $this->getBlobStore();
		$this->assertSame( 604800, $store->getCacheExpiry() );
		$store->setCacheExpiry( 12 );
		$this->assertSame( 12, $store->getCacheExpiry() );
	}

	/**
	 * @covers SqlBlobStore::getUseExternalStore()
	 * @covers SqlBlobStore::setUseExternalStore()
	 */
	public function testGetSetUseExternalStore() {
		$store = $this->getBlobStore();
		$this->assertFalse( $store->getUseExternalStore() );
		$store->setUseExternalStore( true );
		$this->assertTrue( $store->getUseExternalStore() );
	}

	public function provideDecompress() {
		yield '(no legacy encoding), false in false out' => [ false, false, [], false ];
		yield '(no legacy encoding), empty in empty out' => [ false, '', [], '' ];
		yield '(no legacy encoding), empty in empty out' => [ false, 'A', [], 'A' ];
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
			iconv( 'utf8', 'ISO-8859-1', "1®Àþ1" ),
			[],
			'1®Àþ1',
		];
		yield '(ISO-8859-1 encoding), serialized object in with gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( iconv( 'utf8', 'ISO-8859-1', "4®Àþ4" ) ),
			[ 'gzip' ],
			'4®Àþ4',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object flags returns string' => [
			'ISO-8859-1',
			serialize( new TitleValue( 0, iconv( 'utf8', 'ISO-8859-1', "3®Àþ3" ) ) ),
			[ 'object' ],
			'3®Àþ3',
		];
		yield '(ISO-8859-1 encoding), serialized object in with object & gzip flags returns string' => [
			'ISO-8859-1',
			gzdeflate( serialize( new TitleValue( 0, iconv( 'utf8', 'ISO-8859-1', "2®Àþ2" ) ) ) ),
			[ 'gzip', 'object' ],
			'2®Àþ2',
		];
	}

	/**
	 * @dataProvider provideDecompress
	 * @covers SqlBlobStore::decompressRevisionData
	 *
	 * @param string|bool $legacyEncoding
	 * @param mixed $blob
	 * @param array $flags
	 * @param mixed $expected
	 */
	public function testDecompressRevisionData( $legacyEncoding, $blob, $flags, $expected ) {
		$store = $this->getBlobStore( $legacyEncoding );
		$this->assertSame(
			$expected,
			$store->decompressRevisionData( $blob, $flags )
		);
	}

	/**
	 * @covers SqlBlobStore::compressRevisionData
	 */
	public function testCompressRevisionTextUtf8() {
		$store = $this->getBlobStore();
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = $store->compressRevisionData( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
	}

	/**
	 * @covers SqlBlobStore::compressRevisionData
	 */
	public function testCompressRevisionTextUtf8Gzip() {
		$store = $this->getBlobStore( false, true );
		$this->checkPHPExtension( 'zlib' );

		$row = new stdClass;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = $store->compressRevisionData( $row->old_text );
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
	}

	/**
	 * @dataProvider provideBlobs
	 * @covers SqlBlobStore::storeBlob
	 * @covers SqlBlobStore::getBlob
	 */
	public function testSimpleStoreGetBlobSimpleRoundtrip( $blob ) {
		$store = $this->getBlobStore();
		$address = $store->storeBlob( $blob );
		$this->assertSame( $blob, $store->getBlob( $address ) );
	}

}
