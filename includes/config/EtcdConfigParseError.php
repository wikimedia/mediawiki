<?php

namespace MediaWiki\Config;

use Exception;

/**
 * @newable
 */
class EtcdConfigParseError extends Exception {
}

/** @deprecated class alias since 1.41 */
class_alias( EtcdConfigParseError::class, 'EtcdConfigParseError' );
