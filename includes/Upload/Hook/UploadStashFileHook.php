<?php

namespace MediaWiki\Hook;

use MediaWiki\User\User;
use UploadBase;
use Wikimedia\Message\MessageSpecifier;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "UploadStashFile" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface UploadStashFileHook {
	/**
	 * This hook is called before a file is stashed (uploaded to stash).
	 * Note that code which has not been updated for MediaWiki 1.28 may not call this
	 * hook. If your extension absolutely, positively must prevent some files from
	 * being uploaded, use UploadVerifyFile or UploadVerifyUpload.
	 *
	 * @since 1.35
	 *
	 * @param UploadBase $upload Instance of UploadBase with all info about the upload
	 * @param User $user User uploading this file
	 * @param array|null $props File properties, as returned by MWFileProps::getPropsFromPath().
	 *   Note this is not always guaranteed to be set, e.g. in test scenarios. Call
	 *   MWFileProps::getPropsFromPath() yourself in case you need the information.
	 * @param array|MessageSpecifier|null &$error Output: If the file stashing should
	 *   be prevented, set this to the reason in the form of [ messagename, param1, param2, ... ]
	 *   or a MessageSpecifier instance. (You might want to use ApiMessage to provide machine
	 *   -readable details for the API.)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadStashFile( UploadBase $upload, User $user, ?array $props, &$error );
}
