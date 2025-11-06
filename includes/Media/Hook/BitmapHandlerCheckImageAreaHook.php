<?php

namespace MediaWiki\Hook;

use MediaWiki\FileRepo\File\File;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BitmapHandlerCheckImageArea" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BitmapHandlerCheckImageAreaHook {
	/**
	 * This hook is called by BitmapHandler::normaliseParams, after all
	 * normalizations have been performed, except for the $wgMaxImageArea check.
	 *
	 * @since 1.35
	 *
	 * @param File $image
	 * @param array &$params Array of parameters
	 * @param bool|null &$checkImageAreaHookResult Set to true or false to override the
	 *   $wgMaxImageArea check result
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBitmapHandlerCheckImageArea( $image, &$params,
		&$checkImageAreaHookResult
	);
}
