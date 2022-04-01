<?php

namespace MediaWiki\Settings\Source;

use InvalidArgumentException;

/**
 * Helper class to map between JSON types and PHP types.
 *
 * @since 1.39
 */
class JsonTypeHelper {

	/**
	 * Map to PHPDoc types
	 */
	private const NORMALIZE_PHP_TYPES = [
		'array' => 'array',
		'object' => 'array', // could be optional
		'number' => 'float',
		'double' => 'float', // for good measure
		'boolean' => 'bool',
		'integer' => 'int',
	];

	/**
	 * Map to JSON Schema types
	 */
	private const NORMALIZE_JSON_TYPES = [
		'list' => 'array',
		'dict' => 'object',
		'map' => 'object',
		'stdclass' => 'object',
		'int' => 'integer',
		'float' => 'number',
		'bool' => 'boolean',
		'false' => 'boolean',
	];

	/**
	 * Converts a JSON Schema type to a PHPDoc type.
	 *
	 * @param string|string[] $jsonSchemaType A JSON Schema type
	 *
	 * @return string A PHPDoc type
	 */
	public function jsonToPhpDoc( $jsonSchemaType ) {
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

			$jsonSchemaType = array_map( [ $this, 'jsonToPhpDoc' ], $jsonSchemaType );
			$type = implode( '|', $jsonSchemaType );
		} else {
			$type = self::NORMALIZE_PHP_TYPES[ strtolower( $jsonSchemaType ) ] ?? $jsonSchemaType;
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
	public function phpDocToJson( $phpDocType ) {
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

			$types[$i] = self::NORMALIZE_JSON_TYPES[ strtolower( $t ) ] ?? $t;
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
	 *
	 * @return array JSON Schema structure using only proper JSON types
	 */
	public function normalizeJsonSchema( array $schema ): array {
		if ( isset( $schema['type'] ) ) {
			// Support PHP Doc style types, for convenience.
			$schema['type'] = $this->phpDocToJson( $schema['type'] );
		}

		if ( isset( $schema['additionalProperties'] ) && is_array( $schema['additionalProperties'] ) ) {
			$schema['additionalProperties'] =
				$this->normalizeJsonSchema( $schema['additionalProperties'] );
		}

		if ( isset( $schema['items'] ) && is_array( $schema['items'] ) ) {
			$schema['items'] = $this->normalizeJsonSchema( $schema['items'] );
		}

		return $schema;
	}
}
