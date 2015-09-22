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

class LoginThrottlePreAuthenticationProvider extends AbstractPreAuthenticationProvider {

	/** @var Throttler */
	protected $throttler;

	public function __construct() {
		$this->throttler = Throttler::getInstance();
	}

	public function testForAuthentication( array $reqs ) {
		$ip = $this->manager->getRequest()->getIP();
		try {
			$username = AuthenticationRequest::getUsernameFromRequests( $reqs );
		} catch ( \UnexpectedValueException $e ) {
			$username = '';
		}

		// $username is not necessarily the wiki username. This might create a vulnerability
		// in the unlikely case of some authentication provider allowing a huge number of different
		// usernames to refer to the same wiki account. Nothing can be done about that at this
		// level, though, since we don't learn the wiki username for failed authentication attempts;
		// such providers would have to do their own throttling.
		$result = $this->throttler->increase( $username, $ip, __METHOD__ );

		if ( $result ) {
			$message = wfMessage( 'login-throttled' )->durationParams( $result['wait'] );
			return \StatusValue::newFatal( $message );
		} else {
			$this->manager->setAuthenticationSessionData( 'LoginThrottle',
				[ 'user' => $username, 'ip' => $ip ] );
			return \StatusValue::newGood();
		}
	}

	/**
	 * @param null|\User $user
	 * @param AuthenticationResponse $response
	 */
	public function postAuthentication( $user, AuthenticationResponse $response ) {
		if ( $response->status !== AuthenticationResponse::PASS ) {
			return;
		}

		$data = $this->manager->getAuthenticationSessionData( 'LoginThrottle' );
		if ( !$data ) {
			$this->logger->error( 'throttler data not found for {user}', [ 'user' => $user->getName() ] );
			return;
		}

		$this->throttler->clear( $data['user'], $data['ip'] );
	}
}
