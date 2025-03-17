<?php

namespace MediaWiki\Block;

use RuntimeException;

/**
 * Exception thrown when multiple blocks exist but the legacy "reblock" conflict
 * mode was requested in BlockUser::placeBlock().
 *
 * @since 1.44
 */
class MultiblocksException extends RuntimeException {
}
