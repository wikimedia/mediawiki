<?php

use MediaWiki\MainConfigNames;
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
		$passwordHashBeforeExecution = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserBeforeExecution->getId() ] )
			->fetchField();
		// Test providing the maintenance script with a user ID.
		$this->commonTestEmailReset(
			"#" . $testUserBeforeExecution->getId(), [ 'no-reset-password' => 1 ],
			$testUserBeforeExecution->getName(), $oldEmail
		);
		// Check that the password hash for the user has not changed
		$passwordAfterExecution = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserBeforeExecution->getId() ] )
			->fetchField();
		$this->assertSame( $passwordHashBeforeExecution, $passwordAfterExecution );
	}

	public function testEmailReset() {
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		$passwordHashBeforeExecution = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserBeforeExecution->getId() ] )
			->fetchField();
		// Test providing the maintenance script with a user ID.
		$this->commonTestEmailReset(
			"#" . $testUserBeforeExecution->getId(), [],
			$testUserBeforeExecution->getName(), $oldEmail
		);
		// Check that the password hash for the user has changed
		$passwordAfterExecution = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserBeforeExecution->getId() ] )
			->fetchField();
		$this->assertNotSame( $passwordHashBeforeExecution, $passwordAfterExecution );
	}

	public function testEmailResetWithNoPasswordResetAndEmailPasswordOnFailure() {
		$this->overrideConfigValue( MainConfigNames::EnableEmail, true );
		// Abort all password reset submissions for the test
		$this->setTemporaryHook( 'SpecialPasswordResetOnSubmit', static function ( $users, $data, &$error ) {
			$error = 'test';
			return false;
		} );
		// Target an existing user with an email attached
		$testUserBeforeExecution = $this->getTestSysop()->getUser();
		$oldEmail = $testUserBeforeExecution->getEmail();
		$this->assertNotNull( $oldEmail );
		// Test providing the maintenance script with a username.
		$this->commonTestEmailReset(
			$testUserBeforeExecution->getName(), [ 'no-reset-password' => 1, 'email-password' => 1 ],
			$testUserBeforeExecution->getName(),
			$oldEmail
		);
		$this->expectOutputRegex( "/Email couldn't be sent for[\s\S]*Done/" );
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

	public function testBatchResetFromFile() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user1 = $this->getMutableTestUser()->getUser();
		$user2 = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		$content = $user1->getName() . "\tnew1@mediawiki.test\n"
			. $user2->getName() . "\tnew2@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		// Verify directly from DB to avoid process-level user cache
		$row1 = $this->newSelectQueryBuilder()
			->select( [ 'user_email', 'user_email_authenticated' ] )
			->from( 'user' )
			->where( [ 'user_id' => $user1->getId() ] )
			->fetchRow();
		$this->assertSame( 'new1@mediawiki.test', $row1->user_email );
		$this->assertSame( '20240506070809', wfTimestamp( TS_MW, $row1->user_email_authenticated ) );

		$row2 = $this->newSelectQueryBuilder()
			->select( [ 'user_email', 'user_email_authenticated' ] )
			->from( 'user' )
			->where( [ 'user_id' => $user2->getId() ] )
			->fetchRow();
		$this->assertSame( 'new2@mediawiki.test', $row2->user_email );
		$this->assertSame( '20240506070809', wfTimestamp( TS_MW, $row2->user_email_authenticated ) );

		$this->expectOutputRegex( '/Batch complete: 2 succeeded, 0 failed/' );
	}

	public function testBatchResetSkipsCommentsAndEmptyLines() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		$content = "# This is a comment\n"
			. "\n"
			. $user->getName() . "\tnew@mediawiki.test\n"
			. "# Another comment\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		$emailAfter = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->fetchField();
		$this->assertSame( 'new@mediawiki.test', $emailAfter );

		$this->expectOutputRegex( '/Batch complete: 1 succeeded, 0 failed out of 1/' );
	}

	public function testBatchResetContinuesOnInvalidUser() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$validUser = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		$content = "Non-existent-user-12345\ttest@mediawiki.test\n"
			. $validUser->getName() . "\tnew@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		$emailAfter = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $validUser->getId() ] )
			->fetchField();
		$this->assertSame( 'new@mediawiki.test', $emailAfter );

		$this->expectOutputRegex( '/Batch complete: 1 succeeded, 1 failed out of 2/' );
	}

	public function testBatchResetContinuesOnInvalidEmail() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user1 = $this->getMutableTestUser()->getUser();
		$user2 = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		$content = $user1->getName() . "\tnot-an-email\n"
			. $user2->getName() . "\tvalid@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		// user1 should be unchanged (invalid email)
		$email1 = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $user1->getId() ] )
			->fetchField();
		$this->assertNotSame( 'not-an-email', $email1 );

		// user2 should be changed
		$email2 = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $user2->getId() ] )
			->fetchField();
		$this->assertSame( 'valid@mediawiki.test', $email2 );

		$this->expectOutputRegex( '/Batch complete: 1 succeeded, 1 failed out of 2/' );
	}

	public function testBatchResetWithMalformedLine() {
		$file = $this->getNewTempFile();
		$content = "just-a-username-no-email\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		$this->expectOutputRegex( "/Line 1: expected/" );
	}

	public function testBatchResetReportsCorrectLineNumbers() {
		$file = $this->getNewTempFile();
		// Empty line on 2, comment on 3, malformed on 4
		$content = "# comment\n"
			. "\n"
			. "# another comment\n"
			. "malformed-no-email\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		// Line 4 in the original file, not line 1
		$this->expectOutputRegex( "/Line 4: expected/" );
	}

	public function testBatchResetWithUserIdFormat() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		// Use #<id> format instead of username
		$content = '#' . $user->getId() . "\tnew@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		$emailAfter = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->fetchField();
		$this->assertSame( 'new@mediawiki.test', $emailAfter );

		$this->expectOutputRegex( '/Batch complete: 1 succeeded, 0 failed out of 1/' );
	}

	public function testBatchResetRejectsSpaceSeparatedLine() {
		$file = $this->getNewTempFile();
		// Space-separated instead of tab-separated should be rejected
		$content = "SimpleUser new@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->execute();

		$this->expectOutputRegex( "/Line 1: expected/" );
	}

	public function testCannotUseBothFileAndPositionalArgs() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Cannot use both/' );

		$this->maintenance->setArg( 0, 'SomeUser' );
		$this->maintenance->setArg( 1, 'test@mediawiki.test' );
		$this->maintenance->setOption( 'file', '/some/file' );
		$this->maintenance->execute();
	}

	public function testNoArgsAndNoFileFails() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Either provide/' );
		$this->maintenance->execute();
	}

	public function testBatchResetOnUnreadableFile() {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Could not open file/' );

		$this->maintenance->setOption( 'file', '/nonexistent/path/to/file.txt' );
		$this->maintenance->execute();
	}

	public function testBatchResetWithPasswordReset() {
		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user = $this->getMutableTestUser()->getUser();

		$passwordHashBefore = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->fetchField();

		$file = $this->getNewTempFile();
		$content = $user->getName() . "\tnew@mediawiki.test\n";
		file_put_contents( $file, $content );

		// Do NOT set no-reset-password, so the password reset branch is exercised
		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->execute();

		$passwordHashAfter = $this->newSelectQueryBuilder()
			->select( 'user_password' )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->fetchField();
		$this->assertNotSame( $passwordHashBefore, $passwordHashAfter );

		$this->expectOutputRegex( '/Batch complete: 1 succeeded, 0 failed out of 1/' );
	}

	public function testBatchResetWithEmailPasswordOnFailure() {
		$this->overrideConfigValue( MainConfigNames::EnableEmail, true );
		// Abort all password reset submissions for the test
		$this->setTemporaryHook( 'SpecialPasswordResetOnSubmit', static function ( $users, $data, &$error ) {
			$error = 'test';
			return false;
		} );

		ConvertibleTimestamp::setFakeTime( '20240506070809' );
		$user = $this->getMutableTestUser()->getUser();

		$file = $this->getNewTempFile();
		$content = $user->getName() . "\tnew@mediawiki.test\n";
		file_put_contents( $file, $content );

		$this->maintenance->setOption( 'file', $file );
		$this->maintenance->setOption( 'no-reset-password', 1 );
		$this->maintenance->setOption( 'email-password', 1 );
		$this->maintenance->execute();

		// Verify the email was still changed despite email-password failure
		$emailAfter = $this->newSelectQueryBuilder()
			->select( 'user_email' )
			->from( 'user' )
			->where( [ 'user_id' => $user->getId() ] )
			->fetchField();
		$this->assertSame( 'new@mediawiki.test', $emailAfter );

		$this->expectOutputRegex( "/Email couldn't be sent for/" );
	}
}
