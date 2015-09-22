<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @group Database
 * @covers MediaWiki\Session\ImmutableSessionProviderWithCookie
 */
class ImmutableSessionProviderWithCookieTest extends MediaWikiTestCase {

	private function getProvider( $name, $prefix = null ) {
		$config = new \HashConfig();
		$config->set( 'CookiePrefix', 'wgCookiePrefix' );

		$params = array(
			'sessionCookieName' => $name,
			'sessionCookieOptions' => array(),
		);
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\ImmutableSessionProviderWithCookie' )
			->setConstructorArgs( array( $params ) )
			->getMockForAbstractClass();
		$provider->setLogger( new \TestLogger() );
		$provider->setConfig( $config );
		$provider->setManager( new SessionManager() );

		return $provider;
	}

	public function testConstructor() {
		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\ImmutableSessionProviderWithCookie' )
			->getMockForAbstractClass();
		$priv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertNull( $priv->sessionCookieName );
		$this->assertSame( array(), $priv->sessionCookieOptions );

		$provider = $this->getMockBuilder( 'MediaWiki\\Session\\ImmutableSessionProviderWithCookie' )
			->setConstructorArgs( array( array(
				'sessionCookieName' => 'Foo',
				'sessionCookieOptions' => array( 'Bar' ),
			) ) )
			->getMockForAbstractClass();
		$priv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 'Foo', $priv->sessionCookieName );
		$this->assertSame( array( 'Bar' ), $priv->sessionCookieOptions );

		try {
			$provider = $this->getMockBuilder( 'MediaWiki\\Session\\ImmutableSessionProviderWithCookie' )
				->setConstructorArgs( array( array(
					'sessionCookieName' => false,
				) ) )
				->getMockForAbstractClass();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'sessionCookieName must be a string',
				$ex->getMessage()
			);
		}

		try {
			$provider = $this->getMockBuilder( 'MediaWiki\\Session\\ImmutableSessionProviderWithCookie' )
				->setConstructorArgs( array( array(
					'sessionCookieOptions' => 'x',
				) ) )
				->getMockForAbstractClass();
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame(
				'sessionCookieOptions must be an array',
				$ex->getMessage()
			);
		}
	}

	public function testBasics() {
		$provider = $this->getProvider( null );
		$this->assertFalse( $provider->persistsSessionID() );
		$this->assertFalse( $provider->canChangeUser() );

		$provider = $this->getProvider( 'Foo' );
		$this->assertTrue( $provider->persistsSessionID() );
		$this->assertFalse( $provider->canChangeUser() );
	}

	public function testGetVaryCookies() {
		$provider = $this->getProvider( null );
		$this->assertSame( array(), $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo' );
		$this->assertSame( array( 'wgCookiePrefixFoo' ), $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo', 'Bar' );
		$this->assertSame( array( 'BarFoo' ), $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo', '' );
		$this->assertSame( array( 'Foo' ), $provider->getVaryCookies() );
	}

	public function testGetSessionIdFromCookie() {
		$this->setMwGlobals( 'wgCookiePrefix', 'wgCookiePrefix' );
		$request = new \FauxRequest();
		$request->setCookies( array(
			'' => 'empty---------------------------',
			'Foo' => 'foo-----------------------------',
			'wgCookiePrefixFoo' => 'wgfoo---------------------------',
			'BarFoo' => 'foobar--------------------------',
			'bad' => 'bad',
		), '' );

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( null ) );
		try {
			$provider->getSessionIdFromCookie( $request );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\ImmutableSessionProviderWithCookie::getSessionIdFromCookie ' .
					'may not be called when $this->sessionCookieName === null',
				$ex->getMessage()
			);
		}

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo' ) );
		$this->assertSame(
			'wgfoo---------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo', 'Bar' ) );
		$this->assertSame(
			'foobar--------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo', '' ) );
		$this->assertSame(
			'foo-----------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( 'bad', '' ) );
		$this->assertSame( null, $provider->getSessionIdFromCookie( $request ) );

		$provider = \TestingAccessWrapper::newFromObject( $this->getProvider( 'none', '' ) );
		$this->assertSame( null, $provider->getSessionIdFromCookie( $request ) );
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

	public function testPersistSession() {
		$provider = $this->getProvider( 'session', '' );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, array(
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'user' => UserInfo::newAnonymous(),
			) ),
			new \EmptyBagOStuff(),
			10
		);
		\TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;

		// No cookie
		$priv->sessionCookieName = null;
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );

		// Cookie
		$priv->sessionCookieName = 'session';
		$request = new \FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( $backend->getId(), $request->response()->getCookie( 'session', '' ) );

		// Headers sent
		$request = $this->getSentRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );
	}

	public function testUnpersistSession() {
		$provider = $this->getProvider( 'session', '' );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$priv = \TestingAccessWrapper::newFromObject( $provider );

		// No cookie
		$priv->sessionCookieName = null;
		$request = new \FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );

		// Cookie
		$priv->sessionCookieName = 'session';
		$request = new \FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( '', $request->response()->getCookie( 'session', '' ) );

		// Headers sent
		$request = $this->getSentRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );
	}

}
