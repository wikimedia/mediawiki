<?php

namespace Wikimedia\Message;

use MediaWiki\Json\JsonDeserializable;
use MediaWiki\Json\JsonDeserializableTrait;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 */
abstract class MessageParam implements JsonDeserializable {
	use JsonDeserializableTrait;

	protected $type;
	protected $value;

	/**
	 * Get the type of the parameter.
	 *
	 * @return string One of the ParamType constants
	 */
	public function getType() {
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
	abstract public function dump();
}
