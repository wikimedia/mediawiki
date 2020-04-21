<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FileDeleteCompleteHook {
	/**
	 * When a file is deleted.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $file reference to the deleted file
	 * @param ?mixed $oldimage in case of the deletion of an old image, the name of the old file
	 * @param ?mixed $article in case all revisions of the file are deleted a reference to the
	 *   WikiFilePage associated with the file.
	 * @param ?mixed $user user who performed the deletion
	 * @param ?mixed $reason reason
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileDeleteComplete( $file, $oldimage, $article, $user,
		$reason
	);
}
