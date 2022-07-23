<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Services\ServiceContainer;

/**
 * A trait providing utility functions for testing Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @package MediaWiki\Tests\Rest\Handler
 */
trait HandlerTestTrait {
	use MockAuthorityTrait;
	use SessionHelperTestTrait;

	/**
	 * Calls init() on the Handler, supplying a mock Router and ResponseFactory.
	 *
	 * @internal to the trait
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 * @param Authority|null $authority
	 * @param bool $csrfSafe
	 */
	private function initHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		Authority $authority = null,
		bool $csrfSafe = false
	) {
		$formatter = $this->createMock( ITextFormatter::class );
		$formatter->method( 'format' )->willReturnCallback( static function ( MessageValue $msg ) {
			return $msg->dump();
		} );

		/** @var ResponseFactory|MockObject $responseFactory */
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		/** @var Router|MockObject $router */
		$router = $this->createNoOpMock( Router::class, [ 'getRouteUrl' ] );
		$router->method( 'getRouteUrl' )->willReturnCallback( static function ( $route, $path = [], $query = [] ) {
			foreach ( $path as $param => $value ) {
				$route = str_replace( '{' . $param . '}', urlencode( (string)$value ), $route );
			}
			return wfAppendQuery( 'https://wiki.example.com/rest' . $route, $query );
		} );

		$authority = $authority ?: $this->mockAnonUltimateAuthority();
		$hookContainer = $this->createHookContainer( $hooks );

		$handler->init( $router, $request, $config, $authority, $responseFactory, $hookContainer,
			$this->getSession( $csrfSafe )
		);
	}

	/**
	 * Calls validate() on the Handler, with an appropriate Validator supplied.
	 *
	 * @internal to the trait
	 * @param Handler $handler
	 * @param null|Validator $validator
	 * @throws HttpException
	 */
	private function validateHandler(
		Handler $handler,
		Validator $validator = null
	) {
		if ( !$validator ) {
			/** @var ServiceContainer|MockObject $serviceContainer */
			$serviceContainer = $this->createNoOpMock( ServiceContainer::class );
			$objectFactory = new ObjectFactory( $serviceContainer );
			$validator = new Validator( $objectFactory, $handler->getRequest(), $handler->getAuthority() );
		}
		$handler->validate( $validator );
	}

	/**
	 * Creates a mock Validator to bypass actual request query, path, and/or body param validation
	 *
	 * @internal to the trait
	 * @param array $queryPathParams
	 * @param array $bodyParams
	 * @return Validator|MockObject
	 */
	private function getMockValidator( array $queryPathParams, array $bodyParams ): Validator {
		$validator = $this->createNoOpMock( Validator::class, [ 'validateParams', 'validateBody' ] );
		if ( $queryPathParams ) {
			$validator->method( 'validateParams' )->willReturn( $queryPathParams );
		}
		if ( $bodyParams ) {
			$validator->method( 'validateBody' )->willReturn( $bodyParams );
		}
		return $validator;
	}

	/**
	 * Executes the given Handler on the given request.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 * @param array $validatedParams Path/query params to return as already valid
	 * @param array $validatedBody Body params to return as already valid
	 * @param Authority|null $authority
	 * @param bool $csrfSafe
	 * @return ResponseInterface
	 */
	private function executeHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		Authority $authority = null,
		bool $csrfSafe = false
	): ResponseInterface {
		// supply defaults for required fields in $config
		$config += [ 'path' => '/test' ];

		$this->initHandler( $handler, $request, $config, $hooks, $authority, $csrfSafe );
		$validator = null;
		if ( $validatedParams || $validatedBody ) {
			/** @var Validator|MockObject $validator */
			$validator = $this->getMockValidator( $validatedParams, $validatedBody );
		}
		$this->validateHandler( $handler, $validator );

		// Check conditional request headers
		$earlyResponse = $handler->checkPreconditions();
		if ( $earlyResponse ) {
			return $earlyResponse;
		}

		$ret = $handler->execute();

		$response = $ret instanceof Response ? $ret
			: $handler->getResponseFactory()->createFromReturnValue( $ret );

		// Set Last-Modified and ETag headers in the response if available
		$handler->applyConditionalResponseHeaders( $response );

		return $response;
	}

	/**
	 * Executes the given Handler on the given request, parses the response body as JSON,
	 * and returns the result.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks
	 * @param array $validatedParams
	 * @param array $validatedBody
	 * @param Authority|null $authority
	 * @param bool $csrfSafe
	 * @return array
	 */
	private function executeHandlerAndGetBodyData(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		Authority $authority = null,
		bool $csrfSafe = false
	): array {
		$response = $this->executeHandler( $handler, $request, $config, $hooks,
			$validatedParams, $validatedBody, $authority, $csrfSafe );

		$this->assertTrue(
			$response->getStatusCode() >= 200 && $response->getStatusCode() < 300,
			'Status should be in 2xx range.'
		);
		$this->assertSame( 'application/json', $response->getHeaderLine( 'Content-Type' ) );

		$data = json_decode( $response->getBody(), true );
		$this->assertIsArray( $data, 'Body must be a JSON array' );

		return $data;
	}

	/**
	 * Executes the given Handler on the given request, and returns the HttpException thrown.
	 * Fails if no HttpException is thrown.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks
	 *
	 * @return HttpException
	 */
	private function executeHandlerAndGetHttpException(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = []
	): HttpException {
		try {
			$this->executeHandler( $handler, $request, $config, $hooks );
			Assert::fail( 'Expected a HttpException to be thrown' );
		} catch ( HttpException $ex ) {
			return $ex;
		}
	}
}
