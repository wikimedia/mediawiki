<?php

namespace MediaWiki\Rest;

/**
 * This is the base exception class for non-fatal exceptions thrown from REST
 * handlers. The exception is not logged, it is merely converted to an
 * error response.
 */
class HttpException extends \Exception {

	/** @var array|null */
	private $errorData = null;

	public function __construct( $message, $code = 500, $errorData = null ) {
		parent::__construct( $message, $code );
		$this->errorData = $errorData;
	}

	/**
	 * @return array|null
	 */
	public function getErrorData() {
		return $this->errorData;
	}
}
