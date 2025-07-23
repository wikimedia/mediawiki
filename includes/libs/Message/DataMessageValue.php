<?php

namespace Wikimedia\Message;

use Wikimedia\JsonCodec\JsonCodecableTrait;

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
 * DataMessageValues are pure value objects and are newable and (de)serializable.
 *
 * @newable
 */
class DataMessageValue extends MessageValue {
	use JsonCodecableTrait;

	private string $code;
	private ?array $data;

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
	public function __construct( string $key, array $params = [], ?string $code = null, ?array $data = null ) {
		parent::__construct( $key, $params );

		$this->code = $code ?? $key;
		$this->data = $data;
	}

	/**
	 * Static constructor for easier chaining of `->params()` methods
	 *
	 * @param string $key
	 * @param (MessageParam|MessageValue|string|int|float)[] $params
	 * @param string|null $code
	 * @param array|null $data
	 *
	 * @return DataMessageValue
	 */
	public static function new(
		string $key,
		array $params = [],
		?string $code = null,
		?array $data = null
	): DataMessageValue {
		return new DataMessageValue( $key, $params, $code, $data );
	}

	/**
	 * Get the message code
	 */
	public function getCode(): string {
		return $this->code;
	}

	/**
	 * Get the message's structured data
	 * @return array|null
	 */
	public function getData(): ?array {
		return $this->data;
	}

	public function dump(): string {
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

	public function isSameAs( MessageValue $mv ): bool {
		return parent::isSameAs( $mv ) &&
			$mv instanceof DataMessageValue &&
			$this->code === $mv->code &&
			$this->data === $mv->data;
	}

	public function toJsonArray(): array {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return parent::toJsonArray() + [
				'code' => $this->code,
				'data' => $this->data,
			];
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		// JsonStaticClassCodec invokes $className::jsonClassHintFor() so
		// we need to have an explicit definition in DataMessageValue, we
		// can't simply inherit the static method from MessageValue.
		return parent::jsonClassHintFor( $keyName );
	}

	public static function newFromJsonArray( array $json ): DataMessageValue {
		// WARNING: When changing how this class is serialized, follow the instructions
		// at <https://www.mediawiki.org/wiki/Manual:Parser_cache/Serialization_compatibility>!
		return new self( $json['key'], $json['params'], $json['code'], $json['data'] );
	}
}
