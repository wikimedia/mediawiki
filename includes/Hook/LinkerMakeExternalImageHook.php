<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface LinkerMakeExternalImageHook {
	/**
	 * This hook is called at the end of Linker::makeExternalImage() just before the return.
	 *
	 * @since 1.35
	 *
	 * @param string &$url Image URL
	 * @param string &$alt Image's alt text
	 * @param string &$img New image HTML (if returning false)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerMakeExternalImage( &$url, &$alt, &$img );
}
