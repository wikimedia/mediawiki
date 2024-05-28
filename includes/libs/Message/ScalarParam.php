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
 * @newable
 */
class ScalarParam extends MessageParam {
	/**
	 * Construct a text parameter
	 *
	 * @stable to call.
	 *
	 * @param string $type One of the ParamType constants.
	 * @param string|int|float|MessageSpecifier|Stringable $value
	 */
	public function __construct( $type, $value ) {
		if ( !in_array( $type, ParamType::cases() ) ) {
			throw new InvalidArgumentException( '$type must be one of the ParamType constants' );
		}
		if ( $type === ParamType::LIST ) {
			throw new InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( $value instanceof MessageSpecifier ) {
			// Ensure that $this->value is JSON-serializable, even if $value is not
			$value = MessageValue::newFromSpecifier( $value );
		} elseif ( is_object( $value ) && (
			$value instanceof Stringable || is_callable( [ $value, '__toString' ] )
		) ) {
			// TODO: Remove separate '__toString' check above once we drop PHP 7.4
			$value = (string)$value;
		} elseif ( !is_string( $value ) && !is_numeric( $value ) ) {
			$type = get_debug_type( $value );
			throw new InvalidArgumentException(
				"Scalar parameter must be a string, number, Stringable, or MessageSpecifier; got $type"
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

	protected function toJsonArray(): array {
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
