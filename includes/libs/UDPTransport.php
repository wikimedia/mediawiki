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

use Wikimedia\IPUtils;

/**
 * A generic class to send a message over UDP
 *
 * If a message prefix is provided to the constructor or via
 * UDPTransport::newFromString(), the payload of the UDP datagrams emitted
 * will be formatted with the prefix and a single space at the start of each
 * line. This is the payload format expected by the udp2log service.
 *
 * @since 1.25
 */
class UDPTransport {
	// Limit to 64KB
	public const MAX_PAYLOAD_SIZE = 65507;
	private $host, $port, $prefix, $domain;

	/**
	 * @param string $host IP address to send to
	 * @param int $port port number
	 * @param int $domain AF_INET or AF_INET6 constant
	 * @param string|bool $prefix Prefix to use, false for no prefix
	 */
	public function __construct( $host, $port, $domain, $prefix = false ) {
		$this->host = $host;
		$this->port = $port;
		$this->domain = $domain;
		$this->prefix = $prefix;
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
			$prefix = $m[3] ?? false;
			$domain = AF_INET6;
		} elseif ( preg_match( '!^udp:(?://)?([a-zA-Z0-9.-]+):(\d+)(?:/(.*))?$!', $info, $m ) ) {
			$host = $m[1];
			if ( !IPUtils::isIPv4( $host ) ) {
				$host = gethostbyname( $host );
			}
			$port = intval( $m[2] );
			$prefix = $m[3] ?? false;
			$domain = AF_INET;
		} else {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid UDP specification' );
		}

		return new self( $host, $port, $domain, $prefix );
	}

	/**
	 * @param string $text
	 */
	public function emit( $text ) : void {
		// Clean it up for the multiplexer
		if ( $this->prefix !== false ) {
			$text = preg_replace( '/^/m', $this->prefix . ' ', $text );

			if ( strlen( $text ) > self::MAX_PAYLOAD_SIZE - 1 ) {
				$text = substr( $text, 0, self::MAX_PAYLOAD_SIZE - 1 );
			}

			if ( substr( $text, -1 ) != "\n" ) {
				$text .= "\n";
			}
		} elseif ( strlen( $text ) > self::MAX_PAYLOAD_SIZE ) {
			$text = substr( $text, 0, self::MAX_PAYLOAD_SIZE );
		}

		$sock = socket_create( $this->domain, SOCK_DGRAM, SOL_UDP );
		if ( !$sock ) { // @todo should this throw an exception?
			return;
		}

		socket_sendto( $sock, $text, strlen( $text ), 0, $this->host, $this->port );
		socket_close( $sock );
	}
}
