<?php

namespace MediaWiki\Tests\Rest;

use ArrayIterator;
use InvalidArgumentException;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\ResponseFactory;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

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

	private function createResponseFactory() {
		$fakeTextFormatter = new class implements ITextFormatter {
			public function getLangCode() {
				return 'qqx';
			}

			public function format( MessageValue $message ) {
				return $message->getKey();
			}
		};
		return new ResponseFactory( [ $fakeTextFormatter ] );
	}

	/** @dataProvider provideEncodeJson */
	public function testEncodeJson( $input, $expected ) {
		$rf = $this->createResponseFactory();
		$this->assertSame( $expected, $rf->encodeJson( $input ) );
	}

	public function testCreateJson() {
		$rf = $this->createResponseFactory();
		$response = $rf->createJson( [] );
		$response->getBody()->rewind();
		$this->assertSame( 'application/json', $response->getHeaderLine( 'Content-Type' ) );
		$this->assertSame( '[]', $response->getBody()->getContents() );
		// Make sure getSize() is functional, since testCreateNoContent() depends on it
		$this->assertSame( 2, $response->getBody()->getSize() );
	}

	public function testCreateNoContent() {
		$rf = $this->createResponseFactory();
		$response = $rf->createNoContent();
		$this->assertSame( [], $response->getHeader( 'Content-Type' ) );
		$this->assertSame( 0, $response->getBody()->getSize() );
		$this->assertSame( 204, $response->getStatusCode() );
	}

	public function testCreatePermanentRedirect() {
		$rf = $this->createResponseFactory();
		$response = $rf->createPermanentRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 301, $response->getStatusCode() );
	}

	public function testCreateLegacyTemporaryRedirect() {
		$rf = $this->createResponseFactory();
		$response = $rf->createLegacyTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 302, $response->getStatusCode() );
	}

	public function testCreateTemporaryRedirect() {
		$rf = $this->createResponseFactory();
		$response = $rf->createTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 307, $response->getStatusCode() );
	}

	public function testCreateSeeOther() {
		$rf = $this->createResponseFactory();
		$response = $rf->createSeeOther( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 303, $response->getStatusCode() );
	}

	public function testCreateNotModified() {
		$rf = $this->createResponseFactory();
		$response = $rf->createNotModified();
		$this->assertSame( 0, $response->getBody()->getSize() );
		$this->assertSame( 304, $response->getStatusCode() );
	}

	public function testCreateHttpErrorInvalid() {
		$rf = $this->createResponseFactory();
		$this->expectException( InvalidArgumentException::class );
		$rf->createHttpError( 200 );
	}

	public function testCreateHttpError() {
		$rf = $this->createResponseFactory();
		$response = $rf->createHttpError( 415, [ 'message' => '...' ] );
		$this->assertSame( 415, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 415, $data['httpCode'] );
		$this->assertSame( '...', $data['message'] );
	}

	public function testCreateFromExceptionUnlogged() {
		$rf = $this->createResponseFactory();
		$response = $rf->createFromException( new HttpException( 'hello', 415 ) );
		$this->assertSame( 415, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 415, $data['httpCode'] );
		$this->assertSame( 'hello', $data['message'] );
	}

	public function testCreateFromExceptionLogged() {
		$rf = $this->createResponseFactory();
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
		$rf = $this->createResponseFactory();
		$response = $rf->createFromReturnValue( $input );
		$body = $response->getBody();
		$body->rewind();
		$this->assertSame( $expected, $body->getContents() );
	}

	public function testCreateFromReturnValueInvalid() {
		$rf = $this->createResponseFactory();
		$this->expectException( InvalidArgumentException::class );
		$rf->createFromReturnValue( new ArrayIterator );
	}

	public function testCreateLocalizedHttpError() {
		$rf = $this->createResponseFactory();
		$response = $rf->createLocalizedHttpError( 404, new MessageValue( 'rftest' ) );
		$body = $response->getBody();
		$body->rewind();
		$this->assertSame(
			'{"messageTranslations":{"qqx":"rftest"},"httpCode":404,"httpReason":"Not Found"}',
			$body->getContents() );
	}
}
