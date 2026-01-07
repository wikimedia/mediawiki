<?php
/**
 * Base class for the output of file transformation methods.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

namespace MediaWiki\Media;

/**
 * Shortcut class for parameter validation errors
 *
 * @newable
 * @ingroup Media
 */
class TransformParameterError extends MediaTransformError {

	/**
	 * @stable to call
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( 'thumbnail_error',
			max( $params['width'] ?? 0, 120 ),
			max( $params['height'] ?? 0, 120 ),
			wfMessage( 'thumbnail_invalid_params' )
		);
	}

	/** @inheritDoc */
	public function getHttpStatusCode() {
		return 400;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( TransformParameterError::class, 'TransformParameterError' );
