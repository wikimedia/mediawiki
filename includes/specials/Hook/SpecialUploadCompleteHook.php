<?php

namespace MediaWiki\Hook;

use MediaWiki\Specials\SpecialUpload;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialUploadComplete" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialUploadCompleteHook {
	/**
	 * This hook is called after successfully uploading a file from Special:Upload.
	 *
	 * @since 1.35
	 *
	 * @param SpecialUpload $form
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialUploadComplete( $form );
}
