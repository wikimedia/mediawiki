<?php

namespace MediaWiki\Rest;

use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;

abstract class Handler {

	/**
	 * (string) ParamValidator constant to specify the source of the parameter.
	 * Value must be 'path', 'query', or 'post'.
	 */
	const PARAM_SOURCE = 'rest-param-source';

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

	/**
	 * Initialise with dependencies from the Router. This is called after construction.
	 * @internal
	 */
	public function init( Router $router, RequestInterface $request, array $config,
		ResponseFactory $responseFactory
	) {
		$this->router = $router;
		$this->request = $request;
		$this->config = $config;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * Get the Router. The return type declaration causes it to raise
	 * a fatal error if init() has not yet been called.
	 */
	protected function getRouter(): Router {
		return $this->router;
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
	 * Fetch ParamValidator settings for parameters
	 *
	 * Every setting must include self::PARAM_SOURCE to specify which part of
	 * the request is to contain the parameter.
	 *
	 * @return array[] Associative array mapping parameter names to
	 *  ParamValidator settings arrays
	 */
	public function getParamSettings() {
		return [];
	}

	/**
	 * Fetch the BodyValidator
	 * @param string $contentType Content type of the request.
	 * @return BodyValidator
	 */
	public function getBodyValidator( $contentType ) {
		return new NullBodyValidator();
	}

	/**
	 * Fetch the validated parameters
	 *
	 * @return array|null Array mapping parameter names to validated values,
	 *  or null if validateParams() was not called yet or validation failed.
	 */
	public function getValidatedParams() {
		return $this->validatedParams;
	}

	/**
	 * Fetch the validated body
	 * @return mixed Value returned by the body validator, or null if validateParams() was
	 *  not called yet, validation failed, there was no body, or the body was form data.
	 */
	public function getValidatedBody() {
		return $this->validatedBody;
	}

	/**
	 * The subclass should override this to provide the maximum last modified
	 * timestamp for the current request. This is called before execute() in
	 * order to decide whether to send a 304.
	 *
	 * The timestamp can be in any format accepted by ConvertibleTimestamp, or
	 * null to indicate that the timestamp is unknown.
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
	 * See RFC 7232 § 2.3 for semantics.
	 *
	 * @return string|null
	 */
	protected function getETag() {
		return null;
	}

	/**
	 * Indicates whether this route requires read rights.
	 *
	 * The handler should override this if it does not need to read from the
	 * wiki. This is uncommon, but may be useful for login and other account
	 * management APIs.
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
	 * @return bool
	 */
	public function needsWriteAccess() {
		return true;
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
	 * @return mixed
	 */
	abstract public function execute();
}
