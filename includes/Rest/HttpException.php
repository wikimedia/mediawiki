<?php

namespace MediaWiki\Rest;

/**
 * This is the base exception class for non-fatal exceptions thrown from REST
 * handlers. The exception is not logged, it is merely converted to an
 * error response.
 *
 * @newable
 */
class HttpException extends \Exception {

	/**
	 * @stable to call
	 */
	public function __construct(
		string $message,
		int $code = 500,
		private readonly array $errorData = [],
	) {
		parent::__construct( $message, $code );
	}

	public function getErrorData(): array {
		return $this->errorData;
	}
}
