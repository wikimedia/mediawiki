<?php

namespace MediaWiki\Tests\Maintenance;

use EmptyUserGroup;
use MediaWiki\User\UserIdentity;

/**
 * @covers \EmptyUserGroup
 * @group Database
 * @author Dreamy Jazz
 */
class EmptyUserGroupTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return EmptyUserGroup::class;
	}

	public function testExecuteForEmptyGroup() {
		$this->maintenance->setArg( 0, 'sysop' );
		$this->maintenance->execute();
		$this->expectOutputString(
			"Removing users from sysop...\n" .
			"  ...nothing to do, group was empty.\n"
		);
	}

	/**
	 * Creates several testing users, and adds them to the specified $group.
	 *
	 * @param string $group The group the users should be in
	 * @param int $numberOfUsers The number of users to create
	 * @return UserIdentity[] The users that were created
	 */
	private function createTestUsersWithGroup( string $group, int $numberOfUsers ): array {
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();
		$returnArray = [];
		for ( $i = 0; $i < $numberOfUsers; $i++ ) {
			$user = $this->getMutableTestUser()->getUserIdentity();
			$userGroupManager->addUserToGroup( $user, $group );
			$returnArray[] = $user;
		}
		return $returnArray;
	}

	public function testExecuteForGroupWithUsers() {
		// Create 5 test users with the 'sysop' group
		$sysopUsers = $this->createTestUsersWithGroup( 'sysop', 5 );
		// Create a test user with the 'bot' group to verify that it will not loose it's group
		$botUsers = $this->createTestUsersWithGroup( 'bot', 1 );
		// Run the maintenance script to empty the sysop group, with a batch size of 2.
		$this->maintenance->setArg( 0, 'sysop' );
		$this->maintenance->setOption( 'batch-size', 2 );
		$this->maintenance->execute();
		$this->expectOutputString(
			"Removing users from sysop...\n" .
			"  ...done! Removed 5 users in total.\n"
		);
		// Verify all users in the 'sysop' group actually had their group removed.
		foreach ( $sysopUsers as $user ) {
			$this->assertNotContains(
				'sysop',
				$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $user )
			);
		}
		// Verify all users in the 'bot' group still have their group
		foreach ( $botUsers as $user ) {
			$this->assertContains(
				'bot',
				$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $user )
			);
		}
	}
}
