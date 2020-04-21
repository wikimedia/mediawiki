<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageFindFileHook {
	/**
	 * Called when fetching the file associated with an image
	 * page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page ImagePage object
	 * @param ?mixed &$file File object
	 * @param ?mixed &$displayFile displayed File object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFindFile( $page, &$file, &$displayFile );
}
