<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Mime;

use Exception;

/**
 * Internal exception class. Will be caught by private code.
 *
 * @newable
 */
class ZipDirectoryReaderError extends Exception {
	/** @var string */
	protected $errorCode;

	/**
	 * @stable to call
	 *
	 * @param string $code
	 */
	public function __construct( $code ) {
		$this->errorCode = $code;
		parent::__construct( "ZipDirectoryReader error: $code" );
	}

	/**
	 * @return string
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}
}
