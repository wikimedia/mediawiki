<?php
/**
 * See deferred.doc
 * @package MediaWiki
 */

/**
 *
 * @package MediaWiki
 */
class SquidUpdate {
	var $urlArr, $mMaxTitles;

	function SquidUpdate( $urlArr = Array(), $maxTitles = false ) {
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

	/* static */ function newFromLinksTo( &$title ) {
		$fname = 'SquidUpdate::newFromLinksTo';
		wfProfileIn( $fname );

		# Get a list of URLs linking to this page
		$id = $title->getArticleID();

		$dbr =& wfGetDB( DB_SLAVE );
		$links = $dbr->tableName( 'links' );
		$cur = $dbr->tableName( 'cur' );

		$sql = "SELECT cur_namespace,cur_title FROM $links,$cur WHERE l_to={$id} and l_from=cur_id" ;
		$res = $dbr->query( $sql, $fname ) ;
		$blurlArr = $title->getSquidURLs();
		if ( $dbr->numRows( $res ) <= $this->mMaxTitles ) {
			while ( $BL = $dbr->fetchObject ( $res ) )
			{
				$tobj = Title::makeTitle( $BL->cur_namespace, $BL->cur_title ) ; 
				$blurlArr[] = $tobj->getInternalURL();
			}
		}
		$dbr->freeResult ( $res ) ;

		wfProfileOut( $fname );
		return new SquidUpdate( $blurlArr );
	}

	/* static */ function newFromBrokenLinksTo( &$title ) {
		$fname = 'SquidUpdate::newFromBrokenLinksTo';
		wfProfileIn( $fname );

		# Get a list of URLs linking to this (currently non-existent) page
		$dbr =& wfGetDB( DB_SLAVE );
		$brokenlinks = $dbr->tableName( 'brokenlinks' );
		$cur = $dbr->tableName( 'cur' );
		$encTitle = $dbr->addQuotes( $title->getPrefixedDBkey() );

		$sql = "SELECT cur_namespace,cur_title FROM $brokenlinks,$cur WHERE bl_to={$encTitle} AND bl_from=cur_id";
		$res = $dbr->query( $sql, $fname );
		$blurlArr = array();
		if ( $dbr->numRows( $res ) <= $this->mMaxTitles ) {
			while ( $BL = $dbr->fetchObject( $res ) )
			{
				$tobj = Title::makeTitle( $BL->cur_namespace, $BL->cur_title );
				$blurlArr[] = $tobj->getInternalURL();
			}
		}
		$dbr->freeResult( $res );
		wfProfileOut( $fname );
		return new SquidUpdate( $blurlArr );
	}

	/* static */ function newSimplePurge( &$title ) {
		$urlArr = $title->getSquidURLs();
		return new SquidUpdate( $blurlArr );
	}

	function doUpdate() {
		SquidUpdate::purge( $this->urlArr );
	}

	/* Purges a list of Squids defined in $wgSquidServers.
	$urlArr should contain the full URLs to purge as values 
	(example: $urlArr[] = 'http://my.host/something')
	XXX report broken Squids per mail or log */

	/* static */ function purge( $urlArr ) {
		global $wgSquidServers, $wgHTCPMulticastAddress,
			$wgHTCPPort, $wgSquidFastPurge;

		if ( $wgSquidServers == 'echo' ) {
			echo implode("<br />\n", $urlArr);
			return;
		}
		
		if ( $wgHTCPMulticastAddress && $wgHTCPPort )
			SquidUpdate::HTCPPurge( $urlArr );

		if ( $wgSquidFastPurge ) {
			SquidUpdate::fastPurge( $urlArr );
			return;
		}

		$fname = 'SquidUpdate::purge';
		wfProfileIn( $fname );

		$maxsocketspersquid = 8; //  socket cap per Squid
		$urlspersocket = 400; // 400 seems to be a good tradeoff, opening a socket takes a while
		$firsturl = $urlArr[0];
		unset($urlArr[0]);
		$urlArr = array_values($urlArr);
		$sockspersq =  max(ceil(count($urlArr) / $urlspersocket ),1);
		if ($sockspersq == 1) {
			/* the most common case */
			$urlspersocket = count($urlArr);
		} else if ($sockspersq > $maxsocketspersquid ) {
			$urlspersocket = ceil(count($urlArr) / $maxsocketspersquid);
			$sockspersq = $maxsocketspersquid;
		}
		$totalsockets = count($wgSquidServers) * $sockspersq;
		$sockets = Array();

		/* this sets up the sockets and tests the first socket for each server. */
		for ($ss=0;$ss < count($wgSquidServers);$ss++) {
			$failed = false;
			$so = 0;
			while ($so < $sockspersq && !$failed) {
				if ($so == 0) {
					/* first socket for this server, do some testing */
					@list($server, $port) = explode(':', $wgSquidServers[$ss]);
					if(!isset($port)) $port = 80;
					#$this->debug("Opening socket to $server:$port");
					$socket = @fsockopen($server, $port, $error, $errstr, 3);
					#$this->debug("\n");
					if (!$socket) {
						$failed = true;
						$totalsockets -= $sockspersq;
					} else {
						$msg = 'PURGE ' . $firsturl . " HTTP/1.0\r\n".
						"Connection: Keep-Alive\r\n\r\n";
						#$this->debug($msg);
						@stream_set_blocking($socket,false);
						@fputs($socket,$msg);
						#$this->debug("...");
						$sockets[] = $socket;
					} 
				} else {
					/* all seems to be well, open the remaining sockets for this server */
					list($server, $port) = explode(':', $wgSquidServers[$ss]);
					if(!isset($port)) $port = 80;
					$sockets[] = @fsockopen($server, $port, $error, $errstr, 2);
					@stream_set_blocking($sockets[$s],false);
				}
				$so++;
			}
		}

		if ($urlspersocket > 0) {
			# now do the heavy lifting if there are more than a single url to purge.
			# all squids are done at the same time by looping through the sockets, 
			# many squids take only little more time than a single squid.
			# we have to read from the sockets once in a while to avoid the network buffer filling up which otherwise
			# blocks the socket after about 500 purges
			for ($r=0;$r < $urlspersocket;$r++) {
				for ($s=0;$s < $totalsockets;$s++) {
					if($sockets[$s]) {
						if($r != 0 && ( $r == 2 || !(r % 200))) {
							$res = '';
							$tries = $len = 0;
							while (($len < 100 && $tries < 5) || $len == $oldlen  ) {
								$oldlen = $len;
								$res .= @fread($sockets[$s],2048);
								$len = strlen($res);
								$tries++;
							}
						}
						$urindex = $r + $urlspersocket * ($s - $sockspersq * floor($s / $sockspersq));
						$msg = 'PURGE ' . $urlArr[$urindex] . " HTTP/1.0\r\n".
						"Connection: Keep-Alive\r\n\r\n";
						#$this->debug($msg);
						@fputs($sockets[$s],$msg);
						#$this->debug("\n");
						# sanity check: if we got html error pages (access denied for example) from the squid
						# after the second purge remove this socket to avoid hanging while trying to write to it
						if($r == 2 && $len > 5000) $sockets[$s] = false;
					}
				}
			}
		}
		#$this->debug("Closing sockets...");
		foreach ($sockets as $socket) {
			if($socket) @fclose($socket);
		}
		#$this->debug("\n");
		wfProfileOut( $fname );
	}

	/*static*/ function fastPurge( $urlArr ) {
		global $wgSquidServers;
		$fname = 'SquidUpdate::fastPurge';
		wfProfileIn( $fname );
		foreach ( $wgSquidServers as $server ) {
			list($server, $port) = explode(':', $server);
			if(!isset($port)) $port = 80;
			
			$conn = @pfsockopen( $server, $port, $error, $errstr, 3 );
			if ( $conn ) {
				wfDebug( 'Purging ' . count($urlArr) . " URL(s) on server $server:$port..." );
				$msg = '';
				foreach ( $urlArr as $url ) {
					$msg .= 'PURGE ' . $url . " HTTP/1.0\r\n".
					"Connection: Keep-Alive\r\n\r\n";
				}
				wfDebug( "\n$msg" );
				@fputs( $conn, $msg );
				wfDebug( "done\n" );
			} else {
				wfDebug( "SquidUpdate::fastPurge(): Error connecting to $server:$port\n" );
			}

		}
		wfProfileOut( $fname );
	}
        /* static */ function HTCPPurge( $urlArr ) {
                global $wgHTCPMulticastAddress, $wgHTCPMulticastTTL, $wgHTCPPort;
                $fname = 'SquidUpdate::HTCPPurge';
                wfProfileIn( $fname );

                $htcpOpCLR = 4;                 // HTCP CLR

                // FIXME PHP doesn't support these socket constants (include/linux/in.h)
                define( "IPPROTO_IP", 0 );
                define( "IP_MULTICAST_LOOP", 34 );
                define( "IP_MULTICAST_TTL", 33 );

                // pfsockopen doesn't work because we need set_sock_opt
                $conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
                if ( $conn ) {
                        // Set socket options
                        socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_LOOP, 0 );
                        if ( $wgHTCPMulticastTTL != 1 )
                                socket_set_option( $conn, IPPROTO_IP, IP_MULTICAST_TTL,
                                        $wgHTCPMulticastTTL );

                        foreach ( $urlArr as $url ) {
                                // Construct a minimal HTCP request diagram
                                // as per RFC 2756
                                // Opcode 'CLR', no response desired, no auth
                                $htcpTransID = rand();

                                $htcpSpecifier = pack( 'na4na*na8n',
                                        4, 'NONE', strlen( $url ), $url,
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
                        wfDebug( "SquidUpdate::HTCPPurge(): Error opening UDP socket: $errstr\n" );
                }
                wfProfileOut( $fname );
        }

	function debug( $text ) {
		global $wgDebugSquid;
		if ( $wgDebugSquid ) {
			wfDebug( $text );
		}
	}
}
?>
