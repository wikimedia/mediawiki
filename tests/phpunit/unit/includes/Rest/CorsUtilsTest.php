<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\User\UserIdentity;

/**
 * @covers \MediaWiki\Rest\CorsUtils
 */
class CorsUtilsTest extends \MediaWikiUnitTestCase {

	private function createServiceOptions( array $options = [] ) {
		$defaults = [
			'AllowCrossOrigin' => false,
			'RestAllowCrossOriginCookieAuth' => false,
			'CanonicalServer' => 'https://example.com',
			'CrossSiteAJAXdomains' => [],
			'CrossSiteAJAXdomainExceptions' => [],
		];

		return new ServiceOptions( CorsUtils::CONSTRUCTOR_OPTIONS, array_merge( $defaults, $options ) );
	}

	/**
	 * @dataProvider provideAuthorizeAllowOrigin
	 */
	public function testAuthorizeAllowOrigin( bool $isRegistered, bool $needsWriteAccess, string $origin ) {
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'isRegistered' )
			->willReturn( $isRegistered );

		$cors = new CorsUtils(
			$this->createServiceOptions( [
				'CrossSiteAJAXdomains' => [
					'www.mediawiki.org',
				],
			] ),
			$this->createNoOpMock( ResponseFactory::class ),
			$user
		);

		$request = $this->createMock( RequestInterface::class );
		$request->method( 'hasHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', (bool)$origin ]
			] ) );
		$request->method( 'getHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', [ $origin ] ]
			] ) );

		$handler = $this->createMock( Handler::class );
		$handler->method( 'needsWriteAccess' )
			->willReturn( false );

		$result = $cors->authorize(
			$request,
			$handler
		);

		$this->assertNull( $result );
	}

	public function provideAuthorizeAllowOrigin() {
		$origin = 'https://example.com';

		return [
			'User is registered' => [
				true,
				true,
				$origin,
			],
			'Handler does not need write access' => [
				false,
				false,
				$origin,
			],
			'Missing origin' => [
				false,
				true,
				'',
			],
			'Same origin' => [
				false,
				true,
				$origin,
			],
			'Trusted origin' => [
				false,
				true,
				'https://www.mediawiki.org',
			],
		];
	}

	public function testAuthorizeDisallowOrigin() {
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'isRegistered' )
			->willReturn( false );

		$cors = new CorsUtils(
			$this->createServiceOptions(),
			$this->createMock( ResponseFactory::class ),
			$user
		);

		$request = $this->createMock( RequestInterface::class );
		$request->method( 'hasHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', true ]
			] ) );
		$request->expects( $this->once() )
			->method( 'getHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', [ 'https://www.mediawiki.org' ] ]
			] ) );

		$handler = $this->createMock( Handler::class );
		$handler->method( 'needsWriteAccess' )
			->willReturn( true );

		$result = $cors->authorize(
			$request,
			$handler
		);

		$this->assertSame( 'rest-cross-origin-anon-write', $result );
	}

	public function testModifyResponseNoChange() {
		$cors = new CorsUtils(
			$this->createServiceOptions(),
			$this->createMock( ResponseFactory::class ),
			$this->createMock( UserIdentity::class )
		);

		$request = $this->createMock( RequestInterface::class );
		$response = $this->createNoOpMock( ResponseInterface::class );

		$this->assertTrue( true, 'Tests that no methods are called on $response, assertion for making phpunit happy' );
	}

	public function testModifyResponseAllowOrigin() {
		$cors = new CorsUtils(
			$this->createServiceOptions( [
				'AllowCrossOrigin' => true,
			] ),
			$this->createNoOpMock( ResponseFactory::class ),
			$this->createMock( UserIdentity::class )
		);

		$request = $this->createMock( RequestInterface::class );

		$response = new Response();

		$result = $cors->modifyResponse(
			$this->createMock( RequestInterface::class ),
			$response
		);

		$this->assertSame( $response, $result );
		$this->assertFalse( $result->hasHeader( 'Vary' ) );
		$this->assertTrue( $result->hasHeader( 'Access-Control-Allow-Origin' ) );
		$this->assertSame( [ '*' ], $result->getHeader( 'Access-Control-Allow-Origin' ) );
	}

	/**
	 * @dataProvider provideModifyResponseAllowTrustedOriginCookieAuth
	 * @param string $requestMethod
	 * @param string $isRegistered
	 */
	public function testModifyResponseAllowTrustedOriginCookieAuth( string $requestMethod, bool $isRegistered ) {
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'isRegistered' )
			->willReturn( $isRegistered );

		$cors = new CorsUtils(
			$this->createServiceOptions( [
				'AllowCrossOrigin' => true,
				'RestAllowCrossOriginCookieAuth' => true,
			] ),
			$this->createNoOpMock( ResponseFactory::class ),
			$user
		);

		$request = $this->createMock( RequestInterface::class );
		$request->method( 'hasHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', true ]
			] ) );
		$request->method( 'getHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', [ 'https://example.com' ] ],
			] ) );
		$request->method( 'getMethod' )
			->willReturn( $requestMethod );

		$response = new Response();

		$result = $cors->modifyResponse( $request, $response );

		$this->assertSame( $response, $result );
		$this->assertTrue( $result->hasHeader( 'Vary' ) );
		$this->assertSame( [ 'Origin' ], $result->getHeader( 'Vary' ) );
		$this->assertTrue( $result->hasHeader( 'Access-Control-Allow-Credentials' ) );
		$this->assertSame( [ 'true' ], $result->getHeader( 'Access-Control-Allow-Credentials' ) );
		$this->assertTrue( $result->hasHeader( 'Access-Control-Allow-Origin' ) );
		$this->assertSame( [ 'https://example.com' ], $result->getHeader( 'Access-Control-Allow-Origin' ) );
	}

	public function provideModifyResponseAllowTrustedOriginCookieAuth() {
		return [
			'OPTIONS request' => [
				'OPTIONS',
				false
			],
			'Registered user on main request' => [
				'POST',
				true,
			],
		];
	}

	/**
	 * @dataProvider provideModifyResponseDisallowUntrustedOriginCookieAuth
	 */
	public function testModifyResponseDisallowUntrustedOriginCookieAuth(
		string $origin,
		string $requestMethod,
		bool $isRegistered
	) {
		$user = $this->createMock( UserIdentity::class );
		$user->method( 'isRegistered' )
			->willReturn( $isRegistered );

		$cors = new CorsUtils(
			$this->createServiceOptions( [
				'AllowCrossOrigin' => true,
				'RestAllowCrossOriginCookieAuth' => true,
			] ),
			$this->createNoOpMock( ResponseFactory::class ),
			$user
		);

		$request = $this->createMock( RequestInterface::class );
		$request->method( 'hasHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', (bool)$origin ]
			] ) );
		$request->method( 'getHeader' )
			->will( $this->returnValueMap( [
				[ 'Origin', [ $origin ] ],
			] ) );
		$request->method( 'getMethod' )
			->willReturn( $requestMethod );

		$response = new Response();

		$result = $cors->modifyResponse( $request, $response );

		$this->assertSame( $response, $result );
		$this->assertTrue( $result->hasHeader( 'Vary' ) );
		$this->assertSame( [ 'Origin' ], $result->getHeader( 'Vary' ) );
		$this->assertFalse( $result->hasHeader( 'Access-Control-Allow-Credentials' ) );
		$this->assertTrue( $result->hasHeader( 'Access-Control-Allow-Origin' ) );
		$this->assertSame( [ '*' ], $result->getHeader( 'Access-Control-Allow-Origin' ) );
	}

	public function provideModifyResponseDisallowUntrustedOriginCookieAuth() {
		return [
			'Missing Origin' => [
				'',
				'GET',
				true,
			],
			'Untrusted Origin' => [
				'www.mediawiki.org',
				'GET',
				true
			],
			'Trusted Origin, anon user' => [
				'example.com',
				'POST',
				false
			],
		];
	}

	public function testCreatePreflightResponse() {
		$responseFactory = $this->createMock( ResponseFactory::class );
		$responseFactory->method( 'createNoContent' )
			->willReturn( new Response() );

		$cors = new CorsUtils(
			$this->createServiceOptions(),
			$responseFactory,
			$this->createMock( UserIdentity::class )
		);

		$methods = [ 'POST' ];
		$response = $cors->createPreflightResponse( $methods );

		$this->assertInstanceOf( ResponseInterface::class, $response );
		$this->assertTrue( $response->hasHeader( 'Access-Control-Allow-Headers' ) );
		$this->assertTrue( $response->hasHeader( 'Access-Control-Allow-Methods' ) );
		$this->assertSame( $methods, $response->getHeader( 'Access-Control-Allow-Methods' ) );
	}
}
