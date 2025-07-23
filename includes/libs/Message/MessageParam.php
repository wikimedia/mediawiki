<?php

namespace Wikimedia\Message;

use Wikimedia\JsonCodec\Hint;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 */
abstract class MessageParam implements JsonCodecable {
	use JsonCodecableTrait;

	protected string $type;
	/** @var mixed */
	protected $value;

	/**
	 * Get the type of the parameter.
	 *
	 * @return string One of the ParamType constants
	 */
	public function getType(): string {
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
		if ( $keyName === ParamType::LIST ) {
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
		if ( isset( $json[ParamType::LIST] ) ) {
			return ListParam::newFromJsonArray( $json );
		}
		return ScalarParam::newFromJsonArray( $json );
	}

	/**
	 * If you are serializing a MessageParam, use
	 * this JsonCodec hint to suppress unnecessary type information.
	 */
	public static function hint(): Hint {
		return Hint::build( self::class, Hint::INHERITED, Hint::ONLY_FOR_DECODE );
	}
}
