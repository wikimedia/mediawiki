<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadVerifyFileHook {
	/**
	 * extra file verification, based on MIME type, etc.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $upload (object) an instance of UploadBase, with all info about the upload
	 * @param ?mixed $mime (string) The uploaded file's MIME type, as detected by MediaWiki.
	 *   Handlers will typically only apply for specific MIME types.
	 * @param ?mixed &$error (object) output: true if the file is valid. Otherwise, set this to the
	 *   reason in the form of [ messagename, param1, param2, ... ] or a
	 *   MessageSpecifier instance (you might want to use ApiMessage to provide machine
	 *   -readable details for the API).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadVerifyFile( $upload, $mime, &$error );
}
