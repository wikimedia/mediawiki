<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Validator\Validator;

/**
 * Core REST API endpoint that outputs an OpenAPI spec of a set of routes.
 */
class RootSpecHandler extends SimpleHandler {
	/**
	 * @internal
	 */
	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RightsUrl,
		MainConfigNames::RightsText,
		MainConfigNames::EmergencyContact,
		MainConfigNames::Sitename,
	];

	/** @var ServiceOptions */
	private ServiceOptions $options;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	public function run(): array {
		// TODO: implement caching, get cache key from Router.

		return [
			'openapi' => '3.0.0',
			'info' => $this->getInfoSpec(),
			'servers' => $this->getServerSpec(),
			'paths' => $this->getPathsSpec(),
			'components' => $this->getComponentsSpec(),
		];
	}

	private function getInfoSpec(): array {
		return [
			'title' => $this->options->get( MainConfigNames::Sitename ),
			'version' => MW_VERSION,
			'license' => $this->getLicenseSpec(),
			'contact' => $this->getContactSpec(),
		];
	}

	private function getLicenseSpec(): array {
		return [
			'name' => $this->options->get( MainConfigNames::RightsText ),
			'url' => $this->options->get( MainConfigNames::RightsUrl ),
		];
	}

	private function getContactSpec(): array {
		return [
			'email' => $this->options->get( MainConfigNames::EmergencyContact ),
		];
	}

	private function getServerSpec(): array {
		return [
			[
				'url' => $this->getRouter()->getRouteUrl( '/' ),
			]
		];
	}

	private function getPathsSpec(): array {
		$specs = [];

		foreach ( $this->getRouter()->getAllRoutes() as $route ) {
			$path = $route['path'];
			$methods = $route['method'] ?? [ 'get' ];

			foreach ( (array)$methods as $mth ) {
				$mth = strtolower( $mth );
				$specs[ $path ][ $mth ] = $this->getRouteSpec( $route, $mth );
			}
		}

		return $specs;
	}

	private function getRouteSpec( array $handlerObjectSpec, string $method ): array {
		$handler = $this->getRouter()->instantiateHandlerObject( $handlerObjectSpec );

		$operationSpec = $handler->getOpenApiSpec( $method );

		$overrides = array_intersect_key(
			$handlerObjectSpec,
			array_flip( [ 'description', 'summary', 'tags', 'deprecated', 'externalDocs', 'security' ] )
		);

		$operationSpec = $overrides + $operationSpec;

		return $operationSpec;
	}

	private function getComponentsSpec() {
		$components = [];

		// XXX: also collect reusable components from handler specs (but how to avoid name collisions?).
		$componentsSources = [
			[ 'schemas' => Validator::getParameterTypeSchemas() ],
			ResponseFactory::getResponseComponents()
		];

		// 2D merge
		foreach ( $componentsSources as $cmps ) {
			foreach ( $cmps as $name => $cmp ) {
				$components[$name] = array_merge( $components[$name] ?? [], $cmp );
			}
		}

		return $components;
	}

}
