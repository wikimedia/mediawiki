<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleMoveHook {
	/**
	 * Before moving an article (title).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $old old title
	 * @param ?mixed $nt new title
	 * @param ?mixed $user user who does the move
	 * @param ?mixed $reason string of the reason provided by the user
	 * @param ?mixed &$status Status object. To abort the move, add a fatal error to this object
	 *   	(i.e. call $status->fatal()).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleMove( $old, $nt, $user, $reason, &$status );
}
