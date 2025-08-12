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
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordError;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use Wikimedia\Assert\Assert;

/**
 * Basic framework for a primary authentication provider that uses passwords
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractPasswordPrimaryAuthenticationProvider
	extends AbstractPrimaryAuthenticationProvider
{
	/** @var bool Whether this provider should ABSTAIN (false) or FAIL (true) on password failure */
	protected $authoritative;

	/** @var PasswordFactory|null */
	private $passwordFactory = null;

	/**
	 * @stable to call
	 * @param array $params Settings
	 *  - authoritative: Whether this provider should ABSTAIN (false) or FAIL
	 *    (true) on password failure
	 */
	public function __construct( array $params = [] ) {
		$this->authoritative = !isset( $params['authoritative'] ) || (bool)$params['authoritative'];
	}

	/**
	 * @return PasswordFactory
	 */
	protected function getPasswordFactory() {
		if ( $this->passwordFactory === null ) {
			$this->passwordFactory = new PasswordFactory(
				$this->config->get( MainConfigNames::PasswordConfig ),
				$this->config->get( MainConfigNames::PasswordDefault )
			);
		}
		return $this->passwordFactory;
	}

	/**
	 * Get a Password object from the hash
	 * @param string $hash
	 * @return Password
	 */
	protected function getPassword( $hash ) {
		$passwordFactory = $this->getPasswordFactory();
		try {
			return $passwordFactory->newFromCiphertext( $hash );
		} catch ( PasswordError ) {
			$class = static::class;
			$this->logger->debug( "Invalid password hash in {$class}::getPassword()" );
			return $passwordFactory->newFromCiphertext( null );
		}
	}

	/**
	 * Return the appropriate response for failure
	 * @param PasswordAuthenticationRequest $req
	 * @return AuthenticationResponse
	 */
	protected function failResponse( PasswordAuthenticationRequest $req ) {
		if ( $this->authoritative ) {
			return AuthenticationResponse::newFail(
				wfMessage( $req->password === '' ? 'wrongpasswordempty' : 'wrongpassword' )
			);
		} else {
			return AuthenticationResponse::newAbstain();
		}
	}

	/**
	 * Check that the password is valid
	 *
	 * This should be called *before* validating the password. If the result is
	 * not ok, login should fail immediately.
	 *
	 * @param string $username
	 * @param string $password
	 * @return Status
	 */
	protected function checkPasswordValidity( $username, $password ) {
		return User::newFromName( $username )->checkPasswordValidity( $password );
	}

	/**
	 * Adds user-friendly description to a fatal password validity check error.
	 * These errors prevent login even when the password is correct, so just displaying the
	 * description of the error would be somewhat confusing.
	 * @param string $username
	 * @param Status $status The status returned by checkPasswordValidity(); must be a fatal.
	 * @return AuthenticationResponse A FAIL response with an improved description.
	 */
	protected function getFatalPasswordErrorResponse(
		string $username,
		Status $status
	): AuthenticationResponse {
		Assert::precondition( !$status->isOK(), __METHOD__ . ' expects a fatal Status' );
		$resetLinkUrl = SpecialPage::getTitleFor( 'PasswordReset' )
			->getFullURL( [ 'wpUsername' => $username ] );
		return AuthenticationResponse::newFail( wfMessage( 'fatalpassworderror',
			$status->getMessage(), $resetLinkUrl ) );
	}

	/**
	 * Check if the password should be reset
	 *
	 * This should be called after a successful login. It sets 'reset-pass'
	 * authentication data if necessary, see
	 * ResetPassSecondaryAuthenticationProvider.
	 *
	 * @param string $username
	 * @param Status $status From $this->checkPasswordValidity()
	 * @param \stdClass|null $data Passed through to $this->getPasswordResetData()
	 */
	protected function setPasswordResetFlag( $username, Status $status, $data = null ) {
		$reset = $this->getPasswordResetData( $username, $data );

		if ( !$reset && $this->config->get( MainConfigNames::InvalidPasswordReset ) &&
		!$status->isGood() ) {
			$hard = $status->getValue()['forceChange'] ?? false;

			if ( $hard || !empty( $status->getValue()['suggestChangeOnLogin'] ) ) {
				$reset = (object)[
					'msg' => $status->getMessage( $hard ? 'resetpass-validity' : 'resetpass-validity-soft' ),
					'hard' => $hard,
				];
			}
		}

		if ( $reset ) {
			$this->manager->setAuthenticationSessionData( 'reset-pass', $reset );
		}
	}

	/**
	 * Get password reset data, if any
	 *
	 * @stable to override
	 * @param string $username
	 * @param \stdClass|null $data
	 * @return \stdClass|null { 'hard' => bool, 'msg' => Message }
	 */
	protected function getPasswordResetData( $username, $data ) {
		return null;
	}

	/**
	 * Get expiration date for a new password, if any
	 *
	 * @stable to override
	 * @param string $username
	 * @return string|null
	 */
	protected function getNewPasswordExpiry( $username ) {
		$days = $this->config->get( MainConfigNames::PasswordExpirationDays );
		$expires = $days ? wfTimestamp( TS_MW, time() + $days * 86400 ) : null;

		// Give extensions a chance to force an expiration
		$this->getHookRunner()->onResetPasswordExpiration(
			User::newFromName( $username ), $expires );

		return $expires;
	}

	/**
	 * @stable to override
	 * @param string $action
	 * @param array $options
	 *
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
			case AuthManager::ACTION_REMOVE:
			case AuthManager::ACTION_CREATE:
			case AuthManager::ACTION_CHANGE:
				return [ new PasswordAuthenticationRequest() ];
			default:
				return [];
		}
	}
}
