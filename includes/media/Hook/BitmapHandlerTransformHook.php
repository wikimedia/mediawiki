<?php

namespace MediaWiki\Hook;

use MediaTransformOutput;
use MediaWiki\FileRepo\File\File;
use TransformationalImageHandler;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "BitmapHandlerTransform" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface BitmapHandlerTransformHook {
	/**
	 * This hook is called before a file is transformed, giving extensions the
	 * possibility to transform it themselves.
	 *
	 * @since 1.35
	 *
	 * @param TransformationalImageHandler $handler
	 * @param File $image
	 * @param array &$scalerParams Array with scaler parameters
	 * @param null|MediaTransformOutput &$mto
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBitmapHandlerTransform( $handler, $image, &$scalerParams,
		&$mto
	);
}
