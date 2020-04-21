<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialUploadCompleteHook {
	/**
	 * Called after successfully uploading a file from
	 * Special:Upload.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $form The SpecialUpload object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialUploadComplete( $form );
}
