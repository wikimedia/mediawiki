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
	 */
	public function testAuthentication() {
		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'PasswordAuthenticationRequest' => $req );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )->enableProxyingToOriginalMethods()->getMock();
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

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'foo', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( false );
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$pluginUser = $this->getMockBuilder( 'AuthPluginUser' )
			->setMethods( array( 'isLocked' ) )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->willReturn( true );
		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'getUserInstance', 'authenticate', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->willReturn( $pluginUser );
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'strict', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( false );
		$plugin->method( 'strict' )->willReturn( true );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'strictUserAuth', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
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

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain3';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );
	}

	public function testTestUserExists() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->testUserExists( 'foo' ) );
	}

	public function testTestUserCanAuthenticate() {
		$that = $this;

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( false );
		$plugin->expects( $this->never() )->method( 'getUserInstance' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( 'AuthPluginUser' )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->willReturn( true );
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) use ( $that ) {
				$that->assertInstanceOf( 'User', $user );
				$that->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->willReturn( $pluginUser );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( 'AuthPluginUser' )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->willReturn( false );
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->willReturn( true );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) use ( $that ) {
				$that->assertInstanceOf( 'User', $user );
				$that->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->willReturn( $pluginUser );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}

	public function testProviderRevokeAccessForUser() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->identicalTo( null ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerRevokeAccessForUser( 'foo' );
	}

	public function testProviderAllowsPropertyChange() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'allowPropChange' )
			->will( $this->returnCallback( function ( $prop ) {
				return $prop === 'allow';
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'allow' ) );
		$this->assertFalse( $provider->providerAllowsPropertyChange( 'deny' ) );
	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChangeType
	 * @param string $type
	 * @param bool|null $allow
	 * @param bool $expect
	 */
	public function testProviderAllowsAuthenticationDataChangeType( $type, $allow, $expect ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $allow === null ? $this->never() : $this->once() )
			->method( 'allowPasswordChange' )->willReturn( $allow );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertSame( $expect, $provider->providerAllowsAuthenticationDataChangeType( $type ) );
	}

	public static function provideProviderAllowsAuthenticationDataChangeType() {
		return array(
			array( 'AuthenticationRequest', null, true ),
			array( 'PasswordAuthenticationRequest', true, true ),
			array( 'PasswordAuthenticationRequest', false, false ),
		);
	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChangeType
	 * @param string $type
	 * @param bool|null $allow
	 * @param bool $expect
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, $allow, $expect ) {
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
		$req->username = 'UTSysop';
		$req->password = 'Password';
		$this->assertEquals( $expect, $provider->providerAllowsAuthenticationDataChange( $req ) );
	}

	/**
	 * @uses PasswordAuthenticationRequest
	 */
	public function testProviderChangeAuthenticationData() {
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
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain3';
		$req->password = 'bar';
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
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->method( 'canCreateAccounts' )->willReturn( true );
		$plugin->method( 'domainList' )->willReturn( array( 'Domain1', 'Domain2' ) );
		$plugin->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( 'login' );
		$req = new $reqType();
		$req->username = 'foo@Domain3';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );
	}

}
