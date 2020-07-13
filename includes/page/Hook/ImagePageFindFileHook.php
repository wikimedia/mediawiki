<?php

namespace MediaWiki\Page\Hook;

use File;
use ImagePage;

/**
 * @stable to implement
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
	 * @param File|false &$file False on input, can be replaced with a File
	 * @param File|false &$displayFile False on input, can be replaced with a
	 *   file to display.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageFindFile( $page, &$file, &$displayFile );
}
