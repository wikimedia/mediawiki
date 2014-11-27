<?php
/**
 * Profiler sending messages over UDP.
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
 * @ingroup Profiler
 */

/**
 * ProfilerSimpleUDP class, that sends out messages for 'udpprofile' daemon
 * (see http://git.wikimedia.org/tree/operations%2Fsoftware.git/master/udpprofile)
 *
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputUdp extends ProfilerOutput {
	/** @var int port to send profiling data to */
	private $port = 3811;

	/** @var string host to send profiling data to */
	private $host = '127.0.0.1';

	/** @var string format string for profiling data */
	private $format = "%s - %d %f %f %f %f %s\n";

	public function __construct( Profiler $collector, array $params ) {
		parent::__construct( $collector, $params );
		global $wgUDPProfilerPort, $wgUDPProfilerHost, $wgUDPProfilerFormatString;

		// Initialize port, host, and format from config, back-compat if available
		if ( isset( $this->params['udpport'] ) ) {
			$this->port = $this->params['udpport'];
		} elseif ( $wgUDPProfilerPort ) {
			$this->port = $wgUDPProfilerPort;
		}

		if ( isset( $this->params['udphost'] ) ) {
			$this->host = $this->params['udphost'];
		} elseif ( $wgUDPProfilerHost ) {
			$this->host = $wgUDPProfilerHost;
		}

		if ( isset( $this->params['udpformat'] ) ) {
			$this->format = $this->params['udpformat'];
		} elseif ( $wgUDPProfilerFormatString ) {
			$this->format = $wgUDPProfilerFormatString;
		}
	}

	public function canUse() {
		# Sockets are not enabled
		return function_exists( 'socket_create' );
	}

	public function log( array $stats ) {
		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		$plength = 0;
		$packet = "";
		foreach ( $stats as $pfdata ) {
			$pfline = sprintf( $this->format,
				$this->collector->getProfileID(),
				$pfdata['calls'],
				$pfdata['cpu'] / 1000, // ms => sec
				0.0, // sum of CPU^2 for each invocation (unused)
				$pfdata['real'] / 1000, // ms => sec
				0.0, // sum of real^2 for each invocation (unused)
				$pfdata['name'],
				$pfdata['memory']
			);
			$length = strlen( $pfline );
			if ( $length + $plength > 1400 ) {
				socket_sendto( $sock, $packet, $plength, 0, $this->host, $this->port );
				$packet = "";
				$plength = 0;
			}
			$packet .= $pfline;
			$plength += $length;
		}
		socket_sendto( $sock, $packet, $plength, 0x100, $this->host, $this->port );
	}
}
