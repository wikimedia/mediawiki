<?php

use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers \PurgeExpiredBlocks
 * @author Dreamy Jazz
 */
class PurgeExpiredBlocksTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return PurgeExpiredBlocks::class;
	}

	public function testExecute() {
		// Mock the DatabaseBlockStore to expect that ::purgeExpiredBlocks is called once.
		$mockDatabaseBlockStore = $this->createMock( DatabaseBlockStore::class );
		$mockDatabaseBlockStore->expects( $this->once() )
			->method( 'purgeExpiredBlocks' );
		$this->setService( 'DatabaseBlockStore', $mockDatabaseBlockStore );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Purging expired blocks/' );
	}
}
