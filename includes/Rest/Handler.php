<?php

namespace MediaWiki\Rest;

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;

/**
 * Base class for REST route handlers.
 *
 * @stable to extend.
 */
abstract class Handler {

	/**
	 * (string) ParamValidator constant to specify the source of the parameter.
	 * Value must be 'path', 'query', or 'post'.
	 */
	public const PARAM_SOURCE = 'rest-param-source';

	/** @var Router */
	private $router;

	/** @var RequestInterface */
	private $request;

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

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * Initialise with dependencies from the Router. This is called after construction.
	 * @internal
	 * @param Router $router
	 * @param RequestInterface $request
	 * @param array $config
	 * @param ResponseFactory $responseFactory
	 * @param HookContainer $hookContainer
	 */
	final public function init( Router $router, RequestInterface $request, array $config,
		ResponseFactory $responseFactory, HookContainer $hookContainer
	) {
		$this->router = $router;
		$this->request = $request;
		$this->config = $config;
		$this->responseFactory = $responseFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->postInitSetup();
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
	 * Encodes slashes (wfUrlencode does not, but keeping them messes with REST pathes).
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
		$title = str_replace( $replace, $with, $title );

		return $title;
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
				$this->hasRepresentation() );
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
		} else {
			return null;
		}
	}

	/**
	 * Modify the response, adding Last-Modified and ETag headers as indicated
	 * the values previously returned by ETag and getLastModified(). This is
	 * called after execute() returns, and may be overridden.
	 *
	 * @stable to override
	 *
	 * @param ResponseInterface $response
	 */
	public function applyConditionalResponseHeaders( ResponseInterface $response ) {
		$this->getConditionalHeaderUtil()->applyResponseHeaders( $response );
	}

	/**
	 * Fetch ParamValidator settings for parameters
	 *
	 * Every setting must include self::PARAM_SOURCE to specify which part of
	 * the request is to contain the parameter.
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
	 * timestamp for the current request. This is called before execute() in
	 * order to decide whether to send a 304.
	 *
	 * The timestamp can be in any format accepted by ConvertibleTimestamp, or
	 * null to indicate that the timestamp is unknown.
	 *
	 * @stable to override
	 *
	 * @return bool|string|int|float|\DateTime|null
	 */
	protected function getLastModified() {
		return null;
	}

	/**
	 * The subclass should override this to provide an ETag for the current
	 * request. This is called before execute() in order to decide whether to
	 * send a 304.
	 *
	 * This must be a complete ETag, including double quotes.
	 *
	 * See RFC 7232 § 2.3 for semantics.
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
	 * Modules that do not need such writes should also not rely on master database access,
	 * since only read queries are needed and each master DB is a single point of failure.
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function needsWriteAccess() {
		return true;
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
	 * Execute the handler. This is called after parameter validation. The
	 * return value can either be a Response or any type accepted by
	 * ResponseFactory::createFromReturnValue().
	 *
	 * To automatically construct an error response, execute() should throw a
	 * RestException. Such exceptions will not be logged like a normal exception.
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
