<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * Type definition for boolean types.
 *
 *  BinaryBooleanDef behaves exactly like BooleanDef, except that it injects a
 *  false default so an omitted parameter resolves to false rather than null
 *  (hence "binary": either false or true, never null).
 *
 * Failure codes:
 *  - 'badbool': The value is not a recognized boolean. No data.
 *
 * @since 1.47
 */
class BinaryBooleanDef extends BooleanDef {

	/** @inheritDoc */
	public function normalizeSettings( array $settings ) {
		$settings += [ ParamValidator::PARAM_DEFAULT => false ];

		return parent::normalizeSettings(
			$settings
		);
	}
}
