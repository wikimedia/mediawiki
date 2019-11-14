<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * Type definition for "password" types
 *
 * This is a string type that forces PARAM_SENSITIVE = true.
 *
 * @see StringDef
 * @since 1.34
 * @unstable
 */
class PasswordDef extends StringDef {

	public function normalizeSettings( array $settings ) {
		$settings[ParamValidator::PARAM_SENSITIVE] = true;
		return parent::normalizeSettings( $settings );
	}

}
