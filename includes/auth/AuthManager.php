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

namespace MediaWiki\Auth;

use Config;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Status;
use StatusValue;
use User;
use WebRequest;

/**
 * This serves as the entry point to the authentication system.
 *
 * In the future, it may also serve as the entry point to the authorization
 * system.
 *
 * @ingroup Auth
 * @since 1.27
 */
final class AuthManager implements LoggerAwareInterface {
	/** Log in with an existing (not necessarily local) user */
	const ACTION_LOGIN = 'login';
	/** Continue a login process that was interrupted by the need for user input or communication
	 * with an external provider */
	const ACTION_LOGIN_CONTINUE = 'login-continue';
	/** Create a new user */
	const ACTION_CREATE = 'create';
	/** Continue a user creation process that was interrupted by the need for user input or
	 * communication with an external provider */
	const ACTION_CREATE_CONTINUE = 'create-continue';
	/** Link an existing user to a third-party account */
	const ACTION_LINK = 'link';
	/** Continue a user linking process that was interrupted by the need for user input or
	 * communication with an external provider */
	const ACTION_LINK_CONTINUE = 'link-continue';
	/** Change a user's credentials */
	const ACTION_CHANGE = 'change';
	/** Delete some ceredentials */
	const ACTION_REMOVE = 'remove';
	/** Disconnect a remote account (a special case of ACTION_REMOVE) */
	const ACTION_UNLINK = 'unlink';

	/** Security-sensitive operations are ok. */
	const SEC_OK = 'ok';
	/** Security-sensitive operations should re-authenticate. */
	const SEC_REAUTH = 'reauth';
	/** Security-sensitive should not be performed. */
	const SEC_FAIL = 'fail';

	/** @var AuthManager|null */
	private static $instance = null;

	/** @var WebRequest */
	private $request;

	/** @var Config */
	private $config;

	/** @var LoggerInterface */
	private $logger;

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
				\RequestContext::getMain()->getRequest(),
				\ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
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
		$this->setLogger( \MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' ) );
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
			$session = $this->request->getSession();
			$session->set( 'AuthManager::authnState', null );
			$session->set( 'AuthManager::accountCreationState', null );
			$session->set( 'AuthManager::accountLinkState', null );
			$this->createdAccountAuthenticationRequests = array();
		}

		$this->primaryAuthenticationProviders = array();
		foreach ( $providers as $provider ) {
			if ( !$provider instanceof PrimaryAuthenticationProvider ) {
				throw new \RuntimeException(
					"Expected instance of MediaWiki\\Auth\\PrimaryAuthenticationProvider, got " .
						get_class( $provider )
				);
			}
			$provider->setLogger( $this->logger );
			$provider->setManager( $this );
			$provider->setConfig( $this->config );
			$id = $provider->getUniqueId();
			if ( isset( $this->allAuthenticationProviders[$id] ) ) {
				throw new \RuntimeException(
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
	 * @name Authentication
	 * @{
	 */

	/**
	 * Indicate whether user authentication is possible
	 *
	 * It may not be if the session is provided by something like OAuth
	 * for which each individual request includes authentication data.
	 *
	 * @return bool
	 */
	public function canAuthenticateNow() {
		return $this->request->getSession()->canSetUser();
	}

	/**
	 * Start an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse See self::continueAuthentication()
	 */
	public function beginAuthentication( array $reqs ) {
		$session = $this->request->getSession();
		if ( !$session->canSetUser() ) {
			// Caller should have called canAuthenticateNow()
			$session->set( 'AuthManager::authnState', null );
			throw new \LogicException( "Authentication is not possible now" );
		}

		// @codeCoverageIgnoreStart
		$guessUserName = null;
		foreach ( $reqs as $req ) {
			if ( $req->username !== null && $req->username !== '' ) {
				if ( $guessUserName === null ) {
					$guessUserName = $req->username;
				} elseif ( $guessUserName !== $req->username ) {
					$guessUserName = null;
					break;
				}
			}
		}
		// @codeCoverageIgnoreEnd

		// Check for special-case login of a just-created account
		$req = AuthenticationRequest::getRequestByClass( $reqs,
			'MediaWiki\\Auth\\CreatedAccountAuthenticationRequest' );
		if ( $req ) {
			if ( !in_array( $req, $this->createdAccountAuthenticationRequests, true ) ) {
				throw new \LogicException(
					'CreatedAccountAuthenticationRequests are only valid on ' .
						'the same AuthManager that created the account'
				);
			}

			$user = User::newFromName( $req->username );
			// @codeCoverageIgnoreStart
			if ( !$user ) {
				throw new \UnexpectedValueException(
					"CreatedAccountAuthenticationRequest had invalid username \"{$req->username}\""
				);
			} elseif ( $user->getId() != $req->id ) {
				throw new \UnexpectedValueException(
					"ID for \"{$req->username}\" was {$user->getId()}, expected {$req->id}"
				);
			}
			// @codeCoverageIgnoreEnd

			$this->logger->info( "Logging in $user after account creation" );
			$session->set( 'AuthManager::authnState', null );
			$this->setSessionDataForUser( $user );
			$ret = AuthenticationResponse::newPass( $user->getName() );
			\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $ret, $user, $user->getName() ) );
			return $ret;
		}

		$this->removeAuthenticationData( null );

		foreach ( $this->getPreAuthenticationProviders() as $provider ) {
			$status = $provider->testForAuthentication( $reqs );
			if ( !$status->isGood() ) {
				$this->logger->info( 'Login failed in pre-authentication by ' . $provider->getUniqueId() );
				$ret = AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
				\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $ret, null, $guessUserName ) );
				return $ret;
			}
		}

		$session = $this->request->getSession();
		$session->set( 'AuthManager::authnState', array(
			'reqs' => $reqs,
			'guessUserName' => $guessUserName,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => array(),
			'maybeLink' => array(),
		) );
		$session->persist();

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
		$session = $this->request->getSession();
		try {
			if ( !$session->canSetUser() ) {
				// Caller should have called canAuthenticateNow()
				// @codeCoverageIgnoreStart
				throw new \LogicException( "Authentication is not possible now" );
				// @codeCoverageIgnoreEnd
			}

			$state = $session->get( 'AuthManager::authnState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-authn-not-in-progress' )
				);
			}

			$guessUserName = $state['guessUserName'];

			// Step 1: Choose an primary authentication provider, and call it until it succeeds.

			if ( $state['primary'] === null ) {
				// We haven't picked a PrimaryAuthenticationProvider yet
				// @codeCoverageIgnoreStart
				$guessUserName = null;
				foreach ( $reqs as $req ) {
					if ( $req->username !== null && $req->username !== '' ) {
						if ( $guessUserName === null ) {
							$guessUserName = $req->username;
						} elseif ( $guessUserName !== $req->username ) {
							$guessUserName = null;
							break;
						}
					}
				}
				$state['guessUserName'] = $guessUserName;
				// @codeCoverageIgnoreEnd
				$state['reqs'] = $reqs;

				foreach ( $this->getPrimaryAuthenticationProviders() as $id => $provider ) {
					$res = $provider->beginPrimaryAuthentication( $reqs );
					switch ( $res->status ) {
						case AuthenticationResponse::PASS;
							$state['primary'] = $id;
							$state['primaryResponse'] = $res;
							$this->logger->info( "Primary login with $id succeeded" );
							break 2;
						case AuthenticationResponse::FAIL;
							$this->logger->info( "Login failed in primary authentication by $id" );
							$session->set( 'AuthManager::authnState', null );
							\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $res, null, $guessUserName ) );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$this->logger->info( "Primary login with $id returned $res->status" );
							$state['primary'] = $id;
							$session->set( 'AuthManager::authnState', $state );
							return $res;

							// @codeCoverageIgnoreStart
						default:
							throw new \DomainException(
								get_class( $provider ) . "::beginPrimaryAuthentication() returned $res->status"
							);
							// @codeCoverageIgnoreEnd
					}
				}
				if ( $state['primary'] === null ) {
					$this->logger->info( 'Login failed in primary authentication because no provider accepted' );
					$session->set( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-no-primary' )
					);
				}
			} elseif ( $state['primaryResponse'] === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$session->set( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}
				$id = $provider->getUniqueId();
				$res = $provider->continuePrimaryAuthentication( $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$state['primaryResponse'] = $res;
						$this->logger->info( "Primary login with $id succeeded" );
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->info( "Login failed in primary authentication by $id" );
						$session->set( 'AuthManager::authnState', null );
						\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $res, null, $guessUserName ) );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->info( "Primary login with $id returned $res->status" );
						$session->set( 'AuthManager::authnState', $state );
						return $res;
					default:
						throw new \DomainException(
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
					$session->set( 'AuthManager::authnState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}

				if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK ) {
					$state['maybeLink'][] = $res->createRequest;
					$msg = 'authmanager-authn-no-local-user-link';
				} else {
					$msg = 'authmanager-authn-no-local-user';
				}
				$this->logger->info(
					"Primary login with {$provider->getUniqueId()} succeeded, but returned no user"
				);
				$session->set( 'AuthManager::authnState', array(
					'reqs' => array(), // Will be filled in later
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => array(),
				) + $state );
				$ret = AuthenticationResponse::newRestart( wfMessage( $msg ) );
				$ret->neededRequests = $this->getAuthenticationRequests( self::ACTION_LOGIN_CONTINUE );
				$ret->createRequest = $res->createRequest;
				return $ret;
			}

			// Step 2: Primary authentication succeeded, create the User object
			// (and add the user locally if necessary)

			$user = User::newFromName( $res->username, 'usable' );
			if ( !$user ) {
				throw new \DomainException(
					get_class( $provider ) . " returned an invalid username: {$res->username}"
				);
			}
			if ( $user->getId() === 0 ) {
				// User doesn't exist locally. Create it.
				$this->logger->info( "Auto-creating $user on login" );
				$status = $this->autoCreateUser( $user, false );
				if ( !$status->isGood() ) {
					$session->set( 'AuthManager::authnState', null );
					$ret = AuthenticationResponse::newFail(
						Status::wrap( $status )->getMessage( 'authmanager-authn-autocreate-failed' )
					);
					\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $ret, $user, $user->getName() ) );
					return $ret;
				}
			}

			// Step 3: Iterate over all the secondary authentication providers.

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// This provider isn't started yet, so we pass it the set
					// of reqs from beginAuthentication instead of whatever
					// might have been used by a previous provider in line.
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
						$this->logger->info( "Secondary login with $id succeeded" );
						// fall through
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->info( "Login failed in secondary authentication by $id" );
						$session->set( 'AuthManager::authnState', null );
						\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $res, $user, $user->getName() ) );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->info( "Secondary login with $id returned " . $res->status );
						$state['secondary'][$id] = false;
						$session->set( 'AuthManager::authnState', $state );
						return $res;

						// @codeCoverageIgnoreStart
					default:
						throw new \DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status"
						);
						// @codeCoverageIgnoreEnd
				}
			}

			// Step 4: Authentication complete! Set the user in the session and
			// clean up.

			$this->logger->info( "Login for $user succeeded" );
			$session->set( 'AuthManager::authnState', null );
			$this->removeAuthenticationData( null );
			$this->setSessionDataForUser( $user );
			$ret = AuthenticationResponse::newPass( $user->getName() );
			\Hooks::run( 'AuthManagerLoginAuthenticateAudit', array( $ret, $user, $user->getName() ) );
			return $ret;
		} catch ( \Exception $ex ) {
			$session->set( 'AuthManager::authnState', null );
			throw $ex;
		}
	}

	/**
	 * Whether security-sensitive operations should proceed.
	 *
	 * A "security-sensitive operation" is something like a password or email
	 * change, that would normally have a "reenter your password to confirm"
	 * box if we only supported password-based authentication.
	 *
	 * @param string Operation being checked. This should be a message-key-like
	 *  string such as 'change-password' or 'change-email'.
	 * @return string One of the SEC_* constants.
	 */
	public function securitySensitiveOperationStatus( $operation ) {
		$status = self::SEC_OK;

		$this->logger->debug( __METHOD__ . ": Checking $operation" );

		$session = $this->request->getSession();
		$aId = $session->getUser()->getId();
		if ( $aId === 0 ) {
			// User isn't authenticated. DWIM?
			$status = $this->canAuthenticateNow() ? self::SEC_REAUTH : self::SEC_FAIL;
			$this->logger->info( __METHOD__ . ": Not logged in! $operation is $status" );
			return $status;
		}

		if ( $session->canSetUser() ) {
			$id = $session->get( 'AuthManager:lastAuthId' );
			$last = $session->get( 'AuthManager:lastAuthTimestamp' );
			if ( $id !== $aId || $last === null ) {
				$timeSinceLogin = PHP_INT_MAX; // Forever ago
			} else {
				$timeSinceLogin = max( 0, time() - $last );
			}

			$thresholds = $this->config->get( 'ReauthenticateTime' );
			if ( isset( $thresholds[$operation] ) ) {
				$threshold = $thresholds[$operation];
			} elseif ( isset( $thresholds['default'] ) ) {
				$threshold = $thresholds['default'];
			} else {
				throw new \UnexpectedValueException( '$wgReauthenticateTime lacks a default' );
			}

			if ( $threshold >= 0 && $timeSinceLogin > $threshold ) {
				$status = self::SEC_REAUTH;
			}
		} else {
			$timeSinceLogin = -1;

			$pass = $this->config->get( 'AllowSecuritySensitiveOperationIfCannotReauthenticate' );
			if ( isset( $pass[$operation] ) ) {
				$status = $pass[$operation] ? self::SEC_OK : self::SEC_FAIL;
			} elseif ( isset( $pass['default'] ) ) {
				$status = $pass['default'] ? self::SEC_OK : self::SEC_FAIL;
			} else {
				throw new \UnexpectedValueException(
					'$wgAllowSecuritySensitiveOperationIfCannotReauthenticate lacks a default'
				);
			}
		}

		\Hooks::run( 'SecuritySensitiveOperationStatus', array(
			&$status, $operation, $session, $timeSinceLogin
		) );

		// If authentication is not possible, downgrade from "REAUTH" to "FAIL".
		if ( !$this->canAuthenticateNow() && $status === self::SEC_REAUTH ) {
			$status = self::SEC_FAIL;
		}

		$this->logger->info( __METHOD__ . ": $operation is $status" );

		return $status;
	}

	/**
	 * Determine whether a username can authenticate
	 *
	 * @param string $username
	 * @return bool
	 */
	public function userCanAuthenticate( $username ) {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( $provider->testUserCanAuthenticate( $username ) ) {
				return true;
			}
		}
		return false;
	}

	/**@}*/

	/**
	 * @name Authentication data changing
	 * @{
	 */

	/**
	 * Revoke any authentication credentials for a user
	 *
	 * After this, the user should no longer be able to log in.
	 *
	 * @param string $username
	 */
	public function revokeAccessForUser( $username ) {
		$this->logger->info( "Revoking access for $username" );
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$provider->providerRevokeAccessForUser( $username );
		}
	}

	/**
	 * Indicate whether an AuthenticationRequest type is supported for changing
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function allowsAuthenticationDataChangeType( $type ) {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( !$provider->providerAllowsAuthenticationDataChangeType( $type ) ) {
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
	public function allowsAuthenticationDataChange( AuthenticationRequest $req ) {
		$any = false;
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$status = $provider->providerAllowsAuthenticationDataChange( $req );
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
			$status = $provider->providerAllowsAuthenticationDataChange( $req );
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
	public function changeAuthenticationData( AuthenticationRequest $req ) {
		$this->logger->info(
			'Changing authentication data for ' .
				( is_string( $req->username ) ? $req->username : get_class( $req ) )
		);
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
	 *   self::getAuthenticationRequests(), a UserDataAuthenticationRequest
	 *   may be included in $reqs
	 * @param string $username
	 * @param User $creator User doing the account creation
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( $username, User $creator, array $reqs ) {
		$session = $this->request->getSession();
		if ( !$this->canCreateAccounts() ) {
			// Caller should have called canCreateAccounts()
			$session->set( 'AuthManager::accountCreationState', null );
			throw new \LogicException( "Account creation is not possible" );
		}

		if ( $this->userExists( $username ) ) {
			$this->logger->debug( __METHOD__ . ': User exists in some provider' );
			return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
		}

		$user = User::newFromName( $username, 'creatable' );
		if ( !is_object( $user ) ) {
			$this->logger->debug( __METHOD__ . ': Invalid username' );
			return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
		} elseif ( $user->getId() !== 0 ) {
			$this->logger->debug( __METHOD__ . ': User exists locally' );
			return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
		}
		foreach ( $reqs as $req ) {
			$req->username = $username;
			if ( $req instanceof UserDataAuthenticationRequest ) {
				$req->populateUser( $user );
			}
		}

		$this->removeAuthenticationData( null );

		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $id => $provider ) {
			$status = $provider->testForAccountCreation( $user, $creator, $reqs );
			if ( !$status->isGood() ) {
				$this->logger->debug( __METHOD__ . ": Fail in pre-authentication by $id" );
				return AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
			}
		}

		$session->set( 'AuthManager::accountCreationState', array(
			'username' => $username,
			'userid' => 0,
			'reqs' => $reqs,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => array(),
		) );
		$session->persist();

		return $this->continueAccountCreation( $reqs );
	}

	/**
	 * Continue an account creation flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( array $reqs ) {
		$session = $this->request->getSession();
		try {
			if ( !$this->canCreateAccounts() ) {
				// Caller should have called canCreateAccounts()
				$session->set( 'AuthManager::accountCreationState', null );
				throw new \LogicException( "Account creation is not possible" );
			}

			$state = $session->get( 'AuthManager::accountCreationState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-create-not-in-progress' )
				);
			}

			// Step 0: Prepare and validate the input

			$user = User::newFromName( $state['username'], 'creatable' );
			if ( !is_object( $user ) ) {
				$session->set( 'AuthManager::accountCreationState', null );
				$this->logger->debug( __METHOD__ . ': Invalid username' );
				return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
			}
			if ( $state['userid'] === 0 ) {
				if ( $user->getId() != 0 ) {
					$session->set( 'AuthManager::accountCreationState', null );
					$this->logger->debug( __METHOD__ . ': User exists locally' );
					return AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
				}
			} else {
				if ( $user->getId() == 0 ) {
					$this->logger->debug( __METHOD__ . ': User does not exist locally when it should' );
					throw new \UnexpectedValueException(
						"User \"{$state['username']}\" should exist now, but doesn't!"
					);
				}
				if ( $user->getId() != $state['userid'] ) {
					$this->logger->debug( __METHOD__ . ': User ID/name mismatch' );
					throw new \UnexpectedValueException(
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

			// Step 1: Choose a primary authentication provider and call it until it succeeds.

			if ( $state['primary'] === null ) {
				// We haven't picked a PrimaryAuthenticationProvider yet
				foreach ( $this->getPrimaryAuthenticationProviders() as $id => $provider ) {
					if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_NONE ) {
						continue;
					}
					$res = $provider->beginPrimaryAccountCreation( $user, $reqs );
					switch ( $res->status ) {
						case AuthenticationResponse::PASS;
							$this->logger->debug( __METHOD__ . ": Primary creation passed by $id" );
							$state['primary'] = $id;
							$state['primaryResponse'] = $res;
							break 2;
						case AuthenticationResponse::FAIL;
							$this->logger->debug( __METHOD__ . ": Primary creation failed by $id" );
							$session->set( 'AuthManager::accountCreationState', null );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$this->logger->debug( __METHOD__ . ": Primary creation $res->status by $id" );
							$state['primary'] = $id;
							$session->set( 'AuthManager::accountCreationState', $state );
							return $res;

							// @codeCoverageIgnoreStart
						default:
							throw new \DomainException(
								get_class( $provider ) . "::beginPrimaryAccountCreation() returned $res->status"
							);
							// @codeCoverageIgnoreEnd
					}
				}
				if ( $state['primary'] === null ) {
					$this->logger->debug( __METHOD__ . ": Primary creation failed because no provider accepted" );
					$session->set( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-no-primary' )
					);
				}
			} elseif ( $state['primaryResponse'] === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$session->set( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-not-in-progress' )
					);
					// @codeCoverageIgnoreEnd
				}
				$id = $provider->getUniqueId();
				$res = $provider->continuePrimaryAccountCreation( $user, $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
					$this->logger->debug( __METHOD__ . ": Primary creation passed by $id" );
						$state['primaryResponse'] = $res;
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->debug( __METHOD__ . ": Primary creation failed by $id" );
						$session->set( 'AuthManager::accountCreationState', null );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( __METHOD__ . ": Primary creation $res->status by $id" );
						$session->set( 'AuthManager::accountCreationState', $state );
						return $res;
					default:
						throw new \DomainException(
							get_class( $provider ) . "::continuePrimaryAccountCreation() returned $res->status"
						);
				}
			}

			// Step 2: Primary authentication succeeded, create the User object
			// and add the user locally.

			if ( $state['userid'] === 0 ) {
				$this->logger->info( "Creating user $user during account creation" );
				$status = $user->addToDatabase();
				if ( !$status->isOk() ) {
					// @codeCoverageIgnoreStart
					$session->set( 'AuthManager::accountCreationState', null );
					return AuthenticationResponse::newFail( $status->getMessage() );
					// @codeCoverageIgnoreEnd
				}
				$user->setToken();
				\Hooks::run( 'LocalUserCreated', array( $user, false ) );
				$user->saveSettings();
				$state['userid'] = $user->getId();

				// Update user count
				\DeferredUpdates::addUpdate( new \SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

				// Watch user's userpage and talk page
				$user->addWatch( $user->getUserPage(), \WatchedItem::IGNORE_USER_RIGHTS );

				// Inform the provider
				$provider->finishAccountCreation( $user, $state['primaryResponse'] );
			}

			// Step 3: Iterate over all the secondary authentication providers.

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// This provider isn't started yet, so we pass it the set
					// of reqs from beginAuthentication instead of whatever
					// might have been used by a previous provider in line.
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
						$this->logger->debug( __METHOD__ . ": Secondary creation passed by $id" );
						// fall through
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( __METHOD__ . ": Secondary creation $res->status by $id" );
						$state['secondary'][$id] = false;
						$session->set( 'AuthManager::accountCreationState', $state );
						return $res;
					case AuthenticationResponse::FAIL;
						throw new \DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status." .
							' Secondary providers are not allowed to fail account creation, that' .
							' should have been done via testForAccountCreation().'
						);
							// @codeCoverageIgnoreStart
					default:
						throw new \DomainException(
							get_class( $provider ) . "::{$func}() returned $res->status"
						);
							// @codeCoverageIgnoreEnd
				}
			}

			$id = $user->getId();
			$name = $user->getName();
			$req = new CreatedAccountAuthenticationRequest( $id, $name );

			$this->logger->debug( __METHOD__ . ": Account creation succeeded for $user" );
			$session->set( 'AuthManager::accountCreationState', null );
			$this->removeAuthenticationData( null );
			$ret = AuthenticationResponse::newPass( $name );
			$ret->loginRequest = $req;
			$this->createdAccountAuthenticationRequests[] = $req;
			return $ret;
		} catch ( \Exception $ex ) {
			$session->set( 'AuthManager::accountCreationState', null );
			throw $ex;
		}
	}

	/**
	 * Auto-create an account, and log into that account
	 * @param User $user User to auto-create
	 * @param bool $login Whether to also log the user in
	 * @return Status Good if user was created, Ok if user already existed, otherwise Fatal
	 */
	public function autoCreateUser( User $user, $login = true ) {
		$username = $user->getName();

		// Try the local user from the slave DB
		$localId = User::idFromName( $username );

		// Fetch the user ID from the master, so that we don't try to create the user
		// when they already exist, due to replication lag
		// @codeCoverageIgnoreStart
		if ( !$localId && wfGetLB()->getReaderIndex() != 0 ) {
			$localId = User::idFromName( $username, User::READ_LATEST );
		}
		// @codeCoverageIgnoreEnd

		if ( $localId ) {
			$this->logger->debug( __METHOD__ . ": already exists locally" );
			$user->setId( $localId );
			$user->loadFromId();
			if ( $login ) {
				$this->setSessionDataForUser( $user );
			}
			$status = Status::newGood();
			$status->warning( 'userexists' );
			return $status;
		}

		// Wiki is read-only?
		if ( wfReadOnly() ) {
			$this->logger->debug( __METHOD__ . ': denied by wfReadOnly(): ' . wfReadOnlyReason() );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		}

		// Check the session, if we tried to create this user already there's
		// no point in retrying.
		$session = $this->request->getSession();
		if ( $session->get( 'AuthManager::AutoCreateBlacklist' ) ) {
			$this->logger->debug( __METHOD__ . ': blacklisted in session' );
			$user->setId( 0 );
			$user->loadFromId();
			$reason = $session->get( 'AuthManager::AutoCreateBlacklist' );
			if ( $reason instanceof StatusValue ) {
				return Status::wrap( $reason );
			} else {
				return Status::newFatal( $reason );
			}
		}

		// Is the username creatable?
		if ( !User::isCreatableName( $username ) ) {
			$this->logger->debug( __METHOD__ . ": name is not creatable" );
			$session->set( 'AuthManager::AutoCreateBlacklist', 'noname', 600 );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'noname' );
		}

		// Is the IP user able to create accounts?
		$anon = new User;
		if ( !$anon->isAllowedAny( 'createaccount', 'autocreateaccount' ) ) {
			$this->logger->debug( __METHOD__ . ': IP lacks the ability to create or autocreate accounts' );
			$session->set( 'AuthManager::AutoCreateBlacklist', 'authmanager-autocreate-noperm', 600 );
			$session->persist();
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'authmanager-autocreate-noperm' );
		}

		// Denied by providers?
		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->testForAutoCreation( $user );
			if ( !$status->isGood() ) {
				$ret = Status::wrap( $status );
				$this->logger->debug( __METHOD__ . ": Provider denied creation: " . $ret->getWikiText() );
				$session->set( 'AuthManager::AutoCreateBlacklist', $status, 600 );
				$user->setId( 0 );
				$user->loadFromId();
				return $ret;
			}
		}

		// Ignore warnings about master connections/writes...hard to avoid here
		\Profiler::instance()->getTransactionProfiler()->resetExpectations();

		$cache = \ObjectCache::getLocalClusterInstance();
		$backoffKey = wfMemcKey( 'AuthManager', 'autocreate-failed', md5( $username ) );
		if ( $cache->get( $backoffKey ) ) {
			$this->logger->debug( __METHOD__ . ': denied by prior creation attempt failures' );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'authmanager-autocreate-exception' );
		}

		// Checks passed, create the user...
		$from = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : 'CLI';
		$this->logger->info( __METHOD__ . ": creating new user ($username) - from: $from" );

		try {
			$status = $user->addToDatabase();
			if ( !$status->isOk() ) {
				// @codeCoverageIgnoreStart
				$this->logger->error( __METHOD__ . ': failed with message ' . $status->getWikiText() );
				$user->setId( 0 );
				$user->loadFromId();
				return $status;
				// @codeCoverageIgnoreEnd
			}
		} catch ( \Exception $ex ) {
			// @codeCoverageIgnoreStart
			$this->logger->error( __METHOD__ . ': failed with exception ' . $ex->getMessage() );
			// Do not keep throwing errors for a while
			$cache->set( $backoffKey, 1, 600 );
			// Bubble up error; which should normally trigger DB rollbacks
			throw $ex;
			// @codeCoverageIgnoreEnd
		}

		$user->setToken();
		\Hooks::run( 'AuthPluginAutoCreate', array( $user ), '1.27' );
		\Hooks::run( 'LocalUserCreated', array( $user, true ) );
		$user->saveSettings();

		// Update user count
		\DeferredUpdates::addUpdate( new \SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

		// Watch user's userpage and talk page
		$user->addWatch( $user->getUserPage(), \WatchedItem::IGNORE_USER_RIGHTS );

		// Inform the providers
		$providers = $this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$provider->autoCreatedAccount( $user );
		}

		if ( $login ) {
			$this->setSessionDataForUser( $user );
		}

		return Status::newGood();
	}

	/**@}*/

	/**
	 * @name Account linking
	 * @{
	 */

	/**
	 * Determine whether accounts can be linked
	 * @return bool
	 */
	public function canLinkAccounts() {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Start an account linking flow
	 *
	 * @param User $user User being linked
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountLink( User $user, array $reqs ) {
		$session = $this->request->getSession();
		$session->set( 'AuthManager::accountLinkState', null );

		if ( !$this->canLinkAccounts() ) {
			// Caller should have called canLinkAccounts()
			throw new \LogicException( "Account linking is not possible" );
		}

		if ( $user->getId() === 0 ) {
			if ( !User::isUsableName( $user->getName() ) ) {
				$msg = wfMessage( 'noname' );
			} else {
				$msg = wfMessage( 'authmanager-userdoesnotexist', $user->getName() );
			}
			return AuthenticationResponse::newFail( $msg );
		}
		foreach ( $reqs as $req ) {
			$req->username = $user->getName();
		}

		$this->removeAuthenticationData( null );

		$providers = $this->getPreAuthenticationProviders();
		foreach ( $providers as $id => $provider ) {
			$status = $provider->testForAccountLink( $user );
			if ( !$status->isGood() ) {
				$this->logger->debug( __METHOD__ . ": Account linking pre-check failed by $id" );
				return AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
			}
		}

		$state = array(
			'username' => $user->getName(),
			'userid' => $user->getId(),
			'primary' => null,
		);

		$providers = $this->getPrimaryAuthenticationProviders();
		foreach ( $providers as $id => $provider ) {
			if ( $provider->accountCreationType() !== PrimaryAuthenticationProvider::TYPE_LINK ) {
				continue;
			}

			$res = $provider->beginPrimaryAccountLink( $user, $reqs );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS;
					$this->logger->info( "Account linked to $user by $id" );
					return $res;

				case AuthenticationResponse::FAIL;
					$this->logger->debug( __METHOD__ . ": Account linking failed by $id" );
					return $res;

				case AuthenticationResponse::ABSTAIN;
					// Continue loop
					break;

				case AuthenticationResponse::REDIRECT;
				case AuthenticationResponse::UI;
					$this->logger->debug( __METHOD__ . ": Account linking $res->status by $id" );
					$state['primary'] = $id;
					$session->set( 'AuthManager::accountLinkState', $state );
					$session->persist();
					return $res;

					// @codeCoverageIgnoreStart
				default:
					throw new \DomainException(
						get_class( $provider ) . "::beginPrimaryAccountLink() returned $res->status"
					);
					// @codeCoverageIgnoreEnd
			}
		}

		$this->logger->debug( __METHOD__ . ": Account linking failed because no provider accepted" );
		return AuthenticationResponse::newFail(
			wfMessage( 'authmanager-link-no-primary' )
		);
	}

	/**
	 * Continue an account linking flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountLink( array $reqs ) {
		$session = $this->request->getSession();
		try {
			if ( !$this->canLinkAccounts() ) {
				// Caller should have called canLinkAccounts()
				$session->set( 'AuthManager::accountLinkState', null );
				throw new \LogicException( "Account linking is not possible" );
			}

			$state = $session->get( 'AuthManager::accountLinkState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-link-not-in-progress' )
				);
			}

			// Step 0: Prepare and validate the input

			$user = User::newFromName( $state['username'], 'usable' );
			if ( !is_object( $user ) ) {
				$session->set( 'AuthManager::accountLinkState', null );
				return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
			}
			if ( $user->getId() != $state['userid'] ) {
				throw new \UnexpectedValueException(
					"User \"{$state['username']}\" is valid, but " .
						"ID {$user->getId()} != {$state['userid']}!"
				);
			}

			foreach ( $reqs as $req ) {
				$req->username = $state['username'];
			}

			// Step 1: Call the primary again until it succeeds

			$provider = $this->getAuthenticationProvider( $state['primary'] );
			if ( !$provider instanceof PrimaryAuthenticationProvider ) {
				// Configuration changed? Force them to start over.
				// @codeCoverageIgnoreStart
				$session->set( 'AuthManager::accountLinkState', null );
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-link-not-in-progress' )
				);
				// @codeCoverageIgnoreEnd
			}
			$id = $provider->getUniqueId();
			$res = $provider->continuePrimaryAccountLink( $user, $reqs );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS;
					$this->logger->info( "Account linked to $user by $id" );
					$session->set( 'AuthManager::accountLinkState', null );
					return $res;
				case AuthenticationResponse::FAIL;
					$this->logger->debug( __METHOD__ . ": Account linking failed by $id" );
					$session->set( 'AuthManager::accountLinkState', null );
					return $res;
				case AuthenticationResponse::REDIRECT;
				case AuthenticationResponse::UI;
					$this->logger->debug( __METHOD__ . ": Account linking $res->status by $id" );
					$session->set( 'AuthManager::accountLinkState', $state );
					return $res;
				default:
					throw new \DomainException(
						get_class( $provider ) . "::continuePrimaryAccountLink() returned $res->status"
					);
			}
		} catch ( \Exception $ex ) {
			$session->set( 'AuthManager::accountLinkState', null );
			throw $ex;
		}
	}

	/**@}*/

	/**
	 * @name Information methods
	 * @{
	 */

	/**
	 * Return the applicable list of AuthenticationRequest classes
	 *
	 * Possible values for $action:
	 *  - ACTION_LOGIN: Valid for passing to beginAuthentication
	 *  - ACTION_LOGIN_CONTINUE: Valid for passing to continueAuthentication in the current state
	 *  - ACTION_CREATE: Valid for passing to beginAccountCreation
	 *  - ACTION_CREATE_CONTINUE: Valid for passing to continueAccountCreation in the current state
	 *  - ACTION_LINK: Valid for passing to beginAccountLink
	 *  - ACTION_LINK_CONTINUE: Valid for passing to continueAccountLink in the current state
	 *  - ACTION_CHANGE: Valid for changeAuthenticationData
	 *  - ACTION_REMOVE: Valid for removeAuthenticationData
	 *  - ACTION_UNLINK: Same as ACTION_REMOVE, but linking providers only
	 *
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $action ) {
		$requests = $this->getAuthenticationRequests( $action );
		$classes = array_map( 'get_class', $requests );
		return array_keys( array_flip( $classes ) ); // deduplicate
	}

	/**
	 * Return the applicable list of AuthenticationRequests
	 *
	 * Possible values for $action:
	 *  - ACTION_LOGIN: Valid for passing to beginAuthentication
	 *  - ACTION_LOGIN_CONTINUE: Valid for passing to continueAuthentication in the current state
	 *  - ACTION_CREATE: Valid for passing to beginAccountCreation
	 *  - ACTION_CREATE_CONTINUE: Valid for passing to continueAccountCreation in the current state
	 *  - ACTION_LINK: Valid for passing to beginAccountLink
	 *  - ACTION_LINK_CONTINUE: Valid for passing to continueAccountLink in the current state
	 *  - ACTION_CHANGE: Valid for changeAuthenticationData
	 *  - ACTION_REMOVE: Valid for removeAuthenticationData
	 *  - ACTION_UNLINK: Same as ACTION_REMOVE, but linking providers only
	 *
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param string|null Return-to URL, in case of redirect flow. Not used for CHANGE/REMOVE.
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests( $action, $returnToUrl = null ) {
		// Figure out which providers to query
		switch ( $action ) {
			case self::ACTION_LOGIN:
			case self::ACTION_CREATE:
				$providers =
					$this->getPreAuthenticationProviders() +
					$this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
				break;

			case self::ACTION_LOGIN_CONTINUE:
			case self::ACTION_CREATE_CONTINUE:
				if ( $action === self::ACTION_LOGIN_CONTINUE ) {
					$key = 'AuthManager::authnState';
				} else {
					$key = 'AuthManager::accountCreationState';
				}

				$state = $this->request->getSessionData( $key );
				if ( !is_array( $state ) ) {
					return array();
				}
				if ( $state['primary'] === null ) {
					if ( $action === self::ACTION_LOGIN_CONTINUE ) {
						$action = self::ACTION_LOGIN;
						$providers =
							$this->getPrimaryAuthenticationProviders() +
							$this->getSecondaryAuthenticationProviders();
					} else {
						return array();
					}
				} elseif ( $state['primaryResponse'] === null ) {
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

			case self::ACTION_LINK:
			case self::ACTION_UNLINK:
				$providers =
					array_filter( $this->getPrimaryAuthenticationProviders(), function ( $p ) {
						return $p->accountCreationType() ===
							   PrimaryAuthenticationProvider::TYPE_LINK;
					} );
				break;

			case self::ACTION_LINK_CONTINUE:
				$key = 'AuthManager::accountLinkState';
				$state = $this->request->getSessionData( $key );
				if ( !is_array( $state ) ) {
					return array();
				}
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ||
					 $provider->accountCreationType() !== PrimaryAuthenticationProvider::TYPE_LINK
				) {
					return array();
				}
				$providers = array( $provider );
				break;

			case self::ACTION_CHANGE:
			case self::ACTION_REMOVE:
				$providers = $this->getPrimaryAuthenticationProviders();
				break;

			// @codeCoverageIgnoreStart
			default:
				throw new \DomainException( __METHOD__ . ": Invalid action \"$action\"" );
		}
		// @codeCoverageIgnoreEnd

		$requests = array();
		foreach ( $providers as $provider ) {
			$requests = array_merge( $requests, $provider->getAuthenticationRequests( $action ) );
		}

		// For account creation there is an extra request used by AuthManager itself
		if ( $action === self::ACTION_CREATE ) {
			$requests[] = new UserDataAuthenticationRequest();
		}

		// Filter out duplicates
		$requests = array_map( 'serialize', $requests );
		$requests = array_keys( array_flip( $requests ) );
		$requests = array_map( 'unserialize', $requests );

		$actionMap = array(
			AuthManager::ACTION_LOGIN_CONTINUE => AuthManager::ACTION_LOGIN,
			AuthManager::ACTION_CREATE_CONTINUE => AuthManager::ACTION_CREATE,
			AuthManager::ACTION_LINK_CONTINUE => AuthManager::ACTION_LINK,
		);
		$simpleAction = isset( $actionMap[$action] ) ? $actionMap[$action] : $action;
		foreach ( $requests as $req ) {
			/** @var $req AuthenticationRequest */
			$req->returnToUrl = $returnToUrl;
			$req->action = $simpleAction;
		}

		// For self::ACTION_CHANGE, filter out any that something else *doesn't* allow changing
		if ( $action === self::ACTION_CHANGE ) {
			$that = $this;
			$requests = array_values( array_filter( $requests, function ( $req ) use ( $that ) {
				// these are placeholder requests so allowsAuthenticationDataChange wouldn't make sense
				return $that->allowsAuthenticationDataChangeType( get_class( $req ) );
			} ) );
		}

		return $requests;
	}

	/**
	 * Determine whether a username exists
	 * @param string $username
	 * @return bool
	 */
	public function userExists( $username ) {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( $provider->testUserExists( $username ) ) {
				return true;
			}
		}

		return false;
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
	public function allowsPropertyChange( $property ) {
		$providers = $this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			if ( !$provider->providerAllowsPropertyChange( $property ) ) {
				return false;
			}
		}
		return true;
	}

	/**@}*/

	/**
	 * @name Internal methods
	 * @{
	 */

	/**
	 * Store authentication in the current session
	 * @protected For use by AuthenticationProviders
	 * @param string $key
	 * @param mixed $data Must be serializable
	 */
	public function setAuthenticationData( $key, $data ) {
		$session = $this->request->getSession();
		$arr = $session->get( 'authData' );
		if ( !is_array( $arr ) ) {
			$arr = array();
		}
		$arr[$key] = $data;
		$session->set( 'authData', $arr );
	}

	/**
	 * Fetch authentication data from the current session
	 * @protected For use by AuthenticationProviders
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getAuthenticationData( $key, $default = null ) {
		$arr = $this->request->getSessionData( 'authData' );
		if ( is_array( $arr ) && array_key_exists( $key, $arr ) ) {
			return $arr[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Remove authentication data
	 * @protected For use by AuthenticationProviders
	 * @param string|null $key If null, all data is removed
	 */
	public function removeAuthenticationData( $key ) {
		$session = $this->request->getSession();
		if ( $key === null ) {
			$session->set( 'authData', null );
		} else {
			$arr = $session->get( 'authData' );
			if ( is_array( $arr ) && array_key_exists( $key, $arr ) ) {
				unset( $arr[$key] );
				$session->set( 'authData', $arr );
			}
		}
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
			$provider = \ObjectFactory::getObjectFromSpec( $spec );
			if ( !$provider instanceof $class ) {
				throw new \RuntimeException(
					"Expected instance of $class, got " . get_class( $provider )
				);
			}
			$provider->setLogger( $this->logger );
			$provider->setManager( $this );
			$provider->setConfig( $this->config );
			$id = $provider->getUniqueId();
			if ( isset( $this->allAuthenticationProviders[$id] ) ) {
				throw new \RuntimeException(
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
				'MediaWiki\\Auth\\PreAuthenticationProvider', $conf['preauth']
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
				'MediaWiki\\Auth\\PrimaryAuthenticationProvider', $conf['primaryauth']
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
				'MediaWiki\\Auth\\SecondaryAuthenticationProvider', $conf['secondaryauth']
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

	/**
	 * @param User $user
	 */
	private function setSessionDataForUser( $user ) {
		$session = $this->request->getSession();
		$delay = $session->delaySave();

		$session->resetId();
		if ( $session->canSetUser() ) {
			$session->setUser( $user );
		}
		$session->set( 'AuthManager:lastAuthId', $user->getId() );
		$session->set( 'AuthManager:lastAuthTimestamp', time() );
		$session->persist();

		\ScopedCallback::consume( $delay );

		\Hooks::run( 'UserLoggedIn', array( $user ) );
	}

	/**@}*/

}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
