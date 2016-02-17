<?php
/**
 * Squid and Varnish cache purging.
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
 */

/**
 * An HTTP 1.0 client built for the purposes of purging Squid and Varnish.
 * Uses asynchronous I/O, allowing purges to be done in a highly parallel
 * manner.
 *
 * Could be replaced by curl_multi_exec() or some such.
 */
class SquidPurgeClient {
	/** @var string */
	protected $host;

	/** @var int */
	protected $port;

	/** @var string|bool */
	protected $ip;

	/** @var string */
	protected $readState = 'idle';

	/** @var string */
	protected $writeBuffer = '';

	/** @var array */
	protected $requests = [];

	/** @var mixed */
	protected $currentRequestIndex;

	const EINTR = 4;
	const EAGAIN = 11;
	const EINPROGRESS = 115;
	const BUFFER_SIZE = 8192;

	/**
	 * @var resource|null The socket resource, or null for unconnected, or false
	 *   for disabled due to error.
	 */
	protected $socket;

	/** @var string */
	protected $readBuffer;

	/** @var int */
	protected $bodyRemaining;

	/**
	 * @param string $server
	 * @param array $options
	 */
	public function __construct( $server, $options = [] ) {
		$parts = explode( ':', $server, 2 );
		$this->host = $parts[0];
		$this->port = isset( $parts[1] ) ? $parts[1] : 80;
	}

	/**
	 * Open a socket if there isn't one open already, return it.
	 * Returns false on error.
	 *
	 * @return bool|resource
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
		MediaWiki\suppressWarnings();
		$ok = socket_connect( $this->socket, $ip, $this->port );
		MediaWiki\restoreWarnings();
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
	 * @return array
	 */
	public function getReadSocketsForSelect() {
		if ( $this->readState == 'idle' ) {
			return [];
		}
		$socket = $this->getSocket();
		if ( $socket === false ) {
			return [];
		}
		return [ $socket ];
	}

	/**
	 * Get write socket array for select()
	 * @return array
	 */
	public function getWriteSocketsForSelect() {
		if ( !strlen( $this->writeBuffer ) ) {
			return [];
		}
		$socket = $this->getSocket();
		if ( $socket === false ) {
			return [];
		}
		return [ $socket ];
	}

	/**
	 * Get the host's IP address.
	 * Does not support IPv6 at present due to the lack of a convenient interface in PHP.
	 * @throws MWException
	 * @return string
	 */
	protected function getIP() {
		if ( $this->ip === null ) {
			if ( IP::isIPv4( $this->host ) ) {
				$this->ip = $this->host;
			} elseif ( IP::isIPv6( $this->host ) ) {
				throw new MWException( '$wgSquidServers does not support IPv6' );
			} else {
				MediaWiki\suppressWarnings();
				$this->ip = gethostbyname( $this->host );
				if ( $this->ip === $this->host ) {
					$this->ip = false;
				}
				MediaWiki\restoreWarnings();
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
			MediaWiki\suppressWarnings();
			socket_set_block( $this->socket );
			socket_shutdown( $this->socket );
			socket_close( $this->socket );
			MediaWiki\restoreWarnings();
		}
		$this->socket = null;
		$this->readBuffer = '';
		// Write buffer is kept since it may contain a request for the next socket
	}

	/**
	 * Queue a purge operation
	 *
	 * @param string $url
	 */
	public function queuePurge( $url ) {
		global $wgSquidPurgeUseHostHeader;
		$url = CdnCacheUpdate::expand( str_replace( "\n", '', $url ) );
		$request = [];
		if ( $wgSquidPurgeUseHostHeader ) {
			$url = wfParseUrl( $url );
			$host = $url['host'];
			if ( isset( $url['port'] ) && strlen( $url['port'] ) > 0 ) {
				$host .= ":" . $url['port'];
			}
			$path = $url['path'];
			if ( isset( $url['query'] ) && is_string( $url['query'] ) ) {
				$path = wfAppendQuery( $path, $url['query'] );
			}
			$request[] = "PURGE $path HTTP/1.1";
			$request[] = "Host: $host";
		} else {
			$request[] = "PURGE $url HTTP/1.0";
		}
		$request[] = "Connection: Keep-Alive";
		$request[] = "Proxy-Connection: Keep-Alive";
		$request[] = "User-Agent: " . Http::userAgent() . ' ' . __CLASS__;
		// Two ''s to create \r\n\r\n
		$request[] = '';
		$request[] = '';

		$this->requests[] = implode( "\r\n", $request );
		if ( $this->currentRequestIndex === null ) {
			$this->nextRequest();
		}
	}

	/**
	 * @return bool
	 */
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
		MediaWiki\suppressWarnings();
		$bytesSent = socket_send( $socket, $buf, strlen( $buf ), $flags );
		MediaWiki\restoreWarnings();

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
		MediaWiki\suppressWarnings();
		$bytesRead = socket_recv( $socket, $buf, self::BUFFER_SIZE, 0 );
		MediaWiki\restoreWarnings();
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

	/**
	 * @throws MWException
	 * @return string
	 */
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
			if ( $this->readState == 'status' ) {
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
			throw new MWException( __METHOD__ . ': unexpected state' );
		}
	}

	/**
	 * @param string $line
	 */
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

	/**
	 * @param string $line
	 */
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

	/**
	 * @param string $msg
	 */
	protected function log( $msg ) {
		wfDebugLog( 'squid', __CLASS__ . " ($this->host): $msg" );
	}
}
