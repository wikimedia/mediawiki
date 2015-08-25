<?php

use MediaWiki\Widget\UserInputWidget;

/**
 * Implements a text input field for user names.
 * Automatically auto-completes if using the OOUI display format.
 *
 * FIXME: Does not work for forms that support GET requests.
 *
 * Optional parameters:
 * 'exists' - Whether to validate that the user already exists
 *
 * @since 1.26
 */
class HTMLUserTextField extends HTMLTextField {
	public function __construct( $params ) {
		$params = wfArrayPlus2d( $params, array(
				'exists' => false,
				'ipallowed' => false,
				'iprange' => false,
				'iprangelimits' => array(
					'IPv4' => '16',
					'IPv6' => '32',
				),
			)
		);

		parent::__construct( $params );
	}

	public function validate( $value, $alldata ) {
		// check, if a user exists with the given username
		$user = User::newFromName( $value, false );
		$rangeError = null;

		if ( !is_array( $this->mParams['iprange'] ) ) {
			throw new UnexpectedValueException( 'Parameter iprange needs to be an array, ' .
				gettype( $this->mParams['iprange'] ) . ' given.' );
		}

		if ( !$user ) {
			return $this->msg( 'htmlform-user-not-valid', $value )->parse();
		} elseif (
			// check, if the user exists, if requested
			( $this->mParams['exists'] && $user->getId() === 0 ) &&
			// check, if the username is a valid IP address, otherweise save the error message
			!( $this->mParams['ipallowed'] && IP::isValid( $value ) ) &&
			// check, if the username is a valid IP range, otherwise save the error message
			!( $this->mParams['iprange']['allowed'] && ( $rangeError = $this->isValidIPRange( $value ) ) === true )
		) {
			if ( is_string( $rangeError ) ) {
				return $rangeError;
			}
			return $this->msg( 'htmlform-user-not-exists', $user->getName() )->parse();
		}

		return parent::validate( $value, $alldata );
	}

	protected function isValidIPRange( $value ) {
		$blockCIDRLimit = $this->mParams['iprangelimits'];

		if ( !IP::isValidBlock( $value ) ) {
			return false;
		}

		if ( $this->mParams['iprange']['blockable'] ) {
			list( $ip, $range ) = explode( '/', $value, 2 );

			if (
				( IP::isIPv4( $ip ) && $blockCIDRLimit['IPv4'] == 32 ) ||
				( IP::isIPv6( $ip ) && $blockCIDRLimit['IPv6'] == 128 )
			) {
				// Range block effectively disabled
				return $this->msg( 'range_block_disabled' )->parse();
			}

			if (
				( IP::isIPv4( $ip ) && $range > 32 ) ||
				( IP::isIPv6( $ip ) && $range > 128 )
			) {
				// Dodgy range
				return $this->msg( 'ip_range_invalid' )->parse();
			}

			if ( IP::isIPv4( $ip ) && $range < $blockCIDRLimit['IPv4'] ) {
				return $this->msg( 'ip_range_toolarge', $blockCIDRLimit['IPv4'] )->parse();
			}

			if ( IP::isIPv6( $ip ) && $range < $blockCIDRLimit['IPv6'] ) {
				return $this->msg( 'ip_range_toolarge', $blockCIDRLimit['IPv6'] )->parse();
			}
		}
		return true;
	}

	protected function getInputWidget( $params ) {
		$this->mParent->getOutput()->addModules( 'mediawiki.widgets' );

		return new UserInputWidget( $params );
	}
}
