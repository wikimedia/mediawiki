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

	public function checkSettings( string $name, $settings, array $options, array $ret ) : array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		if ( ( $settings[ParamValidator::PARAM_SENSITIVE] ?? true ) !== true &&
			!isset( $ret['issues'][ParamValidator::PARAM_SENSITIVE] )
		) {
			$ret['issues'][ParamValidator::PARAM_SENSITIVE] =
				'Cannot set PARAM_SENSITIVE to false for password-type parameters';
		}

		return $ret;
	}

}
