<?php

namespace MediaWiki\Hook;

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface HistoryToolsHook {
	/**
	 * Use this hook to override or extend the revision tools available from the
	 * page history view, i.e. undo, rollback, etc.
	 *
	 * @since 1.35
	 *
	 * @param RevisionRecord $revRecord
	 * @param string[] &$links Array of HTML links
	 * @param RevisionRecord|null $prevRevRecord RevisionRecord object, next in line
	 *   in page history, or null
	 * @param UserIdentity $userIdentity Current user
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHistoryTools( $revRecord, &$links, $prevRevRecord, $userIdentity );
}
