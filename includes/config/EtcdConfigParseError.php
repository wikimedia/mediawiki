<?php

namespace MediaWiki\Config;

use Exception;

/**
 * @newable
 */
class EtcdConfigParseError extends Exception {
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( EtcdConfigParseError::class, 'EtcdConfigParseError' );
