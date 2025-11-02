<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

/**
 * An error page that emits an HTTP 400 Bad Request status code.
 *
 * @newable
 * @stable to extend
 * @since 1.28
 * @ingroup Exception
 */
class BadRequestError extends ErrorPageError {

	/**
	 * @stable to override
	 * @inheritDoc
	 */
	public function report( $action = self::SEND_OUTPUT ) {
		global $wgOut;
		$wgOut->setStatusCode( 400 );
		parent::report( $action );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( BadRequestError::class, 'BadRequestError' );
