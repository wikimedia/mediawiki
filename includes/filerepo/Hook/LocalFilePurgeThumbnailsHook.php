<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LocalFilePurgeThumbnailsHook {
	/**
	 * Called before thumbnails for a local file a purged.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $file the File object
	 * @param ?mixed $archiveName name of an old file version or false if it's the current one
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalFilePurgeThumbnails( $file, $archiveName );
}
