<?php

namespace MediaWiki\Rest\Module;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Rest\BasicAccess\BasicAuthorizerInterface;
use MediaWiki\Rest\Handler\RedirectHandler;
use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\RouteDefinitionException;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A Module that is based on a module definition file similar to an OpenAPI spec.
 * @see docs/rest/mwapi-1.0.json for the schema of module definition files.
 *
 * Just like an OpenAPI spec, the module definition file contains a "paths"
 * section that maps paths and HTTP methods to operations. Each operation
 * then specifies the PHP class that will handle the request under the "handler"
 * key. The value of the "handler" key is an object spec for use with
 * ObjectFactory::createObject.
 *
 * The following fields are supported as a shorthand notation:
 * - "redirect": the route represents a redirect and will be handled by
 *   the RedirectHandler class. The redirect is specified as a JSON object
 *   that specifies the target "path", and optional the redirect "code".
 *   If a redirect is defined, the "handler" key must be omitted.
 *
 * More shorthands may be added in the future.
 *
 * Route definitions can contain additional fields to configure the handler.
 * The handler can access the route definition by calling getConfig().
 *
 * @internal
 * @since 1.43
 */
class SpecBasedModule extends MatcherBasedModule {

	private string $definitionFile;

	private ?array $moduleDef = null;

	private ?int $routeFileTimestamp = null;

	private ?string $configHash = null;

	/**
	 * @internal
	 */
	public function __construct(
		string $definitionFile,
		Router $router,
		string $pathPrefix,
		ResponseFactory $responseFactory,
		BasicAuthorizerInterface $basicAuth,
		ObjectFactory $objectFactory,
		Validator $restValidator,
		ErrorReporter $errorReporter,
		HookContainer $hookContainer
	) {
		parent::__construct(
			$router,
			$pathPrefix,
			$responseFactory,
			$basicAuth,
			$objectFactory,
			$restValidator,
			$errorReporter,
			$hookContainer
		);
		$this->definitionFile = $definitionFile;
	}

	/**
	 * Get a config version hash for cache invalidation
	 */
	protected function getConfigHash(): string {
		if ( $this->configHash === null ) {
			$this->configHash = md5( json_encode( [
				'class' => __CLASS__,
				'version' => 1,
				'fileTimestamps' => $this->getRouteFileTimestamp()
			] ) );
		}
		return $this->configHash;
	}

	/**
	 * Load the module definition file.
	 */
	private function getModuleDefinition(): array {
		if ( $this->moduleDef !== null ) {
			return $this->moduleDef;
		}

		$this->routeFileTimestamp = filemtime( $this->definitionFile );
		$moduleDef = $this->loadJsonFile( $this->definitionFile );

		if ( !$moduleDef ) {
			throw new ModuleConfigurationException(
				'Malformed module definition file: ' . $this->definitionFile
			);
		}

		if ( !isset( $moduleDef['mwapi'] ) ) {
			throw new ModuleConfigurationException(
				'Missing mwapi version field in ' . $this->definitionFile
			);
		}

		// Require OpenAPI version 3.1 or compatible.
		if ( !version_compare( $moduleDef['mwapi'], '1.0.999', '<=' ) ||
			!version_compare( $moduleDef['mwapi'], '1.0.0', '>=' )
		) {
			throw new ModuleConfigurationException(
				"Unsupported openapi version {$moduleDef['mwapi']} in "
					. $this->definitionFile
			);
		}

		$localizer = new JsonLocalizer( $this->responseFactory );
		$moduleDef = $localizer->localizeJson( $moduleDef );

		$this->moduleDef = $moduleDef;
		return $this->moduleDef;
	}

	/**
	 * Get last modification times of the module definition file.
	 */
	private function getRouteFileTimestamp(): int {
		if ( $this->routeFileTimestamp === null ) {
			$this->routeFileTimestamp = filemtime( $this->definitionFile );
		}
		return $this->routeFileTimestamp;
	}

	/**
	 * @unstable for testing
	 *
	 * @return array[]
	 */
	public function getDefinedPaths(): array {
		$paths = [];
		$moduleDef = $this->getModuleDefinition();

		foreach ( $moduleDef['paths'] as $path => $pSpec ) {
			$paths[$path] = [];
			foreach ( $pSpec as $method => $opSpec ) {
				$paths[$path][] = strtoupper( $method );
			}
		}

		return $paths;
	}

	protected function initRoutes(): void {
		$moduleDef = $this->getModuleDefinition();

		// The structure is similar to OpenAPI, see docs/rest/mwapi.1.0.json
		foreach ( $moduleDef['paths'] as $path => $pathSpec ) {
			foreach ( $pathSpec as $method => $opSpec ) {
				$info = $this->makeRouteInfo( $path, $opSpec );
				$this->addRoute( $method, $path, $info );
			}
		}
	}

	/**
	 * Generate a route info array to be stored in the matcher tree,
	 * in the form expected by MatcherBasedModule::addRoute()
	 * and ultimately Module::getHandlerForPath().
	 */
	private function makeRouteInfo( string $path, array $opSpec ): array {
		static $objectSpecKeys = [
			'class',
			'factory',
			'services',
			'optional_services',
			'args',
		];

		static $oasKeys = [
			'parameters',
			'responses',
			'summary',
			'description',
			'tags',
			'externalDocs',
		];

		if ( isset( $opSpec['redirect'] ) ) {
			// Redirect shorthand
			$opSpec['handler'] = [
				'class' => RedirectHandler::class,
				'redirect' => $opSpec['redirect'],
			];
			unset( $opSpec['redirect'] );
		}

		$handlerSpec = $opSpec['handler'] ?? null;
		if ( !$handlerSpec ) {
			throw new RouteDefinitionException( 'Missing handler spec' );
		}

		$info = [
			'spec' => array_intersect_key( $handlerSpec, array_flip( $objectSpecKeys ) ),
			'config' => array_diff_key( $handlerSpec, array_flip( $objectSpecKeys ) ),
			'openApiSpec' => array_intersect_key( $opSpec, array_flip( $oasKeys ) ),
			'path' => $path,
		];

		return $info;
	}

	/** @inheritDoc */
	public function getOpenApiInfo() {
		$def = $this->getModuleDefinition();
		return $def['info'] ?? [];
	}

}
