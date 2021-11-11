<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;

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
	 * @param MergeStrategy|null $mergeStrategy strategy for merging array config values.
	 * @return ConfigSink
	 * @throws SettingsBuilderException if a $mergeStrategy is not provided and the $value is not an array.
	 */
	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigSink;

	/**
	 * Set the default for the configuration $key to $defaultValue.
	 *
	 * If the $key is already set, non-array $defaultValue will be ignored,
	 * for array $defaultValue the existing value will be merged into it as
	 * if the default was already there when the existing value was set.
	 *
	 * @param string $key
	 * @param mixed $defaultValue
	 * @param MergeStrategy|null $mergeStrategy strategy for merging array config values.
	 * @return ConfigSink
	 * @throws SettingsBuilderException if a $mergeStrategy is not provided and the $value is not an array.
	 */
	public function setDefault( string $key, $defaultValue, MergeStrategy $mergeStrategy = null ): ConfigSink;

}
