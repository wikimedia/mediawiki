<?php

namespace MediaWiki\Tests\Maintenance;

use RenameRestrictions;

/**
 * @covers \RenameRestrictions
 * @group Database
 * @author Dreamy Jazz
 */
class RenameRestrictionsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RenameRestrictions::class;
	}

	public function testExecute() {
		// Create an existing test page that is protected with the wrong 'autoconfirmedb' protection level
		$existingTestPage = $this->getExistingTestPage();
		$cascade = false;
		$existingTestPage->doUpdateRestrictions(
			[ 'edit' => 'autoconfirmedb' ], [ 'edit' => 'infinity' ], $cascade, 'Test',
			$this->getTestUser()->getUserIdentity()
		);
		// Salt an non-existing page with the wrong 'autoconfirmedb' protection level
		$nonExistingTestPage = $this->getNonexistingTestPage();
		$nonExistingTestPage->doUpdateRestrictions(
			[ 'create' => 'autoconfirmedb' ], [ 'create' => 'infinity' ], $cascade, 'Test',
			$this->getTestUser()->getUserIdentity()
		);
		// Run the maintenance script to rename 'autoconfirmedb' to 'autoconfirmed'
		$this->maintenance->setArg( 'oldlevel', 'autoconfirmedb' );
		$this->maintenance->setArg( 'newlevel', 'autoconfirmed' );
		$this->maintenance->execute();
		// Verify that the protection restriction has been appropriately renamed.
		$restrictionStore = $this->getServiceContainer()->getRestrictionStore();
		$this->assertArrayEquals(
			[ 'autoconfirmed' ],
			$restrictionStore->getRestrictions( $existingTestPage, 'edit' )
		);
		$this->assertArrayEquals(
			[ 'autoconfirmed' ],
			$restrictionStore->getRestrictions( $nonExistingTestPage, 'create' )
		);
	}
}
