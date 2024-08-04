<?php

use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \ResetUserEmail
 * @group Database
 * @author Dreamy Jazz
 */
class ResetUserEmailTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return ResetUserEmail::class;
	}

	private function commonTestEmailReset( $userArg, $options, $userName, $oldEmail ) {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		// Execute the maintenance script
		$this->maintenance->loadWithArgv( [ $userArg, 'new@mediawiki.test' ] );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();

		// Check that the email address was changed and invalidated
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$testUserAfterExecution = $userFactory->newFromName( $userName );
		$this->assertNotEquals( $oldEmail, $testUserAfterExecution->getEmail() );
		$this->assertSame( 'new@mediawiki.test', $testUserAfterExecution->getEmail() );
		$this->assertSame(
			'20240506070809',
			$testUserAfterExecution->getEmailAuthenticationTimestamp()
		);

		// Check that the script returns the right output
		$this->expectOutputRegex( '/Done!/' );
	}

	public function testEmailResetWithNoPasswordResetWhenProvidingName() {
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		// Test providing the maintenance script with a username.
		$this->commonTestEmailReset(
			$testUserBeforeExecution->getName(), [ 'no-reset-password' => 1 ], $testUserBeforeExecution->getName(),
			$oldEmail
		);
	}

	public function testEmailResetWithNoPasswordResetWhenProvidingId() {
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		// Test providing the maintenance script with a user ID.
		$this->commonTestEmailReset(
			"#" . $testUserBeforeExecution->getId(), [ 'no-reset-password' => 1 ],
			$testUserBeforeExecution->getName(), $oldEmail
		);
	}

	public function testEmailResetOnInvalidNewEmail() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/testemail.*is not valid/" );
		// Execute the maintenance script
		$this->maintenance->setArg( 0, $this->getTestUser()->getUserIdentity()->getName() );
		$this->maintenance->setArg( 1, 'testemail' );
		$this->maintenance->execute();
	}

	public function testEmailResetOnInvalidUsername() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( "/Non-existent-test-user.*does not exist/" );
		// Execute the maintenance script
		$this->maintenance->setArg( 0, 'Non-existent-test-user' );
		$this->maintenance->setArg( 1, 'new@mediawiki.test' );
		$this->maintenance->execute();
	}
}
