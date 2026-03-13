<?php

namespace MediaWiki\Linker\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "LinkerMakeExternalImage" to register handlers implementing this interface.
 *
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

/** @deprecated class alias since 1.46 */
class_alias( LinkerMakeExternalImageHook::class, 'MediaWiki\\Hook\\LinkerMakeExternalImageHook' );
