<?php

namespace Wikimedia\Message;

/**
 * Value object representing a message parameter holding a single value.
 *
 * Message parameter classes are pure value objects and are safely newable.
 */
class ScalarParam extends MessageParam {
	/**
	 * Construct a text parameter
	 *
	 * @param string $type One of the ParamType constants.
	 * @param string|int|float|MessageValue $value
	 */
	public function __construct( $type, $value ) {
		$this->type = $type;
		$this->value = $value;
	}

	public function dump() {
		if ( $this->value instanceof MessageValue ) {
			$contents = $this->value->dump();
		} else {
			$contents = htmlspecialchars( $this->value );
		}
		return "<{$this->type}>" . $contents . "</{$this->type}>";
	}
}
