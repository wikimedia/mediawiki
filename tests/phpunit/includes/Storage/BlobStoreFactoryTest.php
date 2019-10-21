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

	public function provideDbDomains() {
		yield [ false ];
		yield [ 'someWiki' ];
	}

	/**
	 * @dataProvider provideDbDomains
	 */
	public function testNewBlobStore( $dbDomain ) {
		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newBlobStore( $dbDomain );
		$this->assertInstanceOf( BlobStore::class, $store );

		// This only works as we currently know this is a SqlBlobStore object
		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $dbDomain, $wrapper->dbDomain );
	}

	/**
	 * @dataProvider provideDbDomains
	 */
	public function testNewSqlBlobStore( $dbDomain ) {
		$factory = MediaWikiServices::getInstance()->getBlobStoreFactory();
		$store = $factory->newSqlBlobStore( $dbDomain );
		$this->assertInstanceOf( SqlBlobStore::class, $store );

		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $dbDomain, $wrapper->dbDomain );
	}

}
