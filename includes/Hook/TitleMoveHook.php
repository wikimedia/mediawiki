<?php

namespace MediaWiki\Hook;

use Status;
use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface TitleMoveHook {
	/**
	 * This hook is called before moving an article (title).
	 *
	 * @since 1.35
	 *
	 * @param Title $old Old title
	 * @param Title $nt New title
	 * @param User $user User who does the move
	 * @param string $reason Reason provided by the user
	 * @param Status &$status To abort the move, add a fatal error to this object
	 *   	(i.e. call $status->fatal())
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleMove( $old, $nt, $user, $reason, &$status );
}
