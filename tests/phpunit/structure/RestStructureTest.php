<?php

use MediaWiki\Rest\EntryPoint;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Router;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * Checks that all REST Handlers, core and extensions, conform to the conventions:
 * - parameters in path have correct PARAM_SOURCE
 * - path parameters not in path are not required
 * - do not have inconsistencies in the parameter definitions
 */
class RestStructureTest extends MediaWikiIntegrationTestCase {

	/** @var Router */
	private static $router;

	/**
	 * Initialize/fetch the Router instance for testing
	 * @return Router
	 */
	private static function getRouter() {
		if ( !self::$router ) {
			$context = new DerivativeContext( RequestContext::getMain() );
			$context->setLanguage( 'en' );
			$context->setTitle(
				Title::makeTitle( NS_SPECIAL, 'Badtitle/dummy title for RestStructureTest' )
			);

			self::$router = TestingAccessWrapper::newFromClass( EntryPoint::class )
				->createRouter( $context, new RequestData() );
		}
		return self::$router;
	}

	/**
	 * @dataProvider providePathParameters
	 * @param array $spec
	 */
	public function testPathParameters( array $spec ) : void {
		$router = TestingAccessWrapper::newFromObject( self::getRouter() );
		$request = new RequestData();
		$handler = $router->createHandler( $request, $spec );
		$params = $handler->getParamSettings();

		$dataName = $this->dataName();

		// Test that all parameters in the path exist and are declared as such
		$matcher = TestingAccessWrapper::newFromObject( new PathMatcher );
		$pathParams = [];
		foreach ( explode( '/', $spec['path'] ) as $part ) {
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

	public static function providePathParameters() : Iterator {
		$router = TestingAccessWrapper::newFromObject( self::getRouter() );

		foreach ( $router->getAllRoutes() as $spec ) {
			$method = $spec['method'] ?? 'GET';
			$method = implode( ",", (array)$method );

			yield "Handler {$method} {$spec['path']}" => [ $spec ];
		}
	}

	/**
	 * @dataProvider provideParameters
	 * @param array $spec
	 * @param string $name
	 * @param mixed $settings
	 */
	public function testParameters( array $spec, string $name, $settings ) : void {
		static $sources = [ 'path', 'query', 'post' ];

		$router = TestingAccessWrapper::newFromObject( self::getRouter() );
		$request = new RequestData();

		$dataName = $this->dataName();
		$this->assertNotSame( '', $name, "$dataName: Name cannot be empty" );

		$paramValidator = TestingAccessWrapper::newFromObject( $router->restValidator )->paramValidator;
		$ret = $paramValidator->checkSettings( $name, $settings, [ 'source' => 'unspecified' ] );

		// REST-specific parameters
		$ret['allowedKeys'][] = Handler::PARAM_SOURCE;
		if ( !in_array( $settings[Handler::PARAM_SOURCE] ?? '', $sources, true ) ) {
			$ret['issues'][Handler::PARAM_SOURCE] = "PARAM_SOURCE must be 'path', 'query', or 'post'";
		}

		// Warn about unknown keys. Don't fail, they might be for forward- or back-compat.
		if ( is_array( $settings ) ) {
			$keys = array_diff(
				array_keys( $settings ),
				$ret['allowedKeys']
			);
			if ( $keys ) {
				$this->addWarning(
					"$dataName: Unrecognized settings keys were used: " . implode( ', ', $keys )
				);
			}
		}

		if ( count( $ret['issues'] ) === 1 ) {
			$this->fail( "$dataName: Validation failed: " . reset( $ret['issues'] ) );
		} elseif ( $ret['issues'] ) {
			$this->fail( "$dataName: Validation failed:\n* " . implode( "\n* ", $ret['issues'] ) );
		}

		// Check message existence
		$done = [];
		foreach ( $ret['messages'] as $msg ) {
			// We don't really care about the parameters, so do it simply
			$key = $msg->getKey();
			if ( !isset( $done[$key] ) ) {
				$done[$key] = true;
				$this->assertTrue( Message::newFromKey( $key )->exists(),
					"$dataName: Parameter message $key exists" );
			}
		}
	}

	public static function provideParameters() : Iterator {
		$router = TestingAccessWrapper::newFromObject( self::getRouter() );
		$request = new RequestData();

		foreach ( $router->getAllRoutes() as $spec ) {
			$handler = $router->createHandler( $request, $spec );
			$params = $handler->getParamSettings();
			foreach ( $params as $param => $settings ) {
				$method = $spec['method'] ?? 'GET';
				$method = implode( ",", (array)$method );

				yield "Handler {$method} {$spec['path']}, parameter $param" => [ $spec, $param, $settings ];
			}
		}
	}

	public function testRoutePathAndMethodForDuplicates() {
		$router = TestingAccessWrapper::newFromObject( self::getRouter() );
		$routes = [];

		foreach ( $router->getAllRoutes() as $spec ) {
			$method = $spec['method'] ?? 'GET';
			$method = (array)$method;

			foreach ( $method as $m ) {
				$key = "{$m} {$spec['path']}";

				$this->assertFalse( array_key_exists( $key, $routes ), "{$key} already exists in routes" );

				$routes[$key] = true;
			}
		}
	}
}
