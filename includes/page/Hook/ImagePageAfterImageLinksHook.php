<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImagePageAfterImageLinksHook {
	/**
	 * Called after the image links section on an image
	 * page is built.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $imagePage ImagePage object ($this)
	 * @param ?mixed &$html HTML for the hook to add
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImagePageAfterImageLinks( $imagePage, &$html );
}
