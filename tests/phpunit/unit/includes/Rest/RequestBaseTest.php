<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\RequestBase;

/**
 * @covers \MediaWiki\Rest\RequestBase
 */
class RequestBaseTest extends \MediaWikiUnitTestCase {
	use RestTestTrait;

	public function testGetCookiePrefix() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$this->assertSame( 'cookiePrefix', $rb->getCookiePrefix() );
	}

	public function testGetNullCookie() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->expects( $this->once() )
			->method( 'getCookieParams' )
			->willReturn( [ 'cookiePrefixcookie1' => 'value1' ] );
		$this->assertNull( $rb->getCookie( 'nonExistingCookie' ) );
	}

	public function testGetCookie() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->expects( $this->once() )
			->method( 'getCookieParams' )
			->willReturn( [ 'cookiePrefixcookie1' => 'value1' ] );
		$this->assertSame( 'value1', $rb->getCookie( 'cookie1' ) );
	}

	public function testDefaultPathParams() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$this->assertSame( [], $rb->getPathParams() );
	}

	public function testSetPathParams() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->setPathParams( [ 'foo' => 'bar' ] );
		$this->assertSame( [ 'foo' => 'bar' ], $rb->getPathParams() );
	}

	public function testNullPathParam() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$this->assertNull( $rb->getPathParam( 'Non-existing' ) );
	}

	public function testGetPathParam() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->setPathParams( [ 'foo' => 'bar' ] );
		$this->assertSame( 'bar', $rb->getPathParam( 'foo' ) );
	}

	public function testDefaultHeaders() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->setHeaders( [] );
		$this->assertSame( [], $rb->getHeaders() );
	}

	public function testGetHeaders() {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->setHeaders( [ 'Content-type' => 'application/json' ] );
		$this->assertSame( [ 'application/json' ], $rb->getHeaders()[ 'Content-type' ] );
	}

	public static function provideHasBody() {
		yield 'nothing'
		=> [ [], false ];

		yield 'content-length: 1'
		=> [ [ 'content-length' => '1' ], true ];

		yield 'content-length: 0'
		=> [ [ 'content-length' => '0' ], true ];

		yield 'content-length empty'
		=> [ [ 'content-length' => '' ], false ];

		yield 'transfer-encoding: chunked'
		=> [ [ 'transfer-encoding' => 'chunked' ], true ];

		yield 'transfer-encoding empty'
		=> [ [ 'transfer-encoding' => '' ], false ];
	}

	/**
	 * @dataProvider provideHasBody
	 */
	public function testHasBody( $headers, $expected ) {
		$rb = $this->getMockForAbstractClass( RequestBase::class, [ 'cookiePrefix' ] );
		$rb->setHeaders( $headers );
		$this->assertSame( $expected, $rb->hasBody() );
	}

}
