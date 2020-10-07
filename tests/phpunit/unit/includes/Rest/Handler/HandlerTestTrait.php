<?php

namespace MediaWiki\Tests\Rest\Handler;

use GenderCache;
use Language;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
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
use MediaWikiTitleCodec;
use NamespaceInfo;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Title;
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
	 * Calls init() on the Handler, supplying a mock Router and ResponseFactory.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 */
	private function initHandler( Handler $handler, RequestInterface $request,
		$config = [], $hooks = []
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

		$hookContainer = $this->createHookContainer( $hooks );

		$handler->init( $router, $request, $config, $responseFactory, $hookContainer );
	}

	/**
	 * Calls validate() on the Handler, with an appropriate Validator supplied.
	 *
	 * @param Handler $handler
	 */
	private function validateHandler( Handler $handler ) {
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
		$validator =
			new Validator( $objectFactory, $permissionManager, $handler->getRequest(), $user );

		$handler->validate( $validator );
	}

	/**
	 * Executes the given Handler on the given request.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 *
	 * @return ResponseInterface
	 */
	private function executeHandler( Handler $handler, RequestInterface $request,
		$config = [], $hooks = []
	) {
		// supply defaults for required fields in $config
		$config += [ 'path' => '/test' ];

		$this->initHandler( $handler, $request, $config, $hooks );
		$this->validateHandler( $handler );

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
	 *
	 * @return array
	 */
	private function executeHandlerAndGetBodyData(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = []
	) {
		$response = $this->executeHandler( $handler, $request, $config, $hooks );

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
	 * @param string $text
	 * @param array $props Additional properties to set. Supported keys:
	 *        - id: int
	 *        - namespace: int
	 *
	 * @return Title|MockObject
	 */
	private function makeMockTitle( $text, array $props = [] ) {
		$id = $props['id'] ?? ++$this->pageIdCounter;
		$ns = $props['namespace'] ?? 0;
		$nsName = $ns ? "ns$ns:" : '';

		$preText = $text;
		$text = preg_replace( '/^[\w ]*?:/', '', $text );

		// If no namespace prefix was given, add one if needed.
		if ( $preText == $text && $ns ) {
			$preText = $nsName . $text;
		}

		/** @var Title|MockObject $title */
		$title = $this->createMock( Title::class );

		$title->method( 'getText' )->willReturn( str_replace( '_', ' ', $text ) );
		$title->method( 'getDBkey' )->willReturn( str_replace( ' ', '_', $text ) );

		$title->method( 'getPrefixedText' )->willReturn( str_replace( '_', ' ', $preText ) );
		$title->method( 'getPrefixedDBkey' )->willReturn( str_replace( ' ', '_', $preText ) );

		$title->method( 'getArticleID' )->willReturn( $id );
		$title->method( 'getNamespace' )->willReturn( $props['namespace'] ?? 0 );
		$title->method( 'exists' )->willReturn( $id > 0 );
		$title->method( 'getTouched' )->willReturn( $id ? '20200101223344' : false );

		return $title;
	}

	/**
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

	/**
	 * @return MediaWikiTitleCodec
	 */
	private function makeMockTitleCodec() {
		/** @var Language|MockObject $language */
		$language = $this->createNoOpMock( Language::class, [ 'ucfirst' ] );
		$language->method( 'ucfirst' )->willReturnCallback( 'ucfirst' );

		/** @var GenderCache|MockObject $genderCache */
		$genderCache = $this->createNoOpMock( GenderCache::class );

		/** @var InterwikiLookup|MockObject $interwikiLookup */
		$interwikiLookup = $this->createNoOpMock( InterwikiLookup::class );

		/** @var NamespaceInfo|MockObject $namespaceInfo */
		$namespaceInfo = $this->createNoOpMock( NamespaceInfo::class, [ 'isCapitalized' ] );
		$namespaceInfo->method( 'isCapitalized' )->willReturn( true );

		$titleCodec = new MediaWikiTitleCodec(
			$language,
			$genderCache,
			[ 'en' ],
			$interwikiLookup,
			$namespaceInfo
		);

		return $titleCodec;
	}

}
