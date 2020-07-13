<?php

namespace Wikimedia\Message;

/**
 * Value object representing a message for i18n with alternative
 * machine-readable data.
 *
 * This augments a MessageValue with an additional machine-readable code and
 * structured data. The intended use is to facilitate error reporting in APIs.
 *
 * For example, a MessageValue reporting an "integer out of range" error might
 * use one of three message keys, depending on whether there is a minimum, a
 * maximum, or both. But an API would likely want to use one code for all three
 * cases, and would likely want the endpoints represented along the lines of
 * `[ 'min' => 1, 'max' => 10 ]` rather than
 * `[ 0 => new ScalarParam( ParamType::TEXT, 1 ), 1 => new ScalarParam( ParamType::TEXT, 10 ) ]`.
 *
 * DataMessageValues are pure value objects and are safely newable.
 *
 * @newable
 */
class DataMessageValue extends MessageValue {
	/** @var string */
	private $code;

	/** @var array|null */
	private $data;

	/**
	 * @stable to call
	 *
	 * @param string $key
	 * @param (MessageParam|MessageValue|string|int|float)[] $params
	 * @param string|null $code String representing the concept behind
	 *  this message.
	 * @param array|null $data Structured data representing the concept
	 *  behind this message.
	 */
	public function __construct( $key, $params = [], $code = null, array $data = null ) {
		parent::__construct( $key, $params );

		$this->code = $code ?? $key;
		$this->data = $data;
	}

	/**
	 * Static constructor for easier chaining of `->params()` methods
	 * @param string $key
	 * @param (MessageParam|MessageValue|string|int|float)[] $params
	 * @param string|null $code
	 * @param array|null $data
	 * @return DataMessageValue
	 */
	public static function new( $key, $params = [], $code = null, array $data = null ) {
		return new DataMessageValue( $key, $params, $code, $data );
	}

	/**
	 * Get the message code
	 * @return string
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Get the message's structured data
	 * @return array|null
	 */
	public function getData() {
		return $this->data;
	}

	public function dump() {
		$contents = '';
		if ( $this->getParams() ) {
			$contents = '<params>';
			foreach ( $this->getParams() as $param ) {
				$contents .= $param->dump();
			}
			$contents .= '</params>';
		}

		if ( $this->data !== null ) {
			$contents .= '<data>' . htmlspecialchars( json_encode( $this->data ), ENT_NOQUOTES ) . '</data>';
		}

		return '<datamessage key="' . htmlspecialchars( $this->getKey() ) . '"'
			. ' code="' . htmlspecialchars( $this->code ) . '">'
			. $contents
			. '</datamessage>';
	}
}
