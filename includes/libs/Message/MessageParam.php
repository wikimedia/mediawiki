<?php

namespace Wikimedia\Message;

use Wikimedia\JsonCodec\JsonCodecable;

/**
 * Value object representing a message parameter that consists of a list of values.
 *
 * Message parameter classes are pure value objects and are newable and (de)serializable.
 */
abstract class MessageParam implements JsonCodecable {

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
}
