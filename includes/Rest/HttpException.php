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

	/** @var array */
	private array $errorData;

	/**
	 * @stable to call
	 *
	 * @param string $message
	 * @param int $code
	 * @param array $errorData
	 */
	public function __construct( $message, $code = 500, $errorData = [] ) {
		parent::__construct( $message, $code );
		$this->errorData = $errorData;
	}

	/**
	 * @return array
	 */
	public function getErrorData(): array {
		return $this->errorData;
	}
}
