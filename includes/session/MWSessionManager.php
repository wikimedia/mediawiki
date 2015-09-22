<?php
/**
 * MWSession entry point
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
 * @ingroup Session
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * This serves as the entry point to the MediaWiki session handling system.
 *
 * @ingroup Session
 * @since 1.27
 */
final class MWSessionManager implements LoggerAwareInterface {
	/** @var MWSessionManager|null */
	private static $instance = null;

	/** @var LoggerInterface */
	private $logger;

	/** @var Config */
	private $config;

	/** @var BagOStuff|null */
	private $store;

	/** @var WebRequest */
	private $request;

	/** @var MWSession|null */
	private $primarySession = null;

	/** @var MWSessionProvider[] */
	private $sessionProviders = null;

	/** @var string[] */
	private $varyCookies = null;

	/** @var array */
	private $varyHeaders = null;

	/** @var MWSessionBackend[] */
	private $allSessionBackends = array();

	/**
	 * Get the global MWSessionManager
	 * @return MWSessionManager
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self();
			MWSessionPHPSessionHandler::install( self::$instance );
		}
		return self::$instance;
	}

	/**
	 * @param array $options
	 *  - request: WebRequest to use for self::getSession(). Defaults to the
	 *    main RequestContext's request.
	 *  - config: Config to fetch configuration from. Defaults to the default 'main' config.
	 *  - logger: LoggerInterface to use for logging. Defaults to the 'session' channel.
	 *  - store: BagOStuff to store session data in.
	 */
	public function __construct( $options = array() ) {
		if ( isset( $options['request'] ) ) {
			$this->request = $options['request'];
			if ( !$this->request instanceof WebRequest ) {
				throw new InvalidArgumentException(
					'$options[\'request\'] must be an instance of WebRequest'
				);
			}
		} else {
			$this->request = RequestContext::getMain()->getRequest();
		}

		if ( isset( $options['config'] ) ) {
			$this->config = $options['config'];
			if ( !$this->config instanceof Config ) {
				throw new InvalidArgumentException(
					'$options[\'config\'] must be an instance of Config'
				);
			}
		} else {
			$this->config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}

		if ( isset( $options['logger'] ) ) {
			if ( !$options['logger'] instanceof LoggerInterface ) {
				throw new InvalidArgumentException(
					'$options[\'logger\'] must be an instance of LoggerInterface'
				);
			}
			$this->setLogger( $options['logger'] );
		} else {
			$this->setLogger( MediaWiki\Logger\LoggerFactory::getInstance( 'session' ) );
		}

		if ( isset( $options['store'] ) ) {
			if ( !$options['store'] instanceof BagOStuff ) {
				throw new InvalidArgumentException(
					'$options[\'store\'] must be an instance of BagOStuff'
				);
			}
			$this->store = $options['store'];
		} else {
			$this->store = ObjectCache::getInstance( $this->config->get( 'SessionCacheType' ) );
			$this->store->setLogger( $this->logger );

			if ( !$this->config->get( 'SessionsInObjectCache' ) ) {
				if ( $this->config->get( 'SessionHandler' ) ) {
					$what = '$wgSessionsInObjectCache = false and $wgSessionHandler are';
				} else {
					$what = '$wgSessionsInObjectCache = false is';
				}
				$this->logger->warning(
					$what . ' ignored by MWSessionManager. Session data will be stored in ' .
						'"' . get_class( $this->store ) . '" storage with expiry ' .
						$this->config->get( 'ObjectCacheSessionExpiry' ) . ' seconds.'
				);
			}
		}

		register_shutdown_function( array( $this, 'shutdown' ) );
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Fetch the primary session
	 *
	 * i.e. the session corresponding to the WebRequest passed to the
	 * constructor.
	 *
	 * @return MWSession
	 */
	public function getSession() {
		if ( !$this->primarySession ) {
			$this->primarySession = $this->getSessionForRequest( $this->request );
		}
		return $this->primarySession;
	}

	/**
	 * Fetch the session for a request
	 * @param WebRequest $request
	 * @return MWSession
	 */
	public function getSessionForRequest( WebRequest $request ) {
		// Call all providers to fetch "the" session
		$infos = array();
		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->provideSessionInfo( $request );
			if ( !$info ) {
				continue;
			}
			if ( $info->getProvider() !== $provider ) {
				throw new UnexpectedValueException(
					"$provider returned session info for a different provider: $info"
				);
			}
			$compare = $infos ? MWSessionInfo::compare( $infos[0], $info ) : -1;
			if ( $compare > 0 ) {
				continue;
			}
			if ( !$info->loadFromStore( $this, $this->store, $this->logger, $request ) ) {
				continue;
			}
			if ( $compare === 0 ) {
				$infos[] = $info;
			} else {
				$infos = array( $info );
			}
		}

		// Make sure there's only one
		if ( count( $infos ) > 1 ) {
			// We might get here before $wgContLang or $wgParser is set up,
			// and almost certainly before $wgUser. So only use ->plain()
			// and explicitly specify the language.
			$lang = Language::factory( $this->config->get( 'LanguageCode' ) );

			$list = array();
			foreach ( $infos as $info ) {
				$list[] = $info->getProvider()->describe( $lang );
			}
			$list = $lang->listToText( $list );
			throw new HttpError( 400,
				Message::newFromKey( 'sessionmanager-tie', $list )->inLanguage( $lang )->plain()
			);
		}

		// Make sure there *is* one
		if ( !$infos ) {
			$session = $this->getEmptySession( $request );
		} else {
			$session = $this->getSessionFromInfo( $infos[0], $request );
		}
		if ( $request === $this->request && !$this->primarySession ) {
			if ( $infos ) {
				$this->logger->debug( 'Using session ' . $infos[0] . ' for primary request' );
			} else {
				$this->logger->debug( 'Using empty session for primary request' );
			}
			$this->primarySession = $session;
		}
		return $session;
	}

	/**
	 * Fetch a session by ID
	 * @param string $id
	 * @param WebRequest|null $request Corresponding request
	 * @param bool $noEmpty Don't return an empty session
	 * @return MWSession|null
	 */
	public function getSessionById( $id, $request = null, $noEmpty = false ) {
		if ( !self::validateSessionId( $id ) ) {
			throw new InvalidArgumentException( 'Invalid session ID' );
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		// Test this here to provide a better log message for the common case
		// of "no such ID"
		$key = wfMemcKey( 'MWSession', 'metadata', $id );
		if ( $this->store->get( $key ) === false ) {
			if ( $noEmpty ) {
				return null;
			} else {
				return $this->getEmptySession( $request, $id );
			}
		} else {
			$info = new MWSessionInfo( MWSessionInfo::MIN_PRIORITY, array( 'id' => $id ) );
			if ( $info->loadFromStore( $this, $this->store, $this->logger, $request ) ) {
				return $this->getSessionFromInfo( $info, $request );
			}
		}

		return null;
	}

	/**
	 * Fetch a new, empty session
	 * @param WebRequest|null $request Corresponding request
	 * @param string|null $id ID to force for the new session
	 * @return MWSession
	 */
	public function getEmptySession( WebRequest $request = null, $id = null ) {
		if ( $id !== null ) {
			if ( !self::validateSessionId( $id ) ) {
				throw new InvalidArgumentException( 'Invalid session ID' );
			}

			$key = wfMemcKey( 'MWSession', 'metadata', $id );
			if ( $this->store->get( $key ) !== false ) {
				throw new InvalidArgumentException( 'Session ID already exists' );
			}
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->newSessionInfo( $id );
			if ( $info ) {
				if ( $info->getProvider() !== $provider ) {
					throw new UnexpectedValueException(
						"$provider returned an empty session info for a different provider: $info"
					);
				}
				if ( $id !== null && $info->getId() !== $id ) {
					throw new UnexpectedValueException(
						"$provider returned empty session info with a wrong id: " .
							$info->getId() . ' != ' . $id
					);
				}
				return $this->getSessionFromInfo( $info, $request );
			}
		}
		throw new UnexpectedValueException( 'No provider could provide an empty session!' );
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
			foreach ( $this->getProviders() as $provider ) {
				foreach ( $provider->getVaryHeaders() as $header => $options ) {
					if ( !isset( $headers[$header] ) ) {
						$headers[$header] = array();
					}
					if ( is_array( $options ) ) {
						$headers[$header] = array_unique( array_merge( $headers[$header], $options ) );
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
			foreach ( $this->getProviders() as $provider ) {
				$cookies = array_merge( $cookies, $provider->getVaryCookies() );
			}
			$this->varyCookies = array_values( array_unique( $cookies ) );
		}
		return $this->varyCookies;
	}

	/**
	 * Validate a session ID
	 * @param string $id
	 * @return bool
	 */
	public static function validateSessionId( $id ) {
		return is_string( $id ) && preg_match( '/^[a-zA-Z0-9_-]{32,}$/', $id );
	}

	/**
	 * @name Internal methods
	 * @{
	 */

	/**
	 * Auto-create the given user, if necessary
	 * @param User $user User to auto-create
	 */
	public function autoCreateUser( User $user ) {
		global $wgAuth, $wgMemc;

		// Much of this code is based on that in CentralAuth

		// Try the local user from the slave DB
		$localId = User::idFromName( $user->getName() );

		// Fetch the user ID from the master, so that we don't try to create the user
		// when they already exist, due to replication lag
		// @codeCoverageIgnoreStart
		if ( !$localId && wfGetLB()->getReaderIndex() != 0 ) {
			$localId = User::idFromName( $user->getName(), User::READ_LATEST );
		}
		// @codeCoverageIgnoreEnd

		if ( $localId ) {
			// User exists after all.
			$user->setId( $localId );
			$user->loadFromId();
			return;
		}

		// Denied by AuthPlugin? But ignore AuthPlugin itself.
		if ( get_class( $wgAuth ) !== 'AuthPlugin' && !$wgAuth->autoCreate() ) {
			$this->logger->debug( __METHOD__ . ': denied by AuthPlugin' );
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		// Wiki is read-only?
		if ( wfReadOnly() ) {
			$this->logger->debug( __METHOD__ . ': denied by wfReadOnly()' );
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		$userName = $user->getName();

		// Check the session, if we tried to create this user already there's
		// no point in retrying.
		$session = $this->getSession();
		if ( $session->get( 'MWSession::AutoCreateBlacklist' ) ) {
			$this->logger->debug( __METHOD__ . ': blacklisted in session' );
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		// Is the IP user able to create accounts?
		$anon = new User;
		if ( !$anon->isAllowedAny( 'createaccount', 'autocreateaccount' )
			|| $anon->isBlockedFromCreateAccount()
		) {
			// Blacklist the user to avoid repeated DB queries subsequently
			$this->logger->debug( __METHOD__ . ': user is blocked from this wiki, blacklisting' );
			$session->set( 'MWSession::AutoCreateBlacklist', 'blocked' );
			$session->persist();
			$session->save();
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		// Check for validity of username
		if ( !User::isCreatableName( $userName ) ) {
			$this->logger->debug( __METHOD__ . ': Invalid username, blacklisting' );
			$session->set( 'MWSession::AutoCreateBlacklist', 'invalid username' );
			$session->persist();
			$session->save();
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		// Give other extensions a chance to stop auto creation.
		$user->loadDefaults( $userName );
		$abortMessage = '';
		if ( !Hooks::run( 'AbortAutoAccount', array( $user, &$abortMessage ) ) ) {
			// In this case we have no way to return the message to the user,
			// but we can log it.
			$this->logger->debug( __METHOD__ . ": denied by hook: $abortMessage" );
			$session->set( 'MWSession::AutoCreateBlacklist', "hook aborted: $abortMessage" );
			$session->persist();
			$session->save();
			$user->setId( 0 );
			$user->loadFromId();
			return false;
		}

		// Make sure the name has not been changed
		if ( $user->getName() !== $userName ) {
			$user->setId( 0 );
			$user->loadFromId();
			throw new UnexpectedValueException(
				'AbortAutoAccount hook tried to change the user name'
			);
		}

		// Ignore warnings about master connections/writes...hard to avoid here
		Profiler::instance()->getTransactionProfiler()->resetExpectations();

		$backoffKey = wfMemcKey( 'MWSession', 'autocreate-failed', md5( $userName ) );
		if ( $wgMemc->get( $backoffKey ) ) {
			$this->logger->debug( __METHOD__ . ': denied by prior creation attempt failures' );
			$user->setId( 0 );
			$user->loadFromId();
			return;
		}

		// Checks passed, create the user...
		$from = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : 'CLI';
		$this->logger->info( __METHOD__ . ": creating new user ($userName) - from: $from" );

		try {
			// Insert the user into the local DB master
			$status = $user->addToDatabase();
			if ( !$status->isOK() ) {
				// @codeCoverageIgnoreStart
				$this->logger->error( __METHOD__ . ': failed with message ' . $status->getWikiText() );
				$user->setId( 0 );
				$user->loadFromId();
				return;
				// @codeCoverageIgnoreEnd
			}
		} catch ( Exception $ex ) {
			// @codeCoverageIgnoreStart
			$this->logger->error( __METHOD__ . ': failed with exception ' . $ex->getMessage() );
			// Do not keep throwing errors for a while
			$wgMemc->set( $backoffKey, 1, 60 * 10 );
			// Bubble up error; which should normally trigger DB rollbacks
			throw $ex;
			// @codeCoverageIgnoreEnd
		}

		# Notify hooks (e.g. Newuserlog)
		Hooks::run( 'AuthPluginAutoCreate', array( $user ) );
		Hooks::run( 'LocalUserCreated', array( $user, true ) );

		# Update user count
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );
	}

	/**
	 * Get the available SessionProviders
	 * @return MWSessionProvider[]
	 */
	protected function getProviders() {
		if ( $this->sessionProviders === null ) {
			$this->sessionProviders = array();
			foreach ( $this->config->get( 'MWSessionProviders' ) as $spec ) {
				$provider = ObjectFactory::getObjectFromSpec( $spec );
				$provider->setLogger( $this->logger );
				$provider->setConfig( $this->config );
				$provider->setManager( $this );
				if ( isset( $this->sessionProviders[(string)$provider] ) ) {
					throw new UnexpectedValueException( "Duplicate provider name \"$provider\"" );
				}
				$this->sessionProviders[(string)$provider] = $provider;
			}
		}
		return $this->sessionProviders;
	}

	/**
	 * Get a session provider by name
	 * @param string $name
	 * @return MWSessionProvider|null
	 */
	public function getProvider( $name ) {
		$providers = $this->getProviders();
		return isset( $providers[$name] ) ? $providers[$name] : null;
	}

	/**
	 * Save all active sessions on shutdown
	 * @note This is automatically registered with register_shutdown_function()
	 */
	public function shutdown() {
		foreach ( $this->allSessionBackends as $backend ) {
			$backend->save();
		}
	}

	/**
	 * Create a session corresponding to the passed MWSessionInfo
	 * @param MWSessionInfo $info
	 * @param WebRequest $request
	 * @return MWSession
	 */
	private function getSessionFromInfo( MWSessionInfo $info, WebRequest $request ) {
		$id = $info->getId();

		if ( !isset( $this->allSessionBackends[$id] ) ) {
			$backend = new MWSessionBackend(
				$info, $this->store, $this->config->get( 'ObjectCacheSessionExpiry' )
			);
			$this->allSessionBackends[$id] = $backend;
		} else {
			$backend = $this->allSessionBackends[$id];
			if ( $info->wasPersisted() ) {
				$backend->persist();
			}
			if ( $info->wasRemembered() ) {
				$backend->setRememberUser( true );
			}
		}

		return $backend->getSession( $request );
	}

	/**
	 * Deregister an MWSessionBackend
	 * @param MWSessionBackend
	 */
	public function deregisterSessionBackend( MWSessionBackend $backend ) {
		$backend->save();
		$id = $backend->getId();
		unset( $this->allSessionBackends[$id] );
	}

	/**
	 * Generate a new random session ID
	 * @return string
	 */
	public function generateSessionId() {
		do {
			$id = wfBaseConvert( MWCryptRand::generateHex( 40 ), 16, 32, 32 );
			$key = wfMemcKey( 'MWSession', 'metadata', $id );
		} while ( $this->store->get( $key ) !== false );
		return $id;
	}

	/**
	 * Call setters on a MWSessionPHPSessionHandler
	 * @param MWSessionPHPSessionHandler $handler
	 */
	public function setupMWSessionPHPSessionHandler( MWSessionPHPSessionHandler $handler ) {
		$handler->setManager( $this, $this->store, $this->logger );
	}

	/**@}*/

}
