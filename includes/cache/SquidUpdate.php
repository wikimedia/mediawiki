<?php
/**
 * See deferred.txt
 * @file
 * @ingroup Cache
 */

/**
 * Handles purging appropriate Squid URLs given a title (or titles)
 * @ingroup Cache
 */
class SquidUpdate {
	var $urlArr, $mMaxTitles;

	function __construct( $urlArr = Array(), $maxTitles = false ) {
		global $wgMaxSquidPurgeTitles;
		if ( $maxTitles === false ) {
			$this->mMaxTitles = $wgMaxSquidPurgeTitles;
		} else {
			$this->mMaxTitles = $maxTitles;
		}
		if ( count( $urlArr ) > $this->mMaxTitles ) {
			$urlArr = array_slice( $urlArr, 0, $this->mMaxTitles );
		}
		$this->urlArr = $urlArr;
	}

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

	static function newSimplePurge( &$title ) {
		$urlArr = $title->getSquidURLs();
		return new SquidUpdate( $urlArr );
	}

	function doUpdate() {
		SquidUpdate::purge( $this->urlArr );
	}

	/* Purges a list of Squids defined in $wgSquidServers.
	$urlArr should contain the full URLs to purge as values
	(example: $urlArr[] = 'http://my.host/something')
	XXX report broken Squids per mail or log */

	static function purge( $urlArr ) {
		global $wgSquidServers, $wgHTCPMulticastAddress, $wgHTCPPort;

		/*if ( (@$wgSquidServers[0]) == 'echo' ) {
			echo implode("<br />\n", $urlArr) . "<br />\n";
			return;
		}*/

		if( !$urlArr ) {
			return;
		}

		if ( $wgHTCPMulticastAddress && $wgHTCPPort ) {
			return SquidUpdate::HTCPPurge( $urlArr );
		}

		wfProfileIn( __METHOD__ );

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

	static function HTCPPurge( $urlArr ) {
		global $wgHTCPMulticastAddress, $wgHTCPMulticastTTL, $wgHTCPPort;
		wfProfileIn( __METHOD__ );

		$htcpOpCLR = 4;                 // HTCP CLR

		// FIXME PHP doesn't support these socket constants (include/linux/in.h)
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

			foreach ( $urlArr as $url ) {
				if( !is_string( $url ) ) {
					throw new MWException( 'Bad purge URL' );
				}
				$url = SquidUpdate::expand( $url );

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
					$wgHTCPMulticastAddress, $wgHTCPPort );
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
	 * @return string
	 */
	static function expand( $url ) {
		global $wgInternalServer, $wgServer;
		$server = $wgInternalServer !== false ? $wgInternalServer : $wgServer;
		if( $url !== '' && $url[0] == '/' ) {
			return $server . $url;
		}
		return $url;
	}
}
