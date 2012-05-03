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
 * (the one from mediawiki/trunk/udpprofile SVN )
 * @ingroup Profiler
 */
class ProfilerSimpleUDP extends ProfilerSimple {
	public function isPersistent() {
		return true;
	}

	public function logData() {
		global $wgUDPProfilerHost, $wgUDPProfilerPort;

		$this->close();

		if ( isset( $this->mCollated['-total'] ) && $this->mCollated['-total']['real'] < $this->mMinimumTime ) {
			# Less than minimum, ignore
			return;
		}

		if ( !MWInit::functionExists( 'socket_create' ) ) {
			# Sockets are not enabled
			return;
		}

		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		$plength = 0;
		$packet = "";
		foreach ( $this->mCollated as $entry => $pfdata ) {
			if( !isset($pfdata['count'])
				|| !isset( $pfdata['cpu'] )
				|| !isset( $pfdata['cpu_sq'] )
				|| !isset( $pfdata['real'] )
				|| !isset( $pfdata['real_sq'] ) ) {
				continue;
			}
			$pfline = sprintf( "%s %s %d %f %f %f %f %s\n", $this->getProfileID(), "-", $pfdata['count'],
				$pfdata['cpu'], $pfdata['cpu_sq'], $pfdata['real'], $pfdata['real_sq'], $entry);
			$length = strlen( $pfline );
			/* printf("<!-- $pfline -->"); */
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
