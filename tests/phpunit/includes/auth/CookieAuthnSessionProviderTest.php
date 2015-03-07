<?php

/**
 * @group AuthManager
 * @group Database
 * @covers CookieAuthnSessionProvider
 * @uses AbstractAuthnSessionProvider
 * @uses CookieAuthnSession
 * @uses AuthnSession
 */
class CookieAuthnSessionProviderTest extends MediaWikiTestCase {

	private function getConfig() {
		return new HashConfig( array(
			'CookiePrefix' => 'CookiePrefix',
			'CookiePath' => 'CookiePath',
			'CookieDomain' => 'CookieDomain',
			'CookieSecure' => 'CookieSecure',
			'CookieHttpOnly' => 'CookieHttpOnly',
			'SessionName' => false,
		) );
	}

	public function testConstructor() {
		try {
			new CookieAuthnSessionProvider();
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'CookieAuthnSessionProvider::__construct: priority must be specified',
				$ex->getMessage()
			);
		}

		$config = $this->getConfig();
		$p = new CookieAuthnSessionProvider( array( 'priority' => 1 ) );
		$p->setConfig( $config );
		$this->assertEquals( array(
			'priority' => 1,
			'emptyPriority' => 1,
			'callUserSetCookiesHook' => false,
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => 'CookieSecure',
			'httpOnly' => 'CookieHttpOnly',
			'sessionName' => 'CookiePrefix_session',
		), TestingAccessWrapper::newFromObject( $p )->params );

		$config->set( 'SessionName', 'SessionName' );
		$p = new CookieAuthnSessionProvider( array( 'priority' => 1 ) );
		$p->setConfig( $config );
		$this->assertEquals( array(
			'priority' => 1,
			'emptyPriority' => 1,
			'callUserSetCookiesHook' => false,
			'prefix' => 'CookiePrefix',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => 'CookieSecure',
			'httpOnly' => 'CookieHttpOnly',
			'sessionName' => 'SessionName',
		), TestingAccessWrapper::newFromObject( $p )->params );

		$vals = array(
			'priority' => 10,
			'emptyPriority' => 1,
			'callUserSetCookiesHook' => true,
			'prefix' => 'XPrefix',
			'path' => 'XPath',
			'domain' => 'XDomain',
			'secure' => 'XSecure',
			'httpOnly' => 'XHttpOnly',
			'sessionName' => 'XSession',
		);
		$p = new CookieAuthnSessionProvider( $vals );
		$p->setConfig( $config );
		$this->assertEquals( $vals, TestingAccessWrapper::newFromObject( $p )->params );
	}

	public function testProvideAuthnSession() {
		$logger = new Psr\Log\NullLogger();

		$params = array(
			'priority' => 20,
			'emptyPriority' => 1,
			'sessionName' => 'session',
			'prefix' => 'x',
		);
		$provider = new CookieAuthnSessionProvider( $params );
		$provider->setConfig( $this->getConfig() );
		$provider->setLogger( new Psr\Log\NullLogger() );

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
		$provider->setStore( $emptyStore );
		$session = $provider->provideAuthnSession( $emptyRequest );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Data from cookies
		$provider->setStore( $emptyStore );
		$session = $provider->provideAuthnSession( $fullRequest );
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
		$provider->setStore( $emptyStore );
		$session = $provider->provideAuthnSession( $request );
		$this->assertNull( $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );

		// Data from session
		$request = new FauxRequest();
		$request->setCookie( 'session', 'foobar', '' );
		$provider->setStore( $fullStore );
		$session = $provider->provideAuthnSession( $request );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['priority'], $session->getSessionPriority() );
		$this->assertSame( $userInfo, $session->getSessionUserInfo() );
		$this->assertSame( "CookieAuthnSession<$id=$name><from=session>", $session->__toString() );

		// Data from cookies and session
		$provider->setStore( $fullStore );
		$session = $provider->provideAuthnSession( $fullRequest );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['priority'], $session->getSessionPriority() );
		$this->assertSame( $userInfo, $session->getSessionUserInfo() );
		$this->assertSame( "CookieAuthnSession<$id=$name><from=session>", $session->__toString() );

		// Requested empty session
		$provider->setStore( $fullStore );
		$session = $provider->provideAuthnSession( $fullRequest, true );
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
		$provider->setStore( $fullStore );
		$session = $provider->provideAuthnSession( $request );
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
		$provider->setStore( $fullStore );
		$session = $provider->provideAuthnSession( $request );
		$this->assertSame( 'foobar', $session->getSessionKey() );
		$this->assertSame( $params['emptyPriority'], $session->getSessionPriority() );
		$this->assertSame( array( 0, null, null ), $session->getSessionUserInfo() );
		$this->assertSame( 'CookieAuthnSession<anon>', $session->__toString() );
	}

	public function testDescribeSessions() {
		$p = new CookieAuthnSessionProvider( array( 'priority' => 1 ) );
		$msg = $p->describeSessions();
		$this->assertInstanceOf( 'MessageSpecifier', $msg );
		$this->assertSame( 'cookieauthnsession-description', $msg->getKey() );
	}

	public function testGetVaryCookies() {
		$p = new CookieAuthnSessionProvider( array(
			'priority' => 1,
			'prefix' => 'MyCookiePrefix',
			'sessionName' => 'MySessionName',
		) );
		$this->assertArrayEquals( array(
			'MyCookiePrefixToken',
			'MySessionName',
			'forceHTTPS',
		), $p->getVaryCookies() );
	}

}
