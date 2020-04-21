<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface MovePageIsValidMoveHook {
	/**
	 * Specify whether a page can be moved for technical
	 * reasons.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $oldTitle Title object of the current (old) location
	 * @param ?mixed $newTitle Title object of the new location
	 * @param ?mixed $status Status object to pass error messages to
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMovePageIsValidMove( $oldTitle, $newTitle, $status );
}
