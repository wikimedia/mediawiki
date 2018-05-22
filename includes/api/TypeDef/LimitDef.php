<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use MediaWiki\Api\TypeDef;
use IContextSource;

/**
 * API type definition for limit types
 * @since 1.32
 * @ingroup API
 */
class LimitDef extends IntegerDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( $value === 'max' ) {
			if ( isset( $settings['parse-limit'] ) && !$settings['parse-limit'] ) {
				return $value;
			}
			$value = $module->getMain()->canApiHighLimits()
				? $settings[ApiBase::PARAM_MAX2]
				: $settings[ApiBase::PARAM_MAX];
			if ( !isset( $settings['set-parsed-limit'] ) || $settings['set-parsed-limit'] ) {
				$module->getResult()->addParsedLimit( $module->getModuleName(), $value );
			}
			return $value;
		}

		$settings += [
			ApiBase::PARAM_MIN => 0
		];

		return parent::validate( $name, $value, $settings, $module );
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$info = [];

		$settings += [
			ApiBase::PARAM_MIN => 0
		];

		if ( $settings[ApiBase::PARAM_MAX] !== $settings[ApiBase::PARAM_MAX2] ) {
			$info[] = $context->msg( 'api-help-param-limit2' )
				->numParams( $settings[ApiBase::PARAM_MAX] )
				->numParams( $settings[ApiBase::PARAM_MAX2] )
				->parse();
		} else {
			$info[] = $context->msg( 'api-help-param-limit' )
				->numParams( $settings[ApiBase::PARAM_MAX] )
				->parse();
		}

		return array_merge( $info, TypeDef::getHelpInfo( $context, $name, $settings, $module ) );
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $settings[ApiBase::PARAM_DFLT] ) && $settings[ApiBase::PARAM_DFLT] === 'max' ) {
			$info['default'] = 'max';
		}

		return $info;
	}

}
