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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * This serves as the entry point to the authentication system.
 *
 * In the future, it may also serve as the entry point to the authorization
 * system.
 *
 * @ingroup Auth
 * @since 1.25
 */
final class AuthManager implements LoggerAwareInterface {
	/** @var AuthManager|null */
	private static $instance = null;

	/** @var WebRequest */
	private $request;

	/** @var Config */
	private $config;

	/** @var AuthnSession|null */
	private $session;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * Get the global AuthManager
	 * @return AuthManager
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self( RequestContext::getMain()->getRequest() );
		}
		return self::$instance;
	}

	/**
	 * @param WebRequest $request
	 * */
	public function __construct( WebRequest $request ) {
		$this->request = $request;
		$this->config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$conf = $this->config->get( 'AuthManagerConfig' );
		if ( $conf['logger'] === null ) {
			$this->setLogger( new NullLogger() );
		} else {
			$this->setLogger( MWLoggerFactory::getInstance( $conf['logger'] ) );
		}
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Fetch the current session
	 * @return AuthnSession
	 */
	public function getSession() {
		if ( !$this->session ) {
			$config = $this->config;
			$conf = $config->get( 'AuthManagerConfig' );

			// BC checking
			if ( !isset( $conf['sessionstore']['type'] ) ) {
				$conf['sessionstore']['type'] = $config->get( 'SessionCacheType' );
			}
			if ( !isset( $conf['sessionstore']['expiry'] ) ) {
				$conf['sessionstore']['expiry'] = $config->get( 'ObjectCacheSessionExpiry' );
			}

			// Create the session store
			$store = ObjectCache::getInstance( $conf['sessionstore']['type'] );
			if ( !isset( $conf['sessionstore']['logger'] ) ) {
				$store->setLogger( $this->logger );
			} elseif ( $conf['sessionstore']['logger'] === null ) {
				$store->setLogger( new NullLogger() );
			} else {
				$store->setLogger( MWLoggerFactory::getInstance( $conf['sessionstore']['logger'] ) );
			}
			if ( !$config->get( 'SessionsInObjectCache' ) && !$config->get( 'SessionsInMemcached' ) ) {
				if ( $config->get( 'SessionHandler' ) ) {
					$what = '$wgSessionsInObjectCache = false and $wgSessionHandler are';
				} else {
					$what = '$wgSessionsInObjectCache = false is';
				}
				$this->logger->warning(
					$what . ' ignored by AuthManager. Session data will be stored in ' .
						'"' . get_class( $store ) . '" storage with expiry ' .
						$conf['sessionstore']['expiry'] . ' seconds.'
				);
			}

			// Call all providers to fetch "the" session
			$session = null;
			$providers[] = array();
			foreach ( $conf['session'] as $spec ) {
				$provider = ObjectFactory::getObjectFromSpec( $spec );
				$possibleSession = $provider->provideAuthnSession(
					$this->request, $store, $this->logger
				);
				if ( !$possibleSession ) {
					continue;
				}

				if ( $possibleSession->canSetSessionUserInfo() ) {
					list( $id, $name, $token ) = $possibleSession->getSessionUserInfo();
					if ( !$this->checkUserInfo( $id, $name, $token ) ) {
						$this->logger->info( 'Token check failed for session ' . $session );
						$possibleSession = $provider->provideAuthnSession(
							$this->request, $store, $this->logger, true
						);
						if ( !$possibleSession ) {
							continue;
						}
					}
				}

				if ( !$session ||
					$possibleSession->getSessionPriority() > $session->getSessionPriority()
				) {
					$session = $possibleSession;
					$providers = array( $provider );
				} elseif ( $possibleSession->getSessionPriority() === $session->getSessionPriority() ) {
					$providers[] = $provider;
				}
			}

			// Make sure there's only one
			if ( count( $providers ) > 1 ) {
				// We might get here before $wgContLang or $wgParser is set up,
				// and almost certainly before $wgUser. So only use ->plain()
				// and explicitly specify the language.
				$lang = Language::factory( $config->get( 'LanguageCode' ) );

				$list = array();
				foreach ( $providers as $p ) {
					$list[] = Message::newFromKey( $p->describeSessions(), array(), $lang )->plain();
				}
				$list = join(
					Message::newFromKey( 'comma-separator', array(), $lang )->plain(),
					$lang->listToText( $list )
				);
				throw new HttpError( 400,
					Message::newFromKey( 'authmanager-session-tie', array( $list ), $lang )->plain()
				);
			}

			// Make sure there *is* one
			if ( $session === null ) {
				$this->logger->warning( 'No AuthnSession was provided! Using a null session.' );
				$session = new NullAuthnSession();
			} else {
				$this->logger->debug( 'Using session ' . $session );
			}

			// Set it up
			$session->setupPHPSessionHandler( $conf['sessionstore']['expiry'] );
			$this->session = $session;
		}

		return $this->session;
	}

	/**
	 * Reset the session id
	 */
	public function resetSessionId() {
		$session = $this->getSession();
		$oldSessionId = $session->getKey();
		$newSessionId = $session->resetSessionKey();
		if ( $oldSessionId !== $newSessionId ) {
			/** @todo Can we kill this hook? Brad doesn't know why he added it in the first place. */
			Hooks::run( 'ResetSessionID', array( $oldSessionId, $newSessionId ) );
		}
	}

	/**
	 * Ensure the session is started and persistent
	 */
	public function persistSession() {
		$session = $this->getSession();
		if ( $session->getSessionKey() === null ) {
			// The session hasn't had a key set yet
			$session->resetSessionKey();
		} elseif ( session_id() === '' ) {
			// We somehow don't have an active session (maybe someone else
			// closed it?), so start one.
			session_start();
		}
	}

	/**
	 * Indicate whether user authentication is possible
	 *
	 * It may not be if the authn session is provided by something like OAuth.
	 *
	 * @return bool
	 */
	public function canAuthenticateNow() {
		return $this->getSession()->canSetSessionUserInfo();
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
		if ( !$session->canSetSessionUserInfo() ) {
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
		if ( !$session->canSetSessionUserInfo() ) {
			throw new RuntimeException( "Authentication is not possible now" );
		}

		/** @todo Implement this */
		// * Instantiate the list of PreAuthenticationProviders from $this->config
		// * Call them all and see if they all PASS for $reqs
		// * Set up the state machine in $_SESSION for continueAuthentication()
		//   so it'll start at the beginning.
		// * Call self::continueAuthentication( $reqs )
	}

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAuthentication( array $reqs ) {
		$session = $this->getSession();
		if ( !$session->canSetSessionUserInfo() ) {
			throw new RuntimeException( "Authentication is not possible now" );
		}

		/** @todo Implement this */
		// * Determine the current state from the session, and continue from there.
		//
		// State flow is something like this:
		// * Instantiate the list of PrimaryAuthenticationProviders from $this->config
		//   (or at least the ones that can handle $reqs)
		// * Call each one until one returns non-ABSTAIN
		// * Loop on "yielding" with REDIRECT/UI until it PASSes (or FAILs)
		// * Instantiate the list of SecondaryAuthenticationProviders from $this->config
		// * Iterate over the list, "yielding" on REDIRECT/UI as they occur.
		// * Maybe call the PostAuth hook, or maybe leave that for the caller.
	}

	/**
	 * Return the authenticated user name
	 * @return string|null
	 */
	public function getAuthenticatedUserName() {
		$session = $this->getSession();
		list( $id, $name, $token ) = $session->getSessionUserInfo();

		if ( !$session->canSetSessionUserInfo() ) {
			// We assume if it's something like OAuth that it already knows the
			// user is valid, so just sanity-check the return value.
			if ( $name !== null ) {
				$nId = User::idFromName( $name );
				if ( !$nId ) {
					throw new UnexpectedValueException(
						get_class( $session ) . '::getSessionUserInfo() returned an invalid user name'
					);
				}
				if ( $id !== 0 && $id !== $nId ) {
					throw new UnexpectedValueException(
						get_class( $session ) . '::getSessionUserInfo() returned mismatched user id and name'
					);
				}
			} elseif ( $id !== 0 ) {
				$name = User::newFromId( $id )->getName();
			}
			return $name;
		} elseif ( $this->checkUserInfo( $id, $name, $token ) ) {
			return $name;
		} else {
			return null;
		}
	}

	/**
	 * Fetch a token for a user name or id
	 * @param int $id
	 * @param string|null $name
	 * @return string
	 */
	protected function getUserToken( $id, $name ) {
		$conds = array();
		if ( $id !== 0 ) {
			$conds['user_id'] = $id;
		}
		if ( $name !== null ) {
			$conds['user_name'] = $name;
		}

		if ( !$conds ) {
			return '';
		}

		$db = wfGetDB( DB_SLAVE );
		$row = $db->selectRow( 'user',
			array( 'user_id', 'user_name', 'user_token' ),
			$conds,
			__METHOD__
		);
		if ( !$row ) {
			// No such user
			return '';
		}

		return hash_hmac( 'md5', "{$row->user_id}:{$row->user_name}", $row->user_token );
	}

	/**
	 * Check the user token for validity
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $token
	 */
	protected function checkUserInfo( $id, $name, $token ) {
		$hash = $this->getUserToken( $id, $name );
		return ( hash_equals( $hash, $token ?: '' ) && $hash !== '' ) || $name === null;
	}

	/**
	 * Invalidate an authentication token
	 */
	public function invalidateAuthenticationToken() {
		$session = $this->getSession();
		list( $id, $name, $token ) = $session->getSessionUserInfo();

		if ( $this->getUserToken( $id, $name ) === '' ) {
			return;
		}
		$token = MWCryptRand::generateHex( User::TOKEN_LENGTH );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( 'user_token' => $token ),
			array( 'user_id' => $id ),
			__METHOD__
		);
	}

	/**
	 * Fetch the PrimaryAuthenticationProvider used for the current session
	 * @return PrimaryAuthenticationProvider
	 */
	public function getPrimaryAuthenticationProvider() {
		$session = $this->getSession();
		if ( !$session->canSetSessionUserInfo() ) {
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
		if ( $session->canSetSessionUserInfo() ) {
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
		// This will be much like beginAuthentication()'s flow
	}

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( array $reqs ) {
		/** @todo Implement this */
		// This will be much like continueAuthentication()'s flow
	}

	/**
	 * @todo Something like AuthPlugin::getUserInstance(), that returns an
	 * object with properties like "locked" for the user?
	 */

}
