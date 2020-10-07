<?php

namespace MediaWiki\Hook;

use Title;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface FileUndeleteCompleteHook {
	/**
	 * This hook is called when a file is undeleted.
	 *
	 * @since 1.35
	 *
	 * @param Title $title Title object for the file
	 * @param int[] $fileVersions Array of undeleted filearchive IDs. Empty if
	 *   all versions were restored.
	 * @param User $user User who performed the undeletion
	 * @param string $reason
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason );
}
