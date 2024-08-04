<?php

use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use MediaWiki\User\UserGroupManager;

/**
 * @covers \PurgeExpiredUserrights
 * @author Dreamy Jazz
 */
class PurgeExpiredUserrightsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return PurgeExpiredUserrights::class;
	}

	/** @dataProvider provideExecute */
	public function testExecute( $mockPurgeExpiredReturnValue, $expectedOutputRegex ) {
		// Mock the UserGroupManager to expect that ::purgeExpired is called once
		// and also then return a fake value.
		$mockUserGroupManager = $this->createMock( UserGroupManager::class );
		$mockUserGroupManager->expects( $this->once() )
			->method( 'purgeExpired' )
			->willReturn( $mockPurgeExpiredReturnValue );
		$this->setService( 'UserGroupManager', $mockUserGroupManager );
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'::purgeExpired returns false' => [ false, '/Purging expired user rights[\s\S]*Purging failed/' ],
			'::purgeExpired returns 0' => [ 0, '/Purging expired user rights[\s\S]*0 rows purged/' ],
			'::purgeExpired returns 3' => [ 3, '/Purging expired user rights[\s\S]*3 rows purged/' ],
		];
	}
}
