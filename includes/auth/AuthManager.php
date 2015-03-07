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
 * @todo: Figure out what to pass to an equivalent of the
 *   'LoginAuthenticateAudit' hook. Remember that we might not even have a
 *   username available.
 * @ingroup Auth
 * @since 1.26
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

	/** @var SessionProvider[] */
	private $sessionProviders = null;

	/** @var string[] */
	private $varyCookies = null;

	/** @var array */
	private $varyHeaders = null;

	/** @var AuthenticationProvider[] */
	private $allAuthenticationProviders = array();

	/** @var PreAuthenticationProvider[] */
	private $preAuthenticationProviders = null;

	/** @var PrimaryAuthenticationProvider[] */
	private $primaryAuthenticationProviders = null;

	/** @var SecondaryAuthenticationProvider[] */
	private $secondaryAuthenticationProviders = null;

	/** @var CreatedAccountAuthenticationRequest[] */
	private $createdAccountAuthenticationRequests = array();

	/**
	 * Get the global AuthManager
	 * @return AuthManager
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self(
				RequestContext::getMain()->getRequest(),
				ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
			);
		}
		return self::$instance;
	}

	/**
	 * @param WebRequest $request
	 * @param Config $config
	 */
	public function __construct( WebRequest $request, Config $config ) {
		$this->request = $request;
		$this->config = $config;
		$conf = $this->config->get( 'AuthManagerConfig' );
		if ( $conf['logger'] === null ) {
			$this->setLogger( new NullLogger() );
		} else {
			$this->setLogger( MediaWiki\Logger\LoggerFactory::getInstance( $conf['logger'] ) );
		}
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Force certain PrimaryAuthenticationProviders
	 * @deprecated For backwards compatibility only
	 * @param PrimaryAuthenticationProvider[] $providers
	 * @param string $why
	 */
	public function forcePrimaryAuthenticationProviders( array $providers, $why ) {
		$this->logger->warning( "Overriding AuthManager primary authn because $why" );

		if ( $this->primaryAuthenticationProviders !== null ) {
			$this->logger->warning(
				'PrimaryAuthenticationProviders have already been accessed! I hope nothing breaks.'
			);

			$this->allAuthenticationProviders = array_diff_key(
				$this->allAuthenticationProviders,
				$this->primaryAuthenticationProviders
			);
			$this->request->setSessionData( 'AuthManager::authnState', null );
			$this->request->setSessionData( 'AuthManager::accountCreationState', null );
			$this->createdAccountAuthenticationRequests = array();
		}

		$this->primaryAuthenticationProviders = array();
		foreach ( $providers as $provider ) {
			if ( !$provider instanceof PrimaryAuthenticationProvider ) {
				throw new RuntimeException(
					"Expected instance of PrimaryAuthenticationProvider, got " . get_class( $provider )
				);
			}
			$provider->setLogger( $this->logger );
			$provider->setManager( $this );
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
			$this->primaryAuthenticationProviders[$id] = $provider;
		}
	}

	/**
	 * Call a legacy AuthPlugin method, if necessary
	 * @codeCoverageIgnore
	 * @deprecated For backwards compatibility only, should be avoided in new code
	 * @param string $method AuthPlugin method to call
	 * @param array $params Parameters to pass
	 * @param mixed $return Return value if AuthPlugin wasn't called
	 * @return mixed Return value from the AuthPlugin method, or $return
	 */
	public static function callLegacyAuthPlugin( $method, array $params, $return = null ) {
		global $wgAuth;

		if ( $wgAuth && !$wgAuth instanceof AuthManagerAuthPlugin ) {
			return call_user_func_array( array( $wgAuth, $method ), $params );
		} else {
			return $return;
		}
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
			$conf = $this->config->get( 'AuthManagerConfig' );

			// Call all providers to fetch "the" session
			$session = null;
			$providers[] = array();
			foreach ( $this->getSessionProviders() as $provider ) {
				$possibleSession = $provider->provideAuthnSession( $this->request );
				if ( !$possibleSession ) {
					continue;
				}

				if ( $possibleSession->canSetSessionUserInfo() ) {
					list( $id, $name, $token ) = $possibleSession->getSessionUserInfo();
					if ( !$this->checkUserInfo( $id, $name, $token ) ) {
						$this->logger->info( 'Token check failed for session ' . $session );
						$possibleSession = $provider->provideAuthnSession( $this->request, true );
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
				$lang = Language::factory( $this->config->get( 'LanguageCode' ) );

				$list = array();
				foreach ( $providers as $p ) {
					$list[] = Message::newFromKey( $p->describeSessions() )->inLanguage( $lang )->plain();
				}
				$list = $lang->listToText( $list );
				throw new HttpError( 400,
					Message::newFromKey( 'authmanager-session-tie', $list )->inLanguage( $lang )->plain()
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
			if ( !isset( $conf['sessionstore']['expiry'] ) ) {
				$conf['sessionstore']['expiry'] = $this->config->get( 'ObjectCacheSessionExpiry' );
			}
			$session->setupPHPSessionHandler( $conf['sessionstore']['expiry'] );
			$this->session = $session;
		}

		return $this->session;
	}

	/**
	 * Reset the session id
	 */
	public function resetSessionId() {
		$this->getSession()->resetSessionKey();
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
			// @codeCoverageIgnoreStart
			MediaWiki\suppressWarnings(); // Headers may already have been sent
			session_start();
			MediaWiki\restoreWarnings();
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Return the HTTP headers that need varying on.
	 *
	 * The return value is such that someone could theoretically do this:
	 * @code
	 *  foreach ( $provider->getVaryHeaders() as $header => $options ) {
	 *  	$outputPage->addVaryHeader( $header, $options );
	 *  }
	 * @endcode
	 *
	 * @return array
	 */
	public function getVaryHeaders() {
		if ( $this->varyHeaders === null ) {
			$headers = array();
			foreach ( $this->getSessionProviders() as $provider ) {
				foreach ( $provider->getVaryHeaders() as $header => $options ) {
					if ( !isset( $headers[$header] ) ) {
						$headers[$header] = (array)$options;
					} elseif ( is_array( $options ) ) {
						$headers[$header] = array_merge( $headers[$header], $options );
					}
				}
			}
			$this->varyHeaders = $headers;
		}
		return $this->varyHeaders;
	}

	/**
	 * Return the list of cookies that need varying on.
	 * @return string[]
	 */
	public function getVaryCookies() {
		if ( $this->varyCookies === null ) {
			$cookies = array();
			foreach ( $this->getSessionProviders() as $provider ) {
				$cookies = array_merge( $cookies, $provider->getVaryCookies() );
			}
			$this->varyCookies = array_values( array_unique( $cookies ) );
		}
		return $this->varyCookies;
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

		// Check for special-case login of a just-created account
		if ( isset( $reqs['CreatedAccountAuthenticationRequest'] ) ) {
			$req = $reqs['CreatedAccountAuthenticationRequest'];
			if ( !in_array( $req, $this->createdAccountAuthenticationRequests, true ) ) {
				throw new LogicException(
					'CreatedAccountAuthenticationRequests are only valid on ' .
						'the same AuthManager that created the account'
				);
			}

			$user = User::newFromName( $req->username );
			// @codeCoverageIgnoreStart
			if ( !$user ) {
				throw new UnexpectedValueException(
					"CreatedAccountAuthenticationRequest had invalid username \"{$req->username}\""
				);
			} elseif ( $user->getId() != $req->id ) {
				throw new UnexpectedValueException(
					"ID for \"{$req->username}\" was {$user->getId()}, expected {$req->id}"
				);
			}
			// @codeCoverageIgnoreEnd

			$this->setSessionDataForUser( $user, $req->sessionReq );
			$this->request->setSessionData( 'AuthManager::authnState', null );
			return AuthenticationResponse::newPass( $user->getName() );
		}

		foreach ( $this->getPreAuthenticationProviders() as $provider ) {
			$status = $provider->testForAuthentication( $reqs );
			if ( !$status->isGood() ) {
				return AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
			}
		}

		self::persistSession();
		$this->removeAuthenticationData( null );
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
				// @codeCoverageIgnoreStart
				throw new LogicException( "Authentication is not possible now" );
				// @codeCoverageIgnoreEnd
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
					$res = $provider->beginPrimaryAuthentication( $reqs );
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

							// @codeCoverageIgnoreStart
						default:
							throw new DomainException(
								get_class( $provider ) . "::beginPrimaryAuthentication() returned $res->status"
							);
							// @codeCoverageIgnoreEnd
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
					// @codeCoverageIgnoreStart
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}
				$res = $provider->continuePrimaryAuthentication( $reqs );
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
							get_class( $provider ) . "::continuePrimaryAuthentication() returned $res->status"
						);
				}
			}

			$res = $state['primaryResponse'];
			if ( $res->username === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}

				if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK ) {
					$maybeLink = $this->request->getSessionData( 'AuthManager::maybeLink' ) ?: array();
					$maybeLink[] = $res->createRequest;
					$this->request->setSessionData( 'AuthManager::maybeLink', $maybeLink );
					$msg = 'authmanager-authn-no-local-user-link';
				} else {
					$msg = 'authmanager-authn-no-local-user';
				}
				$this->request->setSessionData( 'AuthManager::authnState', null );
				$ret = AuthenticationResponse::newFail( wfMessage( $msg ) );
				$ret->createRequest = $res->createRequest;
				return $ret;
			}

			$user = User::newFromName( $res->username, 'usable' );
			if ( !$user ) {
				throw new DomainException(
					get_class( $provider ) . " returned an invalid username: {$res->username}"
				);
			}
			if ( $user->getId() === 0 ) {
				// User doesn't exist locally. Create it.
				$status = $this->autoCreateAccount( $res->username, false );
				if ( !$status->isGood() ) {
					$this->request->setSessionData( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						Status::wrap( $status )->getMessage( 'authmanager-authn-autocreate-failed' )
					);
				}
				$user = $status->value;
			}

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// Not started yet, the passed-in reqs probably aren't valid
					$func = 'beginSecondaryAuthentication';
					$res = $provider->beginSecondaryAuthentication( $user, $beginReqs );
				} elseif ( !$state['secondary'][$id] ) {
					$func = 'continueSecondaryAuthentication';
					$res = $provider->continueSecondaryAuthentication( $user, $reqs );
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

						// @codeCoverageIgnoreStart
					default:
						throw new DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status"
						);
						// @codeCoverageIgnoreEnd
				}
			}

			$sessionReq = null;
			$sessionType = $session->getAuthenticationRequestType();
			if ( $sessionType && isset( $state['reqs'][$sessionType] ) ) {
				$sessionReq = $state['reqs'][$sessionType];
			}
			$this->setSessionDataForUser( $user, $sessionReq );

			$this->request->setSessionData( 'AuthManager::authnState', null );
			$this->removeAuthenticationData( null );
			return AuthenticationResponse::newPass( $user->getName() );
		} catch ( Exception $ex ) {
			$this->request->setSessionData( 'AuthManager::authnState', null );
			throw $ex;
		}
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest|null $sessionReq
	 */
	private function setSessionDataForUser( $user, $sessionReq ) {
		$id = $user->getId();
		$name = $user->getName();
		$user->getToken( true );
		$token = $this->getUserToken( $id, $name );

		$session = $this->getSession();
		if ( $session->canSetSessionUserInfo() ) {
			$session->resetSessionKey();
			$session->setSessionUserInfo( $id, $name, $token, $sessionReq );
		}

		$this->request->setSessionData( 'AuthManager:lastAuthId', $id );
		$this->request->setSessionData( 'AuthManager:lastAuthTimestamp', time() );

		Hooks::run( 'UserLoggedIn', array( $user ) );
	}

	/**
	 * Return the authenticated user id and name
	 * @param bool $autoCreate
	 * @return array Array(int, string|null)
	 */
	public function getAuthenticatedUserInfo( $autoCreate = false ) {
		$session = $this->getSession();
		list( $id, $name, $token ) = $session->getSessionUserInfo();

		// Auto-create, if asked
		if ( $id === 0 && $name !== null ) {
			$id = (int)User::idFromName( $name );
			if ( $id === 0 ) {
				if ( $autoCreate ) {
					$status = $this->autoCreateAccount( $name );
					if ( $status->isGood() ) {
						$user = $status->value;
						return array( $user->getId(), $user->getName() );
					}
				}
				return array( 0, null );
			}
		}

		if ( !$session->canSetSessionUserInfo() ) {
			// We assume if it's something like OAuth that it already knows the
			// user is valid, so just sanity-check the return value.
			if ( $name !== null ) {
				$nId = (int)User::idFromName( $name );
				if ( $id !== $nId ) {
					throw new UnexpectedValueException(
						get_class( $session ) . '::getSessionUserInfo() returned mismatched user id and name'
					);
				}
			} elseif ( $id !== 0 ) {
				$name = User::whoIs( $id );
				if ( $name === false ) {
					throw new UnexpectedValueException(
						get_class( $session ) . '::getSessionUserInfo() returned an invalid user id'
					);
				}
			}
			return array( $id, $name );
		} elseif ( $this->checkUserInfo( $id, $name, $token ) ) {
			if ( $name === null ) {
				$name = User::whoIs( $id );
				if ( $name === false ) {
					$name = null;
				}
			}
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
			// @codeCoverageIgnoreStart
			return '';
			// @codeCoverageIgnoreEnd
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
		return hash_equals( $hash, $token ?: '' ) && ( $hash !== '' || $id === 0 );
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
	 * @return int seconds
	 */
	public function timeSinceAuthentication() {
		$session = $this->getSession();
		if ( $session->canSetSessionUserInfo() ) {
			list( $aId, $aName ) = $this->getAuthenticatedUserInfo();
			$id = $this->request->getSessionData( 'AuthManager:lastAuthId' );
			$last = $this->request->getSessionData( 'AuthManager:lastAuthTimestamp' );
			if ( $aId === 0 || $id !== $aId || $last === null ) {
				return PHP_INT_MAX; // Forever ago
			} else {
				return time() - $last;
			}
		} else {
			return -1; // Or would PHP_INT_MAX make more sense?
		}
	}

	/**
	 * Log the user out
	 * @throws LogicException if $this->canAuthenticateNow() returns false
	 */
	public function logout() {
		$session = $this->getSession();
		if ( !$session->canSetSessionUserInfo() ) {
			// Caller should have called canAuthenticateNow()
			throw new LogicException( "Authentication is not possible now" );
		}
		$session->setSessionUserInfo( 0, null, null, null );
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
			if ( !$provider->providerAllowChangingAuthenticationType( $type ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return Status
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  ) {
		$any = false;
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$status = $provider->providerCanChangeAuthenticationData( $req );
			if ( !$status->isGood() ) {
				return Status::wrap( $status );
			}
			$any = $any || $status->value !== 'ignored';
		}
		if ( !$any ) {
			$status = Status::newGood( 'ignored' );
			$status->warning( 'authmanager-change-not-supported' );
			return $status;
		}
		foreach ( $this->getSecondaryAuthenticationProviders() as $provider ) {
			$status = $provider->providerCanChangeAuthenticationData( $req );
			if ( !$status->isGood() ) {
				return Status::wrap( $status );
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
			$provider->providerChangeAuthenticationData( $req );
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
	 *
	 * @note In addition to AuthenticationRequest types from
	 *   self::getAuthenticationRequestTypes(), a UserDataAuthenticationRequest
	 *   may be included in $reqs
	 * @param string $username
	 * @param User $creator User doing the account creation
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( $username, User $creator, array $reqs ) {
		if ( !$this->canCreateAccounts() ) {
			// Caller should have called canCreateAccounts()
			$this->request->setSessionData( 'AuthManager::accountCreationState', null );
			throw new LogicException( "Account creation is not possible" );
		}

		if ( $this->userExists( $username ) ) {
			return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
		}

		$user = User::newFromName( $username, 'creatable' );
		if ( !is_object( $user ) ) {
			return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
		} elseif ( $user->getId() !== 0 ) {
			return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
		}
		foreach ( $reqs as $req ) {
			$req->username = $username;
			if ( $req instanceof UserDataAuthenticationRequest ) {
				$req->populateUser( $user );
			}
		}

		$reqs = $this->prepareAuthenticationRequestArray( $reqs );

		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->testForAccountCreation( $user, $creator, $reqs );
			if ( !$status->isGood() ) {
				return AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
			}
		}

		self::persistSession();
		$this->removeAuthenticationData( null );
		$this->request->setSessionData( 'AuthManager::accountCreationState', array(
			'username' => $username,
			'userid' => 0,
			'reqs' => $reqs,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => array(),
		) );

		return $this->continueAccountCreation( $reqs );
	}

	/**
	 * Continue an account creation flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( array $reqs ) {
		try {
			if ( !$this->canCreateAccounts() ) {
				// Caller should have called canCreateAccounts()
				$this->request->setSessionData( 'AuthManager::accountCreationState', null );
				throw new LogicException( "Account creation is not possible" );
			}

			$state = $this->request->getSessionData( 'AuthManager::accountCreationState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-create-not-in-progress' )
				);
			}

			$user = User::newFromName( $state['username'], 'creatable' );
			if ( !is_object( $user ) ) {
				$this->request->setSessionData( 'AuthManager::accountCreationState', null );
				return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
			}
			if ( $state['userid'] === 0 ) {
				if ( $user->getId() != 0 ) {
					$this->request->setSessionData( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
				}
			} else {
				if ( $user->getId() == 0 ) {
					throw new UnexpectedValueException(
						"User \"{$state['username']}\" should exist now, but doesn't!"
					);
				}
				if ( $user->getId() != $state['userid'] ) {
					throw new UnexpectedValueException(
						"User \"{$state['username']}\" exists, but " .
							"ID {$user->getId()} != {$state['userid']}!"
					);
				}
			}
			foreach ( $state['reqs'] as $req ) {
				if ( $req instanceof UserDataAuthenticationRequest ) {
					$req->populateUser( $user );
				}
			}

			foreach ( $reqs as $req ) {
				$req->username = $state['username'];
			}
			$reqs = $this->prepareAuthenticationRequestArray( $reqs );

			if ( $state['primary'] === null ) {
				// We haven't picked a PrimaryAuthenticationProvider yet
				foreach ( $this->getPrimaryAuthenticationProviders() as $id => $provider ) {
					if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_NONE ) {
						continue;
					}
					$res = $provider->beginPrimaryAccountCreation( $user, $reqs );
					switch ( $res->status ) {
						case AuthenticationResponse::PASS;
							$state['primary'] = $id;
							$state['primaryResponse'] = $res;
							break 2;
						case AuthenticationResponse::FAIL;
							$this->request->setSessionData( 'AuthManager::accountCreationState', null );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$state['primary'] = $id;
							$this->request->setSessionData( 'AuthManager::accountCreationState', $state );
							return $res;

							// @codeCoverageIgnoreStart
						default:
							throw new DomainException(
								get_class( $provider ) . "::beginPrimaryAccountCreation() returned $res->status"
							);
							// @codeCoverageIgnoreEnd
					}
				}
				if ( $state['primary'] === null ) {
					$this->request->setSessionData( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-no-primary' )
					);
				}
			} else {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$this->request->setSessionData( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}
			}

			if ( $state['primaryResponse'] === null ) {
				$res = $provider->continuePrimaryAccountCreation( $user, $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$state['primaryResponse'] = $res;
						break;
					case AuthenticationResponse::FAIL;
						$this->request->setSessionData( 'AuthManager::accountCreationState', null );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->request->setSessionData( 'AuthManager::accountCreationState', $state );
						return $res;
					default:
						throw new DomainException(
							get_class( $provider ) . "::continuePrimaryAccountCreation() returned $res->status"
						);
				}
			}

			if ( $state['userid'] === 0 ) {
				$status = $user->addToDatabase();
				if ( !$status->isOk() ) {
					// @codeCoverageIgnoreStart
					$this->request->setSessionData( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail( $status->getMessage() );
					// @codeCoverageIgnoreEnd
				}
				$user->setToken();
				Hooks::run( 'LocalUserCreated', array( $user, false ) );
				$user->saveSettings();
				$state['userid'] = $user->getId();

				// Update user count
				DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

				// Watch user's userpage and talk page
				$user->addWatch( $user->getUserPage(), WatchedItem::IGNORE_USER_RIGHTS );

				// Inform the provider
				$provider->finishAccountCreation( $user, $state['primaryResponse'] );
			}

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// Not started yet, the passed-in reqs probably aren't valid
					$func = 'beginSecondaryAccountCreation';
					$res = $provider->beginSecondaryAccountCreation( $user, $beginReqs );
				} elseif ( !$state['secondary'][$id] ) {
					$func = 'continueSecondaryAccountCreation';
					$res = $provider->continueSecondaryAccountCreation( $user, $reqs );
				} else {
					continue;
				}
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$state['secondary'][$id] = false;
						$this->request->setSessionData( 'AuthManager::accountCreationState', $state );
						return $res;
					default:
						throw new DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status"
						);
				}
			}

			$id = $user->getId();
			$name = $user->getName();
			$req = new CreatedAccountAuthenticationRequest( $id, $name );
			$sessionType = $this->getSession()->getAuthenticationRequestType();
			if ( $sessionType && isset( $state['reqs'][$sessionType] ) ) {
				$req->sessionReq = $state['reqs'][$sessionType];
			}

			$this->request->setSessionData( 'AuthManager::accountCreationState', null );
			$this->removeAuthenticationData( null );
			$ret = AuthenticationResponse::newPass( $name );
			$ret->loginRequest = $req;
			$this->createdAccountAuthenticationRequests[] = $req;
			return $ret;
		} catch ( Exception $ex ) {
			$this->request->setSessionData( 'AuthManager::accountCreationState', null );
			throw $ex;
		}
	}

	/**
	 * Auto-create an account, and log into that account
	 * @param string $username
	 * @param bool $login Whether to also log the user in
	 * @return Status
	 */
	public function autoCreateAccount( $username, $login = true ) {
		if ( !$this->userExists( $username ) ) {
			return Status::newFatal( 'nosuchusershort' );
		}

		$user = User::newFromName( $username, 'creatable' );
		if ( !is_object( $user ) ) {
			return Status::newFatal( 'noname' );
		} elseif ( $user->getId() !== 0 ) {
			return Status::newFatal( 'userexists' );
		}

		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->testForAutoCreation( $user );
			if ( !$status->isGood() ) {
				return Status::wrap( $status );
			}
		}

		$status = $user->addToDatabase();
		if ( !$status->isOk() ) {
			// @codeCoverageIgnoreStart
			return $status;
			// @codeCoverageIgnoreEnd
		}
		$user->setToken();
		Hooks::run( 'LocalUserCreated', array( $user, true ) );
		$user->saveSettings();

		// Update user count
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

		// Watch user's userpage and talk page
		$user->addWatch( $user->getUserPage(), WatchedItem::IGNORE_USER_RIGHTS );

		// Inform the providers
		$providers = $this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$provider->autoCreatedAccount( $user );
		}

		if ( $login ) {
			$this->setSessionDataForUser( $user, null );
		}

		return Status::newGood( $user );
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
			case 'create-continue':
				if ( $which === 'login-continue' ) {
					$key = 'AuthManager::authnState';
				} else {
					$key = 'AuthManager::accountCreationState';
				}

				$state = $this->request->getSessionData( $key );
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
								$providers[] = $provider;
							}
						}
					}
				}
				break;

			case 'change':
				$providers = $this->getPrimaryAuthenticationProviders();
				break;

				// @codeCoverageIgnoreStart
			default:
				throw new DomainException( __METHOD__ . ": Invalid which \"$which\"" );
		}
				// @codeCoverageIgnoreEnd

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
			if ( !$provider->providerAllowPropertyChange( $property ) ) {
				return false;
			}
		}
		foreach ( $this->getSecondaryAuthenticationProviders() as $provider ) {
			if ( !$provider->providerAllowPropertyChange( $property ) ) {
				return false;
			}
		}
		return true;
	}

	/**@}*/

	/**
	 * @name Internal methods
	 *
	 * Public methods here are meant for use by AuthenticationProviders only.
	 * @{
	 */

	/**
	 * Store authentication in the current session
	 * @param string $key
	 * @param mixed $data Must be serializable
	 */
	public function setAuthenticationData( $key, $data ) {
		$arr = $this->getRequest()->getSessionData( 'authData' );
		if ( !is_array( $arr ) ) {
			$arr = array();
		}
		$arr[$key] = $data;
		$this->getRequest()->setSessionData( 'authData', $arr );
	}

	/**
	 * Fetch authentication data from the current session
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getAuthenticationData( $key, $default = null ) {
		$arr = $this->getRequest()->getSessionData( 'authData' );
		if ( is_array( $arr ) && array_key_exists( $key, $arr ) ) {
			return $arr[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Remove authentication data
	 * @param string|null $key If null, all data is removed
	 */
	public function removeAuthenticationData( $key ) {
		if ( $key === null ) {
			$this->getRequest()->setSessionData( 'authData', null );
		} else {
			$arr = $this->getRequest()->getSessionData( 'authData' );
			if ( is_array( $arr ) && array_key_exists( $key, $arr ) ) {
				unset( $arr[$key] );
				$this->getRequest()->setSessionData( 'authData', $arr );
			}
		}
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
				// @codeCoverageIgnoreStart
				throw new InvalidArgumentException(
					"AuthenticationRequest array cannot contain multiple instances of $class"
				);
				// @codeCoverageIgnoreEnd
			}
			$ret[$class] = $req;
		}
		return $ret;
	}

	/**
	 * Get the session storage backend
	 * @return BagOStuff
	 * @codeCoverageIgnore
	 */
	private function getSessionStore() {
		$config = $this->config;
		$conf = $config->get( 'AuthManagerConfig' );

		// BC checking
		if ( !isset( $conf['sessionstore']['type'] ) ) {
			$conf['sessionstore']['type'] = $config->get( 'SessionCacheType' );
		}

		// Create the session store
		$store = ObjectCache::getInstance( $conf['sessionstore']['type'] );
		if ( !isset( $conf['sessionstore']['logger'] ) ) {
			$store->setLogger( $this->logger );
		} elseif ( $conf['sessionstore']['logger'] === null ) {
			$store->setLogger( new NullLogger() );
		} else {
			$store->setLogger( MediaWiki\Logger\LoggerFactory::getInstance( $conf['sessionstore']['logger'] ) );
		}
		if ( !$config->get( 'SessionsInObjectCache' ) && !$config->get( 'SessionsInMemcached' ) ) {
			if ( $config->get( 'SessionHandler' ) ) {
				$what = '$wgSessionsInObjectCache = false and $wgSessionHandler are';
			} else {
				$what = '$wgSessionsInObjectCache = false is';
			}
			if ( !isset( $conf['sessionstore']['expiry'] ) ) {
				$conf['sessionstore']['expiry'] = $config->get( 'ObjectCacheSessionExpiry' );
			}
			$this->logger->warning(
				$what . ' ignored by AuthManager. Session data will be stored in ' .
				'"' . get_class( $store ) . '" storage with expiry ' .
				$conf['sessionstore']['expiry'] . ' seconds.'
			);
		}

		return $store;
	}

	/**
	 * Get the available SessionProviders
	 * @return SessionProvider[]
	 */
	protected function getSessionProviders() {
		if ( $this->sessionProviders === null ) {
			$this->sessionProviders = array();
			$store = $this->getSessionStore();
			$conf = $this->config->get( 'AuthManagerConfig' );
			foreach ( $conf['session'] as $spec ) {
				$provider = ObjectFactory::getObjectFromSpec( $spec );
				$provider->setLogger( $this->logger );
				$provider->setConfig( $this->config );
				$provider->setStore( $store );
				$this->sessionProviders[] = $provider;
			}
		}
		return $this->sessionProviders;
	}

	/**
	 * Create an array of AuthenticationProviders from an array of ObjectFactory specs
	 * @param string $class
	 * @param array[] $specs
	 * @return AuthenticationProvider[]
	 */
	protected function providerArrayFromSpecs( $class, array $specs ) {
		$ret = array();
		foreach ( $specs as $spec ) {
			$provider = ObjectFactory::getObjectFromSpec( $spec );
			if ( !$provider instanceof $class ) {
				throw new RuntimeException(
					"Expected instance of $class, got " . get_class( $provider )
				);
			}
			$provider->setLogger( $this->logger );
			$provider->setManager( $this );
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
				'PreAuthenticationProvider', $conf['preauth']
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
				'PrimaryAuthenticationProvider', $conf['primaryauth']
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
				'SecondaryAuthenticationProvider', $conf['secondaryauth']
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
