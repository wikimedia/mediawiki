<?php

namespace MediaWiki\Hook;

use Status;
use Title;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface MovePageIsValidMoveHook {
	/**
	 * Use this hook to specify whether a page can be moved for technical reasons.
	 *
	 * @since 1.35
	 *
	 * @param Title $oldTitle Current (old) location
	 * @param Title $newTitle New location
	 * @param Status $status Status object to pass error messages to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMovePageIsValidMove( $oldTitle, $newTitle, $status );
}
