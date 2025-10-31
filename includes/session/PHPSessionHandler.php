<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SessionHandlerInterface;
use Wikimedia\AtEase\AtEase;
use Wikimedia\PhpSessionSerializer;

/**
 * Adapter for PHP's session handling
 *
 * @since 1.27
 * @deprecated since 1.45 Integration with PHP session handling will be removed in the future
 * @ingroup Session
 */
class PHPSessionHandler implements SessionHandlerInterface {
	/** @var PHPSessionHandler */
	protected static $instance = null;

	/** @var bool Whether PHP session handling is enabled */
	protected $enable = false;

	/** @var bool */
	protected $warn = true;

	protected ?SessionManagerInterface $manager = null;
	protected LoggerInterface $logger;

	/** @var array Track original session fields for later modification check */
	protected $sessionFieldCache = [];

	protected function __construct( SessionManagerInterface $manager ) {
		$this->setEnableFlags(
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::PHPSessionHandling )
		);
		if ( $manager instanceof SessionManager ) {
			$manager->setupPHPSessionHandler( $this );
		}
	}

	/**
	 * Set $this->enable and $this->warn
	 *
	 * Separate just because there doesn't seem to be a good way to test it
	 * otherwise.
	 *
	 * @param string $PHPSessionHandling See $wgPHPSessionHandling
	 */
	private function setEnableFlags( $PHPSessionHandling ) {
		switch ( $PHPSessionHandling ) {
			case 'enable':
				$this->enable = true;
				$this->warn = false;
				break;

			case 'warn':
				$this->enable = true;
				$this->warn = true;
				break;

			case 'disable':
				$this->enable = false;
				$this->warn = false;
				break;
		}
	}

	/**
	 * Test whether the handler is installed
	 * @return bool
	 */
	public static function isInstalled() {
		return (bool)self::$instance;
	}

	/**
	 * Test whether the handler is installed and enabled
	 * @return bool
	 */
	public static function isEnabled() {
		return self::$instance && self::$instance->enable;
	}

	/**
	 * Install a session handler for the current web request
	 */
	public static function install( SessionManagerInterface $manager ) {
		/* @var SessionManager $manager*/'@phan-var SessionManager $manager';
		if ( self::$instance ) {
			$manager->setupPHPSessionHandler( self::$instance );
			return;
		}

		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION_HANDLER' ) ) {
			throw new \BadMethodCallException( 'MW_NO_SESSION_HANDLER is defined' );
		}
		// @codeCoverageIgnoreEnd

		self::$instance = new self( $manager );

		// Close any auto-started session, before we replace it
		session_write_close();

		try {
			AtEase::suppressWarnings();

			// Tell PHP not to mess with cookies itself
			ini_set( 'session.use_cookies', 0 );

			// T124510: Disable automatic PHP session related cache headers.
			// MediaWiki adds its own headers and the default PHP behavior may
			// set headers such as 'Pragma: no-cache' that cause problems with
			// some user agents.
			session_cache_limiter( '' );

			// Also set a serialization handler
			PhpSessionSerializer::setSerializeHandler();

			// Register this as the save handler, and register an appropriate
			// shutdown function.
			session_set_save_handler( self::$instance, true );
		} finally {
			AtEase::restoreWarnings();
		}
	}

	/**
	 * @internal Use self::install().
	 * @param SessionManagerInterface $manager
	 * @param LoggerInterface $logger
	 */
	public function setManager(
		SessionManagerInterface $manager, LoggerInterface $logger
	) {
		if ( $this->manager !== $manager ) {
			// Close any existing session before we change stores
			if ( $this->manager ) {
				session_write_close();
			}
			$this->manager = $manager;
			$this->logger = $logger;
			PhpSessionSerializer::setLogger( $this->logger );
		}
	}

	/**
	 * Initialize the session (handler)
	 * @internal For internal use only
	 * @param string $save_path Path used to store session files (ignored)
	 * @param string $session_name Session name (ignored)
	 * @return true
	 */
	#[\ReturnTypeWillChange]
	public function open( $save_path, $session_name ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		if ( !$this->enable ) {
			throw new \BadMethodCallException( 'Attempt to use PHP session management' );
		}
		return true;
	}

	/**
	 * Close the session (handler)
	 * @internal For internal use only
	 * @return true
	 */
	#[\ReturnTypeWillChange]
	public function close() {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		$this->sessionFieldCache = [];
		return true;
	}

	/**
	 * Read session data
	 * @internal For internal use only
	 * @param string $id Session id
	 * @return string Session data
	 */
	#[\ReturnTypeWillChange]
	public function read( $id ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		if ( !$this->enable ) {
			throw new \BadMethodCallException( 'Attempt to use PHP session management' );
		}

		// NOTE: PHPUnit tests also run in CLI mode, so we need to ignore them
		// otherwise tests will break in CI.
		if ( wfIsCLI() && !defined( 'MW_PHPUNIT_TEST' ) ) {
			// T405450: Don't reuse a reference of the cached session manager
			// object in command-line mode when spawning child processes. Always
			// get a fresh instance. This is because during service reset, there
			// could be references to services container that is disabled.
			$session = MediaWikiServices::getInstance()->getSessionManager()
				->getSessionById( $id, false );
		} else {
			$session = $this->manager->getSessionById( $id, false );
		}

		if ( !$session ) {
			return '';
		}
		$session->persist();

		$data = iterator_to_array( $session );
		$this->sessionFieldCache[$id] = $data;
		return (string)PhpSessionSerializer::encode( $data );
	}

	/**
	 * Check if the value is an object, or is an array that contains an object somewhere inside.
	 */
	private function valueContainsAnyObject( mixed $value ): bool {
		if ( is_object( $value ) ) {
			return true;
		}
		if ( is_array( $value ) ) {
			$result = false;
			array_walk_recursive( $value, static function ( $val ) use ( &$result ) {
				if ( is_object( $val ) ) {
					$result = true;
				}
			} );
			return $result;
		}
		return false;
	}

	/**
	 * Write session data
	 * @internal For internal use only
	 * @param string $id Session id
	 * @param string $dataStr Session data. Not that you should ever call this
	 *   directly, but note that this has the same issues with code injection
	 *   via user-controlled data as does PHP's unserialize function.
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function write( $id, $dataStr ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		if ( !$this->enable ) {
			throw new \BadMethodCallException( 'Attempt to use PHP session management' );
		}

		$session = $this->manager->getSessionById( $id, true );
		if ( !$session ) {
			// This can happen under normal circumstances, if the session exists but is
			// invalid. Let's emit a log warning instead of a PHP warning.
			$this->logger->warning(
				__METHOD__ . ': Session "{session}" cannot be loaded, skipping write.',
				[
					'session' => $id,
				] );
			return true;
		}

		// First, decode the string PHP handed us
		$data = PhpSessionSerializer::decode( $dataStr );
		if ( $data === null ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		// Now merge the data into the Session object.
		$changed = [];
		$cache = $this->sessionFieldCache[$id] ?? [];
		foreach ( $data as $key => $value ) {
			if ( !array_key_exists( $key, $cache ) ) {
				if ( $session->exists( $key ) ) {
					// New in both, so ignore and log
					$this->logger->warning(
						__METHOD__ . ": Key \"$key\" added in both Session and \$_SESSION!"
					);
				} else {
					// New in $_SESSION, keep it
					$session->set( $key, $value );
					$changed[] = $key;
				}
			} elseif ( $cache[$key] === $value ) {
				// Unchanged in $_SESSION, so ignore it
			} elseif (
				$this->valueContainsAnyObject( $cache[$key] ) &&
				$this->valueContainsAnyObject( $value ) &&
				PhpSessionSerializer::encode( [ $key => $cache[$key] ] ) ===
					PhpSessionSerializer::encode( [ $key => $value ] )
			) {
				// Also unchanged in $_SESSION. The values go through a serialize-and-deserialize
				// operation before they get here, so if anyone stored any objects in session data,
				// they will not compare as equal with `===`. Compare their serialized representation
				// in that case to avoid unnecessary session writes and spurious warnings. (T402602)
			} elseif ( !$session->exists( $key ) ) {
				// Deleted in Session, keep but log
				$this->logger->warning(
					__METHOD__ . ": Key \"$key\" deleted in Session and changed in \$_SESSION!"
				);
				$session->set( $key, $value );
				$changed[] = $key;
			} elseif ( $cache[$key] === $session->get( $key ) ) {
				// Unchanged in Session, so keep it
				$session->set( $key, $value );
				$changed[] = $key;
			} else {
				// Changed in both, so ignore and log
				$this->logger->warning(
					__METHOD__ . ": Key \"$key\" changed in both Session and \$_SESSION!"
				);
			}
		}
		// Anything deleted in $_SESSION and unchanged in Session should be deleted too
		// (but not if $_SESSION can't represent it at all)
		PhpSessionSerializer::setLogger( new NullLogger() );
		foreach ( $cache as $key => $value ) {
			if ( !array_key_exists( $key, $data ) && $session->exists( $key ) &&
				PhpSessionSerializer::encode( [ $key => true ] )
			) {
				if ( $value === $session->get( $key ) ) {
					// Unchanged in Session, delete it
					$session->remove( $key );
					$changed[] = $key;
				} else {
					// Changed in Session, ignore deletion and log
					$this->logger->warning(
						__METHOD__ . ": Key \"$key\" changed in Session and deleted in \$_SESSION!"
					);
				}
			}
		}
		PhpSessionSerializer::setLogger( $this->logger );

		// Save and update cache if anything changed
		if ( $changed ) {
			if ( $this->warn ) {
				wfDeprecated( '$_SESSION', '1.27' );
				foreach ( $changed as $key ) {
					$this->logger->warning( "Something wrote to \$_SESSION['$key']!" );
				}
			}

			$session->save();
			$this->sessionFieldCache[$id] = iterator_to_array( $session );
		}

		$session->persist();

		return true;
	}

	/**
	 * Destroy a session
	 * @internal For internal use only
	 * @param string $id Session id
	 * @return true
	 */
	#[\ReturnTypeWillChange]
	public function destroy( $id ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		if ( !$this->enable ) {
			throw new \BadMethodCallException( 'Attempt to use PHP session management' );
		}
		$session = $this->manager->getSessionById( $id, false );
		if ( $session ) {
			$session->clear();
		}
		return true;
	}

	/**
	 * Execute garbage collection.
	 * @internal For internal use only
	 * @param int $maxlifetime Maximum session life time (ignored)
	 * @return true
	 * @codeCoverageIgnore See T135576
	 */
	#[\ReturnTypeWillChange]
	public function gc( $maxlifetime ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		return true;
	}
}
