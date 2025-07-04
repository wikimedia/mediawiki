<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * An implementation of BasicAuthorizerInterface which creates a request-local
 * object (a request authorizer) to do the actual authorization.
 *
 * @internal
 */
abstract class BasicAuthorizerBase implements BasicAuthorizerInterface {
	/** @inheritDoc */
	public function authorize( RequestInterface $request, Handler $handler ) {
		return $this->createRequestAuthorizer( $request, $handler )->authorize();
	}

	/**
	 * Create a BasicRequestAuthorizer to authorize the request.
	 *
	 * @param RequestInterface $request
	 * @param Handler $handler
	 * @return BasicRequestAuthorizer
	 */
	abstract protected function createRequestAuthorizer( RequestInterface $request,
		Handler $handler ): BasicRequestAuthorizer;
}
