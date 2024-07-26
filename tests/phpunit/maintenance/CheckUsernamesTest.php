<?php

namespace MediaWiki\Tests\Maintenance;

use CheckUsernames;
use MediaWiki\MainConfigNames;
use TestUser;

/**
 * @covers \CheckUsernames
 * @group Database
 */
class CheckUsernamesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CheckUsernames::class;
	}

	public function testExecute() {
		$testUser1 = ( new TestUser( "TestUser 1234" ) )->getUserIdentity();
		$testUser2 = ( new TestUser( "TestUser 4321" ) )->getUserIdentity();
		// Set wgMaxNameChars to 2 so that both users fail to be valid.
		$this->overrideConfigValue( MainConfigNames::MaxNameChars, 2 );
		// Set the batch size to 1 to test multiple batches (as there are two users)
		$this->maintenance->setOption( 'batch-size', 1 );
		$this->maintenance->execute();
		$this->expectOutputString(
			sprintf( "Found: %6d: '%s'\n", $testUser1->getId(), $testUser1->getName() ) .
			sprintf( "Found: %6d: '%s'\n", $testUser2->getId(), $testUser2->getName() )
		);
	}
}
