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
 * @since 1.26
 */
class AccountCreationThrottlePreAuthenticationProvider extends AbstractAuthenticationProvider implements PreAuthenticationProvider {

	/** @var int */
	protected $throttle = null;

	/** @var BagOStuff */
	protected $cache;

	/**
	 * @param array $params
	 *  - throttle: (int) Accounts per IP per day, defaults to $wgAccountCreationThrottle
	 *  - cache: (BagOStuff) Where to store the throttle, defaults to $wgMemc
	 */
	public function __construct( $params = array() ) {
		global $wgMemc;

		if ( isset( $params['throttle'] ) ) {
			$this->throttle = $params['throttle'];
		}
		$this->cache = isset( $params['cache'] ) ? $params['cache'] : $wgMemc;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) {
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
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return StatusValue
	 */
	public function testForAuthentication( array $reqs ) {
		return StatusValue::newGood();
	}

	/**
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$ip = $this->manager->getRequest()->getIP();

		if ( !Hooks::run( 'ExemptFromAccountCreationThrottle', array( $ip ) ) ) {
			$this->logger->debug( __METHOD__ . ": a hook allowed account creation w/o throttle\n" );
		} else {
			if ( $this->throttle && $creator->isPingLimitable() ) {
				$key = wfMemcKey( 'acctcreate', 'ip', $ip );
				$value = $this->cache->get( $key );
				if ( !$value ) {
					$this->cache->set( $key, 0, 86400 );
				}
				if ( $value >= $this->throttle ) {
					return StatusValue::newFatal( 'acct_creation_throttle_hit', $this->throttle );
				}
				$this->cache->incr( $key );
			}
		}
		return StatusValue::newGood();
	}

	/**
	 * @param User $user
	 * @return StatusValue
	 */
	public function testForAutoCreation( $user ) {
		return StatusValue::newGood();
	}

}
