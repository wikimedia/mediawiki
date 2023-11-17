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

use FormatJson;
use JsonException;
use JsonSerializable;
use MediaWiki\Parser\ParserOutput;
use stdClass;
use Wikimedia\Assert\Assert;

/**
 * Helper class to serialize/unserialize things to/from JSON.
 *
 * @stable to type
 * @since 1.36
 * @package MediaWiki\Json
 */
class JsonCodec implements JsonUnserializer, JsonSerializer {

	public function unserialize( $json, string $expectedClass = null ) {
		Assert::parameterType( [ 'stdClass', 'array', 'string' ], $json, '$json' );
		Assert::precondition(
			!$expectedClass || is_subclass_of( $expectedClass, JsonUnserializable::class ),
			'$expectedClass parameter must be subclass of JsonUnserializable, got ' . $expectedClass
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
			// Recursively unserialize the array values before unserializing
			// the array itself.
			$json = $this->unserializeArray( $json );
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
			 !( class_exists( $class ) && is_subclass_of( $class, JsonUnserializable::class ) )
		) {
			throw new JsonException( "Invalid target class {$class}" );
		}

		if ( $expectedClass && $class !== $expectedClass && !is_subclass_of( $class, $expectedClass ) ) {
			throw new JsonException(
				"Refusing to unserialize: expected $expectedClass, got $class"
			);
		}
		if ( $class === stdClass::class ) {
			unset( $json[JsonConstants::TYPE_ANNOTATION] );
			return (object)$json;
		}
		return $class::newFromJsonArray( $this, $json );
	}

	public function unserializeArray( array $array ): array {
		$unserializedExtensionData = [];
		foreach ( $array as $key => $value ) {
			if ( $key === JsonConstants::COMPLEX_ANNOTATION ) {
				/* don't include this in the result */
			} elseif (
				$this->canMakeNewFromValue( $value ) ||
				$this->containsComplexValue( $value )
			) {
				$unserializedExtensionData[$key] = $this->unserialize( $value );
			} else {
				$unserializedExtensionData[$key] = $value;
			}
		}
		return $unserializedExtensionData;
	}

	private function serializeOne( &$value ) {
		if ( $value instanceof JsonSerializable ) {
			$value = $value->jsonSerialize();
			$value[JsonConstants::COMPLEX_ANNOTATION] = true;
			// The returned array may still have instance of JsonSerializable,
			// stdClass, or array, so fall through to recursively handle these.
		} elseif ( is_object( $value ) && get_class( $value ) === stdClass::class ) {
			// T312589: if $value is stdObject, mark the type
			// so we unserialize as stdObject as well.
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
				throw new JsonException(
					'Unable to serialize JSON.'
				);
		}
		return $value;
	}

	public function serialize( $value ) {
		// Detect if the array contained any properties non-serializable
		// to json.
		// TODO: make detectNonSerializableData not choke on cyclic structures.
		$unserializablePath = $this->detectNonSerializableDataInternal(
			$value, false, '$'
		);
		if ( $unserializablePath ) {
			throw new JsonException(
				"Non-unserializable property set at {$unserializablePath}"
			);
		}
		// Recursively convert stdClass and JsonSerializable
		// to serializable arrays
		$value = $this->serializeOne( $value );
		// Format as JSON
		$json = FormatJson::encode( $value, false, FormatJson::ALL_OK );
		if ( !$json ) {
			throw new JsonException(
				'Failed to encode JSON. Error ' . json_last_error_msg()
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
	 * @param bool $expectUnserialize
	 * @param string $accumulatedPath
	 * @return string|null JSON path to first encountered non-serializable property or null.
	 */
	private function detectNonSerializableDataInternal(
		$value,
		bool $expectUnserialize,
		string $accumulatedPath
	): ?string {
		if (
			$this->canMakeNewFromValue( $value ) ||
			$this->containsComplexValue( $value )
		) {
			// Contains a conflicting use of JsonConstants::TYPE_ANNOTATION or
			// JsonConstants::COMPLEX_ANNOTATION; in the future we might use
			// an alternative encoding for these objects to allow them.
			return $accumulatedPath;
		}
		if ( is_array( $value ) || (
			is_object( $value ) && get_class( $value ) === stdClass::class
		) ) {
			foreach ( $value as $key => &$propValue ) {
				$propValueNonSerializablePath = $this->detectNonSerializableDataInternal(
					$propValue,
					$expectUnserialize,
					$accumulatedPath . '.' . $key
				);
				if ( $propValueNonSerializablePath ) {
					return $propValueNonSerializablePath;
				}
			}
		} elseif (
			( $expectUnserialize && $value instanceof JsonUnserializable )
			// Trust that JsonSerializable will correctly serialize.
			|| ( !$expectUnserialize && $value instanceof JsonSerializable )
		) {
			return null;
			// Instances of classes other the \stdClass or JsonSerializable can not be serialized to JSON.
		} elseif ( !is_scalar( $value ) && $value !== null ) {
			return $accumulatedPath;
		}
		return null;
	}

	/**
	 * Checks if the $value is JSON-serializable (contains only scalar values)
	 * and returns a JSON-path to the first non-serializable property encountered.
	 *
	 * @param mixed $value
	 * @param bool $expectUnserialize whether to expect the $value to be unserializable with JsonUnserializer.
	 * @return string|null JSON path to first encountered non-serializable property or null.
	 * @see JsonUnserializer
	 * @since 1.36
	 */
	public function detectNonSerializableData( $value, bool $expectUnserialize = false ): ?string {
		return $this->detectNonSerializableDataInternal( $value, $expectUnserialize, '$' );
	}
}
