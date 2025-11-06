<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Installer;

use MediaWiki\Exception\MWException;
use MediaWiki\Status\Status;
use Throwable;

/**
 * Exception thrown if an error occurs during installation
 * @ingroup Exception
 */
class InstallException extends MWException {
	/**
	 * @var Status The state when an exception occurs
	 */
	private $status;

	/**
	 * @param Status $status The state when an exception occurs
	 * @param string $message The Exception message to throw
	 * @param int $code The Exception code
	 * @param Throwable|null $previous The previous throwable used for the exception chaining
	 */
	public function __construct( Status $status, $message = '', $code = 0,
		?Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
		$this->status = $status;
	}

	public function getStatus(): Status {
		return $this->status;
	}
}
