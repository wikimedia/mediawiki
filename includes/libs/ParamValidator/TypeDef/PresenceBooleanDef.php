<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
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
		return $this->callbacks->hasParam( $name, $options ) ? true : null;
	}

	public function validate( $name, $value, array $settings, array $options ) {
		return (bool)$value;
	}

	public function normalizeSettings( array $settings ) {
		// Cannot be multi-valued
		$settings[ParamValidator::PARAM_ISMULTI] = false;

		// Default the default to false so ParamValidator::getValue() returns false (T244440)
		$settings += [ ParamValidator::PARAM_DEFAULT => false ];

		return parent::normalizeSettings( $settings );
	}

	public function checkSettings( string $name, $settings, array $options, array $ret ) : array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			!isset( $ret['issues'][ParamValidator::PARAM_ISMULTI] )
		) {
			$ret['issues'][ParamValidator::PARAM_ISMULTI] =
				'PARAM_ISMULTI cannot be used for presence-boolean-type parameters';
		}

		if ( ( $settings[ParamValidator::PARAM_DEFAULT] ?? false ) !== false &&
			!isset( $ret['issues'][ParamValidator::PARAM_DEFAULT] )
		) {
			$ret['issues'][ParamValidator::PARAM_DEFAULT] =
				'Default for presence-boolean-type parameters must be false or null';
		}

		return $ret;
	}

	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		// No need to report the default of "false"
		$info['default'] = null;

		return $info;
	}

	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new(
			'paramvalidator-help-type-presenceboolean'
		)->params( 1 );

		// No need to report the default of "false"
		$info[ParamValidator::PARAM_DEFAULT] = null;

		return $info;
	}

}
