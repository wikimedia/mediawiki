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
 * @ingroup Auth
 */

namespace MediaWiki\Auth;

use Config;
use StatusValue;
use User;

/**
 * Check if the user is blocked, and prevent authentication if so.
 *
 * @ingroup Auth
 * @since 1.27
 */
class CheckBlocksSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	/** @var bool */
	protected $blockDisablesLogin = null;

	/**
	 * @param array $params
	 *  - blockDisablesLogin: (bool) Whether blocked accounts can log in,
	 *    defaults to $wgBlockDisablesLogin
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['blockDisablesLogin'] ) ) {
			$this->blockDisablesLogin = (bool)$params['blockDisablesLogin'];
		}
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->blockDisablesLogin === null ) {
			$this->blockDisablesLogin = $this->config->get( 'BlockDisablesLogin' );
		}
	}

	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		if ( !$this->blockDisablesLogin ) {
			return AuthenticationResponse::newAbstain();
		} elseif ( $user->isBlocked() ) {
			return AuthenticationResponse::newFail(
				new \Message( 'login-userblocked', [ $user->getName() ] )
			);
		} else {
			return AuthenticationResponse::newPass();
		}
	}

	/**
	 * Test whether the user is blocked for account creation
	 * @param User $user
	 * @return StatusValue
	 */
	private function testCreationBlocked( $user ) {
		$block = $user->isBlockedFromCreateAccount();
		if ( $block ) {
			$errorParams = [
				$block->getTarget(),
				$block->mReason ? $block->mReason : \Message::newFromKey( 'blockednoreason' )->text(),
				$block->getByName()
			];

			if ( $block->getType() === \Block::TYPE_RANGE ) {
				$errorMessage = 'cantcreateaccount-range-text';
				$errorParams[] = $this->manager->getRequest()->getIP();
			} else {
				$errorMessage = 'cantcreateaccount-text';
			}

			return StatusValue::newFatal(
				new \Message( $errorMessage, $errorParams )
			);
		}

		$ip = $this->manager->getRequest()->getIP();
		if ( $user->isDnsBlacklisted( $ip, true /* check $wgProxyWhitelist */ ) ) {
			return StatusValue::newFatal( 'sorbs_create_account_reason' );
		}

		// Handling of permission-related hooks is in Title, even for non-page-related permissions.
		// Fake a title and reuse that - account creation checks should not depend on the page name.
		$title = \Title::newFromText( 'Special:CreateAccount' );
		$errors = $title->getUserPermissionsErrors( 'createaccount', $user, 'secure' );
		$sv = StatusValue::newGood();
		foreach ( $errors as $error ) {
			call_user_func_array( [ $sv, 'fatal' ], $error );
		}

		return $sv;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return $this->testCreationBlocked( $creator );
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	public function testUserForCreation( $user, $autocreate ) {
		return $this->testCreationBlocked( $user );
	}

}
