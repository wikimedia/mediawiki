<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\ModuleAvailability;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\SessionManagerInterface;
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
		MainConfigNames::CanonicalServer,
		MainConfigNames::RestLocalModuleTestBaseUrl,
		MainConfigNames::RestTermsOfServiceUrl,
	];

	private readonly ServiceOptions $options;

	public function __construct(
		Config $config,
		private readonly SessionManagerInterface $sessionManager,
	) {
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

		if ( $moduleName === '-' ) {
			// Spec for the prefix-less module.
			$moduleName = '';
		}

		$moduleInfo = $this->getRouter()->getModuleManager()->getModuleInfo( $moduleName );
		if ( !$moduleInfo ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->params( $moduleName ),
				404
			);
		}

		// Suppress OpenAPI spec for HIDDEN or DISABLED modules. This is not a security or
		// protection mechanism. MediaWiki is open source, so callers can learn the details of
		// its endpoints.  This is just a way to hide the spec in cases where it should not be
		// available.
		if (
			$moduleInfo->getAvailability() === ModuleAvailability::HIDDEN ||
			$moduleInfo->getAvailability() === ModuleAvailability::DISABLED
		) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unavailable-spec' )->params( $moduleName ),
				403
			);
		}

		// If this module is external, redirect to its spec.
		if ( $moduleInfo->isExternal() ) {
			$specUrl = $this->getRouter()->getModuleSpecUrl( $moduleName );
			if ( $specUrl === null ) {
				throw new LocalizedHttpException(
					MessageValue::new( 'rest-unavailable-spec' )->params( $moduleName ),
					500
				);
			}
			return $this->getResponseFactory()->createPermanentRedirect( $specUrl );
		}

		$module = $this->getRouter()->getModule( $moduleName );
		if ( !$module ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-unknown-module' )->params( $moduleName ),
				404
			);
		}

		$oad = [
			'openapi' => '3.0.0',
			'info' => $this->getInfoSpec( $module ),
			'servers' => $this->getServerSpec( $module ),
			'externalDocs' => $module->getOpenApiExternalDocs(),
			'tags' => $module->getOpenApiTags(),
			'paths' => $this->getPathsSpec( $module ),
			'components' => $this->getComponentsSpec( $moduleName ),
		];

		// Remove some extra info outside the standard OAD schema
		unset( $oad['info']['deprecationSettings'] );
		unset( $oad['info']['oadSpecPath'] );

		if ( !$oad['externalDocs'] ) {
			unset( $oad['externalDocs'] );
		}

		if ( empty( $oad['tags'] ) ) {
			unset( $oad['tags'] );
		}

		$oad = $this->getJsonLocalizer()->localizeJson( $oad );
		return $oad;
	}

	/**
	 * @see https://spec.openapis.org/oas/v3.0.0#info-object
	 */
	private function getInfoSpec( Module $module ): array {
		// Modules manage their own version see SpecBasedModule::getOpenApiInfo()
		// and ExtraRoutesModule::getOpenApiInfo().
		// We add a blank string as the version fallback here so the required info.version field is
		// always present in the spec, even when empty, as required by the OpenAPI specification.
		// A blank version still raises an error in OpenAPI spec linters, such as the WMF spectral linter.

		$prefix = $module->getPathPrefix();

		if ( $prefix === '' ) {
			$title = $this->getJsonLocalizer()->getFormattedMessage( 'rest-default-module' );
		} else {
			$moduleStr = $this->getJsonLocalizer()->getFormattedMessage( 'rest-module' );
			$title = "$prefix " . $moduleStr;
		}

		$info = $module->getOpenApiInfo() + [
			'title' => $title,
			'version' => '',
			'license' => $this->getLicenseSpec(),
			'contact' => $this->getContactSpec(),
		];

		$sandboxUrl = $this->getLocalModuleSandboxUrl( $module );
		if ( $sandboxUrl !== null ) {
			if ( isset( $info['x-i18n-description'] ) && $info['x-i18n-description'] !== '' ) {
				$info['description'] = $this->getJsonLocalizer()->getFormattedMessage( $info['x-i18n-description'] );
				unset( $info['x-i18n-description'] );
			}

			$host = parse_url( $sandboxUrl, PHP_URL_HOST );
			if ( isset( $info['description'] ) && $info['description'] !== '' ) {
				$info['description'] = $this->getJsonLocalizer()->getFormattedMessage(
					new MessageValue(
						'rest-sandbox-recommend-test-server-with-description',
						[ $info['description'], $host ]
					)
				);
			} else {
				$info['description'] = $this->getJsonLocalizer()->getFormattedMessage(
					new MessageValue( 'rest-sandbox-recommend-test-server', [ $host ] )
				);
			}
		}

		$termsOfService = $this->options->get( MainConfigNames::RestTermsOfServiceUrl );
		if ( is_string( $termsOfService ) && $termsOfService !== '' ) {
			$info['termsOfService'] = $termsOfService;
		}

		return $info;
	}

	private function getLicenseSpec(): array {
		return [
			'name' => $this->options->get( MainConfigNames::RightsText ),
			'url' => $this->options->get( MainConfigNames::RightsUrl ),
		];
	}

	private function getContactSpec(): array {
		$contact = [
			'name' => $this->options->get( MainConfigNames::Sitename ),
			'url' => $this->options->get( MainConfigNames::CanonicalServer ),
		];

		$email = $this->options->get( MainConfigNames::EmergencyContact );
		// OpenAPI requires contact.email to be a valid email address. Keep the rest
		// of the contact object intact and omit the field when the configured value
		// does not satisfy that format.
		if ( is_string( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) !== false ) {
			$contact['email'] = $email;
		}

		return $contact;
	}

	private function getServerSpec( Module $module ): array {
		$prodUrl = $this->getModuleRouteUrl( $module );
		$sandboxUrl = $this->getLocalModuleSandboxUrl( $module );

		if ( $sandboxUrl !== null ) {
			$localizer = $this->getJsonLocalizer();
			return [
				[
					'url' => $prodUrl,
					'description' => $localizer->getFormattedMessage( 'rest-sandbox-server-production' ),
				],
				[
					'url' => $sandboxUrl,
					'description' => $localizer->getFormattedMessage( 'rest-sandbox-server-sandbox' ),
				]
			];
		}

		return [
			[
				'url' => $prodUrl,
			]
		];
	}

	/**
	 * Get the absolute entry point route URL for the given module's path prefix.
	 *
	 * @param Module $module The REST module to resolve the prefix for
	 * @return string The absolute route URL (e.g., https://en.wikipedia.org/w/rest.php/specs/v0)
	 */
	private function getModuleRouteUrl( Module $module ): string {
		$prefix = $module->getPathPrefix();
		if ( $prefix !== '' ) {
			$prefix = "/$prefix";
		}
		return $this->getRouter()->getRouteUrl( $prefix );
	}

	/**
	 * Get the absolute sandbox route URL for the given module, if configured via $wgRestLocalModuleTestBaseUrl.
	 *
	 * @param Module $module The REST module to resolve the sandbox URL for
	 * @return string|null The absolute sandbox route URL, or null if not configured
	 */
	private function getLocalModuleSandboxUrl( Module $module ): ?string {
		$sandboxBaseUrl = $this->options->get( MainConfigNames::RestLocalModuleTestBaseUrl );
		if ( $sandboxBaseUrl === null || $sandboxBaseUrl === '' ) {
			return null;
		}
		$prefix = $module->getPathPrefix();
		if ( $prefix !== '' ) {
			return rtrim( $sandboxBaseUrl, '/' ) . '/' . $prefix;
		}
		return $sandboxBaseUrl;
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
	 * @return array OpenAPI operation object
	 */
	private function getRouteSpec( Module $module, string $path, string $method ): array {
		$request = new RequestData( [ 'method' => $method ] );
		$handler = $module->getHandlerForPath( $path, $request, false );

		$operationSpec = $handler->getOpenApiSpec( $method );

		$operationSpec['security'] = $this->getOpenApiSecurityRequirements( $handler );

		// If the spec already contains an explicit operationId (e.g. set in the JSON
		// definition file via $oasKeys), respect it. Otherwise auto-generate one
		// from the method and path, see pathToOperationId().
		if ( !isset( $operationSpec['operationId'] ) ) {
			$operationSpec['operationId'] = self::pathToOperationId( $method, $path );
		}

		return $operationSpec;
	}

	/**
	 * Build the OpenAPI security requirements array for a route's handler.
	 *
	 * Each session provider contributes a single requirement object grouping all of
	 * its schemes together (logical AND); distinct providers are emitted as separate
	 * objects (logical OR). Routes that do not require write access additionally allow
	 * unauthenticated access, represented by a leading empty requirement object ({}).
	 *
	 * needsWriteAccess() is used as a heuristic for whether anonymous access is allowed,
	 * since it is the only signal Handler exposes today. It is not a guarantee: some
	 * handlers that don't need write access still reject anonymous requests internally
	 * (e.g. ReadingLists' ListsHandler-derived endpoints, which require a logged-in user
	 * even though they are read-only). Such routes will be spec'd as allowing anonymous
	 * access even though the handler will reject anonymous requests at runtime.
	 *
	 * Grouping relies on the "{providerBaseName}-{subKey}" naming convention produced by
	 * SessionManager::getAllOpenApiSecuritySchemes(), where the provider's sanitized class
	 * name forms the base and the per-scheme suffix (subKey) contains no hyphen. The last
	 * hyphen therefore separates the provider base name from the suffix.
	 *
	 * @see https://spec.openapis.org/oas/v3.0.0#security-requirement-object
	 * @param Handler $handler
	 * @return array<int, array|\stdClass> OpenAPI security requirement objects
	 */
	private function getOpenApiSecurityRequirements( Handler $handler ): array {
		$requirements = [];

		// Read-only endpoints are assumed to permit anonymous access. This is only a
		// heuristic; see the note above for known exceptions.
		if ( !$handler->needsWriteAccess() ) {
			$requirements[] = (object)[]; // Represents {} in JSON.
		}

		// Group schemes by provider (AND within a provider, OR across providers).
		$providerGroups = [];
		foreach ( $this->sessionManager->getAllOpenApiSecuritySchemes() as $schemeName => $_ ) {
			$lastDash = strrpos( $schemeName, '-' );
			$baseName = $lastDash !== false ? substr( $schemeName, 0, $lastDash ) : $schemeName;
			$providerGroups[$baseName][$schemeName] = [];
		}

		foreach ( $providerGroups as $groupSchemes ) {
			$requirements[] = $groupSchemes;
		}

		return $requirements;
	}

	/**
	 * Derive an operationId from the HTTP method and route path.
	 *
	 * Path parameters ({name}) become "ByName". Hyphens, underscores and other
	 * non-alphanumeric characters act as word separators.
	 * Example: method=GET, path="/v1/page/{title}/links" → "getV1PageByTitleLinks"
	 *
	 * @note The ID is unique only if the paths of a module differ in more than case,
	 * separators, non-ASCII characters or the name of a path parameter. For
	 * example, "/foo-bar" and "/foo/bar" both yield "getFooBar". By convention,
	 * such combinations of paths should not exist, but we do not protect against
	 * them here. When the logic for generating the operationId moves somewhere
	 * reusable, like ModuleManager, we can write a structure test that ensures
	 * that operationIds are unique per module.
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

	private function getComponentsSpec( string $moduleName ): array {
		$components = [];

		// Use the documented module's response factory rather
		// than $this->getResponseFactory(), so that the error schema is the one that
		// module actually uses. Pass a blank request, not $this->getRequest(): the spec must
		// not depend on request headers, so error formats selected per request (such as
		// RESTBase compat via x-restbase-compat) are intentionally not documented.
		$responseComponents = $this->getRouter()->getModuleResponseFactoryByName(
			$moduleName,
			new RequestData()
		)->getResponseComponents();

		// XXX: also collect reusable components from handler specs (but how to avoid name collisions?).
		$componentsSources = [
			[ 'schemas' => Validator::getParameterTypeSchemas() ],
			$responseComponents
		];

		// 2D merge
		foreach ( $componentsSources as $cmps ) {
			foreach ( $cmps as $name => $cmp ) {
				$components[$name] = array_merge( $components[$name] ?? [], $cmp );
			}
		}

		// Security schemes are declared by the installed session providers. Resolve any
		// localizable descriptions (MessageValue); plain-string descriptions, e.g. from
		// third-party providers, are emitted as-is. The key is omitted when no provider
		// declares schemes, to avoid an empty securitySchemes object.
		$securitySchemes = $this->sessionManager->getAllOpenApiSecuritySchemes();
		if ( $securitySchemes ) {
			$components['securitySchemes'] = $securitySchemes;
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
			'lang' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-module-spec-lang' ),
				Handler::PARAM_EXAMPLE => 'fr',
			],
		];
	}

}
