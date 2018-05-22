<?php

namespace MediaWiki\Api;

use ApiBase;
use IContextSource;

/**
 * Base definition for an API type.
 *
 * @see ApiStructureTest for validation of PARAM_* constant restrictions.
 * @since 1.32
 * @ingroup API
 */
abstract class TypeDef {

	/**
	 * Get the value from the request
	 * @note Only override this if you need to use something other than
	 *  $module->getMain()->getVal() to fetch the value. Reformatting from a
	 *  string should typically be done by self::validate().
	 * @param string $name Name being fetched
	 * @param array $settings Settings array
	 * @param ApiBase $module Module being gotten
	 * @return null|mixed Return null if the value wasn't present, otherwise a
	 *  value to be passed to self::validate().
	 */
	public function get( $name, array $settings, ApiBase $module ) {
		$encName = $module->encodeParamName( $name );
		$value = $module->getMain()->getVal( $encName, null );

		if ( $value !== null ) {
			$request = $module->getMain()->getRequest();
			$rawValue = $request->getRawVal( $encName, null );

			// Preserve U+001F for multi-value handling, or error out if that won't be called
			if ( $rawValue !== null && substr( $rawValue, 0, 1 ) === "\x1f" ) {
				if ( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ) {
					// This loses the potential $wgContLang->checkTitleEncoding() transformation
					// done by WebRequest for $_GET. Let's call that a feature.
					$value = implode( "\x1f", $request->normalizeUnicode( explode( "\x1f", $rawValue ) ) );
				} else {
					$module->dieWithError( 'apierror-badvalue-notmultivalue', 'badvalue_notmultivalue' );
				}
			}

			// Check for NFC normalization, and call the handler if it happened
			if ( $rawValue !== $value ) {
				$module->handleParamNormalization( $name, $value, $rawValue );
			}
		}

		return $value;
	}

	/**
	 * Validate the value
	 * @param string $name Parameter name being validated
	 * @param string $value Value to validate
	 * @param array $settings Parameter settings array. The array may contain certain extra keys:
	 *  - parse-limit: Boolean, the value of $parseLimit passed to ParamValidator::validateValue().
	 *  - set-parsed-limit: Boolean, default true. If the parsed limit should be set in the ApiResult.
	 *  - values-list: Array. If defined, values of a multi-valued parameter are being processed
	 *    (and this array holds the full set of values).
	 * @param ApiBase $module Module having the parameter
	 * @return mixed Validated value
	 * @throws ApiUsageException if the value is invalid
	 */
	abstract public function validate( $name, $value, array $settings, ApiBase $module );

	/**
	 * Get info messages for the help
	 * @param IContextSource $context
	 * @param string $name Parameter name being validated
	 * @param array $settings Parameter settings array
	 * @param ApiBase $module Module having the parameter
	 * @return string[] HTML
	 */
	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		$info = [];

		// In core, this uses the following messages: api-help-param-type-limit
		// api-help-param-type-integer api-help-param-type-boolean
		// api-help-param-type-timestamp api-help-param-type-user
		// api-help-param-type-password
		$msg = $context->msg( 'api-help-param-type-' . $settings[ApiBase::PARAM_TYPE] );
		if ( !$msg->isDisabled() ) {
			$info[] = $msg->params( !empty( $settings[ApiBase::PARAM_ISMULTI] ) ? 2 : 1 )->parse();
		}
		return $info;
	}

	/**
	 * Indicate whether api-help-param-multi-separate is needed for this type
	 * @return bool
	 */
	public function needsHelpParamMultiSeparate() {
		return true;
	}

	/**
	 * Normalize a settings array
	 * @param array $settings
	 * @return array
	 */
	public function normalizeSettings( array $settings ) {
		return $settings;
	}

	/**
	 * Output data for ApiParamInfo
	 * @param string $name Parameter name being validated
	 * @param array $settings
	 * @param ApiBase $module Module having the parameter
	 * @return array
	 */
	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = [];

		if ( isset( $settings[ApiBase::PARAM_DFLT] ) ) {
			$info['default'] = $settings[ApiBase::PARAM_DFLT];
		}

		return $info;
	}

	/**
	 * Get the options for enum-like parameters
	 * @param string $name Parameter name being validated
	 * @param array $settings
	 * @param ApiBase $module Module having the parameter
	 * @return array|null
	 */
	public function getEnumValues( $name, array $settings, ApiBase $module ) {
		return null;
	}

}
