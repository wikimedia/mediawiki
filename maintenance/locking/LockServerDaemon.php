<?php
/**
 * Simple lock server daemon that accepts lock/unlock requests.
 *
 * This code should not require MediaWiki setup or PHP files.
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
 * @ingroup LockManager Maintenance
 */

if ( PHP_SAPI !== 'cli' ) {
	die( "This is not a valid entry point.\n" );
}
error_reporting( E_ALL );

// Run the server...
set_time_limit( 0 );
LockServerDaemon::init(
	getopt( '', array(
		'address:', 'port:', 'authKey:',
		'lockTimeout::', 'maxClients::', 'maxBacklog::', 'maxLocks::',
	) )
)->main();

/**
 * Simple lock server daemon that accepts lock/unlock requests
 *
 * @ingroup LockManager Maintenance
 */
class LockServerDaemon {
	/** @var resource */
	protected $sock; // socket to listen/accept on
	/** @var Array */
	protected $sessions = array(); // (session => resource)
	/** @var Array */
	protected $deadSessions = array(); // (session => UNIX timestamp)

	/** @var LockHolder */
	protected $lockHolder;

	protected $address; // string IP address
	protected $port; // integer
	protected $authKey; // string key
	protected $lockTimeout; // integer number of seconds
	protected $maxBacklog; // integer
	protected $maxClients; // integer

	protected $startTime; // integer UNIX timestamp
	protected $ticks = 0; // integer counter

	/* @var LockServerDaemon */
	protected static $instance = null;

	/**
	 * @params $config Array
	 * @param array $config
	 * @throws Exception
	 * @return LockServerDaemon
	 */
	public static function init( array $config ) {
		if ( self::$instance ) {
			throw new Exception( 'LockServer already initialized.' );
		}
		foreach ( array( 'address', 'port', 'authKey' ) as $par ) {
			if ( !isset( $config[$par] ) ) {
				die( "Usage: php LockServerDaemon.php " .
					"--address <address> --port <port> --authKey <key> " .
					"[--lockTimeout <seconds>] " .
					"[--maxLocks <integer>] [--maxClients <integer>] [--maxBacklog <integer>]\n"
				);
			}
		}
		self::$instance = new self( $config );
		return self::$instance;
	}

	/**
	 * @params $config Array
	 */
	protected function __construct( array $config ) {
		// Required parameters...
		$this->address = $config['address'];
		$this->port = $config['port'];
		$this->authKey = $config['authKey'];
		// Parameters with defaults...
		$this->lockTimeout = isset( $config['lockTimeout'] )
			? (int)$config['lockTimeout']
			: 60;
		$this->maxClients = isset( $config['maxClients'] )
			? (int)$config['maxClients']
			: 1000; // less than default FD_SETSIZE
		$this->maxBacklog = isset( $config['maxBacklog'] )
			? (int)$config['maxBacklog']
			: 100;
		$maxLocks = isset( $config['maxLocks'] )
			? (int)$config['maxLocks']
			: 10000;

		$this->lockHolder = new LockHolder( $maxLocks );
	}

	/**
	 * @throws Exception
	 * @return void
	 */
	protected function setupServerSocket() {
		if ( !function_exists( 'socket_create' ) ) {
			throw new Exception( "PHP sockets extension missing from PHP CLI mode." );
		}
		$sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		if ( $sock === false ) {
			throw new Exception( "socket_create(): " . socket_strerror( socket_last_error() ) );
		}
		socket_set_option( $sock, SOL_SOCKET, SO_REUSEADDR, 1 ); // bypass 2MLS
		socket_set_nonblock( $sock ); // don't block on accept()
		if ( socket_bind( $sock, $this->address, $this->port ) === false ) {
			throw new Exception( "socket_bind(): " .
				socket_strerror( socket_last_error( $sock ) ) );
		} elseif ( socket_listen( $sock, $this->maxBacklog ) === false ) {
			throw new Exception( "socket_listen(): " .
				socket_strerror( socket_last_error( $sock ) ) );
		}
		$this->sock = $sock;
		$this->startTime = time();
	}

	/**
	 * Entry-point function that listens to the server socket, accepts
	 * new clients, and recieves/responds to requests to lock resources.
	 */
	public function main() {
		$this->setupServerSocket(); // setup listening socket
		$socketArray = new SocketArray(); // sockets being serviced
		$socketArray->addSocket( $this->sock ); // add listening socket
		do {
			list( $read, $write ) = $socketArray->socketsForSelect();
			if ( socket_select( $read, $write, $except = NULL, NULL ) < 1 ) {
				continue; // wait
			}
			// Check if there is a client trying to connect...
			if ( in_array( $this->sock, $read ) && $socketArray->size() < $this->maxClients ) {
				$newSock = socket_accept( $this->sock );
				if ( $newSock ) {
					socket_set_option( $newSock, SOL_SOCKET, SO_KEEPALIVE, 1 );
					socket_set_nonblock( $newSock ); // don't block on read()/write()
					$socketArray->addSocket( $newSock );
				}
			}
			// Loop through all the clients that have data to read...
			foreach ( $read as $read_sock ) {
				if ( $read_sock === $this->sock ) {
					continue; // skip listening socket
				}
				// Avoids PHP_NORMAL_READ per https://bugs.php.net/bug.php?id=33471
				$data = socket_read( $read_sock, 65535 );
				// Check if the client is disconnected
				if ( $data === false || $data === '' ) {
					$socketArray->closeSocket( $read_sock );
					$this->recordDeadSocket( $read_sock ); // remove session
				// Check if we reached the end of a message
				} elseif ( substr( $data, -1 ) === "\n" ) {
					// Newline is the last char (given ping-pong message usage)
					$cmd = $socketArray->readRcvBuffer( $read_sock ) . $data;
					// Perform the requested command...
					$response = $this->doCommand( rtrim( $cmd ), $read_sock );
					// Send the response to the client...
					$socketArray->appendSndBuffer( $read_sock, $response . "\n" );
				// Otherwise, we just have more message data to append
				} elseif ( !$socketArray->appendRcvBuffer( $read_sock, $data ) ) {
					$socketArray->closeSocket( $read_sock ); // too big
					$this->recordDeadSocket( $read_sock ); // remove session
				}
			}
			// Loop through all the clients that have data to write...
			foreach ( $write as $write_sock ) {
				$bytes = socket_write( $write_sock, $socketArray->readSndBuffer( $write_sock ) );
				// Check if the client is disconnected
				if ( $bytes === false ) {
					$socketArray->closeSocket( $write_sock );
					$this->recordDeadSocket( $write_sock ); // remove session
				// Otherwise, truncate these bytes from the start of the write buffer
				} else {
					$socketArray->consumeSndBuffer( $write_sock, $bytes );
				}
			}
			// Prune dead locks every few socket events...
			if ( ++$this->ticks >= 9 ) {
				$this->ticks = 0;
				$this->purgeExpiredLocks();
			}
		} while ( true );
	}

	/**
	 * @param $data string
	 * @param $sourceSock resource
	 * @return string
	 */
	protected function doCommand( $data, $sourceSock ) {
		$cmdArr = $this->getCommand( $data );
		if ( is_string( $cmdArr ) ) {
			return $cmdArr; // error
		}
		list( $function, $session, $type, $resources ) = $cmdArr;
		// On first command, track the session => sock correspondence
		if ( !isset( $this->sessions[$session] ) ) {
			$this->sessions[$session] = $sourceSock;
			unset( $this->deadSessions[$session] ); // renew if dead
		}
		if ( $function === 'ACQUIRE' ) {
			return $this->lockHolder->lock( $session, $type, $resources );
		} elseif ( $function === 'RELEASE' ) {
			return $this->lockHolder->unlock( $session, $type, $resources );
		} elseif ( $function === 'RELEASE_ALL' ) {
			return $this->lockHolder->release( $session );
		} elseif ( $function === 'STAT' ) {
			return $this->stat();
		}
		return 'INTERNAL_ERROR';
	}

	/**
	 * @param $data string
	 * @return Array
	 */
	protected function getCommand( $data ) {
		$m = explode( ':', $data ); // <session, key, command, type, values>
		if ( count( $m ) == 5 ) {
			list( $session, $key, $command, $type, $values ) = $m;
			$goodKey = hash_hmac( 'sha1',
				"{$session}\n{$command}\n{$type}\n{$values}", $this->authKey );
			if ( $goodKey !== $key ) {
				return 'BAD_KEY';
			} elseif ( strlen( $session ) !== 32 ) {
				return 'BAD_SESSION';
			}
			$values = explode( '|', $values );
			if ( $command === 'ACQUIRE' ) {
				$needsLockArgs = true;
			} elseif ( $command === 'RELEASE' ) {
				$needsLockArgs = true;
			} elseif ( $command === 'RELEASE_ALL' ) {
				$needsLockArgs = false;
			} elseif ( $command === 'STAT' ) {
				$needsLockArgs = false;
			} else {
				return 'BAD_COMMAND';
			}
			if ( $needsLockArgs ) {
				if ( $type !== 'SH' && $type !== 'EX' ) {
					return 'BAD_TYPE';
				}
				foreach ( $values as $value ) {
					if ( strlen( $value ) !== 31 ) {
						return 'BAD_FORMAT';
					}
				}
			}
			return array( $command, $session, $type, $values );
		}
		return 'BAD_FORMAT';
	}

	/**
	 * Remove a socket's corresponding session from tracking and
	 * store it in the dead session tracking if it still has locks.
	 *
	 * @param $socket resource
	 * @return bool
	 */
	protected function recordDeadSocket( $socket ) {
		$session = array_search( $socket, $this->sessions );
		if ( $session !== false ) {
			unset( $this->sessions[$session] );
			// Record recently killed sessions that still have locks
			if ( $this->lockHolder->sessionHasLocks( $session ) ) {
				$this->deadSessions[$session] = time();
			}
			return true;
		}
		return false;
	}

	/**
	 * Clear locks for sessions that have been dead for a while
	 *
	 * @return integer Number of sessions purged
	 */
	protected function purgeExpiredLocks() {
		$count = 0;
		$now = time();
		foreach ( $this->deadSessions as $session => $timestamp ) {
			if ( ( $now - $timestamp ) > $this->lockTimeout ) {
				$this->lockHolder->release( $session );
				unset( $this->deadSessions[$session] );
				++$count;
			}
		}
		return $count;
	}

	/**
	 * Get the current timestamp and memory usage
	 *
	 * @return string
	 */
	protected function stat() {
		return ( time() - $this->startTime ) . ':' . memory_get_usage();
	}
}

/**
 * LockServerDaemon helper class that keeps track socket states
 */
class SocketArray {
	/* @var Array */
	protected $clients = array(); // array of client sockets
	/* @var Array */
	protected $rBuffers = array(); // corresponding socket read buffers
	/* @var Array */
	protected $wBuffers = array(); // corresponding socket write buffers

	const BUFFER_SIZE = 65535;

	/**
	 * @return Array (list of sockets to read, list of sockets to write)
	 */
	public function socketsForSelect() {
		$rSockets = array();
		$wSockets = array();
		foreach ( $this->clients as $key => $socket ) {
			if ( $this->wBuffers[$key] !== '' ) {
				$wSockets[] = $socket; // wait for writing to unblock
			} else {
				$rSockets[] = $socket; // wait for reading to unblock
			}
		}
		return array( $rSockets, $wSockets );
	}

	/**
	 * @return integer Number of client sockets
	 */
	public function size() {
		return count( $this->clients );
	}

	/**
	 * @param $sock resource
	 * @return bool
	 */
	public function addSocket( $sock ) {
		$this->clients[] = $sock;
		$this->rBuffers[] = '';
		$this->wBuffers[] = '';
		return true;
	}

	/**
	 * @param $sock resource
	 * @return bool
	 */
	public function closeSocket( $sock ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		}
		socket_close( $sock );
		unset( $this->clients[$key] );
		unset( $this->rBuffers[$key] );
		unset( $this->wBuffers[$key] );
		return true;
	}

	/**
	 * @param $sock resource
	 * @param $data string
	 * @return bool
	 */
	public function appendRcvBuffer( $sock, $data ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		} elseif ( ( strlen( $this->rBuffers[$key] ) + strlen( $data ) ) > self::BUFFER_SIZE ) {
			return false;
		}
		$this->rBuffers[$key] .= $data;
		return true;
	}

	/**
	 * @param $sock resource
	 * @return string|bool
	 */
	public function readRcvBuffer( $sock ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		}
		$data = $this->rBuffers[$key];
		$this->rBuffers[$key] = ''; // consume data
		return $data;
	}

	/**
	 * @param $sock resource
	 * @param $data string
	 * @return bool
	 */
	public function appendSndBuffer( $sock, $data ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		} elseif ( ( strlen( $this->wBuffers[$key] ) + strlen( $data ) ) > self::BUFFER_SIZE ) {
			return false;
		}
		$this->wBuffers[$key] .= $data;
		return true;
	}

	/**
	 * @param $sock resource
	 * @return bool
	 */
	public function readSndBuffer( $sock ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		}
		return $this->wBuffers[$key];
	}

	/**
	 * @param $sock resource
	 * @param $bytes integer
	 * @return bool
	 */
	public function consumeSndBuffer( $sock, $bytes ) {
		$key = array_search( $sock, $this->clients );
		if ( $key === false ) {
			return false;
		}
		$this->wBuffers[$key] = (string)substr( $this->wBuffers[$key], $bytes );
		return true;
	}
}

/**
 * LockServerDaemon helper class that keeps track of the locks
 */
class LockHolder {
	/** @var Array */
	protected $shLocks = array(); // (key => session => 1)
	/** @var Array */
	protected $exLocks = array(); // (key => session)

	/** @var Array */
	protected $sessionIndexSh = array(); // (session => key => 1)
	/** @var Array */
	protected $sessionIndexEx = array(); // (session => key => 1)
	protected $lockCount = 0; // integer

	protected $maxLocks; // integer

	/**
	 * @params $maxLocks integer Maximum number of locks to allow
	 */
	public function __construct( $maxLocks ) {
		$this->maxLocks = $maxLocks;
	}

	/**
	 * @param $session string
	 * @return bool
	 */
	public function sessionHasLocks( $session ) {
		return isset( $this->sessionIndexSh[$session] )
			|| isset( $this->sessionIndexEx[$session] );
	}

	/**
	 * @param $session string
	 * @param $type string
	 * @param $keys Array
	 * @return string
	 */
	public function lock( $session, $type, array $keys ) {
		if ( ( $this->lockCount + count( $keys ) ) > $this->maxLocks ) {
			return 'TOO_MANY_LOCKS';
		}
		if ( $type === 'SH' ) {
			// Check if any keys are already write-locked...
			foreach ( $keys as $key ) {
				if ( isset( $this->exLocks[$key] ) && $this->exLocks[$key] !== $session ) {
					return 'CANT_ACQUIRE';
				}
			}
			// Acquire the read-locks...
			foreach ( $keys as $key ) {
				$this->set_sh_lock( $key, $session );
			}
			return 'ACQUIRED';
		} elseif ( $type === 'EX' ) {
			// Check if any keys are already read-locked or write-locked...
			foreach ( $keys as $key ) {
				if ( isset( $this->exLocks[$key] ) && $this->exLocks[$key] !== $session ) {
					return 'CANT_ACQUIRE';
				}
				if ( isset( $this->shLocks[$key] ) ) {
					foreach ( $this->shLocks[$key] as $otherSession => $x ) {
						if ( $otherSession !== $session ) {
							return 'CANT_ACQUIRE';
						}
					}
				}
			}
			// Acquire the write-locks...
			foreach ( $keys as $key ) {
				$this->set_ex_lock( $key, $session );
			}
			return 'ACQUIRED';
		}
		return 'INTERNAL_ERROR';
	}

	/**
	 * @param $session string
	 * @param $type string
	 * @param $keys Array
	 * @return string
	 */
	public function unlock( $session, $type, array $keys ) {
		if ( $type === 'SH' ) {
			foreach ( $keys as $key ) {
				$this->unset_sh_lock( $key, $session );
			}
			return 'RELEASED';
		} elseif ( $type === 'EX' ) {
			foreach ( $keys as $key ) {
				$this->unset_ex_lock( $key, $session );
			}
			return 'RELEASED';
		}
		return 'INTERNAL_ERROR';
	}

	/**
	 * @param $session string
	 * @return string
	 */
	public function release( $session ) {
		if ( isset( $this->sessionIndexSh[$session] ) ) {
			foreach ( $this->sessionIndexSh[$session] as $key => $x ) {
				$this->unset_sh_lock( $key, $session );
			}
		}
		if ( isset( $this->sessionIndexEx[$session] ) ) {
			foreach ( $this->sessionIndexEx[$session] as $key => $x ) {
				$this->unset_ex_lock( $key, $session );
			}
		}
		return 'RELEASED_ALL';
	}

	/**
	 * @param $key string
	 * @param $session string
	 * @return void
	 */
	protected function set_sh_lock( $key, $session ) {
		if ( !isset( $this->shLocks[$key][$session] ) ) {
			$this->shLocks[$key][$session] = 1;
			$this->sessionIndexSh[$session][$key] = 1;
			++$this->lockCount; // we are adding a lock
		}
	}

	/**
	 * @param $key string
	 * @param $session string
	 * @return void
	 */
	protected function set_ex_lock( $key, $session ) {
		if ( !isset( $this->exLocks[$key][$session] ) ) {
			$this->exLocks[$key] = $session;
			$this->sessionIndexEx[$session][$key] = 1;
			++$this->lockCount; // we are adding a lock
		}
	}

	/**
	 * @param $key string
	 * @param $session string
	 * @return void
	 */
	protected function unset_sh_lock( $key, $session ) {
		if ( isset( $this->shLocks[$key][$session] ) ) {
			unset( $this->shLocks[$key][$session] );
			if ( !count( $this->shLocks[$key] ) ) {
				unset( $this->shLocks[$key] );
			}
			unset( $this->sessionIndexSh[$session][$key] );
			if ( !count( $this->sessionIndexSh[$session] ) ) {
				unset( $this->sessionIndexSh[$session] );
			}
			--$this->lockCount;
		}
	}

	/**
	 * @param $key string
	 * @param $session string
	 * @return void
	 */
	protected function unset_ex_lock( $key, $session ) {
		if ( isset( $this->exLocks[$key] ) && $this->exLocks[$key] === $session ) {
			unset( $this->exLocks[$key] );
			unset( $this->sessionIndexEx[$session][$key] );
			if ( !count( $this->sessionIndexEx[$session] ) ) {
				unset( $this->sessionIndexEx[$session] );
			}
			--$this->lockCount;
		}
	}
}
