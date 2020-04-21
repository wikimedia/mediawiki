<?php

namespace MediaWiki\Page\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ImageOpenShowImageInlineBeforeHook {
	/**
	 * Call potential extension just before showing
	 * the image on an image page.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $imagePage ImagePage object ($this)
	 * @param ?mixed $output $wgOut
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onImageOpenShowImageInlineBefore( $imagePage, $output );
}
