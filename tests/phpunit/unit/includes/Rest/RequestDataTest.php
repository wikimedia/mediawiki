<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\StringStream;

/**
 * @covers \MediaWiki\Rest\RequestData
 */
class RequestDataTest extends \MediaWikiUnitTestCase {
	use RestTestTrait;

	public function testDefaultMethodIsGet() {
		$request = new RequestData( [] );
		$this->assertSame( 'GET', $request->getMethod() );
	}

	public function testGetMethod() {
		$request = new RequestData( [
			'method' => 'HEAD'
		] );
		$this->assertSame( 'HEAD', $request->getMethod() );
	}

	public function testDefaultProtocolVersion() {
		$request = new RequestData( [] );
		$this->assertSame( '1.1', $request->getProtocolVersion() );
	}

	public function testGetProtocolVersion() {
		$request = new RequestData( [
			'protocolVersion' => '3.0'
		] );
		$this->assertSame( '3.0', $request->getProtocolVersion() );
	}

	public function testDefaultServerParams() {
		$request = new RequestData( [] );
		$this->assertSame( [], $request->getServerParams() );
	}

	public function testGetServerParams() {
		$request = new RequestData( [
			'serverParams' => [ 'serverFoo' => 'serverBar' ]
		] );
		$this->assertSame( [ 'serverFoo' => 'serverBar' ], $request->getServerParams() );
	}

	public function testDefaultCookieParams() {
		$request = new RequestData( [] );
		$this->assertSame( [], $request->getCookieParams() );
	}

	public function testGetCookieParams() {
		$request = new RequestData( [
			'cookieParams' => 'cookieData'
		] );
		$this->assertSame( 'cookieData', $request->getCookieParams() );
	}

	public function testDefaultQueryParams() {
		$request = new RequestData( [] );
		$this->assertSame( [], $request->getQueryParams() );
	}

	public function testGetQueryParams() {
		$request = new RequestData( [
			'queryParams' => 'queryData'
		] );
		$this->assertSame( 'queryData', $request->getQueryParams() );
	}

	public function testDefaultHeaders() {
		$request = new RequestData( [] );
		$this->assertSame( [], $request->getHeaders() );
	}

	public function testGetHeaders() {
		$request = new RequestData( [
			'headers' => [ 'headersData' ]
		] );
		$this->assertSame( [ [ 'headersData' ] ], $request->getHeaders() );
	}

	public function testSetHeaders() {
		$request = new RequestData( [] );
		$request->setHeaders( [ 'Content-type' => 'application/json' ] );
		$this->assertSame( [ 'application/json' ], $request->getHeaders()[ 'Content-type' ] );
	}

	public function testDefaultUri() {
		$request = new RequestData( [] );
		$this->assertInstanceOf( Uri::class, $request->getUri() );
	}

	public function testGetUri() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/RouterTest/hello' ),
		] );
		$this->assertInstanceOf( Uri::class, $request->getUri() );
		$this->assertSame( '/rest/mock/RouterTest/hello', $request->getUri()->__toString() );
	}

	public function testDefaultBody() {
		$request = new RequestData( [] );
		$this->assertInstanceOf( StringStream::class, $request->getBody() );
		$this->assertSame( "", $request->getBody()->getContents() );
	}

	public function testGetBody() {
		$request = new RequestData( [
			'bodyContents' => 'bodyContents',
		] );
		$this->assertInstanceOf( StringStream::class, $request->getBody() );
		$this->assertSame( "bodyContents", $request->getBody()->getContents() );
	}

	// Testing RequestBase functions
	public function testDefaultCookiePrefix() {
		$request = new RequestData( [] );
		$this->assertSame( '', $request->getCookiePrefix() );
	}

	public function testGetCookiePrefix() {
		$request = new RequestData( [
			'cookiePrefix' => 'cookiePrefixData'
		] );
		$this->assertSame( 'cookiePrefixData', $request->getCookiePrefix() );
	}

	public function testGetNullCookie() {
		$request = new RequestData( [] );
		$this->assertNull( $request->getCookie( 'nonExistingCookie' ) );
	}

	public function testGetCookie() {
		$request = new RequestData( [
			'cookieParams' => [ 'cookie1' => 'value1' ]
		] );
		$this->assertSame( 'value1', $request->getCookie( 'cookie1' ) );
	}

	public function testDefaultPathParams() {
		$request = new RequestData( [] );
		$this->assertSame( [], $request->getPathParams() );
	}

	public function testSetPathParams() {
		$request = new RequestData( [] );
		$request->setPathParams( [ 'foo' => 'bar' ] );
		$this->assertSame( [ 'foo' => 'bar' ], $request->getPathParams() );
	}

	public function testNullPathParam() {
		$request = new RequestData( [] );
		$this->assertNull( $request->getPathParam( 'Non-existing' ) );
	}

	public function testGetPathParam() {
		$request = new RequestData( [
			'pathParams' => [ 'foo' => 'bar' ]
		] );
		$this->assertSame( 'bar', $request->getPathParam( 'foo' ) );
	}

	public function testDefaultParsedBody() {
		$request = new RequestData( [] );
		$this->assertNull( $request->getParsedBody() );
	}

	public function testSetParsedBody() {
		$request = new RequestData( [] );
		$request->setParsedBody( [ 'bodyData' ] );
		$this->assertEquals( [ 'bodyData' ], $request->getParsedBody() );
	}

	public function testGetParsedBody() {
		$request = new RequestData( [
			'parsedBody' => [ 'bodyData' ]
		] );
		$this->assertEquals( [ 'bodyData' ], $request->getParsedBody() );
	}

	public static function provideBodyTypeBasedOnHeader() {
		return [
			'application/json' => [
				[ 'Content-Type' => 'application/json' ],
				'application/json'
			],
			'application/json with charset' => [
				[ 'Content-Type' => 'application/json; charset=utf-8' ],
				'application/json'
			],
			'application/form-urlencoded' => [
				[ 'Content-Type' => 'application/x-www-form-urlencoded' ],
				'application/x-www-form-urlencoded'
			],
			'multipart/form-data' => [
				[ 'Content-Type' => 'multipart/form-data' ],
				'multipart/form-data'
			],
			'no header' => [
				[],
				null
			],
			'empty header' => [
				'headers' => [ 'Content-Type' => '' ],
				null
			],

		];
	}

	/** @dataProvider provideBodyTypeBasedOnHeader */
	public function testBodyTypeBasedOnHeader( $headers, $expectedResult ) {
		$request = new RequestData( [
			'headers' => $headers
		] );
		$this->assertSame( $expectedResult, $request->getBodyType() );
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
	public function testHasBodyBasedOnHeader( $headers, $expected ) {
		$request = new RequestData( [
			'headers' => $headers
		] );
		$this->assertSame( $expected, $request->hasBody() );
	}

	public function testHasBodyWithContent() {
		$request = new RequestData( [
			'bodyContents' => 'test test test'
		] );
		$this->assertTrue( $request->hasBody() );
	}

	public function testHasBodyWithParsedBody() {
		$request = new RequestData( [
			'parsedBody' => [ 'foo' => 'bar' ]
		] );
		$this->assertTrue( $request->hasBody() );
	}
}
