<?php

namespace MediaWiki\Rest;

abstract class Handler {
	/** @var Router */
	private $router;

	/** @var RequestInterface */
	private $request;

	/** @var array */
	private $config;

	/** @var ResponseFactory */
	private $responseFactory;

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
