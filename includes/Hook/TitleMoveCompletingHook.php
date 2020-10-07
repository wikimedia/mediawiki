<?php

namespace MediaWiki\Hook;

use Revision;
use Title;
use User;

/**
 * @deprecated since 1.35, use the PageMoveCompleting hook instead
 * @ingroup Hooks
 */
interface TitleMoveCompletingHook {
	/**
	 * This hook is called after moving an article (title), pre-commit.
	 *
	 * @since 1.35
	 *
	 * @param Title $old Old title
	 * @param Title $nt New title
	 * @param User $user User who did the move
	 * @param int $pageid Database ID of the page that's been moved
	 * @param int $redirid Database ID of the created redirect
	 * @param string $reason Reason for the move
	 * @param Revision $revision Revision created by the move
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onTitleMoveCompleting( $old, $nt, $user, $pageid, $redirid,
		$reason, $revision
	);
}
