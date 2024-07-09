<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\MainConfigSchema;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Request\WebRequest;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * Checks that all REST Handlers, core and extensions, conform to the conventions:
 * - parameters in path have correct PARAM_SOURCE
 * - path parameters not in path are not required
 * - do not have inconsistencies in the parameter definitions
 *
 * @coversNothing
 * @group Database
 */
class RestStructureTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	/** @var ?Router */
	private $router = null;

	/**
	 * Constructs a fake MediaWikiServices instance for use in data providers.
	 *
	 * @return MediaWikiServices
	 */
	private function getFakeServiceContainer(): MediaWikiServices {
		$config = new HashConfig( iterator_to_array( MainConfigSchema::listDefaultValues() ) );

		$objectFactory = $this->getDummyObjectFactory();
		$hookContainer = new HookContainer(
			new StaticHookRegistry(),
			$objectFactory
		);

		$services = $this->createNoOpMock(
			MediaWikiServices::class,
			[
				'getMainConfig',
				'getHookContainer',
				'getObjectFactory',
				'getLocalServerObjectCache',
				'getStatsFactory',
			]
		);
		$services->method( 'getMainConfig' )->willReturn( $config );
		$services->method( 'getHookContainer' )->willReturn( $hookContainer );
		$services->method( 'getObjectFactory' )->willReturn( $objectFactory );
		$services->method( 'getLocalServerObjectCache' )->willReturn( new EmptyBagOStuff() );
		$services->method( 'getStatsFactory' )->willReturn( StatsFactory::newNull() );

		return $services;
	}

	private function getRouterForDataProviders(): Router {
		static $router = null;

		if ( !$router ) {
			$language = $this->createNoOpMock( Language::class, [ 'getCode' ] );
			$language->method( 'getCode' )->willReturn( 'en' );

			$title = Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for RestStructureTest' );
			$authority = new SimpleAuthority( new UserIdentityValue( 0, 'Testor' ), [] );

			$request = $this->createNoOpMock( WebRequest::class, [ 'getSession' ] );
			$request->method( 'getSession' )->willReturn( $this->createNoOpMock( Session::class ) );

			$context = $this->createNoOpMock(
				RequestContext::class,
				[ 'getLanguage', 'getTitle', 'getAuthority', 'getRequest' ]
			);
			$context->method( 'getLanguage' )->willReturn( $language );
			$context->method( 'getTitle' )->willReturn( $title );
			$context->method( 'getAuthority' )->willReturn( $authority );
			$context->method( 'getRequest' )->willReturn( $request );

			$responseFactory = $this->createNoOpMock( ResponseFactory::class );
			$cors = $this->createNoOpMock( CorsUtils::class );

			$services = $this->getFakeServiceContainer();

			// NOTE: createRouter() implements the logic for determining the list of route files to load.
			$entryPoint = TestingAccessWrapper::newFromClass( EntryPoint::class );
			$router = $entryPoint->createRouter(
				$services,
				$context,
				new RequestData(),
				$responseFactory,
				$cors
			);
		}

		return $router;
	}

	/**
	 * Initialize/fetch the Router instance for testing
	 * @warning Must not be called in data providers!
	 * @return Router
	 */
	private function getTestRouter(): Router {
		if ( !$this->router ) {
			$context = new DerivativeContext( RequestContext::getMain() );
			$context->setLanguage( 'en' );
			$context->setTitle(
				Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for RestStructureTest' )
			);

			$responseFactory = $this->createNoOpMock( ResponseFactory::class );
			$cors = $this->createNoOpMock( CorsUtils::class );

			$this->router = EntryPoint::createRouter(
				$this->getServiceContainer(), $context, new RequestData(), $responseFactory, $cors
			);
		}
		return $this->router;
	}

	/**
	 * @dataProvider provideRoutes
	 */
	public function testPathParameters( string $moduleName, string $method, string $path ): void {
		$router = $this->getTestRouter();
		$module = $router->getModule( $moduleName );

		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$params = $handler->getParamSettings();
		$dataName = $this->dataName();

		// Test that all parameters in the path exist and are declared as such
		$matcher = TestingAccessWrapper::newFromObject( new PathMatcher );
		$pathParams = [];
		foreach ( explode( '/', $path ) as $part ) {
			$param = $matcher->getParamName( $part );
			if ( $param !== false ) {
				$this->assertArrayHasKey( $param, $params, "Path parameter $param exists" );
				$this->assertSame( 'path', $params[$param][Handler::PARAM_SOURCE] ?? null,
					"$dataName: Path parameter {{$param}} must have PARAM_SOURCE = 'path'" );
				$pathParams[$param] = true;
			}
		}

		// Test that any path parameters not in the path aren't marked as required
		foreach ( $params as $param => $settings ) {
			if ( ( $settings[Handler::PARAM_SOURCE] ?? null ) === 'path' &&
				!isset( $pathParams[$param] )
			) {
				$this->assertFalse( $settings[ParamValidator::PARAM_REQUIRED] ?? false,
					"$dataName, parameter $param: PARAM_REQUIRED cannot be true for a path parameter "
					. 'not in the path'
				);
			}
		}

		// In case there were no path parameters
		$this->addToAssertionCount( 1 );
	}

	/**
	 * @dataProvider provideRoutes
	 */
	public function testBodyParameters( string $moduleName, string $method, string $path ): void {
		$router = $this->getTestRouter();
		$module = $router->getModule( $moduleName );

		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$paramSettings = $handler->getParamSettings();
		$bodySettings = $handler->getBodyParamSettings();

		$bodySettingsFromParams = array_filter(
			$paramSettings,
			static function ( array $settings ) {
				return $settings[ Handler::PARAM_SOURCE ] === 'body';
			}
		);

		if ( !$bodySettings && !$bodySettingsFromParams ) {
			$this->addToAssertionCount( 1 );
			return;
		}

		if ( $bodySettingsFromParams ) {
			$this->assertSame(
				$bodySettingsFromParams,
				$bodySettings,
				'If getParamSettings and getBodyParamSettings both return body ' .
				'parameters, they must return the same body parameters.' . "\n" .
				'When overriding getBodyParamSettings, do not return body ' .
				'parameters from getParamSettings.'
			);
		}

		foreach ( $bodySettings as $settings ) {
			$this->assertArrayHasKey( Handler::PARAM_SOURCE, $settings );
			$this->assertSame( 'body', $settings[Handler::PARAM_SOURCE] );
		}
	}

	public function provideModules(): Iterator {
		$router = $this->getRouterForDataProviders();

		foreach ( $router->getModuleNames() as $name ) {
			yield "Module '$name'" => [ $name ];
		}
	}

	public function provideRoutes(): Iterator {
		$router = $this->getRouterForDataProviders();

		foreach ( $router->getModuleNames() as $moduleName ) {
			$module = $router->getModule( $moduleName );

			foreach ( $module->getDefinedPaths() as $path => $methods ) {

				foreach ( $methods as $method ) {
					// NOTE: we can't use the $module object directly, since it
					//       may hold references to incorrect service instance.
					yield "Handler in module '$moduleName' for $method $path"
						=> [ $moduleName, $method, $path ];
				}
			}
		}
	}

	/**
	 * @dataProvider provideRoutes
	 */
	public function testParameters( string $moduleName, string $method, string $path ): void {
		$router = $this->getTestRouter();
		$module = $router->getModule( $moduleName );

		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$params = $handler->getParamSettings();
		foreach ( $params as $param => $settings ) {
			$method = $routeSpec['method'] ?? 'GET';
			$method = implode( ",", (array)$method );

			$this->assertParameter( $param, $settings, "Handler {$method} {$path}, parameter $param" );
		}
	}

	private function assertParameter( string $name, $settings, $msg ) {
		$router = TestingAccessWrapper::newFromObject( $this->getTestRouter() );

		$dataName = $this->dataName();
		$this->assertNotSame( '', $name, "$msg: $dataName: Name cannot be empty" );

		$paramValidator = TestingAccessWrapper::newFromObject( $router->restValidator )->paramValidator;
		$ret = $paramValidator->checkSettings( $name, $settings, [ 'source' => 'unspecified' ] );

		// REST-specific parameters
		$ret['allowedKeys'][] = Handler::PARAM_SOURCE;
		$ret['allowedKeys'][] = Handler::PARAM_DESCRIPTION;
		if ( !in_array( $settings[Handler::PARAM_SOURCE] ?? '', Validator::KNOWN_PARAM_SOURCES, true ) ) {
			$ret['issues'][Handler::PARAM_SOURCE] = "PARAM_SOURCE must be one of " . implode( ', ', Validator::KNOWN_PARAM_SOURCES );
		}

		// Warn about unknown keys. Don't fail, they might be for forward- or back-compat.
		if ( is_array( $settings ) ) {
			$keys = array_diff(
				array_keys( $settings ),
				$ret['allowedKeys']
			);
			if ( $keys ) {
				$this->addWarning(
					"$msg: $dataName: Unrecognized settings keys were used: " . implode( ', ', $keys )
				);
			}
		}

		if ( count( $ret['issues'] ) === 1 ) {
			$this->fail( "$msg: $dataName: Validation failed: " . reset( $ret['issues'] ) );
		} elseif ( $ret['issues'] ) {
			$this->fail( "$msg: $dataName: Validation failed:\n* " . implode( "\n* ", $ret['issues'] ) );
		}

		// Check message existence
		$done = [];
		foreach ( $ret['messages'] as $msg ) {
			// We don't really care about the parameters, so do it simply
			$key = $msg->getKey();
			if ( !isset( $done[$key] ) ) {
				$done[$key] = true;
				$this->assertTrue( Message::newFromKey( $key )->exists(),
					"$msg: $dataName: Parameter message $key exists" );
			}
		}
	}

	public function testRoutePathAndMethodForDuplicates() {
		$router = $this->getTestRouter();
		$routes = [];

		foreach ( $router->getModuleNames() as $moduleName ) {
			$module = $router->getModule( $moduleName );
			$paths = $module->getDefinedPaths();

			foreach ( $paths as $path => $methods ) {
				foreach ( $methods as $method ) {
					// NOTE: we can't use the $module object directly, since it
					//       may hold references to incorrect service instance.
					$key = "$moduleName: $method $path";

					$this->assertArrayNotHasKey( $key, $routes, "{$key} already exists in routes" );
					$routes[$key] = true;
				}
			}
		}
	}

}
