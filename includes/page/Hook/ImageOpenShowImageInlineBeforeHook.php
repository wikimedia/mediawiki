<?php

namespace MediaWiki\Page\Hook;

use ImagePage;
use OutputPage;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ImageOpenShowImageInlineBeforeHook {
	/**
	 * This hook is called just before showing the image on an image page.
	 *
	 * @since 1.35
	 *
	 * @param ImagePage $imagePage ImagePage object ($this)
	 * @param OutputPage $output $wgOut
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImageOpenShowImageInlineBefore( $imagePage, $output );
}
