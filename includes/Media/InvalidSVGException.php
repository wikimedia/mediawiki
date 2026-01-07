<?php

namespace MediaWiki\Media;

use Exception;

class InvalidSVGException extends Exception {
}

/** @deprecated class alias since 1.46 */
class_alias( InvalidSVGException::class, 'InvalidSVGException' );
