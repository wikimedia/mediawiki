<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Storage\BlobStoreFactory
 */
class BlobStoreFactoryTest extends MediaWikiTestCase {

	public function provideWikiIds() {
		yield [ false ];
		yield [ 'someWiki' ];
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testNewBlobStore( $wikiId ) {
		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newBlobStore( $wikiId );
		$this->assertInstanceOf( BlobStore::class, $store );

		// This only works as we currently know this is a SqlBlobStore object
		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $wikiId, $wrapper->wikiId );
	}

	/**
	 * @dataProvider provideWikiIds
	 */
	public function testNewSqlBlobStore( $wikiId ) {
		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newSqlBlobStore( $wikiId );
		$this->assertInstanceOf( SqlBlobStore::class, $store );

		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $wikiId, $wrapper->wikiId );
	}

}
