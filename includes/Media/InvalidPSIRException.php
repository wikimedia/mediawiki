<?php

namespace MediaWiki\Media;

use Exception;

class InvalidPSIRException extends Exception {
}

/** @deprecated class alias since 1.46 */
class_alias( InvalidPSIRException::class, 'InvalidPSIRException' );
