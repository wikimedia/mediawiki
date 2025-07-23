<?php

namespace Wikimedia\Message;

use Wikimedia\Assert\Assert;
use Wikimedia\JsonCodec\Hint;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * Value object representing a message for i18n.
 *
 * A MessageValue holds a key and an array of parameters. It can be converted
 * to a string in a particular language using formatters obtained from an
 * IMessageFormatterFactory.
 *
 * MessageValues are pure value objects and are newable and (de)serializable.
 *
 * @newable
 */
class MessageValue implements MessageSpecifier, JsonCodecable {
	use JsonCodecableTrait;

	private string $key;

	/** @var list<MessageParam> */
	private array $params;

	/**
	 * @stable to call
	 *
	 * @param string $key
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] $params Values that are not instances
	 *  of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( string $key, array $params = [] ) {
		$this->key = $key;
		$this->params = [];
		$this->params( ...$params );
		// @phan-suppress-next-line PhanRedundantCondition phan doesn't see side-effects on $this->params
		Assert::invariant( array_is_list( $this->params ), "should be list" );
	}

	/**
	 * Static constructor for easier chaining of `->params()` methods
	 * @param string $key
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] $params
	 * @return MessageValue
	 */
	public static function new( string $key, array $params = [] ): MessageValue {
		return new MessageValue( $key, $params );
	}

	/**
	 * Convert from any MessageSpecifier to a MessageValue.
	 *
	 * When the given object is an instance of MessageValue, the same object is returned.
	 *
	 * @since 1.43
	 * @param MessageSpecifier $spec
	 * @return MessageValue
	 */
	public static function newFromSpecifier( MessageSpecifier $spec ): MessageValue {
		if ( $spec instanceof MessageValue ) {
			return $spec;
		}
		return new MessageValue( $spec->getKey(), $spec->getParams() );
	}

	/**
	 * Get the message key
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * Get the parameter array
	 *
	 * @return MessageParam[]
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * Chainable mutator which adds text parameters and MessageParam parameters
	 *
	 * @param MessageParam|MessageSpecifier|string|int|float ...$values
	 * @return $this
	 */
	public function params( ...$values ): MessageValue {
		foreach ( $values as $value ) {
			if ( $value instanceof MessageParam ) {
				$this->params[] = $value;
			} else {
				$this->params[] = new ScalarParam( ParamType::TEXT, $value );
			}
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds text parameters with a common type
	 *
	 * @param string $type One of the ParamType constants
	 * @param MessageSpecifier|string|int|float ...$values Scalar values
	 * @return $this
	 */
	public function textParamsOfType( string $type, ...$values ): MessageValue {
		foreach ( $values as $value ) {
			$this->params[] = new ScalarParam( $type, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds list parameters with a common type
	 *
	 * @param string $listType One of the ListType constants
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return $this
	 */
	public function listParamsOfType( string $listType, ...$values ): MessageValue {
		foreach ( $values as $value ) {
			$this->params[] = new ListParam( $listType, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds parameters of type text (ParamType::TEXT).
	 *
	 * @param MessageSpecifier|string|int|float ...$values
	 * @return $this
	 */
	public function textParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::TEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds numeric parameters (ParamType::NUM).
	 *
	 * @param int|float ...$values
	 * @return $this
	 */
	public function numParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::NUM, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a duration specified
	 * in seconds (ParamType::DURATION_LONG).
	 *
	 * This is similar to shorDurationParams() except that the result will be
	 * more verbose.
	 *
	 * @param int|float ...$values
	 * @return $this
	 */
	public function longDurationParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::DURATION_LONG, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a duration specified
	 * in seconds (ParamType::DURATION_SHORT).
	 *
	 * This is similar to longDurationParams() except that the result will be more
	 * compact.
	 *
	 * @param int|float ...$values
	 * @return $this
	 */
	public function shortDurationParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::DURATION_SHORT, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are an expiry timestamp (ParamType::EXPIRY).
	 *
	 * @param string ...$values Timestamp as accepted by the Wikimedia\Timestamp library,
	 *  or "infinity"
	 * @return $this
	 */
	public function expiryParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::EXPIRY, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a date-time timestamp (ParamType::DATETIME).
	 *
	 * @since 1.36
	 * @param string ...$values Timestamp as accepted by the Wikimedia\Timestamp library.
	 * @return $this
	 */
	public function dateTimeParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::DATETIME, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a date timestamp (ParamType::DATE).
	 *
	 * @since 1.36
	 * @param string ...$values Timestamp as accepted by the Wikimedia\Timestamp library.
	 * @return $this
	 */
	public function dateParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::DATE, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a time timestamp (ParamType::TIME).
	 *
	 * @since 1.36
	 * @param string ...$values Timestamp as accepted by the Wikimedia\Timestamp library.
	 * @return $this
	 */
	public function timeParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::TIME, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a user group (ParamType::GROUP).
	 *
	 * @since 1.38
	 * @param string ...$values User Groups
	 * @return $this
	 */
	public function userGroupParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::GROUP, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bytes (ParamType::SIZE).
	 *
	 * @param int ...$values
	 * @return $this
	 */
	public function sizeParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::SIZE, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bits per
	 * second (ParamType::BITRATE).
	 *
	 * @param int|float ...$values
	 * @return $this
	 */
	public function bitrateParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::BITRATE, ...$values );
	}

	/**
	 * Chainable mutator which adds "raw" parameters (ParamType::RAW).
	 *
	 * Raw parameters are substituted after formatter processing. The caller is responsible
	 * for ensuring that the value will be safe for the intended output format, and
	 * documenting what that intended output format is.
	 *
	 * @param string ...$values
	 * @return $this
	 */
	public function rawParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::RAW, ...$values );
	}

	/**
	 * Chainable mutator which adds plaintext parameters (ParamType::PLAINTEXT).
	 *
	 * Plaintext parameters are substituted after formatter processing. The value
	 * will be escaped by the formatter as appropriate for the target output format
	 * so as to be represented as plain text rather than as any sort of markup.
	 *
	 * @param string ...$values
	 * @return $this
	 */
	public function plaintextParams( ...$values ): MessageValue {
		return $this->textParamsOfType( ParamType::PLAINTEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds comma lists (ListType::COMMA).
	 *
	 * The list parameters thus created are formatted as a comma-separated list,
	 * or some local equivalent.
	 *
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return $this
	 */
	public function commaListParams( ...$values ): MessageValue {
		return $this->listParamsOfType( ListType::COMMA, ...$values );
	}

	/**
	 * Chainable mutator which adds semicolon lists (ListType::SEMICOLON).
	 *
	 * The list parameters thus created are formatted as a semicolon-separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return $this
	 */
	public function semicolonListParams( ...$values ): MessageValue {
		return $this->listParamsOfType( ListType::SEMICOLON, ...$values );
	}

	/**
	 * Chainable mutator which adds pipe lists (ListType::PIPE).
	 *
	 * The list parameters thus created are formatted as a pipe ("|") -separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|MessageSpecifier|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return $this
	 */
	public function pipeListParams( ...$values ): MessageValue {
		return $this->listParamsOfType( ListType::PIPE, ...$values );
	}

	/**
	 * Chainable mutator which adds natural-language lists (ListType::AND).
	 *
	 * The list parameters thus created, when formatted, are joined as in natural
	 * language. In English, this means a comma-separated list, with the last
	 * two elements joined with "and".
	 *
	 * @param (MessageParam|string)[] ...$values
	 * @return $this
	 */
	public function textListParams( ...$values ): MessageValue {
		return $this->listParamsOfType( ListType::AND, ...$values );
	}

	/**
	 * Dump the object for testing/debugging
	 *
	 * @return string
	 */
	public function dump(): string {
		$contents = '';
		foreach ( $this->params as $param ) {
			$contents .= $param->dump();
		}
		return '<message key="' . htmlspecialchars( $this->key ) . '">' .
			$contents . '</message>';
	}

	public function isSameAs( MessageValue $mv ): bool {
		return $this->key === $mv->key &&
			count( $this->params ) === count( $mv->params ) &&
			array_all(
				$this->params,
				static fn ( $v, $k ) => $v->isSameAs( $mv->params[$k] )
			);
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return [
			'key' => $this->key,
			'params' => array_map(
				/**
				 * Serialize trivial parameters as scalar values to minimize the footprint. Full
				 * round-trip compatibility is guaranteed via the constructor and {@see params}.
				 */
				static fn ( $p ) => $p->getType() === ParamType::TEXT ? $p->getValue() : $p,
				$this->params
			),
		];
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		// Reduce serialization overhead by eliminating the type information
		// when 'params' consists of MessageParam instances
		if ( $keyName === 'params' ) {
			return Hint::build(
				MessageParam::class, Hint::INHERITED,
				Hint::LIST, Hint::USE_SQUARE,
				Hint::ONLY_FOR_DECODE
			);
		}
		return null;
	}

	public static function newFromJsonArray( array $json ): MessageValue {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		// Support use of [MessageValue::class, Hint::INHERITED] for
		// DataMessageValue as well:
		if ( isset( $json['code'] ) ) {
			return DataMessageValue::newFromJsonArray( $json );
		}
		return new self( $json['key'], $json['params'] );
	}

	/**
	 * If you are serializing a MessageValue (or a DataMessageValue), use
	 * this JsonCodec hint to suppress unnecessary type information.
	 */
	public static function hint(): Hint {
		return Hint::build( self::class, Hint::INHERITED, Hint::ONLY_FOR_DECODE );
	}
}
