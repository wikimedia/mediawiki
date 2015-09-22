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

use ObjectCache;
use User;

/**
 * A base class that implements some of the boilerplate for a SecondaryAuthenticationProvider
 *
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractSecondaryAuthenticationProvider extends AbstractAuthenticationProvider
	implements SecondaryAuthenticationProvider
{

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function providerAllowsPropertyChange( $property ) {
		return true;
	}

	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req ) {
		return \StatusValue::newGood( 'ignored' );
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function testForAutoCreation( $user ) {
		return \StatusValue::newGood();
	}

	public function autoCreatedAccount( $user ) {
	}

	/**
	 * Throttles a secondary authentication step using the login throttle settings.
	 * Returns true if the action is over the throttle limit and should be rejected; returns false
	 * (and increases the throttle count) otherwise.
	 * @param User $user
	 * @param string $action An arbitrary action name used to prevent different types of throttles
	 *    from being counted together.
	 * @return bool
	 */
	protected function throttleAction( $user, $action ) {
		global $wgPasswordAttemptThrottle;

		if ( is_array( $wgPasswordAttemptThrottle ) ) {
			$throttleKey = wfMemcKey( 'auth-secondary-throttle', $action, md5( $user->getName() ) );
			$count = $wgPasswordAttemptThrottle['count'];
			$period = $wgPasswordAttemptThrottle['seconds'];

			$cache = ObjectCache::getLocalClusterInstance();
			$throttleCount = $cache->get( $throttleKey );
			if ( !$throttleCount ) {
				$cache->add( $throttleKey, 1, $period ); // start counter
			} elseif ( $throttleCount < $count ) {
				$cache->incr( $throttleKey );
			} elseif ( $throttleCount >= $count ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Clears a throttle after a successful action.
	 * @param User $user
	 * @param string $action An arbitrary action name used to prevent different types of throttles
	 *    from being counted together.
	 */
	protected function clearThrottle( $user, $action ) {
		$throttleKey = wfMemcKey( 'auth-secondary-throttle', $action, md5( $user->getName() ) );
		ObjectCache::getLocalClusterInstance()->delete( $throttleKey );
	}
}
