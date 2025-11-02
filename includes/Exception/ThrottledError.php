<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

/**
 * Show an error when the user hits a rate limit.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class ThrottledError extends ErrorPageError {

	/**
	 * @stable to call
	 */
	public function __construct() {
		parent::__construct(
			'actionthrottled',
			'actionthrottledtext'
		);
	}

	/** @inheritDoc */
	public function report( $action = ErrorPageError::SEND_OUTPUT ) {
		global $wgOut;
		$wgOut->setStatusCode( 429 );
		parent::report( $action );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ThrottledError::class, 'ThrottledError' );
