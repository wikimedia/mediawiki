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
	public function canUse() {
		# Sockets are not enabled
		return function_exists( 'socket_create' );
	}

	public function log( array $stats ) {
		global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgUDPProfilerFormatString;

		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		$plength = 0;
		$packet = "";
		foreach ( $stats as $pfdata ) {
			$pfline = sprintf( $wgUDPProfilerFormatString,
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
				socket_sendto( $sock, $packet, $plength, 0, $wgUDPProfilerHost, $wgUDPProfilerPort );
				$packet = "";
				$plength = 0;
			}
			$packet .= $pfline;
			$plength += $length;
		}
		socket_sendto( $sock, $packet, $plength, 0x100, $wgUDPProfilerHost, $wgUDPProfilerPort );
	}
}
