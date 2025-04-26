<?php

use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \ExternalStoreAccess
 */
class ExternalStoreAccessTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	public function testBasic() {
		$active = [ 'memory' ];
		$defaults = [ 'memory://cluster1', 'memory://cluster2' ];
		$esFactory = new ExternalStoreFactory( $active, $defaults, 'db-prefix' );
		$access = new ExternalStoreAccess( $esFactory );

		$this->assertFalse( $access->isReadOnly() );

		/** @var ExternalStoreMemory $store */
		$store = $esFactory->getStore( 'memory' );
		$this->assertInstanceOf( ExternalStoreMemory::class, $store );

		$location = $esFactory->getStoreLocationFromUrl( 'memory://cluster1' );
		$this->assertFalse( $store->isReadOnly( $location ) );
	}

	/**
	 * @covers \ExternalStoreAccess::isReadOnly
	 */
	public function testReadOnly() {
		/** @var  ExternalStoreMedium|MockObject $medium */
		$medium = $this->createMock( ExternalStoreMedium::class );

		$medium->method( 'isReadOnly' )->willReturn( true );

		/** @var  ExternalStoreFactory|MockObject $esFactory */
		$esFactory = $this->createMock( ExternalStoreFactory::class );

		$esFactory->method( 'getWriteBaseUrls' )->willReturn( [ 'test:' ] );
		$esFactory->method( 'getStoreForUrl' )->willReturn( $medium );
		$esFactory->method( 'getStoreLocationFromUrl' )->willReturn( 'dummy' );

		$access = new ExternalStoreAccess( $esFactory );
		$this->assertTrue( $access->isReadOnly() );

		$this->setService( 'ReadOnlyMode', $this->getDummyReadOnlyMode( 'Some absurd reason' ) );
		$this->expectException( ReadOnlyError::class );
		$access->insert( 'Lorem Ipsum' );
	}

	/**
	 * @covers \ExternalStoreAccess::fetchFromURL
	 * @covers \ExternalStoreAccess::fetchFromURLs
	 * @covers \ExternalStoreAccess::insert
	 */
	public function testReadWrite() {
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
