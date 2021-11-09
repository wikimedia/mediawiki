<?php

namespace MediaWiki\Settings\Config;

/**
 * Interface of a mechanism to be used by SettingsBuilder::apply() to apply
 * configuration variables to the runtime environment.
 *
 * @unstable
 */
interface ConfigSink {

	/**
	 * Set the configuration $key to $value.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return ConfigSink
	 */
	public function set( string $key, $value ): ConfigSink;

	/**
	 * Set the configuration $key to $value if it is not already set.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return ConfigSink
	 */
	public function setIfNotDefined( string $key, $value ): ConfigSink;

}
