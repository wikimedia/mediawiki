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

namespace MediaWiki\PoolCounter;

use InvalidArgumentException;
use MediaWiki\Status\Status;
use Wikimedia\IPUtils;

/**
 * Helper for \MediaWiki\PoolCounter\PoolCounterClient.
 *
 * @internal
 * @since 1.16
 */
class PoolCounterConnectionManager {
	/** @var string[] */
	public $hostNames;
	/** @var array */
	public $conns = [];
	/** @var array */
	public $refCounts = [];
	/** @var float */
	public $timeout;
	/** @var int */
	public $connect_timeout;

	/**
	 * @internal Public for testing only
	 */
	public $host;

	/**
	 * @internal Public for testing only
	 */
	public $port;

	/**
	 * @param array $conf
	 */
	public function __construct( $conf ) {
		if ( !count( $conf['servers'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': no servers configured' );
		}
		$this->hostNames = $conf['servers'];
		$this->timeout = $conf['timeout'] ?? 0.1;
		$this->connect_timeout = $conf['connect_timeout'] ?? 0;
	}

	/**
	 * @param string $key
	 * @return Status
	 */
	public function get( $key ) {
		$hashes = [];
		foreach ( $this->hostNames as $hostName ) {
			$hashes[$hostName] = md5( $hostName . $key );
		}
		asort( $hashes );
		$errno = 0;
		$errstr = '';
		$hostName = '';
		$conn = null;
		foreach ( $hashes as $hostName => $hash ) {
			if ( isset( $this->conns[$hostName] ) ) {
				$this->refCounts[$hostName]++;
				return Status::newGood(
					[ 'conn' => $this->conns[$hostName], 'hostName' => $hostName ] );
			}
			$parts = IPUtils::splitHostAndPort( $hostName );
			if ( $parts === false ) {
				$errstr = '\'servers\' config incorrectly configured.';
				return Status::newFatal( 'poolcounter-connection-error', $errstr, $hostName );
			}
			// IPV6 addresses need to be in brackets otherwise it fails.
			$this->host = IPUtils::isValidIPv6( $parts[0] ) ? '[' . $parts[0] . ']' : $parts[0];
			$this->port = $parts[1] ?: 7531;
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$conn = @$this->open( $this->host, $this->port, $errno, $errstr );
			if ( $conn ) {
				break;
			}
		}
		if ( !$conn ) {
			return Status::newFatal( 'poolcounter-connection-error', $errstr, $hostName );
		}
		// TODO: Inject PSR Logger from ServiceWiring
		wfDebug( "Connected to pool counter server: $hostName\n" );
		$this->conns[$hostName] = $conn;
		$this->refCounts[$hostName] = 1;
		return Status::newGood( [ 'conn' => $conn, 'hostName' => $hostName ] );
	}

	/**
	 * Open a socket. Just a wrapper for fsockopen()
	 * @param string $host
	 * @param int $port
	 * @param int &$errno
	 * @param string &$errstr
	 * @return null|resource
	 */
	private function open( $host, $port, &$errno, &$errstr ) {
		// If connect_timeout is set, we try to open the socket twice.
		// You usually want to set the connection timeout to a very
		// small value so that in case of failure of a server the
		// connection to poolcounter is not a SPOF.
		if ( $this->connect_timeout > 0 ) {
			$tries = 2;
			$timeout = $this->connect_timeout;
		} else {
			$tries = 1;
			$timeout = $this->timeout;
		}

		$fp = null;
		while ( true ) {
			$fp = fsockopen( $host, $port, $errno, $errstr, $timeout );
			if ( $fp !== false || --$tries < 1 ) {
				break;
			}
			usleep( 1000 );
		}

		return $fp;
	}

	/**
	 * @param resource $conn
	 */
	public function close( $conn ) {
		foreach ( $this->conns as $hostName => $otherConn ) {
			if ( $conn === $otherConn ) {
				if ( $this->refCounts[$hostName] ) {
					$this->refCounts[$hostName]--;
				}
				if ( !$this->refCounts[$hostName] ) {
					fclose( $conn );
					unset( $this->conns[$hostName] );
				}
			}
		}
	}
}
