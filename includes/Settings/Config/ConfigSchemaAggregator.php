<?php

namespace MediaWiki\Settings\Config;

use MediaWiki\Settings\SettingsBuilderException;

/**
 * Aggregates multiple config schema arrays into a single config schema.
 */
class ConfigSchemaAggregator {

	/** @var array */
	private $schema = [];

	/**
	 * Add a config schema to the aggregator.
	 *
	 * @param array $newSchema an associating array with config key as key
	 *   and a schema for the config value as value.
	 * @param string $sourceName
	 */
	public function addSchemas( array $newSchema, string $sourceName = 'unknown' ) {
		$schemaOverrides = array_intersect_key( $this->schema, $newSchema );
		if ( !empty( $schemaOverrides ) ) {
			throw new SettingsBuilderException( 'Overriding config schema in {source}', [
				'source' => $sourceName,
				'override_keys' => implode( ',', array_keys( $schemaOverrides ) ),
			] );
		}
		$this->schema = array_merge( $this->schema, $newSchema );
	}

	/**
	 * Get the schemas for all the defined config keys.
	 *
	 * @return array config-key => schema
	 */
	public function getSchemas(): array {
		return $this->schema;
	}

	/**
	 * Check whether schema for $key is defined.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasSchemaFor( string $key ): bool {
		return array_key_exists( $key, $this->schema );
	}

	/**
	 * Get default values for all the keys.
	 *
	 * @return array
	 */
	public function getDefaults(): array {
		$defaults = [];
		foreach ( $this->schema as $key => $schema ) {
			if ( array_key_exists( 'default', $schema ) ) {
				$defaults[$key] = $schema['default'];
			}
		}
		return $defaults;
	}

	/**
	 * Check if the $key has a default values set in the schema.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasDefaultFor( string $key ): bool {
		return array_key_exists( $key, $this->schema ) &&
			array_key_exists( 'default', $this->schema[$key] );
	}

	/**
	 * Get default value for the $key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getDefaultFor( string $key ) {
		return $this->schema[$key]['default'];
	}

	/**
	 * Get the merge strategy defined for the $key, or null if none defined.
	 *
	 * @param string $key
	 * @return MergeStrategy|null
	 * @throws SettingsBuilderException if merge strategy name is invalid.
	 */
	public function getMergeStrategyFor( string $key ): ?MergeStrategy {
		$strategyName = $this->schema[$key]['mergeStrategy'] ?? null;
		if ( $strategyName === null ) {
			return null;
		}
		return MergeStrategy::newFromName( $strategyName );
	}
}
