<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use MediaWiki\Tests\Rest\RestTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A trait providing utility functions for testing Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 */
trait HandlerTestTrait {
	use RestTestTrait;
	use DummyServicesTrait;
	use MockAuthorityTrait;
	use SessionHelperTestTrait;

	/**
	 * Calls init() on the Handler, supplying a mock RouteUrlProvider and ResponseFactory.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param HookContainer|array $hooks Hook container or array of hooks
	 * @param Authority|null $authority
	 * @param Session|null $session Defaults to `$this->getSession( true )`
	 * @param Router|Module|null $routerOrModule
	 *
	 * @internal to the trait
	 */
	private function initHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		?Authority $authority = null,
		?Session $session = null,
		$routerOrModule = null
	) {
		$formatter = $this->getDummyTextFormatter( true );
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		$module = null;
		$router = null;

		if ( $routerOrModule instanceof Module ) {
			$module = $routerOrModule;
			$router = $module->getRouter();
		}

		if ( $routerOrModule instanceof Router ) {
			$router = $routerOrModule;
		}

		if ( !$module ) {
			if ( !$router ) {
				$router = $this->newRouter();
			}

			$module = $this->newModule( [ 'router' => $router ] );
		}

		if ( !$request->hasBody()
			&& in_array( $request->getMethod(), RequestInterface::BODY_METHODS )
		) {
			// Send an empty body if none was provided.
			$request->setParsedBody( [] );
		}

		$authority ??= $this->mockAnonUltimateAuthority();
		$hookContainer =
			$hooks instanceof HookContainer ? $hooks : $this->createHookContainer( $hooks );

		$session ??= $this->getSession( true );
		$handler->initContext( $module, $config['path'] ?? 'test', $config );
		$handler->initServices( $authority, $responseFactory, $hookContainer );
		$handler->initSession( $session );
		$handler->initForExecute( $request );
	}

	/**
	 * @return MockObject&Router
	 */
	private function newRouter(): Router {
		$router = $this->createNoOpMock(
			Router::class,
			[
				'getRoutePath',
				'getRouteUrl',
				'isRestbaseCompatEnabled'
			]
		);
		$router->method( 'getRoutePath' )->willReturnCallback(
			static function ( $route, $path = [], $query = [] ) {
				foreach ( $path as $param => $value ) {
					$route = str_replace(
						'{' . $param . '}',
						urlencode( (string)$value ),
						$route
					);
				}

				return wfAppendQuery(
					'/rest' . $route,
					$query
				);
			}
		);
		$router->method( 'getRouteUrl' )->willReturnCallback(
			static function ( $route, $path = [], $query = [] ) use ( $router ) {
				return 'https://wiki.example.com' . $router->getRoutePath(
						$route,
						$path,
						$query
					);
			}
		);
		$router->method( 'isRestbaseCompatEnabled' )->willReturnCallback(
			static function ( RequestInterface $request ) {
				return $request->getHeaderLine( 'x-restbase-compat' ) === 'true';
			}
		);

		return $router;
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
		?Validator $validator = null
	) {
		if ( !$validator ) {
			$serviceContainer = $this->getServiceContainer();
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
		$validator = $this->createNoOpMock(
			Validator::class,
			[
				'validateParams',
				'validateBodyParams',
				'validateBody',
				'detectExtraneousBodyFields',
			]
		);
		$validator->method( 'validateBody' )->willReturn( null );
		$validator->method( 'validateParams' )->willReturn( $queryPathParams );
		$validator->method( 'validateBodyParams' )->willReturn( $bodyParams );
		return $validator;
	}

	/**
	 * Executes the given Handler on the given request.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param HookContainer|array $hooks Hook container or array of hooks
	 * @param array $validatedParams Path/query params to return as already valid
	 * @param array $validatedBody Body params to return as already valid
	 * @param Authority|null $authority
	 * @param Session|null $session Defaults to `$this->getSession( true )`
	 * @param Router|Module|null $routerOrModule
	 *
	 * @return ResponseInterface
	 */
	private function executeHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		?Authority $authority = null,
		?Session $session = null,
		$routerOrModule = null
	): ResponseInterface {
		// supply defaults for required fields in $config
		$config += [ 'path' => '/test' ];

		$this->initHandler( $handler, $request, $config, $hooks, $authority, $session, $routerOrModule );
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
	 * @param HookContainer|array $hooks Hook container or array of hooks
	 * @param array $validatedParams
	 * @param array $validatedBody
	 * @param Authority|null $authority
	 * @param Session|null $session Defaults to `$this->getSession( true )`
	 * @return array
	 */
	private function executeHandlerAndGetBodyData(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		?Authority $authority = null,
		?Session $session = null
	): array {
		$response = $this->executeHandler( $handler, $request, $config, $hooks,
			$validatedParams, $validatedBody, $authority, $session );

		$this->assertGreaterThanOrEqual( 200, $response->getStatusCode() );
		$this->assertLessThan( 300, $response->getStatusCode() );
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
	 * @param HookContainer|array $hooks Hook container or array of hooks
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
