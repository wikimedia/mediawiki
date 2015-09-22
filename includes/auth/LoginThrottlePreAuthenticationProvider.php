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

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		\Hooks::register( 'AuthManagerLoginAuthenticateAudit',
			[ $this, 'onAuthManagerLoginAuthenticateAudit' ] );
	}

	public function testForAuthentication( array $reqs ) {
		$ip = $this->manager->getRequest()->getIP();
		try {
			$username = AuthenticationRequest::getUsernameFromRequests( $reqs );
		} catch ( \UnexpectedValueException $e ) {
			$username = '';
		}

		$result = $this->throttler->increase( $username, $ip, __METHOD__ );

		if ( $result ) {
			$message = wfMessage( 'login-throttled' )->durationParams( $result['wait'] );
			return \StatusValue::newFatal( $message );
		} else {
			$this->manager->setAuthenticationSessionData( 'LoginThrottle',
				['user' => $username, 'ip' => $ip] );
			return \StatusValue::newGood();
		}
	}

	/**
	 * FIXME bit of a hack. Maybe preauth providers should have a callback for success?
	 * @param AuthenticationResponse $ret
	 * @param \User $user
	 * @param string $localUsername
	 */
	public function onAuthManagerLoginAuthenticateAudit( $ret, $user, $localUsername ) {
		if ( $ret->status !== AuthenticationResponse::PASS ) {
			return;
		}

		$data = $this->manager->getAuthenticationSessionData( 'LoginThrottle' );
		if ( !$data ) {
			$this->logger->error( 'throttler data not found for {user}', ['user' => $localUsername] );
			return;
		}

		$this->throttler->clear( $data['user'], $data['ip'] );
	}
}
