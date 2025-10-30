<?php
/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Config;

/**
 * Interface for configuration instances
 *
 * @stable to implement
 *
 * @since 1.23
 */
interface Config {

	/**
	 * Get a configuration variable such as "Sitename" or "UploadMaintenance."
	 *
	 * @param string $name Name of configuration option
	 * @return mixed Value configured
	 * @throws ConfigException
	 */
	public function get( $name );

	/**
	 * Check whether a configuration option is set for the given name
	 *
	 * @param string $name Name of configuration option
	 * @return bool
	 * @since 1.24
	 */
	public function has( $name );
}

/** @deprecated class alias since 1.41 */
class_alias( Config::class, 'Config' );
