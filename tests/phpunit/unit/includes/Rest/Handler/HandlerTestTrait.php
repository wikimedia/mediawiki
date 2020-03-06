<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\ObjectFactory;
use Wikimedia\Services\ServiceContainer;

/**
 * A trait providing utility functions for testing Handler classes.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @package MediaWiki\Tests\Rest\Handler
 */
trait HandlerTestTrait {

	use MediaWikiTestCaseTrait;

	/** @var int */
	private $pageIdCounter = 0;

	/**
	 * Expected to be provided by the class, probably inherited from TestCase.
	 *
	 * @param string $originalClassName
	 *
	 * @return MockObject
	 */
	abstract protected function createMock( $originalClassName ): MockObject;

	/**
	 * Executes the given Handler on the given request.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 *
	 * @return Response
	 */
	private function executeHandler( Handler $handler, RequestInterface $request, $config = [] ) {
		$formatter = $this->createMock( ITextFormatter::class );
		$formatter->method( 'format' )->willReturnCallback( function ( MessageValue $msg ) {
			return $msg->dump();
		} );

		/** @var ResponseFactory|MockObject $responseFactory */
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		/** @var PermissionManager|MockObject $permissionManager */
		$permissionManager = $this->createNoOpMock(
			PermissionManager::class, [ 'userCan', 'userHasRight' ]
		);
		$permissionManager->method( 'userCan' )->willReturn( true );
		$permissionManager->method( 'userHasRight' )->willReturn( true );

		/** @var ServiceContainer|MockObject $serviceContainer */
		$serviceContainer = $this->createNoOpMock( ServiceContainer::class );
		$objectFactory = new ObjectFactory( $serviceContainer );

		$user = new UserIdentityValue( 0, 'Fake User', 0 );
		$validator = new Validator( $objectFactory, $permissionManager, $request, $user );

		/** @var Router|MockObject $router */
		$router = $this->createNoOpMock( Router::class, [ 'getRouteUrl' ] );
		$router->method( 'getRouteUrl' )->willReturnCallback( function ( $route, $query = [] ) {
			return wfAppendQuery( 'https://wiki.example.com/rest' . $route, $query );
		} );

		$handler->init( $router, $request, $config, $responseFactory );
		$handler->validate( $validator );
		$ret = $handler->execute();

		$response = $ret instanceof Response ? $ret
			: $responseFactory->createFromReturnValue( $ret );

		return $response;
	}

	/**
	 * Executes the given Handler on the given request, parses the response body as JSON,
	 * and returns the result.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 *
	 * @return array
	 */
	private function executeHandlerAndGetBodyData(
		Handler $handler,
		RequestInterface $request,
		$config = []
	) {
		$response = $this->executeHandler( $handler, $request, $config );

		$this->assertTrue( $response->getStatusCode() >= 200 && $response->getStatusCode() < 300 );
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
	 *
	 * @return HttpException
	 */
	private function executeHandlerAndGetHttpException(
		Handler $handler,
		RequestInterface $request,
		$config = []
	) {
		try {
			$this->executeHandler( $handler, $request, $config );
			Assert::fail( 'Expected a HttpException to be thrown' );
		} catch ( HttpException $ex ) {
			return $ex;
		}
	}

	/**
	 * @return Title
	 */
	private function makeMockTitle( $text, $id = null, $model = 'UNKNOWN' ) {
		$id = $id ?? ++$this->pageIdCounter;

		/** @var Title|MockObject $title */
		$title = $this->createMock( Title::class );
		$title->method( 'getText' )->willReturn( str_replace( '_', ' ', $text ) );
		$title->method( 'getDBkey' )->willReturn( str_replace( ' ', '_', $text ) );
		$title->method( 'getPrefixedText' )->willReturn( str_replace( '_', ' ', $text ) );
		$title->method( 'getPrefixedDBkey' )->willReturn( str_replace( ' ', '_', $text ) );
		$title->method( 'getArticleID' )->willReturn( $id );
		$title->method( 'exists' )->willReturn( $id > 0 );
		$title->method( 'getContentModel' )->willReturn( $model );

		return $title;
	}

}
