<?php

namespace MediaWiki\HTMLForm;

use InvalidArgumentException;

/**
 * @newable
 * @stable to extend
 */
class HTMLFormFieldRequiredOptionsException extends InvalidArgumentException {

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

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormFieldRequiredOptionsException::class, 'HTMLFormFieldRequiredOptionsException' );
