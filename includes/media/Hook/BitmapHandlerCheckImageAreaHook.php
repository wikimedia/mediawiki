<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BitmapHandlerCheckImageAreaHook {
	/**
	 * By BitmapHandler::normaliseParams, after all
	 * normalizations have been performed, except for the $wgMaxImageArea check.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $image File
	 * @param ?mixed &$params Array of parameters
	 * @param ?mixed &$checkImageAreaHookResult null, set to true or false to override the
	 *   $wgMaxImageArea check result.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBitmapHandlerCheckImageArea( $image, &$params,
		&$checkImageAreaHookResult
	);
}
