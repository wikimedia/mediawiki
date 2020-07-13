<?php

namespace MediaWiki\Hook;

use MessageSpecifier;
use UploadBase;
use User;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadVerifyUploadHook {
	/**
	 * Use this hook to perform upload verification, based on both file properties like
	 * MIME type (same as UploadVerifyFile) and the information entered by the user
	 * (upload comment, file page contents etc.).
	 *
	 * @since 1.35
	 *
	 * @param UploadBase $upload Instance of UploadBase, with all info about the upload
	 * @param User $user User uploading this file
	 * @param array|null $props File properties, as returned by MWFileProps::getPropsFromPath().
	 *   Note this is not always guaranteed to be set, e.g. in test scenarios.
	 *   Call MWFileProps::getPropsFromPath() yourself in case you need the information.
	 * @param string $comment Upload log comment (also used as edit summary)
	 * @param string $pageText File description page text (only used for new uploads)
	 * @param array|MessageSpecifier &$error Output: If the file upload should be
	 *   prevented, set this to the reason in the form of [ messagename, param1, param2, ... ]
	 *   or a MessageSpecifier instance. (You might want to use ApiMessage to
	 *   provide machine-readable details for the API.)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadVerifyUpload( $upload, $user, $props, $comment,
		$pageText, &$error
	);
}
