<?php

namespace MediaWiki\Rest;

/**
 * This is an exception class that extends HttpException and will
 * generate a redirect when handled. It is used when a redirect is
 * treated as an exception output in your API, and you want to be able
 * to throw it from wherever you are and immediately halt request
 * processing.
 *
 * @newable
 * @since 1.36
 */
class RedirectException extends HttpException {

	/**
	 * The redirect target (an absolute URL)
	 * @var string
	 */
	private $target;

	/**
	 * @stable to call
	 *
	 * @param int $code The HTTP status code (3xx) for this redirect
	 * @param string $target The redirect target (an absolute URL)
	 */
	public function __construct( int $code, string $target ) {
		parent::__construct( 'Redirect', $code );
		$this->target = $target;
	}

	/**
	 * @return string
	 */
	public function getTarget(): string {
		return $this->target;
	}
}
