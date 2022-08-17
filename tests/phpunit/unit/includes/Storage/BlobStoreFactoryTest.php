<?php

namespace MediaWiki\Tests\Storage\Unit;

use ExternalStoreAccess;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiUnitTestCase;
use WANObjectCache;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Storage\BlobStoreFactory
 */
class BlobStoreFactoryTest extends MediaWikiUnitTestCase {

	private function getBlobStoreFactory() {
		$lbFactory = $this->createMock( ILBFactory::class );
		$lbFactory->method( 'getMainLB' )->willReturn( $this->createMock( ILoadBalancer::class ) );
		$options = [
			MainConfigNames::CompressRevisions => false,
			MainConfigNames::DefaultExternalStore => false,
			MainConfigNames::LegacyEncoding => false,
			MainConfigNames::RevisionCacheExpiry => 86400 * 7,
		];
		return new BlobStoreFactory(
			$lbFactory,
			$this->createMock( ExternalStoreAccess::class ),
			$this->createMock( WANObjectCache::class ),
			new ServiceOptions( BlobStoreFactory::CONSTRUCTOR_OPTIONS, $options )
		);
	}

	public function provideDbDomains() {
		yield [ false ];
		yield [ 'someWiki' ];
	}

	/**
	 * @dataProvider provideDbDomains
	 */
	public function testNewBlobStore( $dbDomain ) {
		$factory = $this->getBlobStoreFactory();
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
		$factory = $this->getBlobStoreFactory();
		$store = $factory->newSqlBlobStore( $dbDomain );
		$this->assertInstanceOf( SqlBlobStore::class, $store );

		$wrapper = TestingAccessWrapper::newFromObject( $store );
		$this->assertEquals( $dbDomain, $wrapper->dbDomain );
	}

}
