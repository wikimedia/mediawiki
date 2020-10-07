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
 * A pre-authentication provider to throttle authentication actions.
 *
 * Adding this provider will throttle account creations and primary authentication attempts
 * (more specifically, any authentication that returns FAIL on failure). Secondary authentication
 * cannot be easily throttled on a framework level (since it would typically return UI on failure);
 * secondary providers are expected to do their own throttling.
 * @ingroup Auth
 * @since 1.27
 */
class ThrottlePreAuthenticationProvider extends AbstractPreAuthenticationProvider {
	/** @var array */
	protected $throttleSettings;

	/** @var Throttler */
	protected $accountCreationThrottle;

	/** @var Throttler */
	protected $passwordAttemptThrottle;

	/** @var BagOStuff */
	protected $cache;

	/**
	 * @param array $params
	 *  - accountCreationThrottle: (array) Condition array for the account creation throttle; an array
	 *    of arrays in a format like $wgPasswordAttemptThrottle, passed to the Throttler constructor.
	 *  - passwordAttemptThrottle: (array) Condition array for the password attempt throttle, in the
	 *    same format as accountCreationThrottle.
	 *  - cache: (BagOStuff) Where to store the throttle, defaults to the local cluster instance.
	 */
	public function __construct( $params = [] ) {
		$this->throttleSettings = array_intersect_key( $params,
			[ 'accountCreationThrottle' => true, 'passwordAttemptThrottle' => true ] );
		$this->cache = $params['cache'] ?? \ObjectCache::getLocalClusterInstance();
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		$accountCreationThrottle = $this->config->get( 'AccountCreationThrottle' );
		// Handle old $wgAccountCreationThrottle format (number of attempts per 24 hours)
		if ( !is_array( $accountCreationThrottle ) ) {
			$accountCreationThrottle = [ [
				'count' => $accountCreationThrottle,
				'seconds' => 86400,
			] ];
		}

		// @codeCoverageIgnoreStart
		$this->throttleSettings += [
		// @codeCoverageIgnoreEnd
			'accountCreationThrottle' => $accountCreationThrottle,
			'passwordAttemptThrottle' => $this->config->get( 'PasswordAttemptThrottle' ),
		];

		if ( !empty( $this->throttleSettings['accountCreationThrottle'] ) ) {
			$this->accountCreationThrottle = new Throttler(
				$this->throttleSettings['accountCreationThrottle'], [
					'type' => 'acctcreate',
					'cache' => $this->cache,
				]
			);
		}
		if ( !empty( $this->throttleSettings['passwordAttemptThrottle'] ) ) {
			$this->passwordAttemptThrottle = new Throttler(
				$this->throttleSettings['passwordAttemptThrottle'], [
					'type' => 'password',
					'cache' => $this->cache,
				]
			);
		}
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		if ( !$this->accountCreationThrottle || !$creator->isPingLimitable() ) {
			return \StatusValue::newGood();
		}

		$ip = $this->manager->getRequest()->getIP();

		if ( !$this->getHookRunner()->onExemptFromAccountCreationThrottle( $ip ) ) {
			$this->logger->debug( __METHOD__ . ": a hook allowed account creation w/o throttle" );
			return \StatusValue::newGood();
		}

		$result = $this->accountCreationThrottle->increase( null, $ip, __METHOD__ );
		if ( $result ) {
			$message = wfMessage( 'acct_creation_throttle_hit' )->params( $result['count'] )
				->durationParams( $result['wait'] );
			return \StatusValue::newFatal( $message );
		}

		return \StatusValue::newGood();
	}

	public function testForAuthentication( array $reqs ) {
		if ( !$this->passwordAttemptThrottle ) {
			return \StatusValue::newGood();
		}

		$ip = $this->manager->getRequest()->getIP();
		try {
			$username = AuthenticationRequest::getUsernameFromRequests( $reqs );
		} catch ( \UnexpectedValueException $e ) {
			$username = '';
		}

		// Get everything this username could normalize to, and throttle each one individually.
		// If nothing uses usernames, just throttle by IP.
		$usernames = $this->manager->normalizeUsername( $username );
		$result = false;
		foreach ( $usernames as $name ) {
			$r = $this->passwordAttemptThrottle->increase( $name, $ip, __METHOD__ );
			if ( $r && ( !$result || $result['wait'] < $r['wait'] ) ) {
				$result = $r;
			}
		}

		if ( $result ) {
			$message = wfMessage( 'login-throttled' )->durationParams( $result['wait'] );
			return \StatusValue::newFatal( $message );
		} else {
			$this->manager->setAuthenticationSessionData( 'LoginThrottle',
				[ 'users' => $usernames, 'ip' => $ip ] );
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
		} elseif ( !$this->passwordAttemptThrottle ) {
			return;
		}

		$data = $this->manager->getAuthenticationSessionData( 'LoginThrottle' );
		if ( !$data ) {
			// this can occur when login is happening via AuthenticationRequest::$loginRequest
			// so testForAuthentication is skipped
			$this->logger->info( 'throttler data not found for {user}', [ 'user' => $user->getName() ] );
			return;
		}

		foreach ( $data['users'] as $name ) {
			$this->passwordAttemptThrottle->clear( $name, $data['ip'] );
		}
	}
}
