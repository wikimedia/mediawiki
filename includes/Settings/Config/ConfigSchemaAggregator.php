<?php

namespace MediaWiki\Settings\Config;

use Config;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use MediaWiki\Settings\SettingsBuilderException;
use StatusValue;

/**
 * Aggregates multiple config schemas.
 *
 * Some aspects of the schema are maintained separately, to optimized
 * for settings defaults, types and merge strategies in bulk, and later
 * accessing them independently of each other, for each config key.
 */
class ConfigSchemaAggregator {

	/** @var array[] Maps config keys to JSON schema structures */
	private $schemas = [];

	/** @var array Map of config keys to default values, for optimized access */
	private $defaults = [];

	/** @var array Map of config keys to types, for optimized access */
	private $types = [];

	/** @var array Map of config keys to merge strategies, for optimized access */
	private $mergeStrategies = [];

	/** @var Validator */
	private $validator;

	/**
	 * Add a config schema to the aggregator.
	 *
	 * @param string $key
	 * @param array $schema
	 * @param string $sourceName
	 */
	public function addSchema( string $key, array $schema, string $sourceName = 'unknown' ) {
		if ( $this->hasSchemaFor( $key ) ) {
			throw new SettingsBuilderException( 'Overriding config schema for {key} from {source}', [
				'source' => $sourceName,
				'key' => $key,
			] );
		}

		$this->schemas[$key] = $schema;

		if ( array_key_exists( 'default', $schema ) ) {
			$this->defaults[$key] = $schema['default'];
		}

		if ( isset( $schema['type'] ) ) {
			$this->types[$key] = $schema['type'];
		}

		if ( isset( $schema['mergeStrategy'] ) ) {
			$this->mergeStrategies[$key] = $schema['mergeStrategy'];
		}
	}

	/**
	 * Update a map with the given values.
	 *
	 * @param array $values
	 * @param array &$target
	 * @param string $fieldName
	 * @param string $sourceName
	 *
	 * @throws SettingsBuilderException if a conflict is detected
	 *
	 * @return void
	 */
	private function mergeListInternal( $values, &$target, $fieldName, $sourceName ) {
		$merged = array_merge( $target, $values );
		if ( count( $merged ) < ( count( $target ) + count( $values ) ) ) {
			throw new SettingsBuilderException( 'Overriding config {field} from {source}', [
				'field' => $fieldName,
				'source' => $sourceName,
				'old_values' => implode( ', ', array_intersect_key( $target, $values ) ),
				'new_values' => implode( ', ', array_intersect_key( $values, $target ) ),
			] );
		}

		$target = $merged;
	}

	/**
	 * Declare default values
	 *
	 * @param array $defaults
	 * @param string $sourceName
	 */
	public function addDefaults( array $defaults, string $sourceName = 'unknown' ) {
		$this->mergeListInternal( $defaults, $this->defaults, 'defaults', $sourceName );
	}

	/**
	 * Declare types
	 *
	 * @param array $types
	 * @param string $sourceName
	 */
	public function addTypes( array $types, string $sourceName = 'unknown' ) {
		$this->mergeListInternal( $types, $this->types, 'types', $sourceName );
	}

	/**
	 * Declare merge strategies
	 *
	 * @param array $mergeStrategies
	 * @param string $sourceName
	 */
	public function addMergeStrategies( array $mergeStrategies, string $sourceName = 'unknown' ) {
		$this->mergeListInternal(
			$mergeStrategies,
			$this->mergeStrategies,
			'mergeStrategies',
			$sourceName
		);
	}

	/**
	 * Get a list of all defined keys
	 *
	 * @return string[]
	 */
	public function getDefinedKeys(): array {
		return array_keys(
			array_merge( $this->schemas, $this->defaults, $this->types, $this->mergeStrategies )
		);
	}

	/**
	 * Get the schema for the given key
	 *
	 * @param string $key
	 *
	 * @return array
	 */
	public function getSchemaFor( string $key ): array {
		$schema = $this->schemas[$key] ?? [];

		if ( isset( $this->defaults[$key] ) ) {
			$schema['default'] = $this->defaults[$key];
		}

		if ( isset( $this->types[$key] ) ) {
			$schema['type'] = $this->types[$key];
		}

		if ( isset( $this->mergeStrategies[$key] ) ) {
			$schema['mergeStrategy'] = $this->mergeStrategies[$key];
		}

		return $schema;
	}

	/**
	 * Check whether schema for $key is defined.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasSchemaFor( string $key ): bool {
		return isset( $this->schemas[ $key ] )
			|| array_key_exists( $key, $this->defaults )
			|| isset( $this->types[ $key ] )
			|| isset( $this->mergeStrategies[ $key ] );
	}

	/**
	 * Get all known default values.
	 *
	 * @return array
	 */
	public function getDefaults(): array {
		return $this->defaults;
	}

	/**
	 * Get all known types.
	 *
	 * @return array
	 */
	public function getTypes(): array {
		return $this->types;
	}

	/**
	 * Get all known merge strategies.
	 *
	 * @return array
	 */
	public function getMergeStrategies(): array {
		return $this->mergeStrategies;
	}

	/**
	 * Check if the $key has a default values set in the schema.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function hasDefaultFor( string $key ): bool {
		return array_key_exists( $key, $this->defaults );
	}

	/**
	 * Get default value for the $key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getDefaultFor( string $key ) {
		return $this->defaults[$key] ?? null;
	}

	/**
	 * Get the merge strategy defined for the $key, or null if none defined.
	 *
	 * @param string $key
	 * @return MergeStrategy|null
	 * @throws SettingsBuilderException if merge strategy name is invalid.
	 */
	public function getMergeStrategyFor( string $key ): ?MergeStrategy {
		$strategyName = $this->mergeStrategies[$key] ?? null;

		if ( $strategyName === null ) {
			$type = $this->types[ $key ] ?? null;
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

		foreach ( $this->getDefinedKeys() as $key ) {
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
		$schema = $this->getSchemaFor( $key );

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
