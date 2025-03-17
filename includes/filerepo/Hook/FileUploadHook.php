<?php

namespace MediaWiki\Hook;

use MediaWiki\FileRepo\File\File;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "FileUpload" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface FileUploadHook {
	/**
	 * This hook is called when a file upload occurs.
	 *
	 * @since 1.35
	 *
	 * @param File $file Image object representing the file that was uploaded
	 * @param bool $reupload Boolean indicating if there was a previously another image there or
	 *   not (since 1.17)
	 * @param bool $hasDescription Boolean indicating that there was already a description page
	 *   and a new one from the comment wasn't created (since 1.17)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileUpload( $file, $reupload, $hasDescription );
}
