<?php

namespace MediaWiki\Rest\BasicAccess;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;

/**
 * An interface used by Router to ensure that the client has "basic" access,
 * i.e. the ability to read or write to the wiki.
 *
 * @internal
 */
interface BasicAuthorizerInterface {
	/**
	 * Determine whether a request should be permitted, given the handler's
	 * needsReadAccess() and needsWriteAccess().
	 *
	 * If the request should be permitted, return null. If the request should
	 * be denied, return a string error identifier.
	 *
	 * @param RequestInterface $request
	 * @param Handler $handler
	 * @return string|null If the request is denied, the string error code. If
	 *   the request is allowed, null.
	 */
	public function authorize( RequestInterface $request, Handler $handler );
}
