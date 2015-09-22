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

use BagOStuff;
use Config;

/**
 * A pre-authentication provider to throttle account creations
 * @ingroup Auth
 * @since 1.27
 */
class AccountCreationThrottlePreAuthenticationProvider extends AbstractPreAuthenticationProvider {
	/** @var int */
	protected $throttle;

	/** @var Throttler */
	protected $throttler;

	/** @var BagOStuff */
	protected $cache;

	/**
	 * @param array $params
	 *  - throttle: (int) Accounts per IP per day, defaults to $wgAccountCreationThrottle
	 */
	public function __construct( $params = [] ) {
		if ( isset( $params['throttle'] ) ) {
			$this->throttle = $params['throttle'];
		}
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		if ( $this->throttle === null ) {
			$this->throttle = $this->config->get( 'AccountCreationThrottle' );
		}

		$conditions = [[
			'count' => $this->throttle,
			'wait' => 86400,
		]];
		$this->throttler = Throttler::newFromConditions( $conditions, ['type' => 'acctcreate'] );
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$ip = $this->manager->getRequest()->getIP();

		if ( !\Hooks::run( 'ExemptFromAccountCreationThrottle', [ $ip ] ) ) {
			$this->logger->debug( __METHOD__ . ": a hook allowed account creation w/o throttle\n" );
		} else {
			if ( $this->throttle && $creator->isPingLimitable() ) {
				$result = $this->throttler->increase( null, $ip, __METHOD__ );
				if ( $result ) {
					return \StatusValue::newFatal( 'acct_creation_throttle_hit', $result['wait'] );
				}
			}
		}
		return \StatusValue::newGood();
	}
}
