<?php

use MediaWiki\Widget\UserInputWidget;
use Wikimedia\IPUtils;

/**
 * Implements a text input field for user names.
 * Automatically auto-completes if using the OOUI display format.
 *
 * Optional parameters:
 * 'exists' - Whether to validate that the user already exists
 * 'ipallowed' - Whether an IP adress is interpreted as "valid"
 * 'iprange' - Whether an IP adress range is interpreted as "valid"
 * 'iprangelimits' - Specifies the valid IP ranges for IPv4 and IPv6 in an array.
 *  defaults to IPv4 => 16; IPv6 => 32.
 *
 * @stable to extend
 * @since 1.26
 */
class HTMLUserTextField extends HTMLTextField {
	/*
	 * @stable to call
	 */
	public function __construct( $params ) {
		$params = wfArrayPlus2d( $params, [
				'exists' => false,
				'ipallowed' => false,
				'iprange' => false,
				'iprangelimits' => [
					'IPv4' => '16',
					'IPv6' => '32',
				],
			]
		);

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// If the value is null, reset it to an empty string which is what is expected by the parent.
		if ( $value === null ) {
			$value = '';
		}

		// If the value is empty, there are no additional checks that can be performed.
		if ( $value === '' ) {
			return parent::validate( $value, $alldata );
		}

		// check, if a user exists with the given username
		$user = User::newFromName( $value, false );
		$rangeError = null;

		if ( !$user ) {
			return $this->msg( 'htmlform-user-not-valid', $value );
		} elseif (
			// check, if the user exists, if requested
			( $this->mParams['exists'] && $user->getId() === 0 ) &&
			// check, if the username is a valid IP address, otherwise save the error message
			!( $this->mParams['ipallowed'] && IPUtils::isValid( $value ) ) &&
			// check, if the username is a valid IP range, otherwise save the error message
			!( $this->mParams['iprange'] && ( $rangeError = $this->isValidIPRange( $value ) ) === true )
		) {
			if ( is_string( $rangeError ) ) {
				return $rangeError;
			}
			return $this->msg( 'htmlform-user-not-exists', $user->getName() );
		}

		return parent::validate( $value, $alldata );
	}

	protected function isValidIPRange( $value ) {
		$cidrIPRanges = $this->mParams['iprangelimits'];

		if ( !IPUtils::isValidRange( $value ) ) {
			return false;
		}

		list( $ip, $range ) = explode( '/', $value, 2 );

		if (
			( IPUtils::isIPv4( $ip ) && $cidrIPRanges['IPv4'] == 32 ) ||
			( IPUtils::isIPv6( $ip ) && $cidrIPRanges['IPv6'] == 128 )
		) {
			// Range block effectively disabled
			return $this->msg( 'ip_range_toolow' )->parse();
		}

		if (
			( IPUtils::isIPv4( $ip ) && $range > 32 ) ||
			( IPUtils::isIPv6( $ip ) && $range > 128 )
		) {
			// Dodgy range
			return $this->msg( 'ip_range_invalid' )->parse();
		}

		if ( IPUtils::isIPv4( $ip ) && $range < $cidrIPRanges['IPv4'] ) {
			return $this->msg( 'ip_range_exceeded', $cidrIPRanges['IPv4'] )->parse();
		}

		if ( IPUtils::isIPv6( $ip ) && $range < $cidrIPRanges['IPv6'] ) {
			return $this->msg( 'ip_range_exceeded', $cidrIPRanges['IPv6'] )->parse();
		}

		return true;
	}

	protected function getInputWidget( $params ) {
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
