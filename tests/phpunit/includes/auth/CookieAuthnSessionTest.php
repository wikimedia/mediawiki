<?php

/**
 * @group AuthManager
 * @group Database
 * @covers CookieAuthnSession
 * @uses AuthnSession
 */
class CookieAuthnSessionTest extends MediaWikiTestCase {

	/**
	 * Get test user info
	 * @return array ( $id, $name, $token )
	 */
	private function getUserInfo() {
		$user = User::newFromName( 'UTSysop' );
		return array( $user->getId(), $user->getName(), '!!TOKEN!!' );
	}

	/**
	 * Get an instance, possibly mocked
	 * @param string|null $loginMethod Source of user info: 'cookie', 'session', or 'both'
	 * @param array|null $mockMethods Methods to mock
	 * @return CookieAuthnSessionTest
	 */
	private function getSession( $loginMethod = null, $mockMethods = null ) {
		$request = new FauxRequest();
		$store = new HashBagOStuff();
		$logger = new Psr\Log\NullLogger();
		$params = array(
			'empty' => false,
			'sessionName' => 'session',
			'prefix' => 'x',
			'domain' => 'example.org',
			'path' => '/path',
			'secure' => true,
			'httpOnly' => true,
			'emptyPriority' => 1,
			'priority' => 20,
			'callUserSetCookiesHook' => false,
		);

		if ( $loginMethod !== null ) {
			list( $id, $name, $token ) = $this->getUserInfo();
			if ( $loginMethod === 'cookie' || $loginMethod === 'both' ) {
				$request->setCookies( array(
					'xUserID' => $id,
					'xUserName' => $name,
					'xToken' => $token,
				), '' );
			}
			if ( $loginMethod === 'session' || $loginMethod === 'both' ) {
				$request->setCookie( 'session', 'foobar', '' );
				$store->set( 'CookieAuthnSession:foobar:sess', array(
					'UserID' => $id,
					'UserName' => $name,
					'Token' => $token,
				) );
			}
		}

		if ( $mockMethods ) {
			$ret = $this->getMockBuilder( 'CookieAuthnSession' )
				->disableOriginalConstructor()
				->setMethods( $mockMethods )
				->getMock();
			$sess = $store->get( 'CookieAuthnSession:foobar:sess' );
			if ( $sess ) {
				$store->set( get_class( $ret ) . ':foobar:sess', $sess );
			}
			$ret->__construct( $request, $store, $logger, $params );
			return $ret;
		} else {
			return new CookieAuthnSession( $request, $store, $logger, $params );
		}
	}

	/**
	 * Sets a mock on a hook
	 * @param string $hook
	 * @param object $expect From $this->once(), $this->never(), etc.
	 * @return object $mock->expects( $expect )->method( ... ).
	 */
	protected function hook( $hook, $expect ) {
		$mock = $this->getMock( 'stdClass', array( "on$hook" ) );
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array( $mock ),
		) );
		return $mock->expects( $expect )->method( "on$hook" );
	}

	/**
	 * Unsets a hook
	 * @param string $hook
	 */
	protected function unhook( $hook ) {
		$this->mergeMwGlobalArrayValue( 'wgHooks', array(
			$hook => array(),
		) );
	}

	public function testConstructor() {
		$logger = new Psr\Log\NullLogger();
		$params = array(
			'empty' => false,
			'sessionName' => 'session',
			'prefix' => 'x',
			'emptyPriority' => 1,
			'priority' => 20,
		);

		$user = User::newFromName( 'UTSysop' );
		$id = $user->getId();
		$name = $user->getName();
		$token = '!!TOKEN!!';
		$userInfo = array( $id, $name, $token );

		$emptyRequest = new FauxRequest();
		$fullRequest = new FauxRequest();
		$fullRequest->setCookies( array(
			'xUserID' => $id,
			'xUserName' => $name,
			'xToken' => $token,
			'session' => 'foobar',
		), '' );

		$emptyStore = new HashBagOStuff();
		$fullStore = new HashBagOStuff();
		$fullStore->set( 'CookieAuthnSession:foobar:sess', array(
			'UserID' => $id,
			'UserName' => $name,
			'Token' => $token,
		) );

		// No data
		$session = new CookieAuthnSession( $emptyRequest, $emptyStore, $logger, $params );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Data from cookies
		$session = new CookieAuthnSession( $fullRequest, $emptyStore, $logger, $params );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['priority'], $session->getSessionPriority() );
		$this->assertSame( $userInfo, $session->getSessionUserInfo() );
		$this->assertSame( "CookieAuthnSession<$id=$name><from=cookie>", $session->__toString() );

		// Incomplete data from cookies
		$request = new FauxRequest();
		$request->setCookies( array(
			'xUserID' => $id,
			'xUserName' => $name,
		), '' );
		$session = new CookieAuthnSession( $request, $emptyStore, $logger, $params );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Data from session
		$request = new FauxRequest();
		$request->setCookie( 'session', 'foobar', '' );
		$session = new CookieAuthnSession( $request, $fullStore, $logger, $params );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['priority'], $session->getSessionPriority() );
		$this->assertSame( $userInfo, $session->getSessionUserInfo() );
		$this->assertSame( "CookieAuthnSession<$id=$name><from=session>", $session->__toString() );

		// Data from cookies and session
		$session = new CookieAuthnSession( $fullRequest, $fullStore, $logger, $params );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['priority'], $session->getSessionPriority() );
		$this->assertSame( $userInfo, $session->getSessionUserInfo() );
		$this->assertSame( "CookieAuthnSession<$id=$name><from=session>", $session->__toString() );

		// Requested empty session
		$session = new CookieAuthnSession( $fullRequest, $fullStore, $logger,
			array( 'empty' => true ) + $params );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Mismatched data from cookies and session (id)
		$request = new FauxRequest();
		$request->setCookies( array(
			'xUserID' => $id + 1,
			'session' => 'foobar',
		), '' );
		$session = new CookieAuthnSession( $request, $fullStore, $logger, $params );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Mismatched data from cookies and session (name)
		$request = new FauxRequest();
		$request->setCookies( array(
			'xUserName' => 'Wrong',
			'session' => 'foobar',
		), '' );
		$session = new CookieAuthnSession( $request, $fullStore, $logger, $params );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );
	}

	public function testBasics() {
		$session = $this->getSession();
		$this->assertSame( 'TokenSessionAuthenticationRequest', $session->getAuthenticationRequestType() );
		$this->assertTrue( $session->canSetSessionUserInfo() );
	}

	public function testSetupPHPSessionHandler() {
		// Ignore "headers already sent" warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/headers already sent/', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		$session = $this->getSession( null, array( 'storeSessionData' ) );
		$session->expects( $this->once() )->method( 'storeSessionData' )
			->with( $this->identicalTo( $session->getSessionKey() ) );
		$session->setupPHPSessionHandler( 12345 );
		session_write_close();

		$session = $this->getSession( 'session', array( 'storeSessionData' ) );
		$session->expects( $this->once() )->method( 'storeSessionData' )
			->with( $this->identicalTo( $session->getSessionKey() ) );
		$session->setupPHPSessionHandler( 12345 );
		session_write_close();
	}

	public function testStoreSessionData() {
		list( $id, $name, $token ) = $this->getUserInfo();

		// Session from cookies
		$session = $this->getSession( 'cookie' );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->userInfo = array( 'forceHTTPS' => true, 'remember' => true ) + $priv->userInfo;
		$this->hook( 'UserSetCookies', $this->never() );
		$priv->storeSessionData( $session->getSessionKey() );
		$this->unhook( 'UserSetCookies' );
		$response = $priv->request->response();
		$this->assertSame( '', $response->getcookie( 'session' ) );
		$this->assertSame( (string)$id, $response->getcookie( 'UserID' ) ); // FauxResponse is dumb and ignores the prefix
		$this->assertSame( $name, $response->getcookie( 'UserName' ) );
		$this->assertSame( $token, $response->getcookie( 'Token' ) );
		$this->assertSame( 'true', $response->getcookie( 'forceHTTPS' ) );

		// Session from session data
		$session = $this->getSession( 'session' );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->store->delete( 'CookieAuthnSession:foobar:sess' );
		$this->assertFalse( $priv->store->get( 'CookieAuthnSession:foobar:sess' ), 'sanity check' );
		$this->hook( 'UserSetCookies', $this->never() );
		$priv->storeSessionData( $session->getSessionKey() );
		$this->unhook( 'UserSetCookies' );
		$this->assertNotFalse( $priv->store->get( 'CookieAuthnSession:foobar:sess' ) );
		$response = $priv->request->response();
		$this->assertSame( 'foobar', $response->getcookie( 'session' ) );
		$this->assertSame( (string)$id, $response->getcookie( 'UserID' ) );
		$this->assertSame( $name, $response->getcookie( 'UserName' ) );
		$this->assertSame( '', $response->getcookie( 'Token' ) );
		$this->assertSame( null, $response->getcookie( 'forceHTTPS' ) );

		// Anon
		$session = $this->getSession();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$this->hook( 'UserSetCookies', $this->never() );
		$priv->storeSessionData( $session->getSessionKey() );
		$this->unhook( 'UserSetCookies' );
		$response = $priv->request->response();
		$this->assertSame( '', $response->getcookie( 'session' ) );
		$this->assertSame( '', $response->getcookie( 'UserID' ) );
		$this->assertSame( null, $response->getcookie( 'UserName' ) );
		$this->assertSame( '', $response->getcookie( 'Token' ) );
		$this->assertSame( null, $response->getcookie( 'forceHTTPS' ) );

		// Calling the BC hook
		$session = $this->getSession( 'session' );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->params = array( 'callUserSetCookiesHook' => true ) + $priv->params;
		$priv->store->delete( 'CookieAuthnSession:foobar:sess' );
		$this->assertFalse( $priv->store->get( 'CookieAuthnSession:foobar:sess' ), 'sanity check' );
		$that = $this;
		$this->hook( 'UserSetCookies', $this->once() );
		// Would be nice to put this on the same mock object, but PHPUnit can't
		// create a new method that takes reference parameters.
		$GLOBALS['wgHooks']['UserSetCookies'][] = function ( $u, &$s, &$c ) use ( $that, $id, $name, $token ) {
			$that->assertInstanceOf( 'User', $u );
			$that->assertSame( $id, $u->getId() );
			$that->assertSame( $name, $u->getName() );
			$that->assertEquals( array(
				'wsUserID' => $id,
				'wsToken' => $u->getToken(),
				'wsUserName' => $name,
			), $s );
			$that->assertEquals( array(
				'UserID' => $id,
				'UserName' => $name,
				'Token' => false,
			), $c );

			$s['sTest'] = 's!';
			$c['cTest'] = 'c!';
		};
		$priv->storeSessionData( $session->getSessionKey() );
		$this->unhook( 'UserSetCookies' );
		$this->assertNotFalse( $priv->store->get( 'CookieAuthnSession:foobar:sess' ) );
		$response = $priv->request->response();
		$this->assertSame( 'foobar', $response->getcookie( 'session' ) );
		$this->assertSame( (string)$id, $response->getcookie( 'UserID' ) );
		$this->assertSame( $name, $response->getcookie( 'UserName' ) );
		$this->assertSame( '', $response->getcookie( 'Token' ) );
		$this->assertSame( null, $response->getcookie( 'forceHTTPS' ) );
		$this->assertSame( 'c!', $response->getcookie( 'cTest' ) );
		$this->assertSame( $id, $_SESSION['wsUserID'] );
		$this->assertSame( $name, $_SESSION['wsUserName'] );
		$this->assertSame( User::newFromName( 'UTSysop' )->getToken( true ), $_SESSION['wsToken'] );
		$this->assertSame( 's!', $_SESSION['sTest'] );

		// Logout
		$session = $this->getSession();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$this->hook( 'UserSetCookies', $this->never() );
		$priv->storeSessionData( $session->getSessionKey(), true );
		$this->unhook( 'UserSetCookies' );
		$response = $priv->request->response();
		$this->assertSame( '', $response->getcookie( 'session' ) );
		$this->assertSame( '', $response->getcookie( 'UserID' ) );
		$this->assertSame( null, $response->getcookie( 'UserName' ) );
		$this->assertSame( '', $response->getcookie( 'Token' ) );
		$this->assertSame( '', $response->getcookie( 'forceHTTPS' ) );
	}

	public function testSetNewSessionKey() {
		$session = $this->getSession( 'session', array( 'storeSessionData' ) );
		$session->expects( $this->once() )->method( 'storeSessionData' )
			->with( $this->identicalTo( 'newkey' ) );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$class = get_class( $session );

		$this->assertNotFalse( $priv->store->get( "{$class}:foobar:sess" ), 'sanity check, old key set' );
		$priv->setNewSessionKey( 'newkey' );
		$this->assertFalse( $priv->store->get( "{$class}:foobar:sess" ), 'old key unset' );
	}

	/**
	 * @uses TokenSessionAuthenticationRequest
	 */
	public function testSetSessionUserInfo() {
		$session = $this->getSession( 'session', array( 'storeSessionData' ) );
		$session->expects( $this->once() )->method( 'storeSessionData' )
			->with( $this->identicalTo( 'foobar' ), $this->identicalTo( false ) );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$session->setSessionUserInfo( 42, 'Testing', 'Token?', null );
		$this->assertSame( 'set', $priv->sessionFrom );
		$this->assertEquals( array(
			'UserID' => 42,
			'UserName' => 'Testing',
			'Token' => 'Token?',
			'remember' => false,
			'forceHTTPS' => false,
		), $priv->userInfo );

		$req = new TokenSessionAuthenticationRequest();
		$req->remember = true;
		$req->forceHTTPS = true;
		$session = $this->getSession( 'session', array( 'storeSessionData' ) );
		$session->method( 'storeSessionData' )
			->with( $this->identicalTo( 'foobar' ), $this->identicalTo( false ) );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$session->setSessionUserInfo( 43, 'Testing!', 'Token??', $req );
		$this->assertEquals( array(
			'UserID' => 43,
			'UserName' => 'Testing!',
			'Token' => 'Token??',
			'remember' => true,
			'forceHTTPS' => true,
		), $priv->userInfo );
		$session->setSessionUserInfo( 43, 'Huh', 'Token!', null );
		$this->assertEquals( array(
			'UserID' => 43,
			'UserName' => 'Huh',
			'Token' => 'Token!',
			'remember' => true,
			'forceHTTPS' => true,
		), $priv->userInfo );
		$session->setSessionUserInfo( 44, 'Huh', 'Token!', null );
		$this->assertEquals( array(
			'UserID' => 44,
			'UserName' => 'Huh',
			'Token' => 'Token!',
			'remember' => false,
			'forceHTTPS' => false,
		), $priv->userInfo );

		$session = $this->getSession( 'session', array( 'storeSessionData' ) );
		$session->expects( $this->once() )->method( 'storeSessionData' )
			->with( $this->identicalTo( 'foobar' ), $this->identicalTo( true ) );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$session->setSessionUserInfo( 0, null, null, null );
		$this->assertEquals( array(
			'UserID' => 0,
			'UserName' => null,
			'Token' => null,
			'remember' => false,
			'forceHTTPS' => false,
		), $priv->userInfo );
	}

	public function testSuggestLoginUsername() {
		$session = $this->getSession();
		$priv = TestingAccessWrapper::newFromObject( $session );

		$this->assertSame( null, $session->suggestLoginUsername() );
		$priv->request->setCookie( 'UserName', 'Suggested', 'x' );
		$this->assertSame( 'Suggested', $session->suggestLoginUsername() );
	}

	public function testForceHTTPS() {
		$session = $this->getSession();
		$this->assertFalse( $session->forceHTTPS() );

		$session = $this->getSession();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->userInfo = array( 'forceHTTPS' => true ) + $priv->userInfo;
		$this->assertTrue( $session->forceHTTPS() );

		$session = $this->getSession();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->request->setCookie( 'forceHTTPS', '1', '' );
		$this->assertTrue( $session->forceHTTPS() );
	}

}
