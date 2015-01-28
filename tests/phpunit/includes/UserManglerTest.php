<?php

/**
 * @group Database
 * @covers UserMangler
 */
class UserManglerTest extends MediaWikiTestCase {
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
		$status = UserMangler::canResetPassword( $context );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'passwordreset-disabled', $status->errors[0]['message'] );

		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => true ) );
		$config->set( 'EnableEmail', false );
		$status = UserMangler::canResetPassword( $context );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'passwordreset-emaildisabled', $status->errors[0]['message'] );
	}

	public function testResetPassword() {
		global $wgHooks;

		$wgHooks['SpecialPasswordResetOnSubmit'] = array();

		$config = new HashConfig( array(
			'PasswordResetRoutes' => array( 'username' => true, 'email' => true ),
		) );
		$context = new RequestContext;
		$context->setConfig( new MultiConfig( array(
			$config,
			$context->getConfig(),
		) ) );

		$user = User::newFromName( 'ResetPasswordUnitTestUser' );
		$user->loadDefaults( 'ResetPasswordUnitTestUser' );
		if ( !$user->getId() ) {
			$user->addToDatabase();
		}
		$user->removeGroup( 'testcapture' );
		$user->setEmail( '' );
		$user->saveSettings();
		$context->setUser( $user );

		$status = UserMangler::resetPassword( $context, array( 'username' => '' ), false );
		$this->assertFalse( $status );

		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ), true );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'badaccess-groups', $status->errors[0]['message'] );

		$status = UserMangler::resetPassword( $context, array( 'username' => 'Invalid>Name' ) );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'nosuchuser', $status->errors[0]['message'] );

		$i = 0;
		$db = wfGetDB( DB_SLAVE );
		do {
			$i++;
			$email = "example$i@example.invalid";
		} while ( $db->selectRowCount( 'user', null, array( 'user_email' => $email ) ) > 0 );
		$status = UserMangler::resetPassword( $context, array( 'email' => $email ) );
		$this->assertTrue( $status->isGood() );

		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'noemail', $status->errors[0]['message'] );

		$user->setEmail( 'example@example.invalid' );
		$user->saveSettings();

		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertTrue( $status->isGood() );
		$this->assertNull( $status->value->email );

		$status = UserMangler::resetPassword( $context, array( 'email' => $user->getEmail() ) );
		$this->assertTrue( $status->isGood() );
		$this->assertNull( $status->value->email );

		$config->set( 'PasswordResetRoutes', array( 'username' => false, 'email' => true ) );
		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status );

		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => false ) );
		$status = UserMangler::resetPassword( $context, array( 'email' => $user->getEmail() ) );
		$this->assertFalse( $status );

		$config->set( 'PasswordResetRoutes', array( 'username' => true, 'email' => true ) );

		$user->addGroup( 'testcapture' );

		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertTrue( $status->isGood() );
		$this->assertNull( $status->value->email );

		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ), true );
		$this->assertTrue( $status->isGood() );
		$this->assertNotNull( $status->value->email );

		$wgHooks['SpecialPasswordResetOnSubmit'] = array( function ( &$users, $hookData, &$error ) {
			$error = array( 'hook aborted' );
			return false;
		} );
		$status = UserMangler::resetPassword( $context, array( 'username' => $user->getName() ) );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'hook aborted', $status->errors[0]['message'] );
	}

	public function testChangePassword() {
		global $wgHooks;

		$context = new RequestContext;

		$userAnon = $this->getMockBuilder( 'User' )
			->getMock();
		$userAnon->expects( $this->any() )->method( 'getName' )->will( $this->returnValue( '192.0.2.1' ) );
		$userAnon->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( true ) );

		$user = $this->getMockBuilder( 'User' )
			->getMock();
		$user->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ResetPasswordUnitTestUser' ) );
		$user->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user->expects( $this->atLeastOnce() )->method( 'setCookies' );

		$user2 = $this->getMockBuilder( 'User' )
			->getMock();
		$user2->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ResetPasswordUnitTestUser2' ) );
		$user2->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user2->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user2->expects( $this->never() )->method( 'setCookies' );

		$context->setUser( $user );
		$mangler = new UserMangler( $userAnon, $context );
		$status = $mangler->changePassword( 'old', 'new', 'new' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'nosuchusershort', $status->errors[0]['message'] );

		$mangler = new UserMangler( $user, $context );
		$status = $mangler->changePassword( 'old', 'new', 'new2' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'badretype', $status->errors[0]['message'] );

		$status = $mangler->changePassword( 'wrong', 'new', 'new' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'resetpass-wrong-oldpass', $status->errors[0]['message'] );

		$status = $mangler->changePassword( 'old', 'old', 'old' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'resetpass-recycled', $status->errors[0]['message'] );

		$status = $mangler->changePassword( 'old', 'new', 'new' );
		$this->assertTrue( $status->isGood() );

		$mangler = new UserMangler( $user2, $context );
		$status = $mangler->changePassword( 'old', 'new', 'new' );
		$this->assertTrue( $status->isGood() );

		$user2->expects( $this->any() )->method( 'setPassword' )
			->will( $this->throwException( new PasswordError( 'error!' ) ) );
		$status = $mangler->changePassword( 'old', 'new', 'new' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( array( 'error!' ), $status->errors[0]['message']->getParams() );
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

		$userAnon = $this->getMockBuilder( 'User' )
			->getMock();
		$userAnon->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( '192.0.2.1' ) );
		$userAnon->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( true ) );

		$user = $this->getMockBuilder( 'User' )
			->getMock();
		$user->expects( $this->any() )->method( 'getName' )
			->will( $this->returnValue( 'ResetPasswordUnitTestUser' ) );
		$user->expects( $this->any() )->method( 'isAnon' )->will( $this->returnValue( false ) );
		$user->expects( $this->any() )->method( 'checkPassword' )
			->will( $this->returnCallback( function ( $p ) {
				return $p === 'old';
			} ) );
		$user->expects( $this->any() )->method( 'setEmailWithConfirmation' )
			->will( $this->returnValue( Status::newGood() ) );

		$context->setUser( $user );
		$mangler = new UserMangler( $userAnon, $context );
		$status = $mangler->changeEmail( 'old', 'example@example.invalid' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'nosuchusershort', $status->errors[0]['message'] );

		$context->setUser( $user );
		$mangler = new UserMangler( $user, $context );
		$status = $mangler->changeEmail( 'old', 'example.invalid' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'invalidemailaddress', $status->errors[0]['message'] );

		$status = $mangler->changeEmail( 'wrong', 'example@example.invalid' );
		$this->assertFalse( $status->isOk() );
		$this->assertSame( 'wrongpassword', $status->errors[0]['message'] );

		$status = $mangler->changeEmail( 'old', 'example@example.invalid' );
		$this->assertTrue( $status->isGood() );

		$config->set( 'RequirePasswordforEmailChange', false );
		$status = $mangler->changeEmail( 'wrong', 'example@example.invalid' );
		$this->assertTrue( $status->isGood() );
	}

}
