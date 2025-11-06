<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use Exception;
use UnexpectedValueException;

/**
 * Subclass of UnexpectedValueException annotated with data for debug logs.
 *
 * @newable
 * @since 1.27
 * @ingroup Session
 * @copyright © 2016 Wikimedia Foundation and contributors
 */
class MetadataMergeException extends UnexpectedValueException {
	/** @var array */
	protected $context;

	/**
	 * @param string $message
	 * @param int $code
	 * @param Exception|null $previous
	 * @param array $context Additional context data
	 */
	public function __construct(
		$message = '',
		$code = 0,
		?Exception $previous = null,
		array $context = []
	) {
		parent::__construct( $message, $code, $previous );
		$this->context = $context;
	}

	/**
	 * Get context data.
	 * @return array
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * Set context data.
	 */
	public function setContext( array $context ) {
		$this->context = $context;
	}
}
