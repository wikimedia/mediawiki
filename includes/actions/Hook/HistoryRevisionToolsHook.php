<?php

namespace MediaWiki\Hook;

use Revision;
use User;

/**
 * @deprecated since 1.35
 * @ingroup Hooks
 */
interface HistoryRevisionToolsHook {
	/**
	 * Use this hook to override or extend the revision tools available from the
	 * page history view, i.e. undo, rollback, etc.
	 *
	 * @since 1.35
	 *
	 * @param Revision $rev
	 * @param string[] &$links Array of HTML links
	 * @param Revision|null $prevRev Revision object, next in line in page history, or null
	 * @param User $user Current user object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHistoryRevisionTools( $rev, &$links, $prevRev, $user );
}
