<?php

namespace MediaWiki\Hook;

use SpecialUpload;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialUploadCompleteHook {
	/**
	 * This hook is called after successfully uploading a file from Special:Upload.
	 *
	 * @since 1.35
	 *
	 * @param SpecialUpload $form The SpecialUpload object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialUploadComplete( $form );
}
