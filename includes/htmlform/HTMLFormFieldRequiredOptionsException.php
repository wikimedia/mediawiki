<?php

/**
 * @newable
 * @stable to extend
 */
class HTMLFormFieldRequiredOptionsException extends MWException {

	/**
	 * @stable to call
	 *
	 * @param HTMLFormField $field
	 * @param array $missing
	 */
	public function __construct( HTMLFormField $field, array $missing ) {
		parent::__construct( sprintf( "Form type `%s` expected the following parameters to be set: %s",
			get_class( $field ),
			implode( ', ', $missing ) ) );
	}
}
