<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use IContextSource;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for password types
 * @since 1.32
 * @ingroup API
 */
class PasswordDef extends StringDef {

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		return TypeDef::getHelpInfo( $context, $name, $settings, $module );
	}

	public function normalizeSettings( array $settings ) {
		// Force PARAM_SENSITIVE for 'password' types
		$settings[ApiBase::PARAM_SENSITIVE] = true;
		return parent::normalizeSettings( $settings );
	}

}
