<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface HistoryRevisionToolsHook {
	/**
	 * Override or extend the revision tools available from the
	 * page history view, i.e. undo, rollback, etc.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $rev Revision object
	 * @param ?mixed &$links Array of HTML links
	 * @param ?mixed $prevRev Revision object, next in line in page history, or null
	 * @param ?mixed $user Current user object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onHistoryRevisionTools( $rev, &$links, $prevRev, $user );
}
