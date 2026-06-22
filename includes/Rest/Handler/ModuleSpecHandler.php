<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
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
		MainConfigNames::RestExternalModules,
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
	 *
	 * @return array|Response OpenAPI operation object, or Response object if redirect is needed
	 */
	public function run( $moduleName, $version = '' ): array|Response {
		// TODO: implement caching, get cache key from Router.

		if ( $version !== '' ) {
			$moduleName .= '/' . $version;
		}

		$mode = null;
		if ( $moduleName === '-' ) {
			// Hack that allows us to fetch a spec for the empty module prefix
			$moduleName = '';
			$mode = ModuleMode::PUBLISHED;
		}

		// Suppress OpenAPI spec for HIDDEN or DISABLED modules. This is not a security or
		// protection mechanism. MediaWiki is open source, so callers can learn the details of
		// its endpoints.  This is just a way to hide the spec in cases where it should not be
		// available.
		$mode ??= $this->getRouter()->getModuleManager()->getModuleMode( $moduleName );
		if ( $mode === ModuleMode::HIDDEN || $mode === ModuleMode::DISABLED ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unavailable-spec' )->params( $moduleName ),
				403
			);
		}

		// If this is an external module, redirect to its spec
		$restExternalModules = $this->options->get( MainConfigNames::RestExternalModules );
		$em = $restExternalModules[$moduleName] ?? null;
		if ( $em ) {
			$response = $this->getResponseFactory()->createPermanentRedirect( $em['spec'] );
			return $response;
		}

		$module = $this->getRouter()->getModule( $moduleName );
		if ( !$module ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->params( $moduleName ),
				404
			);
		}

		$spec = [
			'openapi' => '3.0.0',
			'info' => $this->getInfoSpec( $module ),
			'servers' => $this->getServerSpec( $module ),
			'externalDocs' => $module->getOpenApiExternalDocs(),
			'tags' => $module->getOpenApiTags(),
			'paths' => $this->getPathsSpec( $module ),
			'components' => $this->getComponentsSpec(),
		];

		unset( $spec['info']['deprecationSettings'] );

		if ( !$spec['externalDocs'] ) {
			unset( $spec['externalDocs'] );
		}

		if ( empty( $spec['tags'] ) ) {
			unset( $spec['tags'] );
		}

		return $spec;
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
		$usedOpIds = [];

		// XXX: We currently don't support meta-data on OpenAPI path objects
		//      (summary, description).

		foreach ( $module->getDefinedPaths() as $path => $methods ) {
			foreach ( $methods as $mth ) {
				$key = strtolower( $mth );
				$mth = strtoupper( $mth );
				$specs[ $path ][ $key ] = $this->getRouteSpec( $module, $path, $mth, $usedOpIds );
			}
		}

		return $specs;
	}

	/**
	 * Build the OpenAPI operation object for a single route.
	 *
	 * Operation IDs are arbitrary opaque strings required to be unique within
	 * this spec, but they carry no meaning outside it and need not be unique
	 * across different OpenAPI specs generated by other modules or wikis.
	 *
	 * @param Module $module
	 * @param string $path Route path, e.g. "/v1/page/{title}"
	 * @param string $method HTTP method (case-insensitive)
	 * @param array &$usedOpIds Operation IDs already assigned in this spec,
	 *   updated in-place to include the ID assigned here
	 * @return array OpenAPI operation object
	 */
	private function getRouteSpec( Module $module, string $path, string $method, array &$usedOpIds ): array {
		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$operationSpec = $handler->getOpenApiSpec( $method );

		// If the spec already contains an explicit operationId (e.g. set in the JSON
		// definition file via $oasKeys), respect it. Otherwise auto-generate one.
		if ( !isset( $operationSpec['operationId'] ) ) {
			$baseId = self::generateOperationId(
				$method,
				$operationSpec['summary'] ?? null,
				$path
			);
			$operationId = $baseId;
			$counter = 2;
			while ( in_array( $operationId, $usedOpIds, true ) ) {
				$operationId = $baseId . $counter;
				$counter++;
			}
			$operationSpec['operationId'] = $operationId;
		}

		$usedOpIds[] = $operationSpec['operationId'];

		return $operationSpec;
	}

	/**
	 * Generate an operationId for an operation.
	 *
	 * Uses the summary when available (more readable), falls back to the path.
	 *
	 * @param string $method HTTP method (case-insensitive; will be normalized to lowercase)
	 * @param string|null $summary Localized summary, or null/empty if absent
	 * @param string $path Route path, e.g. "/v1/page/{title}"
	 * @return string camelCase operationId
	 */
	private static function generateOperationId(
		string $method,
		?string $summary,
		string $path
	): string {
		if ( $summary !== null && trim( $summary ) !== '' ) {
			return self::summaryToOperationId( $method, $summary );
		}
		return self::pathToOperationId( $method, $path );
	}

	/**
	 * Derive an operationId from the HTTP method and operation summary.
	 *
	 * Converts the summary to camelCase and prepends the HTTP method in lowercase.
	 * Example: method=GET, summary="Search pages" → "getSearchPages"
	 *
	 * @param string $method HTTP method (case-insensitive)
	 * @param string $summary The operation summary string
	 * @return string camelCase operationId
	 */
	private static function summaryToOperationId( string $method, string $summary ): string {
		// Replace any non-alphanumeric character with a space, then split into words.
		$clean = preg_replace( '/[^a-zA-Z0-9]/', ' ', $summary );
		$words = preg_split( '/\s+/', trim( $clean ), -1, PREG_SPLIT_NO_EMPTY );
		$id = strtolower( $method );
		foreach ( $words as $word ) {
			$id .= ucfirst( strtolower( $word ) );
		}
		return $id;
	}

	/**
	 * Derive an operationId from the HTTP method and route path.
	 * Used as a fallback when no summary is available.
	 *
	 * Path parameters ({name}) become "ByName". Hyphens, underscores and other
	 * non-alphanumeric characters act as word separators.
	 * Example: method=GET, path="/v1/page/{title}/links" → "getV1PageByTitleLinks"
	 *
	 * @param string $method HTTP method (case-insensitive)
	 * @param string $path Route path, e.g. "/v1/page/{title}/links"
	 * @return string camelCase operationId
	 */
	private static function pathToOperationId( string $method, string $path ): string {
		$segments = explode( '/', trim( $path, '/' ) );
		$id = strtolower( $method );
		foreach ( $segments as $segment ) {
			if ( $segment === '' ) {
				continue;
			}
			// Convert {paramName} to "ByParamname"
			if ( preg_match( '/^\{(.+)\}$/', $segment, $matches ) ) {
				$id .= 'By' . ucfirst( strtolower( $matches[1] ) );
			} else {
				// Split on any non-alphanumeric char and ucfirst each word
				$clean = preg_replace( '/[^a-zA-Z0-9]/', ' ', $segment );
				$words = preg_split( '/\s+/', trim( $clean ), -1, PREG_SPLIT_NO_EMPTY );
				foreach ( $words as $word ) {
					$id .= ucfirst( strtolower( $word ) );
				}
			}
		}
		return $id;
	}

	private function getComponentsSpec(): array {
		$components = [];

		// Resolve x-i18n-message references
		$resolvedComponents = $this->getJsonLocalizer()->localizeJson(
			ResponseFactory::getResponseComponents()
		);

		// XXX: also collect reusable components from handler specs (but how to avoid name collisions?).
		$componentsSources = [
			[ 'schemas' => Validator::getParameterTypeSchemas() ],
			$resolvedComponents
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
		return __DIR__ . '/Schema/ModuleSpec.json';
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
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
