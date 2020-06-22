<?php
/**
 * Reset login/signup throttling for a specified user and/or IP.
 *
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
 * @ingroup Maintenance
 */

use MediaWiki\Auth\Throttler;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\IPUtils;

require_once __DIR__ . '/Maintenance.php';

/**
 * Reset login/signup throttling for a specified user and/or IP.
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class ResetAuthenticationThrottle extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Reset login/signup throttling for a specified user and/or IP. '
			. "\n\n"
			. 'When resetting signup only, provide the IP. When resetting login (or both), provide '
			. 'both username (as entered in login screen) and IP. An easy way to obtain them is '
			. "the 'throttler' log channel." );
		$this->addOption( 'login', 'Reset login throttle' );
		$this->addOption( 'signup', 'Reset account creation throttle' );
		$this->addOption( 'user', 'Username to reset', false, true );
		$this->addOption( 'ip', 'IP to reset', false, true );
	}

	public function execute() {
		$forLogin = (bool)$this->getOption( 'login' );
		$forSignup = (bool)$this->getOption( 'signup' );
		$username = $this->getOption( 'user' );
		$ip = $this->getOption( 'ip' );

		if ( !$forLogin && !$forSignup ) {
			$this->fatalError( 'At least one of --login and --signup is required!' );
		} elseif ( $forLogin && ( $ip === null || $username === null ) ) {
			$this->fatalError( '--user and --ip are both required when using --login!' );
		} elseif ( $forSignup && $ip === null ) {
			$this->fatalError( '--ip is required when using --signup!' );
		} elseif ( $ip !== null && !IPUtils::isValid( $ip ) ) {
			$this->fatalError( "Not a valid IP: $ip" );
		}

		if ( $forLogin ) {
			$this->clearLoginThrottle( $username, $ip );
		}
		if ( $forSignup ) {
			$this->clearSignupThrottle( $ip );
		}

		LoggerFactory::getInstance( 'throttler' )->notice( 'Manually cleared {type} throttle', [
			'type' => implode( ' and ', array_filter( [
				$forLogin ? 'login' : null,
				$forSignup ? 'signup' : null,
			] ) ),
			'username' => $username,
			'ipKey' => $ip,
		] );
	}

	/**
	 * @param string|null $rawUsername
	 * @param string|null $ip
	 */
	protected function clearLoginThrottle( $rawUsername, $ip ) {
		$this->output( 'Clearing login throttle... ' );

		$passwordAttemptThrottle = $this->getConfig()->get( 'PasswordAttemptThrottle' );
		if ( !$passwordAttemptThrottle ) {
			$this->output( "none set\n" );
			return;
		}

		$throttler = new Throttler( $passwordAttemptThrottle, [
			'type' => 'password',
			'cache' => ObjectCache::getLocalClusterInstance(),
		] );
		if ( $rawUsername !== null ) {
			$usernames = MediaWikiServices::getInstance()->getAuthManager()
				->normalizeUsername( $rawUsername );
			if ( !$usernames ) {
				$this->fatalError( "Not a valid username: $rawUsername" );
			}
		} else {
			$usernames = [ null ];
		}
		foreach ( $usernames as $username ) {
			$throttler->clear( $username, $ip );
		}

		$botPasswordThrottler = new Throttler( $passwordAttemptThrottle, [
			'type' => 'botpassword',
			'cache' => ObjectCache::getLocalClusterInstance(),
		] );
		$botPasswordThrottler->clear( $username, $ip );

		$this->output( "done\n" );
	}

	/**
	 * @param string $ip
	 */
	protected function clearSignupThrottle( $ip ) {
		$this->output( 'Clearing signup throttle... ' );

		$accountCreationThrottle = $this->getConfig()->get( 'AccountCreationThrottle' );
		if ( !is_array( $accountCreationThrottle ) ) {
			$accountCreationThrottle = [ [
				'count' => $accountCreationThrottle,
				'seconds' => 86400,
			] ];
		}
		if ( !$accountCreationThrottle ) {
			$this->output( "none set\n" );
			return;
		}
		$throttler = new Throttler( $accountCreationThrottle, [
			'type' => 'acctcreate',
			'cache' => ObjectCache::getLocalClusterInstance(),
		] );

		$throttler->clear( null, $ip );

		$this->output( "done\n" );
	}

}

$maintClass = ResetAuthenticationThrottle::class;
require_once RUN_MAINTENANCE_IF_MAIN;
