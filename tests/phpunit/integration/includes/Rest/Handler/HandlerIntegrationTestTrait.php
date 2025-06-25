<?php

namespace MediaWiki\Tests\Rest\Handler;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use Throwable;
use Wikimedia\TestingAccessWrapper;

/**
 * Trait for actually integrated REST handler tests, i.e. using real
 * dependencies rather than mocked dependencies where possible.
 */
trait HandlerIntegrationTestTrait {
	/**
	 * @param array $requestParams Request parameters as in the RequestData
	 *   constructor, except that "path" is accepted as a convenience alias
	 *   for a URI with that path.
	 * @return ResponseInterface
	 */
	private function execute( $requestParams ) {
		if ( isset( $requestParams['path'] ) ) {
			$requestParams['uri'] = new Uri( $requestParams['path'] );
		}
		$request = new RequestData( $requestParams );
		$context = RequestContext::getMain();
		$responseFactory = new ResponseFactory( [] );
		$router = EntryPoint::createRouter(
			$this->getServiceContainer(),
			$context,
			$request,
			new ResponseFactory( [] ),
			new CorsUtils(
				new ServiceOptions(
					CorsUtils::CONSTRUCTOR_OPTIONS,
					$this->getServiceContainer()->getMainConfig()
				),
				$responseFactory,
				$context->getUser()
			)
		);

		// Rethrow unrecognised exceptions, don't just let them go out of scope
		TestingAccessWrapper::newFromObject( $router )->errorReporter = new class implements ErrorReporter {
			public function reportError(
				Throwable $error, ?Handler $handler, RequestInterface $request
			) {
				throw $error;
			}
		};

		return $router->execute( $request );
	}
}
