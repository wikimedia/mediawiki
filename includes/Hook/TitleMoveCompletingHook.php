<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface TitleMoveCompletingHook {
	/**
	 * After moving an article (title), pre-commit.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $old old title
	 * @param ?mixed $nt new title
	 * @param ?mixed $user user who did the move
	 * @param ?mixed $pageid database ID of the page that's been moved
	 * @param ?mixed $redirid database ID of the created redirect
	 * @param ?mixed $reason reason for the move
	 * @param ?mixed $revision the Revision created by the move
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleMoveCompleting( $old, $nt, $user, $pageid, $redirid,
		$reason, $revision
	);
}
