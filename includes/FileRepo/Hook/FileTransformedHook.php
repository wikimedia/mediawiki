<?php

namespace MediaWiki\FileRepo\Hook;

use MediaWiki\FileRepo\File\File;
use MediaWiki\Media\MediaTransformOutput;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "FileTransformed" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface FileTransformedHook {
	/**
	 * This hook is called when a file is transformed and moved into storage.
	 *
	 * @since 1.35
	 *
	 * @param File $file Reference to the File object
	 * @param MediaTransformOutput $thumb
	 * @param string $tmpThumbPath Temporary file system path of the transformed file
	 * @param string $thumbPath Permanent storage path of the transformed file
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onFileTransformed( $file, $thumb, $tmpThumbPath, $thumbPath );
}

/** @deprecated class alias since 1.46 */
class_alias( FileTransformedHook::class, 'MediaWiki\\Hook\\FileTransformedHook' );
