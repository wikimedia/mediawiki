<?php

namespace MediaWiki\Media;

use Wikimedia\NormalizedException\NormalizedException;

class InvalidTiffException extends NormalizedException {
}

/** @deprecated class alias since 1.46 */
class_alias( InvalidTiffException::class, 'InvalidTiffException' );
