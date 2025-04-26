<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\MediaWikiServices;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\Widget\UserInputWidget;
use Wikimedia\IPUtils;

/**
 * Implements a text input field for user names.
 * Automatically auto-completes if using the OOUI display format.
 *
 * Optional parameters:
 * 'exists' - Whether to validate that the user already exists
 * 'external' - Whether an external user (imported actor) is interpreted as "valid"
 * 'ipallowed' - Whether an IP address is interpreted as "valid"
 * 'usemodwiki-ipallowed' - Whether an IP address in the usemod wiki format (e.g. 300.300.300.xxx) is accepted. The
 *    'ipallowed' parameter must be set to true if this parameter is set to true.
 * 'iprange' - Whether an IP address range is interpreted as "valid"
 * 'iprangelimits' - Specifies the valid IP ranges for IPv4 and IPv6 in an array.
 * 'excludenamed' - Whether to exclude named users or not.
 * 'excludetemp' - Whether to exclude temporary users or not.
 *
 * @stable to extend
 * @since 1.26
 */
class HTMLUserTextField extends HTMLTextField {
	/**
	 * @stable to call
	 * @inheritDoc
	 */
	public function __construct( $params ) {
		$params = wfArrayPlus2d( $params, [
				'exists' => false,
				'external' => false,
				'ipallowed' => false,
				'usemodwiki-ipallowed' => false,
				'iprange' => false,
				'iprangelimits' => [
					'IPv4' => 0,
					'IPv6' => 0,
				],
				'excludenamed' => false,
				'excludetemp' => false,
			]
		);

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// If the value is null, reset it to an empty string which is what is expected by the parent.
		$value ??= '';

		// If the value is empty, there are no additional checks that can be performed.
		if ( $value === '' ) {
			return parent::validate( $value, $alldata );
		}

		// check if the input is a valid username
		$user = MediaWikiServices::getInstance()->getUserFactory()->newFromName( $value );
		if ( $user ) {
			// check if the user exists, if requested
			if ( $this->mParams['exists'] && !(
				$user->isRegistered() &&
				// Treat hidden users as unregistered if current user can't view them (T309894)
				!( $user->isHidden() && !( $this->mParent && $this->mParent->getUser()->isAllowed( 'hideuser' ) ) )
			) ) {
				return $this->msg( 'htmlform-user-not-exists', wfEscapeWikiText( $user->getName() ) );
			}

			// check if the user account type matches the account type filter
			$excludeNamed = $this->mParams['excludenamed'] ?? null;
			$excludeTemp = $this->mParams['excludetemp'] ?? null;
			if ( ( $excludeTemp && $user->isTemp() ) || ( $excludeNamed && $user->isNamed() ) ) {
				return $this->msg( 'htmlform-user-not-valid', wfEscapeWikiText( $user->getName() ) );
			}
		} else {
			// not a valid username
			$valid = false;
			// check if the input is a valid external user
			if ( $this->mParams['external'] && ExternalUserNames::isExternal( $value ) ) {
				$valid = true;
			}
			// check if the input is a valid IP address, optionally also checking for usemod wiki IPs
			if ( $this->mParams['ipallowed'] ) {
				$b = IPUtils::RE_IP_BYTE;
				if ( IPUtils::isValid( $value ) ) {
					$valid = true;
				} elseif ( $this->mParams['usemodwiki-ipallowed'] && preg_match( "/^$b\.$b\.$b\.xxx$/", $value ) ) {
					$valid = true;
				}
			}
			// check if the input is a valid IP range
			if ( $this->mParams['iprange'] ) {
				$rangeError = $this->isValidIPRange( $value );
				if ( $rangeError === true ) {
					$valid = true;
				} elseif ( $rangeError !== false ) {
					return $rangeError;
				}
			}
			if ( !$valid ) {
				return $this->msg( 'htmlform-user-not-valid', wfEscapeWikiText( $value ) );
			}
		}

		return parent::validate( $value, $alldata );
	}

	protected function isValidIPRange( $value ) {
		$cidrIPRanges = $this->mParams['iprangelimits'];

		if ( !IPUtils::isValidRange( $value ) ) {
			return false;
		}

		[ $ip, $range ] = explode( '/', $value, 2 );

		if (
			( IPUtils::isIPv4( $ip ) && $cidrIPRanges['IPv4'] == 32 ) ||
			( IPUtils::isIPv6( $ip ) && $cidrIPRanges['IPv6'] == 128 )
		) {
			// Range block effectively disabled
			return $this->msg( 'ip_range_toolow' );
		}

		if (
			( IPUtils::isIPv4( $ip ) && $range > 32 ) ||
			( IPUtils::isIPv6( $ip ) && $range > 128 )
		) {
			// Dodgy range
			return $this->msg( 'ip_range_invalid' );
		}

		if ( IPUtils::isIPv4( $ip ) && $range < $cidrIPRanges['IPv4'] ) {
			return $this->msg( 'ip_range_exceeded', $cidrIPRanges['IPv4'] );
		}

		if ( IPUtils::isIPv6( $ip ) && $range < $cidrIPRanges['IPv6'] ) {
			return $this->msg( 'ip_range_exceeded', $cidrIPRanges['IPv6'] );
		}

		return true;
	}

	protected function getInputWidget( $params ) {
		if ( isset( $this->mParams['excludenamed'] ) ) {
			$params['excludenamed'] = $this->mParams['excludenamed'];
		}

		if ( isset( $this->mParams['excludetemp'] ) ) {
			$params['excludetemp'] = $this->mParams['excludetemp'];
		}

		return new UserInputWidget( $params );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.UserInputWidget' ];
	}

	public function getInputHtml( $value ) {
		// add the required module and css class for user suggestions in non-OOUI mode
		$this->mParent->getOutput()->addModules( 'mediawiki.userSuggest' );
		$this->mClass .= ' mw-autocomplete-user';

		// return parent html
		return parent::getInputHTML( $value );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLUserTextField::class, 'HTMLUserTextField' );
