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

/**
 * Check if the user is blocked, and prevent authentication if so.
 *
 * @ingroup Auth
 * @since 1.26
 */
class CheckBlocksSecondaryAuthenticationProvider extends AbstractAuthenticationProvider implements SecondaryAuthenticationProvider {

	/** @var bool */
	protected $blockDisablesLogin = null;

	/**
	 * @param array $params
	 *  - blockDisablesLogin: (bool) Whether blocked accounts can log in,
	 *    defaults to $wgBlockDisablesLogin
	 */
	public function __construct( $params = array() ) {
		if ( isset( $params['blockDisablesLogin'] ) ) {
			$this->blockDisablesLogin = (bool)$params['blockDisablesLogin'];
		}
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->blockDisablesLogin === null ) {
			$this->blockDisablesLogin = $this->config->get( 'BlockDisablesLogin' );
		}
	}

	/**
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		return array();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		if ( !$this->blockDisablesLogin ) {
			return AuthenticationResponse::newAbstain();
		} elseif ( $user->isBlocked() ) {
			return AuthenticationResponse::newFail(
				new Message( 'login-userblocked', array( $user->getName() ) )
			);
		} else {
			return AuthenticationResponse::newPass();
		}
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' should never be reached.' );
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowPropertyChange( $property ) {
		return true;
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerCanChangeAuthenticationData( AuthenticationRequest $req ) {
		return StatusValue::newGood( 'ignored' );
	}

	/**
	 * @param User $user
	 * @return StatusValue
	 */
	private function testCreationBlocked( $user ) {
		$block = $user->isBlockedFromCreateAccount();
		if ( $block ) {
			$errorParams = array(
				$block->getTarget(),
				$block->mReason ? $block->mReason : Message::newFromKey( 'blockednoreason' )->text(),
				$block->getByName()
			);

			if ( $block->getType() === Block::TYPE_RANGE ) {
				$errorMessage = 'cantcreateaccount-range-text';
				$errorParams[] = $this->manager->getRequest()->getIP();
			} else {
				$errorMessage = 'cantcreateaccount-text';
			}

			return StatusValue::newFatal(
				new Message( $errorMessage, $errorParams )
			);
		} else {
			return StatusValue::newGood();
		}
	}

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return $this->testCreationBlocked( $creator );
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * @param User $user
	 * @return StatusValue
	 */
	public function testForAutoCreation( $user ) {
		return $this->testCreationBlocked( $user );
	}

	/**
	 * @param User $user
	 */
	public function autoCreatedAccount( $user ) {
	}

}
