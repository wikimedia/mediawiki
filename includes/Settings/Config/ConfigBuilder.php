<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Config\Config;
use MediaWiki\Settings\SettingsBuilderException;

/**
 * Builder for Config objects.
 *
 * @unstable
 */
interface ConfigBuilder {

	/**
	 * Set the configuration $key to $value.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param MergeStrategy|null $mergeStrategy strategy for merging array config values.
	 * @return ConfigBuilder
	 */
	public function set( string $key, $value, ?MergeStrategy $mergeStrategy = null ): ConfigBuilder;

	/**
	 * Set all values in the array.
	 *
	 * @param array $values
	 * @param MergeStrategy[] $mergeStrategies The merge strategies indexed by config key
	 * @return ConfigBuilder
	 */
	public function setMulti( array $values, array $mergeStrategies = [] ): ConfigBuilder;

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
	 * @return ConfigBuilder
	 */
	public function setDefault( string $key, $defaultValue, ?MergeStrategy $mergeStrategy = null ): ConfigBuilder;

	/**
	 * Set defaults in a batch.
	 *
	 * @param array $defaults The default values
	 * @param MergeStrategy[] $mergeStrategies The merge strategies indexed by config key
	 * @return ConfigBuilder
	 * @throws SettingsBuilderException if a merge strategy is not provided and
	 *   the value is not an array.
	 */
	public function setMultiDefault( array $defaults, array $mergeStrategies ): ConfigBuilder;

	/**
	 * Get the current value for $key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get( string $key );

	/**
	 * Build the resulting Config object.
	 *
	 * @return Config
	 */
	public function build(): Config;
}
