<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for checkbox-like boolean types
 *
 * This boolean is considered true if the parameter is present in the request,
 * regardless of value. The only way for it to be false is for the parameter to
 * be omitted entirely.
 *
 * The result from validate() is a PHP boolean.
 *
 * @since 1.34
 * @unstable
 */
class PresenceBooleanDef extends TypeDef {

	public function getValue( $name, array $settings, array $options ) {
		return $this->callbacks->hasParam( $name, $options );
	}

	public function validate( $name, $value, array $settings, array $options ) {
		return (bool)$value;
	}

	public function describeSettings( $name, array $settings, array $options ) {
		$info = parent::describeSettings( $name, $settings, $options );
		unset( $info['default'] );
		return $info;
	}

}
