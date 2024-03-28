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
	 * Recursively applies phpDocToJson() to type declarations in a JSON schema.
	 *
	 * @param array $schema JSON Schema structure with PHPDoc types
	 * @param array &$defs List of definitions (JSON schemas) referenced in the schema
	 * @param string $source An identifier for the source schema being reflected, used
	 * for error descriptions.
	 * @param string $propertyName The name of the property the schema belongs to, used for error descriptions.
	 * @return array JSON Schema structure using only proper JSON types
	 */
	private static function normalizeJsonSchema(
		array $schema,
		array &$defs,
		string $source,
		string $propertyName
	): array {
		if ( isset( $schema['type'] ) ) {
			// Support PHP Doc style types, for convenience.
			$schema['type'] = self::phpDocToJson( $schema['type'] );
		}

		if ( isset( $schema['additionalProperties'] ) && is_array( $schema['additionalProperties'] ) ) {
			$schema['additionalProperties'] =
				self::normalizeJsonSchema( $schema['additionalProperties'], $defs, $source, $propertyName );
		}

		if ( isset( $schema['items'] ) && is_array( $schema['items'] ) ) {
			$schema['items'] = self::normalizeJsonSchema( $schema['items'], $defs, $source, $propertyName );
		}

		if ( isset( $schema['properties'] ) && is_array( $schema['properties'] ) ) {
			foreach ( $schema['properties'] as $name => $propSchema ) {
				$schema['properties'][$name] = self::normalizeJsonSchema( $propSchema, $defs, $source, $propertyName );
			}
		}

		// Definitions need to be collected before normalizing the reference because
		// JsonSchemaReferenceResolver expects the $ref to be an array with:
		// [ "class" => "Some\\Class", "field" => "someField" ]
		JsonSchemaReferenceResolver::getDefinitions( $schema, $defs, $source, $propertyName );

		if ( isset( $schema['$ref'] ) ) {
			$schema['$ref'] = JsonSchemaReferenceResolver::normalizeRef( $schema['$ref'] );
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
