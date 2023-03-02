<?php

namespace MediaWiki\Hook;

use MediaWiki\Title\Title;
use Status;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "TitleMove" to register handlers implementing this interface.
 *
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
	public function onTitleMove( Title $old, Title $nt, User $user, $reason, Status &$status );
}
