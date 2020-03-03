<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UploadCompleteHook {
	/**
	 * Upon completion of a file upload.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $uploadBase UploadBase (or subclass) object. File can be accessed by
	 *   $uploadBase->getLocalFile().
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUploadComplete( $uploadBase );
}
