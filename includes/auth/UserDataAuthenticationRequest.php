<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
