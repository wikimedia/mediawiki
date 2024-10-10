<?php

namespace MediaWiki\Tests\Maintenance;

use CreateAndPromote;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\SiteStats\SiteStats;
use MediaWiki\User\User;

/**
 * @covers \CreateAndPromote
 * @group Database
 * @author Dreamy Jazz
 */
class CreateAndPromoteTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CreateAndPromote::class;
	}

	/** @dataProvider provideExecuteForExistingAccount */
	public function testExecuteForExistingAccount(
		$options, $expectedGroupsAfterCall, $expectedOutputRegex, $shouldCreateLogEntry
	) {
		$testUser = $this->getMutableTestUser()->getUser();
		// Set the user as the test user and use 'force' because we are not creating a user.
		$this->maintenance->setArg( 'username', $testUser );
		$this->maintenance->setOption( 'force', true );
		// Add the options from $options
		foreach ( $options as $option => $value ) {
			$this->maintenance->setOption( $option, $value );
		}
		$this->maintenance->execute();
		// Verify that the user is now in the expected groups
		$this->assertArrayEquals(
			$expectedGroupsAfterCall,
			$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $testUser )
		);
		$this->expectOutputRegex( $expectedOutputRegex );
		// Verify that the log entry was created, if that is expected
		$queryBuilder = $this->newSelectQueryBuilder()
			->field( 'COUNT(*)' )
			->from( 'logging' )
			->where( [
				'log_actor' => $this->getServiceContainer()->getActorStore()
					->findActorIdByName( User::MAINTENANCE_SCRIPT_USER, $this->getDb() ),
				'log_title' => $testUser->getUserPage()->getDBkey(),
				'log_namespace' => NS_USER,
				'log_comment_id' => $this->getServiceContainer()->getCommentStore()
					->createComment( $this->getDb(), $options['reason'] ?? '' )->id,
			] );
		$this->assertSame( (int)$shouldCreateLogEntry, (int)$queryBuilder->fetchField() );
	}

	public static function provideExecuteForExistingAccount() {
		return [
			'Assigning no rights' => [ [], [], "/Account exists and nothing to do.\n/", false ],
			'Assigning sysop with reason provided' => [
				[ 'sysop' => 1, 'reason' => 'testing' ], [ 'sysop' ], "/Promoting .* into sysop...\ndone/", true
			],
			'Assigning bot' => [ [ 'bot' => 1 ], [ 'bot' ], "/Promoting .* into bot...\ndone/", true ],
			'Assigning suppress, bureaucrat, and interface-admin' => [
				[ 'custom-groups' => 'suppress', 'bureaucrat' => 1, 'interface-admin' => 1 ],
				[ 'suppress', 'bureaucrat', 'interface-admin' ],
				"/Promoting .* into bureaucrat, interface-admin, suppress...\ndone/",
				true,
			],
			'Assigning unrecognised group' => [
				[ 'custom-groups' => 'abctesting' ], [], "/Account exists and nothing to do.\n/", false,
			],
		];
	}

	public function testExecuteToSetPasswordForExistingUser() {
		$password = PasswordFactory::generateRandomPasswordString( 128 );
		$testUser = $this->getMutableTestUser()->getUser();
		// Set the username as our existing test user and set the password option.
		$this->maintenance->setArg( 'username', $testUser );
		$this->maintenance->setArg( 'password', $password );
		$this->maintenance->setOption( 'force', true );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Password set/' );
		// Check that the password for the $testUser matches the password we set
		$actualPasswordHash = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_name' => $testUser->getName() ] )
			->fetchField();
		$this->assertTrue(
			$this->getServiceContainer()->getPasswordFactory()
				->newFromCiphertext( $actualPasswordHash )->verify( $password )
		);
	}

	public function testExecuteForInvalidUsername() {
		// Call the maintenance script with a username that is more than wgMaxNameChars, and so shouldn't be valid.
		$this->overrideConfigValue( MainConfigNames::MaxNameChars, 2 );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/invalid username/' );
		$this->maintenance->setArg( 'username', 'testing-username-1234' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenUserExistsButForceOptionNotProvided() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Account exists.*--force/' );
		$this->maintenance->setArg( 'username', $this->getTestUser()->getUserIdentity()->getName() );
		$this->maintenance->execute();
	}

	public function testExecuteWhenUserDoesNotExistAndNoPasswordSpecified() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Argument <password> required/' );
		$this->maintenance->setName( 'createAndPromote.php' );
		$this->maintenance->setArg( 'username', 'NonExistingTestUser1234' );
		$this->maintenance->execute();
	}

	public function testExecuteForNewAccountButPasswordDoesNotMeetRequirements() {
		$this->maintenance->setArg( 'username', 'NewTestUser1234' );
		// Use a very commonly used password "abc" and check that it rejects this
		$this->maintenance->setArg( 'password', 'abc' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/password entered is in a list of very commonly used passwords/' );
		$this->maintenance->execute();
	}

	public function testExecuteForNewAccountWhenReadOnly() {
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'test' );
		$this->maintenance->setArg( 'username', 'NewTestUser1234' );
		$this->maintenance->setArg( 'password', PasswordFactory::generateRandomPasswordString( 128 ) );
		$this->expectCallToFatalError();
		// Assert that the "readonlytext" message is displayed.
		$this->expectOutputRegex( '/database is currently locked/' );
		$this->maintenance->execute();
	}

	public function testExecuteForNewAccount() {
		$this->assertSame( 0, SiteStats::users() );
		$password = PasswordFactory::generateRandomPasswordString( 128 );
		// Run the maintenance script
		$this->maintenance->setArg( 'username', 'NewTestUser1234' );
		$this->maintenance->setArg( 'password', $password );
		$this->maintenance->setOption( 'sysop', 1 );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Creating and promoting User:NewTestUser1234[\s\S]*done/' );
		// Check that the new user exists and that the password matches
		$actualPasswordHash = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_name' => 'NewTestUser1234' ] )
			->fetchField();
		$this->assertTrue(
			$this->getServiceContainer()->getPasswordFactory()
				->newFromCiphertext( $actualPasswordHash )->verify( $password )
		);
		// Check that the number of users has increased to 2, one for the new user and the other for the maintenance
		// script user.
		$this->assertSame( 2, SiteStats::users() );
	}
}
