<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\ModuleInfo;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Rest\Reporter\PHPErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Rest\Handler\SessionHelperTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Utils\UrlUtils;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A trait providing utility function for testing the REST framework.
 * This trait is intended to be used on subclasses of MediaWikiUnitTestCase
 * or MediaWikiIntegrationTestCase.
 *
 * @stable to use
 * @since 1.39
 */
trait RestTestTrait {
	use SessionHelperTestTrait;
	use MockAuthorityTrait;
	use DummyServicesTrait;

	/**
	 * @since 1.47
	 *
	 * @param array $routeFiles route files to return from mocked getRouteFiles() call
	 * @param array $moduleModes maps module ids to module availabilities for
	 *   mocked getModuleMode() call
	 * @param array $moduleGroups maps module ids to groups for mocked ModuleInfo objects
	 * @param array $externalModules external module configuration array
	 *
	 * @return ModuleManager
	 */
	private function newMockModuleManager(
		array $routeFiles,
		array $moduleModes = [],
		array $moduleGroups = [],
		array $externalModules = []
	): ModuleManager {
		$getModuleMode = static fn ( string $moduleId ) =>
			$moduleModes[$moduleId] ?? ModuleMode::DISABLED;
		$getGroups = static fn ( string $moduleId ) =>
			(array)( $moduleGroups[$moduleId] ?? [] );

		$createModuleInfo = static fn (
			string $moduleId,
			array $moduleDefinition,
			bool $isExternal
		) => new ModuleInfo(
			$moduleId,
			$getModuleMode( $moduleId ),
			$isExternal,
			$moduleDefinition['info']['title'] ?? $moduleId,
			$moduleDefinition['info']['description'] ?? null,
			$moduleDefinition['info']['version'] ?? null,
			$getGroups( $moduleId ),
			$moduleDefinition['base'] ?? null,
			$moduleDefinition['spec'] ?? null
		);

		$mock = $this->createMock( ModuleManager::class );
		$mock->method( 'getRouteFiles' )->willReturn( $routeFiles );
		$mock->method( 'getModuleMode' )->willReturnCallback( $getModuleMode );

		$mock->method( 'getModuleInfos' )->willReturnCallback(
			static function () use ( $routeFiles, $externalModules, $createModuleInfo ) {
				$modules = [];
				// Parse local module spec files.
				foreach ( $routeFiles as $routeFile ) {
					if ( is_string( $routeFile ) && file_exists( $routeFile ) ) {
						$moduleDefinition = json_decode( file_get_contents( $routeFile ), true );
						if ( isset( $moduleDefinition['moduleId'] ) ) {
							$moduleId = $moduleDefinition['moduleId'];
							$modules[$moduleId] = $createModuleInfo(
								$moduleId,
								$moduleDefinition,
								false
							);
						}
					}
				}
				// Add the prefix-less module.
				$modules[''] = $createModuleInfo(
					'',
					[
						'info' => [
							'title' => 'MediaWiki REST API (routes not in modules)',
							'description' => 'Routes not in modules',
							'version' => '0.1.0',
						],
					],
					false
				);

				// Add external modules.
				foreach ( $externalModules as $moduleId => $externalModuleConfig ) {
					$modules[$moduleId] = $createModuleInfo(
						$moduleId,
						$externalModuleConfig,
						true
					);
				}
				// Sort: prefix-less "" first, then natural case-insensitive.
				uksort( $modules, static function ( $moduleA, $moduleB ) {
					if ( $moduleA === '' ) {
						return -1;
					}
					if ( $moduleB === '' ) {
						return 1;
					}
					return strnatcasecmp( $moduleA, $moduleB );
				} );
				return $modules;
			}
		);

		$mock->method( 'getModuleInfo' )->willReturnCallback(
			static fn ( string $moduleId ) => $mock->getModuleInfos()[$moduleId] ?? null
		);

		return $mock;
	}

	/**
	 * @param array $params Constructor parameters, as an associative array.
	 *   In addition to the actual parameters, the following pseudo-parameters
	 *   are supported:
	 *   - 'config': an associative array of configuration variables, used
	 *     to construct the 'options' parameter.
	 *   - 'request': A request object, used to construct the 'validator' parameter.
	 * @return Router
	 */
	private function newRouter( array $params = [] ) {
		$textFormatters = [
			$this->getDummyTextFormatter( true )
		];
		$showExceptionDetails = true;

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
			MainConfigNames::RestTermsOfServiceUrl => 'https://foundation.wikimedia.org/wiki/Policy:Terms_of_Use#12._API_Terms',
			MainConfigNames::Sitename => 'Test Site',
		];

		$request = $params['request'] ?? new RequestData();

		$defaultRouteFiles = [ MW_INSTALL_PATH . '/tests/phpunit/unit/includes/Rest/testRoutes.json' ];

		return new Router(
			$params['moduleManager'] ?? $this->newMockModuleManager(
				$params['routeFiles'] ?? $defaultRouteFiles
			),
			$params['extraRoutes'] ?? [],
			$params['options'] ?? new ServiceOptions( Router::CONSTRUCTOR_OPTIONS, $config ),
			$params['cacheBag'] ?? new EmptyBagOStuff(),
			$params['textFormatters'] ?? $textFormatters,
			$params['showExceptionDetails'] ?? $showExceptionDetails,
			$params['basicAuth'] ?? new StaticBasicAuthorizer(),
			$params['authority'] ?? $authority,
			$params['objectFactory'] ?? $objectFactory,
			$params['validator'] ?? new Validator( $objectFactory, $request, $authority ),
			$params['errorReporter'] ?? new PHPErrorReporter(),
			$params['hookContainer'] ?? $this->createHookContainer(),
			$params['session'] ?? $this->getSession( true ),
			$params['urlUtils'] ?? new UrlUtils( [
				UrlUtils::SERVER => $config[MainConfigNames::CanonicalServer] ?? 'https://wiki.example.com',
			] )
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
		$formatter = $params['formatter'] ?? $this->getDummyTextFormatter( true );

		$module = $this->getMockBuilder( Module::class )
			->setConstructorArgs( [
				$params['router'] ?? $this->newRouter( $params ),
				$params['pathPrefix'] ?? 'mock',
				$params['jsonLocalizer'] ?? new JsonLocalizer( $formatter ),
				$params['basicAuth'] ?? new StaticBasicAuthorizer(),
				$params['objectFactory'] ?? $objectFactory,
				$params['restValidator'] ?? new Validator( $objectFactory, $request, $authority ),
				$params['errorReporter'] ?? new PHPErrorReporter(),
				$params['hookContainer'] ?? $this->createHookContainer(),
			] )
			->onlyMethods( [] )
			->getMockForAbstractClass();

		return $module;
	}

}
