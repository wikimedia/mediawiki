<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FileUploadHook {
	/**
	 * When a file upload occurs.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $file Image object representing the file that was uploaded
	 * @param ?mixed $reupload Boolean indicating if there was a previously another image there or
	 *   not (since 1.17)
	 * @param ?mixed $hasDescription Boolean indicating that there was already a description page
	 *   and a new one from the comment wasn't created (since 1.17)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileUpload( $file, $reupload, $hasDescription );
}
