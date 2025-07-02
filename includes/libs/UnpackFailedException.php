<?php

namespace Wikimedia;

use Exception;

/**
 * @since 1.42
 */
class UnpackFailedException extends Exception {
}

/** @deprecated class alias since 1.45 */
class_alias( UnpackFailedException::class, 'MediaWiki\\Libs\\UnpackFailedException' );
