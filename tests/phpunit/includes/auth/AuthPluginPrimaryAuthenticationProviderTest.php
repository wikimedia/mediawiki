<?php

/**
 * @group AuthManager
 * @covers AuthPluginPrimaryAuthenticationProvider
 * @uses AbstractPasswordPrimaryAuthenticationProvider
 * @uses AbstractAuthenticationProvider
 */
class AuthPluginPrimaryAuthenticationProviderTest extends MediaWikiTestCase {

	/**
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 */
	public function testConstruction() {
		$plugin = new AuthManagerAuthPlugin();
		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'Trying to wrap AuthManagerAuthPlugin in AuthPluginPrimaryAuthenticationProvider makes no sense.',
				$ex->getMessage()
			);
		}

		$plugin = $this->getMock( 'AuthPlugin' );

		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame(
			array( 'PasswordAuthenticationRequest' ),
			$provider->getAuthenticationRequestTypes( 'all' )
		);

		$reqType = get_class( $this->getMock( 'PasswordAuthenticationRequest' ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, $reqType );
		$this->assertSame(
			array( $reqType ),
			$provider->getAuthenticationRequestTypes( 'all' )
		);

		$reqType = get_class( $this->getMock( 'AuthenticationRequest' ) );
		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, $reqType );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				"$reqType is not a PasswordAuthenticationRequest",
				$ex->getMessage()
			);
		}

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'all' );
		$this->assertTrue( is_subclass_of( $reqType, 'PasswordDomainAuthenticationRequest' ),
			"$reqType instanceof PasswordDomainAuthenticationRequest" );
		$info = call_user_func( array( $reqType, 'getFieldInfo' ) );
		$this->assertSame(
			array( 'Domain1', 'Domain2' ),
			array_keys( $info['domain']['options'] )
		);

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2', 'Domain3' ) );
		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, $reqType );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				"Domain list for $reqType does not match that for " . get_class( $plugin ),
				$ex->getMessage()
			);
		}

		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, 'PasswordAuthenticationRequest' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'PasswordAuthenticationRequest is not a subclass of PasswordDomainAuthenticationRequest',
				$ex->getMessage()
			);
		}
	}

	public function testOnUserSaveSettings() {
		$user = User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateExternalDB' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		Hooks::run( 'UserSaveSettings', array( $user ) );
	}

	public function testOnUserGroupsChanged() {
		$user = User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateExternalDBGroups' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( array( 'added' ) ),
				$this->identicalTo( array( 'removed' ) )
			);
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		Hooks::run( 'UserGroupsChanged', array( $user, array( 'added' ), array( 'removed' ) ) );
	}

	public function testOnUserLoggedIn() {
		$user = User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->exactly( 2 ) )->method( 'updateUser' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		Hooks::run( 'UserLoggedIn', array( $user ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			Hooks::run( 'UserLoggedIn', array( $user ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $plugin ) . '::updateUser() tried to replace $user!',
				$ex->getMessage()
			);
		}
	}

	public function testOnLocalUserCreated() {
		$user = User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->exactly( 2 ) )->method( 'initUser' )
			->with( $this->identicalTo( $user ), $this->identicalTo( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		Hooks::run( 'LocalUserCreated', array( $user, false ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'initUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			Hooks::run( 'LocalUserCreated', array( $user, false ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $plugin ) . '::initUser() tried to replace $user!',
				$ex->getMessage()
			);
		}
	}

	public function testGetUniqueId() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame(
			'AuthPluginPrimaryAuthenticationProvider:' . get_class( $plugin ),
			$provider->getUniqueId()
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $which
	 * @param array $response
	 * @param bool $allowPasswordChange
	 */
	public function testGetAuthenticationRequestTypes( $which, $response, $allowPasswordChange ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'allowPasswordChange' )->willReturn( $allowPasswordChange );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $which ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		return array(
			array( 'login', array( 'PasswordAuthenticationRequest' ), true ),
			array( 'login', array( 'PasswordAuthenticationRequest' ), false ),
			array( 'create', array( 'PasswordAuthenticationRequest' ), true ),
			array( 'create', array( 'PasswordAuthenticationRequest' ), false ),
			array( 'change', array( 'PasswordAuthenticationRequest' ), true ),
			array( 'change', array(), false ),
			array( 'all', array( 'PasswordAuthenticationRequest' ), true ),
			array( 'all', array( 'PasswordAuthenticationRequest' ), false ),
			array( 'login-continue', array(), true ),
			array( 'login-continue', array(), false ),
			array( 'create-continue', array(), true ),
			array( 'create-continue', array(), false ),
		);
	}

	/**
	 * @uses AuthenticationResponse
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 */
	public function testAuthentication() {
		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

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

		$req->username = 'foo';
		$req->password = 'bar';

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'foo', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$plugin->method( 'strict' )->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$plugin->method( 'strictUserAuth' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->expects( $this->at( 2 ) )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->at( 3 ) )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain1';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );
	}

	/**
	 * @uses AuthnUserStatus
	 */
	public function testUserStatus() {
		$userInst = $this->getMock( 'AuthPluginUser', array(), array( 'foo' ) );
		$userInst->expects( $this->once() )->method( 'isLocked' )->willReturn( true );
		$userInst->expects( $this->once() )->method( 'isHidden' )->willReturn( true );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) {
				return $user->getName() === 'Foo';
			} ) )
			->willReturn( $userInst );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertEquals(
			new AuthnUserStatus( array( 'exists' => true, 'locked' => true, 'hidden' => true ) ),
			$provider->userStatus( 'foo' )
		);
	}

	public function testProviderAllowPropertyChange() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'allowPropChange' )
			->will( $this->returnCallback( function ( $prop ) {
				return $prop === 'allow';
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->providerAllowPropertyChange( 'allow' ) );
		$this->assertFalse( $provider->providerAllowPropertyChange( 'deny' ) );
	}

	/**
	 * @dataProvider provideProviderAllowChangingAuthenticationType
	 * @param string $type
	 * @param bool|null $allow
	 * @param bool $expect
	 */
	public function testProviderAllowChangingAuthenticationType( $type, $allow, $expect ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $allow === null ? $this->never() : $this->once() )
			->method( 'allowPasswordChange' )->willReturn( $allow );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertSame( $expect, $provider->providerAllowChangingAuthenticationType( $type ) );
	}

	public static function provideProviderAllowChangingAuthenticationType() {
		return array(
			array( 'AuthenticationRequest', null, true ),
			array( 'PasswordAuthenticationRequest', true, true ),
			array( 'PasswordAuthenticationRequest', false, false ),
			array( 'PasswordDomainAuthenticationRequest', true, true ),
			array( 'PasswordDomainAuthenticationRequest', false, false ),
		);
	}

	/**
	 * @dataProvider provideProviderAllowChangingAuthenticationType
	 * @param string $type
	 * @param bool|null $allow
	 * @param bool $expect
	 */
	public function testCanChangeAuthenticationData( $type, $allow, $expect ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $allow === null ? $this->never() : $this->once() )
			->method( 'allowPasswordChange' )->willReturn( $allow );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		if ( $expect ) {
			$expect = StatusValue::newGood();
		} else {
			$expect = StatusValue::newFatal( 'authmanager-authplugin-setpass-fail' );
		}

		if ( $type === 'PasswordAuthenticationRequest' ) {
			$req = new $type();
		} else {
			$req = $this->getMock( $type );
		}
		$this->assertEquals( $expect, $provider->providerCanChangeAuthenticationData( $req ) );
	}

	/**
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 */
	public function testChangeAuthenticationData() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->never() )->method( 'setPassword' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData( $this->getMock( 'AuthenticationRequest' ) );

		$req = new PasswordAuthenticationRequest();
		$req->username = 'foo';
		$req->password = 'bar';

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->providerChangeAuthenticationData( $req );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ErrorPageError $e ) {
			$this->assertSame( 'authmanager-authplugin-setpass-failed-title', $e->title );
			$this->assertSame( 'authmanager-authplugin-setpass-failed-message', $e->msg );
		}

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->expects( $this->at( 2 ) )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->at( 3 ) )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain1';
		$provider->providerChangeAuthenticationData( $req );
	}

	/**
	 * @dataProvider provideAccountCreationType
	 * @param bool $can
	 * @param string $expect
	 */
	public function testAccountCreationType( $can, $expect ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )
			->method( 'canCreateAccounts' )->willReturn( $can );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertSame( $expect, $provider->accountCreationType() );
	}

	public static function provideAccountCreationType() {
		return array(
			array( true, PrimaryAuthenticationProvider::TYPE_CREATE ),
			array( false, PrimaryAuthenticationProvider::TYPE_NONE ),
		);
	}

	public function testTestForAccountCreation() {
		$user = User::newFromName( 'foo' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, array() )
		);
	}

	/**
	 * @uses AuthenticationResponse
	 * @uses PasswordAuthenticationRequest
	 * @uses PasswordDomainAuthenticationRequest
	 */
	public function testAccountCreation() {
		$user = User::newFromName( 'foo' );
		$user->setEmail( 'email' );
		$user->setRealName( 'realname' );

		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( false );
		$plugin->expects( $this->never() )->method( 'addUser' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->beginPrimaryAccountCreation( $user, array() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame( 'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage() );
		}

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->expects( $this->never() )->method( 'addUser' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

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

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->equalTo( 'foo' ), $this->equalTo( 'bar' ),
				$this->equalTo( 'email' ), $this->equalTo( 'realname' )
			)
			->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->equalTo( 'foo' ), $this->equalTo( 'bar' ),
				$this->equalTo( 'email' ), $this->equalTo( 'realname' )
			)
			->willReturn( false );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAccountCreation( $user, $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-authplugin-create-fail', $ret->message->getKey() );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->expects( $this->at( 3 ) )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->at( 4 ) )->method( 'addUser' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain1';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );
	}

}
