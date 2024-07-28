<?php

namespace MediaWiki\Hook;

use UploadBase;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UploadVerifyFile" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadVerifyFileHook {
	/**
	 * Use this hook to perform extra file verification, based on MIME type, etc.
	 *
	 * @since 1.35
	 *
	 * @param UploadBase $upload Instance of UploadBase, with all info about the upload
	 * @param string $mime Uploaded file's MIME type, as detected by MediaWiki.
	 *   Handlers will typically only apply for specific MIME types.
	 * @param bool|array|MessageSpecifier &$error Output: true if the file is valid.
	 *   Otherwise, set this to the reason in the form of [ messagename, param1, param2, ... ]
	 *   or a MessageSpecifier instance. (You might want to use ApiMessage to provide machine
	 *   -readable details for the API.)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadVerifyFile( $upload, $mime, &$error );
}
