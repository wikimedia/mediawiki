<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * A request authorizer which checks needsReadAccess() in the
 * handler and calls isReadAllowed() in the subclass
 * accordingly.
 *
 * @internal
 */
abstract class BasicRequestAuthorizer {
	protected $request;
	protected $handler;

	/**
	 * @param RequestInterface $request
	 * @param Handler $handler
	 */
	public function __construct( RequestInterface $request, Handler $handler ) {
		$this->request = $request;
		$this->handler = $handler;
	}

	/**
	 * @see BasicAuthorizerInterface::authorize()
	 * @return string|null If the request is denied, the string error code. If
	 *   the request is allowed, null.
	 */
	public function authorize() {
		if ( $this->handler->needsReadAccess() && !$this->isReadAllowed() ) {
			return 'rest-read-denied';
		}
		return null;
	}

	/**
	 * Check if the current user is allowed to read from the wiki
	 *
	 * @return bool
	 */
	abstract protected function isReadAllowed();
}
