<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use IContextSource;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for string types
 * @since 1.32
 * @ingroup API
 */
class StringDef extends TypeDef {

	protected $allowEmptyWhenRequired = false;

	/**
	 * @param array $options Options:
	 *  - allowEmptyWhenRequired: (bool) Whether to reject empty values when PARAM_REQUIRED
	 */
	public function __construct( array $options = [] ) {
		$this->allowEmptyWhenRequired = !empty( $options['allowEmptyWhenRequired'] );
	}

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( !$this->allowEmptyWhenRequired && $value === '' &&
			!empty( $settings[ApiBase::PARAM_REQUIRED] )
		) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError( [ 'apierror-missingparam', $encName ] );
		}

		if ( isset( $settings[ApiBase::PARAM_MAX_BYTES] )
			&& strlen( $value ) > $settings[ApiBase::PARAM_MAX_BYTES]
		) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError( [ 'apierror-maxbytes', $encName, $settings[ApiBase::PARAM_MAX_BYTES] ] );
		}
		if ( isset( $settings[ApiBase::PARAM_MAX_CHARS] )
			&& mb_strlen( $value, 'UTF-8' ) > $settings[ApiBase::PARAM_MAX_CHARS]
		) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError( [ 'apierror-maxchars', $encName, $settings[ApiBase::PARAM_MAX_CHARS] ] );
		}

		return $value;
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		return [];
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $info['default'] ) ) {
			$info['default'] = strval( $info['default'] );
		}

		return $info;
	}

}
