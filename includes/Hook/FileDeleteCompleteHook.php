<?php

namespace MediaWiki\Hook;

use LocalFile;
use User;
use WikiFilePage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface FileDeleteCompleteHook {
	/**
	 * This hook is called when a file is deleted.
	 *
	 * @since 1.35
	 *
	 * @param LocalFile $file Reference to the deleted file
	 * @param string $oldimage In case of the deletion of an old image, the name of the old file
	 * @param WikiFilePage $article In case all revisions of the file are deleted, a reference to
	 *   the WikiFilePage associated with the file
	 * @param User $user User who performed the deletion
	 * @param string $reason Reason
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileDeleteComplete( $file, $oldimage, $article, $user,
		$reason
	);
}
