<?php
/**
 * Authentication (and possibly Authorization in the future) system entry point
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
 * @ingroup Auth
 */

/**
 * This serves as the entry point to the authentication system.
 *
 * In the future, it may also serve as the entry point to the authorization
 * system.
 *
 * @ingroup Auth
 * @since 1.25
 */
final class AuthManager {
	/** @var AuthManager|null */
	private static $instance = null;

	/** @var IContextSource */
	private $context;

	/** @var AuthnSession|null */
	private $session;

	/**
	 * Get the global AuthManager
	 * @return AuthManager
	 */
	public function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self( RequestContext::getMain() );
		}
		return self::$instance;
	}

	/**
	 * @param IContextSource $context
	 * */
	public function __construct( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * Fetch the current session
	 * @return AuthnSession
	 */
	public function getSession() {
		if ( !$this->session ) {
			/** @todo Implement this */
			// * Instantiate the list of SessionProviders from $this->context->getConfig()
			// * Call each one, pick the session with highest priority (or throw
			//   an exception in case of a tie)
		}
		return $this->session;
	}

	/**
	 * Indicate whether user authentication is possible
	 *
	 * It may not be if the authn session is provided by something like OAuth.
	 *
	 * @return bool
	 */
	public function canAuthenticateNow() {
		return $this->getSession()->canSetAuthenticatedUserName();
	}

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * Possible values for $which:
	 *  - login: Valid for passing to beginAuthentication
	 *  - login-continue: Valid for passing to continueAuthentication in the current state
	 *  - create: Valid for passing to beginAccountCreation
	 *  - create-continue: Valid for passing to continueAccountCreation
	 *  - all: All possible
	 *
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		$session = $this->getSession();
		if ( !$session->canSetAuthenticatedUserName() ) {
			throw new RuntimeException( "Authentication is not possible now" );
		}

		/** @todo Implement this */
		// login: Get the lists of PreAuthenticationProviders and
		//   PrimaryAuthenticationProviders, collect types
		// login-continue: Determine the current state from the session, instantiate
		//   the appropriate class, ask it for its list of types.
		// create: Get the lists of PreAuthenticationProviders and
		//   PrimaryAuthenticationProviders, collect types
		// create-continue: Determine the current state from the session, instantiate
		//   the appropriate class, ask it for its list of types.
		// all: Get all the lists of AuthenticationProviders,
		//   collect types.
	}

	/**
	 * Start an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAuthentication( array $reqs ) {
		$session = $this->getSession();
		if ( !$session->canSetAuthenticatedUserName() ) {
			throw new RuntimeException( "Authentication is not possible now" );
		}

		/** @todo Implement this */
		// * Instantiate the list of PreAuthenticationProviders from $this->context->getConfig()
		// * Call them all and see if they all PASS for $reqs
		// * Instantiate the list of PrimaryAuthenticationProviders from $this->context->getConfig()
		//   (or at least the ones that can handle $reqs)
		// * Call each one until one returns non-ABSTAIN
		// * Munge the returned AuthenticationResponse, and return it
	}

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAuthentication( array $reqs ) {
		$session = $this->getSession();
		if ( !$session->canSetAuthenticatedUserName() ) {
			throw new RuntimeException( "Authentication is not possible now" );
		}

		/** @todo Implement this */
		// * Determine the current state from the session
		// * Instantiate the appropriate provider
		// * Invoke it
		// * Munge the returned AuthenticationResponse, and return it
	}

	/**
	 * Return the authenticated user name
	 * @return string|null
	 */
	public function getAuthenticatedUserName() {
		$session = $this->getSession();
		if ( $session->canSetAuthenticatedUserName() ) {
			/** @todo Implement this */
			// * Fetch PrimaryAuthenticationProvider and token from session
			// * Validate the token with the provider
			// * If fail, return null
		} else {
			// We assume if it's something like OAuth that it already did
			// appropriate validation, nothing to do here.
		}

		return $session->getAuthenticatedUserName();
	}

	/**
	 * Invalidate an authentication token
	 */
	public function invalidateAuthenticationToken() {
		$session = $this->getSession();
		if ( $session->canSetAuthenticatedUserName() ) {
			/** @todo Implement this */
			// * Fetch PrimaryAuthenticationProvider and token from session
			// * Tell the provider to invalidate
		}
	}

	/**
	 * Fetch the PrimaryAuthenticationProvider used for the current session
	 * @return PrimaryAuthenticationProvider
	 */
	public function getPrimaryAuthenticationProvider() {
		$session = $this->getSession();
		if ( !$session->canSetAuthenticatedUserName() ) {
			return null;
		}

		/** @todo Implement this */
	}

	/**
	 * Return the time since the last authentication
	 * @return int
	 */
	public function timeSinceAuthentication() {
		$session = $this->getSession();
		if ( $session->canSetAuthenticatedUserName() ) {
			$last = $session->get( 'AuthManager:lastAuthTimestamp' );
			if ( $last === null ) {
				return PHP_INT_MAX; // Forever ago
			} else {
				return time() - $last;
			}
		} else {
			return -1; // Or would PHP_INT_MAX make more sense?
		}
	}

	/**
	 * Determine whether a username exists
	 * @param string $username
	 * @return bool
	 */
	public function userExists( $username ) {
		/** @todo Implement this */
		// * Instantiate list of PrimaryAuthenticationProviders
		// * Ask each one if the user exists, returning true if yes
		// * Else return false.
	}

	/**
	 * Determine whether a user property should be allowed to be changed.
	 *
	 * Supported properties are:
	 *  - emailaddress
	 *  - realname
	 *  - nickname
	 *
	 * @param string $property
	 * @return bool
	 */
	public function allowPropertyChange( $property ) {
		/** @todo Implement this */
		// * Instantiate list of PrimaryAuthenticationProviders (also others?)
		// * Ask each one if the prop can change, returning false if no
		// * Else return true.
	}

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  ) {
		/** @todo Implement this */
		// * Instantiate list of PrimaryAuthenticationProviders
		// * Pass $req to each one, return $status if not good
		// * Return a good status
	}

	/**
	 * Change authentication data (e.g. passwords)
	 *
	 * If the provider supports the AuthenticationRequest type, passing $req
	 * should result in a successful login in the future.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function changeAuthenticationData( AuthenticationRequest $req  ) {
		/** @todo Implement this */
		// * Instantiate list of PrimaryAuthenticationProviders
		// * Pass $req to each one
	}

	/**
	 * Determine whether accounts can be created
	 * @return bool
	 */
	public function canCreateAccounts() {
		/** @todo Implement this */
		// * See if we have any 'create' or 'link' PrimaryAuthenticationProviders
	}

	/**
	 * Start an account creation flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( array $reqs ) {
		/** @todo Implement this */
	}

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( array $reqs ) {
		/** @todo Implement this */
	}

	/**
	 * @todo Something like AuthPlugin::getUserInstance(), that returns an
	 * object with properties like "locked" for the user?
	 */

}
