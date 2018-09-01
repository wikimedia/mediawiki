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
 */

namespace MediaWiki\Auth;

use Psr\Log\LoggerInterface;
use User;

/**
 * Backwards-compatibility wrapper for AuthManager via $wgAuth
 * @since 1.27
 * @deprecated since 1.27
 */
class AuthManagerAuthPlugin extends \AuthPlugin {
	/** @var string|null */
	protected $domain = null;

	/** @var LoggerInterface */
	protected $logger = null;

	public function __construct() {
		$this->logger = \MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' );
	}

	public function userExists( $name ) {
		return AuthManager::singleton()->userExists( $name );
	}

	public function authenticate( $username, $password ) {
		$data = [
			'username' => $username,
			'password' => $password,
		];
		if ( $this->domain !== null && $this->domain !== '' ) {
			$data['domain'] = $this->domain;
		}
		$reqs = AuthManager::singleton()->getAuthenticationRequests( AuthManager::ACTION_LOGIN );
		$reqs = AuthenticationRequest::loadRequestsFromSubmission( $reqs, $data );

		$res = AuthManager::singleton()->beginAuthentication( $reqs, 'null:' );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				$msg = $res->message instanceof \Message ? $res->message : new \Message( $res->message );
				$this->logger->info( __METHOD__ . ': Authentication failed: ' . $msg->plain() );
				return false;
			default:
				throw new \BadMethodCallException(
					'AuthManager does not support such simplified authentication'
				);
		}
	}

	public function modifyUITemplate( &$template, &$type ) {
		// AuthManager does not support direct UI screwing-around-with
	}

	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

	public function getDomain() {
		if ( isset( $this->domain ) ) {
			return $this->domain;
		} else {
			return 'invaliddomain';
		}
	}

	public function validDomain( $domain ) {
		$domainList = $this->domainList();
		return $domainList ? in_array( $domain, $domainList, true ) : $domain === '';
	}

	public function updateUser( &$user ) {
		\Hooks::run( 'UserLoggedIn', [ $user ] );
		return true;
	}

	public function autoCreate() {
		return true;
	}

	public function allowPropChange( $prop = '' ) {
		return AuthManager::singleton()->allowsPropertyChange( $prop );
	}

	public function allowPasswordChange() {
		$reqs = AuthManager::singleton()->getAuthenticationRequests( AuthManager::ACTION_CHANGE );
		foreach ( $reqs as $req ) {
			if ( $req instanceof PasswordAuthenticationRequest ) {
				return true;
			}
		}

		return false;
	}

	public function allowSetLocalPassword() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return false;
	}

	public function setPassword( $user, $password ) {
		$data = [
			'username' => $user->getName(),
			'password' => $password,
		];
		if ( $this->domain !== null && $this->domain !== '' ) {
			$data['domain'] = $this->domain;
		}
		$reqs = AuthManager::singleton()->getAuthenticationRequests( AuthManager::ACTION_CHANGE );
		$reqs = AuthenticationRequest::loadRequestsFromSubmission( $reqs, $data );
		foreach ( $reqs as $req ) {
			$status = AuthManager::singleton()->allowsAuthenticationDataChange( $req );
			if ( !$status->isGood() ) {
				$this->logger->info( __METHOD__ . ': Password change rejected: {reason}', [
					'username' => $data['username'],
					'reason' => $status->getWikiText( null, null, 'en' ),
				] );
				return false;
			}
		}
		foreach ( $reqs as $req ) {
			AuthManager::singleton()->changeAuthenticationData( $req );
		}
		return true;
	}

	public function updateExternalDB( $user ) {
		// This fires the necessary hook
		$user->saveSettings();
		return true;
	}

	public function updateExternalDBGroups( $user, $addgroups, $delgroups = [] ) {
		throw new \BadMethodCallException(
			'Update of user groups via AuthPlugin is not supported with AuthManager.'
		);
	}

	public function canCreateAccounts() {
		return AuthManager::singleton()->canCreateAccounts();
	}

	public function addUser( $user, $password, $email = '', $realname = '' ) {
		throw new \BadMethodCallException(
			'Creation of users via AuthPlugin is not supported with '
			. 'AuthManager. Generally, user creation should be left to either '
			. 'Special:CreateAccount, auto-creation when triggered by a '
			. 'SessionProvider or PrimaryAuthenticationProvider, or '
			. 'User::newSystemUser().'
		);
	}

	public function strict() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	public function strictUserAuth( $username ) {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	public function initUser( &$user, $autocreate = false ) {
		\Hooks::run( 'LocalUserCreated', [ $user, $autocreate ] );
	}

	public function getCanonicalName( $username ) {
		// AuthManager doesn't support restrictions beyond MediaWiki's
		return $username;
	}

	public function getUserInstance( User &$user ) {
		return new AuthManagerAuthPluginUser( $user );
	}

	public function domainList() {
		return [];
	}
}
