<?php

namespace MediaWiki\Tests\Maintenance;

use ChangePassword;
use MediaWiki\Maintenance\MaintenanceFatalError;
use MediaWiki\Password\PasswordFactory;

/**
 * @covers \ChangePassword
 * @group Database
 * @author Dreamy Jazz
 */
class ChangePasswordTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return ChangePassword::class;
	}

	public function testExecuteWithoutProvidedUserOrUserId() {
		$this->maintenance->setOption( 'password', 'abc' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/A "user" or "userid" must be set to change the password for/' );
		$this->maintenance->execute();
	}

	public function testExecuteForTooShortPassword() {
		$testUser = $this->getTestUser()->getUserIdentity();
		// Get the current password for the user, and assert later that it does not change
		$oldPasswordHash = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUser->getId() ] )
			->fetchField();
		// Use a password which is too short and common, so will fail to be set
		$this->maintenance->setOption( 'password', 'abc' );
		$this->maintenance->setOption( 'userid', $testUser->getId() );
		// Run the maintenance script in a try block, because we want to do assertions that can only be run after
		// we have called execute.
		$threwFatalError = false;
		try {
			$this->maintenance->execute();
		} catch ( MaintenanceFatalError ) {
			$threwFatalError = true;
		}
		$this->assertTrue( $threwFatalError );
		// Check that the password hash has not changed.
		$this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUser->getId() ] )
			->assertFieldValue( $oldPasswordHash );
		$this->expectOutputRegex( '/Error: Passwords must be at least.*characters/' );
	}

	public function testExecute() {
		$testUser = $this->getTestUser()->getUserIdentity();
		$newPasswordPlaintext = PasswordFactory::generateRandomPasswordString();
		$this->maintenance->setOption( 'password', $newPasswordPlaintext );
		$this->maintenance->setOption( 'userid', $testUser->getId() );
		$this->maintenance->execute();
		// Check that the password hash has not changed.
		$newPasswordHash = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUser->getId() ] )
			->fetchField();
		$password = $this->getServiceContainer()->getPasswordFactory()->newFromCiphertext( $newPasswordHash );
		$this->assertTrue( $password->verify( $newPasswordPlaintext ) );
	}
}
