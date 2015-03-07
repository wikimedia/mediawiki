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

	/** @var AuthenticationProvider[] */
	private $allAuthenticationProviders = array();

	/** @var PreAuthenticationProvider[] */
	private $preAuthenticationProviders = null;

	/** @var PrimaryAuthenticationProvider[] */
	private $primaryAuthenticationProviders = null;

	/** @var SecondaryAuthenticationProvider[] */
	private $secondaryAuthenticationProviders = null;

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
	 * @name Session handling
	 * @{
	 */

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

	/**@}*/

	/**
	 * @name Authentication
	 * @{
	 */

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
	 * Start an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse See self::continueAuthentication()
	 */
	public function beginAuthentication( array $reqs ) {
		$session = $this->getSession();
		if ( !$session->canSetSessionUserInfo() ) {
			// Caller should have called canAuthenticateNow()
			$this->request->setSessionData( 'AuthManager::authnState', null );
			throw new LogicException( "Authentication is not possible now" );
		}

		$reqs = $this->prepareAuthenticationRequestArray( $reqs );

		foreach ( $this->getPreAuthenticationProviders() as $provider ) {
			$res = $provider->testForAuthentication( $this, $reqs );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS;
				case AuthenticationResponse::ABSTAIN; /// @todo Or should this be an error?
					// ok
					break;
				case AuthenticationResponse::FAIL;
					return $res;
				default:
					throw new DomainException(
						get_class( $provider ) . "::testForAuthentication() returned $res->status"
					);
			}
		}

		self::persistSession();
		$this->request->setSessionData( 'AuthManager::authnState', array(
			'reqs' => $reqs,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => array(),
		) );

		return $this->continueAuthentication( $reqs );
	}

	/**
	 * Continue an authentication flow
	 *
	 * Return values are interpreted as follows:
	 * - status FAIL: Authentication failed. Certain message keys might be
	 *   specially handled:
	 *   - authmanager-authn-not-in-progress: Start again from the beginning.
	 *   - authmanager-authn-no-local-user: No local user exists. The
	 *     $createRequest in the response may be passed to
	 *     AuthManager::beginAccountCreation() after adding a local username.
	 *   - authmanager-authn-no-local-user-link: Same. In addition, the client
	 *     might log in with different credentials and link these credentials to
	 *     that account.
	 * - status REDIRECT: The client should be redirected to the contained URL,
	 *   new AuthenticationRequests should be made (if any), then
	 *   AuthManager::continueAuthentication() should be called.
	 * - status UI: The client should be presented with a user interface for
	 *   the fields in the specified AuthenticationRequests, then new
	 *   AuthenticationRequests should be made, then
	 *   AuthManager::continueAuthentication() should be called.
	 * - status PASS: Authentication was successful.
	 *
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAuthentication( array $reqs ) {
		try {
			$session = $this->getSession();
			if ( !$session->canSetSessionUserInfo() ) {
				// Caller should have called canAuthenticateNow()
				throw new LogicException( "Authentication is not possible now" );
			}

			$reqs = $this->prepareAuthenticationRequestArray( $reqs );

			$state = $this->request->getSessionData( 'AuthManager::authnState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-authn-not-in-progress' )
				);
			}

			if ( $state['primary'] === null ) {
				// We haven't picked a PrimaryAuthenticationProvider yet
				foreach ( $this->getPrimaryAuthenticationProviders() as $id => $provider ) {
					$res = $provider->beginAuthentication( $this, $reqs );
					switch ( $res->status ) {
						case AuthenticationResponse::PASS;
							$state['primary'] = $id;
							$state['primaryResponse'] = $res;
							break 2;
						case AuthenticationResponse::FAIL;
							$this->request->setSessionData( 'AuthManager::authnState', null );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$state['primary'] = $id;
							$this->request->setSessionData( 'AuthManager::authnState', $state );
							return $res;
						default:
							throw new DomainException(
								get_class( $provider ) . "::beginAuthentication() returned $res->status"
							);
					}
				}
				if ( $state['primary'] === null ) {
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-no-primary' )
					);
				}
			} elseif ( $state['primaryResponse'] === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
				}
				$res = $provider->continueAuthentication( $this, $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$state['primaryResponse'] = $res;
						break;
					case AuthenticationResponse::FAIL;
						$this->request->setSessionData( 'AuthManager::authnState', null );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->request->setSessionData( 'AuthManager::authnState', $state );
						return $res;
					default:
						throw new DomainException(
							get_class( $provider ) . "::continueAuthentication() returned $res->status"
						);
				}
			}

			$res = $state['primaryResponse'];
			if ( $res->username === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
				}

				if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK ) {
					$maybeLink = $this->request->getSessionData( 'AuthManager::maybeLink' ) ?: array();
					$maybeLink[] = $res->createReq;
					$this->request->setSessionData( 'AuthManager::maybeLink', $maybeLink );
					$msg = 'authmanager-authn-no-local-user-link';
				} else {
					$msg = 'authmanager-authn-no-local-user';
				}
				$this->request->setSessionData( 'AuthManager::authnState', null );
				$ret = AuthenticationResponse::newFail( wfMessage( $msg ) );
				$ret->createReq = $res->createReq;
				return $ret;
			}

			$user = User::newFromName( $res->username );
			if ( !$user || !User::isUsableName( $user->getName() ) ) {
				throw new DomainException(
					get_class( $provider ) . " returned an invalid username: {$res->username}"
				);
			}
			if ( $user->getId() === 0 ) {
				// User doesn't exist locally. Create it.

				$block = RequestContext::getMain()->getUser()->isBlockedFromCreateAccount();
				if ( $block ) {
					$errorParams = array(
						$block->getTarget(),
						$block->mReason ? $block->mReason : wfMessage( 'blockednoreason' )->text(),
						$block->getByName(),
					);
					if ( $block->getType() === Block::TYPE_RANGE ) {
						$errorMessage = 'cantcreateaccount-range-text';
						$errorParams[] = $this->request->getIP();
					} else {
						$errorMessage = 'cantcreateaccount-text';
					}

					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( $errorMessage, $errorParams )
					);
				}

				$abortError = '';
				if ( !Hooks::run( 'AbortAutoAccount', array( $user, &$abortError ) ) ) {
					// Hook point to add extra creation throttles and blocks
					$this->logger->debug( __METHOD__ . ": a hook blocked creation: $abortError\n" );
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage(
							$abortError ?: 'login-abort-generic', wfEscapeWikiText( $res->username )
						)
					);
				}

				$status = $user->addToDatabase();
				if ( !$status->isOK() ) {
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail( $status->getMessage() );
				}
				$user->setToken();
				Hooks::run( 'LocalUserCreated', array( $user, true ) );
				$user->saveSettings();

				// Update user count
				DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

				// Watch user's userpage and talk page
				$user->addWatch( $user->getUserPage(), WatchedItem::IGNORE_USER_RIGHTS );
			}

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// Not started yet, the passed-in reqs probably aren't valid
					$func = 'beginSecondaryAuthentication';
					$res = $provider->beginSecondaryAuthentication( $this, $user, $beginReqs );
				} elseif ( !$state['secondary'][$id] ) {
					$func = 'continueSecondaryAuthentication';
					$res = $provider->continueSecondaryAuthentication( $this, $user, $reqs );
				} else {
					continue;
				}
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::FAIL;
						$this->request->setSessionData( 'AuthManager::authnState', null );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$state['secondary'][$id] = false;
						$this->request->setSessionData( 'AuthManager::authnState', $state );
						return $res;
					default:
						throw new DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status"
						);
				}
			}

			$id = $user->getId();
			$name = $user->getName();
			$user->getToken( true );
			$token = $this->getUserToken( $id, $name );

			$sessionReq = null;
			$sessionType = $session->getAuthenticationRequestType();
			if ( $sessionType && isset( $state['reqs'][$sessionType] ) ) {
				$sessionReq = $state['reqs'][$sessionType];
			}
			$session->resetSessionKey();
			$session->setSessionUserInfo( $id, $name, $token, $sessionReq );

			Hooks::run( 'UserLoggedIn', array( $user ) );

			$this->request->setSessionData( 'AuthManager::authnState', null );
			return AuthenticationResponse::newPass( $user->getName() );
		} catch ( Exception $ex ) {
			$this->request->setSessionData( 'AuthManager::authnState', null );
			throw $ex;
		}
	}

	/**
	 * Return the authenticated user id and name
	 * @return array Array(int, string|null)
	 */
	public function getAuthenticatedUserInfo() {
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
				if ( $id !== 0 ) {
					if ( $id !== $nId ) {
						throw new UnexpectedValueException(
							get_class( $session ) . '::getSessionUserInfo() returned mismatched user id and name'
						);
					}
				} else {
					$id = $nId;
				}
			} elseif ( $id !== 0 ) {
				$name = User::newFromId( $id )->getName();
			}
			return array( $id, $name );
		} elseif ( $this->checkUserInfo( $id, $name, $token ) ) {
			return array( $id, $name );
		} else {
			return array( 0, null );
		}
	}

	/**
	 * Fetch a token for a user name or id
	 * @param int $id
	 * @param string|null $name
	 * @param IDatabase $db
	 * @return string
	 */
	protected function getUserToken( $id, $name, IDatabase $db = null ) {
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

		if ( $db === null ) {
			$db = wfGetDB( DB_SLAVE );
		}
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
	 * @param string|null $name Username
	 */
	public function invalidateAuthenticationToken( $name = null ) {
		if ( $name === null ) {
			$session = $this->getSession();
			list( $id, $name, $token ) = $session->getSessionUserInfo();

			if ( $this->getUserToken( $id, $name ) === '' ) {
				return;
			}
		}

		$token = MWCryptRand::generateHex( User::TOKEN_LENGTH );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'user',
			array( 'user_token' => $token ),
			array( 'user_name' => $name ),
			__METHOD__
		);
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

	/**@}*/

	/**
	 * @name Authentication data changing
	 * @{
	 */

	/**
	 * Indicate whether an AuthenticationRequest type is supported for changing
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function allowChangingAuthenticationType( $type ) {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( !$provider->allowChangingAuthenticationType( $type ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  ) {
		$any = false;
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$status = $provider->canChangeAuthenticationData( $req );
			if ( !$status->isGood() ) {
				return $status;
			}
			$any = $any || $status->value !== 'ignored';
		}
		if ( !$any ) {
			return Status::newFatal( 'authmanager-change-not-supported' );
		}
		foreach ( $this->getSecondaryAuthenticationProviders() as $provider ) {
			$status = $provider->canChangeAuthenticationData( $req );
			if ( !$status->isGood() ) {
				return $status;
			}
		}
		return Status::newGood();
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
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$provider->changeAuthenticationData( $req );
		}
	}

	/**@}*/

	/**
	 * @name Account creation
	 * @{
	 */

	/**
	 * Determine whether accounts can be created
	 * @return bool
	 */
	public function canCreateAccounts() {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			switch ( $provider->accountCreationType() ) {
				case PrimaryAuthenticationProvider::TYPE_CREATE:
				case PrimaryAuthenticationProvider::TYPE_LINK:
					return true;
			}
		}
		return false;
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

	/**@}*/

	/**
	 * @name Information methods
	 * @{
	 */

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * Possible values for $which:
	 *  - login: Valid for passing to beginAuthentication
	 *  - login-continue: Valid for passing to continueAuthentication in the current state
	 *  - change: Valid for changeAuthenticationData
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

		// Figure out which providers to query
		switch ( $which ) {
			case 'login':
			case 'create':
			case 'all':
				$providers = $this->getPreAuthenticationProviders() +
					$this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
				break;

			case 'login-continue':
				$state = $this->request->getSessionData( 'AuthManager::authnState' );
				if ( !is_array( $state ) ) {
					return array();
				}
				if ( $state['primaryResponse'] === null ) {
					$provider = $this->getAuthenticationProvider( $state['primary'] );
					if ( !$provider instanceof PrimaryAuthenticationProvider ) {
						return array();
					}
					$providers = array( $provider );
				} else {
					$providers = array();
					foreach ( $state['secondary'] as $id => $done ) {
						if ( !$done ) {
							$provider = $this->getAuthenticationProvider( $id );
							if ( $provider instanceof SecondaryAuthenticationProvider ) {
								$providers = array( $provider );
							}
						}
					}
				}
				break;

			case 'change':
				$providers = $this->getPrimaryAuthenticationProviders();
				break;

			case 'create-continue':
				/** @todo: Implement this! */
				break;

			default:
				throw new DomainException( __METHOD__ . ": Invalid which \"$which\"" );
		}

		// Query them and merge results
		$types = array();
		foreach ( $providers as $provider ) {
			$types += array_flip( $provider->getAuthenticationRequestTypes( $which ) );
		}

		// Add the AuthnSession's type, if applicable.
		if ( $which === 'login' || $which === 'all' ) {
			$type = $this->getSession()->getAuthenticationRequestType();
			if ( $type !== null ) {
				$types[$type] = 1;
			}
		}

		$types = array_keys( $types );

		// For 'change', filter out any that something else *doesn't* allow changing
		if ( $which === 'change' ) {
			$types = array_values( array_filter(
				$types, array( $this, 'allowChangingAuthenticationType' )
			) );
		}

		return $types;
	}

	/**
	 * Determine whether a username exists
	 * @param string $username
	 * @return bool
	 */
	public function userExists( $username ) {
		return $this->getUserStatus( $username )->exists();
	}

	/**
	 * Fetch user status
	 * @param string $username
	 * @return AuthnUserStatus
	 */
	public function getUserStatus( $username ) {
		$status = AuthnUserStatus::newDefaultStatus();

		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$status = AuthnUserStatus::newCombinedStatus( $status, $provider->userStatus( $username ) );
		}

		return $status;
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
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( !$provider->allowPropertyChange( $property ) ) {
				return false;
			}
		}
		foreach ( $this->getSecondaryAuthenticationProviders() as $provider ) {
			if ( !$provider->allowPropertyChange( $property ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Preprocess an array of AuthenticationRequests
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationRequests[] Keys are class names
	 */
	protected function prepareAuthenticationRequestArray( array $reqs ) {
		$ret = array();
		foreach ( $reqs as $req ) {
			$class = get_class( $req );
			if ( isset( $ret[$class] ) ) {
				throw new InvalidArgumentException(
					"AuthenticationRequest array cannot contain multiple instances of $class"
				);
			}
			$ret[$class] = $req;
		}
		return $ret;
	}

	/**
	 * Create an array of AuthenticationProviders from an array of ObjectFactory specs
	 * @param array[] $specs
	 * @return AuthenticationProvider[]
	 */
	 protected function providerArrayFromSpecs( array $specs ) {
		 $ret = array();
		 foreach ( $specs as $spec ) {
			 $provider = ObjectFactory::getObjectFromSpec( $spec );
			 $provider->setLogger( $this->logger );
			 $provider->setConfig( $this->config );
			 $id = $provider->getUniqueId();
			 if ( isset( $this->allAuthenticationProviders[$id] ) ) {
				 throw new RuntimeException(
					 "Duplicate specifications for id $id (classes " .
						 get_class( $provider ) . ' and ' .
						 get_class( $this->allAuthenticationProviders[$id] ) . ')'
				 );
			 }
			 $this->allAuthenticationProviders[$id] = $provider;
			 $ret[$id] = $provider;
		 }
		 return $ret;
	 }

	/**
	 * Get the list of PreAuthenticationProviders
	 * @return PreAuthenticationProvider[]
	 */
	protected function getPreAuthenticationProviders() {
		if ( $this->preAuthenticationProviders === null ) {
			$conf = $this->config->get( 'AuthManagerConfig' );
			$this->preAuthenticationProviders = $this->providerArrayFromSpecs(
				$conf['preauth']
			);
		}
		return $this->preAuthenticationProviders;
	}

	/**
	 * Get the list of PrimaryAuthenticationProviders
	 * @return PrimaryAuthenticationProvider[]
	 */
	protected function getPrimaryAuthenticationProviders() {
		if ( $this->primaryAuthenticationProviders === null ) {
			$conf = $this->config->get( 'AuthManagerConfig' );
			$this->primaryAuthenticationProviders = $this->providerArrayFromSpecs(
				$conf['primaryauth']
			);
		}
		return $this->primaryAuthenticationProviders;
	}

	/**
	 * Get the list of SecondaryAuthenticationProviders
	 * @return SecondaryAuthenticationProvider[]
	 */
	protected function getSecondaryAuthenticationProviders() {
		if ( $this->secondaryAuthenticationProviders === null ) {
			$conf = $this->config->get( 'AuthManagerConfig' );
			$this->secondaryAuthenticationProviders = $this->providerArrayFromSpecs(
				$conf['secondaryauth']
			);
		}
		return $this->secondaryAuthenticationProviders;
	}

	/**
	 * Get a provider by ID
	 * @param string $id
	 * @return AuthenticationProvider|null
	 */
	protected function getAuthenticationProvider( $id ) {
		// Fast version
		if ( isset( $this->allAuthenticationProviders[$id] ) ) {
			return $this->allAuthenticationProviders[$id];
		}

		// Slow version: instantiate each kind and check
		$providers = $this->getPrimaryAuthenticationProviders();
		if ( isset( $providers[$id] ) ) {
			return $providers[$id];
		}
		$providers = $this->getSecondaryAuthenticationProviders();
		if ( isset( $providers[$id] ) ) {
			return $providers[$id];
		}
		$providers = $this->getPreAuthenticationProviders();
		if ( isset( $providers[$id] ) ) {
			return $providers[$id];
		}

		return null;
	}

	/**@}*/

}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
