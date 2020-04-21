<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BitmapHandlerTransformHook {
	/**
	 * before a file is transformed, gives extension the
	 * possibility to transform it themselves
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $handler BitmapHandler
	 * @param ?mixed $image File
	 * @param ?mixed &$scalerParams Array with scaler parameters
	 * @param ?mixed &$mto null, set to a MediaTransformOutput
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBitmapHandlerTransform( $handler, $image, &$scalerParams,
		&$mto
	);
}
