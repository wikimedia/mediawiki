<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ExternalUserNames;
use IP;
use MediaWiki\Api\TypeDef;
use User;

/**
 * API type definition for user types
 * @since 1.32
 * @ingroup API
 */
class UserDef extends TypeDef {

	public function validate( $name, $value, array $settings, ApiBase $module ) {
		if ( ExternalUserNames::isExternal( $value ) && User::newFromName( $value, false ) ) {
			return $value;
		}

		$username = User::getCanonicalName( $value, 'valid' );
		if ( $username !== false ) {
			return $username;
		}

		if (
			// We allow ranges as well, for blocks.
			IP::isIPAddress( $value ) ||
			// See comment for User::isIP.  We don't just call that function
			// here because it also returns true for things like
			// 300.300.300.300 that are neither valid usernames nor valid IP
			// addresses.
			preg_match(
				'/^' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.xxx$/',
				$value
			)
		) {
			return IP::sanitizeIP( $value );
		}

		$encName = $module->encodeParamName( $name );
		$module->dieWithError(
			[ 'apierror-baduser', $encName, wfEscapeWikiText( $value ) ],
			"baduser_{$encName}"
		);
	}

}
