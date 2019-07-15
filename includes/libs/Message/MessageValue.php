<?php

namespace Wikimedia\Message;

/**
 * A MessageValue holds a key and an array of parameters
 */
class MessageValue {
	/** @var string */
	private $key;

	/** @var MessageParam[] */
	private $params;

	/**
	 * @param string $key
	 * @param array $params Each element of the parameter array
	 *   may be either a MessageParam or a scalar. If it is a scalar, it is
	 *   converted to a parameter of type TEXT.
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
	 * @param mixed ...$values Scalar or MessageParam values
	 * @return MessageValue
	 */
	public function params( ...$values ) {
		foreach ( $values as $value ) {
			if ( $value instanceof MessageParam ) {
				$this->params[] = $value;
			} else {
				$this->params[] = new TextParam( ParamType::TEXT, $value );
			}
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds text parameters with a common type
	 *
	 * @param string $type One of the ParamType constants
	 * @param mixed ...$values Scalar values
	 * @return MessageValue
	 */
	public function textParamsOfType( $type, ...$values ) {
		foreach ( $values as $value ) {
			$this->params[] = new TextParam( $type, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds list parameters with a common type
	 *
	 * @param string $listType One of the ListType constants
	 * @param array ...$values Each value should be an array of list items.
	 * @return MessageValue
	 */
	public function listParamsOfType( $listType, ...$values ) {
		foreach ( $values as $value ) {
			$this->params[] = new ListParam( $listType, $value );
		}
		return $this;
	}

	/**
	 * Chainable mutator which adds parameters of type text.
	 *
	 * @param string ...$values
	 * @return MessageValue
	 */
	public function textParams( ...$values ) {
		return $this->textParamsOfType( ParamType::TEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds numeric parameters
	 *
	 * @param mixed ...$values
	 * @return MessageValue
	 */
	public function numParams( ...$values ) {
		return $this->textParamsOfType( ParamType::NUM, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a duration specified
	 * in seconds. This is similar to timePeriodParams() except that the result
	 * will be more verbose.
	 *
	 * @param int|float ...$values
	 * @return MessageValue
	 */
	public function longDurationParams( ...$values ) {
		return $this->textParamsOfType( ParamType::DURATION_LONG, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a time period in seconds.
	 * This is similar to durationParams() except that the result will be more
	 * compact.
	 *
	 * @param int|float ...$values
	 * @return MessageValue
	 */
	public function shortDurationParams( ...$values ) {
		return $this->textParamsOfType( ParamType::DURATION_SHORT, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are an expiry timestamp
	 * as used in the MediaWiki database schema.
	 *
	 * @param string ...$values
	 * @return MessageValue
	 */
	public function expiryParams( ...$values ) {
		return $this->textParamsOfType( ParamType::EXPIRY, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bytes.
	 *
	 * @param int ...$values
	 * @return MessageValue
	 */
	public function sizeParams( ...$values ) {
		return $this->textParamsOfType( ParamType::SIZE, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters which are a number of bits per
	 * second.
	 *
	 * @param int|float ...$values
	 * @return MessageValue
	 */
	public function bitrateParams( ...$values ) {
		return $this->textParamsOfType( ParamType::BITRATE, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters of type "raw".
	 *
	 * @param mixed ...$values
	 * @return MessageValue
	 */
	public function rawParams( ...$values ) {
		return $this->textParamsOfType( ParamType::RAW, ...$values );
	}

	/**
	 * Chainable mutator which adds parameters of type "plaintext".
	 */
	public function plaintextParams( ...$values ) {
		return $this->textParamsOfType( ParamType::PLAINTEXT, ...$values );
	}

	/**
	 * Chainable mutator which adds comma lists. Each comma list is an array of
	 * list elements, and each list element is either a MessageParam or a
	 * string. String parameters are converted to parameters of type "text".
	 *
	 * The list parameters thus created are formatted as a comma-separated list,
	 * or some local equivalent.
	 *
	 * @param (MessageParam|string)[] ...$values
	 * @return MessageValue
	 */
	public function commaListParams( ...$values ) {
		return $this->listParamsOfType( ListType::COMMA, ...$values );
	}

	/**
	 * Chainable mutator which adds semicolon lists. Each semicolon list is an
	 * array of list elements, and each list element is either a MessageParam
	 * or a string. String parameters are converted to parameters of type
	 * "text".
	 *
	 * The list parameters thus created are formatted as a semicolon-separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|string)[] ...$values
	 * @return MessageValue
	 */
	public function semicolonListParams( ...$values ) {
		return $this->listParamsOfType( ListType::SEMICOLON, ...$values );
	}

	/**
	 * Chainable mutator which adds pipe lists. Each pipe list is an array of
	 * list elements, and each list element is either a MessageParam or a
	 * string. String parameters are converted to parameters of type "text".
	 *
	 * The list parameters thus created are formatted as a pipe ("|") -separated
	 * list, or some local equivalent.
	 *
	 * @param (MessageParam|string)[] ...$values
	 * @return MessageValue
	 */
	public function pipeListParams( ...$values ) {
		return $this->listParamsOfType( ListType::PIPE, ...$values );
	}

	/**
	 * Chainable mutator which adds text lists. Each text list is an array of
	 * list elements, and each list element is either a MessageParam or a
	 * string. String parameters are converted to parameters of type "text".
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
