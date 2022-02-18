<?php

namespace MediaWiki\Rest\Reporter;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use MWExceptionHandler;
use Throwable;

/**
 * Error reporter based on MWExceptionHandler.
 * @see MWExceptionHandler
 * @since 1.38
 */
class MWErrorReporter implements ErrorReporter {

	/**
	 * @param Throwable $error
	 * @param Handler $handler
	 * @param RequestInterface $request
	 */
	public function reportError( Throwable $error, Handler $handler, RequestInterface $request ) {
		MWExceptionHandler::rollbackPrimaryChangesAndLog(
			$error,
			MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
		);
	}

}
