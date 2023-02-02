<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserRigorOptions;
use MediaWiki\Widget\UsersMultiselectWidget;
use Wikimedia\IPUtils;

/**
 * Implements a tag multiselect input field for user names.
 *
 * Besides the parameters recognized by HTMLUserTextField, additional recognized
 * parameters are:
 *  default - (optional) Array of usernames to use as preset data
 *  placeholder - (optional) Custom placeholder message for input
 *
 * The result is the array of usernames
 *
 * @stable to extend
 * @note This widget is not likely to remain functional in non-OOUI forms.
 */
class HTMLUsersMultiselectField extends HTMLUserTextField {
	public function loadDataFromRequest( $request ) {
		$value = $request->getText( $this->mName, $this->getDefault() );

		$usersArray = explode( "\n", $value );
		// Remove empty lines
		$usersArray = array_values( array_filter( $usersArray, static function ( $username ) {
			return trim( $username ) !== '';
		} ) );

		// Normalize usernames
		$normalizedUsers = [];
		$userNameUtils = MediaWikiServices::getInstance()->getUserNameUtils();
		$listOfIps = [];
		foreach ( $usersArray as $user ) {
			$canonicalUser = false;
			if ( IPUtils::isIPAddress( $user ) ) {
				$parsedIPRange = IPUtils::parseRange( $user );
				if ( !in_array( $parsedIPRange, $listOfIps ) ) {
					$canonicalUser = IPUtils::sanitizeRange( $user );
					$listOfIps[] = $parsedIPRange;
				}
			} else {
				$canonicalUser = $userNameUtils->getCanonical(
					$user, UserRigorOptions::RIGOR_NONE );
			}
			if ( $canonicalUser !== false ) {
				$normalizedUsers[] = $canonicalUser;
			}
		}
		// Remove any duplicate usernames
		$uniqueUsers = array_unique( $normalizedUsers );

		// This function is expected to return a string
		return implode( "\n", $uniqueUsers );
	}

	public function validate( $value, $alldata ) {
		if ( !$this->mParams['exists'] ) {
			return true;
		}

		if ( $value === null ) {
			return false;
		}

		// $value is a string, because HTMLForm fields store their values as strings
		$usersArray = explode( "\n", $value );

		if ( isset( $this->mParams['max'] ) && ( count( $usersArray ) > $this->mParams['max'] ) ) {
			return $this->msg( 'htmlform-multiselect-toomany', $this->mParams['max'] );
		}

		foreach ( $usersArray as $username ) {
			$result = parent::validate( $username, $alldata );
			if ( $result !== true ) {
				return $result;
			}
		}

		return true;
	}

	public function getInputHTML( $value ) {
		$this->mParent->getOutput()->enableOOUI();
		return $this->getInputOOUI( $value );
	}

	public function getInputOOUI( $value ) {
		$params = [ 'name' => $this->mName ];

		if ( isset( $this->mParams['id'] ) ) {
			$params['id'] = $this->mParams['id'];
		}

		if ( isset( $this->mParams['disabled'] ) ) {
			$params['disabled'] = $this->mParams['disabled'];
		}

		if ( isset( $this->mParams['default'] ) ) {
			$params['default'] = $this->mParams['default'];
		}

		$params['placeholder'] = $this->mParams['placeholder'] ??
			$this->msg( 'mw-widgets-usersmultiselect-placeholder' )->plain();

		if ( isset( $this->mParams['max'] ) ) {
			$params['tagLimit'] = $this->mParams['max'];
		}

		if ( isset( $this->mParams['ipallowed'] ) ) {
			$params['ipAllowed'] = $this->mParams['ipallowed'];
		}

		if ( isset( $this->mParams['iprange'] ) ) {
			$params['ipRangeAllowed'] = $this->mParams['iprange'];
		}

		if ( isset( $this->mParams['iprangelimits'] ) ) {
			$params['ipRangeLimits'] = $this->mParams['iprangelimits'];
		}

		if ( isset( $this->mParams['input'] ) ) {
			$params['input'] = $this->mParams['input'];
		}

		if ( $value !== null ) {
			// $value is a string, but the widget expects an array
			$params['default'] = $value === '' ? [] : explode( "\n", $value );
		}

		// Make the field auto-infusable when it's used inside a legacy HTMLForm rather than OOUIHTMLForm
		$params['infusable'] = true;
		$params['classes'] = [ 'mw-htmlform-autoinfuse' ];
		$widget = new UsersMultiselectWidget( $params );
		$widget->setAttributes( [ 'data-mw-modules' => implode( ',', $this->getOOUIModules() ) ] );

		return $widget;
	}

	protected function shouldInfuseOOUI() {
		return true;
	}

	protected function getOOUIModules() {
		return [ 'mediawiki.widgets.UsersMultiselectWidget' ];
	}

}
