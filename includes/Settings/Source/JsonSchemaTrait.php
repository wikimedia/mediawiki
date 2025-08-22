<?php

namespace MediaWiki\Settings\Source;

use InvalidArgumentException;

/**
 * Trait for dealing with JSON Schema structures and types.
 *
 * @since 1.39
 */
trait JsonSchemaTrait {

	/**
	 * Converts a JSON Schema type to a PHPDoc type.
	 *
	 * @param string|string[] $jsonSchemaType A JSON Schema type
	 *
	 * @return string A PHPDoc type
	 */
	private static function jsonToPhpDoc( $jsonSchemaType ) {
		static $phpTypes = [
			'array' => 'array',
			'object' => 'array', // could be optional
			'number' => 'float',
			'double' => 'float', // for good measure
			'boolean' => 'bool',
			'integer' => 'int',
		];

		if ( $jsonSchemaType === null ) {
			throw new InvalidArgumentException( 'The type name cannot be null! Use "null" instead.' );
		}

		$nullable = false;
		if ( is_array( $jsonSchemaType ) ) {
			$nullIndex = array_search( 'null', $jsonSchemaType );
			if ( $nullIndex !== false ) {
				$nullable = true;
				unset( $jsonSchemaType[$nullIndex] );
			}

			$jsonSchemaType = array_map( [ self::class, 'jsonToPhpDoc' ], $jsonSchemaType );
			$type = implode( '|', $jsonSchemaType );
		} else {
			$type = $phpTypes[ strtolower( $jsonSchemaType ) ] ?? $jsonSchemaType;
		}

		if ( $nullable ) {
			$type = "?$type";
		}

		return $type;
	}

	/**
	 * @param string|string[] $phpDocType The PHPDoc type
	 *
	 * @return string|string[] A JSON Schema type
	 */
	private static function phpDocToJson( $phpDocType ) {
		static $jsonTypes = [
			'list' => 'array',
			'dict' => 'object',
			'map' => 'object',
			'stdclass' => 'object',
			'int' => 'integer',
			'float' => 'number',
			'bool' => 'boolean',
			'false' => 'boolean',
		];

		if ( $phpDocType === null ) {
			throw new InvalidArgumentException( 'The type name cannot be null! Use "null" instead.' );
		}

		if ( is_array( $phpDocType ) ) {
			$types = $phpDocType;
		} else {
			$types = explode( '|', trim( $phpDocType ) );
		}

		$nullable = false;
		foreach ( $types as $i => $t ) {
			if ( str_starts_with( $t, '?' ) ) {
				$nullable = true;
				$t = substr( $t, 1 );
			}

			$types[$i] = $jsonTypes[ strtolower( $t ) ] ?? $t;
		}

		if ( $nullable ) {
			$types[] = 'null';
		}

		$types = array_unique( $types );

		if ( count( $types ) === 1 ) {
			return reset( $types );
		}

		return $types;
	}

	/**
	 * Applies phpDocToJson() to type declarations in a JSON schema.
	 *
	 * @param array $schema JSON Schema structure with PHPDoc types
	 * @param array &$defs List of definitions (JSON schemas) referenced in the schema
	 * @param class-string $source An identifier for the source schema being reflected, used
	 * for error descriptions.
	 * @param string $propertyName The name of the property the schema belongs to, used for error descriptions.
	 * @return array JSON Schema structure using only proper JSON types
	 */
	private static function normalizeJsonSchema(
		array $schema,
		array &$defs,
		string $source,
		string $propertyName,
		bool $inlineReferences = false
	): array {
		$traversedReferences = [];
		return self::doNormalizeJsonSchema(
			$schema, $defs, $source, $propertyName, $inlineReferences, $traversedReferences
		);
	}

	/**
	 * Recursively applies phpDocToJson() to type declarations in a JSON schema.
	 *
	 * @param array $schema JSON Schema structure with PHPDoc types
	 * @param array &$defs List of definitions (JSON schemas) referenced in the schema
	 * @param class-string $source An identifier for the source schema being reflected, used
	 * for error descriptions.
	 * @param string $propertyName The name of the property the schema belongs to, used for error descriptions.
	 * @param bool $inlineReferences Whether references in the schema should be inlined or not.
	 * @param array $traversedReferences An accumulator for the resolved references within a schema normalization,
	 * used for cycle detection.
	 * @return array JSON Schema structure using only proper JSON types
	 */
	private static function doNormalizeJsonSchema(
		array $schema,
		array &$defs,
		string $source,
		string $propertyName,
		bool $inlineReferences,
		array $traversedReferences
	): array {
		if ( isset( $schema['type'] ) ) {
			// Support PHP Doc style types, for convenience.
			$schema['type'] = self::phpDocToJson( $schema['type'] );
		}

		if ( isset( $schema['additionalProperties'] ) && is_array( $schema['additionalProperties'] ) ) {
			$schema['additionalProperties'] =
				self::doNormalizeJsonSchema(
					$schema['additionalProperties'],
					$defs,
					$source,
					$propertyName,
					$inlineReferences,
					$traversedReferences
				);
		}

		if ( isset( $schema['items'] ) && is_array( $schema['items'] ) ) {
			$schema['items'] = self::doNormalizeJsonSchema(
				$schema['items'],
				$defs,
				$source,
				$propertyName,
				$inlineReferences,
				$traversedReferences
			);
		}

		if ( isset( $schema['properties'] ) && is_array( $schema['properties'] ) ) {
			foreach ( $schema['properties'] as $name => $propSchema ) {
				$schema['properties'][$name] = self::doNormalizeJsonSchema(
					$propSchema,
					$defs,
					$source,
					$propertyName,
					$inlineReferences,
					$traversedReferences
				);
			}
		}

		if ( isset( $schema['$ref'] ) ) {
			$definitionName = JsonSchemaReferenceResolver::getDefinitionName( $schema[ '$ref' ] );
			if ( array_key_exists( $definitionName, $traversedReferences ) ) {
				throw new RefLoopException(
					"Found a loop while resolving reference $definitionName in $propertyName." .
					" Root schema location: $source"
				);
			}
			$def = JsonSchemaReferenceResolver::resolveRef( $schema['$ref'], $source );
			if ( $def ) {
				if ( !isset( $defs[$definitionName] ) ) {
					$traversedReferences[$definitionName] = true;
					$normalizedDefinition = self::doNormalizeJsonSchema(
						$def,
						$defs,
						$source,
						$propertyName,
						$inlineReferences,
						$traversedReferences
					);
					if ( !$inlineReferences ) {
						$defs[$definitionName] = $normalizedDefinition;
					}
				} else {
					$normalizedDefinition = $defs[$definitionName];
				}
				// Normalize reference after resolving it since JsonSchemaReferenceResolver expects
				// the $ref to be an array with: [ "class" => "Some\\Class", "field" => "someField" ]
				if ( $inlineReferences ) {
					$schema = $normalizedDefinition;
				} else {
					$schema['$ref'] = JsonSchemaReferenceResolver::normalizeRef( $schema['$ref'] );
				}
			}
		}

		return $schema;
	}

	/**
	 * Returns the default value from the given schema structure.
	 * If the schema defines properties, the default value of each
	 * property is determined recursively, and the collected into a
	 * the top level default, which in that case will be a map
	 * (that is, a JSON object).
	 *
	 * @param array $schema
	 * @return mixed The default specified by $schema, or null if no default
	 *         is defined.
	 */
	private static function getDefaultFromJsonSchema( array $schema ) {
		$default = $schema['default'] ?? null;

		foreach ( $schema['properties'] ?? [] as $name => $sch ) {
			$def = self::getDefaultFromJsonSchema( $sch );

			$default[$name] = $def;
		}

		return $default;
	}

}
