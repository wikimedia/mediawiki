<?php

if ( php_sapi_name() !== 'cli' ) {
	die( "This is not a valid entry point.\n" );
}
error_reporting( E_ALL );

// Run the server...
set_time_limit( 0 );
LockServerDaemon::init(
	getopt( '', array(
		'address:', 'port:', 'authKey:',
		'connTimeout::', 'lockTimeout::', 'maxClients::', 'maxBacklog::', 'maxLocks::',
	) )
)->main();

/**
 * Simple lock server daemon that accepts lock/unlock requests.
 * This should not require MediaWiki setup or PHP files.
 */
class LockServerDaemon {
	/** @var resource */
	protected $sock; // socket to listen/accept on
	/** @var Array */
	protected $shLocks = array(); // (key => session => 1)
	/** @var Array */
	protected $exLocks = array(); // (key => session)
	/** @var Array */
	protected $sessions = array(); // (session => resource)
	/** @var Array */
	protected $deadSessions = array(); // (session => UNIX timestamp)

	/** @var Array */
	protected $sessionIndexSh = array(); // (session => key => 1)
	/** @var Array */
	protected $sessionIndexEx = array(); // (session => key => 1)

	protected $address; // string (IP/hostname)
	protected $port; // integer
	protected $authKey; // string key
	protected $connTimeout; // array ( 'sec' => integer, 'usec' => integer )
	protected $lockTimeout; // integer number of seconds
	protected $maxLocks; // integer
	protected $maxClients; // integer
	protected $maxBacklog; // integer

	protected $startTime; // integer UNIX timestamp
	protected $lockCount = 0; // integer
	protected $ticks = 0; // integer counter

	protected static $instance = null;

	/**
	 * @params $config Array
	 * @return LockServerDaemon
	 */
	public static function init( array $config ) {
		if ( self::$instance ) {
			throw new Exception( 'LockServer already initialized.' );
		}
		foreach ( array( 'address', 'port', 'authKey' ) as $par ) {
			if ( !isset( $config[$par] ) ) {
				die( "Usage: php LockServerDaemon.php " .
					"--address <address> --port <port> --authkey <key> " .
					"[--connTimeout <seconds>] [--lockTimeout <seconds>] " .
					"[--maxLocks <integer>] [--maxClients <integer>] [--maxBacklog <integer>]"
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
		$connTimeout = isset( $config['connTimeout'] )
			? $config['connTimeout']
			: 1.5;
		$this->connTimeout = array(
			'sec'  => floor( $connTimeout ),
			'usec' => floor( ( $connTimeout - floor( $connTimeout ) ) * 1e6 )
		);
		$this->lockTimeout = isset( $config['lockTimeout'] )
			? $config['lockTimeout']
			: 60;
		$this->maxLocks = isset( $config['maxLocks'] )
			? $config['maxLocks']
			: 5000;
		$this->maxClients = isset( $config['maxClients'] )
			? $config['maxClients']
			: 1000; // less than default FD_SETSIZE
		$this->maxBacklog = isset( $config['maxBacklog'] )
			? $config['maxBacklog']
			: 10;
	}

	/**
	 * @return void
	 */
	protected function setupSocket() {
		if ( !function_exists( 'socket_create' ) ) {
			throw new Exception( "PHP sockets extension missing from PHP CLI mode." );
		}
		$sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		if ( $sock === false ) {
			throw new Exception( "socket_create(): " . socket_strerror( socket_last_error() ) );
		}
		socket_set_option( $sock, SOL_SOCKET, SO_REUSEADDR, 1 ); // bypass 2MLS
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
	 * @return void
	 */
	public function main() {
		// Setup socket and start listing
		$this->setupSocket();
		// Create a list of all the clients that will be connected to us.
		$clients = array( $this->sock ); // start off with listening socket
		do {
			// Create a copy, so $clients doesn't get modified by socket_select()
			$read = $clients; // clients-with-data
			// Get a list of all the clients that have data to be read from
			$changed = socket_select( $read, $write = NULL, $except = NULL, NULL );
			if ( $changed === false ) {
				trigger_error( 'socket_listen(): ' . socket_strerror( socket_last_error() ) );
				continue;
			} elseif ( $changed < 1 ) {
				continue; // wait
			}
			// Check if there is a client trying to connect...
			if ( in_array( $this->sock, $read ) && count( $clients ) < $this->maxClients ) {
				// Accept the new client...
				$newsock = socket_accept( $this->sock );
				socket_set_option( $newsock, SOL_SOCKET, SO_RCVTIMEO, $this->connTimeout );
				socket_set_option( $newsock, SOL_SOCKET, SO_SNDTIMEO, $this->connTimeout );
				$clients[] = $newsock;
				// Remove the listening socket from the clients-with-data array...
				$key = array_search( $this->sock, $read );
				unset( $read[$key] );
			}
			// Loop through all the clients that have data to read...
			foreach ( $read as $read_sock ) {
				// Read until newline or 65535 bytes are recieved.
				// socket_read show errors when the client is disconnected.
				$data = @socket_read( $read_sock, 65535, PHP_NORMAL_READ );
				// Check if the client is disconnected
				if ( $data === false ) {
					// Remove client from $clients list
					$key = array_search( $read_sock, $clients );
					unset( $clients[$key] );
					// Remove socket's session from tracking (if it exists)
					$session = array_search( $read_sock, $this->sessions );
					if ( $session !== false ) {
						unset( $this->sessions[$session] );
						// Record recently killed sessions that still have locks
						if ( isset( $this->sessionIndexSh[$session] )
							|| isset( $this->sessionIndexEx[$session] ) )
						{
							$this->deadSessions[$session] = time();
						}
					}
				} else {
					// Perform the requested command...
					$response = $this->doCommand( trim( $data ), $read_sock );
					// Send the response to the client...
					if ( socket_write( $read_sock, "$response\n" ) === false ) {
						trigger_error( 'socket_write(): ' .
							socket_strerror( socket_last_error( $read_sock ) ) );
					}
				}
			}
			// Prune dead locks every 10 socket events...
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
		}
		if ( $function === 'ACQUIRE' ) {
			return $this->lock( $session, $type, $resources );
		} elseif ( $function === 'RELEASE' ) {
			return $this->unlock( $session, $type, $resources );
		} elseif ( $function === 'RELEASE_ALL' ) {
			return $this->release( $session );
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
			if ( sha1( $session . $command . $type . $values . $this->authKey ) !== $key ) {
				return 'BAD_KEY';
			} elseif ( strlen( $session ) !== 31 ) {
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
	 * @param $session string
	 * @param $type string
	 * @param $keys Array
	 * @return string
	 */
	protected function lock( $session, $type, $keys ) {
		if ( $this->lockCount >= $this->maxLocks ) {
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
	protected function unlock( $session, $type, $keys ) {
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
	protected function release( $session ) {
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
	 * @return string
	 */
	protected function stat() {
		return ( time() - $this->startTime ) . ':' . memory_get_usage();
	}

	/**
	 * Clear locks for sessions that have been dead for a while
	 *
	 * @return void
	 */
	protected function purgeExpiredLocks() {
		$now = time();
		foreach ( $this->deadSessions as $session => $timestamp ) {
			if ( ( $now - $timestamp ) > $this->lockTimeout ) {
				$this->release( $session );
				unset( $this->deadSessions[$session] );
			}
		}
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
