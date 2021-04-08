<?php

namespace MediaWiki\Page\Hook;

use ManualLogEntry;
use PageArchive;
use User;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ArticleUndeleteLogEntry" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ArticleUndeleteLogEntryHook {
	/**
	 * This hook is called when a log entry is generated but not yet saved.
	 *
	 * @since 1.35
	 *
	 * @param PageArchive $pageArchive
	 * @param ManualLogEntry &$logEntry
	 * @param User $user User who is performing the log action
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleUndeleteLogEntry( $pageArchive, &$logEntry, $user );
}
