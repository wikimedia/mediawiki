<?php
/**
 * Squid cache purging.
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
 * @ingroup Cache
 */

/**
 * Handles purging appropriate Squid URLs given a title (or titles)
 * @ingroup Cache
 */
class SquidUpdate {
	/**
	 * Collection of URLs to purge.
	 * @var array
	 */
	protected $urlArr;

	/**
	 * @param array $urlArr Collection of URLs to purge
	 * @param bool|int $maxTitles Maximum number of unique URLs to purge
	 */
	public function __construct( $urlArr = array(), $maxTitles = false ) {
		global $wgMaxSquidPurgeTitles;
		if ( $maxTitles === false ) {
			$maxTitles = $wgMaxSquidPurgeTitles;
		}

		// Remove duplicate URLs from list
		$urlArr = array_unique( $urlArr );
		if ( count( $urlArr ) > $maxTitles ) {
			// Truncate to desired maximum URL count
			$urlArr = array_slice( $urlArr, 0, $maxTitles );
		}
		$this->urlArr = $urlArr;
	}

	/**
	 * Create a SquidUpdate from an array of Title objects, or a TitleArray object
	 *
	 * @param array $titles
	 * @param array $urlArr
	 * @return SquidUpdate
	 */
	public static function newFromTitles( $titles, $urlArr = array() ) {
		global $wgMaxSquidPurgeTitles;
		$i = 0;
		/** @var Title $title */
		foreach ( $titles as $title ) {
			$urlArr[] = $title->getInternalURL();
			if ( $i++ > $wgMaxSquidPurgeTitles ) {
				break;
			}
		}

		return new SquidUpdate( $urlArr );
	}

	/**
	 * @param Title $title
	 * @return SquidUpdate
	 */
	public static function newSimplePurge( Title $title ) {
		$urlArr = $title->getSquidURLs();

		return new SquidUpdate( $urlArr );
	}

	/**
	 * Purges the list of URLs passed to the constructor.
	 */
	public function doUpdate() {
		self::purge( $this->urlArr );
	}

	/**
	 * Purges a list of Squids defined in $wgSquidServers.
	 * $urlArr should contain the full URLs to purge as values
	 * (example: $urlArr[] = 'http://my.host/something')
	 * XXX report broken Squids per mail or log
	 *
	 * @param array $urlArr List of full URLs to purge
	 */
	public static function purge( $urlArr ) {
		global $wgSquidServers, $wgHTCPRouting;

		if ( !$urlArr ) {
			return;
		}

		wfDebugLog( 'squid', __METHOD__ . ': ' . implode( ' ', $urlArr ) );

		if ( $wgHTCPRouting ) {
			self::HTCPPurge( $urlArr );
		}

		// Remove duplicate URLs
		$urlArr = array_unique( $urlArr );
		// Maximum number of parallel connections per squid
		$maxSocketsPerSquid = 8;
		// Number of requests to send per socket
		// 400 seems to be a good tradeoff, opening a socket takes a while
		$urlsPerSocket = 400;
		$socketsPerSquid = ceil( count( $urlArr ) / $urlsPerSocket );
		if ( $socketsPerSquid > $maxSocketsPerSquid ) {
			$socketsPerSquid = $maxSocketsPerSquid;
		}

		$pool = new SquidPurgeClientPool;
		$chunks = array_chunk( $urlArr, ceil( count( $urlArr ) / $socketsPerSquid ) );
		foreach ( $wgSquidServers as $server ) {
			foreach ( $chunks as $chunk ) {
				$client = new SquidPurgeClient( $server );
				foreach ( $chunk as $url ) {
					$client->queuePurge( $url );
				}
				$pool->addClient( $client );
			}
		}
		$pool->run();

	}

	/**
	 * Send Hyper Text Caching Protocol (HTCP) CLR requests.
	 *
	 * @throws MWException
	 * @param array $urlArr Collection of URLs to purge
	 */
	public static function HTCPPurge( $urlArr ) {
		global $wgHTCPRouting, $wgHTCPMulticastTTL;

		// HTCP CLR operation
		$htcpOpCLR = 4;

		// @todo FIXME: PHP doesn't support these socket constants (include/linux/in.h)
		if ( !defined( "IPPROTO_IP" ) ) {
			define( "IPPROTO_IP", 0 );
			define( "IP_MULTICAST_LOOP", 34 );
			define( "IP_MULTICAST_TTL", 33 );
		}

		// pfsockopen doesn't work because we need set_sock_opt
		$conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		if ( !$conn ) {
			$errstr = socket_strerror( socket_last_error() );
			wfDebugLog( 'squid', __METHOD__ .
				": Error opening UDP socket: $errstr" );

			return;
		}

		// Set socket options
		socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_LOOP, 0 );
		if ( $wgHTCPMulticastTTL != 1 ) {
			// Set multicast time to live (hop count) option on socket
			socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_TTL,
				$wgHTCPMulticastTTL );
		}

		// Remove duplicate URLs from collection
		$urlArr = array_unique( $urlArr );
		// Get sequential trx IDs for packet loss counting
		$ids = UIDGenerator::newSequentialPerNodeIDs(
			'squidhtcppurge', 32, count( $urlArr ), UIDGenerator::QUICK_VOLATILE
		);

		foreach ( $urlArr as $url ) {
			if ( !is_string( $url ) ) {
				throw new MWException( 'Bad purge URL' );
			}
			$url = self::expand( $url );
			$conf = self::getRuleForURL( $url, $wgHTCPRouting );
			if ( !$conf ) {
				wfDebugLog( 'squid', __METHOD__ .
					"No HTCP rule configured for URL {$url} , skipping" );
				continue;
			}

			if ( isset( $conf['host'] ) && isset( $conf['port'] ) ) {
				// Normalize single entries
				$conf = array( $conf );
			}
			foreach ( $conf as $subconf ) {
				if ( !isset( $subconf['host'] ) || !isset( $subconf['port'] ) ) {
					throw new MWException( "Invalid HTCP rule for URL $url\n" );
				}
			}

			// Construct a minimal HTCP request diagram
			// as per RFC 2756
			// Opcode 'CLR', no response desired, no auth
			$htcpTransID = current( $ids );
			next( $ids );

			$htcpSpecifier = pack( 'na4na*na8n',
				4, 'HEAD', strlen( $url ), $url,
				8, 'HTTP/1.0', 0 );

			$htcpDataLen = 8 + 2 + strlen( $htcpSpecifier );
			$htcpLen = 4 + $htcpDataLen + 2;

			// Note! Squid gets the bit order of the first
			// word wrong, wrt the RFC. Apparently no other
			// implementation exists, so adapt to Squid
			$htcpPacket = pack( 'nxxnCxNxxa*n',
				$htcpLen, $htcpDataLen, $htcpOpCLR,
				$htcpTransID, $htcpSpecifier, 2 );

			wfDebugLog( 'squid', __METHOD__ .
				"Purging URL $url via HTCP" );
			foreach ( $conf as $subconf ) {
				socket_sendto( $conn, $htcpPacket, $htcpLen, 0,
					$subconf['host'], $subconf['port'] );
			}
		}
	}

	/**
	 * Expand local URLs to fully-qualified URLs using the internal protocol
	 * and host defined in $wgInternalServer. Input that's already fully-
	 * qualified will be passed through unchanged.
	 *
	 * This is used to generate purge URLs that may be either local to the
	 * main wiki or include a non-native host, such as images hosted on a
	 * second internal server.
	 *
	 * Client functions should not need to call this.
	 *
	 * @param string $url
	 * @return string
	 */
	public static function expand( $url ) {
		return wfExpandUrl( $url, PROTO_INTERNAL );
	}

	/**
	 * Find the HTCP routing rule to use for a given URL.
	 * @param string $url URL to match
	 * @param array $rules Array of rules, see $wgHTCPRouting for format and behavior
	 * @return mixed Element of $rules that matched, or false if nothing matched
	 */
	private static function getRuleForURL( $url, $rules ) {
		foreach ( $rules as $regex => $routing ) {
			if ( $regex === '' || preg_match( $regex, $url ) ) {
				return $routing;
			}
		}

		return false;
	}
}
