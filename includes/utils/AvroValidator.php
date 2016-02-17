<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Generate error strings for data that doesn't match the specified
 * Avro schema. This is very similar to AvroSchema::is_valid_datum(),
 * but returns error messages instead of a boolean.
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class AvroValidator {
	/**
	 * @param AvroSchema $schema The rules to conform to.
	 * @param mixed $datum The value to validate against $schema.
	 * @return string|string[] An error or list of errors in the
	 *  provided $datum. When no errors exist the empty array is
	 *  returned.
	 */
	public static function getErrors( AvroSchema $schema, $datum ) {
		switch ( $schema->type ) {
		case AvroSchema::NULL_TYPE:
			if ( !is_null( $datum ) ) {
				return self::wrongType( 'null', $datum );
			}
			return [];
		case AvroSchema::BOOLEAN_TYPE:
			if ( !is_bool( $datum ) ) {
				return self::wrongType( 'boolean', $datum );
			}
			return [];
		case AvroSchema::STRING_TYPE:
		case AvroSchema::BYTES_TYPE:
			if ( !is_string( $datum ) ) {
				return self::wrongType( 'string', $datum );
			}
			return [];
		case AvroSchema::INT_TYPE:
			if ( !is_int( $datum ) ) {
				return self::wrongType( 'integer', $datum );
			}
			if ( AvroSchema::INT_MIN_VALUE > $datum
				|| $datum > AvroSchema::INT_MAX_VALUE
			) {
				return self::outOfRange(
					AvroSchema::INT_MIN_VALUE,
					AvroSchema::INT_MAX_VALUE,
					$datum
				);
			}
			return [];
		case AvroSchema::LONG_TYPE:
			if ( !is_int( $datum ) ) {
				return self::wrongType( 'integer', $datum );
			}
			if ( AvroSchema::LONG_MIN_VALUE > $datum
				|| $datum > AvroSchema::LONG_MAX_VALUE
			) {
				return self::outOfRange(
					AvroSchema::LONG_MIN_VALUE,
					AvroSchema::LONG_MAX_VALUE,
					$datum
				);
			}
			return [];
		case AvroSchema::FLOAT_TYPE:
		case AvroSchema::DOUBLE_TYPE:
			if ( !is_float( $datum ) && !is_int( $datum ) ) {
				return self::wrongType( 'float or integer', $datum );
			}
			return [];
		case AvroSchema::ARRAY_SCHEMA:
			if ( !is_array( $datum ) ) {
				return self::wrongType( 'array', $datum );
			}
			$errors = [];
			foreach ( $datum as $d ) {
				$result = self::getErrors( $schema->items(), $d );
				if ( $result ) {
					$errors[] = $result;
				}
			}
			return $errors;
		case AvroSchema::MAP_SCHEMA:
			if ( !is_array( $datum ) ) {
				return self::wrongType( 'array', $datum );
			}
			$errors = [];
			foreach ( $datum as $k => $v ) {
				if ( !is_string( $k ) ) {
					$errors[] = self::wrongType( 'string key', $k );
				}
				$result = self::getErrors( $schema->values(), $v );
				if ( $result ) {
					$errors[$k] = $result;
				}
			}
			return $errors;
		case AvroSchema::UNION_SCHEMA:
			$errors = [];
			foreach ( $schema->schemas() as $schema ) {
				$result = self::getErrors( $schema, $datum );
				if ( !$result ) {
					return [];
				}
				$errors[] = $result;
			}
			if ( $errors ) {
				return [ "Expected any one of these to be true", $errors ];
			}
			return "No schemas provided to union";
		case AvroSchema::ENUM_SCHEMA:
			if ( !in_array( $datum, $schema->symbols() ) ) {
				$symbols = implode( ', ', $schema->symbols );
				return "Expected one of $symbols but recieved $datum";
			}
			return [];
		case AvroSchema::FIXED_SCHEMA:
			if ( !is_string( $datum ) ) {
				return self::wrongType( 'string', $datum );
			}
			$len = strlen( $datum );
			if ( $len !== $schema->size() ) {
				return "Expected string of length {$schema->size()}, "
					. "but recieved one of length $len";
			}
			return [];
		case AvroSchema::RECORD_SCHEMA:
		case AvroSchema::ERROR_SCHEMA:
		case AvroSchema::REQUEST_SCHEMA:
			if ( !is_array( $datum ) ) {
				return self::wrongType( 'array', $datum );
			}
			$errors = [];
			foreach ( $schema->fields() as $field ) {
				$name = $field->name();
				if ( !array_key_exists( $name, $datum ) ) {
					$errors[$name] = 'Missing expected field';
					continue;
				}
				$result = self::getErrors( $field->type(), $datum[$name] );
				if ( $result ) {
					$errors[$name] = $result;
				}
			}
			return $errors;
		default:
			return "Unknown avro schema type: {$schema->type}";
		}
	}

	public static function typeOf( $datum ) {
		return is_object( $datum ) ? get_class( $datum ) : gettype( $datum );
	}

	public static function wrongType( $expected, $datum ) {
		return "Expected $expected, but recieved " . self::typeOf( $datum );
	}

	public static function outOfRange( $min, $max, $datum ) {
		return "Expected value between $min and $max, but recieved $datum";
	}
}
