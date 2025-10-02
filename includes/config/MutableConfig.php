<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

/**
 * Interface for mutable configuration instances
 *
 * @stable to implement
 *
 * @since 1.24
 */
interface MutableConfig extends Config {

	/**
	 * Set a configuration variable such a "Sitename" to something like "My Wiki"
	 *
	 * @param string $name Name of configuration option
	 * @param mixed $value Value to set
	 * @throws ConfigException
	 */
	public function set( $name, $value );
}

/** @deprecated class alias since 1.41 */
class_alias( MutableConfig::class, 'MutableConfig' );
