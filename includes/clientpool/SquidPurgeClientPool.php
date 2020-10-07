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
 * SquidPurgeClient helper class
 *
 * @deprecated Since 1.35 Use MultiHttpClient
 */
class SquidPurgeClientPool {
	/** @var SquidPurgeClient[] */
	protected $clients = [];

	/** @var int */
	protected $timeout = 5;

	/**
	 * @param array $options
	 */
	public function __construct( $options = [] ) {
		if ( isset( $options['timeout'] ) ) {
			$this->timeout = $options['timeout'];
		}
	}

	/**
	 * @param SquidPurgeClient $client
	 * @return void
	 */
	public function addClient( $client ) {
		$this->clients[] = $client;
	}

	public function run() {
		$done = false;
		$startTime = microtime( true );

		while ( !$done ) {
			$readSockets = $writeSockets = [];
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
			if ( $readSockets === [] && $writeSockets === [] ) {
				break;
			}

			$exceptSockets = null;
			$timeout = min( $startTime + $this->timeout - microtime( true ), 1 );
			Wikimedia\suppressWarnings();
			$numReady = socket_select( $readSockets, $writeSockets, $exceptSockets, $timeout );
			Wikimedia\restoreWarnings();
			if ( $numReady === false ) {
				wfDebugLog( 'squid', __METHOD__ . ': Error in stream_select: ' .
					socket_strerror( socket_last_error() ) );
				break;
			}

			// Check for timeout, use 1% tolerance since we aimed at having socket_select()
			// exit at precisely the overall timeout
			if ( microtime( true ) - $startTime > $this->timeout * 0.99 ) {
				wfDebugLog( 'squid', __CLASS__ . ": timeout ({$this->timeout}s)" );
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
