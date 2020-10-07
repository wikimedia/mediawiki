<?php

namespace MediaWiki\Page\Hook;

use ImagePage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImagePageAfterImageLinksHook {
	/**
	 * This hook is called after the image links section on an image
	 * page is built.
	 *
	 * @since 1.35
	 *
	 * @param ImagePage $imagePage ImagePage object ($this)
	 * @param string &$html HTML for the hook to add
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageAfterImageLinks( $imagePage, &$html );
}
