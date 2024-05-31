<?php

namespace Wikimedia\Message;

use InvalidArgumentException;
use Stringable;

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
	 * @param string|int|float|MessageValue|Stringable $value
	 *   Using Stringable objects is deprecated since 1.43.
	 */
	public function __construct( $type, $value ) {
		if ( $type === ParamType::LIST ) {
			throw new InvalidArgumentException(
				'ParamType::LIST cannot be used with ScalarParam; use ListParam instead'
			);
		}
		if ( $value instanceof Stringable ) {
			wfDeprecatedMsg( 'Passing Stringable objects to ScalarParam' .
				' was deprecated in MediaWiki 1.43', '1.43' );
		} elseif ( !is_string( $value ) && !is_numeric( $value ) &&
			!$value instanceof MessageValue ) {
			$type = is_object( $value ) ? get_class( $value ) : gettype( $value );
			throw new InvalidArgumentException(
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
			$contents = htmlspecialchars( (string)$this->value );
		}
		return "<{$this->type}>" . $contents . "</{$this->type}>";
	}
}
