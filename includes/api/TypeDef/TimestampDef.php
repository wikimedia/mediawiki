<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use MediaWiki\Api\TypeDef;

/**
 * API type definition for timestamp types
 * @since 1.32
 * @ingroup API
 */
class TimestampDef extends TypeDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		// Confusing synonyms for the current time accepted by wfTimestamp()
		// (wfTimestamp() also accepts various non-strings and the string of 14
		// ASCII NUL bytes, but those can't get here)
		if ( !$value ) {
			$encName = $module->encodeParamName( $name );
			$module->addDeprecation(
				[ 'apiwarn-unclearnowtimestamp', $encName, wfEscapeWikiText( $value ) ],
				'unclear-"now"-timestamp'
			);
			return wfTimestamp( TS_MW );
		}

		// Explicit synonym for the current time
		if ( $value === 'now' ) {
			return wfTimestamp( TS_MW );
		}

		$timestamp = wfTimestamp( TS_MW, $value );
		if ( $timestamp === false ) {
			$encName = $module->encodeParamName( $name );
			$module->dieWithError(
				[ 'apierror-badtimestamp', $encName, wfEscapeWikiText( $value ) ],
				"badtimestamp_{$encName}"
			);
		}

		return $timestamp;
	}

	public function getParamInfo( $name, array $settings, ApiBase $module ) {
		$info = parent::getParamInfo( $name, $settings, $module );

		if ( isset( $info['default'] ) ) {
			$info['default'] = wfTimestamp( TS_ISO_8601, $info['default'] );
		}

		return $info;
	}

}
