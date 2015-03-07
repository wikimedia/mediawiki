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
 * A pre-authentication provider to throttle account creations
 * @ingroup Auth
 * @since 1.25
 */
class AccountCreationThrottlePreAuthenticationProvider extends AbstractAuthenticationProvider implements PreAuthenticationProvider {

	protected $throttle = null;

	/**
	 * @param array $params
	 */
	public function __construct( $params ) {
		if ( isset( $params['throttle'] ) ) {
			$this->throttle = $params['throttle'];
		}
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( $config ) {
		parent::setConfig( $config );

		if ( $this->throttle === null ) {
			$this->throttle = $this->config->get( 'AccountCreationThrottle' );
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
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return StatusValue
	 */
	public function testForAuthentication( AuthManager $manager, array $reqs ) {
		return Status::newGood();
	}

	/**
	 * @param AuthManager $manager
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return Status
	 */
	public function testForAccountCreation( AuthManager $manager, $user, array $reqs ) {
		global $wgMemc;

		$ip = $manager->getRequest()->getIP();

		if ( !Hooks::run( 'ExemptFromAccountCreationThrottle', array( $ip ) ) ) {
			$this->logger->debug( __METHOD__ . ": a hook allowed account creation w/o throttle\n" );
		} else {
			$currentUser = RequestContext::getMain()->getUser(); /// @todo: Does this make sense?
			if ( $this->throttle && $currentUser->isPingLimitable() ) {
				$key = wfMemcKey( 'acctcreate', 'ip', $ip );
				$value = $wgMemc->get( $key );
				if ( !$value ) {
					$wgMemc->set( $key, 0, 86400 );
				}
				if ( $value >= $this->throttle ) {
					return Status::newFatal( 'acct_creation_throttle_hit', $this->throttle );
				}
				$wgMemc->incr( $key );
			}
		}
		return Status::newGood();
	}

	/**
	 * @param AuthManager $manager
	 * @param User $user
	 * @return StatusValue
	 */
	public function testForAutoCreation( AuthManager $manager, $user ) {
		return Status::newGood();
	}

}
