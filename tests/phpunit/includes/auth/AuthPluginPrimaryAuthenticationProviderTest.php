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

		$plugin = $this->getMock( 'AuthPlugin' );

		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame(
			array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ),
			$provider->getAuthenticationRequestTypes( AuthManager::ACTION_ALL )
		);

		$reqType = get_class( $this->getMock( 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin, $reqType );
		$this->assertSame(
			array( $reqType ),
			$provider->getAuthenticationRequestTypes( AuthManager::ACTION_ALL )
		);

		$reqType = get_class( $this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' ) );
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

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateExternalDB' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		\Hooks::run( 'UserSaveSettings', array( $user ) );
	}

	public function testOnUserGroupsChanged() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateExternalDBGroups' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( array( 'added' ) ),
				$this->identicalTo( array( 'removed' ) )
			);
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		\Hooks::run( 'UserGroupsChanged', array( $user, array( 'added' ), array( 'removed' ) ) );
	}

	public function testOnUserLoggedIn() {
		$user = \User::newFromName( 'UTSysop' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->exactly( 2 ) )->method( 'updateUser' )
			->with( $this->identicalTo( $user ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		\Hooks::run( 'UserLoggedIn', array( $user ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'updateUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = \User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			\Hooks::run( 'UserLoggedIn', array( $user ) );
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

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->exactly( 2 ) )->method( 'initUser' )
			->with( $this->identicalTo( $user ), $this->identicalTo( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		\Hooks::run( 'LocalUserCreated', array( $user, false ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'initUser' )
			->will( $this->returnCallback( function ( &$user ) {
				$user = \User::newFromName( 'UTSysop' );
			} ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			\Hooks::run( 'LocalUserCreated', array( $user, false ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \UnexpectedValueException $ex ) {
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
			'MediaWiki\\Auth\\AuthPluginPrimaryAuthenticationProvider:' . get_class( $plugin ),
			$provider->getUniqueId()
		);
	}

	/**
	 * @dataProvider provideGetAuthenticationRequestTypes
	 * @param string $action
	 * @param array $response
	 * @param bool $allowPasswordChange
	 */
	public function testGetAuthenticationRequestTypes( $action, $response, $allowPasswordChange ) {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'allowPasswordChange' )
			->will( $this->returnValue( $allowPasswordChange ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertSame( $response, $provider->getAuthenticationRequestTypes( $action ) );
	}

	public static function provideGetAuthenticationRequestTypes() {
		$arr = array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest' );
		return array(
			array( AuthManager::ACTION_LOGIN, $arr, true ),
			array( AuthManager::ACTION_LOGIN, $arr, false ),
			array( AuthManager::ACTION_CREATE, $arr, true ),
			array( AuthManager::ACTION_CREATE, $arr, false ),
			array( AuthManager::ACTION_CHANGE, $arr, true ),
			array( AuthManager::ACTION_CHANGE, array(), false ),
			array( AuthManager::ACTION_ALL, $arr, true ),
			array( AuthManager::ACTION_ALL, $arr, false ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, array(), true ),
			array( AuthManager::ACTION_LOGIN_CONTINUE, array(), false ),
			array( AuthManager::ACTION_CREATE_CONTINUE, array(), true ),
			array( AuthManager::ACTION_CREATE_CONTINUE, array(), false ),
			array( AuthManager::ACTION_LINK, array(), true ),
			array( AuthManager::ACTION_LINK, array(), false ),
			array( AuthManager::ACTION_LINK_CONTINUE, array(), true ),
			array( AuthManager::ACTION_LINK_CONTINUE, array(), false ),
		);
	}

	public function testAuthentication() {
		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'authenticate', 'setDomain' ) )
			->getMock();
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
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
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
			->will( $this->returnValue( false ) );
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
			->will( $this->returnValue( true ) );
		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'getUserInstance', 'authenticate', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->will( $this->returnValue( $pluginUser ) );
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
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
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
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->any() )->method( 'strict' )->will( $this->returnValue( true ) );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'userExists', 'authenticate', 'strictUserAuth', 'setDomain' ) )
			->getMock();
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->any() )->method( 'strictUserAuth' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAuthentication( $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );

		$plugin = $this->getMockBuilder( 'AuthPlugin' )
			->setMethods( array( 'domainList', 'validDomain', 'setDomain', 'userExists', 'authenticate' ) )
			->getMock();
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'authenticate' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo@Domain3';
		$req->password = 'bar';
		$provider->beginPrimaryAuthentication( array( $reqType => $req ) );
	}

	public function testTestUserExists() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertTrue( $provider->testUserExists( 'foo' ) );
	}

	public function testTestUserCanAuthenticate() {
		$that = $this;

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'getUserInstance' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( 'AuthPluginUser' )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->will( $this->returnValue( true ) );
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) use ( $that ) {
				$that->assertInstanceOf( 'User', $user );
				$that->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->will( $this->returnValue( $pluginUser ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertFalse( $provider->testUserCanAuthenticate( 'foo' ) );

		$pluginUser = $this->getMockBuilder( 'AuthPluginUser' )
			->disableOriginalConstructor()
			->getMock();
		$pluginUser->expects( $this->once() )->method( 'isLocked' )
			->will( $this->returnValue( false ) );
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->once() )->method( 'userExists' )
			->with( $this->equalTo( 'foo' ) )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'getUserInstance' )
			->with( $this->callback( function ( $user ) use ( $that ) {
				$that->assertInstanceOf( 'User', $user );
				$that->assertEquals( 'Foo', $user->getName() );
				return true;
			} ) )
			->will( $this->returnValue( $pluginUser ) );
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
		$plugin->expects( $this->any() )->method( 'allowPropChange' )
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
			->method( 'allowPasswordChange' )->will( $this->returnValue( $allow ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		$this->assertSame( $expect, $provider->providerAllowsAuthenticationDataChangeType( $type ) );
	}

	public static function provideProviderAllowsAuthenticationDataChangeType() {
		return array(
			array( 'MediaWiki\\Auth\\AuthenticationRequest', null, true ),
			array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', true, true ),
			array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest', false, false ),
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
			->method( 'allowPasswordChange' )->will( $this->returnValue( $allow ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );

		if ( $expect ) {
			$expect = \StatusValue::newGood();
		} else {
			$expect = \StatusValue::newFatal( 'authmanager-authplugin-setpass-fail' );
		}

		if ( $type === 'MediaWiki\\Auth\\PasswordAuthenticationRequest' ) {
			$req = new $type();
		} else {
			$req = $this->getMock( $type );
		}
		$req->username = 'UTSysop';
		$req->password = 'Password';
		$this->assertEquals( $expect, $provider->providerAllowsAuthenticationDataChange( $req ) );
	}

	public function testProviderChangeAuthenticationData() {
		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->never() )->method( 'setPassword' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData(
			$this->getMock( 'MediaWiki\\Auth\\AuthenticationRequest' )
		);

		$req = new PasswordAuthenticationRequest();
		$req->username = 'foo';
		$req->password = 'bar';

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( false ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->providerChangeAuthenticationData( $req );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \ErrorPageError $e ) {
			$this->assertSame( 'authmanager-authplugin-setpass-failed-title', $e->title );
			$this->assertSame( 'authmanager-authplugin-setpass-failed-message', $e->msg );
		}

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->providerChangeAuthenticationData( $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'setPassword' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
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
			->method( 'canCreateAccounts' )->will( $this->returnValue( $can ) );
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
		$user = \User::newFromName( 'foo' );

		$plugin = $this->getMock( 'AuthPlugin' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			\StatusValue::newGood(),
			$provider->testForAccountCreation( $user, $user, array() )
		);
	}

	public function testAccountCreation() {
		$user = \User::newFromName( 'foo' );
		$user->setEmail( 'email' );
		$user->setRealName( 'realname' );

		$req = new PasswordAuthenticationRequest();
		$reqs = array( 'MediaWiki\\Auth\\PasswordAuthenticationRequest' => $req );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'addUser' );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		try {
			$provider->beginPrimaryAccountCreation( $user, array() );
			$this->fail( 'Expected exception was not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'Shouldn\'t call this when accountCreationType() is NONE', $ex->getMessage()
			);
		}

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
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
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->equalTo( 'foo' ), $this->equalTo( 'bar' ),
				$this->equalTo( 'email' ), $this->equalTo( 'realname' )
			)
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$provider->beginPrimaryAccountCreation( $user, $reqs )
		);

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with(
				$this->equalTo( 'foo' ), $this->equalTo( 'bar' ),
				$this->equalTo( 'email' ), $this->equalTo( 'realname' )
			)
			->will( $this->returnValue( false ) );
		$plugin->expects( $this->never() )->method( 'setDomain' );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		$ret = $provider->beginPrimaryAccountCreation( $user, $reqs );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'authmanager-authplugin-create-fail', $ret->message->getKey() );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain2' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo@Domain2';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );

		$plugin = $this->getMock( 'AuthPlugin' );
		$plugin->expects( $this->any() )->method( 'canCreateAccounts' )
			->will( $this->returnValue( true ) );
		$plugin->expects( $this->any() )->method( 'domainList' )
			->will( $this->returnValue( array( 'Domain1', 'Domain2' ) ) );
		$plugin->expects( $this->any() )->method( 'validDomain' )
			->will( $this->returnCallback( function ( $domain ) {
				return in_array( $domain, array( 'Domain1', 'Domain2' ) );
			} ) );
		$plugin->expects( $this->once() )->method( 'setDomain' )
			->with( $this->equalTo( 'Domain1' ) );
		$plugin->expects( $this->once() )->method( 'addUser' )
			->with( $this->equalTo( 'foo@Domain3' ), $this->equalTo( 'bar' ) )
			->will( $this->returnValue( true ) );
		$provider = new AuthPluginPrimaryAuthenticationProvider( $plugin );
		list( $reqType ) = $provider->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$req = new $reqType();
		$req->username = 'foo@Domain3';
		$req->password = 'bar';
		$provider->beginPrimaryAccountCreation( $user, array( $reqType => $req ) );
	}

}
