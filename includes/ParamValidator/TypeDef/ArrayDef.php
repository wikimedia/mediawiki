<?php

namespace MediaWiki\ParamValidator\TypeDef;

use InvalidArgumentException;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use LogicException;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for array structures, typically
 * used for validating JSON request bodies.
 *
 * Failure codes:
 *  - 'notarray': The value is not an array.
 *
 * @since 1.42
 */
class ArrayDef extends TypeDef {

	/**
	 * (object) Schema settings.
	 *
	 */
	public const PARAM_SCHEMA = 'param-schema';

	/** @inheritDoc */
	public function supportsArrays() {
		return true;
	}

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			// Message used: paramvalidator-notarray
			$this->failure( 'notarray', $name, $value, $settings, $options );
		}

		if ( isset( $settings[ self::PARAM_SCHEMA ] ) ) {
			$schema = $settings[ self::PARAM_SCHEMA ];

			if ( !isset( $schema[ 'type' ] ) ) {
				throw new InvalidArgumentException( "Schema type not set " );
			}

			$types = (array)$schema['type'];
			foreach ( $types as $type ) {
				// @todo:  start using JsonSchemaTrait::normalizeJsonSchema
				// so we can also support the "list" and "map" types
				if ( ( $type !== 'object' ) && ( $type !== 'array' ) ) {
					throw new LogicException( 'Invalid data type' );
				}
			}

			$validator = new Validator();
			$validator->validate(
				$value, $schema,
				Constraint::CHECK_MODE_TYPE_CAST | Constraint::CHECK_MODE_APPLY_DEFAULTS );
			if ( !$validator->isValid() ) {
				$errorCode = 'schema-validation-failed';
				foreach ( $validator->getErrors() as $error ) {
					$message = DataMessageValue::new(
						"paramvalidator-$errorCode",
						[ $error[ 'message' ] ],
						$errorCode,
						[ 'schema-validation-error' => $error ]
					);
					$this->failure( $message, $name, $value, $settings, $options );
				}
			}
		}
		return $value;
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			return parent::stringifyValue( $name, $value, $settings, $options );
		}

		return json_encode( $value );
	}

	/**
	 * Returns a JSON Schema of type array, with the input schema for each array item.
	 *
	 * If $itemSchema is a string, it must be a valid JSON type, and all list entries will be
	 * validated to be of that type.
	 *
	 * If $itemSchema is an array, it must represent a valid schema, and all list entries will
	 * be validated to be of that schema. Nested lists are supported, as are lists of maps and
	 * more complicated schemas.
	 *
	 * Examples:
	 *  A list of integers, like [ 1, 2, 3 ]
	 *   ArrayDef::makeListSchema( "integer" )
	 *  A list of strings, where each value must be either "a" or "b", like [ "a", "a", "b", "b" ]
	 * *   ArrayDef::makeListSchema( [ 'enum' => [ 'a', 'b' ] ] )
	 *  A list of lists of strings, like [ [ "foo", 'bar" ], [ "baz", "qux" ] ]
	 *   ArrayDef::makeListSchema( ArrayDef::makeListSchema( "string" ) )
	 *
	 * @since 1.43
	 *
	 * @param array|string $itemSchema
	 *
	 * @return array
	 */
	public static function makeListSchema( $itemSchema ): array {
		return [
			'type' => 'array',
			'items' => static::normalizeSchema( $itemSchema )
		];
	}

	/**
	 * Returns a JSON Schema of type object, with the input schema for each array item.
	 *
	 * If $entrySchema is a string, it must be a valid JSON type, and all map entries will be
	 * validated to be of that type.
	 *
	 * If $entrySchema is an array, it must represent a valid schema, and all map entries will
	 * be validated to be of that schema. Nested maps are supported, as are maps of lists and
	 * more complicated schemas
	 *
	 * Examples:
	 *  A map of integers, like [ 'key1' => 1, 'key2' => 2, 'key3' => 3 ]
	 *   ArrayDef::makeMapSchema( "integer" )
	 *  A map of where each value must be 0 or 1, like [ 'key1' => 1, 'key2' => 1, 'key3' => 0 ]
	 *   ArrayDef::makeMapSchema( [ 'enum' => [ 0, 1 ] ] )
	 *  A map of maps, like [ 'k1' => [ 'k2' => 'a' ], 'k3' => [ 'k4' => 'b', 'k5' => 'c' ] ]
	 *   ArrayDef::makeMapSchema( ArrayDef::makeMapSchema( "string" ) )
	 *
	 * @since 1.43
	 *
	 * @param array|string $entrySchema
	 *
	 * @return array
	 */
	public static function makeMapSchema( $entrySchema ): array {
		return [
			'type' => 'object',
			'additionalProperties' => static::normalizeSchema( $entrySchema )
		];
	}

	/**
	 * Returns a JSON Schema of type object, with properties defined by the function params.
	 *
	 * Any input schemas must either be a string corresponding to valid JSON types, or valid
	 * schemas. Nested schemas are supported.
	 *
	 * Examples:
	 *  An object with required parameters "a" and "b", where "a" must be an integer and "b" can
	 *  have one of the values "x", "y", or "z", no optional parameters, and additional parameters
	 *  are disallowed:
	 *   ArrayDef::makeObjectSchema( [ 'a' => 'integer', 'b' => [ 'enum' => [ 'x', 'y', 'z' ] ] ] )
	 *  The same object, but parameter "b" is optional:
	 *   ArrayDef::makeObjectSchema( [ 'a' => 'integer' ], [ 'b' => [ 'enum' => [ 'x', 'y', 'z' ] ] ] )
	 *  An object with no required properties, an optional property "a" of type string, with
	 *  arbitrary additional properties allowed (effectively, an arbitrary object, but if "a"
	 *  is present, it must be a string):
	 *   ArrayDef::makeObjectSchema( [ ], [ 'a' => 'string' ], true )
	 * @since 1.43
	 *
	 * @param array $required properties that are required to be present, as name/schema pairs
	 * @param array $optional properties that may or may not be present, as name/schema pairs
	 * @param array|bool|string $additional schema additional properties must match, or false
	 * 	to disallow additional properties, or true to allow arbitrary additional properties
	 *
	 * @return array
	 */
	public static function makeObjectSchema(
		array $required = [], array $optional = [], $additional = false
	): array {
		$schema = [ 'type' => 'object' ];

		if ( $required ) {
			foreach ( $required as $propertyName => $propertySchema ) {
				$schema['required'][] = $propertyName;
				$schema['properties'][$propertyName] = static::normalizeSchema( $propertySchema );
			}
		}

		if ( $optional ) {
			foreach ( $optional as $propertyName => $propertySchema ) {
				if ( isset( $schema['properties'][$propertyName] ) ) {
					throw new InvalidArgumentException(
						"Property {$propertyName} defined as both required and optional"
					);
				}
				$schema['properties'][$propertyName] = static::normalizeSchema( $propertySchema );
			}
		}

		// The easiest way to allow all extra properties is to not specify additionalProperties
		if ( $additional !== true ) {
			// A value of false disallows additional properties
			if ( $additional === false ) {
				$schema['additionalProperties'] = false;
			} else {
				$schema['additionalProperties'] = static::normalizeSchema( $additional );
			}
		}

		return $schema;
	}

	/**
	 * Returns a representation of the input schema
	 *
	 * If $schema is a string, it must be a valid JSON type.
	 * If $schema is an array, it must represent a valid schema.
	 *
	 * @param array|string $schema
	 *
	 * @return array
	 */
	private static function normalizeSchema( $schema ): array {
		if ( is_array( $schema ) ) {
			return $schema;
		} else {
			return [ 'type' => $schema ];
		}
	}
}
