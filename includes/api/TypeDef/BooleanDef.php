<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for boolean types
 * @since 1.32
 * @ingroup API
 */
class BooleanDef extends TypeDef {

	public function get( $name, array $settings, ApiBase $module ) {
		$encName = $module->encodeParamName( $name );
		return $module->getMain()->getCheck( $encName ) ? true : null;
	}

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		return (bool)$value;
	}

	public function normalizeSettings( array $settings ) {
		// Ensure a default is set
		return parent::normalizeSettings( $settings + [ ApiBase::PARAM_DFLT => false ] );
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $info['default'] ) ) {
			$info['default'] = (bool)$info['default'];
		}

		return $info;
	}

}
