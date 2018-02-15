<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AuthPluginPrimaryAuthenticationProvider
 */
class AuthPluginPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testConstruction() {
		$plugin = new AuthManagerAuthPlugin();
		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'Trying to wrap AuthManagerAuthPlugin in AuthPluginPrimaryAuthenticationProvider ' .
					'makes no sense.',
				$ex->getMessage()
			);
		}

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );

		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			[ new PasswordAuthenticationRequest ],
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, [] )
		);

		$req = $this->createMock( PasswordAuthenticationRequest::class );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, get_class( $req ) );
		$this->assertEquals(
			[ $req ],
			$provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, [] )
		);

		$reqType = get_class( $this->createMock( AuthenticationRequest::class ) );
		try {
			$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, $reqType );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				"$reqType is not a MediaWiki\\Auth\\PasswordAuthenticationRequest",
				$ex->getMessage()
			);
		}
	}

	public function testOnUserSaveSettings() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'updateExternalDB' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		\Hooks::run( 'UserSaveSettings', [ $user ] );
	}

	public function testOnUserGroupsChanged() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'updateExternalDBGroups' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( [ 'added' ] ),
				$this->identicalTo( [ 'removed' ] )
			);
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		\Hooks::run( 'UserGroupsChanged', [ $user, [ 'added' ], [ 'removed' ], false, false, [], [] ] );
	}

	public function testOnUserLoggedIn() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->exactly( 2 ) )->method( 'updateUser' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		\Hooks::run( 'UserLoggedIn', [ $user ] );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'updateUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = \User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			\Hooks::run( 'UserLoggedIn', [ $user ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $plugin ) . '::updateUser() tried to replace $user!',
				$ex->getMessage()
			);
		}
	}

	public function testOnLocalUserCreated() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->exactly( 2 ) )->method( 'initUser' )
			->with( $this->identicalTo( $user ), $this->identicalTo( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		\Hooks::run( 'LocalUserCreated', [ $user, false ] );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'initUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = \User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			\Hooks::run( 'LocalUserCreated', [ $user, false ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				get_class( $plugin ) . '::initUser() tried to replace $user!',
				$ex->getMessage()
			);
		}
	}

	public function testGetUniqueId() {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame(
			'MediaWiki\\Auth\\AuthPluginPrimaryAuthenticationProvider:' . get_class( $plugin ),
			$provider->getUniqueId()
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 * @param bool $allowPasswordChange
	 */
	public function testGetAuthenticationRequests( $action, $response, $allowPasswordChange ) {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'allowPasswordChange' )
			->will( $this->returnValue( $allowPasswordChange ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		$arr = [ new PasswordAuthenticationRequest() ];
		return [
			[ AuthManager::ACTION_LOGIN, $arr, true ],
			[ AuthManager::ACTION_LOGIN, $arr, false ],
			[ AuthManager::ACTION_CREATE, $arr, true ],
			[ AuthManager::ACTION_CREATE, $arr, false ],
			[ AuthManager::ACTION_LINK, [], true ],
			[ AuthManager::ACTION_LINK, [], false ],
			[ AuthManager::ACTION_CHANGE, $arr, true ],
			[ AuthManager::ACTION_CHANGE, [], false ],
			[ AuthManager::ACTION_REMOVE, $arr, true ],
			[ AuthManager::ACTION_REMOVE, [], false ],
		];
	}

	public function testAuthentication() {
		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_LOGIN;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( [] )
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

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'Foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass( 'Foo', $req ),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$pluginUser = $this->getMockBuilder( \AuthPluginUser::class )
			->setMethods( [ 'isLocked' ] )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->will( $this->returnValue( true ) );
		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'getUserInstance', 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->will( $this->returnValue( $pluginUser ) );
		$plugin->expects( $this->never() )->method( 'authenticate' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'Foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAuthentication( $reqs )
		);

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'authenticate', 'strict' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'Foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->any() )->method( 'strict' )->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'authenticate', 'strictUserAuth' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'Foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->any() )->method( 'strictUserAuth' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ] )
			->getMock();
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( [ 'Domain1', 'Domain2' ] ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, [ 'Domain1', 'Domain2' ] );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'Foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $req ) = $provider->getAuthenticationRequests( AuthManager::ACTION_LOGIN, [] );
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain2';
		$provider->beginPrimaryAuthentication( [ $req ] );
	}

	public function testTestUserExists() {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->testUserExists( 'foo' ) );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertFalse( $provider->testUserExists( 'foo' ) );
	}

	public function testTestUserCanAuthenticate() {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'getUserInstance' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( \AuthPluginUser::class )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->will( $this->returnValue( true ) );
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) {
				$this->assertInstanceOf( \User::class, $user );
				$this->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->will( $this->returnValue( $pluginUser ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( \AuthPluginUser::class )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->will( $this->returnValue( false ) );
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'Foo' ) )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) {
				$this->assertInstanceOf( \User::class, $user );
				$this->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->will( $this->returnValue( $pluginUser ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertTrue( $provider->testUserCanAuthenticate( 'foo' ) );
	}

	public function testProviderRevokeAccessForUser() {
		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'userExists', 'setPassword' ] )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )->willReturn( true );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->identicalTo( null ) )
			->willReturn( true );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerRevokeAccessForUser( 'foo' );

		$plugin = $this->getMockBuilder( \AuthPlugin::class )
			->setMethods( [ 'domainList', 'userExists', 'setPassword' ] )
			->getMock();
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [ 'D1', 'D2', 'D3' ] );
		$plugin->expects( $this->exactly( 3 ) )->method( 'userExists' )
			->willReturnCallback( function () use ( $plugin ) {
				return $plugin->getDomain() !== 'D2';
			} );
		$plugin->expects( $this->exactly( 2 ) )->method( 'setPassword' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->identicalTo( null ) )
			->willReturnCallback( function () use ( $plugin ) {
				$this->assertNotEquals( 'D2', $plugin->getDomain() );
				return $plugin->getDomain() !== 'D1';
			} );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->providerRevokeAccessForUser( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
			$this->assertSame(
				'AuthPlugin failed to reset password for Foo in the following domains: D1',
				$ex->getMessage()
			);
		}
	}

	public function testProviderAllowsPropertyChange() {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'allowPropChange' )
			->will( $this->returnCallback( function ( $prop ) {
				return $prop === 'allow';
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->providerAllowsPropertyChange( 'allow' ) );
		$this->assertFalse( $provider->providerAllowsPropertyChange( 'deny' ) );
	}

	/**
	 * @dataProvider provideProviderAllowsAuthenticationDataChange
	 * @param string $type
	 * @param bool|null $allow
	 * @param StatusValue $expect
	 */
	public function testProviderAllowsAuthenticationDataChange( $type, $allow, $expect ) {
		$domains = $type instanceof PasswordDomainAuthenticationRequest ? [ 'foo', 'bar' ] : [];
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( $domains );
		$plugin->expects( $allow === null ? $this->never() : $this->once() )
			->method( 'allowPasswordChange' )->will( $this->returnValue( $allow ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->willReturnCallback( function ( $d ) use ( $domains ) {
				return in_array( $d, $domains, true );
			} );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		if ( is_object( $type ) ) {
			$req = $type;
		} else {
			$req = $this->createMock( $type );
		}
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = 'UTSysop';
		$req->password = 'Pa$$w0Rd!!!';
		$req->retype = 'Pa$$w0Rd!!!';
		$this->assertEquals( $expect, $provider->providerAllowsAuthenticationDataChange( $req ) );
	}

	public static function provideProviderAllowsAuthenticationDataChange() {
		$domains = [ 'foo', 'bar' ];
		$reqNoDomain = new PasswordDomainAuthenticationRequest( $domains );
		$reqValidDomain = new PasswordDomainAuthenticationRequest( $domains );
		$reqValidDomain->domain = 'foo';
		$reqInvalidDomain = new PasswordDomainAuthenticationRequest( $domains );
		$reqInvalidDomain->domain = 'invalid';

		return [
			[ AuthenticationRequest::class, null, \StatusValue::newGood( 'ignored' ) ],
			[ new PasswordAuthenticationRequest, true, \StatusValue::newGood() ],
			[
				new PasswordAuthenticationRequest,
				false,
				\StatusValue::newFatal( 'authmanager-authplugin-setpass-denied' )
			],
			[ $reqNoDomain, true, \StatusValue::newGood( 'ignored' ) ],
			[ $reqValidDomain, true, \StatusValue::newGood() ],
			[
				$reqInvalidDomain,
				true,
				\StatusValue::newFatal( 'authmanager-authplugin-setpass-bad-domain' )
			],
		];
	}

	public function testProviderChangeAuthenticationData() {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->never() )->method( 'setPassword' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData(
			$this->createMock( AuthenticationRequest::class )
		);

		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_CHANGE;
		$req->username = 'foo';
		$req->password = 'bar';

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->providerChangeAuthenticationData( $req );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \ErrorPageError $e ) {
			$this->assertSame( 'authmanager-authplugin-setpass-failed-title', $e->title );
			$this->assertSame( 'authmanager-authplugin-setpass-failed-message', $e->msg );
		}

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( [ 'Domain1', 'Domain2' ] ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, [ 'Domain1', 'Domain2' ] );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $req ) = $provider->getAuthenticationRequests( AuthManager::ACTION_CREATE, [] );
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain2';
		$provider->providerChangeAuthenticationData( $req );
	}

	/**
	 * @dataProvider provideAccountCreationType
	 * @param bool $can
	 * @param string $expect
	 */
	public function testAccountCreationType( $can, $expect ) {
		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->once() )
			->method( 'canCreateAccounts' )->will( $this->returnValue( $can ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertSame( $expect, $provider->accountCreationType() );
	}

	public static function provideAccountCreationType() {
		return [
			[ true, PrimaryAuthenticationProvider::TYPE_CREATE ],
			[ false, PrimaryAuthenticationProvider::TYPE_NONE ],
		];
	}

	public function testTestForAccountCreation() {
		$user = \User::newFromName( 'foo' );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, [] )
		);
	}

	public function testAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$user->setEmail( 'email' );
		$user->setRealName( 'realname' );

		$req = new PasswordAuthenticationRequest();
		$req->action = AuthManager::ACTION_CREATE;
		$reqs = [ PasswordAuthenticationRequest::class => $req ];

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'addUser' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->beginPrimaryAccountCreation( $user, $user, [] );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->never() )->method( 'addUser' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, [] )
		);

		$req->username = 'foo';
		$req->password = null;
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, $reqs )
		);

		$req->username = null;
		$req->password = 'bar';
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginPrimaryAccountCreation( $user, $user, $reqs )
		);

		$req->username = 'foo';
		$req->password = 'bar';

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->callback( function ( $u ) {
					return $u instanceof \User && $u->getName() === 'Foo';
				} ),
				$this->equalTo( 'bar' ),
				$this->equalTo( 'email' ),
				$this->equalTo( 'realname' )
			)
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginPrimaryAccountCreation( $user, $user, $reqs )
		);

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'domainList' )->willReturn( [] );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->callback( function ( $u ) {
					return $u instanceof \User && $u->getName() === 'Foo';
				} ),
				$this->equalTo( 'bar' ),
				$this->equalTo( 'email' ),
				$this->equalTo( 'realname' )
			)
			->will( $this->returnValue( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAccountCreation( $user, $user, $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-authplugin-create-fail', $ret->message->getKey() );

		$plugin = $this->createMock( \AuthPlugin::class );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( [ 'Domain1', 'Domain2' ] ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, [ 'Domain1', 'Domain2' ] );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->callback( function ( $u ) {
				return $u instanceof \User && $u->getName() === 'Foo';
			} ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $req ) = $provider->getAuthenticationRequests( AuthManager::ACTION_CREATE, [] );
		$req->username = 'foo';
		$req->password = 'bar';
		$req->domain = 'Domain2';
		$provider->beginPrimaryAccountCreation( $user, $user, [ $req ] );
	}

}
