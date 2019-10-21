<?php

namespace Wikimedia\Message;

/**
 * Value object representing a message for i18n.
 *
 * A MessageValue holds a key and an array of parameters. It can be converted
 * to a string in a particular language using formatters obtained from an
 * IMessageFormatterFactory.
 *
 * MessageValues are pure value objects and are safely newable.
 */
class MessageValue {
	/** @var string */
	private $key;

	/** @var MessageParam[] */
	private $params;

	/**
	 * @param string $key
	 * @param (MessageParam|MessageValue|string|int|float)[] $params Values that are not instances
	 *  of MessageParam are wrapped using ParamType::TEXT.
	 */
	public function __construct( $key, $params = [] ) {
		$this->key = $key;
		$this->params = [];
		$this->params( ...$params );
	}

	/**
	 * Get the message key
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Get the parameter array
	 *
	 * @return MessageParam[]
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Chainable mutator which adds text parameters and MessageParam parameters
	 *
	 * @param MessageParam|MessageValue|string|int|float ...$values
	 * @return MessageValue
	 */
	public function params( ...$values ) {
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
	 * @param MessageValue|string|int|float ...$values Scalar values
	 * @return MessageValue
	 */
	public function textParamsOfType( $type, ...$values ) {
		foreach ( $values as $value ) {
			$this->params[] = new ScalarParam( $type, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds list parameters with a common type
	 *
	 * @param string $listType One of the ListType constants
	 * @param (MessageParam|MessageValue|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return MessageValue
	 */
	public function listParamsOfType( $listType, ...$values ) {
		foreach ( $values as $value ) {
			$this->params[] = new ListParam( $listType, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds parameters of type text (ParamType::TEXT).
	 *
	 * @param MessageValue|string|int|float ...$values
	 * @return MessageValue
	 */
	public function textParams( ...$values ) {
		return $this->textParamsOfType( ParamType::TEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds numeric parameters (ParamType::NUM).
	 *
	 * @param int|float ...$values
	 * @return MessageValue
	 */
	public function numParams( ...$values ) {
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
	 * @return MessageValue
	 */
	public function longDurationParams( ...$values ) {
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
	 * @return MessageValue
	 */
	public function shortDurationParams( ...$values ) {
		return $this->textParamsOfType( ParamType::DURATION_SHORT, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are an expiry timestamp (ParamType::EXPIRY).
	 *
	 * @param string ...$values Timestamp as accepted by the Wikimedia\Timestamp library,
	 *  or "infinity"
	 * @return MessageValue
	 */
	public function expiryParams( ...$values ) {
		return $this->textParamsOfType( ParamType::EXPIRY, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bytes (ParamType::SIZE).
	 *
	 * @param int ...$values
	 * @return MessageValue
	 */
	public function sizeParams( ...$values ) {
		return $this->textParamsOfType( ParamType::SIZE, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bits per
	 * second (ParamType::BITRATE).
	 *
	 * @param int|float ...$values
	 * @return MessageValue
	 */
	public function bitrateParams( ...$values ) {
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
	 * @return MessageValue
	 */
	public function rawParams( ...$values ) {
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
	 * @return MessageValue
	 */
	public function plaintextParams( ...$values ) {
		return $this->textParamsOfType( ParamType::PLAINTEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds comma lists (ListType::COMMA).
	 *
	 * The list parameters thus created are formatted as a comma-separated list,
	 * or some local equivalent.
	 *
	 * @param (MessageParam|MessageValue|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return MessageValue
	 */
	public function commaListParams( ...$values ) {
		return $this->listParamsOfType( ListType::COMMA, ...$values );
	}

	/**
	 * Chainable mutator which adds semicolon lists (ListType::SEMICOLON).
	 *
	 * The list parameters thus created are formatted as a semicolon-separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|MessageValue|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return MessageValue
	 */
	public function semicolonListParams( ...$values ) {
		return $this->listParamsOfType( ListType::SEMICOLON, ...$values );
	}

	/**
	 * Chainable mutator which adds pipe lists (ListType::PIPE).
	 *
	 * The list parameters thus created are formatted as a pipe ("|") -separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|MessageValue|string|int|float)[] ...$values Each value
	 *  is an array of items suitable to pass as $params to ListParam::__construct()
	 * @return MessageValue
	 */
	public function pipeListParams( ...$values ) {
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
	 * @return MessageValue
	 */
	public function textListParams( ...$values ) {
		return $this->listParamsOfType( ListType::AND, ...$values );
	}

	/**
	 * Dump the object for testing/debugging
	 *
	 * @return string
	 */
	public function dump() {
		$contents = '';
		foreach ( $this->params as $param ) {
			$contents .= $param->dump();
		}
		return '<message key="' . htmlspecialchars( $this->key ) . '">' .
			$contents . '</message>';
	}
}
