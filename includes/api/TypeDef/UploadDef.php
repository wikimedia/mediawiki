<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use IContextSource;
use MediaWiki\Api\TypeDef;
use WebRequestUpload;

/**
 * API type definition for upload types
 * @since 1.32
 * @ingroup API
 */
class UploadDef extends TypeDef {

	public function get( $name, array $settings, ApiBase $module ) {
		$encName = $module->encodeParamName( $name );
		$value = $module->getMain()->getUpload( $encName );
		if ( !$value->exists() && !$module->getMain()->getRequest()->getCheck( $encName ) ) {
			return null;
		}
		return $value;
	}

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( !$value instanceof WebRequestUpload || !$value->exists() ) {
			// This will get the value without trying to normalize it
			// (because trying to normalize a large binary file
			// accidentally uploaded as a field fails spectacularly)
			$encName = $module->encodeParamName( $name );
			$value = $module->getMain()->getRequest()->unsetVal( $encName );
			if ( $value !== null ) {
				$module->dieWithError(
					[ 'apierror-badupload', $encName ],
					"badupload_{$encName}"
				);
			}
		}
		return $value;
	}

	public function getHelpInfo( IContextSource $context, $name, array $settings, ApiBase $module ) {
		return [ $context->msg( 'api-help-param-upload' )->parse() ];
	}

}
