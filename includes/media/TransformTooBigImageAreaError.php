<?php
/**
 * Base class for the output of file transformation methods.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

/**
 * Shortcut class for parameter file size errors
 *
 * @newable
 * @ingroup Media
 * @since 1.25
 */
class TransformTooBigImageAreaError extends MediaTransformError {

	/**
	 * @stable to call
	 *
	 * @param array $params
	 * @param int $maxImageArea
	 */
	public function __construct( $params, $maxImageArea ) {
		$msg = wfMessage( 'thumbnail_toobigimagearea' );
		$msg->params(
			// messages used: size-pixel, size-kilopixel, size-megapixel, size-gigapixel, size-terapixel,
			// size-petapixel, size-exapixel, size-zettapixel, size-yottapixel, size-ronnapixel, size-quettapixel
			$msg->getLanguage()->formatComputingNumbers( $maxImageArea, 1000, "size-$1pixel" )
		);

		parent::__construct( 'thumbnail_error',
			max( $params['width'] ?? 0, 120 ),
			max( $params['height'] ?? 0, 120 ),
			$msg
		);
	}

	/** @inheritDoc */
	public function getHttpStatusCode() {
		return 400;
	}
}
