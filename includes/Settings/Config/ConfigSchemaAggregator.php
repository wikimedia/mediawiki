<?php

namespace MediaWiki\Settings\Config;

use Config;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use MediaWiki\Settings\SettingsBuilderException;
use StatusValue;

/**
 * Aggregates multiple config schema arrays into a single config schema.
 */
class ConfigSchemaAggregator {

	/** @var array */
	private $schema = [];

	/** @var Validator */
	private $validator;

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
			$type = $this->schema[ $key ]['type'] ?? null;
			$strategyName = $type ? $this->getStrategyForType( $type ) : null;
		}

		return $strategyName ? MergeStrategy::newFromName( $strategyName ) : null;
	}

	/**
	 * Returns an appropriate merge strategy for the given type.
	 *
	 * @param string|array $type
	 *
	 * @return string
	 */
	private function getStrategyForType( $type ): string {
		if ( is_array( $type ) ) {
			if ( in_array( 'array', $type ) ) {
				$type = 'array';
			} elseif ( in_array( 'object', $type ) ) {
				$type = 'object';
			}
		}

		if ( $type === 'array' ) {
			// In JSON Schema, "array" means a list.
			// Use array_merge to append.
			return 'array_merge';
		} elseif ( $type === 'object' ) {
			// In JSON Schema, "object" means a map.
			// Use array_plus to replace keys, even if they are numeric.
			return 'array_plus';
		}

		return 'replace';
	}

	/**
	 * Check if the given config conforms to the schema.
	 * Note that all keys for which a schema was defined are required to be present in $config.
	 *
	 * @param Config $config
	 *
	 * @return StatusValue
	 */
	public function validateConfig( Config $config ): StatusValue {
		$result = StatusValue::newGood();

		foreach ( $this->getSchemas() as $key => $schema ) {
			// All config keys present in the schema must be set.
			if ( !$config->has( $key ) ) {
				$result->fatal( 'config-missing-key', $key );
				continue;
			}

			$value = $config->get( $key );
			$result->merge( $this->validateValue( $key, $value ) );
		}
		return $result;
	}

	/**
	 * Check if the given value conforms to the relevant schema.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return StatusValue
	 */
	public function validateValue( string $key, $value ): StatusValue {
		$status = StatusValue::newGood();
		$schema = $this->schema[$key] ?? null;

		if ( !$schema ) {
			return $status;
		}

		if ( !$this->validator ) {
			$this->validator = new Validator();
		}

		$types = isset( $schema['type'] ) ? (array)$schema['type'] : [];

		if ( in_array( 'object', $types ) && is_array( $value ) ) {
			if ( $this->hasNumericKeys( $value ) ) {
				// JSON Schema validation doesn't like numeric keys in objects,
				// but we need this quite a bit. Skip type validation in this case.
				$status->warning(
					'config-invalid-key',
					$key,
					'Skipping validation of object with integer keys'
				);
				unset( $schema['type'] );
			}
		}

		if ( in_array( 'integer', $types ) && is_float( $value ) ) {
			// The validator complains about float values when an integer is expected,
			// even when the fractional part is 0. So cast to integer to avoid spurious errors.
			$intval = intval( $value );
			if ( $intval == $value ) {
				$value = $intval;
			}
		}

		if ( isset( $schema['ignoreKeys'] ) && $schema['ignoreKeys'] ) {
			if ( is_array( $value ) ) {
				// This array acts as a set, any array keys should be ignored.
				$value = array_values( $value );
			}
		}

		$this->validator->validate(
			$value,
			$schema,
			Constraint::CHECK_MODE_TYPE_CAST
		);
		if ( !$this->validator->isValid() ) {
			foreach ( $this->validator->getErrors() as $error ) {
				$status->fatal( 'config-invalid-key', $key, $error['message'], var_export( $value, true ) );
			}
		}
		$this->validator->reset();
		return $status;
	}

	/**
	 * @param array $value
	 *
	 * @return bool
	 */
	private function hasNumericKeys( array $value ) {
		foreach ( $value as $key => $dummy ) {
			if ( is_int( $key ) ) {
				return true;
			}
		}

		return false;
	}

}
