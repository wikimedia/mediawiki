<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\CookieSessionProvider
 */
class CookieSessionProviderTest extends MediaWikiTestCase {

	private function getConfig() {
		global $wgCookieExpiration;
		return new \HashConfig( array(
			'CookiePrefix' => 'CookiePrefix',
			'CookiePath' => 'CookiePath',
			'CookieDomain' => 'CookieDomain',
			'CookieSecure' => true,
			'CookieHttpOnly' => true,
			'SessionName' => false,
			'ExtendedLoginCookies' => array( 'UserID', 'Token' ),
			'ExtendedLoginCookieExpiration' => $wgCookieExpiration * 2,
		) );
	}

	public function testConstructor() {
		try {
			new CookieSessionProvider();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider( array( 'priority' => 'foo' ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider( array( 'priority' => SessionInfo::MIN_PRIORITY - 1 ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}
		try {
			new CookieSessionProvider( array( 'priority' => SessionInfo::MAX_PRIORITY + 1 ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: Invalid priority',
				$ex->getMessage()
			);
		}

		try {
			new CookieSessionProvider( array( 'priority' => 1, 'cookieOptions' => null ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\CookieSessionProvider::__construct: cookieOptions must be an array',
				$ex->getMessage()
			);
		}

		$config = $this->getConfig();
		$p = \TestingAccessWrapper::newFromObject(
			new CookieSessionProvider( array( 'priority' => 1 ) )
		);
		$p->setLogger( new \TestLogger() );
		$p->setConfig( $config );
		$this->assertEquals( 1, $p->priority );
		$this->assertEquals( array(
			'callUserSetCookiesHook' => false,
			'sessionName' => 'CookiePrefix_session',
		), $p->params );
		$this->assertEquals( array(
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
		), $p->cookieOptions );

		$config->set( 'SessionName', 'SessionName' );
		$p = \TestingAccessWrapper::newFromObject(
			new CookieSessionProvider( array( 'priority' => 3 ) )
		);
		$p->setLogger( new \TestLogger() );
		$p->setConfig( $config );
		$this->assertEquals( 3, $p->priority );
		$this->assertEquals( array(
			'callUserSetCookiesHook' => false,
			'sessionName' => 'SessionName',
		), $p->params );
		$this->assertEquals( array(
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
		), $p->cookieOptions );

		$p = \TestingAccessWrapper::newFromObject( new CookieSessionProvider( array(
			'priority' => 10,
			'callUserSetCookiesHook' => true,
			'cookieOptions' => array(
				'prefix' => 'XPrefix',
				'path' => 'XPath',
				'domain' => 'XDomain',
				'secure' => 'XSecure',
				'httpOnly' => 'XHttpOnly',
			),
			'sessionName' => 'XSession',
		) ) );
		$p->setLogger( new \TestLogger() );
		$p->setConfig( $config );
		$this->assertEquals( 10, $p->priority );
		$this->assertEquals( array(
			'callUserSetCookiesHook' => true,
			'sessionName' => 'XSession',
		), $p->params );
		$this->assertEquals( array(
			'prefix' => 'XPrefix',
			'path' => 'XPath',
			'domain' => 'XDomain',
			'secure' => 'XSecure',
			'httpOnly' => 'XHttpOnly',
		), $p->cookieOptions );
	}

	public function testBasics() {
		$provider = new CookieSessionProvider( array( 'priority' => 10 ) );

		$this->assertTrue( $provider->persistsSessionID() );
		$this->assertTrue( $provider->canChangeUser() );

		$msg = $provider->whyNoSession();
		$this->assertInstanceOf( 'Message', $msg );
		$this->assertSame( 'sessionprovider-nocookies', $msg->getKey() );
	}

	public function testProvideSessionInfo() {
		$params = array(
			'priority' => 20,
			'sessionName' => 'session',
			'cookieOptions' => array( 'prefix' => 'x' ),
		);
		$provider = new CookieSessionProvider( $params );
		$provider->setLogger( new \TestLogger() );
		$provider->setConfig( $this->getConfig() );
		$provider->setManager( new SessionManager() );

		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();
		$token = $user->getToken( true );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		// No data
		$request = new \FauxRequest();
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );

		// Session key only
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNull( $info->getUserInfo() );
		$this->assertFalse( $info->forceHTTPS() );

		// User, no session key
		$request = new \FauxRequest();
		$request->setCookies( array(
			'xUserID' => $id,
			'xToken' => $token,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertNotSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );

		// User and session key
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => $token,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );

		// User with bad token
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => 'BADTOKEN',
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );

		// User id with no token
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertFalse( $info->getUserInfo()->isVerified() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );

		$request = new \FauxRequest();
		$request->setCookies( array(
			'xUserID' => $id,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );

		// User and session key, with forceHTTPS flag
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
			'xToken' => $token,
			'forceHTTPS' => true,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertTrue( $info->forceHTTPS() );

		// Invalid user id
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => '-1',
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );

		// User id with matching name
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
			'xUserName' => $name,
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info );
		$this->assertSame( $params['priority'], $info->getPriority() );
		$this->assertSame( $sessionId, $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertFalse( $info->getUserInfo()->isVerified() );
		$this->assertSame( $id, $info->getUserInfo()->getId() );
		$this->assertSame( $name, $info->getUserInfo()->getName() );
		$this->assertFalse( $info->forceHTTPS() );

		// User id with wrong name
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => $id,
			'xUserName' => 'Wrong',
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
	}

	public function testGetVaryCookies() {
		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => array( 'prefix' => 'MyCookiePrefix' ),
		) );
		$this->assertArrayEquals( array(
			'MyCookiePrefixToken',
			'MyCookiePrefixLoggedOut',
			'MySessionName',
			'forceHTTPS',
		), $provider->getVaryCookies() );
	}

	public function testSuggestLoginUsername() {
		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => array( 'prefix' => 'x' ),
		) );

		$request = new \FauxRequest();
		$this->assertEquals( null, $provider->suggestLoginUsername( $request ) );

		$request->setCookies( array(
			'xUserName' => 'Example',
		), '' );
		$this->assertEquals( 'Example', $provider->suggestLoginUsername( $request ) );
	}

	public function testPersistSession() {
		$this->setMwGlobals( array( 'wgCookieExpiration' => 100 ) );

		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'callUserSetCookiesHook' => false,
			'cookieOptions' => array( 'prefix' => 'x' ),
		) );
		$config = $this->getConfig();
		$provider->setLogger( new \TestLogger() );
		$provider->setConfig( $config );
		$provider->setManager( SessionManager::singleton() );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$store = new \HashBagOStuff();
		$user = User::newFromName( 'UTSysop' );
		$anon = new User;

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			) ),
			$store,
			new \Psr\Log\NullLogger(),
			10
		);
		\TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;

		$mock = $this->getMock( 'stdClass', array( 'onUserSetCookies' ) );
		$mock->expects( $this->never() )->method( 'onUserSetCookies' );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'UserSetCookies' => array( $mock ) ) );

		// Anonymous user
		$backend->setUser( $anon );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( false );
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( null, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( null, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( array(), $backend->getData() );

		// Logged-in user, no remember
		$backend->setUser( $user );
		$backend->setRememberUser( false );
		$backend->setForceHTTPS( false );
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( null, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( array(), $backend->getData() );

		// Logged-in user, remember
		$backend->setUser( $user );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( true );
		$request = new \FauxRequest();
		$time = time();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( $user->getToken(), $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( 'true', $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( array(), $backend->getData() );
	}

	/**
	 * @dataProvider provideCookieData
	 * @param bool $secure
	 * @param bool $remember
	 */
	public function testCookieData( $secure, $remember ) {
		$this->setMwGlobals( array(
			'wgCookieExpiration' => 100,
			'wgSecureLogin' => false,
		) );

		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'callUserSetCookiesHook' => false,
			'cookieOptions' => array( 'prefix' => 'x' ),
		) );
		$config = $this->getConfig();
		$config->set( 'CookieSecure', false );
		$provider->setLogger( new \TestLogger() );
		$provider->setConfig( $config );
		$provider->setManager( SessionManager::singleton() );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = User::newFromName( 'UTSysop' );
		$this->assertFalse( $user->requiresHTTPS(), 'sanity check' );

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			) ),
			new \EmptyBagOStuff(),
			new \Psr\Log\NullLogger(),
			10
		);
		\TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;
		$backend->setUser( $user );
		$backend->setRememberUser( $remember );
		$backend->setForceHTTPS( $secure );
		$request = new \FauxRequest();
		$time = time();
		$provider->persistSession( $backend, $request );

		$defaults = array(
			'expire' => (int)100,
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $secure,
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
			'raw' => false,
		);
		$extendedExpiry = $config->get( 'ExtendedLoginCookieExpiration' );
		$extendedExpiry = (int)( $extendedExpiry === null ? 0 : $extendedExpiry );
		$this->assertEquals( array( 'UserID', 'Token' ), $config->get( 'ExtendedLoginCookies' ),
			'sanity check' );
		$expect = array(
			'MySessionName' => array(
				'value' => (string)$sessionId,
				'expire' => 0,
			) + $defaults,
			'xUserID' => array(
				'value' => (string)$user->getId(),
				'expire' => $extendedExpiry,
			) + $defaults,
			'xUserName' => array(
				'value' => $user->getName(),
			) + $defaults,
			'xToken' => array(
				'value' => $remember ? $user->getToken() : '',
				'expire' => $remember ? $extendedExpiry : -31536000,
			) + $defaults,
			'forceHTTPS' => !$secure ? null : array(
				'value' => 'true',
				'secure' => false,
				'expire' => $remember ? $defaults['expire'] : null,
			) + $defaults,
		);
		foreach ( $expect as $key => $value ) {
			$actual = $request->response()->getCookieData( $key );
			if ( $actual && $actual['expire'] > 0 ) {
				// Round expiry so we don't randomly fail if the seconds ticked during the test.
				$actual['expire'] = round( $actual['expire'] - $time, -2 );
			}
			$this->assertEquals( $value, $actual, "Cookie $key" );
		}
	}

	public static function provideCookieData() {
		return array(
			array( false, false ),
			array( false, true ),
			array( true, false ),
			array( true, true ),
		);
	}

	protected function getSentRequest() {
		$sentResponse = $this->getMock( 'FauxResponse', array( 'headersSent', 'setCookie', 'header' ) );
		$sentResponse->expects( $this->any() )->method( 'headersSent' )
			->will( $this->returnValue( true ) );
		$sentResponse->expects( $this->never() )->method( 'setCookie' );
		$sentResponse->expects( $this->never() )->method( 'header' );

		$sentRequest = $this->getMock( 'FauxRequest', array( 'response' ) );
		$sentRequest->expects( $this->any() )->method( 'response' )
			->will( $this->returnValue( $sentResponse ) );
		return $sentRequest;
	}

	public function testPersistSessionWithHook() {
		$that = $this;

		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'callUserSetCookiesHook' => true,
			'cookieOptions' => array( 'prefix' => 'x' ),
		) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $this->getConfig() );
		$provider->setManager( SessionManager::singleton() );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$store = new \HashBagOStuff();
		$user = User::newFromName( 'UTSysop' );
		$anon = new User;

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'idIsSafe' => true,
			) ),
			$store,
			new \Psr\Log\NullLogger(),
			10
		);
		\TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;

		// Anonymous user
		$mock = $this->getMock( 'stdClass', array( 'onUserSetCookies' ) );
		$mock->expects( $this->never() )->method( 'onUserSetCookies' );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'UserSetCookies' => array( $mock ) ) );
		$backend->setUser( $anon );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( false );
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( null, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( null, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( array(), $backend->getData() );

		$provider->persistSession( $backend, $this->getSentRequest() );

		// Logged-in user, no remember
		$mock = $this->getMock( __CLASS__, array( 'onUserSetCookies' ) );
		$mock->expects( $this->once() )->method( 'onUserSetCookies' )
			->will( $this->returnCallback( function ( $u, &$sessionData, &$cookies ) use ( $that, $user ) {
				$that->assertSame( $user, $u );
				$that->assertEquals( array(
					'wsUserID' => $user->getId(),
					'wsUserName' => $user->getName(),
					'wsToken' => $user->getToken(),
				), $sessionData );
				$that->assertEquals( array(
					'UserID' => $user->getId(),
					'UserName' => $user->getName(),
					'Token' => false,
				), $cookies );

				$sessionData['foo'] = 'foo!';
				$cookies['bar'] = 'bar!';
				return true;
			} ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'UserSetCookies' => array( $mock ) ) );
		$backend->setUser( $user );
		$backend->setRememberUser( false );
		$backend->setForceHTTPS( false );
		$backend->setLoggedOutTimestamp( $loggedOut = time() );
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( null, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( 'bar!', $request->response()->getCookie( 'xbar' ) );
		$this->assertSame( (string)$loggedOut, $request->response()->getCookie( 'xLoggedOut' ) );
		$this->assertEquals( array(
			'wsUserID' => $user->getId(),
			'wsUserName' => $user->getName(),
			'wsToken' => $user->getToken(),
			'foo' => 'foo!',
		), $backend->getData() );

		$provider->persistSession( $backend, $this->getSentRequest() );

		// Logged-in user, remember
		$mock = $this->getMock( __CLASS__, array( 'onUserSetCookies' ) );
		$mock->expects( $this->once() )->method( 'onUserSetCookies' )
			->will( $this->returnCallback( function ( $u, &$sessionData, &$cookies ) use ( $that, $user ) {
				$that->assertSame( $user, $u );
				$that->assertEquals( array(
					'wsUserID' => $user->getId(),
					'wsUserName' => $user->getName(),
					'wsToken' => $user->getToken(),
				), $sessionData );
				$that->assertEquals( array(
					'UserID' => $user->getId(),
					'UserName' => $user->getName(),
					'Token' => $user->getToken(),
				), $cookies );

				$sessionData['foo'] = 'foo 2!';
				$cookies['bar'] = 'bar 2!';
				return true;
			} ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array( 'UserSetCookies' => array( $mock ) ) );
		$backend->setUser( $user );
		$backend->setRememberUser( true );
		$backend->setForceHTTPS( true );
		$backend->setLoggedOutTimestamp( 0 );
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( $user->getToken(), $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( 'true', $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( 'bar 2!', $request->response()->getCookie( 'xbar' ) );
		$this->assertSame( null, $request->response()->getCookie( 'xLoggedOut' ) );
		$this->assertEquals( array(
			'wsUserID' => $user->getId(),
			'wsUserName' => $user->getName(),
			'wsToken' => $user->getToken(),
			'foo' => 'foo 2!',
		), $backend->getData() );

		$provider->persistSession( $backend, $this->getSentRequest() );
	}

	public function testUnpersistSession() {
		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => array( 'prefix' => 'x' ),
		) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $this->getConfig() );
		$provider->setManager( SessionManager::singleton() );

		$request = new \FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( '', $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( null, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( '', $request->response()->getCookie( 'forceHTTPS' ) );

		$provider->unpersistSession( $this->getSentRequest() );
	}

	public function testSetLoggedOutCookie() {
		$provider = \TestingAccessWrapper::newFromObject( new CookieSessionProvider( array(
			'priority' => 1,
			'sessionName' => 'MySessionName',
			'cookieOptions' => array( 'prefix' => 'x' ),
		) ) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $this->getConfig() );
		$provider->setManager( SessionManager::singleton() );

		$t1 = time();
		$t2 = time() - 86400 * 2;

		// Set it
		$request = new \FauxRequest();
		$provider->setLoggedOutCookie( $t1, $request );
		$this->assertSame( (string)$t1, $request->response()->getCookie( 'xLoggedOut' ) );

		// Too old
		$request = new \FauxRequest();
		$provider->setLoggedOutCookie( $t2, $request );
		$this->assertSame( null, $request->response()->getCookie( 'xLoggedOut' ) );

		// Don't reset if it's already set
		$request = new \FauxRequest();
		$request->setCookies( array(
			'xLoggedOut' => $t1,
		), '' );
		$provider->setLoggedOutCookie( $t1, $request );
		$this->assertSame( null, $request->response()->getCookie( 'xLoggedOut' ) );
	}

	/**
	 * To be mocked for hooks, since PHPUnit can't otherwise mock methods that
	 * take references.
	 */
	public function onUserSetCookies( $user, &$sessionData, &$cookies ) {
	}

}
