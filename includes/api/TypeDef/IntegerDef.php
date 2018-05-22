<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMessage;
use IContextSource;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for integer types
 * @since 1.32
 * @ingroup API
 */
class IntegerDef extends TypeDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( !is_int( $value ) && !preg_match( '/^[+-]?\d+$/', $value ) ) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError( [ 'apierror-badinteger', $encName, wfEscapeWikiText( $value ) ] );
		}
		$value = intval( $value );

		$min = isset( $settings[ApiBase::PARAM_MIN] ) ? $settings[ApiBase::PARAM_MIN] : null;
		$max = isset( $settings[ApiBase::PARAM_MAX] ) ? $settings[ApiBase::PARAM_MAX] : null;
		$botMax = isset( $settings[ApiBase::PARAM_MAX2] ) ? $settings[ApiBase::PARAM_MAX2] : null;
		$onFail = empty( $settings[ApiBase::PARAM_RANGE_ENFORCE] )
			? [ $module, 'addWarning' ]
			: [ $module, 'dieWithError' ];

		if ( $min !== null && $value < $min ) {
			$encName = $module->encodeParamName( $name );
			$msg = ApiMessage::create(
				[ 'apierror-integeroutofrange-belowminimum', $encName, $min, $value ],
				'integeroutofrange',
				[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
			);
			call_user_func( $onFail, $msg );
			$value = $min;
		}

		// Minimum is always validated, whereas maximum is checked only if not
		// running in internal call mode
		if ( !$module->getMain()->isInternalMode() ) {
			// Optimization: do not check user's bot status unless really needed -- skips db query
			// assumes $botMax >= $max
			if ( $max !== null && $value > $max ) {
				if ( $botMax !== null && $module->getMain()->canApiHighLimits() ) {
					if ( $value > $botMax ) {
						$encName = $module->encodeParamName( $name );
						$msg = ApiMessage::create(
							[ 'apierror-integeroutofrange-abovebotmax', $encName, $botMax, $value ],
							'integeroutofrange',
							[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
						);
						call_user_func( $onFail, $msg );
						$value = $botMax;
					}
				} else {
					$encName = $module->encodeParamName( $name );
					$msg = ApiMessage::create(
						[ 'apierror-integeroutofrange-abovemax', $encName, $max, $value ],
						'integeroutofrange',
						[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
					);
					call_user_func( $onFail, $msg );
					$value = $max;
				}
			}
		}

		return $value;
	}

	public function normalizeSettings( array $settings ) {
		// Ignore invalid values when not enforcing the range to mostly match
		// historical behavior.
		return parent::normalizeSettings( $settings + [
			ApiBase::PARAM_IGNORE_INVALID_VALUES => empty( $settings[ApiBase::PARAM_RANGE_ENFORCE] ),
		] );
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$info = [];

		// Possible messages: api-help-param-integer-min,
		// api-help-param-integer-max, api-help-param-integer-minmax
		$suffix = '';
		$min = $max = 0;
		if ( isset( $settings[ApiBase::PARAM_MIN] ) ) {
			$suffix .= 'min';
			$min = $settings[ApiBase::PARAM_MIN];
		}
		if ( isset( $settings[ApiBase::PARAM_MAX] ) ) {
			$suffix .= 'max';
			$max = $settings[ApiBase::PARAM_MAX];
		}
		if ( $suffix !== '' ) {
			$info[] = $context->msg( "api-help-param-integer-$suffix" )
				->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )
				->numParams( $min, $max )
				->parse();
		}

		return array_merge( $info, parent::getHelpInfo( $context, $name, $settings, $module ) );
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $info['default'] ) ) {
			$info['default'] = intval( $info['default'] );
		}

		return $info;
	}

}
