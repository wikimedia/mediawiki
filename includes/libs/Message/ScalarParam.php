<?php

namespace Wikimedia\Message;

use InvalidArgumentException;
use MediaWiki\Json\JsonDeserializer;
use Stringable;

/**
 * Value object representing a message parameter holding a single value.
 *
 * Message parameter classes are pure value objects and are safely newable.
 *
 * When using the deprecated ParamType::OBJECT, the parameter value
 * should be (de)serializable, otherwise (de)serialization of the
 * ScalarParam object will fail.
 *
 * @newable
 */
class ScalarParam extends MessageParam {
	/**
	 * Construct a text parameter
	 *
	 * @stable to call.
	 *
	 * @param string $type One of the ParamType constants.
	 *   Using ParamType::OBJECT is deprecated since 1.43.
	 * @param string|int|float|MessageValue|Stringable $value
	 */
	public function __construct( $type, $value ) {
		if ( $type === ParamType::LIST ) {
			throw new InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( $type === ParamType::OBJECT ) {
			wfDeprecatedMsg( 'Using ParamType::OBJECT was deprecated in MediaWiki 1.43', '1.43' );
		} elseif ( $value instanceof Stringable ) {
			// Stringify the stringable to ensure that $this->value is JSON-serializable
			// (but don't do it when using ParamType::OBJECT, since those objects may not expect it)
			$value = (string)$value;
		} elseif ( !is_string( $value ) && !is_numeric( $value ) &&
			!$value instanceof MessageValue ) {
			$type = is_object( $value ) ? get_class( $value ) : gettype( $value );
			throw new InvalidArgumentException(
				"Scalar parameter must be a string, number, or MessageValue; got $type"
			);
		}

		$this->type = $type;
		$this->value = $value;
	}

	public function dump() {
		if ( $this->value instanceof MessageValue ) {
			$contents = $this->value->dump();
		} else {
			$contents = htmlspecialchars( (string)$this->value );
		}
		return "<{$this->type}>" . $contents . "</{$this->type}>";
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			$this->type => $this->value,
		];
	}

	public static function newFromJsonArray( JsonDeserializer $deserializer, array $json ) {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		if ( count( $json ) !== 1 ) {
			throw new InvalidArgumentException( 'Invalid format' );
		}
		// Use a dummy loop to get the first (and only) key/value pair in the array.
		foreach ( $json as $type => $value ) {
			return new self( $type, $value );
		}
	}
}
