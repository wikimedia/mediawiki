<?php

use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers \RebuildMessages
 * @author Dreamy Jazz
 */
class RebuildMessagesTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return RebuildMessages::class;
	}

	public function testExecute() {
		// Mock the MessageCache to expect that ::clear is called once by the maintenance script
		$mockMessageCache = $this->createMock( MessageCache::class );
		$mockMessageCache->expects( $this->once() )
			->method( 'clear' );
		$this->setService( 'MessageCache', $mockMessageCache );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Purging message cache.*Done/' );
	}
}
