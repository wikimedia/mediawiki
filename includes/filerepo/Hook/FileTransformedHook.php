<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface FileTransformedHook {
	/**
	 * When a file is transformed and moved into storage.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $file reference to the File object
	 * @param ?mixed $thumb the MediaTransformOutput object
	 * @param ?mixed $tmpThumbPath The temporary file system path of the transformed file
	 * @param ?mixed $thumbPath The permanent storage path of the transformed file
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileTransformed( $file, $thumb, $tmpThumbPath, $thumbPath );
}
