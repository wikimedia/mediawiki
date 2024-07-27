<?php
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers \DeleteUserEmail
 * @group Database
 */
class DeleteUserEmailTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return DeleteUserEmail::class;
	}

	private function commonTestEmailDeletion( $userArg, $userName, $oldEmail ) {
		// Execute the maintenance script
		$this->maintenance->loadWithArgv( [ $userArg ] );
		$this->maintenance->execute();

		// Check that the email address was changed and invalidated
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$testUserAfterExecution = $userFactory->newFromName( $userName );
		$this->assertNotEquals( $oldEmail, $testUserAfterExecution->getEmail() );
		$this->assertSame( '', $testUserAfterExecution->getEmail() );
		$this->assertNull( $testUserAfterExecution->getEmailAuthenticationTimestamp() );

		// Check that the script returns the right output
		$this->expectOutputRegex( '/Done!/' );
	}

	public function testEmailDeletionWhenProvidingName() {
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		// Test providing the maintenance script with a username.
		$this->commonTestEmailDeletion(
			$testUserBeforeExecution->getName(), $testUserBeforeExecution->getName(), $oldEmail
		);
	}

	public function testEmailDeletionWhenProvidingId() {
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		// Test providing the maintenance script with a user ID.
		$this->commonTestEmailDeletion(
			"#" . $testUserBeforeExecution->getId(), $testUserBeforeExecution->getName(), $oldEmail
		);
	}
}
