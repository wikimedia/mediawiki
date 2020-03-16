<?php

namespace MediaWiki\Page\Hook;

use File;
use ImagePage;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageFindFileHook {
	/**
	 * This hook is called when fetching the file associated with an image
	 * page.
	 *
	 * @since 1.35
	 *
	 * @param ImagePage $page
	 * @param File &$file
	 * @param File &$displayFile Displayed file
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFindFile( $page, &$file, &$displayFile );
}
