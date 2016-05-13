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
class AuthManager implements LoggerAwareInterface {
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
	/** Remove a user's credentials */
	const ACTION_REMOVE = 'remove';
	/** Like ACTION_REMOVE but for linking providers only */
	const ACTION_UNLINK = 'unlink';

	/** Security-sensitive operations are ok. */
	const SEC_OK = 'ok';
	/** Security-sensitive operations should re-authenticate. */
	const SEC_REAUTH = 'reauth';
	/** Security-sensitive should not be performed. */
	const SEC_FAIL = 'fail';

	/** Auto-creation is due to SessionManager */
	const AUTOCREATE_SOURCE_SESSION = \MediaWiki\Session\SessionManager::class;

	/** @var AuthManager|null */
	private static $instance = null;

	/** @var WebRequest */
	private $request;

	/** @var Config */
	private $config;

	/** @var LoggerInterface */
	private $logger;

	/** @var AuthenticationProvider[] */
	private $allAuthenticationProviders = [];

	/** @var PreAuthenticationProvider[] */
	private $preAuthenticationProviders = null;

	/** @var PrimaryAuthenticationProvider[] */
	private $primaryAuthenticationProviders = null;

	/** @var SecondaryAuthenticationProvider[] */
	private $secondaryAuthenticationProviders = null;

	/** @var CreatedAccountAuthenticationRequest[] */
	private $createdAccountAuthenticationRequests = [];

	/**
	 * Get the global AuthManager
	 * @return AuthManager
	 */
	public static function singleton() {
		global $wgDisableAuthManager;

		if ( $wgDisableAuthManager ) {
			throw new \BadMethodCallException( '$wgDisableAuthManager is set' );
		}

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
			$session->remove( 'AuthManager::authnState' );
			$session->remove( 'AuthManager::accountCreationState' );
			$session->remove( 'AuthManager::accountLinkState' );
			$this->createdAccountAuthenticationRequests = [];
		}

		$this->primaryAuthenticationProviders = [];
		foreach ( $providers as $provider ) {
			if ( !$provider instanceof PrimaryAuthenticationProvider ) {
				throw new \RuntimeException(
					'Expected instance of MediaWiki\\Auth\\PrimaryAuthenticationProvider, got ' .
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
			return call_user_func_array( [ $wgAuth, $method ], $params );
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
	 * @param string $returnToUrl Url that REDIRECT responses should eventually
	 *  return to.
	 * @return AuthenticationResponse See self::continueAuthentication()
	 */
	public function beginAuthentication( array $reqs, $returnToUrl ) {
		$session = $this->request->getSession();
		if ( !$session->canSetUser() ) {
			// Caller should have called canAuthenticateNow()
			$session->remove( 'AuthManager::authnState' );
			throw new \LogicException( 'Authentication is not possible now' );
		}

		$guessUserName = null;
		foreach ( $reqs as $req ) {
			$req->returnToUrl = $returnToUrl;
			// @codeCoverageIgnoreStart
			if ( $req->username !== null && $req->username !== '' ) {
				if ( $guessUserName === null ) {
					$guessUserName = $req->username;
				} elseif ( $guessUserName !== $req->username ) {
					$guessUserName = null;
					break;
				}
			}
			// @codeCoverageIgnoreEnd
		}

		// Check for special-case login of a just-created account
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, CreatedAccountAuthenticationRequest::class
		);
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

			$this->logger->info( 'Logging in {user} after account creation', [
				'user' => $user->getName(),
			] );
			$ret = AuthenticationResponse::newPass( $user->getName() );
			$this->setSessionDataForUser( $user );
			$this->callMethodOnProviders( 7, 'postAuthentication', [ $user, $ret ] );
			$session->remove( 'AuthManager::authnState' );
			\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $ret, $user, $user->getName() ] );
			return $ret;
		}

		$this->removeAuthenticationSessionData( null );

		foreach ( $this->getPreAuthenticationProviders() as $provider ) {
			$status = $provider->testForAuthentication( $reqs );
			if ( !$status->isGood() ) {
				$this->logger->debug( 'Login failed in pre-authentication by ' . $provider->getUniqueId() );
				$ret = AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
				$this->callMethodOnProviders( 7, 'postAuthentication',
					[ User::newFromName( $guessUserName ) ?: null, $ret ]
				);
				\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $ret, null, $guessUserName ] );
				return $ret;
			}
		}

		$state = [
			'reqs' => $reqs,
			'returnToUrl' => $returnToUrl,
			'guessUserName' => $guessUserName,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => [],
			'maybeLink' => [],
			'continueRequests' => [],
		];

		// Preserve state from a previous failed login
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, CreateFromLoginAuthenticationRequest::class
		);
		if ( $req ) {
			$state['maybeLink'] = $req->maybeLink;
		}

		$session = $this->request->getSession();
		$session->setSecret( 'AuthManager::authnState', $state );
		$session->persist();

		return $this->continueAuthentication( $reqs );
	}

	/**
	 * Continue an authentication flow
	 *
	 * Return values are interpreted as follows:
	 * - status FAIL: Authentication failed. If $response->createRequest is
	 *   set, that may be passed to self::beginAuthentication() or to
	 *   self::beginAccountCreation() (after adding a username, if necessary)
	 *   to preserve state.
	 * - status REDIRECT: The client should be redirected to the contained URL,
	 *   new AuthenticationRequests should be made (if any), then
	 *   AuthManager::continueAuthentication() should be called.
	 * - status UI: The client should be presented with a user interface for
	 *   the fields in the specified AuthenticationRequests, then new
	 *   AuthenticationRequests should be made, then
	 *   AuthManager::continueAuthentication() should be called.
	 * - status RESTART: The user logged in successfully with a third-party
	 *   service, but the third-party credentials aren't attached to any local
	 *   account. This could be treated as a UI or a FAIL.
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
				throw new \LogicException( 'Authentication is not possible now' );
				// @codeCoverageIgnoreEnd
			}

			$state = $session->getSecret( 'AuthManager::authnState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-authn-not-in-progress' )
				);
			}
			$state['continueRequests'] = [];

			$guessUserName = $state['guessUserName'];

			foreach ( $reqs as $req ) {
				$req->returnToUrl = $state['returnToUrl'];
			}

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
							$this->logger->debug( "Primary login with $id succeeded" );
							break 2;
						case AuthenticationResponse::FAIL;
							$this->logger->debug( "Login failed in primary authentication by $id" );
							if ( $res->createRequest || $state['maybeLink'] ) {
								$res->createRequest = new CreateFromLoginAuthenticationRequest(
									$res->createRequest, $state['maybeLink']
								);
							}
							$this->callMethodOnProviders( 7, 'postAuthentication',
								[ User::newFromName( $guessUserName ) ?: null, $res ]
							);
							$session->remove( 'AuthManager::authnState' );
							\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $res, null, $guessUserName ] );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$this->logger->debug( "Primary login with $id returned $res->status" );
							$state['primary'] = $id;
							$state['continueRequests'] = $res->neededRequests;
							$session->setSecret( 'AuthManager::authnState', $state );
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
					$this->logger->debug( 'Login failed in primary authentication because no provider accepted' );
					$ret = AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-no-primary' )
					);
					$this->callMethodOnProviders( 7, 'postAuthentication',
						[ User::newFromName( $guessUserName ) ?: null, $ret ]
					);
					$session->remove( 'AuthManager::authnState' );
					return $ret;
				}
			} elseif ( $state['primaryResponse'] === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$ret = AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					$this->callMethodOnProviders( 7, 'postAuthentication',
						[ User::newFromName( $guessUserName ) ?: null, $ret ]
					);
					$session->remove( 'AuthManager::authnState' );
					return $ret;
					// @codeCoverageIgnoreEnd
				}
				$id = $provider->getUniqueId();
				$res = $provider->continuePrimaryAuthentication( $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$state['primaryResponse'] = $res;
						$this->logger->debug( "Primary login with $id succeeded" );
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->debug( "Login failed in primary authentication by $id" );
						if ( $res->createRequest || $state['maybeLink'] ) {
							$res->createRequest = new CreateFromLoginAuthenticationRequest(
								$res->createRequest, $state['maybeLink']
							);
						}
						$this->callMethodOnProviders( 7, 'postAuthentication',
							[ User::newFromName( $guessUserName ) ?: null, $res ]
						);
						$session->remove( 'AuthManager::authnState' );
						\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $res, null, $guessUserName ] );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( "Primary login with $id returned $res->status" );
						$state['continueRequests'] = $res->neededRequests;
						$session->setSecret( 'AuthManager::authnState', $state );
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
					$ret = AuthenticationResponse::newFail(
						wfMessage( 'authmanager-authn-not-in-progress' )
					);
					$this->callMethodOnProviders( 7, 'postAuthentication',
						[ User::newFromName( $guessUserName ) ?: null, $ret ]
					);
					$session->remove( 'AuthManager::authnState' );
					return $ret;
					// @codeCoverageIgnoreEnd
				}

				if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK &&
					$res->linkRequest &&
					 // don't confuse the user with an incorrect message if linking is disabled
					$this->getAuthenticationProvider( ConfirmLinkSecondaryAuthenticationProvider::class )
				) {
					$state['maybeLink'][$res->linkRequest->getUniqueId()] = $res->linkRequest;
					$msg = 'authmanager-authn-no-local-user-link';
				} else {
					$msg = 'authmanager-authn-no-local-user';
				}
				$this->logger->debug(
					"Primary login with {$provider->getUniqueId()} succeeded, but returned no user"
				);
				$ret = AuthenticationResponse::newRestart( wfMessage( $msg ) );
				$ret->neededRequests = $this->getAuthenticationRequestsInternal(
					self::ACTION_LOGIN,
					[],
					$this->getPrimaryAuthenticationProviders() + $this->getSecondaryAuthenticationProviders()
				);
				if ( $res->createRequest || $state['maybeLink'] ) {
					$ret->createRequest = new CreateFromLoginAuthenticationRequest(
						$res->createRequest, $state['maybeLink']
					);
					$ret->neededRequests[] = $ret->createRequest;
				}
				$session->setSecret( 'AuthManager::authnState', [
					'reqs' => [], // Will be filled in later
					'primary' => null,
					'primaryResponse' => null,
					'secondary' => [],
					'continueRequests' => $ret->neededRequests,
				] + $state );
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
				$this->logger->info( 'Auto-creating {user} on login', [
					'user' => $user->getName(),
				] );
				$status = $this->autoCreateUser( $user, $state['primary'], false );
				if ( !$status->isGood() ) {
					$ret = AuthenticationResponse::newFail(
						Status::wrap( $status )->getMessage( 'authmanager-authn-autocreate-failed' )
					);
					$this->callMethodOnProviders( 7, 'postAuthentication', [ $user, $ret ] );
					$session->remove( 'AuthManager::authnState' );
					\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $ret, $user, $user->getName() ] );
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
						$this->logger->debug( "Secondary login with $id succeeded" );
						// fall through
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->debug( "Login failed in secondary authentication by $id" );
						$this->callMethodOnProviders( 7, 'postAuthentication', [ $user, $res ] );
						$session->remove( 'AuthManager::authnState' );
						\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $res, $user, $user->getName() ] );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( "Secondary login with $id returned " . $res->status );
						$state['secondary'][$id] = false;
						$state['continueRequests'] = $res->neededRequests;
						$session->setSecret( 'AuthManager::authnState', $state );
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

			$this->logger->info( 'Login for {user} succeeded', [
				'user' => $user->getName(),
			] );
			$req = AuthenticationRequest::getRequestByClass(
				$beginReqs, RememberMeAuthenticationRequest::class
			);
			$this->setSessionDataForUser( $user, $req && $req->rememberMe );
			$ret = AuthenticationResponse::newPass( $user->getName() );
			$this->callMethodOnProviders( 7, 'postAuthentication', [ $user, $ret ] );
			$session->remove( 'AuthManager::authnState' );
			$this->removeAuthenticationSessionData( null );
			\Hooks::run( 'AuthManagerLoginAuthenticateAudit', [ $ret, $user, $user->getName() ] );
			return $ret;
		} catch ( \Exception $ex ) {
			$session->remove( 'AuthManager::authnState' );
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
	 * @param string $operation Operation being checked. This should be a
	 *  message-key-like string such as 'change-password' or 'change-email'.
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

		\Hooks::run( 'SecuritySensitiveOperationStatus', [
			&$status, $operation, $session, $timeSinceLogin
		] );

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

	/**
	 * Provide normalized versions of the username for security checks
	 *
	 * Since different providers can normalize the input in different ways,
	 * this returns an array of all the different ways the name might be
	 * normalized for authentication.
	 *
	 * The returned strings should not be revealed to the user, as that might
	 * leak private information (e.g. an email address might be normalized to a
	 * username).
	 *
	 * @param string $username
	 * @return string[]
	 */
	public function normalizeUsername( $username ) {
		$ret = [];
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			$normalized = $provider->providerNormalizeUsername( $username );
			if ( $normalized !== null ) {
				$ret[$normalized] = true;
			}
		}
		return array_keys( $ret );
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
		$this->logger->info( 'Revoking access for {user}', [
			'user' => $username,
		] );
		$this->callMethodOnProviders( 6, 'providerRevokeAccessForUser', [ $username ] );
	}

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @param bool $checkData If false, $req hasn't been loaded from the
	 *  submission so checks on user-submitted fields should be skipped. $req->username is
	 *  considered user-submitted for this purpose, even if it cannot be changed via
	 *  $req->loadFromSubmission.
	 * @return Status
	 */
	public function allowsAuthenticationDataChange( AuthenticationRequest $req, $checkData = true ) {
		$any = false;
		$providers = $this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->providerAllowsAuthenticationDataChange( $req, $checkData );
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
		return Status::newGood();
	}

	/**
	 * Change authentication data (e.g. passwords)
	 *
	 * If $req was returned for AuthManager::ACTION_CHANGE, using $req should
	 * result in a successful login in the future.
	 *
	 * If $req was returned for AuthManager::ACTION_REMOVE, using $req should
	 * no longer result in a successful login.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function changeAuthenticationData( AuthenticationRequest $req ) {
		$this->logger->info( 'Changing authentication data for {user} class {what}', [
			'user' => is_string( $req->username ) ? $req->username : '<no name>',
			'what' => get_class( $req ),
		] );

		$this->callMethodOnProviders( 6, 'providerChangeAuthenticationData', [ $req ] );

		// When the main account's authentication data is changed, invalidate
		// all BotPasswords too.
		\BotPassword::invalidateAllPasswordsForUser( $req->username );
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
	 * Determine whether a particular account can be created
	 * @param string $username
	 * @param int $flags Bitfield of User:READ_* constants
	 * @return Status
	 */
	public function canCreateAccount( $username, $flags = User::READ_NORMAL ) {
		if ( !$this->canCreateAccounts() ) {
			return Status::newFatal( 'authmanager-create-disabled' );
		}

		if ( $this->userExists( $username, $flags ) ) {
			return Status::newFatal( 'userexists' );
		}

		$user = User::newFromName( $username, 'creatable' );
		if ( !is_object( $user ) ) {
			return Status::newFatal( 'noname' );
		} else {
			$user->load( $flags ); // Explicitly load with $flags, auto-loading always uses READ_NORMAL
			if ( $user->getId() !== 0 ) {
				return Status::newFatal( 'userexists' );
			}
		}

		// Denied by providers?
		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->testUserForCreation( $user, false );
			if ( !$status->isGood() ) {
				return Status::wrap( $status );
			}
		}

		return Status::newGood();
	}

	/**
	 * Basic permissions checks on whether a user can create accounts
	 * @param User $creator User doing the account creation
	 * @return Status
	 */
	public function checkAccountCreatePermissions( User $creator ) {
		// Wiki is read-only?
		if ( wfReadOnly() ) {
			return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		}

		// This is awful, this permission check really shouldn't go through Title.
		$permErrors = \SpecialPage::getTitleFor( 'CreateAccount' )
			->getUserPermissionsErrors( 'createaccount', $creator, 'secure' );
		if ( $permErrors ) {
			$status = Status::newGood();
			foreach ( $permErrors as $args ) {
				call_user_func_array( [ $status, 'fatal' ], $args );
			}
			return $status;
		}

		$block = $creator->isBlockedFromCreateAccount();
		if ( $block ) {
			$errorParams = [
				$block->getTarget(),
				$block->mReason ?: wfMessage( 'blockednoreason' )->text(),
				$block->getByName()
			];

			if ( $block->getType() === \Block::TYPE_RANGE ) {
				$errorMessage = 'cantcreateaccount-range-text';
				$errorParams[] = $this->getRequest()->getIP();
			} else {
				$errorMessage = 'cantcreateaccount-text';
			}

			return Status::newFatal( wfMessage( $errorMessage, $errorParams ) );
		}

		$ip = $this->getRequest()->getIP();
		if ( $creator->isDnsBlacklisted( $ip, true /* check $wgProxyWhitelist */ ) ) {
			return Status::newFatal( 'sorbs_create_account_reason' );
		}

		return Status::newGood();
	}

	/**
	 * Start an account creation flow
	 * @param User $creator User doing the account creation
	 * @param AuthenticationRequest[] $reqs
	 * @param string $returnToUrl Url that REDIRECT responses should eventually
	 *  return to.
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( User $creator, array $reqs, $returnToUrl ) {
		$session = $this->request->getSession();
		if ( !$this->canCreateAccounts() ) {
			// Caller should have called canCreateAccounts()
			$session->remove( 'AuthManager::accountCreationState' );
			throw new \LogicException( 'Account creation is not possible' );
		}

		try {
			$username = AuthenticationRequest::getUsernameFromRequests( $reqs );
		} catch ( \UnexpectedValueException $ex ) {
			$username = null;
		}
		if ( $username === null ) {
			$this->logger->debug( __METHOD__ . ': No username provided' );
			return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
		}

		// Permissions check
		$status = $this->checkAccountCreatePermissions( $creator );
		if ( !$status->isGood() ) {
			$this->logger->debug( __METHOD__ . ': {creator} cannot create users: {reason}', [
				'user' => $username,
				'creator' => $creator->getName(),
				'reason' => $status->getWikiText( null, null, 'en' )
			] );
			return AuthenticationResponse::newFail( $status->getMessage() );
		}

		$status = $this->canCreateAccount( $username, User::READ_LOCKING );
		if ( !$status->isGood() ) {
			$this->logger->debug( __METHOD__ . ': {user} cannot be created: {reason}', [
				'user' => $username,
				'creator' => $creator->getName(),
				'reason' => $status->getWikiText( null, null, 'en' )
			] );
			return AuthenticationResponse::newFail( $status->getMessage() );
		}

		$user = User::newFromName( $username, 'creatable' );
		foreach ( $reqs as $req ) {
			$req->username = $username;
			$req->returnToUrl = $returnToUrl;
			if ( $req instanceof UserDataAuthenticationRequest ) {
				$status = $req->populateUser( $user );
				if ( !$status->isGood() ) {
					$status = Status::wrap( $status );
					$session->remove( 'AuthManager::accountCreationState' );
					$this->logger->debug( __METHOD__ . ': UserData is invalid: {reason}', [
						'user' => $user->getName(),
						'creator' => $creator->getName(),
						'reason' => $status->getWikiText( null, null, 'en' ),
					] );
					return AuthenticationResponse::newFail( $status->getMessage() );
				}
			}
		}

		$this->removeAuthenticationSessionData( null );

		$state = [
			'username' => $username,
			'userid' => 0,
			'creatorid' => $creator->getId(),
			'creatorname' => $creator->getName(),
			'reqs' => $reqs,
			'returnToUrl' => $returnToUrl,
			'primary' => null,
			'primaryResponse' => null,
			'secondary' => [],
			'continueRequests' => [],
			'maybeLink' => [],
			'ranPreTests' => false,
		];

		// Special case: converting a login to an account creation
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, CreateFromLoginAuthenticationRequest::class
		);
		if ( $req ) {
			$state['maybeLink'] = $req->maybeLink;

			// If we get here, the user didn't submit a form with any of the
			// usual AuthenticationRequests that are needed for an account
			// creation. So we need to determine if there are any and return a
			// UI response if so.
			if ( $req->createRequest ) {
				// We have a createRequest from a
				// PrimaryAuthenticationProvider, so don't ask.
				$providers = $this->getPreAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
			} else {
				// We're only preserving maybeLink, so ask for primary fields
				// too.
				$providers = $this->getPreAuthenticationProviders() +
					$this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
			}
			$reqs = $this->getAuthenticationRequestsInternal(
				self::ACTION_CREATE,
				[],
				$providers
			);
			// See if we need any requests to begin
			foreach ( (array)$reqs as $r ) {
				if ( !$r instanceof UsernameAuthenticationRequest &&
					!$r instanceof UserDataAuthenticationRequest &&
					!$r instanceof CreationReasonAuthenticationRequest
				) {
					// Needs some reqs, so request them
					$reqs[] = new CreateFromLoginAuthenticationRequest( $req->createRequest, [] );
					$state['continueRequests'] = $reqs;
					$session->setSecret( 'AuthManager::accountCreationState', $state );
					$session->persist();
					return AuthenticationResponse::newUI( $reqs, wfMessage( 'authmanager-create-from-login' ) );
				}
			}
			// No reqs needed, so we can just continue.
			$req->createRequest->returnToUrl = $returnToUrl;
			$reqs = [ $req->createRequest ];
		}

		$session->setSecret( 'AuthManager::accountCreationState', $state );
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
				$session->remove( 'AuthManager::accountCreationState' );
				throw new \LogicException( 'Account creation is not possible' );
			}

			$state = $session->getSecret( 'AuthManager::accountCreationState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-create-not-in-progress' )
				);
			}
			$state['continueRequests'] = [];

			// Step 0: Prepare and validate the input

			$user = User::newFromName( $state['username'], 'creatable' );
			if ( !is_object( $user ) ) {
				$session->remove( 'AuthManager::accountCreationState' );
				$this->logger->debug( __METHOD__ . ': Invalid username', [
					'user' => $state['username'],
				] );
				return AuthenticationResponse::newFail( wfMessage( 'noname' ) );
			}

			if ( $state['creatorid'] ) {
				$creator = User::newFromId( $state['creatorid'] );
			} else {
				$creator = new User;
				$creator->setName( $state['creatorname'] );
			}

			// Avoid account creation races on double submissions
			$cache = \ObjectCache::getLocalClusterInstance();
			$lock = $cache->getScopedLock( $cache->makeGlobalKey( 'account', md5( $user->getName() ) ) );
			if ( !$lock ) {
				// Don't clear AuthManager::accountCreationState for this code
				// path because the process that won the race owns it.
				$this->logger->debug( __METHOD__ . ': Could not acquire account creation lock', [
					'user' => $user->getName(),
					'creator' => $creator->getName(),
				] );
				return AuthenticationResponse::newFail( wfMessage( 'usernameinprogress' ) );
			}

			// Permissions check
			$status = $this->checkAccountCreatePermissions( $creator );
			if ( !$status->isGood() ) {
				$this->logger->debug( __METHOD__ . ': {creator} cannot create users: {reason}', [
					'user' => $user->getName(),
					'creator' => $creator->getName(),
					'reason' => $status->getWikiText( null, null, 'en' )
				] );
				$ret = AuthenticationResponse::newFail( $status->getMessage() );
				$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
				$session->remove( 'AuthManager::accountCreationState' );
				return $ret;
			}

			// Load from master for existence check
			$user->load( User::READ_LOCKING );

			if ( $state['userid'] === 0 ) {
				if ( $user->getId() != 0 ) {
					$this->logger->debug( __METHOD__ . ': User exists locally', [
						'user' => $user->getName(),
						'creator' => $creator->getName(),
					] );
					$ret = AuthenticationResponse::newFail( wfMessage( 'userexists' ) );
					$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
					$session->remove( 'AuthManager::accountCreationState' );
					return $ret;
				}
			} else {
				if ( $user->getId() == 0 ) {
					$this->logger->debug( __METHOD__ . ': User does not exist locally when it should', [
						'user' => $user->getName(),
						'creator' => $creator->getName(),
						'expected_id' => $state['userid'],
					] );
					throw new \UnexpectedValueException(
						"User \"{$state['username']}\" should exist now, but doesn't!"
					);
				}
				if ( $user->getId() != $state['userid'] ) {
					$this->logger->debug( __METHOD__ . ': User ID/name mismatch', [
						'user' => $user->getName(),
						'creator' => $creator->getName(),
						'expected_id' => $state['userid'],
						'actual_id' => $user->getId(),
					] );
					throw new \UnexpectedValueException(
						"User \"{$state['username']}\" exists, but " .
							"ID {$user->getId()} != {$state['userid']}!"
					);
				}
			}
			foreach ( $state['reqs'] as $req ) {
				if ( $req instanceof UserDataAuthenticationRequest ) {
					$status = $req->populateUser( $user );
					if ( !$status->isGood() ) {
						// This should never happen...
						$status = Status::wrap( $status );
						$this->logger->debug( __METHOD__ . ': UserData is invalid: {reason}', [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
							'reason' => $status->getWikiText( null, null, 'en' ),
						] );
						$ret = AuthenticationResponse::newFail( $status->getMessage() );
						$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
						$session->remove( 'AuthManager::accountCreationState' );
						return $ret;
					}
				}
			}

			foreach ( $reqs as $req ) {
				$req->returnToUrl = $state['returnToUrl'];
				$req->username = $state['username'];
			}

			// If we're coming in from a create-from-login UI response, we need
			// to extract the createRequest (if any).
			$req = AuthenticationRequest::getRequestByClass(
				$reqs, CreateFromLoginAuthenticationRequest::class
			);
			if ( $req && $req->createRequest ) {
				$reqs[] = $req->createRequest;
			}

			// Run pre-creation tests, if we haven't already
			if ( !$state['ranPreTests'] ) {
				$providers = $this->getPreAuthenticationProviders() +
					$this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
				foreach ( $providers as $id => $provider ) {
					$status = $provider->testForAccountCreation( $user, $creator, $reqs );
					if ( !$status->isGood() ) {
						$this->logger->debug( __METHOD__ . ": Fail in pre-authentication by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						$ret = AuthenticationResponse::newFail(
							Status::wrap( $status )->getMessage()
						);
						$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
						$session->remove( 'AuthManager::accountCreationState' );
						return $ret;
					}
				}

				$state['ranPreTests'] = true;
			}

			// Step 1: Choose a primary authentication provider and call it until it succeeds.

			if ( $state['primary'] === null ) {
				// We haven't picked a PrimaryAuthenticationProvider yet
				foreach ( $this->getPrimaryAuthenticationProviders() as $id => $provider ) {
					if ( $provider->accountCreationType() === PrimaryAuthenticationProvider::TYPE_NONE ) {
						continue;
					}
					$res = $provider->beginPrimaryAccountCreation( $user, $creator, $reqs );
					switch ( $res->status ) {
						case AuthenticationResponse::PASS;
							$this->logger->debug( __METHOD__ . ": Primary creation passed by $id", [
								'user' => $user->getName(),
								'creator' => $creator->getName(),
							] );
							$state['primary'] = $id;
							$state['primaryResponse'] = $res;
							break 2;
						case AuthenticationResponse::FAIL;
							$this->logger->debug( __METHOD__ . ": Primary creation failed by $id", [
								'user' => $user->getName(),
								'creator' => $creator->getName(),
							] );
							$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $res ] );
							$session->remove( 'AuthManager::accountCreationState' );
							return $res;
						case AuthenticationResponse::ABSTAIN;
							// Continue loop
							break;
						case AuthenticationResponse::REDIRECT;
						case AuthenticationResponse::UI;
							$this->logger->debug( __METHOD__ . ": Primary creation $res->status by $id", [
								'user' => $user->getName(),
								'creator' => $creator->getName(),
							] );
							$state['primary'] = $id;
							$state['continueRequests'] = $res->neededRequests;
							$session->setSecret( 'AuthManager::accountCreationState', $state );
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
					$this->logger->debug( __METHOD__ . ': Primary creation failed because no provider accepted', [
						'user' => $user->getName(),
						'creator' => $creator->getName(),
					] );
					$ret = AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-no-primary' )
					);
					$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
					$session->remove( 'AuthManager::accountCreationState' );
					return $ret;
				}
			} elseif ( $state['primaryResponse'] === null ) {
				$provider = $this->getAuthenticationProvider( $state['primary'] );
				if ( !$provider instanceof PrimaryAuthenticationProvider ) {
					// Configuration changed? Force them to start over.
					// @codeCoverageIgnoreStart
					$ret = AuthenticationResponse::newFail(
						wfMessage( 'authmanager-create-not-in-progress' )
					);
					$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
					$session->remove( 'AuthManager::accountCreationState' );
					return $ret;
					// @codeCoverageIgnoreEnd
				}
				$id = $provider->getUniqueId();
				$res = $provider->continuePrimaryAccountCreation( $user, $creator, $reqs );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$this->logger->debug( __METHOD__ . ": Primary creation passed by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						$state['primaryResponse'] = $res;
						break;
					case AuthenticationResponse::FAIL;
						$this->logger->debug( __METHOD__ . ": Primary creation failed by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $res ] );
						$session->remove( 'AuthManager::accountCreationState' );
						return $res;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( __METHOD__ . ": Primary creation $res->status by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						$state['continueRequests'] = $res->neededRequests;
						$session->setSecret( 'AuthManager::accountCreationState', $state );
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
				$this->logger->info( 'Creating user {user} during account creation', [
					'user' => $user->getName(),
					'creator' => $creator->getName(),
				] );
				$status = $user->addToDatabase();
				if ( !$status->isOk() ) {
					// @codeCoverageIgnoreStart
					$ret = AuthenticationResponse::newFail( $status->getMessage() );
					$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
					$session->remove( 'AuthManager::accountCreationState' );
					return $ret;
					// @codeCoverageIgnoreEnd
				}
				$this->setDefaultUserOptions( $user, $creator->isAnon() );
				\Hooks::run( 'LocalUserCreated', [ $user, false ] );
				$user->saveSettings();
				$state['userid'] = $user->getId();

				// Update user count
				\DeferredUpdates::addUpdate( new \SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

				// Watch user's userpage and talk page
				$user->addWatch( $user->getUserPage(), User::IGNORE_USER_RIGHTS );

				// Inform the provider
				$logSubtype = $provider->finishAccountCreation( $user, $creator, $state['primaryResponse'] );

				// Log the creation
				if ( $this->config->get( 'NewUserLog' ) ) {
					$isAnon = $creator->isAnon();
					$logEntry = new \ManualLogEntry(
						'newusers',
						$logSubtype ?: ( $isAnon ? 'create' : 'create2' )
					);
					$logEntry->setPerformer( $isAnon ? $user : $creator );
					$logEntry->setTarget( $user->getUserPage() );
					$req = AuthenticationRequest::getRequestByClass(
						$state['reqs'], CreationReasonAuthenticationRequest::class
					);
					$logEntry->setComment( $req ? $req->reason : '' );
					$logEntry->setParameters( [
						'4::userid' => $user->getId(),
					] );
					$logid = $logEntry->insert();
					$logEntry->publish( $logid );
				}
			}

			// Step 3: Iterate over all the secondary authentication providers.

			$beginReqs = $state['reqs'];

			foreach ( $this->getSecondaryAuthenticationProviders() as $id => $provider ) {
				if ( !isset( $state['secondary'][$id] ) ) {
					// This provider isn't started yet, so we pass it the set
					// of reqs from beginAuthentication instead of whatever
					// might have been used by a previous provider in line.
					$func = 'beginSecondaryAccountCreation';
					$res = $provider->beginSecondaryAccountCreation( $user, $creator, $beginReqs );
				} elseif ( !$state['secondary'][$id] ) {
					$func = 'continueSecondaryAccountCreation';
					$res = $provider->continueSecondaryAccountCreation( $user, $creator, $reqs );
				} else {
					continue;
				}
				switch ( $res->status ) {
					case AuthenticationResponse::PASS;
						$this->logger->debug( __METHOD__ . ": Secondary creation passed by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						// fall through
					case AuthenticationResponse::ABSTAIN;
						$state['secondary'][$id] = true;
						break;
					case AuthenticationResponse::REDIRECT;
					case AuthenticationResponse::UI;
						$this->logger->debug( __METHOD__ . ": Secondary creation $res->status by $id", [
							'user' => $user->getName(),
							'creator' => $creator->getName(),
						] );
						$state['secondary'][$id] = false;
						$state['continueRequests'] = $res->neededRequests;
						$session->setSecret( 'AuthManager::accountCreationState', $state );
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
			$ret = AuthenticationResponse::newPass( $name );
			$ret->loginRequest = $req;
			$this->createdAccountAuthenticationRequests[] = $req;

			$this->logger->info( __METHOD__ . ': Account creation succeeded for {user}', [
				'user' => $user->getName(),
				'creator' => $creator->getName(),
			] );

			$this->callMethodOnProviders( 7, 'postAccountCreation', [ $user, $creator, $ret ] );
			$session->remove( 'AuthManager::accountCreationState' );
			$this->removeAuthenticationSessionData( null );
			return $ret;
		} catch ( \Exception $ex ) {
			$session->remove( 'AuthManager::accountCreationState' );
			throw $ex;
		}
	}

	/**
	 * Auto-create an account, and log into that account
	 * @param User $user User to auto-create
	 * @param string $source What caused the auto-creation? This must be the ID
	 *  of a PrimaryAuthenticationProvider or the constant self::AUTOCREATE_SOURCE_SESSION.
	 * @param bool $login Whether to also log the user in
	 * @return Status Good if user was created, Ok if user already existed, otherwise Fatal
	 */
	public function autoCreateUser( User $user, $source, $login = true ) {
		if ( $source !== self::AUTOCREATE_SOURCE_SESSION &&
			!$this->getAuthenticationProvider( $source ) instanceof PrimaryAuthenticationProvider
		) {
			throw new \InvalidArgumentException( "Unknown auto-creation source: $source" );
		}

		$username = $user->getName();

		// Try the local user from the slave DB
		$localId = User::idFromName( $username );
		$flags = User::READ_NORMAL;

		// Fetch the user ID from the master, so that we don't try to create the user
		// when they already exist, due to replication lag
		// @codeCoverageIgnoreStart
		if ( !$localId && wfGetLB()->getReaderIndex() != 0 ) {
			$localId = User::idFromName( $username, User::READ_LATEST );
			$flags = User::READ_LATEST;
		}
		// @codeCoverageIgnoreEnd

		if ( $localId ) {
			$this->logger->debug( __METHOD__ . ': {username} already exists locally', [
				'username' => $username,
			] );
			$user->setId( $localId );
			$user->loadFromId( $flags );
			if ( $login ) {
				$this->setSessionDataForUser( $user );
			}
			$status = Status::newGood();
			$status->warning( 'userexists' );
			return $status;
		}

		// Wiki is read-only?
		if ( wfReadOnly() ) {
			$this->logger->debug( __METHOD__ . ': denied by wfReadOnly(): {reason}', [
				'username' => $username,
				'reason' => wfReadOnlyReason(),
			] );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		}

		// Check the session, if we tried to create this user already there's
		// no point in retrying.
		$session = $this->request->getSession();
		if ( $session->get( 'AuthManager::AutoCreateBlacklist' ) ) {
			$this->logger->debug( __METHOD__ . ': blacklisted in session {sessionid}', [
				'username' => $username,
				'sessionid' => $session->getId(),
			] );
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
			$this->logger->debug( __METHOD__ . ': name "{username}" is not creatable', [
				'username' => $username,
			] );
			$session->set( 'AuthManager::AutoCreateBlacklist', 'noname', 600 );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'noname' );
		}

		// Is the IP user able to create accounts?
		$anon = new User;
		if ( !$anon->isAllowedAny( 'createaccount', 'autocreateaccount' ) ) {
			$this->logger->debug( __METHOD__ . ': IP lacks the ability to create or autocreate accounts', [
				'username' => $username,
				'ip' => $anon->getName(),
			] );
			$session->set( 'AuthManager::AutoCreateBlacklist', 'authmanager-autocreate-noperm', 600 );
			$session->persist();
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'authmanager-autocreate-noperm' );
		}

		// Avoid account creation races on double submissions
		$cache = \ObjectCache::getLocalClusterInstance();
		$lock = $cache->getScopedLock( $cache->makeGlobalKey( 'account', md5( $username ) ) );
		if ( !$lock ) {
			$this->logger->debug( __METHOD__ . ': Could not acquire account creation lock', [
				'user' => $username,
			] );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'usernameinprogress' );
		}

		// Denied by providers?
		$providers = $this->getPreAuthenticationProviders() +
			$this->getPrimaryAuthenticationProviders() +
			$this->getSecondaryAuthenticationProviders();
		foreach ( $providers as $provider ) {
			$status = $provider->testUserForCreation( $user, $source );
			if ( !$status->isGood() ) {
				$ret = Status::wrap( $status );
				$this->logger->debug( __METHOD__ . ': Provider denied creation of {username}: {reason}', [
					'username' => $username,
					'reason' => $ret->getWikiText( null, null, 'en' ),
				] );
				$session->set( 'AuthManager::AutoCreateBlacklist', $status, 600 );
				$user->setId( 0 );
				$user->loadFromId();
				return $ret;
			}
		}

		// Ignore warnings about master connections/writes...hard to avoid here
		\Profiler::instance()->getTransactionProfiler()->resetExpectations();

		$backoffKey = wfMemcKey( 'AuthManager', 'autocreate-failed', md5( $username ) );
		if ( $cache->get( $backoffKey ) ) {
			$this->logger->debug( __METHOD__ . ': {username} denied by prior creation attempt failures', [
				'username' => $username,
			] );
			$user->setId( 0 );
			$user->loadFromId();
			return Status::newFatal( 'authmanager-autocreate-exception' );
		}

		// Checks passed, create the user...
		$from = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : 'CLI';
		$this->logger->info( __METHOD__ . ': creating new user ({username}) - from: {from}', [
			'username' => $username,
			'from' => $from,
		] );

		try {
			$status = $user->addToDatabase();
			if ( !$status->isOk() ) {
				// double-check for a race condition (T70012)
				$localId = User::idFromName( $username, User::READ_LATEST );
				if ( $localId ) {
					$this->logger->info( __METHOD__ . ': {username} already exists locally (race)', [
						'username' => $username,
					] );
					$user->setId( $localId );
					$user->loadFromId( User::READ_LATEST );
					if ( $login ) {
						$this->setSessionDataForUser( $user );
					}
					$status = Status::newGood();
					$status->warning( 'userexists' );
				} else {
					$this->logger->error( __METHOD__ . ': {username} failed with message {message}', [
						'username' => $username,
						'message' => $status->getWikiText( null, null, 'en' )
					] );
					$user->setId( 0 );
					$user->loadFromId();
				}
				return $status;
			}
		} catch ( \Exception $ex ) {
			$this->logger->error( __METHOD__ . ': {username} failed with exception {exception}', [
				'username' => $username,
				'exception' => $ex,
			] );
			// Do not keep throwing errors for a while
			$cache->set( $backoffKey, 1, 600 );
			// Bubble up error; which should normally trigger DB rollbacks
			throw $ex;
		}

		$this->setDefaultUserOptions( $user, true );

		// Inform the providers
		$this->callMethodOnProviders( 6, 'autoCreatedAccount', [ $user, $source ] );

		\Hooks::run( 'AuthPluginAutoCreate', [ $user ], '1.27' );
		\Hooks::run( 'LocalUserCreated', [ $user, true ] );
		$user->saveSettings();

		// Update user count
		\DeferredUpdates::addUpdate( new \SiteStatsUpdate( 0, 0, 0, 0, 1 ) );

		// Watch user's userpage and talk page
		$user->addWatch( $user->getUserPage(), User::IGNORE_USER_RIGHTS );

		// Log the creation
		if ( $this->config->get( 'NewUserLog' ) ) {
			$logEntry = new \ManualLogEntry( 'newusers', 'autocreate' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $user->getUserPage() );
			$logEntry->setComment( '' );
			$logEntry->setParameters( [
				'4::userid' => $user->getId(),
			] );
			$logid = $logEntry->insert();
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
	 * @param string $returnToUrl Url that REDIRECT responses should eventually
	 *  return to.
	 * @return AuthenticationResponse
	 */
	public function beginAccountLink( User $user, array $reqs, $returnToUrl ) {
		$session = $this->request->getSession();
		$session->remove( 'AuthManager::accountLinkState' );

		if ( !$this->canLinkAccounts() ) {
			// Caller should have called canLinkAccounts()
			throw new \LogicException( 'Account linking is not possible' );
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
			$req->returnToUrl = $returnToUrl;
		}

		$this->removeAuthenticationSessionData( null );

		$providers = $this->getPreAuthenticationProviders();
		foreach ( $providers as $id => $provider ) {
			$status = $provider->testForAccountLink( $user );
			if ( !$status->isGood() ) {
				$this->logger->debug( __METHOD__ . ": Account linking pre-check failed by $id", [
					'user' => $user->getName(),
				] );
				$ret = AuthenticationResponse::newFail(
					Status::wrap( $status )->getMessage()
				);
				$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $ret ] );
				return $ret;
			}
		}

		$state = [
			'username' => $user->getName(),
			'userid' => $user->getId(),
			'returnToUrl' => $returnToUrl,
			'primary' => null,
			'continueRequests' => [],
		];

		$providers = $this->getPrimaryAuthenticationProviders();
		foreach ( $providers as $id => $provider ) {
			if ( $provider->accountCreationType() !== PrimaryAuthenticationProvider::TYPE_LINK ) {
				continue;
			}

			$res = $provider->beginPrimaryAccountLink( $user, $reqs );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS;
					$this->logger->info( "Account linked to {user} by $id", [
						'user' => $user->getName(),
					] );
					$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $res ] );
					return $res;

				case AuthenticationResponse::FAIL;
					$this->logger->debug( __METHOD__ . ": Account linking failed by $id", [
						'user' => $user->getName(),
					] );
					$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $res ] );
					return $res;

				case AuthenticationResponse::ABSTAIN;
					// Continue loop
					break;

				case AuthenticationResponse::REDIRECT;
				case AuthenticationResponse::UI;
					$this->logger->debug( __METHOD__ . ": Account linking $res->status by $id", [
						'user' => $user->getName(),
					] );
					$state['primary'] = $id;
					$state['continueRequests'] = $res->neededRequests;
					$session->setSecret( 'AuthManager::accountLinkState', $state );
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

		$this->logger->debug( __METHOD__ . ': Account linking failed because no provider accepted', [
			'user' => $user->getName(),
		] );
		$ret = AuthenticationResponse::newFail(
			wfMessage( 'authmanager-link-no-primary' )
		);
		$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $ret ] );
		return $ret;
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
				$session->remove( 'AuthManager::accountLinkState' );
				throw new \LogicException( 'Account linking is not possible' );
			}

			$state = $session->getSecret( 'AuthManager::accountLinkState' );
			if ( !is_array( $state ) ) {
				return AuthenticationResponse::newFail(
					wfMessage( 'authmanager-link-not-in-progress' )
				);
			}
			$state['continueRequests'] = [];

			// Step 0: Prepare and validate the input

			$user = User::newFromName( $state['username'], 'usable' );
			if ( !is_object( $user ) ) {
				$session->remove( 'AuthManager::accountLinkState' );
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
				$req->returnToUrl = $state['returnToUrl'];
			}

			// Step 1: Call the primary again until it succeeds

			$provider = $this->getAuthenticationProvider( $state['primary'] );
			if ( !$provider instanceof PrimaryAuthenticationProvider ) {
				// Configuration changed? Force them to start over.
				// @codeCoverageIgnoreStart
				$ret = AuthenticationResponse::newFail(
					wfMessage( 'authmanager-link-not-in-progress' )
				);
				$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $ret ] );
				$session->remove( 'AuthManager::accountLinkState' );
				return $ret;
				// @codeCoverageIgnoreEnd
			}
			$id = $provider->getUniqueId();
			$res = $provider->continuePrimaryAccountLink( $user, $reqs );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS;
					$this->logger->info( "Account linked to {user} by $id", [
						'user' => $user->getName(),
					] );
					$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $res ] );
					$session->remove( 'AuthManager::accountLinkState' );
					return $res;
				case AuthenticationResponse::FAIL;
					$this->logger->debug( __METHOD__ . ": Account linking failed by $id", [
						'user' => $user->getName(),
					] );
					$this->callMethodOnProviders( 3, 'postAccountLink', [ $user, $res ] );
					$session->remove( 'AuthManager::accountLinkState' );
					return $res;
				case AuthenticationResponse::REDIRECT;
				case AuthenticationResponse::UI;
					$this->logger->debug( __METHOD__ . ": Account linking $res->status by $id", [
						'user' => $user->getName(),
					] );
					$state['continueRequests'] = $res->neededRequests;
					$session->setSecret( 'AuthManager::accountLinkState', $state );
					return $res;
				default:
					throw new \DomainException(
						get_class( $provider ) . "::continuePrimaryAccountLink() returned $res->status"
					);
			}
		} catch ( \Exception $ex ) {
			$session->remove( 'AuthManager::accountLinkState' );
			throw $ex;
		}
	}

	/**@}*/

	/**
	 * @name Information methods
	 * @{
	 */

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
	 *  - ACTION_CHANGE: Valid for passing to changeAuthenticationData to change credentials
	 *  - ACTION_REMOVE: Valid for passing to changeAuthenticationData to remove credentials.
	 *  - ACTION_UNLINK: Same as ACTION_REMOVE, but limited to linked accounts.
	 *
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param User|null $user User being acted on, instead of the current user.
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests( $action, User $user = null ) {
		$options = [];
		$providerAction = $action;

		// Figure out which providers to query
		switch ( $action ) {
			case self::ACTION_LOGIN:
			case self::ACTION_CREATE:
				$providers = $this->getPreAuthenticationProviders() +
					$this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
				break;

			case self::ACTION_LOGIN_CONTINUE:
				$state = $this->request->getSession()->getSecret( 'AuthManager::authnState' );
				return is_array( $state ) ? $state['continueRequests'] : [];

			case self::ACTION_CREATE_CONTINUE:
				$state = $this->request->getSession()->getSecret( 'AuthManager::accountCreationState' );
				return is_array( $state ) ? $state['continueRequests'] : [];

			case self::ACTION_LINK:
				$providers = array_filter( $this->getPrimaryAuthenticationProviders(), function ( $p ) {
					return $p->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK;
				} );
				break;

			case self::ACTION_UNLINK:
				$providers = array_filter( $this->getPrimaryAuthenticationProviders(), function ( $p ) {
					return $p->accountCreationType() === PrimaryAuthenticationProvider::TYPE_LINK;
				} );

				// To providers, unlink and remove are identical.
				$providerAction = self::ACTION_REMOVE;
				break;

			case self::ACTION_LINK_CONTINUE:
				$state = $this->request->getSession()->getSecret( 'AuthManager::accountLinkState' );
				return is_array( $state ) ? $state['continueRequests'] : [];

			case self::ACTION_CHANGE:
			case self::ACTION_REMOVE:
				$providers = $this->getPrimaryAuthenticationProviders() +
					$this->getSecondaryAuthenticationProviders();
				break;

			// @codeCoverageIgnoreStart
			default:
				throw new \DomainException( __METHOD__ . ": Invalid action \"$action\"" );
		}
		// @codeCoverageIgnoreEnd

		return $this->getAuthenticationRequestsInternal( $providerAction, $options, $providers, $user );
	}

	/**
	 * Internal request lookup for self::getAuthenticationRequests
	 *
	 * @param string $providerAction Action to pass to providers
	 * @param array $options Options to pass to providers
	 * @param AuthenticationProvider[] $providers
	 * @param User|null $user
	 * @return AuthenticationRequest[]
	 */
	private function getAuthenticationRequestsInternal(
		$providerAction, array $options, array $providers, User $user = null
	) {
		$user = $user ?: \RequestContext::getMain()->getUser();
		$options['username'] = $user->isAnon() ? null : $user->getName();

		// Query them and merge results
		$reqs = [];
		$allPrimaryRequired = null;
		foreach ( $providers as $provider ) {
			$isPrimary = $provider instanceof PrimaryAuthenticationProvider;
			$thisRequired = [];
			foreach ( $provider->getAuthenticationRequests( $providerAction, $options ) as $req ) {
				$id = $req->getUniqueId();

				// If it's from a Primary, mark it as "primary-required" but
				// track it for later.
				if ( $isPrimary ) {
					if ( $req->required ) {
						$thisRequired[$id] = true;
						$req->required = AuthenticationRequest::PRIMARY_REQUIRED;
					}
				}

				if ( !isset( $reqs[$id] ) || $req->required === AuthenticationRequest::REQUIRED ) {
					$reqs[$id] = $req;
				}
			}

			// Track which requests are required by all primaries
			if ( $isPrimary ) {
				$allPrimaryRequired = $allPrimaryRequired === null
					? $thisRequired
					: array_intersect_key( $allPrimaryRequired, $thisRequired );
			}
		}
		// Any requests that were required by all primaries are required.
		foreach ( (array)$allPrimaryRequired as $id => $dummy ) {
			$reqs[$id]->required = AuthenticationRequest::REQUIRED;
		}

		// AuthManager has its own req for some actions
		switch ( $providerAction ) {
			case self::ACTION_LOGIN:
				$reqs[] = new RememberMeAuthenticationRequest;
				break;

			case self::ACTION_CREATE:
				$reqs[] = new UsernameAuthenticationRequest;
				$reqs[] = new UserDataAuthenticationRequest;
				if ( $options['username'] !== null ) {
					$reqs[] = new CreationReasonAuthenticationRequest;
					$options['username'] = null; // Don't fill in the username below
				}
				break;
		}

		// Fill in reqs data
		foreach ( $reqs as $req ) {
			$req->action = $providerAction;
			if ( $req->username === null ) {
				$req->username = $options['username'];
			}
		}

		// For self::ACTION_CHANGE, filter out any that something else *doesn't* allow changing
		if ( $providerAction === self::ACTION_CHANGE || $providerAction === self::ACTION_REMOVE ) {
			$reqs = array_filter( $reqs, function ( $req ) {
				return $this->allowsAuthenticationDataChange( $req, false )->isGood();
			} );
		}

		return array_values( $reqs );
	}

	/**
	 * Determine whether a username exists
	 * @param string $username
	 * @param int $flags Bitfield of User:READ_* constants
	 * @return bool
	 */
	public function userExists( $username, $flags = User::READ_NORMAL ) {
		foreach ( $this->getPrimaryAuthenticationProviders() as $provider ) {
			if ( $provider->testUserExists( $username, $flags ) ) {
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
	public function setAuthenticationSessionData( $key, $data ) {
		$session = $this->request->getSession();
		$arr = $session->getSecret( 'authData' );
		if ( !is_array( $arr ) ) {
			$arr = [];
		}
		$arr[$key] = $data;
		$session->setSecret( 'authData', $arr );
	}

	/**
	 * Fetch authentication data from the current session
	 * @protected For use by AuthenticationProviders
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getAuthenticationSessionData( $key, $default = null ) {
		$arr = $this->request->getSession()->getSecret( 'authData' );
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
	public function removeAuthenticationSessionData( $key ) {
		$session = $this->request->getSession();
		if ( $key === null ) {
			$session->remove( 'authData' );
		} else {
			$arr = $session->getSecret( 'authData' );
			if ( is_array( $arr ) && array_key_exists( $key, $arr ) ) {
				unset( $arr[$key] );
				$session->setSecret( 'authData', $arr );
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
		$i = 0;
		foreach ( $specs as &$spec ) {
			$spec = [ 'sort2' => $i++ ] + $spec + [ 'sort' => 0 ];
		}
		unset( $spec );
		usort( $specs, function ( $a, $b ) {
			return ( (int)$a['sort'] ) - ( (int)$b['sort'] )
				?: $a['sort2'] - $b['sort2'];
		} );

		$ret = [];
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
	 * Get the configuration
	 * @return array
	 */
	private function getConfiguration() {
		return $this->config->get( 'AuthManagerConfig' ) ?: $this->config->get( 'AuthManagerAutoConfig' );
	}

	/**
	 * Get the list of PreAuthenticationProviders
	 * @return PreAuthenticationProvider[]
	 */
	protected function getPreAuthenticationProviders() {
		if ( $this->preAuthenticationProviders === null ) {
			$conf = $this->getConfiguration();
			$this->preAuthenticationProviders = $this->providerArrayFromSpecs(
				PreAuthenticationProvider::class, $conf['preauth']
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
			$conf = $this->getConfiguration();
			$this->primaryAuthenticationProviders = $this->providerArrayFromSpecs(
				PrimaryAuthenticationProvider::class, $conf['primaryauth']
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
			$conf = $this->getConfiguration();
			$this->secondaryAuthenticationProviders = $this->providerArrayFromSpecs(
				SecondaryAuthenticationProvider::class, $conf['secondaryauth']
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
	 * @param bool|null $remember
	 */
	private function setSessionDataForUser( $user, $remember = null ) {
		$session = $this->request->getSession();
		$delay = $session->delaySave();

		$session->resetId();
		if ( $session->canSetUser() ) {
			$session->setUser( $user );
		}
		if ( $remember !== null ) {
			$session->setRememberUser( $remember );
		}
		$session->set( 'AuthManager:lastAuthId', $user->getId() );
		$session->set( 'AuthManager:lastAuthTimestamp', time() );
		$session->persist();

		\ScopedCallback::consume( $delay );

		\Hooks::run( 'UserLoggedIn', [ $user ] );
	}

	/**
	 * @param User $user
	 * @param bool $useContextLang Use 'uselang' to set the user's language
	 */
	private function setDefaultUserOptions( User $user, $useContextLang ) {
		global $wgContLang;

		\MediaWiki\Session\SessionManager::singleton()->invalidateSessionsForUser( $user );

		$lang = $useContextLang ? \RequestContext::getMain()->getLanguage() : $wgContLang;
		$user->setOption( 'language', $lang->getPreferredVariant() );

		if ( $wgContLang->hasVariants() ) {
			$user->setOption( 'variant', $wgContLang->getPreferredVariant() );
		}
	}

	/**
	 * @param int $which Bitmask: 1 = pre, 2 = primary, 4 = secondary
	 * @param string $method
	 * @param array $args
	 */
	private function callMethodOnProviders( $which, $method, array $args ) {
		$providers = [];
		if ( $which & 1 ) {
			$providers += $this->getPreAuthenticationProviders();
		}
		if ( $which & 2 ) {
			$providers += $this->getPrimaryAuthenticationProviders();
		}
		if ( $which & 4 ) {
			$providers += $this->getSecondaryAuthenticationProviders();
		}
		foreach ( $providers as $provider ) {
			call_user_func_array( [ $provider, $method ], $args );
		}
	}

	/**
	 * Reset the internal caching for unit testing
	 */
	public static function resetCache() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			// @codeCoverageIgnoreStart
			throw new \MWException( __METHOD__ . ' may only be called from unit tests!' );
			// @codeCoverageIgnoreEnd
		}

		self::$instance = null;
	}

	/**@}*/

}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
