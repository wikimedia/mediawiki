<?php
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers DeleteUserEmail
 * @group Database
 */
class DeleteUserEmailTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return DeleteUserEmail::class;
	}

	public function testEmailDeletion() {
		// Target an existing user with an email attached
		$userName = $this->getTestSysop()->getUserIdentity()->getName();
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$testUserBeforeExecution = $userFactory->newFromName( $userName );
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );

		// Execute the maintance script
		$this->maintenance->loadWithArgv( [ $userName ] );
		$this->maintenance->execute();

		// Check that the email address was changed and invalidated
		$testUserAfterExecution = $userFactory->newFromName( $userName );
		$this->assertNotEquals( $oldEmail, $testUserAfterExecution->getEmail() );
		$this->assertSame( '', $testUserAfterExecution->getEmail() );
		$this->assertNull( $testUserAfterExecution->getEmailAuthenticationTimestamp() );

		// Check that the script returns the right output
		$this->expectOutputRegex( '/Done!/' );
	}
}
