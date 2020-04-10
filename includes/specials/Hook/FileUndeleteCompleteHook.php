<?php

namespace MediaWiki\Hook;

use Title;
use User;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FileUndeleteCompleteHook {
	/**
	 * This hook is called when a file is undeleted
	 *
	 * @since 1.35
	 *
	 * @param Title $title title object to the file
	 * @param array $fileVersions array of undeleted versions. Empty if all versions were restored
	 * @param User $user user who performed the undeletion
	 * @param string $reason reason
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason );
}
