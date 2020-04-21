<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageShowTOCHook {
	/**
	 * Called when the file toc on an image page is generated.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $page ImagePage object
	 * @param ?mixed &$toc Array of <li> strings
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageShowTOC( $page, &$toc );
}
