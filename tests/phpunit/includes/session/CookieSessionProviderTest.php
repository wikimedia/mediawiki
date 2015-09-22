<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\CookieSessionProvider
 * @uses MediaWiki\Session\SessionProvider
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

		$config = $this->getConfig();
		$p = new CookieSessionProvider( array( 'priority' => 1 ) );
		$p->setLogger( new \Psr\Log\NullLogger() );
		$p->setConfig( $config );
		$this->assertEquals( array(
			'priority' => 1,
			'callUserSetCookiesHook' => false,
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
			'sessionName' => 'CookiePrefix_session',
		), \TestingAccessWrapper::newFromObject( $p )->params );

		$config->set( 'SessionName', 'SessionName' );
		$p = new CookieSessionProvider( array( 'priority' => 1 ) );
		$p->setLogger( new \Psr\Log\NullLogger() );
		$p->setConfig( $config );
		$this->assertEquals( array(
			'priority' => 1,
			'callUserSetCookiesHook' => false,
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => true,
			'httpOnly' => true,
			'sessionName' => 'SessionName',
		), \TestingAccessWrapper::newFromObject( $p )->params );

		$vals = array(
			'priority' => 10,
			'callUserSetCookiesHook' => true,
			'prefix' => 'XPrefix',
			'path' => 'XPath',
			'domain' => 'XDomain',
			'secure' => 'XSecure',
			'httpOnly' => 'XHttpOnly',
			'sessionName' => 'XSession',
		);
		$p = new CookieSessionProvider( $vals );
		$p->setLogger( new \Psr\Log\NullLogger() );
		$p->setConfig( $config );
		$this->assertEquals( $vals, \TestingAccessWrapper::newFromObject( $p )->params );
	}

	/**
	 * @uses MediaWiki\Session\SessionInfo
	 * @uses MediaWiki\Session\UserInfo
	 * @uses MediaWiki\Session\SessionManager::__construct
	 * @uses MediaWiki\Session\SessionManager::setLogger
	 * @uses MediaWiki\Session\SessionManager::validateSessionId
	 * @uses MediaWiki\Session\SessionManager::generateSessionId
	 */
	public function testProvideSessionInfo() {
		$params = array(
			'priority' => 20,
			'sessionName' => 'session',
			'prefix' => 'x',
		);
		$provider = new CookieSessionProvider( $params );
		$provider->setLogger( new \Psr\Log\NullLogger() );
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
		$this->assertNull( $info->getUser() );
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
		$this->assertNotNull( $info->getUser() );
		$this->assertSame( $id, $info->getUser()->getId() );
		$this->assertSame( $name, $info->getUser()->getName() );
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
		$this->assertNotNull( $info->getUser() );
		$this->assertSame( $id, $info->getUser()->getId() );
		$this->assertSame( $name, $info->getUser()->getName() );
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
		$this->assertNotNull( $info->getUser() );
		$this->assertFalse( $info->getUser()->isAuthenticated() );
		$this->assertSame( $id, $info->getUser()->getId() );
		$this->assertSame( $name, $info->getUser()->getName() );
		$this->assertFalse( $info->forceHTTPS() );

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
		$this->assertNotNull( $info->getUser() );
		$this->assertSame( $id, $info->getUser()->getId() );
		$this->assertSame( $name, $info->getUser()->getName() );
		$this->assertTrue( $info->forceHTTPS() );

		// Invalid user id
		$request = new \FauxRequest();
		$request->setCookies( array(
			'session' => $sessionId,
			'xUserID' => '-1',
		), '' );
		$info = $provider->provideSessionInfo( $request );
		$this->assertNull( $info );
	}

	public function testGetVaryCookies() {
		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'prefix' => 'MyCookiePrefix',
			'sessionName' => 'MySessionName',
		) );
		$this->assertArrayEquals( array(
			'MyCookiePrefixToken',
			'MySessionName',
			'forceHTTPS',
		), $provider->getVaryCookies() );
	}

	public function testSuggestLoginUsername() {
		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'prefix' => 'x',
			'sessionName' => 'MySessionName',
		) );

		$request = new \FauxRequest();
		$this->assertEquals( null, $provider->suggestLoginUsername( $request ) );

		$request->setCookies( array(
			'xUserName' => 'Example',
		), '' );
		$this->assertEquals( 'Example', $provider->suggestLoginUsername( $request ) );
	}

	/**
	 * @uses MediaWiki\Session\SessionBackend
	 * @uses MediaWiki\Session\SessionInfo
	 * @uses MediaWiki\Session\SessionManager::validateSessionId
	 */
	public function testPersistSession() {
		$this->setMwGlobals( array( 'wgCookieExpiration' => 100 ) );

		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'prefix' => 'x',
			'sessionName' => 'MySessionName',
			'callUserSetCookiesHook' => false,
		) );
		$config = $this->getConfig();
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $config );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$store = new \HashBagOStuff();
		$user = User::newFromName( 'UTSysop' );
		$anon = new User;

		$backend = new SessionBackend(
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
			) ),
			$store,
			10
		);

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
		$this->assertSame( array(), $backend->getData() );

		// Validate cookie data for this one
		$defaults = array(
			'expire' => (int)100,
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
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
				'value' => $user->getToken(),
				'expire' => $extendedExpiry,
			) + $defaults,
			'forceHTTPS' => array(
				'value' => 'true',
				'secure' => false,
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

	/**
	 * @uses MediaWiki\Session\SessionBackend
	 * @uses MediaWiki\Session\SessionInfo
	 * @uses MediaWiki\Session\SessionManager::validateSessionId
	 */
	public function testPersistSessionWithHook() {
		$that = $this;

		$provider = new CookieSessionProvider( array(
			'priority' => 1,
			'prefix' => 'x',
			'sessionName' => 'MySessionName',
			'callUserSetCookiesHook' => true,
		) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $this->getConfig() );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$store = new \HashBagOStuff();
		$user = User::newFromName( 'UTSysop' );
		$anon = new User;

		$backend = new SessionBackend(
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
			) ),
			$store,
			10
		);

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
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( null, $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( 'bar!', $request->response()->getCookie( 'xbar' ) );
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
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $sessionId, $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( (string)$user->getId(), $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( $user->getName(), $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( $user->getToken(), $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( 'true', $request->response()->getCookie( 'forceHTTPS' ) );
		$this->assertSame( 'bar 2!', $request->response()->getCookie( 'xbar' ) );
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
			'prefix' => 'x',
			'sessionName' => 'MySessionName',
		) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setConfig( $this->getConfig() );

		$request = new \FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( '', $request->response()->getCookie( 'MySessionName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xUserID' ) );
		$this->assertSame( null, $request->response()->getCookie( 'xUserName' ) );
		$this->assertSame( '', $request->response()->getCookie( 'xToken' ) );
		$this->assertSame( '', $request->response()->getCookie( 'forceHTTPS' ) );

		$provider->unpersistSession( $this->getSentRequest() );
	}

	/**
	 * To be mocked for hooks, since PHPUnit can't otherwise mock methods that
	 * take references.
	 */
	public function onUserSetCookies( $user, &$sessionData, &$cookies ) {
	}

}
