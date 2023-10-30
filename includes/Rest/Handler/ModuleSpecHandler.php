<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Validator\Validator;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Core REST API endpoint that outputs an OpenAPI spec of a set of routes.
 */
class ModuleSpecHandler extends SimpleHandler {

	/**
	 * @internal
	 */
	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RightsUrl,
		MainConfigNames::RightsText,
		MainConfigNames::EmergencyContact,
		MainConfigNames::Sitename,
	];

	private ServiceOptions $options;

	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	public function run( $moduleName, $version = '' ): array {
		// TODO: implement caching, get cache key from Router.

		if ( $version !== '' ) {
			$moduleName .= '/' . $version;
		}

		if ( $moduleName === '-' ) {
			// Hack that allows us to fetch a spec for the empty module prefix
			$moduleName = '';
		}

		$module = $this->getRouter()->getModule( $moduleName );

		if ( !$module ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->params( $moduleName ),
				404
			);
		}

		return [
			'openapi' => '3.0.0',
			'info' => $this->getInfoSpec( $module ),
			'servers' => $this->getServerSpec( $module ),
			'paths' => $this->getPathsSpec( $module ),
			'components' => $this->getComponentsSpec( $module ),
		];
	}

	private function getInfoSpec( Module $module ): array {
		// TODO: Let Modules provide their name, description, version, etc
		return [
			'title' => $module->getPathPrefix(),
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

	private function getServerSpec( Module $module ): array {
		return [
			[
				'url' => $this->getRouter()->getRouteUrl( '/' . $module->getPathPrefix() ),
			]
		];
	}

	private function getPathsSpec( Module $module ): array {
		$specs = [];

		foreach ( $module->getDefinedPaths() as $path => $methods ) {
			foreach ( $methods as $mth ) {
				$mth = strtolower( $mth );
				$specs[ $path ][ $mth ] = $this->getRouteSpec( $module, $path, $mth );
			}
		}

		return $specs;
	}

	private function getRouteSpec( Module $module, string $path, string $method ): array {
		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$operationSpec = $handler->getOpenApiSpec( $method );

		return $operationSpec;
	}

	private function getComponentsSpec( Module $module ) {
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

	public function getParamSettings() {
		return [
			'module' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'version' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
			],
		];
	}

}
