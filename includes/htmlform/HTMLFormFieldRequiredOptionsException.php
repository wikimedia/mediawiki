<?php

class HTMLFormFieldRequiredOptionsException extends MWException {
	public function __construct( HTMLFormField $field, array $missing ) {
		parent::__construct( sprintf( "Form type `%s` expected the following parameters to be set: %s",
			get_class( $field ),
			implode( ', ', $missing ) ) );
	}
}
