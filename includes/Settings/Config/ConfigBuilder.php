<?php

namespace MediaWiki\Settings\Config;

use Config;
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
	 * @throws SettingsBuilderException if a $mergeStrategy is not provided and the $value is not an array.
	 */
	public function set( string $key, $value, MergeStrategy $mergeStrategy = null ): ConfigBuilder;

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
	 * @throws SettingsBuilderException if a $mergeStrategy is not provided and the $value is not an array.
	 */
	public function setDefault( string $key, $defaultValue, MergeStrategy $mergeStrategy = null ): ConfigBuilder;

	/**
	 * Build the resulting Config object.
	 *
	 * @return Config
	 */
	public function build(): Config;
}
