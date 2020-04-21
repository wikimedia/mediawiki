<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadStashFileHook {
	/**
	 * Before a file is stashed (uploaded to stash).
	 * Note that code which has not been updated for MediaWiki 1.28 may not call this
	 * hook. If your extension absolutely, positively must prevent some files from
	 * being uploaded, use UploadVerifyFile or UploadVerifyUpload.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $upload (object) An instance of UploadBase, with all info about the upload
	 * @param ?mixed $user (object) An instance of User, the user uploading this file
	 * @param ?mixed $props (array|null) File properties, as returned by
	 *   MWFileProps::getPropsFromPath(). Note this is not always guaranteed to be set,
	 *   e.g. in test scenarios. Call MWFileProps::getPropsFromPath() yourself in case
	 *   you need the information.
	 * @param ?mixed &$error output: If the file stashing should be prevented, set this to the
	 *   reason in the form of [ messagename, param1, param2, ... ] or a
	 *   MessageSpecifier instance (you might want to use ApiMessage to provide machine
	 *   -readable details for the API).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadStashFile( $upload, $user, $props, &$error );
}
