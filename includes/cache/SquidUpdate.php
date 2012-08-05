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
	var $urlArr, $mMaxTitles;

	/**
	 * @param $urlArr array
	 * @param $maxTitles bool|int
	 */
	function __construct( $urlArr = array(), $maxTitles = false ) {
		global $wgMaxSquidPurgeTitles;
		if ( $maxTitles === false ) {
			$this->mMaxTitles = $wgMaxSquidPurgeTitles;
		} else {
			$this->mMaxTitles = $maxTitles;
		}
		$urlArr = array_unique( $urlArr ); // Remove duplicates
		if ( count( $urlArr ) > $this->mMaxTitles ) {
			$urlArr = array_slice( $urlArr, 0, $this->mMaxTitles );
		}
		$this->urlArr = $urlArr;
	}

	/**
	 * @param $title Title
	 *
	 * @return SquidUpdate
	 */
	static function newFromLinksTo( &$title ) {
		global $wgMaxSquidPurgeTitles;
		wfProfileIn( __METHOD__ );

		# Get a list of URLs linking to this page
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'links', 'page' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'pl_namespace' => $title->getNamespace(),
				'pl_title'     => $title->getDBkey(),
				'pl_from=page_id' ),
			__METHOD__ );
		$blurlArr = $title->getSquidURLs();
		if ( $dbr->numRows( $res ) <= $wgMaxSquidPurgeTitles ) {
			foreach ( $res as $BL ) {
				$tobj = Title::makeTitle( $BL->page_namespace, $BL->page_title ) ;
				$blurlArr[] = $tobj->getInternalURL();
			}
		}

		wfProfileOut( __METHOD__ );
		return new SquidUpdate( $blurlArr );
	}

	/**
	 * Create a SquidUpdate from an array of Title objects, or a TitleArray object
	 *
	 * @param $titles array
	 * @param $urlArr array
	 *
	 * @return SquidUpdate
	 */
	static function newFromTitles( $titles, $urlArr = array() ) {
		global $wgMaxSquidPurgeTitles;
		$i = 0;
		foreach ( $titles as $title ) {
			$urlArr[] = $title->getInternalURL();
			if ( $i++ > $wgMaxSquidPurgeTitles ) {
				break;
			}
		}
		return new SquidUpdate( $urlArr );
	}

	/**
	 * @param $title Title
	 *
	 * @return SquidUpdate
	 */
	static function newSimplePurge( &$title ) {
		$urlArr = $title->getSquidURLs();
		return new SquidUpdate( $urlArr );
	}

	/**
	 * Purges the list of URLs passed to the constructor
	 */
	function doUpdate() {
		SquidUpdate::purge( $this->urlArr );
	}

	/**
	 * Purges a list of Squids defined in $wgSquidServers.
	 * $urlArr should contain the full URLs to purge as values
	 * (example: $urlArr[] = 'http://my.host/something')
	 * XXX report broken Squids per mail or log
	 *
	 * @param $urlArr array
	 * @return void
	 */
	static function purge( $urlArr ) {
		global $wgSquidServers, $wgHTCPMulticastRouting;

		if( !$urlArr ) {
			return;
		}

		if ( $wgHTCPMulticastRouting ) {
			SquidUpdate::HTCPPurge( $urlArr );
		}

		wfProfileIn( __METHOD__ );

		$urlArr = array_unique( $urlArr ); // Remove duplicates
		$maxSocketsPerSquid = 8; //  socket cap per Squid
		$urlsPerSocket = 400; // 400 seems to be a good tradeoff, opening a socket takes a while
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

		wfProfileOut( __METHOD__ );
	}

	/**
	 * @throws MWException
	 * @param $urlArr array
	 */
	static function HTCPPurge( $urlArr ) {
		global $wgHTCPMulticastRouting, $wgHTCPMulticastTTL;
		wfProfileIn( __METHOD__ );

		$htcpOpCLR = 4; // HTCP CLR

		// @todo FIXME: PHP doesn't support these socket constants (include/linux/in.h)
		if( !defined( "IPPROTO_IP" ) ) {
			define( "IPPROTO_IP", 0 );
			define( "IP_MULTICAST_LOOP", 34 );
			define( "IP_MULTICAST_TTL", 33 );
		}

		// pfsockopen doesn't work because we need set_sock_opt
		$conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		if ( $conn ) {
			// Set socket options
			socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_LOOP, 0 );
			if ( $wgHTCPMulticastTTL != 1 )
				socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_TTL,
					$wgHTCPMulticastTTL );

			$urlArr = array_unique( $urlArr ); // Remove duplicates
			foreach ( $urlArr as $url ) {
				if( !is_string( $url ) ) {
					throw new MWException( 'Bad purge URL' );
				}
				$url = SquidUpdate::expand( $url );
				$conf = self::getRuleForURL( $url, $wgHTCPMulticastRouting );
				if ( !$conf ) {
					wfDebug( "No HTCP rule configured for URL $url , skipping\n" );
					continue;
				}
				if ( !isset( $conf['host'] ) || !isset( $conf['port'] ) ) {
					throw new MWException( "Invalid HTCP rule for URL $url\n" );
				}

				// Construct a minimal HTCP request diagram
				// as per RFC 2756
				// Opcode 'CLR', no response desired, no auth
				$htcpTransID = rand();

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
					$htcpTransID, $htcpSpecifier, 2);

				// Send out
				wfDebug( "Purging URL $url via HTCP\n" );
				socket_sendto( $conn, $htcpPacket, $htcpLen, 0,
					$conf['host'], $conf['port'] );
			}
		} else {
			$errstr = socket_strerror( socket_last_error() );
			wfDebug( __METHOD__ . "(): Error opening UDP socket: $errstr\n" );
		}
		wfProfileOut( __METHOD__ );
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
	 * @param $url string
	 *
	 * @return string
	 */
	static function expand( $url ) {
		return wfExpandUrl( $url, PROTO_INTERNAL );
	}
	
	/**
	 * Find the HTCP routing rule to use for a given URL.
	 * @param $url string URL to match
	 * @param $rules array Array of rules, see $wgHTCPMulticastRouting for format and behavior
	 * @return mixed Element of $rules that matched, or false if nothing matched
	 */
	static function getRuleForURL( $url, $rules ) {
		foreach ( $rules as $regex => $routing ) {
			if ( $regex === '' || preg_match( $regex, $url ) ) {
				return $routing;
			}
		}
		return false;
	}
	
}
