<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FileUndeleteCompleteHook {
	/**
	 * When a file is undeleted
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title title object to the file
	 * @param ?mixed $fileVersions array of undeleted versions. Empty if all versions were restored
	 * @param ?mixed $user user who performed the undeletion
	 * @param ?mixed $reason reason
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileUndeleteComplete( $title, $fileVersions, $user, $reason );
}
