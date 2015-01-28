<?php

/**
 * @group Database
 * @covers UserManager
 */
class UserManagerTest extends MediaWikiTestCase {
	public function setUp() {
		parent::setUp();
		$this->setMwGlobals( array(
			'wgGroupPermissions' => array(
				'testcapture' => array( 'passwordreset' => true ),
			),
			'wgPasswordReminderResendTime' => false,
			'wgRateLimits' => array(),
			'wgHooks' => array(),
		) );
	}

	public function testCanResetPassword() {
		$config = new HashConfig( array() );
		$context = new RequestContext;
		$context->setConfig( new MultiConfig( array(
			$config,
			$context->getConfig(),
		) ) );

		$config->set( 'PasswordResetRoutes', array( 'username' => false, 'email' => false ) );
		$status = UserManager::canResetPassword( $context );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'passwordreset-disabled', $status->errors[0]['message'] );

		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => true ) );
		$config->set( 'EnableEmail', false );
		$status = UserManager::canResetPassword( $context );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'passwordreset-emaildisabled', $status->errors[0]['message'] );
	}

	public function testResetPassword() {
		global $wgHooks;

		$wgHooks['SpecialPasswordResetOnSubmit'] = array();

		$config = new HashConfig( array() );
		$context = new RequestContext;
		$context->setConfig( new MultiConfig( array(
			$config,
			$context->getConfig(),
		) ) );

		// Create our test user. Ensure it lacks the "passwordreset" right and
		// has no email address set for the initial tests (these will be
		// changed below).
		$user = User::newFromName( 'ResetPasswordUnitTestUser' );
		$user->loadDefaults( 'ResetPasswordUnitTestUser' );
		if ( !$user->getId() ) {
			$user->addToDatabase();
		}
		$user->removeGroup( 'testcapture' );
		$user->setEmail( '' );
		$user->saveSettings();
		$context->setUser( $user );

		// Test pass-through of error from canResetPassword()
		$config->set( 'PasswordResetRoutes', array( 'username' => false, 'email' => false ) );
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status->isOk(),
			'Pass through error from UserManager::canResetPassword()' );
		$this->assertSame( 'passwordreset-disabled', $status->errors[0]['message'],
			'Pass through error from UserManager::canResetPassword()' );

		// Test that $wgPasswordResetRoutes is honored
		$config->set( 'PasswordResetRoutes', array( 'username' => false, 'email' => true ) );
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status, '$wgPasswordResetRoutes missing username, reset with username' );

		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => false ) );
		$status = UserManager::resetPassword( $context, array( 'email' => 'example@example.invalid' ) );
		$this->assertFalse( $status, '$wgPasswordResetRoutes missing email, reset with email' );

		// == Enable all routes for all remaining tests. ==
		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => true ) );

		// Make sure empty username/email input is correctly handled
		$status = UserManager::resetPassword( $context, array( 'username' => '', 'email' => '' ) );
		$this->assertFalse( $status, 'Empty username/email correctly handled' );

		$status = UserManager::resetPassword( $context,
			array( 'username' => '', 'email' => 'example@example.invalid' ) );
		$this->assertInstanceOf( 'Status', $status, 'Empty username doesn\'t override email' );

		$status = UserManager::resetPassword( $context,
			array( 'username' => $user->getName(), 'email' => '' ) );
		$this->assertInstanceOf( 'Status', $status, 'Empty email doesn\'t override username' );

		// Test various invalid inputs
		$status = UserManager::resetPassword( $context, array( 'username' => 'Invalid>Name' ) );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'nosuchuser', $status->errors[0]['message'], 'Invalid username is handled' );

		$i = 0;
		$db = wfGetDB( DB_SLAVE );
		do {
			$i++;
			$name = "ResetPasswordUnitTestUser$i";
		} while ( $db->selectRowCount( 'user', null, array( 'user_name' => $name ) ) > 0 );
		$status = UserManager::resetPassword( $context, array( 'username' => $name ) );
		$this->assertSame( 'nosuchuser', $status->errors[0]['message'], 'Nonexistent username is handled' );

		$i = 0;
		$db = wfGetDB( DB_SLAVE );
		do {
			$i++;
			$email = "example$i@example.invalid";
		} while ( $db->selectRowCount( 'user', null, array( 'user_email' => $email ) ) > 0 );
		$status = UserManager::resetPassword( $context, array( 'email' => $email ) );
		$this->assertTrue( $status->isGood(), 'Not-in-use email returns a "good" status' );

		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'noemail', $status->errors[0]['message'],
			'User with no email address set is handled' );

		// == Set email address on test user for remaining tests ==
		$user->setEmail( 'example@example.invalid' );
		$user->saveSettings();

		// Test successful resets
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertTrue( $status->isGood(), 'Successful reset by username' );
		$this->assertNull( $status->value->email, 'Successful reset by username (no capture)' );

		$status = UserManager::resetPassword( $context, array( 'email' => $user->getEmail() ) );
		$this->assertTrue( $status->isGood(), 'Successful reset by email' );
		$this->assertNull( $status->value->email, 'Successful reset by email (no capture)' );

		// Make sure capture attempt without the needed right fails
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ), true );
		$this->assertFalse( $status->isOk(), 'Attempt to capture without user right' );
		$this->assertSame( 'badaccess-groups', $status->errors[0]['message'],
			'Attempt to capture without user right' );

		// == Test capture with the needed right now ==
		$user->addGroup( 'testcapture' );

		// No capture if not asked
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertTrue( $status->isGood(),
			'Successful reset by username, capture possible but not requested' );
		$this->assertNull( $status->value->email,
			'Successful reset by username, capture possible but not requested' );

		// Capture works if asked with correct permission
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ), true );
		$this->assertTrue( $status->isGood(), 'Successful reset by username with capture' );
		$this->assertNotNull( $status->value->email, 'Successful reset by username with capture' );

		// Test handling of failure returned from the
		// 'SpecialPasswordResetOnSubmit' hook.
		$wgHooks['SpecialPasswordResetOnSubmit'] = array( function ( &$users, $hookData, &$error ) {
			$error = array( 'SpecialPasswordResetOnSubmit hook aborted' );
			return false;
		} );
		$status = UserManager::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status->isOk(),
			'Failed by SpecialPasswordResetOnSubmit hook' );
		$this->assertSame( 'SpecialPasswordResetOnSubmit hook aborted', $status->errors[0]['message'],
			'Failed by SpecialPasswordResetOnSubmit hook' );
		$wgHooks['SpecialPasswordResetOnSubmit'] = array();
	}

	public function testChangePassword() {
		global $wgHooks;

		$context = new RequestContext;

		// For this test, we can mock the User objects to avoid having to care
		// about database and such.
		$userAnon = $this->getMockBuilder( 'User' )
			->getMock();
		$userAnon->expects( $this->any() )->method( 'getName' )->will( $this->returnValue( '192.0.2.1' ) );
		$userAnon->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( true ) );

		$user = $this->getMockBuilder( 'User' )
			->getMock();
		$user->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ChangePasswordUnitTestUser' ) );
		$user->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user->expects( $this->atLeastOnce() )->method( 'setCookies' );

		$user2 = $this->getMockBuilder( 'User' )
			->getMock();
		$user2->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ChangePasswordUnitTestUser2' ) );
		$user2->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user2->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user2->expects( $this->never() )->method( 'setCookies' );

		// Anonymous user
		$context->setUser( $user );
		$manager = new UserManager( $userAnon, $context );
		$status = $manager->changePassword( 'old', 'new', 'new' );
		$this->assertFalse( $status->isOk(), 'Anonymous user' );
		$this->assertSame( 'nosuchusershort', $status->errors[0]['message'],
			'Anonymous user' );

		// Various bad input
		$manager = new UserManager( $user, $context );
		$status = $manager->changePassword( 'old', 'new', 'new2' );
		$this->assertFalse( $status->isOk(),
			'Mismatched password and retype' );
		$this->assertSame( 'badretype', $status->errors[0]['message'],
			'Mismatched password and retype' );

		$manager = new UserManager( $user, $context );
		$status = $manager->changePassword( 'wrong', 'new', 'new' );
		$this->assertFalse( $status->isOk(),
			'Incorrect current password' );
		$this->assertSame( 'resetpass-wrong-oldpass', $status->errors[0]['message'],
			'Incorrect current password' );

		$manager = new UserManager( $user, $context );
		$status = $manager->changePassword( 'old', 'old', 'old' );
		$this->assertFalse( $status->isOk(),
			'New password is the same as the old one' );
		$this->assertSame( 'resetpass-recycled', $status->errors[0]['message'],
			'New password is the same as the old one' );

		// Correct input. This should call $user->setCookies()
		$manager = new UserManager( $user, $context );
		$status = $manager->changePassword( 'old', 'new', 'new' );
		$this->assertTrue( $status->isGood(), 'Successful change' );

		// Correct input. This should not call $user->setCookies()
		$manager = new UserManager( $user2, $context );
		$status = $manager->changePassword( 'old', 'new', 'new' );
		$this->assertTrue( $status->isGood() );

		// Test correct handling of exceptions from User::setPassword()
		$manager = new UserManager( $user2, $context );
		$user2->expects( $this->any() )->method( 'setPassword' )
			->will( $this->throwException( new PasswordError( 'error!' ) ) );
		$status = $manager->changePassword( 'old', 'new', 'new' );
		$this->assertFalse( $status->isOk(),
			'Exception handling from User::setPassword()' );
		$this->assertSame( array( 'error!' ), $status->errors[0]['message']->getParams(),
			'Exception handling from User::setPassword()' );
	}

	public function testChangeEmail() {
		global $wgHooks;

		$config = new HashConfig( array(
			'RequirePasswordforEmailChange' => true,
		) );
		$context = new RequestContext;
		$context->setConfig( new MultiConfig( array(
			$config,
			$context->getConfig(),
		) ) );

		// For this test, we can mock the User objects to avoid having to care
		// about database and such.
		$userAnon = $this->getMockBuilder( 'User' )
			->getMock();
		$userAnon->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( '192.0.2.1' ) );
		$userAnon->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( true ) );

		$user = $this->getMockBuilder( 'User' )
			->getMock();
		$user->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ChangeEmailUnitTestUser' ) );
		$user->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user->expects( $this->any() )->method( 'setEmailWithConfirmation' )
			->will( $this->returnValue( Status::newGood() ) );

		$context->setUser( $user );

		// Anonymous user
		$manager = new UserManager( $userAnon, $context );
		$status = $manager->changeEmail( 'old', 'example@example.invalid' );
		$this->assertFalse( $status->isOk(),
			'Anonymous user' );
		$this->assertSame( 'nosuchusershort', $status->errors[0]['message'],
			'Anonymous user' );

		// Invalid email address
		$manager = new UserManager( $user, $context );
		$status = $manager->changeEmail( 'old', 'example.invalid' );
		$this->assertFalse( $status->isOk(),
			'Attempt to set invalid email address' );
		$this->assertSame( 'invalidemailaddress', $status->errors[0]['message'],
			'Attempt to set invalid email address' );

		// Incorrect password
		$manager = new UserManager( $user, $context );
		$status = $manager->changeEmail( 'wrong', 'example@example.invalid' );
		$this->assertFalse( $status->isOk(),
			'Incorrect password' );
		$this->assertSame( 'wrongpassword', $status->errors[0]['message'],
			'Incorrect password' );

		// Successful reset
		$manager = new UserManager( $user, $context );
		$status = $manager->changeEmail( 'old', 'example@example.invalid' );
		$this->assertTrue( $status->isGood(), 'Successful reset' );

		// Check that $wgRequirePasswordforEmailChange false doesn't check
		// the password
		$config->set( 'RequirePasswordforEmailChange', false );
		$manager = new UserManager( $user, $context );
		$status = $manager->changeEmail( 'wrong', 'example@example.invalid' );
		$this->assertTrue( $status->isGood(),
			'Wrong password with $wgRequirePasswordforEmailChange = false' );
		$config->set( 'RequirePasswordforEmailChange', true );
	}

}
