<?php

namespace Wikimedia\Message;

/**
 * Value object representing a message parameter holding a single value.
 *
 * Message parameter classes are pure value objects and are safely newable.
 *
 * @newable
 */
class ScalarParam extends MessageParam {
	/**
	 * Construct a text parameter
	 *
	 * @stable to call.
	 *
	 * @param string $type One of the ParamType constants.
	 * @param string|int|float|MessageValue $value
	 */
	public function __construct( $type, $value ) {
		if ( $type === ParamType::LIST ) {
			throw new \InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( !is_string( $value ) && !is_numeric( $value ) && !$value instanceof MessageValue ) {
			$type = is_object( $value ) ? get_class( $value ) : gettype( $value );
			throw new \InvalidArgumentException(
				"Scalar parameter must be a string, number, or MessageValue; got $type"
			);
		}

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
