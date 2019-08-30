<?php

namespace Wikimedia\Message;

/**
 * The base class for message parameters.
 */
abstract class MessageParam {
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
	 * @return int|float|string|array
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
