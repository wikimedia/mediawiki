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

/**
 * A generic class to send a message over UDP
 *
 * @since 1.25
 */
class UDPTransport {
	private $host, $port, $prefix, $domain;

	/**
	 * @param string $host IP address to send to
	 * @param int $port port number
	 * @param string|bool $prefix Prefix to use, false for no prefix
	 * @param int $domain AF_INET or AF_INET6 constant
	 */
	public function __construct( $host, $port, $prefix, $domain ) {
		$this->host = $host;
		$this->port = $port;
		$this->prefix = $prefix;
		$this->domain = $domain;
	}

	/**
	 * @param string $info In the format of "udp://host:port/prefix"
	 * @return UDPTransport
	 * @throws InvalidArgumentException
	 */
	public static function newFromString( $info ) {
		if ( preg_match( '!^udp:(?://)?\[([0-9a-fA-F:]+)\]:(\d+)(?:/(.*))?$!', $info, $m ) ) {
			// IPv6 bracketed host
			$host = $m[1];
			$port = intval( $m[2] );
			$prefix = isset( $m[3] ) ? $m[3] : false;
			$domain = AF_INET6;
		} elseif ( preg_match( '!^udp:(?://)?([a-zA-Z0-9.-]+):(\d+)(?:/(.*))?$!', $info, $m ) ) {
			$host = $m[1];
			if ( !IP::isIPv4( $host ) ) {
				$host = gethostbyname( $host );
			}
			$port = intval( $m[2] );
			$prefix = isset( $m[3] ) ? $m[3] : false;
			$domain = AF_INET;
		} else {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid UDP specification' );
		}

		return new self( $host, $port, $prefix, $domain );
	}

	/**
	 * @param string $text
	 */
	public function emit( $text ) {
		// Clean it up for the multiplexer
		if ( strval( $this->prefix ) !== '' ) {
			$text = preg_replace( '/^/m', $this->prefix . ' ', $text );

			// Limit to 64KB
			if ( strlen( $text ) > 65506 ) {
				$text = substr( $text, 0, 65506 );
			}

			if ( substr( $text, -1 ) != "\n" ) {
				$text .= "\n";
			}
		} elseif ( strlen( $text ) > 65507 ) {
			$text = substr( $text, 0, 65507 );
		}

		$sock = socket_create( $this->domain, SOCK_DGRAM, SOL_UDP );
		if ( !$sock ) { // @todo should this throw an exception?
			return;
		}

		socket_sendto( $sock, $text, strlen( $text ), 0, $this->host, $this->port );
		socket_close( $sock );
	}
}
