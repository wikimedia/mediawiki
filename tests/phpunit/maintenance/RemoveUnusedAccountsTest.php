<?php

namespace MediaWiki\Tests\Maintenance;

use RemoveUnusedAccounts;

/**
 * @covers \RemoveUnusedAccounts
 * @group Database
 * @author Dreamy Jazz
 */
class RemoveUnusedAccountsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return RemoveUnusedAccounts::class;
	}

	public function testExecuteWhenIgnoreTouchedIsNotAnInteger() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex(
			'/Please put a valid positive integer on the --ignore-touched parameter/'
		);
		$this->maintenance->setOption( 'ignore-touched', 'abc' );
		$this->maintenance->execute();
	}

	/**
	 * Sets the value of the user_touched column in user table to the specified value.
	 *
	 * @param int[] $userIds
	 * @param string $userTouched Timestamp in any format accepted by {@link ConvertibleTimestamp}
	 * @return void
	 */
	private function setUserTouchedForUserIds( array $userIds, string $userTouched ) {
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_touched' => $this->getDb()->timestamp( $userTouched ) ] )
			->where( [ 'user_id' => $userIds ] )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Verifies that the given user IDs exist in the user table.
	 *
	 * @param int[] $userIds
	 * @return void
	 */
	private function verifyUserIdsExist( array $userIds ) {
		$this->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user' )
			->caller( __METHOD__ )
			->assertFieldValues( array_map( 'strval', $userIds ) );
	}

	public function testExecuteWhenAllUsersIgnoredForDryRun() {
		$testUser = $this->getMutableTestUser()->getUserIdentity();
		$sysop = $this->getTestSysop()->getUserIdentity();

		// Set user_touched so that the users are considered inactive by that requirement.
		$this->setUserTouchedForUserIds(
			[ $sysop->getId(), $testUser->getId() ],
			'20240506070809'
		);

		$this->maintenance->setOption( 'ignore-groups', 'user' );
		$this->maintenance->execute();

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Checking for unused user accounts...', $actualOutput );
		$this->assertStringContainsString( '...found 0', $actualOutput );
		$this->assertStringNotContainsString(
			'Run the script again with --delete to remove them from the database', $actualOutput
		);
		$this->assertStringNotContainsString( 'Deleting unused accounts', $actualOutput );
	}

	public function testExecuteWhenSomeUsersIgnoredForDryRun() {
		// Get some test users, one of which should be ignored.
		$sysop = $this->getTestSysop()->getUserIdentity();
		$testUser1 = $this->getMutableTestUser()->getUser();
		$testUser2 = $this->getMutableTestUser( [ 'bot' ] )->getUserIdentity();

		// Perform an edit using the first test user, so that it's not defined as inactive.
		$this->editPage(
			$this->getNonexistingTestPage(), 'Testingabc', '', NS_MAIN, $testUser1
		);

		// Set user_touched for all users to be old enough for the account to be considered inactive.
		$this->setUserTouchedForUserIds(
			[ $sysop->getId(), $testUser1->getId(), $testUser2->getId() ],
			'20240506070809'
		);

		// Verify that the user table is populated as we expect it, so that our later assertions can check no
		// change was made.
		$this->verifyUserIdsExist( [ $sysop->getId(), $testUser1->getId(), $testUser2->getId() ] );

		// Run the script while ignoring users in the sysop group
		$this->maintenance->setOption( 'ignore-groups', 'sysop' );
		$this->maintenance->execute();

		// Verify that only the second test user is marked as going to be deleted, but that nothing was deleted
		// as this is a dry run.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Checking for unused user accounts...', $actualOutput );
		$this->assertStringContainsString( '...found 1', $actualOutput );
		$this->assertStringContainsString(
			'Run the script again with --delete to remove them from the database', $actualOutput
		);
		$this->assertStringNotContainsString( 'Deleting unused accounts', $actualOutput );

		$this->verifyUserIdsExist( [ $sysop->getId(), $testUser1->getId(), $testUser2->getId() ] );
	}

	public function testExecuteWhenSomeUsersHaveNoActorIdForDryRun() {
		// Get some test users, one of which should be ignored.
		$sysop = $this->getTestSysop()->getUserIdentity();

		// Set user_touched for all users to be old enough for the account to be considered inactive.
		$this->setUserTouchedForUserIds( [ $sysop->getId() ], '20240506070809' );

		// Verify that the user table is populated as we expect it, so that our later assertions can check no
		// change was made.
		$this->verifyUserIdsExist( [ $sysop->getId() ] );

		// Drop the actor row for the sysop so that we can test when the user has no actor ID.
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'actor' )
			->where( [ 'actor_user' => $sysop->getId() ] )
			->caller( __METHOD__ )
			->execute();

		// Verify that the account is considered inactive if they have no actor ID.
		$this->maintenance->execute();
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Checking for unused user accounts...', $actualOutput );
		$this->assertStringContainsString( '...found 1', $actualOutput );
		$this->assertStringContainsString(
			'Run the script again with --delete to remove them from the database', $actualOutput
		);
		$this->assertStringNotContainsString( 'Deleting unused accounts', $actualOutput );

		$this->verifyUserIdsExist( [ $sysop->getId() ] );
	}

	public function testExecuteWhenSomeUsersIgnoredForDelete() {
		// Get some test users, one of which should be ignored.
		$sysop = $this->getTestSysop()->getUser();
		$testUser1 = $this->getMutableTestUser( [ 'bot' ] )->getUser();
		$testUser2 = $this->getMutableTestUser( [ 'bot' ] )->getUser();

		// Perform an edit using the first test user, so that it's not defined as inactive.
		$this->editPage(
			$this->getNonexistingTestPage(), 'Testingabc', '', NS_MAIN, $testUser1
		);

		// Set some user options for the second test user, to test that they get purged.
		$optionsManager = $this->getServiceContainer()->getUserOptionsManager();
		$optionsManager->setOption( $testUser2, 'disablemail', 1 );
		$optionsManager->saveOptions( $testUser2 );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'user_properties' )
			->where( [ 'up_user' => $testUser2->getId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 1 );

		// Set user_touched for all users to be old enough for the account to be considered inactive.
		$this->setUserTouchedForUserIds(
			[ $sysop->getId(), $testUser1->getId(), $testUser2->getId() ],
			'20240506070809'
		);

		// Verify that the user table is populated as we expect it, so that our later assertions can check that
		// deletions were performed.
		$this->verifyUserIdsExist( [ $sysop->getId(), $testUser1->getId(), $testUser2->getId() ] );

		// Run the script while ignoring users in the sysop group
		$this->maintenance->setOption( 'ignore-groups', 'sysop' );
		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->execute();

		// Verify that the second account is found to be inactive and then deleted.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( 'Checking for unused user accounts...', $actualOutput );
		$this->assertStringContainsString( '...found 1', $actualOutput );
		$this->assertStringNotContainsString(
			'Run the script again with --delete to remove them from the database', $actualOutput
		);
		$this->assertStringContainsString( 'Deleting unused accounts', $actualOutput );

		// Verify that the second test user has been deleted, but the other ones have not
		$this->verifyUserIdsExist( [ $sysop->getId(), $testUser1->getId() ] );
		$this->newSelectQueryBuilder()
			->select( [ 'actor_id' ] )
			->from( 'actor' )
			->caller( __METHOD__ )
			->assertFieldValues( array_map( 'strval', [ $sysop->getActorId(), $testUser1->getActorId() ] ) );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'user_properties' )
			->where( [ 'up_user' => $testUser2->getId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'logging' )
			->where( [ 'log_actor' => $testUser2->getActorId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'user_groups' )
			->where( [ 'ug_user' => $testUser2->getId() ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
	}
}
