<?php

namespace MediaWiki\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for array structures, typically
 * used for validating JSON request bodies.
 *
 * Failure codes:
 *  - 'notarray': The value is not an array.
 *
 * @todo implement validation based on a JSON schema
 *
 * @since 1.42
 */
class ArrayDef extends TypeDef {

	public function supportsArrays() {
		return true;
	}

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !is_array( $value ) ) {
			// Message used: paramvalidator-notarray
			$this->failure( 'notarray', $name, $value, $settings, $options );
		}

		return $value;
	}

}
