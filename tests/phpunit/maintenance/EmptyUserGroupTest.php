<?php

namespace MediaWiki\Tests\Maintenance;

use EmptyUserGroup;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
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

		// Verify that no log entries were created, as this was not configured.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->where( [ 'log_type' => 'rights' ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
	}

	public function testExecuteForGroupWithUsersWhenCreateLogSpecified() {
		$sysopUsers = $this->createTestUsersWithGroup( 'sysop', 3 );

		// Run the maintenance script to empty the sysop group and logs being created
		$this->maintenance->setArg( 0, 'sysop' );
		$this->maintenance->setOption( 'create-log', 1 );
		$this->maintenance->setOption( 'log-reason', 'Test log reason' );
		$this->maintenance->execute();
		$this->expectOutputString(
			"Removing users from sysop...\n" .
			"  ...done! Removed 3 users in total.\n"
		);

		// Verify all users in the 'sysop' group actually had their group removed.
		foreach ( $sysopUsers as $user ) {
			$this->assertNotContains(
				'sysop',
				$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $user )
			);
		}

		// Verify that log entries were created and that no recentchanges entries were created.
		$this->newSelectQueryBuilder()
			->select( 'log_title' )
			->from( 'logging' )
			->join( 'actor', null, [ 'log_actor=actor_id' ] )
			->join( 'comment', null, [ 'log_comment_id=comment_id' ] )
			->where( [
				'log_type' => 'rights',
				'log_action' => 'rights',
				'actor_name' => User::MAINTENANCE_SCRIPT_USER,
				'log_namespace' => NS_USER,
				'log_params' => LogEntryBase::makeParamBlob( [
					'4::oldgroups' => [ 'sysop' ],
					'5::newgroups' => [],
				] ),
				'comment_text' => 'Test log reason',
			] )
			->caller( __METHOD__ )
			->assertFieldValues(
				array_map( static function ( UserIdentity $user ) {
					return Title::newFromText( $user->getName(), NS_USER )->getDBkey();
				}, $sysopUsers )
			);

		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'recentchanges' )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
	}
}
