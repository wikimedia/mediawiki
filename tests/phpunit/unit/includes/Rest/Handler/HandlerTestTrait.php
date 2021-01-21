<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use User;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory;
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
	use MediaWikiTestCaseTrait;

	/**
	 * Expected to be provided by the class, probably inherited from TestCase.
	 *
	 * @param string $originalClassName
	 *
	 * @return MockObject
	 */
	abstract protected function createMock( $originalClassName ): MockObject;

	/**
	 * Calls init() on the Handler, supplying a mock Router and ResponseFactory.
	 *
	 * @internal to the trait
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 * @param Authority|null $authority
	 */
	private function initHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		Authority $authority = null
	) {
		$formatter = $this->createMock( ITextFormatter::class );
		$formatter->method( 'format' )->willReturnCallback( function ( MessageValue $msg ) {
			return $msg->dump();
		} );

		/** @var ResponseFactory|MockObject $responseFactory */
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		/** @var Router|MockObject $router */
		$router = $this->createNoOpMock( Router::class, [ 'getRouteUrl' ] );
		$router->method( 'getRouteUrl' )->willReturnCallback( function ( $route, $path = [], $query = [] ) {
			foreach ( $path as $param => $value ) {
				$route = str_replace( '{' . $param . '}', urlencode( $value ), $route );
			}
			return wfAppendQuery( 'https://wiki.example.com/rest' . $route, $query );
		} );

		$authority = $authority ?: new UltimateAuthority( new UserIdentityValue( 0, 'Fake User', 0 ) );
		$hookContainer = $this->createHookContainer( $hooks );

		$handler->init( $router, $request, $config, $authority, $responseFactory, $hookContainer );
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
	private function getMockValidator( array $queryPathParams, array $bodyParams ) {
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
	 * @return ResponseInterface
	 * @throws HttpException
	 */
	private function executeHandler(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		Authority $authority = null
	) {
		// supply defaults for required fields in $config
		$config += [ 'path' => '/test' ];

		$this->initHandler( $handler, $request, $config, $hooks, $authority );
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
	 * @return array
	 */
	private function executeHandlerAndGetBodyData(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		Authority $authority = null
	) {
		$response = $this->executeHandler( $handler, $request, $config, $hooks,
			$validatedParams, $validatedBody, $authority );

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
	) {
		try {
			$this->executeHandler( $handler, $request, $config, $hooks );
			Assert::fail( 'Expected a HttpException to be thrown' );
		} catch ( HttpException $ex ) {
			return $ex;
		}
	}

	/**
	 * @internal
	 * @return PermissionManager|MockObject
	 */
	private function makeMockPermissionManager() {
		/** @var PermissionManager|MockObject $permissionManager */
		$permissionManager = $this->createNoOpMock(
			PermissionManager::class, [ 'userCan' ]
		);
		$permissionManager->method( 'userCan' )
			->willReturnCallback( function ( $action, User $user, LinkTarget $page ) {
				return !preg_match( '/Forbidden/', $page->getText() );
			} );

		return $permissionManager;
	}
}
