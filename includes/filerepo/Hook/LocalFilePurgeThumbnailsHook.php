<?php

namespace MediaWiki\Hook;

use File;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LocalFilePurgeThumbnails" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalFilePurgeThumbnailsHook {
	/**
	 * This hook is called before thumbnails for a local file are purged.
	 *
	 * @since 1.35
	 *
	 * @param File $file
	 * @param string $archiveName Name of an old file version or false if it's the current one
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalFilePurgeThumbnails( $file, $archiveName );
}
