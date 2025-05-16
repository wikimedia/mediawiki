<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\ParamValidator\TypeDef\ArrayDef;
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
use Wikimedia\Message\MessageValue;
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
	use JsonSchemaAssertionTrait;

	private const SPEC_FILES = [
		'https://spec.openapis.org/oas/3.0/schema/2021-09-28#' =>
			MW_INSTALL_PATH . '/tests/phpunit/integration/includes/' .
				'Rest/Handler/data/OpenApi-3.0.json',

		'http://json-schema.org/draft-04/schema#' =>
			MW_INSTALL_PATH . '/vendor/justinrainbow/json-schema/dist/' .
				'schema/json-schema-draft-04.json',

		'https://www.mediawiki.org/schema/mwapi-1.0#' =>
			MW_INSTALL_PATH . '/docs/rest/mwapi-1.0.json',

		'https://www.mediawiki.org/schema/discovery-1.0#' =>
			MW_INSTALL_PATH . '/docs/rest/discovery-1.0.json',
	];

	/** @var ?Router */
	private $router = null;

	/**
	 * Constructs a fake MediaWikiServices instance for use in data providers.
	 */
	private function getFakeServiceContainer(): MediaWikiServices {
		$realConfig = MediaWikiServices::getInstance()->getMainConfig();

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
		$services->method( 'getMainConfig' )->willReturn( $realConfig );
		$services->method( 'getHookContainer' )->willReturn( $hookContainer );
		$services->method( 'getObjectFactory' )->willReturn( $objectFactory );
		$services->method( 'getLocalServerObjectCache' )->willReturn( new EmptyBagOStuff() );
		$services->method( 'getStatsFactory' )->willReturn( StatsFactory::newNull() );

		return $services;
	}

	/**
	 * Initialize/fetch the Router instance for testing
	 * @warning Must not be called in data providers!
	 * @return Router
	 */
	private function getTestRouter(): Router {
		if ( !$this->router ) {
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

			$responseFactory = $this->createNoOpMock( ResponseFactory::class, [ 'getFormattedMessage' ] );
			$responseFactory->method( 'getFormattedMessage' )->willReturn( '' );

			$cors = $this->createNoOpMock( CorsUtils::class );

			$this->router = EntryPoint::createRouter(
				$this->getServiceContainer(), $context, new RequestData(), $responseFactory, $cors
			);
		}
		return $this->router;
	}

	public function testPathParameters(): void {
		$matcher = TestingAccessWrapper::newFromObject( new PathMatcher );

		$router = $this->getTestRouter();
		foreach ( $this->generateRoutesTestData( $router ) as [ $moduleName, $module, $method, $path ] ) {
			$message = "Handler in module '$moduleName' for $method $path.";
			$request = new RequestData( [ 'method' => $method ] );
			$handler = $module->getHandlerForPath( $path, $request, false );

			$params = $handler->getParamSettings();

			// Test that all parameters in the path exist and are declared as such
			$pathParams = [];
			foreach ( explode( '/', $path ) as $part ) {
				$param = $matcher->getParamName( $part );
				if ( $param !== false ) {
					$this->assertArrayHasKey( $param, $params, $message . " Path parameter $param exists" );
					$this->assertSame( 'path', $params[$param][Handler::PARAM_SOURCE] ?? null,
						$message . " Path parameter {{$param}} must have PARAM_SOURCE = 'path'" );
					$pathParams[$param] = true;
				}
			}

			// Test that any path parameters not in the path aren't marked as required
			foreach ( $params as $param => $settings ) {
				if ( ( $settings[Handler::PARAM_SOURCE] ?? null ) === 'path' &&
					!isset( $pathParams[$param] )
				) {
					$this->assertFalse( $settings[ParamValidator::PARAM_REQUIRED] ?? false,
						$message . " Parameter $param: PARAM_REQUIRED cannot be true for a path parameter "
						. 'not in the path'
					);
				}
			}
		}

		// In case there were no path parameters
		$this->addToAssertionCount( 1 );
	}

	public function testBodyParameters(): void {
		$router = $this->getTestRouter();
		foreach ( $this->generateRoutesTestData( $router ) as [ $moduleName, $module, $method, $path ] ) {
			$message = "Handler in module '$moduleName' for $method $path.";
			$request = new RequestData( [ 'method' => $method ] );
			$handler = $module->getHandlerForPath( $path, $request, false );

			$bodySettings = $handler->getBodyParamSettings();

			if ( !$bodySettings ) {
				continue;
			}

			foreach ( $bodySettings as $settings ) {
				$this->assertArrayHasKey( Handler::PARAM_SOURCE, $settings, $message );
				$this->assertSame( 'body', $settings[Handler::PARAM_SOURCE], $message );

				if ( isset( $settings[ ArrayDef::PARAM_SCHEMA ] ) ) {
					try {
						$this->assertValidJsonSchema( $settings[ ArrayDef::PARAM_SCHEMA ], $message );
					} catch ( LogicException $e ) {
						$this->fail( $message . " Invalid JSON schema for parameter {$settings['name']}: " . $e->getMessage() );
					}
				}
			}
		}
		$this->addToAssertionCount( 1 );
	}

	public function testBodyParametersNotInParamSettings(): void {
		$router = $this->getTestRouter();
		foreach ( $this->generateRoutesTestData( $router ) as [ $moduleName, $module, $method, $path ] ) {
			$message = "Handler in module '$moduleName' for $method $path.";
			$request = new RequestData( [ 'method' => $method ] );
			$handler = $module->getHandlerForPath( $path, $request, false );

			$paramSettings = $handler->getParamSettings();

			if ( !$paramSettings ) {
				continue;
			}

			foreach ( $paramSettings as $settings ) {
				$this->assertArrayHasKey( Handler::PARAM_SOURCE, $settings, $message );
				$this->assertNotSame( 'body', $settings[Handler::PARAM_SOURCE], $message );
			}
		}
		$this->addToAssertionCount( 1 );
	}

	public function generateRoutesTestData( Router $router ): Iterator {
		foreach ( $router->getModuleIds() as $moduleName ) {
			$module = $router->getModule( $moduleName );

			foreach ( $module->getDefinedPaths() as $path => $methods ) {

				foreach ( $methods as $method ) {
					yield "Handler in module '$moduleName' for $method $path"
						=> [ $moduleName, $module, $method, $path ];
				}
			}
		}
	}

	public function testParameters(): void {
		$router = $this->getTestRouter();
		foreach ( $this->generateRoutesTestData( $router ) as [ $moduleName, $module, $method, $path ] ) {
			$message = "Handler in module '$moduleName' for $method $path.";
			$request = new RequestData( [ 'method' => $method ] );
			$handler = $module->getHandlerForPath( $path, $request, false );

			$params = $handler->getParamSettings();
			foreach ( $params as $param => $settings ) {
				$this->assertParameter( $param, $settings, $message . " Parameter $param" );
			}
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

		// Check that "array" type is not used in getParamSettings
		if ( isset( $settings[ParamValidator::PARAM_TYPE] ) && $settings[ParamValidator::PARAM_TYPE] === 'array' ) {
			$this->fail( "$msg: $dataName: 'array' type is not allowed in getParamSettings" );
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

		$description = $settings[Handler::PARAM_DESCRIPTION] ?? null;
		if ( $description && !is_string( $description ) ) {
			$this->assertInstanceOf( MessageValue::class, $description );
			$this->assertTrue(
				wfMessage( $description->getKey() )->exists(),
				'Message key of parameter description should exit: '
				. $description->getKey()
			);
		}
	}

	public function testRoutePathAndMethodForDuplicates() {
		$router = $this->getTestRouter();
		$routes = [];

		foreach ( $router->getModuleIds() as $moduleName ) {
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

	public static function provideModuleDefinitionFiles() {
		$conf = MediaWikiServices::getInstance()->getMainConfig();
		$entryPoint = TestingAccessWrapper::newFromClass( EntryPoint::class );
		$routeFiles = $entryPoint->getRouteFiles( $conf );

		foreach ( $routeFiles as $file ) {
			$moduleSpec = self::loadJsonData( $file );
			if ( !isset( $moduleSpec->mwapi ) ) {
				// old-school flat route file, skip
				continue;
			}
			yield $file => [ $moduleSpec ];
		}
	}

	/**
	 * @dataProvider provideModuleDefinitionFiles
	 */
	public function testModuleDefinitionFiles( stdClass $moduleSpec ) {
		$schemaFile = MW_INSTALL_PATH . '/docs/rest/mwapi-1.0.json';

		$this->assertMatchesJsonSchema( $schemaFile, $moduleSpec, self::SPEC_FILES );
	}

	public function testGetModuleDescription(): void {
		static $infoSchema = [ '$ref' =>
			'https://www.mediawiki.org/schema/discovery-1.0#/definitions/Module'
		];

		$router = $this->getTestRouter();
		foreach ( $router->getModuleIds() as $moduleName ) {
			$module = $router->getModule( $moduleName );
			$info = $module->getModuleDescription();

			$this->assertMatchesJsonSchema( $infoSchema, $info, self::SPEC_FILES, "Module '$moduleName'" );
		}
	}

	public function testGetOpenApiInfo(): void {
		static $infoSchema = [ '$ref' =>
			'https://spec.openapis.org/oas/3.0/schema/2021-09-28#/definitions/Info'
		];

		$router = $this->getTestRouter();
		foreach ( $router->getModuleIds() as $moduleName ) {
			$module = $router->getModule( $moduleName );
			$info = $module->getOpenApiInfo();

			$this->assertMatchesJsonSchema( $infoSchema, $info, self::SPEC_FILES, "Module '$moduleName'" );
		}
	}

}
