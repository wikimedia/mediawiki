<?php

namespace MediaWiki\Tests\Session;

use BadMethodCallException;
use InvalidArgumentException;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Session\ImmutableSessionProviderWithCookie;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\UserInfo;
use MediaWikiIntegrationTestCase;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\ArrayUtils\ArrayUtils;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @group Database
 * @covers \MediaWiki\Session\ImmutableSessionProviderWithCookie
 */
class ImmutableSessionProviderWithCookieTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	private function getProvider( $name, $prefix = null, $forceHTTPS = false, $logger = null ) {
		$config = new HashConfig();
		$config->set( MainConfigNames::CookiePrefix, 'wgCookiePrefix' );
		$config->set( MainConfigNames::ForceHTTPS, $forceHTTPS );

		$params = [
			'sessionCookieName' => $name,
			'sessionCookieOptions' => [],
		];
		if ( $prefix !== null ) {
			$params['sessionCookieOptions']['prefix'] = $prefix;
		}

		$provider = $this->getMockBuilder( ImmutableSessionProviderWithCookie::class )
			->setConstructorArgs( [ $params ] )
			->getMockForAbstractClass();
		$this->initProvider( $provider, $logger ?? new TestLogger(), $config, new SessionManager() );

		return $provider;
	}

	public function testConstructor() {
		$provider = $this->getMockBuilder( ImmutableSessionProviderWithCookie::class )
			->getMockForAbstractClass();
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertNull( $priv->sessionCookieName );
		$this->assertSame( [], $priv->sessionCookieOptions );

		$provider = $this->getMockBuilder( ImmutableSessionProviderWithCookie::class )
			->setConstructorArgs( [ [
				'sessionCookieName' => 'Foo',
				'sessionCookieOptions' => [ 'Bar' ],
			] ] )
			->getMockForAbstractClass();
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertSame( 'Foo', $priv->sessionCookieName );
		$this->assertSame( [ 'Bar' ], $priv->sessionCookieOptions );

		try {
			$provider = $this->getMockBuilder( ImmutableSessionProviderWithCookie::class )
				->setConstructorArgs( [ [
					'sessionCookieName' => false,
				] ] )
				->getMockForAbstractClass();
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'sessionCookieName must be a string',
				$ex->getMessage()
			);
		}

		try {
			$provider = $this->getMockBuilder( ImmutableSessionProviderWithCookie::class )
				->setConstructorArgs( [ [
					'sessionCookieOptions' => 'x',
				] ] )
				->getMockForAbstractClass();
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame(
				'sessionCookieOptions must be an array',
				$ex->getMessage()
			);
		}
	}

	public function testBasics() {
		$provider = $this->getProvider( null );
		$this->assertFalse( $provider->persistsSessionId() );
		$this->assertFalse( $provider->canChangeUser() );

		$provider = $this->getProvider( 'Foo' );
		$this->assertTrue( $provider->persistsSessionId() );
		$this->assertFalse( $provider->canChangeUser() );

		$msg = $provider->whyNoSession();
		$this->assertInstanceOf( Message::class, $msg );
		$this->assertSame( 'sessionprovider-nocookies', $msg->getKey() );
	}

	public function testGetVaryCookies() {
		$provider = $this->getProvider( null );
		$this->assertSame( [], $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo' );
		$this->assertSame( [ 'wgCookiePrefixFoo' ], $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo', 'Bar' );
		$this->assertSame( [ 'BarFoo' ], $provider->getVaryCookies() );

		$provider = $this->getProvider( 'Foo', '' );
		$this->assertSame( [ 'Foo' ], $provider->getVaryCookies() );
	}

	public function testGetSessionIdFromCookie() {
		$this->overrideConfigValue( MainConfigNames::CookiePrefix, 'wgCookiePrefix' );
		$request = new FauxRequest();
		$request->setCookies( [
			'' => 'empty---------------------------',
			'Foo' => 'foo-----------------------------',
			'wgCookiePrefixFoo' => 'wgfoo---------------------------',
			'BarFoo' => 'foobar--------------------------',
			'bad' => 'bad',
		], '' );

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( null ) );
		try {
			$provider->getSessionIdFromCookie( $request );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame(
				'MediaWiki\\Session\\ImmutableSessionProviderWithCookie::getSessionIdFromCookie ' .
					'may not be called when $this->sessionCookieName === null',
				$ex->getMessage()
			);
		}

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo' ) );
		$this->assertSame(
			'wgfoo---------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo', 'Bar' ) );
		$this->assertSame(
			'foobar--------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( 'Foo', '' ) );
		$this->assertSame(
			'foo-----------------------------',
			$provider->getSessionIdFromCookie( $request )
		);

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( 'bad', '' ) );
		$this->assertSame( null, $provider->getSessionIdFromCookie( $request ) );

		$provider = TestingAccessWrapper::newFromObject( $this->getProvider( 'none', '' ) );
		$this->assertSame( null, $provider->getSessionIdFromCookie( $request ) );
	}

	protected function getSentRequest() {
		$sentResponse = $this->getMockBuilder( FauxResponse::class )
			->onlyMethods( [ 'headersSent', 'setCookie', 'header' ] )
			->getMock();
		$sentResponse->method( 'headersSent' )
			->willReturn( true );
		$sentResponse->expects( $this->never() )->method( 'setCookie' );
		$sentResponse->expects( $this->never() )->method( 'header' );

		$sentRequest = $this->getMockBuilder( FauxRequest::class )
			->onlyMethods( [ 'response' ] )->getMock();
		$sentRequest->method( 'response' )
			->willReturn( $sentResponse );
		return $sentRequest;
	}

	/**
	 * @dataProvider providePersistSession
	 * @param bool $secure
	 * @param bool $remember
	 * @param bool $forceHTTPS
	 */
	public function testPersistSession( $secure, $remember, $forceHTTPS ) {
		$this->overrideConfigValues( [
			MainConfigNames::CookieExpiration => 100,
			MainConfigNames::SecureLogin => false,
			MainConfigNames::ForceHTTPS => $forceHTTPS,
		] );

		$provider = $this->getProvider( 'session', null, $forceHTTPS, new NullLogger() );
		$priv = TestingAccessWrapper::newFromObject( $provider );
		$priv->sessionCookieOptions = [
			'prefix' => 'x',
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => false,
			'httpOnly' => true,
		];

		$sessionId = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$user = $this->getTestSysop()->getUser();
		$this->assertSame( $forceHTTPS, $user->requiresHTTPS() );

		$backend = new SessionBackend(
			new SessionId( $sessionId ),
			new SessionInfo( SessionInfo::MIN_PRIORITY, [
				'provider' => $provider,
				'id' => $sessionId,
				'persisted' => true,
				'userInfo' => UserInfo::newFromUser( $user, true ),
				'idIsSafe' => true,
			] ),
			new TestBagOStuff(),
			new NullLogger(),
			$this->createHookContainer(),
			10
		);
		TestingAccessWrapper::newFromObject( $backend )->usePhpSessionHandling = false;
		$backend->setRememberUser( $remember );
		$backend->setForceHTTPS( $secure );

		// No cookie
		$priv->sessionCookieName = null;
		$request = new FauxRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( [], $request->response()->getCookies() );

		// Cookie
		$priv->sessionCookieName = 'session';
		$request = new FauxRequest();
		$time = time();
		$provider->persistSession( $backend, $request );

		$cookie = $request->response()->getCookieData( 'xsession' );
		$this->assertIsArray( $cookie );
		if ( isset( $cookie['expire'] ) && $cookie['expire'] > 0 ) {
			// Round expiry so we don't randomly fail if the seconds ticked during the test.
			$cookie['expire'] = round( $cookie['expire'] - $time, -2 );
		}
		$this->assertEquals( [
			'value' => $sessionId,
			'expire' => null,
			'path' => 'CookiePath',
			'domain' => 'CookieDomain',
			'secure' => $secure || $forceHTTPS,
			'httpOnly' => true,
			'raw' => false,
		], $cookie );

		$cookie = $request->response()->getCookieData( 'forceHTTPS' );
		if ( $secure && !$forceHTTPS ) {
			$this->assertIsArray( $cookie );
			if ( isset( $cookie['expire'] ) && $cookie['expire'] > 0 ) {
				// Round expiry so we don't randomly fail if the seconds ticked during the test.
				$cookie['expire'] = round( $cookie['expire'] - $time, -2 );
			}
			$this->assertEquals( [
				'value' => 'true',
				'expire' => null,
				'path' => 'CookiePath',
				'domain' => 'CookieDomain',
				'secure' => false,
				'httpOnly' => true,
				'raw' => false,
			], $cookie );
		} else {
			$this->assertNull( $cookie );
		}

		// Headers sent
		$request = $this->getSentRequest();
		$provider->persistSession( $backend, $request );
		$this->assertSame( [], $request->response()->getCookies() );
	}

	public static function providePersistSession() {
		return ArrayUtils::cartesianProduct(
			[ false, true ], // $secure
			[ false, true ], // $remember
			[ false, true ] // $forceHTTPS
		);
	}

	public function testUnpersistSession() {
		$provider = $this->getProvider( 'session', '', false, new NullLogger() );
		$priv = TestingAccessWrapper::newFromObject( $provider );

		// No cookie
		$priv->sessionCookieName = null;
		$request = new FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );

		// Cookie
		$priv->sessionCookieName = 'session';
		$request = new FauxRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( '', $request->response()->getCookie( 'session', '' ) );

		// Headers sent
		$request = $this->getSentRequest();
		$provider->unpersistSession( $request );
		$this->assertSame( null, $request->response()->getCookie( 'session', '' ) );
	}

}
