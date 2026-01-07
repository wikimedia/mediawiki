<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Media;

use Exception;

/**
 * MediaWiki exception thrown by some methods when the transform parameter array is invalid
 *
 * @newable
 * @ingroup Exception
 */
class MediaTransformInvalidParametersException extends Exception {
}

/** @deprecated class alias since 1.46 */
class_alias( MediaTransformInvalidParametersException::class, 'MediaTransformInvalidParametersException' );
