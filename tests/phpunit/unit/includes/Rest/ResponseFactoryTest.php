<?php

namespace MediaWiki\Tests\Rest;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use JsonSchemaAssertionTrait;
use MediaWiki\Rest\ErrorFormatter;
use MediaWiki\Rest\ErrorFormatterV1;
use MediaWiki\Rest\ErrorFormatterV2;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RedirectException;
use MediaWiki\Rest\ResponseException;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWikiUnitTestCase;
use RuntimeException;
use Wikimedia\Message\MessageValue;
use Wikimedia\TestingAccessWrapper;

/** @covers \MediaWiki\Rest\ResponseFactory */
class ResponseFactoryTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use JsonSchemaAssertionTrait;

	public static function provideEncodeJson() {
		return [
			[ (object)[], '{}' ],
			[ '/', '"/"' ],
			[ '£', '"£"' ],
			[ [], '[]' ],
			[ "\xc0", "\"\u{FFFD}\"" ] // T289597
		];
	}

	private function createResponseFactory() {
		$textFormatters = [ $this->getDummyTextFormatter() ];
		// Supply tracing data so that 5xx errors carry a reqId, mirroring
		// production where Router::getTracingData() provides the request id.
		return new ResponseFactory(
			$textFormatters,
			new ErrorFormatterV1( $textFormatters, false, [ 'request_id' => 'test-request-id' ] )
		);
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

	public static function provideUseException() {
		return [ [ false ], [ true ] ];
	}

	/** @dataProvider provideUseException */
	public function testCreateNoContent( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new HttpException( '', 204 ) ) :
			$rf->createNoContent();
		$this->assertSame( [], $response->getHeader( 'Content-Type' ) );
		$this->assertSame( 0, $response->getBody()->getSize() );
		$this->assertSame( 204, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreatePermanentRedirect( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new RedirectException(
				301, 'http://www.example.com/'
			) ) :
			$rf->createPermanentRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 301, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreateLegacyTemporaryRedirect( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new RedirectException(
				302, 'http://www.example.com/'
			) ) :
			$rf->createLegacyTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 302, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreateRedirect( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new RedirectException(
				333, 'http://www.example.com/'
			) ) :
			$rf->createRedirect( 'http://www.example.com/', 333 );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 333, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreateTemporaryRedirect( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new RedirectException(
				307, 'http://www.example.com/'
			) ) :
			$rf->createTemporaryRedirect( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 307, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreateSeeOther( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new RedirectException(
				303, 'http://www.example.com/'
			) ) :
			$rf->createSeeOther( 'http://www.example.com/' );
		$this->assertSame( [ 'http://www.example.com/' ], $response->getHeader( 'Location' ) );
		$this->assertSame( 303, $response->getStatusCode() );
	}

	/** @dataProvider provideUseException */
	public function testCreateNotModified( $useException ) {
		$rf = $this->createResponseFactory();
		$response = $useException ?
			$rf->createFromException( new HttpException( '', 304 ) ) :
			$rf->createNotModified();
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
		$this->assertArrayNotHasKey( 'reqId', $data );
	}

	public function testCreateFatalHttpError() {
		$rf = $this->createResponseFactory();
		$response = $rf->createHttpError( 501, [ 'message' => '...' ] );
		$this->assertSame( 501, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 501, $data['httpCode'] );
		$this->assertSame( '...', $data['message'] );
		$this->assertArrayHasKey( 'reqId', $data );
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
		$response = $rf->createFromException( new Exception( "hello", 415 ) );
		$this->assertSame( 500, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 500, $data['httpCode'] );
		$this->assertSame( 'Error: exception of type Exception', $data['message'] );
		$this->assertArrayHasKey( 'reqId', $data );
	}

	public function testCreateFromExceptionWrapped() {
		$rf = $this->createResponseFactory();
		$wrapped = $rf->create();
		$response = $rf->createFromException( new ResponseException( $wrapped ) );
		$this->assertSame( $wrapped, $response );
	}

	public function testCreateFromExceptionWithExtraData() {
		$rf = $this->createResponseFactory();
		$e = new LocalizedHttpException( new MessageValue( 'rftest' ), 404 );
		$response = $rf->createFromException( $e, [ 'foo' => 'bar' ] );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertArrayHasKey( 'foo', $data );
		$this->assertSame( 'bar', $data['foo'] );
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

	public function testFormatMessage() {
		$rf = $this->createResponseFactory();
		$mv = new MessageValue( 'rftest' );
		$ret = $rf->formatMessage( $mv );
		$this->assertIsArray( $ret );
		$this->assertArrayHasKey( 'messageTranslations', $ret );
		$this->assertIsArray( $ret['messageTranslations'] );
		$this->assertArrayHasKey( 'qqx', $ret['messageTranslations'] );
		$this->assertSame( 'rftest', $ret['messageTranslations']['qqx'] );
	}

	public function testGetFormattedMessage() {
		$rf = $this->createResponseFactory();
		$mv = new MessageValue( 'rftest' );

		$ret = $rf->getFormattedMessage( $mv );
		$this->assertIsString( $ret );
		$this->assertSame( 'rftest', $ret );

		$ret = $rf->getFormattedMessage( $mv, 'doesnotexist' );
		$this->assertIsString( $ret );
		$this->assertSame( 'rftest', $ret );
	}

	public function testGetResponseComponentsDescribesTheFormatterInUse() {
		$errorFormatter = new ErrorFormatterV2( [], false, [] );
		$components = ( new ResponseFactory( [], $errorFormatter ) )->getResponseComponents();

		$this->assertSame(
			[ '$ref' => '#/components/schemas/GenericErrorResponseModel' ],
			$components['responses']['GenericErrorResponse']['content']['application/json']['schema']
		);
		$this->assertSame(
			[ 'GenericErrorResponseModel' => $errorFormatter->getOpenApiSchema() ],
			$components['schemas']
		);
	}

	public static function provideErrorBodies(): iterable {
		$entryPoints = [
			// "error" is supplied by the caller, as in Module::executeHandler()
			'formatErrorBody' => static fn ( ErrorFormatter $ef ) =>
				$ef->formatErrorBody( 403, [ 'error' => 'rest-read-denied' ] ),
			'formatException' => static fn ( ErrorFormatter $ef ) =>
				$ef->formatException( 500, new RuntimeException( 'boom' ) ),
			'formatHttpException' => static fn ( ErrorFormatter $ef ) =>
				$ef->formatHttpException( 403, new HttpException( 'denied', 403 ) ),
			'formatLocalizedHttpException' => static fn ( ErrorFormatter $ef ) =>
				$ef->formatLocalizedHttpException(
					404, new LocalizedHttpException( new MessageValue( 'rest-test-key' ), 404 )
				),
			'formatLocalizedHttpError' => static fn ( ErrorFormatter $ef ) =>
				$ef->formatLocalizedHttpError( 404, new MessageValue( 'rest-test-key' ) ),
		];
		// Every error format the Router can select
		foreach ( TestingAccessWrapper::constant( Router::class, 'ERROR_FORMATTERS' ) as $version => $spec ) {
			foreach ( $entryPoints as $entryPoint => $format ) {
				yield "$version, $entryPoint" => [ $spec['class'], $format ];
			}
		}
	}

	/**
	 * Each formatter's OpenAPI schema must describe what the formatter actually emits.
	 *
	 * @dataProvider provideErrorBodies
	 * @covers \MediaWiki\Rest\ErrorFormatterV1
	 * @covers \MediaWiki\Rest\ErrorFormatterV2
	 * @covers \MediaWiki\Rest\RestbaseCompatErrorFormatter
	 */
	public function testErrorResponseSchemaMatchesFormatterOutput( string $formatterClass, callable $format ) {
		$tracingData = [
			'module' => 'mediawiki',
			'method' => 'get',
			'uri' => '/w/rest.php/v1/page/Foo',
			'request_id' => '123455',
			'url' => 'https://wiki.example.com/w/rest.php/v1/page/Foo',
		];
		$variants = [
			'no text formatters' => new $formatterClass( [], false, $tracingData ),
			'text formatter, exception details' =>
				new $formatterClass( [ $this->getDummyTextFormatter() ], true, $tracingData ),
		];

		foreach ( $variants as $variant => $ef ) {
			$schema = $ef->getOpenApiSchema();
			$body = $format( $ef );
			$this->assertMatchesJsonSchema( $schema, $body, [], $variant );

			// The schemas keep additionalProperties open for handler-supplied data, so
			// validation alone would not notice a formatter renaming or leaking a field.
			$this->assertSame(
				[],
				array_values( array_diff( array_keys( $body ), array_keys( $schema['properties'] ), [ 'error' ] ) ),
				"$variant: keys not declared in the schema"
			);
			if ( isset( $body['tracing'] ) ) {
				$this->assertSame(
					[],
					array_values( array_diff(
						array_keys( $body['tracing'] ),
						array_keys( $schema['properties']['tracing']['properties'] )
					) ),
					"$variant: tracing keys not declared in the schema"
				);
			}
		}
	}
}
