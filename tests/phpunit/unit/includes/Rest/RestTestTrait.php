<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Reporter\PHPErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Rest\Handler\SessionHelperTestTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A trait providing utility function for testing the REST framework.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 */
trait RestTestTrait {
	use SessionHelperTestTrait;
	use MockAuthorityTrait;

	/**
	 * @param array $params Constructor parameters, as an associative array.
	 *        In addition to the actual parameters, the following pseudo-parameters
	 *        are supported:
	 *        - 'config': an associative array of configuration variables, used
	 *          to construct the 'options' parameter.
	 *        - 'request': A request object, used to construct the 'validator' parameter.
	 * @return Router
	 */
	private function newRouter( array $params = [] ) {
		$responseFactory = new ResponseFactory( [] );
		$responseFactory->setShowExceptionDetails( true );

		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$authority = $params['authority'] ?? $this->mockAnonUltimateAuthority();

		$config = ( $params['config'] ?? [] ) + [
			MainConfigNames::CanonicalServer => 'https://wiki.example.com',
			MainConfigNames::InternalServer => 'http://api.local:8080',
			MainConfigNames::RestPath => '/rest',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::RightsUrl => 'https://rights.url',
			MainConfigNames::RightsText => 'your rights',
			MainConfigNames::EmergencyContact => 'admin@test.test',
			MainConfigNames::Sitename => 'Test Site',
		];

		$request = $params['request'] ?? new RequestData();

		return new Router(
			$params['routeFiles'] ?? [ MW_INSTALL_PATH . '/tests/phpunit/unit/includes/Rest/testRoutes.json' ],
			$params['extraRoutes'] ?? [],
			$params['options'] ?? new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $config ),
			$params['cacheBag'] ?? new EmptyBagOStuff(),
			$params['responseFactory'] ?? $responseFactory,
			$params['basicAuth'] ?? new StaticBasicAuthorizer(),
			$params['authority'] ?? $authority,
			$params['objectFactory'] ?? $objectFactory,
			$params['validator'] ?? new Validator( $objectFactory, $request, $authority ),
			$params['errorReporter'] ?? new PHPErrorReporter(),
			$params['hookContainer'] ?? $this->createHookContainer(),
			$params['session'] ?? $this->getSession( true )
		);
	}

	/**
	 * @since 1.43
	 * @param array $params Constructor parameters for Module and Router, as an associative array.
	 * @return Module
	 */
	private function newModule( array $params ) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);

		$authority = $params['authority'] ?? $this->mockAnonUltimateAuthority();
		$request = $params['request'] ?? new RequestData();

		$module = $this->getMockBuilder( Module::class )
			->setConstructorArgs( [
				$params['router'] ?? $this->newRouter( $params ),
				$params['pathPrefix'] ?? 'mock',
				$params['responseFactory'] ?? new ResponseFactory( [] ),
				$params['basicAuth'] ?? new StaticBasicAuthorizer(),
				$params['objectFactory'] ?? $objectFactory,
				$params['restValidator'] ?? new Validator( $objectFactory, $request, $authority ),
				$params['errorReporter'] ?? new PHPErrorReporter()
			] )
			->onlyMethods( [] )
			->getMockForAbstractClass();

		return $module;
	}

}
