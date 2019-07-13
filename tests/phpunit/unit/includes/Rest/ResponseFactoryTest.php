<?php

namespace MediaWiki\Tests\Rest;

use ArrayIterator;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\ResponseFactory;
use MediaWikiUnitTestCase;

/** @covers \MediaWiki\Rest\ResponseFactory */
class ResponseFactoryTest extends MediaWikiUnitTestCase {
	public static function provideEncodeJson() {
		return [
			[ (object)[], '{}' ],
			[ '/', '"/"' ],
			[ '£', '"£"' ],
			[ [], '[]' ],
		];
	}

	/** @dataProvider provideEncodeJson */
	public function testEncodeJson( $input, $expected ) {
		$rf = new ResponseFactory;
		$this->assertSame( $expected, $rf->encodeJson( $input ) );
	}

	public function testCreateJson() {
		$rf = new ResponseFactory;
		$response = $rf->createJson( [] );
		$response->getBody()->rewind();
		$this->assertSame( 'application/json', $response->getHeaderLine( 'Content-Type' ) );
		$this->assertSame( '[]', $response->getBody()->getContents() );
		// Make sure getSize() is functional, since testCreateNoContent() depends on it
		$this->assertSame( 2, $response->getBody()->getSize() );
	}

	public function testCreateNoContent() {
		$rf = new ResponseFactory;
		$response = $rf->createNoContent();
		$this->assertSame( [], $response->getHeader( 'Content-Type' ) );
		$this->assertSame( 0, $response->getBody()->getSize() );
		$this->assertSame( 204, $response->getStatusCode() );
	}

	public function testCreatePermanentRedirect() {
		$rf = new ResponseFactory;
		$response = $rf->createPermanentRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 301, $response->getStatusCode() );
	}

	public function testCreateLegacyTemporaryRedirect() {
		$rf = new ResponseFactory;
		$response = $rf->createLegacyTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 302, $response->getStatusCode() );
	}

	public function testCreateTemporaryRedirect() {
		$rf = new ResponseFactory;
		$response = $rf->createTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 307, $response->getStatusCode() );
	}

	public function testCreateSeeOther() {
		$rf = new ResponseFactory;
		$response = $rf->createSeeOther( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 303, $response->getStatusCode() );
	}

	public function testCreateNotModified() {
		$rf = new ResponseFactory;
		$response = $rf->createNotModified();
		$this->assertSame( 0, $response->getBody()->getSize() );
		$this->assertSame( 304, $response->getStatusCode() );
	}

	/** @expectedException \InvalidArgumentException */
	public function testCreateHttpErrorInvalid() {
		$rf = new ResponseFactory;
		$rf->createHttpError( 200 );
	}

	public function testCreateHttpError() {
		$rf = new ResponseFactory;
		$response = $rf->createHttpError( 415, [ 'message' => '...' ] );
		$this->assertSame( 415, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 415, $data['httpCode'] );
		$this->assertSame( '...', $data['message'] );
	}

	public function testCreateFromExceptionUnlogged() {
		$rf = new ResponseFactory;
		$response = $rf->createFromException( new HttpException( 'hello', 415 ) );
		$this->assertSame( 415, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 415, $data['httpCode'] );
		$this->assertSame( 'hello', $data['message'] );
	}

	public function testCreateFromExceptionLogged() {
		$rf = new ResponseFactory;
		$response = $rf->createFromException( new \Exception( "hello", 415 ) );
		$this->assertSame( 500, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 500, $data['httpCode'] );
		$this->assertSame( 'Error: exception of type Exception', $data['message'] );
	}

	public static function provideCreateFromReturnValue() {
		return [
			[ 'hello', '{"value":"hello"}' ],
			[ true, '{"value":true}' ],
			[ [ 'x' => 'y' ], '{"x":"y"}' ],
			[ [ 'x', 'y' ], '["x","y"]' ],
			[ [ 'a', 'x' => 'y' ], '{"0":"a","x":"y"}' ],
			[ (object)[ 'a', 'x' => 'y' ], '{"0":"a","x":"y"}' ],
			[ [], '[]' ],
			[ (object)[], '{}' ],
		];
	}

	/** @dataProvider provideCreateFromReturnValue */
	public function testCreateFromReturnValue( $input, $expected ) {
		$rf = new ResponseFactory;
		$response = $rf->createFromReturnValue( $input );
		$body = $response->getBody();
		$body->rewind();
		$this->assertSame( $expected, $body->getContents() );
	}

	/** @expectedException \InvalidArgumentException */
	public function testCreateFromReturnValueInvalid() {
		$rf = new ResponseFactory;
		$rf->createFromReturnValue( new ArrayIterator );
	}
}
