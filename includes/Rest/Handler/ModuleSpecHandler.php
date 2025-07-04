<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
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

	public const MODULE_SPEC_PATH = '/coredev/v0/specs/module/{module}';

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

	/**
	 * @param string $moduleName
	 * @param string $version
	 */
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

	/**
	 * @see https://spec.openapis.org/oas/v3.0.0#info-object
	 */
	private function getInfoSpec( Module $module ): array {
		// TODO: Let Modules provide their name, description, version, etc
		$prefix = $module->getPathPrefix();

		if ( $prefix === '' ) {
			$title = $this->getJsonLocalizer()->getFormattedMessage( 'rest-default-module' );
		} else {
			$moduleStr = $this->getJsonLocalizer()->getFormattedMessage( 'rest-module' );
			$title = "$prefix " . $moduleStr;
		}

		return $module->getOpenApiInfo() + [
			'title' => $title,
			'version' => 'undefined',
			'license' => $this->getLicenseSpec(),
			'contact' => $this->getContactSpec(),
		];
	}

	private function getLicenseSpec(): array {
		// TODO: get terms-of-use URL, not content license.

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
		$prefix = $module->getPathPrefix();

		if ( $prefix !== '' ) {
			$prefix = "/$prefix";
		}

		return [
			[
				'url' => $this->getRouter()->getRouteUrl( $prefix ),
			]
		];
	}

	private function getPathsSpec( Module $module ): array {
		$specs = [];

		// XXX: We currently don't support meta-data on OpenAPI path objects
		//      (summary, description).

		foreach ( $module->getDefinedPaths() as $path => $methods ) {
			foreach ( $methods as $mth ) {
				$key = strtolower( $mth );
				$mth = strtoupper( $mth );
				$specs[ $path ][ $key ] = $this->getRouteSpec( $module, $path, $mth );
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

	private function getComponentsSpec( Module $module ): array {
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

	protected function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/ModuleSpec.json';
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'module' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-module-spec-module' ),
			],
			'version' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-module-spec-version' ),
			],
		];
	}

}
