<?php
/**
 * Session storage in object cache.
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

/**
 * Adapter for PHP's session handling
 * @ingroup Session
 * @since 1.27
 */
class PHPSessionHandler implements \SessionHandlerInterface {
	/** @var PHPSessionHandler */
	protected static $instance = null;

	/** @var bool Whether PHP session handling is enabled */
	protected $enable = false;
	protected $warn = true;

	/** @var SessionManager|null */
	protected $manager;

	/** @var BagOStuff|null */
	protected $store;

	/** @var LoggerInterface */
	protected $logger;

	/** @var array Track original session fields for later modification check */
	protected $sessionFieldCache = [];

	protected function __construct( SessionManager $manager ) {
		$this->setEnableFlags(
			\RequestContext::getMain()->getConfig()->get( 'PHPSessionHandling' )
		);
		$manager->setupPHPSessionHandler( $this );
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
	 * @param SessionManager $manager
	 */
	public static function install( SessionManager $manager ) {
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

		// Tell PHP not to mess with cookies itself
		ini_set( 'session.use_cookies', 0 );
		ini_set( 'session.use_trans_sid', 0 );

		// T124510: Disable automatic PHP session related cache headers.
		// MediaWiki adds it's own headers and the default PHP behavior may
		// set headers such as 'Pragma: no-cache' that cause problems with
		// some user agents.
		session_cache_limiter( '' );

		// Also set a sane serialization handler
		\Wikimedia\PhpSessionSerializer::setSerializeHandler();

		// Register this as the save handler, and register an appropriate
		// shutdown function.
		session_set_save_handler( self::$instance, true );
	}

	/**
	 * Set the manager, store, and logger
	 * @private Use self::install().
	 * @param SessionManager $manager
	 * @param BagOStuff $store
	 * @param LoggerInterface $store
	 */
	public function setManager(
		SessionManager $manager, BagOStuff $store, LoggerInterface $logger
	) {
		if ( $this->manager !== $manager ) {
			// Close any existing session before we change stores
			if ( $this->manager ) {
				session_write_close();
			}
			$this->manager = $manager;
			$this->store = $store;
			$this->logger = $logger;
			\Wikimedia\PhpSessionSerializer::setLogger( $this->logger );
		}
	}

	/**
	 * Initialize the session (handler)
	 * @private For internal use only
	 * @param string $save_path Path used to store session files (ignored)
	 * @param string $session_name Session name (ignored)
	 * @return bool Success
	 */
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
	 * @private For internal use only
	 * @return bool Success
	 */
	public function close() {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		$this->sessionFieldCache = [];
		return true;
	}

	/**
	 * Read session data
	 * @private For internal use only
	 * @param string $id Session id
	 * @return string Session data
	 */
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
		return (string)\Wikimedia\PhpSessionSerializer::encode( $data );
	}

	/**
	 * Write session data
	 * @private For internal use only
	 * @param string $id Session id
	 * @param string $dataStr Session data. Not that you should ever call this
	 *   directly, but note that this has the same issues with code injection
	 *   via user-controlled data as does PHP's unserialize function.
	 * @return bool Success
	 */
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
		$data = \Wikimedia\PhpSessionSerializer::decode( $dataStr );
		if ( $data === null ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		// Now merge the data into the Session object.
		$changed = false;
		$cache = isset( $this->sessionFieldCache[$id] ) ? $this->sessionFieldCache[$id] : [];
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
		\Wikimedia\PhpSessionSerializer::setLogger( new \Psr\Log\NullLogger() );
		foreach ( $cache as $key => $value ) {
			if ( !array_key_exists( $key, $data ) && $session->exists( $key ) &&
				\Wikimedia\PhpSessionSerializer::encode( [ $key => true ] )
			) {
				if ( $cache[$key] === $session->get( $key ) ) {
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
		\Wikimedia\PhpSessionSerializer::setLogger( $this->logger );

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
	 * @private For internal use only
	 * @param string $id Session id
	 * @return bool Success
	 */
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
	 * @private For internal use only
	 * @param int $maxlifetime Maximum session life time (ignored)
	 * @return bool Success
	 */
	public function gc( $maxlifetime ) {
		if ( self::$instance !== $this ) {
			throw new \UnexpectedValueException( __METHOD__ . ': Wrong instance called!' );
		}
		$before = date( 'YmdHis', time() );
		$this->store->deleteObjectsExpiringBefore( $before );
		return true;
	}
}
