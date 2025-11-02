<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use MediaWiki\MediaWikiServices;

/**
 * Show an error when the wiki is locked/read-only and the user tries to do
 * something that requires write access.
 *
 * @newable
 * @since 1.18
 * @ingroup Exception
 */
class ReadOnlyError extends ErrorPageError {

	/**
	 * @stable to call
	 */
	public function __construct() {
		$reason = MediaWikiServices::getInstance()->getReadOnlyMode()->getReason();
		parent::__construct(
			'readonly',
			'readonlytext',
			$reason ? [ $reason ] : []
		);
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ReadOnlyError::class, 'ReadOnlyError' );
