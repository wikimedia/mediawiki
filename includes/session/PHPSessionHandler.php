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

namespace MediaWiki\Session;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SessionHandlerInterface;
use Wikimedia\AtEase\AtEase;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\PhpSessionSerializer;

/**
 * Adapter for PHP's session handling
 *
 * @since 1.27
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
	protected ?BagOStuff $store = null;
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
		register_shutdown_function( $manager->shutdown( ... ) );
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
	 * Set the manager, store, and logger
	 * @internal Use self::install().
	 * @param SessionManagerInterface $manager
	 * @param BagOStuff $store
	 * @param LoggerInterface $logger
	 */
	public function setManager(
		SessionManagerInterface $manager, BagOStuff $store, LoggerInterface $logger
	) {
		if ( $this->manager !== $manager ) {
			// Close any existing session before we change stores
			if ( $this->manager ) {
				session_write_close();
			}
			$this->manager = $manager;
			$this->store = $store;
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

		$session = $this->manager->getSessionById( $id, false );
		if ( !$session ) {
			return '';
		}
		$session->persist();

		$data = iterator_to_array( $session );
		$this->sessionFieldCache[$id] = $data;
		return (string)PhpSessionSerializer::encode( $data );
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
		$changed = false;
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
					$changed = true;
				}
			} elseif ( $cache[$key] === $value ) {
				// Unchanged in $_SESSION, so ignore it
			} elseif ( !$session->exists( $key ) ) {
				// Deleted in Session, keep but log
				$this->logger->warning(
					__METHOD__ . ": Key \"$key\" deleted in Session and changed in \$_SESSION!"
				);
				$session->set( $key, $value );
				$changed = true;
			} elseif ( $cache[$key] === $session->get( $key ) ) {
				// Unchanged in Session, so keep it
				$session->set( $key, $value );
				$changed = true;
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
					$changed = true;
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
				$this->logger->warning( 'Something wrote to $_SESSION!' );
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
		$this->store->deleteObjectsExpiringBefore( wfTimestampNow() );
		return true;
	}
}
