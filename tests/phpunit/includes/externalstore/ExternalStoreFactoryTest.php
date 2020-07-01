<?php

use Wikimedia\Rdbms\LBFactory;

/**
 * @covers ExternalStoreFactory
 * @covers ExternalStoreAccess
 */
class ExternalStoreFactoryTest extends MediaWikiIntegrationTestCase {

	use MediaWikiCoversValidator;

	public function testExternalStoreFactory_noStores1() {
		$factory = new ExternalStoreFactory( [], [], 'test-id' );
		$this->expectException( ExternalStoreException::class );
		$factory->getStore( 'ForTesting' );
	}

	public function testExternalStoreFactory_noStores2() {
		$factory = new ExternalStoreFactory( [], [], 'test-id' );
		$this->expectException( ExternalStoreException::class );
		$factory->getStore( 'foo' );
	}

	public function provideStoreNames() {
		yield 'Same case as construction' => [ 'ForTesting' ];
		yield 'All lower case' => [ 'fortesting' ];
		yield 'All upper case' => [ 'FORTESTING' ];
		yield 'Mix of cases' => [ 'FOrTEsTInG' ];
	}

	/**
	 * @dataProvider provideStoreNames
	 */
	public function testExternalStoreFactory_someStore_protoMatch( $proto ) {
		$factory = new ExternalStoreFactory( [ 'ForTesting' ], [], 'test-id' );
		$store = $factory->getStore( $proto );
		$this->assertInstanceOf( ExternalStoreForTesting::class, $store );
	}

	/**
	 * @dataProvider provideStoreNames
	 */
	public function testExternalStoreFactory_someStore_noProtoMatch( $proto ) {
		$factory = new ExternalStoreFactory( [ 'SomeOtherClassName' ], [], 'test-id' );
		$this->expectException( ExternalStoreException::class );
		$factory->getStore( $proto );
	}

	/**
	 * @covers ExternalStoreFactory::getProtocols
	 * @covers ExternalStoreFactory::getWriteBaseUrls
	 * @covers ExternalStoreFactory::getStore
	 */
	public function testStoreFactoryBasic() {
		$active = [ 'memory', 'mwstore' ];
		$defaults = [ 'memory://cluster1', 'memory://cluster2', 'mwstore://memstore1' ];
		$esFactory = new ExternalStoreFactory( $active, $defaults, 'db-prefix' );
		$this->setMwGlobals( 'wgFileBackends', [
			[
				'name' => 'memstore1',
				'class' => 'MemoryFileBackend',
				'domain' => 'its-all-in-your-head',
				'readOnly' => 'reason is a lie',
				'lockManager' => 'nullLockManager'
			]
		] );

		$this->assertEquals( $active, $esFactory->getProtocols() );
		$this->assertEquals( $defaults, $esFactory->getWriteBaseUrls() );

		/** @var ExternalStoreMemory $store */
		$store = $esFactory->getStore( 'memory' );
		$this->assertInstanceOf( ExternalStoreMemory::class, $store );
		$this->assertFalse( $store->isReadOnly( 'cluster1' ), "Location is writable" );
		$this->assertFalse( $store->isReadOnly( 'cluster2' ), "Location is writable" );

		$mwStore = $esFactory->getStore( 'mwstore' );
		$this->assertTrue( $mwStore->isReadOnly( 'memstore1' ), "Location is read-only" );

		$lb = $this->getMockBuilder( \Wikimedia\Rdbms\LoadBalancer::class )
			->disableOriginalConstructor()->getMock();
		$lb->expects( $this->any() )->method( 'getReadOnlyReason' )->willReturn( 'Locked' );
		$lbFactory = $this->getMockBuilder( LBFactory::class )
			->disableOriginalConstructor()->getMock();
		$lbFactory->expects( $this->any() )->method( 'getExternalLB' )->willReturn( $lb );

		$this->setService( 'DBLoadBalancerFactory', $lbFactory );

		$active = [ 'db', 'mwstore' ];
		$defaults = [ 'db://clusterX' ];
		$esFactory = new ExternalStoreFactory( $active, $defaults, 'db-prefix' );
		$this->assertEquals( $active, $esFactory->getProtocols() );
		$this->assertEquals( $defaults, $esFactory->getWriteBaseUrls() );

		$store->clear();
	}

	/**
	 * @covers ExternalStoreFactory::getStoreForUrl
	 * @covers ExternalStoreFactory::getStoreLocationFromUrl
	 */
	public function testStoreFactoryReadWrite() {
		$active = [ 'memory' ]; // active store types
		$defaults = [ 'memory://cluster1', 'memory://cluster2' ];
		$esFactory = new ExternalStoreFactory( $active, $defaults, 'db-prefix' );
		$access = new ExternalStoreAccess( $esFactory );

		/** @var ExternalStoreMemory $storeLocal */
		$storeLocal = $esFactory->getStore( 'memory' );
		/** @var ExternalStoreMemory $storeOther */
		$storeOther = $esFactory->getStore( 'memory', [ 'domain' => 'other' ] );
		$this->assertInstanceOf( ExternalStoreMemory::class, $storeLocal );
		$this->assertInstanceOf( ExternalStoreMemory::class, $storeOther );

		$v1 = wfRandomString();
		$v2 = wfRandomString();
		$v3 = wfRandomString();

		$this->assertFalse( $storeLocal->fetchFromURL( 'memory://cluster1/1' ) );

		$url1 = 'memory://cluster1/1';
		$this->assertEquals(
			$url1,
			$esFactory->getStoreForUrl( 'memory://cluster1' )
				->store( $esFactory->getStoreLocationFromUrl( 'memory://cluster1' ), $v1 )
		);
		$this->assertEquals(
			$v1,
			$esFactory->getStoreForUrl( 'memory://cluster1/1' )
				->fetchFromURL( 'memory://cluster1/1' )
		);
		$this->assertEquals( $v1, $storeLocal->fetchFromURL( 'memory://cluster1/1' ) );

		$url2 = $access->insert( $v2 );
		$url3 = $access->insert( $v3, [ 'domain' => 'other' ] );
		$this->assertNotFalse( $url2 );
		$this->assertNotFalse( $url3 );
		// There is only one active store type
		$this->assertEquals( $v2, $storeLocal->fetchFromURL( $url2 ) );
		$this->assertEquals( $v3, $storeOther->fetchFromURL( $url3 ) );
		$this->assertFalse( $storeOther->fetchFromURL( $url2 ) );
		$this->assertFalse( $storeLocal->fetchFromURL( $url3 ) );

		$res = $access->fetchFromURLs( [ $url1, $url2, $url3 ] );
		$this->assertEquals( [ $url1 => $v1, $url2 => $v2, $url3 => false ], $res, "Local-only" );

		$storeLocal->clear();
		$storeOther->clear();
	}
}
