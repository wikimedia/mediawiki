<?php
/**
 * An HTTP 1.0 client built for the purposes of purging Squid and Varnish. 
 * Uses asynchronous I/O, allowing purges to be done in a highly parallel 
 * manner. 
 *
 * Could be replaced by curl_multi_exec() or some such.
 */
class SquidPurgeClient {
	var $host, $port, $ip;

	var $readState = 'idle';
	var $writeBuffer = '';
	var $requests = array();
	var $currentRequestIndex;

	const EINTR = 4;
	const EAGAIN = 11;
	const EINPROGRESS = 115;
	const BUFFER_SIZE = 8192;

	/**
	 * The socket resource, or null for unconnected, or false for disabled due to error
	 */
	var $socket;
	
	public function __construct( $server, $options = array() ) {
		$parts = explode( ':', $server, 2 );
		$this->host = $parts[0];
		$this->port = isset( $parts[1] ) ? $parts[1] : 80;
	}

	/**
	 * Open a socket if there isn't one open already, return it.
	 * Returns false on error.
	 */
	protected function getSocket() {
		if ( $this->socket !== null ) {
			return $this->socket;
		}

		$ip = $this->getIP();
		if ( !$ip ) {
			$this->log( "DNS error" );
			$this->markDown();
			return false;
		}
		$this->socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		socket_set_nonblock( $this->socket );
		wfSuppressWarnings();
		$ok = socket_connect( $this->socket, $ip, $this->port );
		wfRestoreWarnings();
		if ( !$ok ) {
			$error = socket_last_error( $this->socket );
			if ( $error !== self::EINPROGRESS ) {
				$this->log( "connection error: " . socket_strerror( $error ) );
				$this->markDown();
				return false;
			}
		}

		return $this->socket;
	}

	/**
	 * Get read socket array for select()
	 */
	public function getReadSocketsForSelect() {
		if ( $this->readState == 'idle' ) {
			return array();
		}
		$socket = $this->getSocket();
		if ( $socket === false ) {
			return array();
		}
		return array( $socket );
	}

	/**
	 * Get write socket array for select()
	 */
	public function getWriteSocketsForSelect() {
		if ( !strlen( $this->writeBuffer ) ) {
			return array();
		}
		$socket = $this->getSocket();
		if ( $socket === false ) {
			return array();
		}
		return array( $socket );
	}

	/** 
	 * Get the host's IP address.
	 * Does not support IPv6 at present due to the lack of a convenient interface in PHP.
	 */
	protected function getIP() {
		if ( $this->ip === null ) {
			if ( IP::isIPv4( $this->host ) ) {
				$this->ip = $this->host;
			} elseif ( IP::isIPv6( $this->host ) ) {
				throw new MWException( '$wgSquidServers does not support IPv6' );
			} else {
				wfSuppressWarnings();
				$this->ip = gethostbyname( $this->host );
				if ( $this->ip === $this->host ) {
					$this->ip = false;
				}
				wfRestoreWarnings();
			}
		}
		return $this->ip;
	}

	/**
	 * Close the socket and ignore any future purge requests.
	 * This is called if there is a protocol error.
	 */
	protected function markDown() {
		$this->close();
		$this->socket = false;
	}

	/**
	 * Close the socket but allow it to be reopened for future purge requests
	 */
	public function close() {
		if ( $this->socket ) {
			wfSuppressWarnings();
			socket_set_block( $this->socket );
			socket_shutdown( $this->socket );
			socket_close( $this->socket );
			wfRestoreWarnings();
		}
		$this->socket = null;
		$this->readBuffer = '';
		// Write buffer is kept since it may contain a request for the next socket
	}

	/**
	 * Queue a purge operation
	 */
	public function queuePurge( $url ) {
		$url = str_replace( "\n", '', $url );
		$this->requests[] = "PURGE $url HTTP/1.0\r\n" .
			"Connection: Keep-Alive\r\n" .
			"Proxy-Connection: Keep-Alive\r\n" .
			"User-Agent: " . Http::userAgent() . ' ' . __CLASS__ . "\r\n\r\n";
		if ( $this->currentRequestIndex === null ) {
			$this->nextRequest();
		}
	}

	public function isIdle() {
		return strlen( $this->writeBuffer ) == 0 && $this->readState == 'idle';
	}

	/**
	 * Perform pending writes. Call this when socket_select() indicates that writing will not block.
	 */
	public function doWrites() {
		if ( !strlen( $this->writeBuffer ) ) {
			return;
		}
		$socket = $this->getSocket();
		if ( !$socket ) {
			return;
		}

		if ( strlen( $this->writeBuffer ) <= self::BUFFER_SIZE ) {
			$buf = $this->writeBuffer;
			$flags = MSG_EOR;
		} else {
			$buf = substr( $this->writeBuffer, 0, self::BUFFER_SIZE );
			$flags = 0;
		}
		wfSuppressWarnings();
		$bytesSent = socket_send( $socket, $buf, strlen( $buf ), $flags );
		wfRestoreWarnings();

		if ( $bytesSent === false ) {
			$error = socket_last_error( $socket );
			if ( $error != self::EAGAIN && $error != self::EINTR ) {
				$this->log( 'write error: ' . socket_strerror( $error ) );
				$this->markDown();
			}
			return;
		}

		$this->writeBuffer = substr( $this->writeBuffer, $bytesSent );
	}

	/**
	 * Read some data. Call this when socket_select() indicates that the read buffer is non-empty.
	 */
	public function doReads() {
		$socket = $this->getSocket();
		if ( !$socket ) {
			return;
		}

		$buf = '';
		wfSuppressWarnings();
		$bytesRead = socket_recv( $socket, $buf, self::BUFFER_SIZE, 0 );
		wfRestoreWarnings();
		if ( $bytesRead === false ) {
			$error = socket_last_error( $socket );
			if ( $error != self::EAGAIN && $error != self::EINTR ) {
				$this->log( 'read error: ' . socket_strerror( $error ) );
				$this->markDown();
				return;
			}
		} elseif ( $bytesRead === 0 ) {
			// Assume EOF
			$this->close();
			return;
		}

		$this->readBuffer .= $buf;
		while ( $this->socket && $this->processReadBuffer() === 'continue' );
	}

	protected function processReadBuffer() {
		switch ( $this->readState ) {
		case 'idle':
			return 'done';
		case 'status':
		case 'header':
			$lines = explode( "\r\n", $this->readBuffer, 2 );
			if ( count( $lines ) < 2 ) {
				return 'done';
			}
			if ( $this->readState == 'status' )  {
				$this->processStatusLine( $lines[0] );
			} else { // header
				$this->processHeaderLine( $lines[0] );
			}
			$this->readBuffer = $lines[1];
			return 'continue';
		case 'body':
			if ( $this->bodyRemaining !== null ) {
				if ( $this->bodyRemaining > strlen( $this->readBuffer ) ) {
					$this->bodyRemaining -= strlen( $this->readBuffer );
					$this->readBuffer = '';
					return 'done';
				} else {
					$this->readBuffer = substr( $this->readBuffer, $this->bodyRemaining );
					$this->bodyRemaining = 0;
					$this->nextRequest();
					return 'continue';
				}
			} else {
				// No content length, read all data to EOF
				$this->readBuffer = '';
				return 'done';
			}
		default:
			throw new MWException( __METHOD__.': unexpected state' );
		}
	}

	protected function processStatusLine( $line ) {
		if ( !preg_match( '!^HTTP/(\d+)\.(\d+) (\d{3}) (.*)$!', $line, $m ) ) {
			$this->log( 'invalid status line' );
			$this->markDown();
			return;
		}
		list( , , , $status, $reason ) = $m;
		$status = intval( $status );
		if ( $status !== 200 && $status !== 404 ) {
			$this->log( "unexpected status code: $status $reason" );
			$this->markDown();
			return;
		}
		$this->readState = 'header';
	}

	protected function processHeaderLine( $line ) {
		if ( preg_match( '/^Content-Length: (\d+)$/i', $line, $m ) ) {
			$this->bodyRemaining = intval( $m[1] );
		} elseif ( $line === '' ) {
			$this->readState = 'body';
		}
	}

	protected function nextRequest() {
		if ( $this->currentRequestIndex !== null ) {
			unset( $this->requests[$this->currentRequestIndex] );
		}
		if ( count( $this->requests ) ) {
			$this->readState = 'status';
			$this->currentRequestIndex = key( $this->requests );
			$this->writeBuffer = $this->requests[$this->currentRequestIndex];
		} else {
			$this->readState = 'idle';
			$this->currentRequestIndex = null;
			$this->writeBuffer = '';
		}
		$this->bodyRemaining = null;
	}

	protected function log( $msg ) {
		wfDebugLog( 'squid', __CLASS__." ($this->host): $msg\n" );
	}
}

class SquidPurgeClientPool {

	/**
	 * @var array of SquidPurgeClient
	 */
	var $clients = array();
	var $timeout = 5;

	function __construct( $options = array() ) {
		if ( isset( $options['timeout'] ) ) {
			$this->timeout = $options['timeout'];
		}
	}

	/**
	 * @param $client SquidPurgeClient
	 * @return void
	 */
	public function addClient( $client ) {
		$this->clients[] = $client;
	}

	public function run() {
		$done = false;
		$startTime = microtime( true );
		while ( !$done ) {
			$readSockets = $writeSockets = array();
			foreach ( $this->clients as $clientIndex => $client ) {
				$sockets = $client->getReadSocketsForSelect();
				foreach ( $sockets as $i => $socket ) {
					$readSockets["$clientIndex/$i"] = $socket;
				}
				$sockets = $client->getWriteSocketsForSelect();
				foreach ( $sockets as $i => $socket ) {
					$writeSockets["$clientIndex/$i"] = $socket;
				}
			}
			if ( !count( $readSockets ) && !count( $writeSockets ) ) {
				break;
			}
			$exceptSockets = null;
			$timeout = min( $startTime + $this->timeout - microtime( true ), 1 );
			wfSuppressWarnings();
			$numReady = socket_select( $readSockets, $writeSockets, $exceptSockets, $timeout );
			wfRestoreWarnings();
			if ( $numReady === false ) {
				wfDebugLog( 'squid', __METHOD__.': Error in stream_select: ' . 
					socket_strerror( socket_last_error() ) . "\n" );
				break;
			}
			// Check for timeout, use 1% tolerance since we aimed at having socket_select()
			// exit at precisely the overall timeout
			if ( microtime( true ) - $startTime > $this->timeout * 0.99 ) {
				wfDebugLog( 'squid', __CLASS__.": timeout ({$this->timeout}s)\n" );
				break;
			} elseif ( !$numReady ) {
				continue;
			}

			foreach ( $readSockets as $key => $socket ) {
				list( $clientIndex, ) = explode( '/', $key );
				$client = $this->clients[$clientIndex];
				$client->doReads();
			}
			foreach ( $writeSockets as $key => $socket ) {
				list( $clientIndex, ) = explode( '/', $key );
				$client = $this->clients[$clientIndex];
				$client->doWrites();
			}

			$done = true;
			foreach ( $this->clients as $client ) {
				if ( !$client->isIdle() ) {
					$done = false;
				}
			}
		}
		foreach ( $this->clients as $client ) {
			$client->close();
		}
	}
}
