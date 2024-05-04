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
 * @ingroup Json
 */

namespace MediaWiki\Json;

use JsonException;
use JsonSerializable;
use MediaWiki\Parser\ParserOutput;
use stdClass;
use Wikimedia\Assert\Assert;

/**
 * Helper class to serialize/deserialize things to/from JSON.
 *
 * @stable to type
 * @since 1.36
 * @package MediaWiki\Json
 */
class JsonCodec implements JsonDeserializer, JsonSerializer {

	/** @deprecated since 1.43; use ::deserialize() */
	public function unserialize( $json, ?string $expectedClass = null ) {
		return $this->deserialize( $json, $expectedClass );
	}

	public function deserialize( $json, string $expectedClass = null ) {
		Assert::parameterType( [ 'stdClass', 'array', 'string' ], $json, '$json' );
		Assert::precondition(
			!$expectedClass || is_subclass_of( $expectedClass, JsonDeserializable::class ),
			'$expectedClass parameter must be subclass of JsonDeserializable, got ' . $expectedClass
		);
		if ( is_string( $json ) ) {
			$jsonStatus = FormatJson::parse( $json, FormatJson::FORCE_ASSOC );
			if ( !$jsonStatus->isGood() ) {
				throw new JsonException( "Bad JSON: {$jsonStatus}" );
			}
			$json = $jsonStatus->getValue();
		}

		if ( $json instanceof stdClass ) {
			$json = (array)$json;
		}

		if ( $this->containsComplexValue( $json ) ) {
			// Recursively deserialize the array values before deserializing
			// the array itself.
			$json = $this->deserializeArray( $json );
		}

		if ( !$this->canMakeNewFromValue( $json ) ) {
			if ( $expectedClass ) {
				throw new JsonException( 'JSON did not have ' . JsonConstants::TYPE_ANNOTATION );
			}
			return $json;
		}

		$class = $json[JsonConstants::TYPE_ANNOTATION];
		if ( $class == "ParserOutput" || $class == "MediaWiki\\Parser\\ParserOutput" ) {
			$class = ParserOutput::class; // T353835
		} elseif ( $class !== stdClass::class &&
			!( class_exists( $class ) && is_subclass_of( $class, JsonDeserializable::class ) )
		) {
			throw new JsonException( "Invalid target class {$class}" );
		}

		if ( $expectedClass && $class !== $expectedClass && !is_subclass_of( $class, $expectedClass ) ) {
			throw new JsonException(
				"Refusing to deserialize: expected $expectedClass, got $class"
			);
		}

		unset( $json[JsonConstants::TYPE_ANNOTATION] );
		if ( $class === stdClass::class ) {
			return (object)$json;
		}
		return $class::newFromJsonArray( $this, $json );
	}

	/** @deprecated since 1.43; use ::deserializeArray() */
	public function unserializeArray( array $array ): array {
		return $this->deserializeArray( $array );
	}

	public function deserializeArray( array $array ): array {
		$deserializedExtensionData = [];
		foreach ( $array as $key => $value ) {
			if ( $key === JsonConstants::COMPLEX_ANNOTATION ) {
				/* don't include this in the result */
			} elseif (
				$this->canMakeNewFromValue( $value ) ||
				$this->containsComplexValue( $value )
			) {
				$deserializedExtensionData[$key] = $this->deserialize( $value );
			} else {
				$deserializedExtensionData[$key] = $value;
			}
		}
		return $deserializedExtensionData;
	}

	private function serializeOne( &$value ) {
		if ( $value instanceof JsonSerializable ) {
			$value = $value->jsonSerialize();
			if ( !is_array( $value ) ) {
				// Although JsonSerializable doesn't /require/ the result to be
				// an array, JsonCodec and JsonDeserializableTrait do.
				throw new JsonException( "jsonSerialize didn't return array" );
			}
			$value[JsonConstants::COMPLEX_ANNOTATION] = true;
			// The returned array may still have instance of JsonSerializable,
			// stdClass, or array, so fall through to recursively handle these.
		} elseif ( is_object( $value ) && get_class( $value ) === stdClass::class ) {
			// T312589: if $value is stdObject, mark the type
			// so we deserialize as stdObject as well.
			$value = (array)$value;
			$value[JsonConstants::TYPE_ANNOTATION] = stdClass::class;
			$value[JsonConstants::COMPLEX_ANNOTATION] = true;
			// Fall through to handle the property values
		}
		if ( is_array( $value ) ) {
			$is_complex = false;
			// Recursively convert array values to serializable form
			foreach ( $value as &$v ) {
				if ( is_object( $v ) || is_array( $v ) ) {
					$v = $this->serializeOne( $v );
					if ( isset( $v[JsonConstants::COMPLEX_ANNOTATION] ) ) {
						$is_complex = true;
					}
				}
			}
			if ( $is_complex ) {
				$value[JsonConstants::COMPLEX_ANNOTATION] = true;
			}
		} elseif ( !is_scalar( $value ) && $value !== null ) {
			$details = is_object( $value ) ? get_class( $value ) : gettype( $value );
			throw new JsonException(
				'Unable to serialize JSON: ' . $details
			);
		}
		return $value;
	}

	public function serialize( $value ) {
		// Detect if the array contained any properties non-serializable
		// to json.
		// TODO: make detectNonSerializableData not choke on cyclic structures.
		$deserializablePath = $this->detectNonSerializableDataInternal(
			$value, false, '$', false
		);
		if ( $deserializablePath ) {
			throw new JsonException(
				"Non-deserializable property set at {$deserializablePath}"
			);
		}
		// Recursively convert stdClass and JsonSerializable
		// to serializable arrays
		$value = $this->serializeOne( $value );
		// Format as JSON
		$json = FormatJson::encode( $value, false, FormatJson::ALL_OK );
		if ( !$json ) {
			try {
				// Try to collect more information on the failure.
				$details = $this->detectNonSerializableData( $value );
			} catch ( \Throwable $t ) {
				$details = $t->getMessage();
			}
			throw new JsonException(
				'Failed to encode JSON. ' .
				'Error: ' . json_last_error_msg() . '. ' .
				'Details: ' . $details
			);
		}

		return $json;
	}

	/**
	 * Is it likely possible to make a new instance from $json serialization?
	 * @param mixed $json
	 * @return bool
	 */
	private function canMakeNewFromValue( $json ): bool {
		$classAnnotation = JsonConstants::TYPE_ANNOTATION;
		if ( is_array( $json ) ) {
			return array_key_exists( $classAnnotation, $json ) &&
				# T313818: conflict with ParserOutput::detectAndEncodeBinary()
				$json[$classAnnotation] !== 'string';
		}

		if ( is_object( $json ) ) {
			return property_exists( $json, $classAnnotation );
		}
		return false;
	}

	/**
	 * Does this serialized array contain a complex value (a serialized class
	 * or an array which itself contains a serialized class)?
	 * @param mixed $json
	 * @return bool
	 */
	private function containsComplexValue( $json ): bool {
		if ( is_array( $json ) ) {
			return array_key_exists( JsonConstants::COMPLEX_ANNOTATION, $json );
		}
		return false;
	}

	/**
	 * Recursive check for ability to serialize $value to JSON via FormatJson::encode().
	 *
	 * @param mixed $value
	 * @param bool $expectDeserialize
	 * @param string $accumulatedPath
	 * @param bool $exhaustive Whether to (slowly) completely traverse the
	 *  $value in order to find the precise location of a problem
	 * @return string|null JSON path to first encountered non-serializable property or null.
	 */
	private function detectNonSerializableDataInternal(
		$value,
		bool $expectDeserialize,
		string $accumulatedPath,
		bool $exhaustive = false
	): ?string {
		if (
			$this->canMakeNewFromValue( $value ) ||
			$this->containsComplexValue( $value )
		) {
			// Contains a conflicting use of JsonConstants::TYPE_ANNOTATION or
			// JsonConstants::COMPLEX_ANNOTATION; in the future we might use
			// an alternative encoding for these objects to allow them.
			return $accumulatedPath . ': conflicting use of protected property';
		}
		if ( is_object( $value ) ) {
			if ( get_class( $value ) === stdClass::class ) {
				$value = (array)$value;
			} elseif (
				$expectDeserialize ?
				$value instanceof JsonDeserializable :
				$value instanceof JsonSerializable
			) {
				if ( $exhaustive ) {
					// Call the appropriate serialization method and recurse to
					// ensure contents are also serializable.
					$value = $value->jsonSerialize();
					if ( !is_array( $value ) ) {
						return $accumulatedPath . ": jsonSerialize didn't return array";
					}
				} else {
					// Assume that serializable objects contain 100%
					// serializable contents in their representation.
					return null;
				}
			} else {
				// Instances of classes other the \stdClass or JsonSerializable can not be serialized to JSON.
				return $accumulatedPath . ': ' . get_class( $value );
			}
		}
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $propValue ) {
				$propValueNonSerializablePath = $this->detectNonSerializableDataInternal(
					$propValue,
					$expectDeserialize,
					$accumulatedPath . '.' . $key,
					$exhaustive
				);
				if ( $propValueNonSerializablePath !== null ) {
					return $propValueNonSerializablePath;
				}
			}
		} elseif ( !is_scalar( $value ) && $value !== null ) {
			return $accumulatedPath . ': nonscalar ' . gettype( $value );
		}
		return null;
	}

	/**
	 * Checks if the $value is JSON-serializable (contains only scalar values)
	 * and returns a JSON-path to the first non-serializable property encountered.
	 *
	 * @param mixed $value
	 * @param bool $expectDeserialize whether to expect the $value to be deserializable with JsonDeserializer.
	 * @return string|null JSON path to first encountered non-serializable property or null.
	 * @see JsonDeserializer
	 * @since 1.36
	 */
	public function detectNonSerializableData( $value, bool $expectDeserialize = false ): ?string {
		return $this->detectNonSerializableDataInternal( $value, $expectDeserialize, '$', true );
	}
}
