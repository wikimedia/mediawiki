<?php

namespace MediaWiki\Rest\Reporter;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use Throwable;

/**
 * An ErrorReporter internally reports an error that happened during the handling of a request.
 * It must have no effect on the response sent to the client.
 *
 * @since 1.38
 */
interface ErrorReporter {

	/**
	 * @param Throwable $error
	 * @param Handler|null $handler
	 * @param RequestInterface $request
	 */
	public function reportError( Throwable $error, ?Handler $handler, RequestInterface $request );

}
