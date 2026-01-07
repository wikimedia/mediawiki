<?php

namespace MediaWiki\Media;

use Exception;

class InvalidJpegException extends Exception {
}

/** @deprecated class alias since 1.46 */
class_alias( InvalidJpegException::class, 'InvalidJpegException' );
