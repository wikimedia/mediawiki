<?php

namespace MediaWiki\Rest;

/**
 * This is the base exception class for non-fatal exceptions thrown from REST
 * handlers. The exception is not logged, it is merely converted to an
 * error response.
 */
class HttpException extends \Exception {
	public function __construct( $message, $code = 500 ) {
		parent::__construct( $message, $code );
	}
}
