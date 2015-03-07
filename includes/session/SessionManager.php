<?php
/**
 * MediaWiki\Session entry point
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

namespace MediaWiki\Session;

use Psr\Log\LoggerInterface;
use BagOStuff;
use Config;
use FauxRequest;
use Language;
use Message;
use User;
use WebRequest;

/**
 * This serves as the entry point to the MediaWiki session handling system.
 *
 * @ingroup Session
 * @since 1.27
 */
final class SessionManager implements SessionManagerInterface {
	/** @var SessionManager|null */
	private static $instance = null;

	/** @var Session|null */
	private static $globalSession = null;

	/** @var WebRequest|null */
	private static $globalSessionRequest = null;

	/** @var LoggerInterface */
	private $logger;

	/** @var Config */
	private $config;

	/** @var BagOStuff|null */
	private $store;

	/** @var SessionProvider[] */
	private $sessionProviders = null;

	/** @var string[] */
	private $varyCookies = null;

	/** @var array */
	private $varyHeaders = null;

	/** @var SessionBackend[] */
	private $allSessionBackends = array();

	/** @var SessionId[] */
	private $allSessionIds = array();

	/** @var string[] */
	private $preventUsers = array();

	/**
	 * Get the global SessionManager
	 * @return SessionManagerInterface
	 *  (really a SessionManager, but this is to make IDEs less confused)
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get the "global" session
	 *
	 * If PHP's session_id() has been set, returns that session. Otherwise
	 * returns the session for RequestContext::getMain()->getRequest().
	 *
	 * @return Session
	 */
	public static function getGlobalSession() {
		if ( !PHPSessionHandler::isEnabled() ) {
			$id = '';
		} else {
			$id = session_id();
		}

		$request = \RequestContext::getMain()->getRequest();
		if ( !self::$globalSession || self::$globalSessionRequest !== $request ||
			$id !== '' && self::$globalSession->getId() !== $id
		) {
			self::$globalSessionRequest = $request;
			if ( $id === '' ) {
				self::$globalSession = $request->getSession();
			} else {
				self::$globalSession = self::singleton()->getSessionById( $id, false, $request );
			}
		}
		return self::$globalSession;
	}

	/**
	 * @param array $options
	 *  - config: Config to fetch configuration from. Defaults to the default 'main' config.
	 *  - logger: LoggerInterface to use for logging. Defaults to the 'session' channel.
	 *  - store: BagOStuff to store session data in.
	 */
	public function __construct( $options = array() ) {
		if ( isset( $options['config'] ) ) {
			$this->config = $options['config'];
			if ( !$this->config instanceof Config ) {
				throw new \InvalidArgumentException(
					'$options[\'config\'] must be an instance of Config'
				);
			}
		} else {
			$this->config = \ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}

		if ( isset( $options['logger'] ) ) {
			if ( !$options['logger'] instanceof LoggerInterface ) {
				throw new \InvalidArgumentException(
					'$options[\'logger\'] must be an instance of LoggerInterface'
				);
			}
			$this->setLogger( $options['logger'] );
		} else {
			$this->setLogger( \MediaWiki\Logger\LoggerFactory::getInstance( 'session' ) );
		}

		if ( isset( $options['store'] ) ) {
			if ( !$options['store'] instanceof BagOStuff ) {
				throw new \InvalidArgumentException(
					'$options[\'store\'] must be an instance of BagOStuff'
				);
			}
			$this->store = $options['store'];
		} else {
			$this->store = \ObjectCache::getInstance( $this->config->get( 'SessionCacheType' ) );
			$this->store->setLogger( $this->logger );
		}

		register_shutdown_function( array( $this, 'shutdown' ) );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function getPersistedSessionId( WebRequest $request ) {
		$infos = $this->getSessionInfoForRequest( $request );
		if ( count( $infos ) > 1 ) {
			throw new \InvalidArgumentException(
				'Multiple sessions for this request tied for top priority: ' . join( ', ', $infos )
			);
		}
		if ( $infos && $infos[0]->wasPersisted() ) {
			return $infos[0]->getId();
		} else {
			return null;
		}
	}

	public function getSessionForRequest( WebRequest $request ) {
		$infos = $this->getSessionInfoForRequest( $request );

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
			throw new \HttpError( 400,
				Message::newFromKey( 'sessionmanager-tie', $list )->inLanguage( $lang )->plain()
			);
		}

		if ( !$infos ) {
			$session = $this->getEmptySession( $request );
		} else {
			$session = $this->getSessionFromInfo( $infos[0], $request );
		}
		return $session;
	}

	public function getSessionById( $id, $noEmpty = false, WebRequest $request = null ) {
		if ( !self::validateSessionId( $id ) ) {
			throw new \InvalidArgumentException( 'Invalid session ID' );
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		$session = null;

		// Test this here to provide a better log message for the common case
		// of "no such ID"
		$key = wfMemcKey( 'MWSession', $id );
		if ( is_array( $this->store->get( $key ) ) ) {
			$info = new SessionInfo( SessionInfo::MIN_PRIORITY, array( 'id' => $id ) );
			if ( $info->loadFromStore( $this, $this->store, $this->logger, $request ) ) {
				$session = $this->getSessionFromInfo( $info, $request );
			}
		}

		if ( !$noEmpty && $session === null ) {
			$ex = null;
			try {
				$session = $this->getEmptySession( $request, $id );
			} catch ( \Exception $ex ) {
				$this->logger->error( __METHOD__ . ': failed to create empty session: ' .
					$ex->getMessage() );
				$session = null;
			}
			if ( $session === null ) {
				throw new \UnexpectedValueException(
					'Can neither load the session nor create an empty session', 0, $ex
				);
			}
		}

		return $session;
	}

	public function getEmptySession( WebRequest $request = null, $id = null ) {
		if ( $id !== null ) {
			if ( !self::validateSessionId( $id ) ) {
				throw new \InvalidArgumentException( 'Invalid session ID' );
			}

			$key = wfMemcKey( 'MWSession', $id );
			if ( is_array( $this->store->get( $key ) ) ) {
				throw new \InvalidArgumentException( 'Session ID already exists' );
			}
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		$infos = array();
		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->newSessionInfo( $id );
			if ( !$info ) {
				continue;
			}
			if ( $info->getProvider() !== $provider ) {
				throw new \UnexpectedValueException(
					"$provider returned an empty session info for a different provider: $info"
				);
			}
			if ( $id !== null && $info->getId() !== $id ) {
				throw new \UnexpectedValueException(
					"$provider returned empty session info with a wrong id: " .
						$info->getId() . ' != ' . $id
				);
			}
			$compare = $infos ? SessionInfo::compare( $infos[0], $info ) : -1;
			if ( $compare > 0 ) {
				continue;
			}
			if ( $compare === 0 ) {
				$infos[] = $info;
			} else {
				$infos = array( $info );
			}
		}

		// Make sure there's exactly one
		if ( count( $infos ) > 1 ) {
			throw new \UnexpectedValueException(
				'Multiple empty sessions tied for top priority: ' . join( ', ', $infos )
			);
		} elseif ( count( $infos ) < 1 ) {
			throw new \UnexpectedValueException( 'No provider could provide an empty session!' );
		}

		return $this->getSessionFromInfo( $infos[0], $request );
	}

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
	 * @private Don't call this yourself. Let Setup.php do it for you at the right time.
	 * @deprecated since 1.27, use MediaWiki\Auth\AuthManager::autoCreateUser instead
	 * @param User $user User to auto-create
	 * @return bool Success
	 * @codeCoverageIgnore
	 */
	public static function autoCreateUser( User $user ) {
		wfDeprecated( __METHOD__, '1.27' );
		return \MediaWiki\Auth\AuthManager::singleton()->autoCreateUser( $user, false )->isGood();
	}

	/**
	 * Prevent future sessions for the user
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the prevention of access).
	 *
	 * @private For use from \\User::newSystemUser only
	 * @param string $username
	 */
	public function preventSessionsForUser( $username ) {
		$this->preventUsers[$username] = true;

		// Reset the user's token to kill existing sessions
		$user = User::newFromName( $username );
		if ( $user && $user->getToken() ) {
			$user->setToken( true );
			$user->saveSettings();
		}

		// Instruct the session providers to kill any other sessions too.
		foreach ( $this->getProviders() as $provider ) {
			$provider->preventSessionsForUser( $username );
		}
	}

	/**
	 * Test if a user is prevented
	 * @private For use from SessionBackend only
	 * @param string $username
	 * @return bool
	 */
	public function isUserSessionPrevented( $username ) {
		return !empty( $this->preventUsers[$username] );
	}

	/**
	 * Get the available SessionProviders
	 * @return SessionProvider[]
	 */
	protected function getProviders() {
		if ( $this->sessionProviders === null ) {
			$this->sessionProviders = array();
			foreach ( $this->config->get( 'SessionProviders' ) as $spec ) {
				$provider = \ObjectFactory::getObjectFromSpec( $spec );
				$provider->setLogger( $this->logger );
				$provider->setConfig( $this->config );
				$provider->setManager( $this );
				if ( isset( $this->sessionProviders[(string)$provider] ) ) {
					throw new \UnexpectedValueException( "Duplicate provider name \"$provider\"" );
				}
				$this->sessionProviders[(string)$provider] = $provider;
			}
		}
		return $this->sessionProviders;
	}

	/**
	 * Get a session provider by name
	 * @private For use from MediaWiki\\Session\\SessionInfo only
	 * @param string $name
	 * @return SessionProvider|null
	 */
	public function getProvider( $name ) {
		$providers = $this->getProviders();
		return isset( $providers[$name] ) ? $providers[$name] : null;
	}

	/**
	 * Save all active sessions on shutdown
	 * @private For internal use with register_shutdown_function()
	 */
	public function shutdown() {
		if ( $this->allSessionBackends ) {
			$this->logger->debug( 'Saving all sessions on shutdown' );
			if ( session_id() !== '' ) {
				// @codeCoverageIgnoreStart
				session_write_close();
			}
			// @codeCoverageIgnoreEnd
			foreach ( $this->allSessionBackends as $backend ) {
				$backend->save( true );
			}
		}
	}

	/**
	 * Fetch the SessionInfo(s) for a request
	 * @param WebRequest $request
	 * @return SessionInfo[]
	 */
	private function getSessionInfoForRequest( WebRequest $request ) {
		// Call all providers to fetch "the" session
		$infos = array();
		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->provideSessionInfo( $request );
			if ( !$info ) {
				continue;
			}
			if ( $info->getProvider() !== $provider ) {
				throw new \UnexpectedValueException(
					"$provider returned session info for a different provider: $info"
				);
			}
			$compare = $infos ? SessionInfo::compare( $infos[0], $info ) : -1;
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

		return $infos;
	}

	/**
	 * Create a session corresponding to the passed SessionInfo
	 * @param SessionInfo $info
	 * @param WebRequest $request
	 * @return Session
	 */
	private function getSessionFromInfo( SessionInfo $info, WebRequest $request ) {
		$id = $info->getId();

		if ( !isset( $this->allSessionBackends[$id] ) ) {
			if ( !isset( $this->allSessionIds[$id] ) ) {
				$this->allSessionIds[$id] = new SessionId( $id );
			}
			$backend = new SessionBackend(
				$this->allSessionIds[$id],
				$info,
				$this->store,
				$this->logger,
				$this->config->get( 'ObjectCacheSessionExpiry' )
			);
			$this->allSessionBackends[$id] = $backend;
			$delay = $backend->delaySave();
		} else {
			$backend = $this->allSessionBackends[$id];
			$delay = $backend->delaySave();
			if ( $info->wasPersisted() ) {
				$backend->persist();
			}
			if ( $info->wasRemembered() ) {
				$backend->setRememberUser( true );
			}
		}

		$request->setSession( $backend );
		$ret = $backend->getSession( $request );
		\ScopedCallback::consume( $delay );
		return $ret;
	}

	/**
	 * Deregister a SessionBackend
	 * @private For use from \\MediaWiki\\Session\\SessionBackend only
	 * @param SessionBackend $backend
	 */
	public function deregisterSessionBackend( SessionBackend $backend ) {
		$id = $backend->getId();
		if ( !isset( $this->allSessionBackends[$id] ) || !isset( $this->allSessionIds[$id] ) ||
			$this->allSessionBackends[$id] !== $backend ||
			$this->allSessionIds[$id] !== $backend->getSessionId()
		) {
			throw new \InvalidArgumentException( 'Backend was not registered with this SessionManager' );
		}

		unset( $this->allSessionBackends[$id] );
		// Explicitly do not unset $this->allSessionIds[$id]
	}

	/**
	 * Change a SessionBackend's ID
	 * @private For use from \\MediaWiki\\Session\\SessionBackend only
	 * @param SessionBackend $backend
	 */
	public function changeBackendId( SessionBackend $backend ) {
		$sessionId = $backend->getSessionId();
		$oldId = (string)$sessionId;
		if ( !isset( $this->allSessionBackends[$oldId] ) || !isset( $this->allSessionIds[$oldId] ) ||
			$this->allSessionBackends[$oldId] !== $backend ||
			$this->allSessionIds[$oldId] !== $sessionId
		) {
			throw new \InvalidArgumentException( 'Backend was not registered with this SessionManager' );
		}

		$newId = $this->generateSessionId();

		unset( $this->allSessionBackends[$oldId], $this->allSessionIds[$oldId] );
		$sessionId->setId( $newId );
		$this->allSessionBackends[$newId] = $backend;
		$this->allSessionIds[$newId] = $sessionId;
	}

	/**
	 * Generate a new random session ID
	 * @return string
	 */
	public function generateSessionId() {
		do {
			$id = wfBaseConvert( \MWCryptRand::generateHex( 40 ), 16, 32, 32 );
			$key = wfMemcKey( 'MWSession', $id );
		} while ( isset( $this->allSessionIds[$id] ) || is_array( $this->store->get( $key ) ) );
		return $id;
	}

	/**
	 * Call setters on a PHPSessionHandler
	 * @private Use PhpSessionHandler::install()
	 * @param PHPSessionHandler $handler
	 */
	public function setupPHPSessionHandler( PHPSessionHandler $handler ) {
		$handler->setManager( $this, $this->store, $this->logger );
	}

	/**
	 * Override the singleton for unit testing
	 * @param SessionManager|null $manager
	 * @return \\ScopedCallback|null
	 */
	public static function setSingletonForTest( SessionManager $manager = null ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			// @codeCoverageIgnoreStart
			throw new MWException( __METHOD__ . ' may only be called from unit tests!' );
			// @codeCoverageIgnoreEnd
		}

		session_write_close();

		$oldInstance = self::$instance;

		$reset = array(
			array( &self::$instance, $oldInstance ),
			array( &self::$globalSession, self::$globalSession ),
			array( &self::$globalSessionRequest, self::$globalSessionRequest ),
		);

		self::$instance = $manager;
		self::$globalSession = null;
		self::$globalSessionRequest = null;
		if ( $manager && PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install( $manager );
		}

		return new \ScopedCallback( function () use ( &$reset, $oldInstance ) {
			foreach ( $reset as &$arr ) {
				$arr[0] = $arr[1];
			}
			if ( $oldInstance && PHPSessionHandler::isInstalled() ) {
				PHPSessionHandler::install( $oldInstance );
			}
		} );
	}

	/**
	 * Reset the internal caching for unit testing
	 */
	public static function resetCache() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			// @codeCoverageIgnoreStart
			throw new MWException( __METHOD__ . ' may only be called from unit tests!' );
			// @codeCoverageIgnoreEnd
		}

		self::$globalSession = null;
		self::$globalSessionRequest = null;
	}

	/**@}*/

}
