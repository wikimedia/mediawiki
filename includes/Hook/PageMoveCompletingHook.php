<?php

namespace MediaWiki\Hook;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface PageMoveCompletingHook {
	/**
	 * This hook is called after moving an article (title), pre-commit
	 *
	 * @since 1.35
	 *
	 * @param LinkTarget $old Old title
	 * @param LinkTarget $new New title
	 * @param UserIdentity $user User who did the move
	 * @param int $pageid Database ID of the page that's been moved
	 * @param int $redirid Database ID of the created redirect
	 * @param string $reason Reason for the move
	 * @param RevisionRecord $revision Revision created by the move
	 * @return bool|void True or no return value to continue or false stop other hook handlers,
	 *     doesn't abort the move itself
	 */
	public function onPageMoveCompleting( $old, $new, $user, $pageid, $redirid,
		$reason, $revision
	);
}
