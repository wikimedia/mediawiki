<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleMoveStartingHook {
	/**
	 * Before moving an article (title), but just after the atomic
	 * DB section starts.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $old old title
	 * @param ?mixed $nt new title
	 * @param ?mixed $user user who does the move
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleMoveStarting( $old, $nt, $user );
}
