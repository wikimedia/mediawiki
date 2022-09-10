<?php
namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;

/**
 * Represents a config schema.
 *
 * @since 1.39
 */
interface ConfigSchema {

	/**
	 * Get a list of all defined keys
	 *
	 * @return string[]
	 */
	public function getDefinedKeys(): array;

	/**
	 * Check whether schema for $key is defined.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function hasSchemaFor( string $key ): bool;

	/**
	 * Get all defined default values.
	 *
	 * @return array<string,mixed> An associative array mapping setting names
	 *         to their respective default values.
	 */
	public function getDefaults(): array;

	/**
	 * Get all dynamic default declarations.
	 * @see DynamicDefaultValues.
	 *
	 * @return array<string,array>
	 */
	public function getDynamicDefaults(): array;

	/**
	 * Check if the $key has a default value set in the schema.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function hasDefaultFor( string $key ): bool;

	/**
	 * Get the default value for the $key.
	 * For keys that do not define a default, null is assumed.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getDefaultFor( string $key );

	/**
	 * Get the merge strategy defined for the $key, or null if none defined.
	 *
	 * @param string $key
	 *
	 * @return MergeStrategy|null
	 * @throws SettingsBuilderException if merge strategy name is invalid.
	 */
	public function getMergeStrategyFor( string $key ): ?MergeStrategy;
}
