<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\User\User;
use StatusValue;

/**
 * This represents additional user data requested on the account creation form
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class UserDataAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Email address */
	public $email;

	/** @var string|null Real name */
	public $realname;

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$ret = [
			'email' => [
				'type' => 'string',
				'label' => wfMessage( 'authmanager-email-label' ),
				'help' => wfMessage( 'authmanager-email-help' ),
				'optional' => true,
			],
			'realname' => [
				'type' => 'string',
				'label' => wfMessage( 'authmanager-realname-label' ),
				'help' => wfMessage( 'authmanager-realname-help' ),
				'optional' => true,
			],
		];

		if ( !$config->get( MainConfigNames::EnableEmail ) ) {
			unset( $ret['email'] );
		}

		if ( in_array( 'realname', $config->get( MainConfigNames::HiddenPrefs ), true ) ) {
			unset( $ret['realname'] );
		}

		return $ret;
	}

	/**
	 * Add data to the User object
	 * @stable to override
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @return StatusValue
	 */
	public function populateUser( $user ) {
		if ( $this->email !== null && $this->email !== '' ) {
			if ( !Sanitizer::validateEmail( $this->email ) ) {
				return StatusValue::newFatal( 'invalidemailaddress' );
			}
			$user->setEmail( $this->email );
		}
		if ( $this->realname !== null && $this->realname !== '' ) {
			$user->setRealName( $this->realname );
		}
		return StatusValue::newGood();
	}

}
