<?php

namespace MediaWiki\Rest\Reporter;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\RequestInterface;
use Throwable;

/**
 * Error reporter based on php's native trigger_error() method.
 * @since 1.38
 */
class PHPErrorReporter implements ErrorReporter {

	/** @var int */
	private $level;

	/**
	 * @param int $level The error level to pass to trigger_error
	 */
	public function __construct( $level = E_USER_WARNING ) {
		$this->level = $level;
	}

	/**
	 * @param Throwable $error
	 * @param Handler|null $handler
	 * @param RequestInterface $request
	 */
	public function reportError( Throwable $error, ?Handler $handler, RequestInterface $request ) {
		$firstLine = preg_split( '#$#m', (string)$error, 0 )[0];
		trigger_error( $firstLine, $this->level );
	}

}
