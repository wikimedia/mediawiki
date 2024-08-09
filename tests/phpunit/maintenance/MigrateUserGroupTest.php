<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\User\UserIdentity;
use MigrateUserGroup;

/**
 * @covers \MigrateUserGroup
 * @group Database
 * @author Dreamy Jazz
 */
class MigrateUserGroupTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return MigrateUserGroup::class;
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

	public function testExecute() {
		// Test moving 5 users with the "bot" group to the "sysop" group, adding one already into the "sysop" group
		// to test that functionality.
		$testUsers = $this->createTestUsersWithGroup( 'bot', 3 );
		$this->maintenance->setArg( 'oldgroup', 'bot' );
		$this->maintenance->setArg( 'newgroup', 'sysop' );
		$this->maintenance->execute();
		// Verify that the move correctly occurred.
		foreach ( $testUsers as $testUser ) {
			$testUserGroups = $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $testUser );
			$this->assertContains( 'sysop', $testUserGroups );
			$this->assertNotContains( 'bot', $testUserGroups );
		}
		$this->expectOutputRegex( "/Done! 3 users in group 'bot' are now in 'sysop' instead.\n/" );
	}

	public function testExecuteWithOneUserInNewGroup() {
		// Test moving 5 users with the "bot" group to the "sysop" group, adding one already into the "sysop" group
		// to test that functionality.
		$testUsers = $this->createTestUsersWithGroup( 'bot', 5 );
		$this->getServiceContainer()->getUserGroupManager()
			->addUserToGroup( $testUsers[1], 'sysop' );
		$this->maintenance->setArg( 'oldgroup', 'bot' );
		$this->maintenance->setArg( 'newgroup', 'sysop' );
		$this->maintenance->execute();
		// Verify that the move correctly occurred.
		foreach ( $testUsers as $testUser ) {
			$testUserGroups = $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $testUser );
			$this->assertContains( 'sysop', $testUserGroups );
			$this->assertNotContains( 'bot', $testUserGroups );
		}
		$this->expectOutputRegex( "/Done! 5 users in group 'bot' are now in 'sysop' instead.\n/" );
	}
}
