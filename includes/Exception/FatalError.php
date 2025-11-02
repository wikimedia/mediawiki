<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

/**
 * Abort the web request with a custom HTML string that will represent
 * the entire response.
 *
 * This is not caught anywhere in MediaWiki code. It is handled through PHP's
 * exception handler, which calls MWExceptionHandler::report.
 *
 * Unlike MWException, this will not provide error IDs or stack traces.
 * It is intended for early installation and configuration problems where
 * the exception is all the site administrator needs to know.
 *
 * @newable
 * @stable to extend
 * @since 1.7
 * @ingroup Exception
 */
class FatalError extends MWException {

	/**
	 * Replace our usual detailed HTML response for uncaught exceptions,
	 * with just the bare message as HTML.
	 *
	 * @return string
	 */
	public function getHTML() {
		return $this->getMessage();
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->getMessage();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( FatalError::class, 'FatalError' );
