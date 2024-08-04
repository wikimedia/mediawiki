<?php

use MediaWiki\ResourceLoader\MessageBlobStore;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers \PurgeMessageBlobStore
 * @author Dreamy Jazz
 */
class PurgeMessageBlobStoreTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return PurgeMessageBlobStore::class;
	}

	public function testExecute() {
		// Mock MessageBlobStore::clear, expecting that it be called once.
		// Testing that calling the method actually clears the cache is done by MessageBlobStoreTest.
		$mockMessageBlobStore = $this->createMock( MessageBlobStore::class );
		$mockMessageBlobStore->expects( $this->once() )
			->method( 'clear' );
		$resourceLoader = $this->createMock( ResourceLoader::class );
		$resourceLoader->method( 'getMessageBlobStore' )
			->willReturn( $mockMessageBlobStore );
		$this->setService( 'ResourceLoader', $resourceLoader );
		$this->maintenance->execute();
	}
}
