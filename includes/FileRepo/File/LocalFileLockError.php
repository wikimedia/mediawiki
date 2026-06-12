<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Status\Status;

/**
 * @newable
 * @stable to extend
 * @deprecated since 1.47
 * @ingroup FileAbstraction
 */
class LocalFileLockError extends ErrorPageError {

	/**
	 * @stable to call
	 *
	 * @param Status $status
	 */
	public function __construct( Status $status ) {
		wfDeprecated( __CLASS__, '1.47' );
		parent::__construct(
			'actionfailed',
			$status->getMessage()
		);
	}

	/** @inheritDoc */
	public function report( $action = self::SEND_OUTPUT ) {
		global $wgOut;
		$wgOut->setStatusCode( 429 );
		parent::report( $action );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LocalFileLockError::class, 'LocalFileLockError' );
