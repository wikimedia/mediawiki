<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Utils;
use MediaWiki\Rest\Response;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Rest\Response
 */
class ResponseTest extends MediaWikiUnitTestCase {

	public function testConstructDefault() {
		$response = new Response();
		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertSame( 'OK', $response->getReasonPhrase() );
		$this->assertSame( '1.1', $response->getProtocolVersion() );
		$this->assertSame( '', $response->getBody()->getContents() );
		$this->assertSame( [], $response->getCookies() );
		$this->assertSame( [], $response->getRawHeaderLines() );
	}

	public function testConstructWithStringBody() {
		$response = new Response( 'hello world' );
		$this->assertSame( 'hello world', $response->getBody()->getContents() );
	}

	public function testConstructWithStreamInterface() {
		$stream = Utils::streamFor( 'stream content' );
		$response = new Response( $stream );
		$this->assertSame( 'stream content', $response->getBody()->getContents() );
	}

	public function testSetStatus() {
		$response = new Response();
		$response->setStatus( 404, 'Nothing Here' );
		$this->assertSame( 404, $response->getStatusCode() );
		$this->assertSame( 'Nothing Here', $response->getReasonPhrase() );

		$response->setStatus( 500 );
		$this->assertSame( 500, $response->getStatusCode() );
		$this->assertSame( 'Internal Server Error', $response->getReasonPhrase() );
	}

	public function testSetCookieAndGetCookies() {
		$response = new Response();
		$this->assertSame( [], $response->getCookies() );

		$response->setCookie( 'SessionCookie', 'val1' );
		$response->setCookie( 'PersistentCookie', 'val2', 1700000000, [ 'path' => '/wiki', 'secure' => true ] );

		$expected = [
			[
				'name' => 'SessionCookie',
				'value' => 'val1',
				'expire' => 0,
				'options' => []
			],
			[
				'name' => 'PersistentCookie',
				'value' => 'val2',
				'expire' => 1700000000,
				'options' => [ 'path' => '/wiki', 'secure' => true ]
			]
		];

		$this->assertSame( $expected, $response->getCookies() );
	}

	public function testHeaders() {
		$response = new Response();
		$response->setHeader( 'Content-Type', 'text/html' );
		$response->addHeader( 'X-Custom', 'A' );
		$response->addHeader( 'X-Custom', 'B' );

		$this->assertSame( [ 'text/html' ], $response->getHeader( 'Content-Type' ) );
		$this->assertSame( 'text/html', $response->getHeaderLine( 'Content-Type' ) );
		$this->assertSame( [ 'A', 'B' ], $response->getHeader( 'X-Custom' ) );

		$response->removeHeader( 'Content-Type' );
		$this->assertFalse( $response->hasHeader( 'Content-Type' ) );
	}

	public function testCast() {
		$original = new Response( 'test body' );
		$original->setStatus( 201, 'Created' );
		$original->setHeader( 'X-Foo', 'Bar' );

		$casted = Response::cast( $original );
		$this->assertSame( $original, $casted );

		$mockInterface = $this->createMock( \MediaWiki\Rest\ResponseInterface::class );
		$mockInterface->method( 'getBody' )->willReturn( Utils::streamFor( 'mock stream' ) );
		$mockInterface->method( 'getHeaders' )->willReturn( [ 'X-Mock' => [ 'Value' ] ] );

		$converted = Response::cast( $mockInterface );
		$this->assertInstanceOf( Response::class, $converted );
		$this->assertSame( 'mock stream', $converted->getBody()->getContents() );
		$this->assertSame( [ 'Value' ], $converted->getHeader( 'X-Mock' ) );
	}
}
