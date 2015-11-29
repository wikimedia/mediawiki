<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @group Database
 * @covers MediaWiki\Auth\TemporaryPasswordPrimaryAuthenticationProvider
 */
class TemporaryPasswordPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {

	private $manager = null;
	private $config = null;
	private $validity = null;

	/**
	 * Get an instance of the provider
	 *
	 * $provider->checkPasswordValidity is mocked to return $this->validity,
	 * because we don't need to test that here.
	 *
	 * @return TemporaryPasswordPrimaryAuthenticationProvider
	 */
	protected function getProvider() {
		if ( !$this->config ) {
			$this->config = new \HashConfig();
		}
		$config = new \MultiConfig( array(
			$this->config,
			\ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		) );

		if ( !$this->manager ) {
			$this->manager = new AuthManager( new \FauxRequest(), $config );
		}
		$this->validity = \Status::newGood();

		$that = $this;
		$provider = $this->getMock(
			'MediaWiki\\Auth\\TemporaryPasswordPrimaryAuthenticationProvider',
			array( 'checkPasswordValidity' )
		);
		$provider->expects( $this->any() )->method( 'checkPasswordValidity' )
			->will( $this->returnCallback( function () use ( $that ) {
				return $that->validity;
			} ) );
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $this->manager );

		return $provider;
	}

	public function testBasics() {
		$provider = new TemporaryPasswordPrimaryAuthenticationProvider();

		$this->assertSame(
			PrimaryAuthenticationProvider::TYPE_CREATE,
			$provider->accountCreationType()
		);

		$this->assertTrue( $provider->testUserExists( 'UTSysop' ) );
		$this->assertFalse( $provider->testUserExists( 'DoesNotExist' ) );
	}

	public function testTestUserCanAuthenticate() {
		$dbw = wfGetDB( DB_MASTER );

		$passwordFactory = new \PasswordFactory();
		$passwordFactory->init( \RequestContext::getMain()->getConfig() );
		// A is unsalted MD5 (thus fast) ... we don't care about security here, this is test only
		$passwordFactory->setDefaultType( 'A' );
		$pwhash = $passwordFactory->newFromPlaintext( 'password' )->toString();

		$provider = $this->getProvider();

		$this->assertFalse( $provider->testUserCanAuthenticate( 'DoesNotExist' ) );

		$dbw->update(
			'user',
			array(
				'user_newpassword' => \PasswordFactory::newInvalidPassword()->toString(),
				'user_newpass_time' => null,
			),
			array( 'user_name' => 'UTSysop' )
		);
		$this->assertFalse( $provider->testUserCanAuthenticate( 'UTSysop' ) );

		$dbw->update(
			'user',
			array(
				'user_newpassword' => $pwhash,
				'user_newpass_time' => null,
			),
			array( 'user_name' => 'UTSysop' )
		);
		$this->assertTrue( $provider->testUserCanAuthenticate( 'UTSysop' ) );

		$dbw->update(
			'user',
			array(
				'user_newpassword' => $pwhash,
				'user_newpass_time' => $dbw->timestamp( time() - 10 ),
			),
			array( 'user_name' => 'UTSysop' )
		);
		$this->config->set( 'NewPasswordExpiry', 100 );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'UTSysop' ) );
		$this->config->set( 'NewPasswordExpiry', 1 );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'UTSysop' ) );

		$dbw->update(
			'user',
			array(
				'user_newpassword' => \PasswordFactory::newInvalidPassword()->toString(),
				'user_newpass_time' => null,
			),
			array( 'user_name' => 'UTSysop' )
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$this->assertEquals( $response, $this->getProvider()->getAuthenticationRequests( $action ) );
	}

	public static function provideGetAuthenticationRequests() {
		return array(
			array( AuthManager::ACTION_LOGIN, array(
				new PasswordAuthenticationRequest()
			) ),
			array( AuthManager::ACTION_CREATE, array(
				new TemporaryPasswordAuthenticationRequest()
			) ),
			array( AuthManager::ACTION_CHANGE, array(
				new TemporaryPasswordAuthenticationRequest()
			) ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, array() ),
			array( AuthManager::ACTION_CREATE_CONTINUE, array() ),
			array( AuthManager::ACTION_LINK, array() ),
			array( AuthManager::ACTION_LINK_CONTINUE, array() ),
		);
	}

	/**
	 * @group Database
	 */
	public function testGetAuthenticationRequests_special() {
		\RequestContext::getMain()->setUser( \User::newFromName( 'UTSysop' ) );
		$this->assertEquals( array( new TemporaryPasswordAuthenticationRequest() ),
			$this->getProvider()->getAuthenticationRequests( AuthManager::ACTION_CREATE ) );

		\RequestContext::getMain()->setUser( new \User() );
		$this->assertEquals( array(),
			$this->getProvider()->getAuthenticationRequests( AuthManager::ACTION_CREATE ) );

		$provider = $this->getProvider( array( 'testUserCanAuthenticate' ) );
		$provider->expects( $this->any() )->method( 'testUserCanAuthenticate' )
			->will( $this->returnValue( false ) );
		$this->assertEquals( array(),
			$this->getProvider()->getAuthenticationRequests( AuthManager::ACTION_REMOVE ) );
		$provider->expects( $this->any() )->method( 'testUserCanAuthenticate' )
			->will( $this->returnValue( true ) );
		$this->assertEquals( array( new TemporaryPasswordAuthenticationRequest() ),
			$this->getProvider()->getAuthenticationRequests( AuthManager::ACTION_REMOVE ) );
	}

	public function testAuthentication() {
		$password = 'TemporaryPassword';
		$hash = ':A:' . md5( $password );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			array( 'user_newpassword' => $hash, 'user_newpass_time' => $dbw->timestamp( time() - 10 ) ),
			array( 'user_name' => 'UTSysop' )
		);

		$req = new PasswordAuthenticationRequest();
		$reqs = array( $req );

		$provider = $this->getProvider();

		$this->config->set( 'NewPasswordExpiry', 100 );

		// General failures
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( array() )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$req->username = 'DoesNotExist';
		$req->password = 'DoesNotExist';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		// Validation failure
		$req->username = 'UTSysop';
		$req->password = $password;
		$this->validity = \Status::newFatal( 'arbitrary-failure' );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'arbitrary-failure',
			$ret->message->getKey()
		);

		// Successful auth
		$this->manager->removeAuthenticationSessionData( null );
		$this->validity = \Status::newGood();
		$this->assertEquals(
			AuthenticationResponse::newPass( 'UTSysop', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);
		$this->assertNotNull( $this->manager->getAuthenticationSessionData( 'reset-pass' ) );

		// Expired password
		$this->config->set( 'NewPasswordExpiry', 1 );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

		// Bad password
		$this->config->set( 'NewPasswordExpiry', 100 );
		$this->validity = \Status::newGood();
		$req->password = 'Wrong';
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey()
		);

	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChange
	 * @param string $type
	 * @param string $user
	 * @param Status $validity
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, $user, $validity ) {
		$expect = \StatusValue::newGood();
		$expect->merge( $validity, true );

		if ( $type === 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ||
			$type === 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest'
		) {
			$req = new $type();
		} else {
			$req = $this->getMock( $type );
		}
		$req->username = $user;
		$req->password = 'NewPassword';

		$provider = $this->getProvider();
		$this->validity = $validity;
		$this->assertEquals( $expect, $provider->providerAllowsAuthenticationDataChange( $req ) );
	}

	public static function provideProviderAllowsAuthenticationDataChange() {
		$err = \Status::newGood();
		$err->error( 'arbitrary-warning' );

		return array(
			array( 'MediaWiki\\Auth\\AuthenticationRequest', 'UTSysop',
				\Status::newGood( 'ignored' ) ),
			array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', 'UTSysop',
				\Status::newGood( 'ignored' ) ),
			array( 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest', 'UTSysop',
				\Status::newGood() ),
			array( 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest', 'UTSysop', $err ),
			array( 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest', 'UTSysop',
				\Status::newFatal( 'arbitrary-error' ) ),
			array( 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest', 'DoesNotExist',
				\Status::newGood( 'ignored' ) ),
		);
	}

	/**
	 * @dataProvider provideProviderChangeAuthenticationData
	 * @param string $type
	 * @param bool $changed
	 */
	public function testProviderChangeAuthenticationData( $type, $changed ) {
		$user = 'UTSysop';
		$oldpass = 'OldTempPassword';
		$newpass = 'NewTempPassword';

		$hash = ':A:' . md5( $oldpass );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			array( 'user_newpassword' => $hash, 'user_newpass_time' => $dbw->timestamp( time() + 10 ) ),
			array( 'user_name' => 'UTSysop' )
		);

		$dbw = wfGetDB( DB_MASTER );
		$oldHash = $dbw->selectField( 'user', 'user_newpassword', array( 'user_name' => $user ) );
		$cb = new \ScopedCallback( function () use ( $dbw, $user, $oldHash ) {
			$dbw->update( 'user', array( 'user_newpassword' => $oldHash ), array( 'user_name' => $user ) );
		} );

		$provider = $this->getProvider();

		// Sanity check
		$req = new PasswordAuthenticationRequest();
		$req->username = $user;
		$req->password = $oldpass;
		$reqs = array( $req );
		$this->assertEquals(
			AuthenticationResponse::newPass( $user, $req ),
			$provider->beginPrimaryAuthentication( $reqs ),
			'Sanity check'
		);

		if ( $type === 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ||
			$type === 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest'
		) {
			$req2 = new $type();
		} else {
			$req2 = $this->getMock( $type );
		}
		$req2->username = $user;
		$req2->password = $newpass;
		$provider->providerChangeAuthenticationData( $req2 );

		$req->password = $oldpass;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertEquals(
			AuthenticationResponse::FAIL,
			$ret->status,
			'old password should fail'
		);
		$this->assertEquals(
			'wrongpassword',
			$ret->message->getKey(),
			'old password should fail'
		);

		$req->password = $newpass;
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		if ( $changed ) {
			$this->assertEquals(
				AuthenticationResponse::newPass( $user, $req ),
				$ret,
				'new password should pass'
			);
		} else {
			$this->assertEquals(
				AuthenticationResponse::FAIL,
				$ret->status,
				'new password should fail'
			);
			$this->assertEquals(
				'wrongpassword',
				$ret->message->getKey(),
				'new password should fail'
			);
		}
	}

	public static function provideProviderChangeAuthenticationData() {
		return array(
			array( 'MediaWiki\\Auth\\AuthenticationRequest', false ),
			array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', false ),
			array( 'MediaWiki\\Auth\\TemporaryPasswordAuthenticationRequest', true ),
		);
	}

	public function testTestForAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$req = new TemporaryPasswordAuthenticationRequest();
		$req->username = 'Foo';
		$req->password = 'Bar';
		$reqs = array( $req );

		$provider = $this->getProvider();
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, array() ),
			'No password request'
		);

		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, validated'
		);

		$this->validity->error( 'arbitrary warning' );
		$expect = \StatusValue::newGood();
		$expect->error( 'arbitrary warning' );
		$this->assertEquals(
			$expect,
			$provider->testForAccountCreation( $user, $user, $reqs ),
			'Password request, not validated'
		);
	}

	public function testAccountCreation() {
		$user = \User::newFromName( 'Foo' );

		$req = new TemporaryPasswordAuthenticationRequest();
		$reqs = array( $req );

		$authreq = new PasswordAuthenticationRequest();
		$authreqs = array( $authreq );

		$provider = $this->getProvider();

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, array() )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$req->username = 'foo';
		$req->password = 'bar';

		$req2 = clone( $req );
		$req2->username = 'Foo';
		$this->assertEquals(
			AuthenticationResponse::newPass( 'Foo', $req2 ),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		// We have to cheat a bit to avoid having to add a new user to
		// the database to test the actual setting of the password works right
		$user = \User::newFromName( 'UTSysop' );
		$req->username = $authreq->username = $user->getName();
		$req->password = $authreq->password = 'NewPassword';
		$expect = AuthenticationResponse::newPass( 'UTSysop', $req );

		$res2 = $provider->beginPrimaryAccountCreation( $user, $reqs );
		$this->assertEquals( $expect, $res2, 'Sanity check' );

		$ret = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertEquals( AuthenticationResponse::FAIL, $ret->status, 'sanity check' );

		$provider->finishAccountCreation( $user, $res2 );
		$ret = $provider->beginPrimaryAuthentication( $authreqs );
		$this->assertEquals( AuthenticationResponse::PASS, $ret->status, 'new password is set' );

	}

}
