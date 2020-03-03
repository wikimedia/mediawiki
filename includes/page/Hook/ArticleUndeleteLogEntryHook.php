<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ArticleUndeleteLogEntryHook {
	/**
	 * When a log entry is generated but not yet saved.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $pageArchive the PageArchive object
	 * @param ?mixed &$logEntry ManualLogEntry object
	 * @param ?mixed $user User who is performing the log action
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onArticleUndeleteLogEntry( $pageArchive, &$logEntry, $user );
}
