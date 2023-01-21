<?php

namespace MediaWiki\Rest;

use DateTime;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\Authority;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Session\Session;
use Wikimedia\Message\MessageValue;

/**
 * Base class for REST route handlers.
 *
 * @stable to extend.
 */
abstract class Handler {

	/**
	 * (string) ParamValidator constant to specify the source of the parameter.
	 * Value must be 'path', 'query', or 'post'.
	 * 'post' refers to application/x-www-form-urlencoded or multipart/form-data encoded parameters
	 * in the body of a POST request (in other words, parameters in PHP's $_POST). For other kinds
	 * of POST parameters, such as JSON fields, use BodyValidator instead of ParamValidator.
	 */
	public const PARAM_SOURCE = 'rest-param-source';

	/** @var Router */
	private $router;

	/** @var RequestInterface */
	private $request;

	/** @var Authority */
	private $authority;

	/** @var array */
	private $config;

	/** @var ResponseFactory */
	private $responseFactory;

	/** @var array|null */
	private $validatedParams;

	/** @var mixed */
	private $validatedBody;

	/** @var ConditionalHeaderUtil */
	private $conditionalHeaderUtil;

	/** @var HookContainer */
	private $hookContainer;

	/** @var Session */
	private $session;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Initialise with dependencies from the Router. This is called after construction.
	 * @param Router $router
	 * @param RequestInterface $request
	 * @param array $config
	 * @param Authority $authority
	 * @param ResponseFactory $responseFactory
	 * @param HookContainer $hookContainer
	 * @param Session $session
	 * @internal
	 */
	final public function init( Router $router, RequestInterface $request, array $config,
		Authority $authority, ResponseFactory $responseFactory, HookContainer $hookContainer,
		Session $session
	) {
		$this->router = $router;
		$this->request = $request;
		$this->authority = $authority;
		$this->config = $config;
		$this->responseFactory = $responseFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->session = $session;
		$this->postInitSetup();
	}

	/**
	 * Returns the path this handler is bound to, including path variables.
	 *
	 * @return string
	 */
	public function getPath(): string {
		return $this->getConfig()['path'];
	}

	/**
	 * Get the Router. The return type declaration causes it to raise
	 * a fatal error if init() has not yet been called.
	 * @return Router
	 */
	protected function getRouter(): Router {
		return $this->router;
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
		$path = $this->getConfig()['path'];
		return $this->router->getRouteUrl( $path, $pathParams, $queryParams );
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
	 * a fatal error if init() has not yet been called.
	 *
	 * @return RequestInterface
	 */
	public function getRequest(): RequestInterface {
		return $this->request;
	}

	/**
	 * Get the current acting authority. The return type declaration causes it to raise
	 * a fatal error if init() has not yet been called.
	 *
	 * @since 1.36
	 * @return Authority
	 */
	public function getAuthority(): Authority {
		return $this->authority;
	}

	/**
	 * Get the configuration array for the current route. The return type
	 * declaration causes it to raise a fatal error if init() has not
	 * been called.
	 *
	 * @return array
	 */
	public function getConfig(): array {
		return $this->config;
	}

	/**
	 * Get the ResponseFactory which can be used to generate Response objects.
	 * This will raise a fatal error if init() has not been
	 * called.
	 *
	 * @return ResponseFactory
	 */
	public function getResponseFactory(): ResponseFactory {
		return $this->responseFactory;
	}

	/**
	 * Get the Session.
	 * This will raise a fatal error if init() has not been
	 * called.
	 *
	 * @return Session
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
		$validatedParams = $restValidator->validateParams( $this->getParamSettings() );
		$validatedBody = $restValidator->validateBody( $this->request, $this );
		$this->validatedParams = $validatedParams;
		$this->validatedBody = $validatedBody;
		$this->postValidationSetup();
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
			$this->conditionalHeaderUtil->setValidators(
				$this->getETag(),
				$this->getLastModified(),
				$this->hasRepresentation()
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
	 * will be set per default.
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
	 *
	 * @param ResponseInterface $response
	 */
	public function applyCacheControl( ResponseInterface $response ) {
		// NOTE: keep this consistent with the logic in OutputPage::sendCacheControl

		if ( $response->getHeaderLine( 'Set-Cookie' ) ) {
			// If the response sets cookies, it must not be cached in proxies!
			$response->setHeader( 'Cache-Control', 'private,no-cache,s-maxage=0' );
		}

		if ( !$response->getHeaderLine( 'Cache-Control' ) ) {
			$rqMethod = $this->getRequest()->getMethod();
			if ( $rqMethod !== 'GET' && $rqMethod !== 'HEAD' ) {
				// Responses to requests other than GET or HEAD should not be cacheable per default.
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
	 * Can be used for validating parameters inside an application/x-www-form-urlencoded or
	 * multipart/form-data POST body (i.e. parameters which would be present in PHP's $_POST
	 * array). For validating other kinds of request bodies, override getBodyValidator().
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
	 * Fetch the BodyValidator
	 *
	 * @stable to override
	 *
	 * @param string $contentType Content type of the request.
	 * @return BodyValidator
	 */
	public function getBodyValidator( $contentType ) {
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
	 * @return mixed Value returned by the body validator, or null if validate() was
	 *  not called yet, validation failed, there was no body, or the body was form data.
	 */
	public function getValidatedBody() {
		return $this->validatedBody;
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
	 * @return bool|string|int|float|DateTime|null
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
	 * Indicates whether this route requires write access.
	 *
	 * The handler should override this if the route does not need to write to
	 * the database.
	 *
	 * This should return true for routes that may require synchronous database writes.
	 * Modules that do not need such writes should also not rely on primary database access,
	 * since only read queries are needed and each primary DB is a single point of failure.
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
	 * The handler can override this to do any necessary setup after init()
	 * is called to inject the dependencies.
	 *
	 * @stable to override
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
