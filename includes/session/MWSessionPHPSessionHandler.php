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

use Psr\Log\LoggerInterface;

/**
 * Adapter for PHP's session handling
 * @todo Once we drop support for PHP < 5.4, use SessionHandlerInterface
 *  (should just be a matter of adding "implements SessionHandlerInterface" and
 *  changing the session_set_save_handler() call).
 * @ingroup Session
 * @since 1.27
 */
class MWSessionPHPSessionHandler {
	/** @var MWSessionPHPSessionHandler */
	protected static $instance = null;

	/** @var MWSessionManager */
	protected $manager;

	/** @var BagOStuff */
	protected $store;

	/** @var LoggerInterface */
	protected $logger;

	/** @var array Track original session fields for later modification check */
	protected $sessionFieldCache = array();

	protected function __construct( MWSessionManager $manager ) {
		$manager->setupMWSessionPHPSessionHandler( $this );
	}

	/**
	 * Install a session handler for the current web request
	 * @param MWSessionManager $manager
	 */
	public static function install( MWSessionManager $manager ) {
		if ( self::$instance ) {
			$manager->setupMWSessionPHPSessionHandler( self::$instance );
			return;
		}

		self::$instance = new self( $manager );

		// Close any auto-started session, before we replace it
		session_write_close();

		// Tell PHP not to mess with cookies itself
		ini_set( 'session.use_cookies', 0 );
		ini_set( 'session.use_trans_sid', 0 );

		// Also set a sane serialization handler
		// @codeCoverageIgnoreStart
		MediaWiki\suppressWarnings();
		ini_set( 'session.serialize_handler', 'php_serialize' );
		if ( ini_get( 'session.serialize_handler' ) !== 'php_serialize' ) {
			ini_set( 'session.serialize_handler', 'php' );
			if ( ini_get( 'session.serialize_handler' ) !== 'php' ) {
				MediaWiki\restoreWarnings();
				throw new DomainException(
					'Cannot set session.serialize_handler to "php_serialize" or "php"'
				);
			}
		}
		MediaWiki\restoreWarnings();
		// @codeCoverageIgnoreEnd

		session_set_save_handler(
			array( self::$instance, 'open' ),
			array( self::$instance, 'close' ),
			array( self::$instance, 'read' ),
			array( self::$instance, 'write' ),
			array( self::$instance, 'destroy' ),
			array( self::$instance, 'gc' )
		);

		// It's necessary to register a shutdown function to call session_write_close(),
		// because by the time the request shutdown function for the session module is
		// called, other needed objects may have already been destroyed. Shutdown functions
		// registered this way are called before object destruction.
		register_shutdown_function( array( self::$instance, 'handleShutdown' ) );
	}

	/**
	 * Set the manager, store, and logger
	 * @param MWSessionManager $manager
	 * @param BagOStuff $store
	 * @param LoggerInterface $store
	 */
	public function setManager(
		MWSessionManager $manager, BagOStuff $store, LoggerInterface $logger
	) {
		if ( $this->manager !== $manager ) {
			// Close any existing session before we change stores
			if ( $this->manager ) {
				session_write_close();
			}
			$this->manager = $manager;
			$this->store = $store;
			$this->logger = $logger;
		}
	}

	/**
	 * Initialize the session (handler)
	 * @param string $save_path Path used to store session files (ignored)
	 * @param string $session_name Session name (ignored)
	 * @return bool Success
	 */
	public function open( $save_path, $session_name ) {
		return true;
	}

	/**
	 * Close the session (handler)
	 * @return bool Success
	 */
	public function close() {
		$this->sessionFieldCache = array();
		return true;
	}

	/**
	 * Read session data
	 * @param string $id Session id
	 * @return mixed Session data
	 */
	public function read( $id ) {
		$session = $this->manager->getSessionById( $id, null, true );
		if ( !$session ) {
			return '';
		}
		$session->persist();
		$session->renew();

		// We need to turn the session data array into whatever format PHP is
		// expecting.
		switch ( ini_get( 'session.serialize_handler' ) ) {
			case 'php':
				$data = array();
				$this->sessionFieldCache[$id] = array();
				foreach ( $session as $key => $value ) {
					// @codeCoverageIgnoreStart
					if ( $key === (string)intval( $key ) ) {
						$this->logger->debug( __METHOD__ . ': Ignoring unsupported integer key' );
						continue;
					}
					if ( strcspn( $key, '|!' ) !== strlen( $key ) ) {
						$this->logger->debug(
							__METHOD__ . ': Ignoring key with unsupported characters'
						);
						continue;
					}
					// @codeCoverageIgnoreEnd
					$v = serialize( $value );
					$data[] = "$key|$v";
					$this->sessionFieldCache[$id][$key] = $value;
				}
				$ret = join( '', $data );
				break;

			case 'php_serialize':
				$data = iterator_to_array( $session );
				$this->sessionFieldCache[$id] = $data;
				$ret = serialize( $data );
				break;

				// @codeCoverageIgnoreStart
			default:
				throw new DomainException(
					'Unsupported serialize handler ' . ini_get( 'session.serialize_handler' )
				);
				// @codeCoverageIgnoreEnd
		}

		return $ret;
	}

	/**
	 * Write session data
	 * @param string $id Session id
	 * @param string $dataStr Session data
	 * @return bool Success
	 */
	public function write( $id, $dataStr ) {
		$session = $this->manager->getSessionById( $id );
		$session->persist();

		// First, decode the string PHP handed us
		switch ( ini_get( 'session.serialize_handler' ) ) {
			case 'php':
				$data = array();
				$pos = 0;
				$len = strlen( $dataStr );
				while ( $pos < $len ) {
					// Get key
					$i = strpos( $dataStr, '|', $pos );
					if ( $i === false ) {
						// @codeCoverageIgnoreStart
						$this->logger->debug( __METHOD__ . ': Failed to find delimiter' );
						return false;
						// @codeCoverageIgnoreEnd
					}
					$key = substr( $dataStr, $pos, $i - $pos );
					$pos = $i + 1;

					// "undefined" marker
					if ( substr( $dataStr, $pos, 1 ) === '!' ) {
						// @codeCoverageIgnoreStart
						$pos++;
						continue;
						// @codeCoverageIgnoreEnd
					}

					// Thankfully unserialize() ignores any trailing text...
					MediaWiki\suppressWarnings();
					$value = unserialize( substr( $dataStr, $pos ) );
					MediaWiki\restoreWarnings();
					if ( $value === false && substr( $dataStr, $pos, 4 ) !== 'b:0;' ) {
						// @codeCoverageIgnoreStart
						$error = error_get_last();
						$this->logger->error(
							__METHOD__ . ': Value unserialize failed: ' . $error['message'],
							$error
						);
						return false;
						// @codeCoverageIgnoreEnd
					}
					$data[$key] = $value;
					$pos += strlen( serialize( $value ) );
				}
				break;

			case 'php_serialize':
				MediaWiki\suppressWarnings();
				$data = unserialize( $dataStr );
				MediaWiki\restoreWarnings();
				if ( $data === false && substr( $dataStr, 0, 4 ) !== 'b:0;' ) {
					// @codeCoverageIgnoreStart
					$error = error_get_last();
					$this->logger->error(
						__METHOD__ . ': Value unserialize failed: ' . $error['message'],
						$error
					);
					return false;
					// @codeCoverageIgnoreEnd
				}
				break;

				// @codeCoverageIgnoreStart
			default:
				throw new DomainException(
					'Unsupported serialize handler ' . ini_get( 'session.serialize_handler' )
				);
				// @codeCoverageIgnoreEnd
		}

		// Now merge the data into the MWSession object.
		$changed = false;
		$cache = isset( $this->sessionFieldCache[$id] ) ? $this->sessionFieldCache[$id] : array();
		foreach ( $data as $key => $value ) {
			if ( !isset( $cache[$key] ) ) {
				if ( $session->exists( $key ) ) {
					// New in both, so ignore and log
					$this->logger->debug(
						__METHOD__ . ": Key \"$key\" added in both MWSession and \$_SESSION!"
					);
				} else {
					// New in $_SESSION, keep it
					$session[$key] = $value;
					$changed = true;
				}
			} elseif ( $cache[$key] === $value ) {
				// Unchanged in $_SESSION, so ignore it
			} elseif ( !$session->exists( $key ) || $cache[$key] === $session[$key] ) {
				// Unchanged in MWSession, so keep it
				$session[$key] = $value;
				$changed = true;
			} else {
				// Changed in both, so ignore and log
				$this->logger->debug(
					__METHOD__ . ": Key \"$key\" changed in both MWSession and \$_SESSION!"
				);
			}
		}
		// Anything deleted in $_SESSION and unchanged in MWSession should be deleted too
		foreach ( $cache as $key => $value ) {
			if ( !isset( $data[$key] ) && $session->exists( $key ) &&
				$cache[$key] === $session[$key]
			) {
				unset( $session[$key] );
				$changed = true;
			}
		}

		// Save and update cache if anything changed
		if ( $changed ) {
			$session->save();
			$this->sessionFieldCache[$id] = iterator_to_array( $session );
		}

		return true;
	}

	/**
	 * Destroy a session
	 * @param string $id Session id
	 * @return bool Success
	 */
	public function destroy( $id ) {
		$session = $this->manager->getSessionById( $id, null, true );
		if ( $session ) {
			$session->clear();
		}
		return true;
	}

	/**
	 * Execute garbage collection.
	 * @param int $maxlifetime Maximum session life time (ignored)
	 * @return bool Success
	 */
	public function gc( $maxlifetime ) {
		$before = date( 'YmdHis', time() );
		$this->store->deleteObjectsExpiringBefore( $before );
		return true;
	}

	/**
	 * Shutdown function.
	 *
	 * See the comment inside self::install for rationale.
	 * @codeCoverageIgnore
	 */
	public function handleShutdown() {
		session_write_close();
	}

}
