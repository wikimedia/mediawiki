<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadVerifyUploadHook {
	/**
	 * Upload verification, based on both file properties like
	 * MIME type (same as UploadVerifyFile) and the information entered by the user
	 * (upload comment, file page contents etc.).
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $upload (object) An instance of UploadBase, with all info about the upload
	 * @param ?mixed $user (object) An instance of User, the user uploading this file
	 * @param ?mixed $props (array|null) File properties, as returned by
	 *   MWFileProps::getPropsFromPath(). Note this is not always guaranteed to be set,
	 *   e.g. in test scenarios. Call MWFileProps::getPropsFromPath() yourself in case
	 *   you need the information.
	 * @param ?mixed $comment (string) Upload log comment (also used as edit summary)
	 * @param ?mixed $pageText (string) File description page text (only used for new uploads)
	 * @param ?mixed &$error output: If the file upload should be prevented, set this to the reason
	 *   in the form of [ messagename, param1, param2, ... ] or a MessageSpecifier
	 *   instance (you might want to use ApiMessage to provide machine-readable details
	 *   for the API).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadVerifyUpload( $upload, $user, $props, $comment,
		$pageText, &$error
	);
}
