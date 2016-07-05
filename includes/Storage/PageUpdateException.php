<?php

namespace MediaWiki\Storage;

use Exception;
use Message;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageUpdateException extends StorageException{

	/**
	 * @var string
	 */
	private $errorCode;

	/**
	 * PageUpdateException constructor.
	 *
	 * @param string|Message $errorCode
	 * @param Exception|null $previous
	 */
	public function __construct( $errorCode, Exception $previous = null ) {
		parent::__construct( 'Page update failed: ' . $errorCode, 0, $previous );

		if ( $errorCode instanceof Message ) {
			//FIXME: don't throw away the message!
			$this->errorCode = $errorCode->getKey();
		} else {
			$this->errorCode = $errorCode;
		}
	}

	/**
	 * @return string
	 */
	public function getErrorCode() {
		return $this->errorCode;
	}

}
