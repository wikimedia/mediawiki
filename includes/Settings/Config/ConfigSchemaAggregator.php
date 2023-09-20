<?php

namespace MediaWiki\Settings\Config;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use MediaWiki\Config\Config;
use MediaWiki\Settings\DynamicDefaultValues;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\JsonSchemaTrait;
use StatusValue;
use function array_key_exists;

/**
 * Aggregates multiple config schemas.
 *
 * Some aspects of the schema are maintained separately, to optimized
 * for settings defaults, types and merge strategies in bulk, and later
 * accessing them independently of each other, for each config key.
 */
class ConfigSchemaAggregator implements ConfigSchema {
	use JsonSchemaTrait;

	/** @var array[] Maps config keys to JSON schema structures */
	private $schemas = [];

	/** @var array Map of config keys to default values, for optimized access */
	private $defaults = [];

	/** @var array Map of config keys to dynamic default declaration ararys, for optimized access */
	private $dynamicDefaults = [];

	/** @var array Map of config keys to types, for optimized access */
	private $types = [];

	/** @var array Map of config keys to merge strategies, for optimized access */
	private $mergeStrategies = [];

	/** @var MergeStrategy[]|null */
	private $mergeStrategyCache;

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
		if ( isset( $schema['properties'] ) ) {
			// Collect the defaults of nested property declarations into the top level default.
			$schema['default'] = self::getDefaultFromJsonSchema( $schema );
		}

		$this->schemas[$key] = $schema;

		$this->setListValueInternal( $schema, $this->defaults, $key, 'default', $sourceName );
		$this->setListValueInternal( $schema, $this->types, $key, 'type', $sourceName );
		$this->setListValueInternal( $schema, $this->mergeStrategies, $key, 'mergeStrategy', $sourceName );
		$this->setListValueInternal( $schema, $this->dynamicDefaults, $key, 'dynamicDefault', $sourceName );

		if ( isset( $schema['mergeStrategy'] ) ) {
			// TODO: mark cache as incomplete rather than throwing it away
			$this->mergeStrategyCache = null;
		}
	}

	/**
	 * Update a map with a specific field.
	 *
	 * @param array $schema
	 * @param array &$target
	 * @param string $key
	 * @param string $fieldName
	 * @param string $sourceName
	 *
	 * @return void
	 * @throws SettingsBuilderException if a conflict is detected
	 *
	 */
	private function setListValueInternal( $schema, &$target, $key, $fieldName, $sourceName ) {
		if ( array_key_exists( $fieldName, $schema ) ) {
			if ( array_key_exists( $key, $target ) ) {
				throw new SettingsBuilderException(
					"Overriding $fieldName in schema for {key} from {source}",
					[
						'source' => $sourceName,
						'key' => $key,
					]
				);
			}
			$target[$key] = $schema[$fieldName];
		}
	}

	/**
	 * Add multiple schema definitions.
	 *
	 * @see addSchema()
	 *
	 * @param array[] $schemas An associative array mapping config variable
	 *        names to their respective schemas.
	 */
	public function addSchemaMulti( array $schemas ) {
		foreach ( $schemas as $key => $sch ) {
			$this->addSchema( $key, $sch );
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

		// TODO: mark cache as incomplete rather than throwing it away
		$this->mergeStrategyCache = null;
	}

	/**
	 * Declare dynamic defaults
	 *
	 * @see DynamicDefaultValues.
	 *
	 * @param array $dynamicDefaults
	 * @param string $sourceName
	 */
	public function addDynamicDefaults( array $dynamicDefaults, string $sourceName = 'unknown' ) {
		$this->mergeListInternal(
			$dynamicDefaults,
			$this->dynamicDefaults,
			'dynamicDefaults',
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
			array_merge(
				$this->schemas,
				$this->defaults,
				$this->types,
				$this->mergeStrategies,
				$this->dynamicDefaults
			)
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

		if ( isset( $this->dynamicDefaults[$key] ) ) {
			$schema['dynamicDefault'] = $this->dynamicDefaults[$key];
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
			|| isset( $this->mergeStrategies[ $key ] )
			|| isset( $this->dynamicDefaults[ $key ] );
	}

	/**
	 * Get all defined default values.
	 *
	 * @return array
	 */
	public function getDefaults(): array {
		return $this->defaults;
	}

	/**
	 * Get all known types.
	 *
	 * @return array<string|array>
	 */
	public function getTypes(): array {
		return $this->types;
	}

	/**
	 * Get the names of all known merge strategies.
	 *
	 * @return array<string>
	 */
	public function getMergeStrategyNames(): array {
		return $this->mergeStrategies;
	}

	/**
	 * Get all dynamic default declarations.
	 * @see DynamicDefaultValues.
	 *
	 * @return array<string,array>
	 */
	public function getDynamicDefaults(): array {
		return $this->dynamicDefaults;
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
	 * If no default value was declared, this returns null.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getDefaultFor( string $key ) {
		return $this->defaults[$key] ?? null;
	}

	/**
	 * Get type for the $key, or null if the type is not known.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getTypeFor( string $key ) {
		return $this->types[$key] ?? null;
	}

	/**
	 * Get a dynamic default declaration for $key.
	 * If no dynamic default is declared, this returns null.
	 *
	 * @param string $key
	 * @return ?array An associative array of the form expected by DynamicDefaultValues.
	 */
	public function getDynamicDefaultDeclarationFor( string $key ): ?array {
		return $this->dynamicDefaults[$key] ?? null;
	}

	/**
	 * Get the merge strategy defined for the $key, or null if none defined.
	 *
	 * @param string $key
	 * @return MergeStrategy|null
	 * @throws SettingsBuilderException if merge strategy name is invalid.
	 */
	public function getMergeStrategyFor( string $key ): ?MergeStrategy {
		if ( $this->mergeStrategyCache === null ) {
			$this->initMergeStrategies();
		}
		return $this->mergeStrategyCache[$key] ?? null;
	}

	/**
	 * Get all merge strategies indexed by config key. If there is no merge
	 * strategy for a given key, the element will be absent.
	 *
	 * @return MergeStrategy[]
	 */
	public function getMergeStrategies() {
		if ( $this->mergeStrategyCache === null ) {
			$this->initMergeStrategies();
		}
		return $this->mergeStrategyCache;
	}

	/**
	 * Initialise $this->mergeStrategyCache
	 */
	private function initMergeStrategies() {
		// XXX: Keep $strategiesByName for later, in case we reset the cache?
		//      Or we could make a bulk version of MergeStrategy::newFromName(),
		//      to make use of the cache there without the overhead of a method
		//      call for each setting.

		$strategiesByName = [];
		$strategiesByKey = [];

		// Explicitly defined merge strategies
		$strategyNamesByKey = $this->mergeStrategies;

		// Loop over settings for which we know a type but not a merge strategy,
		// so we can add a merge strategy for them based on their type.
		$types = array_diff_key( $this->types, $strategyNamesByKey );
		foreach ( $types as $key => $type ) {
			$strategyNamesByKey[$key] = self::getStrategyForType( $type );
		}

		// Assign MergeStrategy objects to settings. Create only one object per strategy name.
		foreach ( $strategyNamesByKey as $key => $strategyName ) {
			if ( !array_key_exists( $strategyName, $strategiesByName ) ) {
				$strategiesByName[$strategyName] = MergeStrategy::newFromName( $strategyName );
			}
			$strategiesByKey[$key] = $strategiesByName[$strategyName];
		}

		$this->mergeStrategyCache = $strategiesByKey;
	}

	/**
	 * Returns an appropriate merge strategy for the given type.
	 *
	 * @param string|array $type
	 *
	 * @return string
	 */
	private static function getStrategyForType( $type ) {
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
