<?php

namespace MediaWiki\Hook;

use File;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LocalFilePurgeThumbnailsHook {
	/**
	 * This hook is called before thumbnails for a local file are purged.
	 *
	 * @since 1.35
	 *
	 * @param File $file The File of which the thumbnails are being purged
	 * @param string $archiveName Name of an old file version or false if it's the current one
	 * @param array $urls Urls to be purged
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLocalFilePurgeThumbnails( $file, $archiveName, $urls );
}
