<?php

namespace MediaWiki\Rest;

use DateTime;
use MediaWiki\Debug\MWDebug;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use UtfNormal\Validator as UtfNormalValidator;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Base class for REST route handlers.
 *
 * @stable to extend.
 */
abstract class Handler {

	/**
	 * @see Validator::KNOWN_PARAM_SOURCES
	 */
	public const KNOWN_PARAM_SOURCES = Validator::KNOWN_PARAM_SOURCES;

	/**
	 * @see Validator::PARAM_SOURCE
	 */
	public const PARAM_SOURCE = Validator::PARAM_SOURCE;

	/**
	 * @see Validator::PARAM_DESCRIPTION
	 */
	public const PARAM_DESCRIPTION = Validator::PARAM_DESCRIPTION;

	public const OPENAPI_DESCRIPTION_KEY = 'description';

	/** @var Module */
	private $module;

	/** @var RequestInterface */
	private $request;

	/** @var Authority */
	private $authority;

	/** @var string */
	private $path;

	/** @var array */
	private $config;

	/** @var ResponseFactory */
	private $responseFactory;

	/** @var array|null */
	private $validatedParams;

	/** @var mixed|null */
	private $validatedBody;

	/** @var ConditionalHeaderUtil */
	private $conditionalHeaderUtil;

	/** @var JsonLocalizer */
	private $jsonLocalizer;

	/** @var HookContainer */
	private $hookContainer;

	/** @var Session */
	private $session;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Injects information about the handler's context in the Module.
	 * The framework should call this right after the object was constructed.
	 *
	 * First function of the initialization function, must be called before
	 * initServices().
	 *
	 * @param Module $module
	 * @param string $path
	 * @param array $routeConfig information about the route declaration.
	 *
	 * @internal
	 */
	final public function initContext( Module $module, string $path, array $routeConfig ) {
		Assert::precondition(
			$this->authority === null,
			'initContext() must be called before initServices()'
		);

		$this->module = $module;
		$this->path = $path;
		$this->config = $routeConfig;
	}

	/**
	 * Inject service objects.
	 *
	 * Second function of the initialization function, must be called after
	 * initContext() and before initSession().
	 *
	 * @param Authority $authority
	 * @param ResponseFactory $responseFactory
	 * @param HookContainer $hookContainer
	 *
	 * @internal
	 */
	final public function initServices(
		Authority $authority, ResponseFactory $responseFactory, HookContainer $hookContainer
	) {
		// Warn if a subclass overrides getBodyValidator()
		MWDebug::detectDeprecatedOverride(
			$this,
			__CLASS__,
			'getBodyValidator',
			'1.43'
		);

		Assert::precondition(
			$this->module !== null,
			'initServices() must not be called before initContext()'
		);
		Assert::precondition(
			$this->session === null,
			'initServices() must be called before initSession()'
		);

		$this->authority = $authority;
		$this->responseFactory = $responseFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Inject session information.
	 *
	 * Third function of the initialization function, must be called after
	 * initServices() and before initForExecute().
	 *
	 * @param Session $session
	 *
	 * @internal
	 */
	final public function initSession( Session $session ) {
		Assert::precondition(
			$this->authority !== null,
			'initSession() must not be called before initContext()'
		);
		Assert::precondition(
			$this->request === null,
			'initSession() must be called before initForExecute()'
		);

		$this->session = $session;
	}

	/**
	 * Initialise for execution based on the given request.
	 *
	 * Last function of the initialization function, must be called after
	 * initSession() and before validate() and checkPreconditions().
	 *
	 * This function will call postInitSetup() to allow subclasses to
	 * perform their own initialization.
	 *
	 * The request object is updated with parsed body data if needed.
	 *
	 * @internal
	 *
	 * @param RequestInterface $request
	 *
	 * @throws HttpException if the handler does not accept the request for
	 *         some reason.
	 */
	final public function initForExecute( RequestInterface $request ) {
		Assert::precondition(
			$this->session !== null,
			'initForExecute() must not be called before initSession()'
		);

		if ( $request->getParsedBody() === null ) {
			$this->processRequestBody( $request );
		}

		$this->request = $request;

		$this->postInitSetup();
	}

	/**
	 * Process the request's request body and set the parsed body data
	 * if appropriate.
	 *
	 * @see parseBodyData()
	 *
	 * @throws HttpException if the request body is not acceptable.
	 */
	private function processRequestBody( RequestInterface $request ) {
		// fail if the request method is in NO_BODY_METHODS but has body
		$requestMethod = $request->getMethod();
		if ( in_array( $requestMethod, RequestInterface::NO_BODY_METHODS ) ) {
			// check if the request has a body
			if ( $request->hasBody() ) {
				// NOTE: Don't throw, see T359509.
				// TODO: Ignore only empty bodies, log a warning or fail if
				//       there is actual content.
				return;
			}
		}

		// fail if the request method expects a body but has no body
		if ( in_array( $requestMethod, RequestInterface::BODY_METHODS ) ) {
			// check if it has no body
			if ( !$request->hasBody() ) {
				throw new LocalizedHttpException(
					new MessageValue(
						"rest-request-body-expected",
						[ $requestMethod ]
					),
					411
				);
			}
		}

		// call parsedbody
		if ( $request->hasBody() ) {
			$parsedBody = $this->parseBodyData( $request );
			// Set the parsed body data on the request object
			$request->setParsedBody( $parsedBody );
		}
	}

	/**
	 * Returns the path this handler is bound to relative to the module prefix.
	 * Includes path variables.
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * Get a list of parameter placeholders present in the route's path
	 * as returned by getPath(). Note that this is independent of the parameters
	 * defined by getParamSettings(): required path parameters defined in
	 * getParamSettings() should be present in the path, but there is no
	 * mechanism to ensure that they are.
	 *
	 * @return string[]
	 */
	public function getSupportedPathParams(): array {
		$path = $this->getPath();

		preg_match_all( '/\{(.*?)\}/', $path, $matches, PREG_PATTERN_ORDER );

		return $matches[1] ?? [];
	}

	protected function getRouter(): Router {
		return $this->module->getRouter();
	}

	/**
	 * Get the Module this handler belongs to.
	 * Will fail hard if called before initContext().
	 */
	protected function getModule(): Module {
		return $this->module;
	}

	/**
	 * Get the URL of this handler's endpoint.
	 * Supports the substitution of path parameters, and additions of query parameters.
	 *
	 * @see Router::getRouteUrl()
	 *
	 * @param string[] $pathParams Path parameters to be injected into the path
	 * @param string[] $queryParams Query parameters to be attached to the URL
	 *
	 * @return string
	 */
	protected function getRouteUrl( $pathParams = [], $queryParams = [] ): string {
		$path = $this->getPath();
		return $this->getRouter()->getRouteUrl( $path, $pathParams, $queryParams );
	}

	/**
	 * URL-encode titles in a "pretty" way.
	 *
	 * Keeps intact ;@$!*(),~: (urlencode does not, but wfUrlencode does).
	 * Encodes spaces as underscores (wfUrlencode does not).
	 * Encodes slashes (wfUrlencode does not, but keeping them messes with REST paths).
	 * Encodes pluses (this is not necessary, and may change).
	 *
	 * @see wfUrlencode
	 *
	 * @param string $title
	 *
	 * @return string
	 */
	protected function urlEncodeTitle( $title ) {
		$title = str_replace( ' ', '_', $title );
		$title = urlencode( $title );

		// %3B_a_%40_b_%24_c_%21_d_%2A_e_%28_f_%29_g_%2C_h_~_i_%3A
		$replace = [ '%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%7E', '%3A' ];
		$with = [ ';', '@', '$', '!', '*', '(', ')', ',', '~', ':' ];

		return str_replace( $replace, $with, $title );
	}

	/**
	 * Get the current request. The return type declaration causes it to raise
	 * a fatal error if initForExecute() has not yet been called.
	 */
	public function getRequest(): RequestInterface {
		return $this->request;
	}

	/**
	 * Get the current acting authority. The return type declaration causes it to raise
	 * a fatal error if initServices() has not yet been called.
	 *
	 * @since 1.36
	 * @return Authority
	 */
	public function getAuthority(): Authority {
		return $this->authority;
	}

	/**
	 * Get the configuration array for the current route. The return type
	 * declaration causes it to raise a fatal error if initContext() has not
	 * been called.
	 */
	public function getConfig(): array {
		return $this->config;
	}

	/**
	 * Get the ResponseFactory which can be used to generate Response objects.
	 * This will raise a fatal error if initServices() has not been
	 * called.
	 */
	public function getResponseFactory(): ResponseFactory {
		return $this->responseFactory;
	}

	/**
	 * Get the Session.
	 * This will raise a fatal error if initSession() has not been
	 * called.
	 */
	public function getSession(): Session {
		return $this->session;
	}

	/**
	 * Validate the request parameters/attributes and body. If there is a validation
	 * failure, a response with an error message should be returned or an
	 * HttpException should be thrown.
	 *
	 * @stable to override
	 * @param Validator $restValidator
	 * @throws HttpException On validation failure.
	 */
	public function validate( Validator $restValidator ) {
		$this->validatedParams = $restValidator->validateParams(
			$this->getParamSettings()
		);

		$bodyType = $this->request->getBodyType();
		$legacyBodyValidator = $bodyType === null ? null
			: $this->getBodyValidator( $bodyType );

		if ( $legacyBodyValidator && !$legacyBodyValidator instanceof NullBodyValidator ) {
			$this->validatedBody = $restValidator->validateBody( $this->request, $this );
		} else {
			// Allow type coercion if the request body is form data.
			// For JSON requests, insist on proper types.
			$enforceTypes = !in_array(
				$this->request->getBodyType(),
				RequestInterface::FORM_DATA_CONTENT_TYPES
			);

			$this->validatedBody = $restValidator->validateBodyParams(
				$this->getBodyParamSettings(),
				$enforceTypes
			);

			// If there is a body, check if it contains extra fields.
			if ( $this->getRequest()->hasBody() ) {
				$this->detectExtraneousBodyFields( $restValidator );
			}
		}

		$this->postValidationSetup();
	}

	/**
	 * Subclasses may override this to disable or modify checks for extraneous
	 * body fields.
	 *
	 * @since 1.42
	 * @stable to override
	 * @param Validator $restValidator
	 * @throws HttpException On validation failure.
	 */
	protected function detectExtraneousBodyFields( Validator $restValidator ) {
		$parsedBody = $this->getRequest()->getParsedBody();

		if ( !$parsedBody ) {
			// nothing to do
			return;
		}

		$restValidator->detectExtraneousBodyFields(
			$this->getBodyParamSettings(),
			$parsedBody
		);
	}

	/**
	 * Check the session (and session provider)
	 * @throws HttpException on failed check
	 * @internal
	 */
	public function checkSession() {
		if ( !$this->session->getProvider()->safeAgainstCsrf() ) {
			if ( $this->requireSafeAgainstCsrf() ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-requires-safe-against-csrf' ),
					400
				);
			}
		} elseif ( !empty( $this->validatedBody['token'] ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-extraneous-csrf-token' ),
				400
			);
		}
	}

	/**
	 * Get a JsonLocalizer object.
	 *
	 * @return JsonLocalizer
	 */
	protected function getJsonLocalizer(): JsonLocalizer {
		Assert::precondition(
			$this->responseFactory !== null,
			'getJsonLocalizer() must not be called before initServices()'
		);

		if ( $this->jsonLocalizer === null ) {
			$this->jsonLocalizer = new JsonLocalizer( $this->responseFactory );
		}

		return $this->jsonLocalizer;
	}

	/**
	 * Get a ConditionalHeaderUtil object.
	 *
	 * On the first call to this method, the object will be initialized with
	 * validator values by calling getETag(), getLastModified() and
	 * hasRepresentation().
	 *
	 * @return ConditionalHeaderUtil
	 */
	protected function getConditionalHeaderUtil() {
		if ( $this->conditionalHeaderUtil === null ) {
			$this->conditionalHeaderUtil = new ConditionalHeaderUtil;

			// NOTE: It would be nicer to have Handler implement a
			// ConditionalHeaderValues interface that defines methods that
			// ConditionalHeaderUtil can call. But the relevant methods already
			// exist in Handler as protected and stable to override.
			// We can't make them public without breaking all subclasses that
			// override them. So we pass closures for now.
			$this->conditionalHeaderUtil->setValidators(
				fn () => $this->getETag(),
				fn () => $this->getLastModified(),
				fn () => $this->hasRepresentation()
			);
		}
		return $this->conditionalHeaderUtil;
	}

	/**
	 * Check the conditional request headers and generate a response if appropriate.
	 * This is called by the Router before execute() and may be overridden.
	 *
	 * @stable to override
	 *
	 * @return ResponseInterface|null
	 */
	public function checkPreconditions() {
		$status = $this->getConditionalHeaderUtil()->checkPreconditions( $this->getRequest() );
		if ( $status ) {
			$response = $this->getResponseFactory()->create();
			$response->setStatus( $status );
			$this->applyConditionalResponseHeaders( $response );
			return $response;
		}

		return null;
	}

	/**
	 * Apply verifier headers to the response, per RFC 7231 §7.2.
	 * This is called after execute() returns.
	 *
	 * For GET and HEAD requests, the default behavior is to set the ETag and
	 * Last-Modified headers based on the values returned by getETag() and
	 * getLastModified() when they were called before execute() was run.
	 *
	 * Other request methods are assumed to be state-changing, so no headers
	 * will be set by default.
	 *
	 * This may be overridden to modify the verifier headers sent in the response.
	 * However, handlers that modify the resource's state would typically just
	 * set the ETag and Last-Modified headers in the execute() method.
	 *
	 * @stable to override
	 *
	 * @param ResponseInterface $response
	 */
	public function applyConditionalResponseHeaders( ResponseInterface $response ) {
		$method = $this->getRequest()->getMethod();
		if ( $method === 'GET' || $method === 'HEAD' ) {
			$this->getConditionalHeaderUtil()->applyResponseHeaders( $response );
		}
	}

	/**
	 * Apply cache control to enforce privacy.
	 */
	public function applyCacheControl( ResponseInterface $response ) {
		// NOTE: keep this consistent with the logic in OutputPage::sendCacheControl

		// If the response sets cookies, it must not be cached in proxies.
		// If there's an active cookie-based session (logged-in user or anonymous user with
		// session-scoped cookies), it is not safe to cache either, as the session manager may set
		// cookies in the response, or the response itself may vary on user-specific variables,
		// for example on private wikis where the 'read' permission is restricted. (T264631)
		if ( $response->getHeaderLine( 'Set-Cookie' ) || $this->getSession()->isPersistent() ) {
			$response->setHeader( 'Cache-Control', 'private,must-revalidate,s-maxage=0' );
		}

		if ( !$response->getHeaderLine( 'Cache-Control' ) ) {
			$rqMethod = $this->getRequest()->getMethod();
			if ( $rqMethod !== 'GET' && $rqMethod !== 'HEAD' ) {
				// Responses to requests other than GET or HEAD should not be cacheable by default.
				$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
			}
		}
	}

	/**
	 * Fetch ParamValidator settings for parameters
	 *
	 * Every setting must include self::PARAM_SOURCE to specify which part of
	 * the request is to contain the parameter.
	 *
	 * Can be used for the request body as well, by setting self::PARAM_SOURCE
	 * to "post". Note that the values of "post" parameters will be accessible
	 * through getValidatedParams(). "post" parameters are used with
	 * form data (application/x-www-form-urlencoded or multipart/form-data).
	 *
	 * For "query" parameters, a PARAM_REQUIRED setting of "false" means the caller
	 * does not have to supply the parameter. For "path" parameters, the path matcher will always
	 * require the caller to supply all path parameters for a route, regardless of the
	 * PARAM_REQUIRED setting. However, "path" parameters may be specified in getParamSettings()
	 * as non-required to indicate that the handler services multiple routes, some of which may
	 * not supply the parameter.
	 *
	 * @stable to override
	 *
	 * @return array[] Associative array mapping parameter names to
	 *  ParamValidator settings arrays
	 */
	public function getParamSettings() {
		return [];
	}

	/**
	 * Fetch ParamValidator settings for body fields. Parameters defined
	 * by this method are used to validate the request body. The parameter
	 * values will become available through getValidatedBody().
	 *
	 * Subclasses may override this method to specify what fields they support
	 * in the request body. All parameter settings returned by this method must
	 * have self::PARAM_SOURCE set to 'body'.
	 *
	 * @return array[]
	 */
	public function getBodyParamSettings(): array {
		return [];
	}

	/**
	 * Returns an OpenAPI Operation Object specification structure as an associative array.
	 *
	 * @see https://swagger.io/specification/#operation-object
	 *
	 * By default, this will contain information about the supported parameters, as well as
	 * the response for status 200.
	 *
	 * Subclasses may override this to provide additional information.
	 *
	 * @since 1.42
	 * @stable to override
	 *
	 * @param string $method The HTTP method to produce a spec for ("get", "post", etc).
	 *        Useful for handlers that behave differently depending on the
	 *        request method.
	 *
	 * @return array
	 */
	public function getOpenApiSpec( string $method ): array {
		$parameters = [];

		$supportedPathParams = array_flip( $this->getSupportedPathParams() );

		foreach ( $this->getParamSettings() as $name => $setting ) {
			$source = $setting[ Validator::PARAM_SOURCE ] ?? '';

			if ( $source !== 'query' && $source !== 'path' ) {
				continue;
			}

			if ( $source === 'path' && !isset( $supportedPathParams[$name] ) ) {
				// Skip optional path param not used in the current path
				continue;
			}

			$setting[ Validator::PARAM_DESCRIPTION ] = $this->getJsonLocalizer()->localizeValue(
				$setting, Validator::PARAM_DESCRIPTION,
			);

			$param = Validator::getParameterSpec( $name, $setting );

			$parameters[] = $param;
		}

		$spec = [
			'parameters' => $parameters,
			'responses' => $this->generateResponseSpec( $method ),
		];

		if ( !in_array( $method, RequestInterface::NO_BODY_METHODS ) ) {
			$requestBody = $this->getRequestSpec( $method );
			if ( $requestBody ) {
				$spec['requestBody'] = $requestBody;
			}
		}

		// TODO: Allow additional information about parameters and responses to
		//       be provided in the route definition.
		$oas = $this->getConfig()['OAS'] ?? [];
		$spec += $oas;

		return $spec;
	}

	/**
	 * Returns an OpenAPI Request Body Object specification structure as an associative array.
	 *
	 * @see https://swagger.io/specification/#request-body-object
	 *
	 * This is based on the getBodyParamSettings() and getSupportedRequestTypes().
	 *
	 * Subclasses may override this to provide additional information about the
	 * structure of responses, or to add support for additional mediaTypes.
	 *
	 * @stable to override getBodySchema() to generate a schema for each
	 * supported media type as returned by getSupportedBodyTypes().
	 *
	 * @param string $method
	 *
	 * @return ?array
	 */
	protected function getRequestSpec( string $method ): ?array {
		$mediaTypes = [];

		foreach ( $this->getSupportedRequestTypes() as $type ) {
			$schema = $this->getRequestBodySchema( $type );

			if ( $schema ) {
				$mediaTypes[$type] = [ 'schema' => $schema ];
			}
		}

		if ( !$mediaTypes ) {
			return null;
		}

		return [
			// TODO: some DELETE handlers may require a body that contains a token
			// FIXME: check if there are required body params!
			'required' => in_array( $method, RequestInterface::BODY_METHODS ),
			'content' => $mediaTypes
		];
	}

	/**
	 * Returns a content schema per the OpenAPI spec.
	 * @see https://swagger.io/specification/#schema-object
	 *
	 * Per default, this provides schemas for JSON requests and form data, based
	 * on the parameter declarations returned by getParamSettings().
	 *
	 * Subclasses may override this to provide additional information about the
	 * structure of responses, or to add support for additional mediaTypes.
	 *
	 * @stable to override
	 * @return array
	 */
	protected function getRequestBodySchema( string $mediaType ): array {
		if ( $mediaType === RequestInterface::FORM_URLENCODED_CONTENT_TYPE ) {
			$allowedSources = [ 'body', 'post' ];
		} elseif ( $mediaType === RequestInterface::MULTIPART_FORM_DATA_CONTENT_TYPE ) {
			$allowedSources = [ 'body', 'post' ];
		} else {
			$allowedSources = [ 'body' ];
		}

		$paramSettings = $this->getBodyParamSettings();

		$properties = [];
		$required = [];

		foreach ( $paramSettings as $name => $settings ) {
			$source = $settings[ Validator::PARAM_SOURCE ] ?? '';
			$isRequired = $settings[ ParamValidator::PARAM_REQUIRED ] ?? false;

			if ( !in_array( $source, $allowedSources ) ) {
				// TODO: post parameters also work as body parameters...
				continue;
			}

			$properties[$name] = Validator::getParameterSchema( $settings );
			$properties[$name][self::OPENAPI_DESCRIPTION_KEY] =
				$this->getJsonLocalizer()->localizeValue( $settings, Validator::PARAM_DESCRIPTION )
				?? "$name parameter";

			if ( $isRequired ) {
				$required[] = $name;
			}
		}

		if ( !$properties ) {
			return [];
		}

		$schema = [
			'type' => 'object',
			'properties' => $properties,
		];

		if ( $required ) {
			$schema['required'] = $required;
		}

		return $schema;
	}

	/**
	 * Returns an OpenAPI Schema Object specification structure as an associative array.
	 *
	 * @see https://swagger.io/specification/#schema-object
	 *
	 * Returns null by default. Subclasses that return a JSON response should
	 * implement this method to return a schema of the response body.
	 *
	 * @param string $method The HTTP method to produce a spec for ("get", "post", etc).
	 *
	 * @stable to override
	 * @return ?array
	 */
	protected function getResponseBodySchema( string $method ): ?array {
		$file = $this->getResponseBodySchemaFileName( $method );
		return $file ? Module::loadJsonFile( $file ) : null;
	}

	/**
	 * Returns the path and name of a JSON file containing an OpenAPI Schema Object
	 * specification structure.
	 *
	 * @see https://swagger.io/specification/#schema-object
	 *
	 * Returns null by default. Subclasses with a suitable JSON file should implement this method.
	 *
	 * @param string $method The HTTP method to produce a spec for ("get", "post", etc).
	 *
	 * @stable to override
	 * @since 1.43
	 * @return ?string
	 */
	protected function getResponseBodySchemaFileName( string $method ): ?string {
		return null;
	}

	/**
	 * Returns an OpenAPI Responses Object specification structure as an associative array.
	 *
	 * @see https://swagger.io/specification/#responses-object
	 *
	 * By default, this will contain basic information response for status 200, 400, and 500.
	 * The getResponseBodySchema() method is used to determine the structure of the response for status 200.
	 *
	 * Subclasses may override this to provide additional information about the structure of responses.
	 *
	 * @param string $method The HTTP method to produce a spec for ("get", "post", etc).
	 *
	 * @stable to override
	 * @return array
	 */
	protected function generateResponseSpec( string $method ): array {
		$ok = [ self::OPENAPI_DESCRIPTION_KEY => 'OK' ];

		$bodySchema = $this->getResponseBodySchema( $method );

		if ( $bodySchema ) {
			$bodySchema = $this->getJsonLocalizer()->localizeJson( $bodySchema );
			$ok['content']['application/json']['schema'] = $bodySchema;
		}

		// XXX: we should add info about redirects, and maybe a default for errors?
		return [
			'200' => $ok,
			'400' => [ '$ref' => '#/components/responses/GenericErrorResponse' ],
			'500' => [ '$ref' => '#/components/responses/GenericErrorResponse' ],
		];
	}

	/**
	 * Fetch the BodyValidator
	 *
	 * @deprecated since 1.43, return body properties from getBodyParamSettings().
	 * Subclasses that need full control over body data parsing should override
	 * parseBodyData() or implement validation in the execute() method based on
	 * the unparsed body data returned by getRequest()->getBody().
	 *
	 * @param string $contentType Content type of the request.
	 * @return BodyValidator A {@see NullBodyValidator} in this default implementation
	 * @throws HttpException It's possible to fail early here when e.g. $contentType is unsupported,
	 *  or later when {@see BodyValidator::validateBody} is called
	 */
	public function getBodyValidator( $contentType ) {
		// NOTE: When removing this method, also remove the BodyValidator interface and
		//       all classes implementing it!
		return new NullBodyValidator();
	}

	/**
	 * Fetch the validated parameters. This must be called after validate() is
	 * called. During execute() is fine.
	 *
	 * @return array Array mapping parameter names to validated values
	 * @throws \RuntimeException If validate() has not been called
	 */
	public function getValidatedParams() {
		if ( $this->validatedParams === null ) {
			throw new \RuntimeException( 'getValidatedParams() called before validate()' );
		}
		return $this->validatedParams;
	}

	/**
	 * Fetch the validated body
	 * @return mixed|null Value returned by the body validator, or null if validate() was
	 *  not called yet, validation failed, there was no body, or the body was form data.
	 */
	public function getValidatedBody() {
		return $this->validatedBody;
	}

	/**
	 * Returns the parsed body of the request.
	 * Should only be called if $request->hasBody() returns true.
	 *
	 * The default implementation handles application/x-www-form-urlencoded
	 * and multipart/form-data by calling $request->getPostParams(),
	 * if the list returned by getSupportedRequestTypes() includes these types.
	 *
	 * The default implementation handles application/json by parsing
	 * the body content as JSON. Only object structures (maps) are supported,
	 * other types will trigger an HttpException with status 400.
	 *
	 * Other content types will trigger a HttpException with status 415 per
	 * default.
	 *
	 * Subclasses may override this method to support parsing additional
	 * content types or to disallow content types by throwing an HttpException
	 * with status 415. Subclasses may also return null to indicate that they
	 * support reading the content, but intend to handle it as an unparsed
	 * stream in their implementation of the execute() method.
	 *
	 * Subclasses that override this method to support additional request types
	 * should also override getSupportedRequestTypes() to allow  that support
	 * to be documented in the OpenAPI spec.
	 *
	 * @since 1.42
	 *
	 * @throws HttpException If the content type is not supported or the content
	 *         is malformed.
	 *
	 * @return array|null The body content represented as an associative array,
	 *         or null if the request body is accepted unparsed.
	 */
	public function parseBodyData( RequestInterface $request ): ?array {
		// Parse the body based on its content type
		$contentType = $request->getBodyType();

		// HACK: If the Handler uses a custom BodyValidator, the
		// getBodyValidator() is also responsible for checking whether
		// the content type is valid, and for parsing the body.
		// See T359149.
		// TODO: remove once no subclasses override getBodyValidator() anymore
		$bodyValidator = $this->getBodyValidator( $contentType ?? 'unknown/unknown' );
		if ( !$bodyValidator instanceof NullBodyValidator ) {
			// TODO: Trigger a deprecation warning.
			return null;
		}

		$supportedTypes = $this->getSupportedRequestTypes();
		if ( $contentType !== null && !in_array( $contentType, $supportedTypes ) ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-unsupported-content-type', [ $contentType ] ),
				415
			);
		}

		// if it's supported and ends with "+json", we can probably parse it like a normal application/json request
		$contentType = str_ends_with( $contentType ?? '', '+json' )
			? RequestInterface::JSON_CONTENT_TYPE
			: $contentType;

		switch ( $contentType ) {
			case RequestInterface::FORM_URLENCODED_CONTENT_TYPE:
			case RequestInterface::MULTIPART_FORM_DATA_CONTENT_TYPE:
				$params = $request->getPostParams();
				foreach ( $params as $key => $value ) {
					$params[ $key ] = UtfNormalValidator::cleanUp( $value );
					// TODO: Warn if normalization was applied
				}
				return $params;
			case RequestInterface::JSON_CONTENT_TYPE:
				$jsonStream = $request->getBody();
				$jsonString = (string)$jsonStream;
				$normalizedJsonString = UtfNormalValidator::cleanUp( $jsonString );
				$parsedBody = json_decode( $normalizedJsonString, true );
				if ( !is_array( $parsedBody ) ) {
					throw new LocalizedHttpException(
						new MessageValue(
							'rest-json-body-parse-error',
							[ 'not a valid JSON object' ]
						),
						400
					);
				}
				// TODO: Warn if normalization was applied
				return $parsedBody;
			case null:
				// Specifying no Content-Type is fine if the body is empty
				if ( $request->getBody()->getSize() === 0 ) {
					return null;
				}
			// no break, else fall through to the error below.
			default:
				throw new LocalizedHttpException(
					new MessageValue( 'rest-unsupported-content-type', [ $contentType ?? '(null)' ] ),
					415
				);
		}
	}

	/**
	 * Returns the content types that should be accepted by parseBodyData().
	 *
	 * Subclasses that support request types other than application/json
	 * should override this method.
	 *
	 * If "application/x-www-form-urlencoded" or "multipart/form-data" are
	 * returned, parseBodyData() will use $request->getPostParams() to determine
	 * the body data.
	 *
	 * @note The return value of this method is ignored for requests
	 * using a method listed in Validator::NO_BODY_METHODS,
	 * in particular for the GET method.
	 *
	 * @note for backwards compatibility, the default implementation of this
	 * method will examine the parameter definitions returned by getParamSettings()
	 * to see if any of the parameters are declared as "post" parameters. If this
	 * is the case, support for "application/x-www-form-urlencoded" and
	 * "multipart/form-data" is added. This may change in future releases.
	 * It is preferred to use "body" parameters and override this method explicitly
	 * when support for form data is desired.
	 *
	 * @stable to override
	 *
	 * @return string[] A list of content-types
	 */
	public function getSupportedRequestTypes(): array {
		$types = [
			RequestInterface::JSON_CONTENT_TYPE
		];

		// TODO: remove this once "post" parameters are no longer supported! T362850
		foreach ( $this->getParamSettings() as $settings ) {
			if ( ( $settings[self::PARAM_SOURCE] ?? null ) === 'post' ) {
				$types[] = RequestInterface::FORM_URLENCODED_CONTENT_TYPE;
				$types[] = RequestInterface::MULTIPART_FORM_DATA_CONTENT_TYPE;
				break;
			}
		}

		return $types;
	}

	/**
	 * Get a HookContainer, for running extension hooks or for hook metadata.
	 *
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		return $this->hookContainer;
	}

	/**
	 * Get a HookRunner for running core hooks.
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *   without notice.
	 * @since 1.35
	 * @return HookRunner
	 */
	protected function getHookRunner() {
		return $this->hookRunner;
	}

	/**
	 * The subclass should override this to provide the maximum last modified
	 * timestamp of the requested resource. This is called before execute() in
	 * order to decide whether to send a 304. If the request is going to
	 * change the state of the resource, the time returned must represent
	 * the last modification date before the change. In other words, it must
	 * provide the timestamp of the entity that the change is going to be
	 * applied to.
	 *
	 * For GET and HEAD requests, this value will automatically be included
	 * in the response in the Last-Modified header.
	 *
	 * Handlers that modify the resource and want to return a Last-Modified
	 * header representing the new state in the response should set the header
	 * in the execute() method.
	 *
	 * See RFC 7231 §7.2 and RFC 7232 §2.3 for semantics.
	 *
	 * @stable to override
	 *
	 * @return string|int|float|DateTime|null
	 */
	protected function getLastModified() {
		return null;
	}

	/**
	 * The subclass should override this to provide an ETag for the current
	 * state of the requested resource. This is called before execute() in
	 * order to decide whether to send a 304. If the request is going to
	 * change the state of the resource, the ETag returned must represent
	 * the state before the change. In other words, it must identify
	 * the entity that the change is going to be applied to.
	 *
	 * For GET and HEAD requests, this ETag will also be included in the
	 * response.
	 *
	 * Handlers that modify the resource and want to return an ETag
	 * header representing the new state in the response should set the header
	 * in the execute() method. However, note that responses to PUT requests
	 * must not return an ETag unless the new content of the resource is exactly
	 * the data that was sent by the client in the request body.
	 *
	 * This must be a complete ETag, including double quotes.
	 * See RFC 7231 §7.2 and RFC 7232 §2.3 for semantics.
	 *
	 * This method should return null if the resource doesn't exist. It may also
	 * return null if ETag semantics is not supported by the Handler.
	 *
	 * @stable to override
	 *
	 * @return string|null
	 */
	protected function getETag() {
		return null;
	}

	/**
	 * The subclass should override this to indicate whether the resource
	 * exists. This is used for wildcard validators, for example "If-Match: *"
	 * fails if the resource does not exist.
	 *
	 * If this method returns null, the value returned by getETag() will be used
	 * to determine whether the resource exists.
	 *
	 * In a state-changing request, the return value of this method should
	 * reflect the state before the requested change is applied.
	 *
	 * @stable to override
	 *
	 * @return bool|null
	 */
	protected function hasRepresentation() {
		return null;
	}

	/**
	 * Indicates whether this route requires read rights.
	 *
	 * The handler should override this if it does not need to read from the
	 * wiki. This is uncommon, but may be useful for login and other account
	 * management APIs.
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function needsReadAccess() {
		return true;
	}

	/**
	 * Indicates whether this route requires write access to the wiki.
	 *
	 * Handlers may override this method to return false if and only if the operation they
	 * implement is "safe" per RFC 7231 section 4.2.1. A handler's operation is "safe" if
	 * it is essentially read-only, i.e. the client does not request nor expect any state
	 * change that would be observable in the responses to future requests.
	 *
	 * Implementations of this method must always return the same value, regardless of the
	 * parameters passed to the constructor or system state.
	 *
	 * Handlers for GET, HEAD, OPTIONS, and TRACE requests should each implement a "safe"
	 * operation. Handlers of PUT and DELETE requests should each implement a non-"safe"
	 * operation. Note that handlers of POST requests can implement a "safe" operation,
	 * particularly in the case where large input parameters are required.
	 *
	 * The information provided by this method is used to perform basic authorization checks
	 * and to determine whether cross-origin requests are safe.
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function needsWriteAccess() {
		return true;
	}

	/**
	 * Indicates whether this route can be accessed only by session providers safe vs csrf
	 *
	 * The handler should override this if the route must only be accessed by session
	 * providers that are safe against csrf.
	 *
	 * A return value of false does not necessarily mean the route is vulnerable to csrf attacks.
	 * It means the route can be accessed by session providers that are not automatically safe
	 * against csrf attacks, so the possibility of csrf attacks must be considered.
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function requireSafeAgainstCsrf() {
		return false;
	}

	/**
	 * The handler can override this to do any necessary setup after the init functions
	 * are called to inject dependencies.
	 *
	 * @stable to override
	 * @throws HttpException if the handler does not accept the request for
	 *         some reason.
	 */
	protected function postInitSetup() {
	}

	/**
	 * The handler can override this to do any necessary setup after validate()
	 * has been called. This gives the handler an opportunity to do initialization
	 * based on parameters before pre-execution calls like getLastModified() or getETag().
	 *
	 * @stable to override
	 * @since 1.36
	 */
	protected function postValidationSetup() {
	}

	/**
	 * Execute the handler. This is called after parameter validation. The
	 * return value can either be a Response or any type accepted by
	 * ResponseFactory::createFromReturnValue().
	 *
	 * To automatically construct an error response, execute() should throw a
	 * \MediaWiki\Rest\HttpException. Such exceptions will not be logged like
	 * a normal exception.
	 *
	 * If execute() throws any other kind of exception, the exception will be
	 * logged and a generic 500 error page will be shown.
	 *
	 * @stable to override
	 *
	 * @return mixed
	 */
	abstract public function execute();
}
