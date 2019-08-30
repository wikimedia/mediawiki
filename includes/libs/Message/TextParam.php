<?php

namespace Wikimedia\Message;

class TextParam extends MessageParam {
	/**
	 * Construct a text parameter
	 *
	 * @param string $type May be one of:
	 *   - ParamType::TEXT: A simple text parameter
	 *   - ParamType::NUM: A number, to be formatted using local digits and
	 *     separators
	 *   - ParamType::DURATION_LONG: A number of seconds, to be formatted as natural
	 *     language text.
	 *   - ParamType::DURATION_SHORT: A number of seconds, to be formatted in an
	 *     abbreviated way.
	 *   - ParamType::EXPIRY: An expiry time for a block. The input is either
	 *     a timestamp in one of the formats accepted by the Wikimedia\Timestamp
	 *     library, or "infinity" for an infinite block.
	 *   - ParamType::SIZE: A number of bytes.
	 *   - ParamType::BITRATE: A number of bits per second.
	 *   - ParamType::RAW: A text parameter which is substituted after
	 *     preprocessing, and so is not available to the preprocessor and cannot
	 *     be modified by it.
	 *   - ParamType::PLAINTEXT: Reserved for future use.
	 *
	 * @param string|int|float $value
	 */
	public function __construct( $type, $value ) {
		$this->type = $type;
		$this->value = $value;
	}

	public function dump() {
		return "<{$this->type}>" . htmlspecialchars( $this->value ) . "</{$this->type}>";
	}
}
