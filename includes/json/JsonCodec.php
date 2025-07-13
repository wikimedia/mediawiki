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

use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use stdClass;
use Throwable;
use Wikimedia\Assert\Assert;
use Wikimedia\JsonCodec\JsonClassCodec;
use Wikimedia\JsonCodec\JsonCodecable;

/**
 * Helper class to serialize/deserialize things to/from JSON.
 *
 * @stable to type
 * @since 1.36
 * @package MediaWiki\Json
 */
class JsonCodec
	extends \Wikimedia\JsonCodec\JsonCodec
	implements JsonDeserializer, JsonSerializer
{

	/**
	 * When true, add extra properties to the serialized output for
	 * backwards compatibility. This will eventually be made a
	 * configuration variable and/or removed. (T367584)
	 */
	private bool $backCompat = true;

	/**
	 * Create a new JsonCodec, with optional access to the provided services.
	 */
	public function __construct( ?ContainerInterface $services = null ) {
		parent::__construct( $services );
	}

	/**
	 * Support the JsonCodecable interface by maintaining a mapping of
	 * class names to codecs.
	 * @param class-string $className
	 * @return ?JsonClassCodec
	 */
	protected function codecFor( string $className ): ?JsonClassCodec {
		static $deserializableCodec = null;
		$codec = parent::codecFor( $className );
		if ( $codec !== null ) {
			return $codec;
		}
		// Resolve class aliases to ensure we don't use split codecs
		$className = ( new ReflectionClass( $className ) )->getName();
		// Provide a codec for JsonDeserializable objects
		if ( is_a( $className, JsonDeserializable::class, true ) ) {
			if ( $deserializableCodec === null ) {
				$deserializableCodec = new JsonDeserializableCodec( $this );
			}
			$codec = $deserializableCodec;
			$this->addCodecFor( $className, $codec );
			return $codec;
		}
		// Provide a codec for JsonSerializable objects:
		// NOTE this is for compatibility only and does not deserialize!
		if ( is_a( $className, JsonSerializable::class, true ) ) {
			$codec = JsonSerializableCodec::getInstance();
			$this->addCodecFor( $className, $codec );
			return $codec;
		}
		return null;
	}

	/** @inheritDoc */
	protected function markArray( array &$value, string $className, ?string $classHint ): void {
		parent::markArray( $value, $className, $classHint );
		// Temporarily for backward compatibility add COMPLEX_ANNOTATION as well
		if ( $this->backCompat ) {
			$value[JsonConstants::COMPLEX_ANNOTATION] = true;
			if ( ( $value[JsonConstants::TYPE_ANNOTATION] ?? null ) === 'array' ) {
				unset( $value[JsonConstants::TYPE_ANNOTATION] );
			}
		}
	}

	/** @inheritDoc */
	protected function isArrayMarked( array $value ): bool {
		// Temporarily for backward compatibility look for COMPLEX_ANNOTATION as well
		if ( $this->backCompat && array_key_exists( JsonConstants::COMPLEX_ANNOTATION, $value ) ) {
			return true;
		}
		if ( ( $value['_type_'] ?? null ) === 'string' ) {
			// T313818: see ParserOutput::detectAndEncodeBinary()
			return false;
		}
		return parent::isArrayMarked( $value );
	}

	/** @inheritDoc */
	protected function unmarkArray( array &$value, ?string $classHint ): string {
		// Temporarily use the presence of COMPLEX_ANNOTATION as a hint that
		// the type is 'array'
		if (
			$this->backCompat &&
			$classHint === null &&
			array_key_exists( JsonConstants::COMPLEX_ANNOTATION, $value )
		) {
			$classHint = 'array';
		}
		// @phan-suppress-next-line PhanUndeclaredClassReference 'array'
		$className = parent::unmarkArray( $value, $classHint );
		// Remove the temporarily added COMPLEX_ANNOTATION
		if ( $this->backCompat ) {
			unset( $value[JsonConstants::COMPLEX_ANNOTATION] );
		}
		return $className;
	}

	/** @deprecated since 1.43; use ::deserialize() */
	public function unserialize( $json, ?string $expectedClass = null ) {
		return $this->deserialize( $json, $expectedClass );
	}

	public function deserialize( $json, ?string $expectedClass = null ) {
		Assert::parameterType( [ 'stdClass', 'array', 'string' ], $json, '$json' );
		Assert::precondition(
			!$expectedClass ||
			is_subclass_of( $expectedClass, JsonDeserializable::class ) ||
			is_subclass_of( $expectedClass, JsonCodecable::class ),
			'$expectedClass parameter must be subclass of JsonDeserializable or JsonCodecable, got ' . $expectedClass
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

		if ( $expectedClass !== null ) {
			// Make copy of $json to avoid unmarking the 'real thing'
			$jsonCopy = $json;
			if ( is_array( $jsonCopy ) && $this->isArrayMarked( $jsonCopy ) ) {
				$got = $this->unmarkArray( $jsonCopy, $expectedClass );
				// Compare $got to $expectedClass in a way that works in the
				// presence of aliases
				if ( !is_a( $got, $expectedClass, true ) ) {
					throw new JsonException( "Expected {$expectedClass} got {$got}" );
				}
			} else {
				$got = get_debug_type( $json );
				throw new JsonException( "Expected {$expectedClass} got {$got}" );
			}
		}
		try {
			$result = is_array( $json ) ? $this->newFromJsonArray( $json ) : $json;
		} catch ( InvalidArgumentException $e ) {
			throw new JsonException( $e->getMessage() );
		}
		if ( $expectedClass && !is_a( $result, $expectedClass, false ) ) {
			throw new JsonException( "Unexpected class: {$expectedClass}" );
		}
		return $result;
	}

	/** @deprecated since 1.43; use ::deserializeArray() */
	public function unserializeArray( array $array ): array {
		return $this->deserializeArray( $array );
	}

	public function deserializeArray( array $array ): array {
		try {
			// Pass a class hint here to ensure we recurse into the array.
			// @phan-suppress-next-line PhanUndeclaredClassReference 'array'
			return $this->newFromJsonArray( $array, 'array' );
		} catch ( InvalidArgumentException $e ) {
			throw new JsonException( $e->getMessage() );
		}
	}

	public function serialize( $value ) {
		// Recursively convert stdClass, JsonSerializable, and JsonCodecable
		// to serializable arrays
		try {
			$value = $this->toJsonArray( $value );
		} catch ( InvalidArgumentException $e ) {
			throw new JsonException( $e->getMessage() );
		}
		// Format as JSON
		$json = FormatJson::encode( $value, false, FormatJson::ALL_OK );
		if ( !$json ) {
			try {
				// Try to collect more information on the failure.
				$details = $this->detectNonSerializableData( $value );
			} catch ( Throwable $t ) {
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

	// The code below this point is used only for diagnostics; in particular
	// for the ::detectNonSerializableData() method which is used to provide
	// debugging information in the event of a serialization failure.

	/**
	 * Recursive check for the ability to serialize $value to JSON via FormatJson::encode().
	 *
	 * @param mixed $value
	 * @param bool $expectDeserialize
	 * @param string $accumulatedPath
	 * @param bool $exhaustive Whether to (slowly) completely traverse the
	 *  $value in order to find the precise location of a problem
	 * @return string|null JSON path to the first encountered non-serializable property or null.
	 */
	private function detectNonSerializableDataInternal(
		$value,
		bool $expectDeserialize,
		string $accumulatedPath,
		bool $exhaustive = false
	): ?string {
		if (
			( is_array( $value ) && $this->isArrayMarked( $value ) ) ||
			( $value instanceof stdClass && $this->isArrayMarked( (array)$value ) )
		) {
			// Contains a conflicting use of JsonConstants::TYPE_ANNOTATION or
			// JsonConstants::COMPLEX_ANNOTATION; in the future we might use
			// an alternative encoding for these objects to allow them.
			return $accumulatedPath . ': conflicting use of protected property';
		}
		if ( is_object( $value ) ) {
			if ( get_class( $value ) === stdClass::class ) {
				$value = (array)$value;
			} elseif ( $value instanceof JsonCodecable ) {
				if ( $exhaustive ) {
					// Call the appropriate serialization method and recurse to
					// ensure contents are also serializable.
					$codec = $this->codecFor( get_class( $value ) );
					$value = $codec->toJsonArray( $value );
				} else {
					// Assume that serializable objects contain 100%
					// serializable contents in their representation.
					return null;
				}
			} elseif (
				$expectDeserialize ?
				$value instanceof JsonDeserializable :
				$value instanceof JsonSerializable
			) {
				if ( $exhaustive ) {
					// Call the appropriate serialization method and recurse to
					// ensure contents are also serializable.
					'@phan-var JsonSerializable $value';
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
				// Instances of classes other the \stdClass or JsonSerializable cannot be serialized to JSON.
				return $accumulatedPath . ': ' . get_debug_type( $value );
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
			return $accumulatedPath . ': nonscalar ' . get_debug_type( $value );
		}
		return null;
	}

	/**
	 * Checks if the $value is JSON-serializable (contains only scalar values)
	 * and returns a JSON-path to the first non-serializable property encountered.
	 *
	 * @param mixed $value
	 * @param bool $expectDeserialize whether to expect the $value to be deserializable with JsonDeserializer.
	 * @return string|null JSON path to the first encountered non-serializable property or null.
	 * @see JsonDeserializer
	 * @since 1.36
	 */
	public function detectNonSerializableData( $value, bool $expectDeserialize = false ): ?string {
		return $this->detectNonSerializableDataInternal( $value, $expectDeserialize, '$', true );
	}
}
