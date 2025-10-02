<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

use LogicException;

/**
 * Exceptions for config failures
 *
 * @newable
 * @since 1.23
 */
class ConfigException extends LogicException {
}

/** @deprecated class alias since 1.41 */
class_alias( ConfigException::class, 'ConfigException' );
