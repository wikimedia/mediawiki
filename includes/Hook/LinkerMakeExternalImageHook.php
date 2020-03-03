<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface LinkerMakeExternalImageHook {
	/**
	 * At the end of Linker::makeExternalImage() just
	 * before the return.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$url the image url
	 * @param ?mixed &$alt the image's alt text
	 * @param ?mixed &$img the new image HTML (if returning false)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onLinkerMakeExternalImage( &$url, &$alt, &$img );
}
