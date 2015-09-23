<?php

/**
 * A field that must contain a number
 */
class HTMLIntField extends HTMLFloatField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		# http://www.w3.org/TR/html5/infrastructure.html#signed-integers
		# with the addition that a leading '+' sign is ok. Note that leading zeros
		# are fine, and will be left in the input, which is useful for things like
		# phone numbers when you know that they are integers (the HTML5 type=tel
		# input does not require its value to be numeric).  If you want a tidier
		# value to, eg, save in the DB, clean it up with intval().
		if ( !preg_match( '/^((\+|\-)?\d+)?$/', trim( $value ) ) ) {
			return $this->msg( 'htmlform-int-invalid' )->parseAsBlock();
		}

		return true;
	}
}
