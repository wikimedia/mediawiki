<?php

namespace Wikimedia\Message;

use Wikimedia\JsonCodec\Hint;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message parameter with one of the types from {@see ParamType} and a
 * corresponding value.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 */
abstract class MessageParam implements JsonCodecable {
	use JsonCodecableTrait;

	// We can't use PHP type hint here without breaking deserialization of
	// old MessageParams saved with PHP serialize().
	/** @var ParamType */
	protected $type;
	/** @var mixed */
	protected $value;

	/**
	 * Get the type of the parameter.
	 *
	 * @return ParamType One of the ParamType constants
	 */
	public function getType(): ParamType {
		return $this->type;
	}

	/**
	 * Get the input value of the parameter
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Dump the object for testing/debugging
	 *
	 * @return string
	 */
	abstract public function dump(): string;

	/**
	 * Equality testing.
	 */
	abstract public function isSameAs( MessageParam $mp ): bool;

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		// Support Hint::INHERITED
		if ( $keyName === ParamType::LIST->value ) {
			return ListParam::jsonClassHintFor( $keyName );
		}
		return ScalarParam::jsonClassHintFor( $keyName );
	}

	/** @inheritDoc */
	public static function newFromJsonArray( array $json ): MessageParam {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		// Because of the use of Hint::INHERITED,
		// MessageParam::newFromJsonArray() needs to know how to dispatch to
		// an appropriate subclass constructor.
		if ( isset( $json[ParamType::LIST->value] ) ) {
			return ListParam::newFromJsonArray( $json );
		}
		return ScalarParam::newFromJsonArray( $json );
	}

	/**
	 * If you are serializing a MessageParam, use
	 * this JsonCodec hint to suppress unnecessary type information.
	 */
	public static function hint(): Hint {
		return Hint::build( self::class, Hint::INHERITED );
	}

	public function __wakeup(): void {
		// Backward-compatibility for PHP serialization:
		// Fixup $type after deserialization
		if ( is_string( $this->type ) ) {
			$this->type = ParamType::from( $this->type );
		}
	}
}
