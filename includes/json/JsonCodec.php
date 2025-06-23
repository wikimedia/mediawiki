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

use CacheTime;
use FormatJson;
use InvalidArgumentException;
use JsonSerializable;
use ParserOutput;
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
		Assert::parameterType( [ 'object', 'array', 'string' ], $json, '$json' );
		Assert::precondition(
			!$expectedClass || is_subclass_of( $expectedClass, JsonUnserializable::class ),
			'$expectedClass parameter must be subclass of JsonUnserializable, got ' . $expectedClass
		);
		if ( is_string( $json ) ) {
			$jsonStatus = FormatJson::parse( $json, FormatJson::FORCE_ASSOC );
			if ( !$jsonStatus->isGood() ) {
				// TODO: in PHP 7.3, we can use JsonException
				throw new InvalidArgumentException( "Bad JSON: {$jsonStatus}" );
			}
			$json = $jsonStatus->getValue();
		}

		if ( is_object( $json ) ) {
			$json = (array)$json;
		}

		if ( !$this->canMakeNewFromValue( $json ) ) {
			if ( $expectedClass ) {
				throw new InvalidArgumentException( 'JSON did not have ' . JsonConstants::TYPE_ANNOTATION );
			}
			return $json;
		}

		$class = $json[JsonConstants::TYPE_ANNOTATION];
		if ( $class == "ParserOutput" || $class == "MediaWiki\\Parser\\ParserOutput" ) {
			$class = ParserOutput::class; // T353835
		} elseif ( $class == "CacheTime" || $class == "MediaWiki\\Parser\\CacheTime" ) {
			$class = CacheTime::class; // T353835
		} elseif ( !class_exists( $class ) || !is_subclass_of( $class, JsonUnserializable::class ) ) {
			throw new InvalidArgumentException( "Invalid target class {$class}" );
		}

		if ( $expectedClass && $class !== $expectedClass && !is_subclass_of( $class, $expectedClass ) ) {
			throw new InvalidArgumentException(
				"Refusing to unserialize: expected $expectedClass, got $class"
			);
		}
		return $class::newFromJsonArray( $this, $json );
	}

	public function unserializeArray( array $array ): array {
		$unserializedExtensionData = [];
		foreach ( $array as $key => $value ) {
			if ( $this->canMakeNewFromValue( $value ) ) {
				$unserializedExtensionData[$key] = $this->unserialize( $value );
			} else {
				$unserializedExtensionData[$key] = $value;
			}
		}
		return $unserializedExtensionData;
	}

	public function serialize( $value ) {
		if ( $value instanceof JsonSerializable ) {
			$value = $value->jsonSerialize();
		}
		$json = FormatJson::encode( $value, false, FormatJson::ALL_OK );
		if ( !$json ) {
			// TODO: make it JsonException
			throw new InvalidArgumentException(
				'Failed to encode JSON. Error ' . json_last_error_msg()
			);
		}

		// Detect if the array contained any properties non-serializable
		// to json. We will not be able to deserialize the value correctly
		// anyway, so return null. This is done after calling FormatJson::encode
		// to avoid walking over circular structures.
		// TODO: make detectNonSerializableData not choke on cyclic structures.
		$unserializablePath = $this->detectNonSerializableData( $value, true );
		if ( $unserializablePath ) {
			// TODO: Make it JsonException
			throw new InvalidArgumentException(
				"Non-unserializable property set at {$unserializablePath}"
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
			return array_key_exists( $classAnnotation, $json );
		}

		if ( is_object( $json ) ) {
			return $json->$classAnnotation;
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
		if ( is_array( $value ) ||
			( is_object( $value ) && get_class( $value ) === 'stdClass' ) ) {
			foreach ( $value as $key => $propValue ) {
				$propValueNonSerializablePath = $this->detectNonSerializableDataInternal(
					$propValue,
					$expectUnserialize,
					$accumulatedPath . '.' . $key
				);
				if ( $propValueNonSerializablePath ) {
					return $propValueNonSerializablePath;
				}
			}
		} elseif ( ( $expectUnserialize && $value instanceof JsonUnserializable )
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
