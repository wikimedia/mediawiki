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
		$page = $dbr->tableName( 'page' );

		$sql = "SELECT page_namespace,page_title FROM $links,$page WHERE l_to={$id} and l_from=page_id" ;
		$res = $dbr->query( $sql, $fname ) ;
		$blurlArr = $title->getSquidURLs();
		if ( $dbr->numRows( $res ) <= $this->mMaxTitles ) {
			while ( $BL = $dbr->fetchObject ( $res ) )
			{
				$tobj = Title::makeTitle( $BL->page_namespace, $BL->page_title ) ; 
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
		$page = $dbr->tableName( 'page' );
		$encTitle = $dbr->addQuotes( $title->getPrefixedDBkey() );

		$sql = "SELECT page_namespace,page_title FROM $brokenlinks,$cur WHERE bl_to={$encTitle} AND bl_from=page_id";
		$res = $dbr->query( $sql, $fname );
		$blurlArr = array();
		if ( $dbr->numRows( $res ) <= $this->mMaxTitles ) {
			while ( $BL = $dbr->fetchObject( $res ) )
			{
				$tobj = Title::makeTitle( $BL->page_namespace, $BL->page_title );
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
		global  $wgSquidServers;

		if ( $wgSquidServers == 'echo' ) {
			echo implode("<br>\n", $urlArr);
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
					/* first socket for this server, do the tests */
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
						@fputs($socket,$msg);
						#$this->debug("...");
						$res = @fread($socket,512);
						#$this->debug("\n");
						/* Squid only returns http headers with 200 or 404 status, 
						if there's more returned something's wrong */
						if (strlen($res) > 250) {
							fclose($socket);
							$failed = true;
							$totalsockets -= $sockspersq;
						} else {
							@stream_set_blocking($socket,false);
							$sockets[] = $socket;
						}
					} 
				} else {
					/* open the remaining sockets for this server */
					list($server, $port) = explode(':', $wgSquidServers[$ss]);
					if(!isset($port)) $port = 80;
					$sockets[] = @fsockopen($server, $port, $error, $errstr, 2);
					@stream_set_blocking($sockets[$s],false);
				}
				$so++;
			}
		}

		if ($urlspersocket > 0) {
			/* now do the heavy lifting. The fread() relies on Squid returning only the headers */
			for ($r=0;$r < $urlspersocket;$r++) {
				for ($s=0;$s < $totalsockets;$s++) {
					if($r != 0) {
						$res = '';
						$esc = 0;
						while (strlen($res) < 100 && $esc < 200  ) {
							$res .= @fread($sockets[$s],512);
							$esc++;
							usleep(20);
						}
					}
					$urindex = $r + $urlspersocket * ($s - $sockspersq * floor($s / $sockspersq));
					$msg = 'PURGE ' . $urlArr[$urindex] . " HTTP/1.0\r\n".
					"Connection: Keep-Alive\r\n\r\n";
					#$this->debug($msg);
					@fputs($sockets[$s],$msg);
					#$this->debug("\n");
				}
			}
		}
		#$this->debug("Reading response...");
		foreach ($sockets as $socket) {
			$res = '';
			$esc = 0;
			while (strlen($res) < 100 && $esc < 200  ) {
				$res .= @fread($socket,1024);
				$esc++;
				usleep(20);
			}

			@fclose($socket);
		}
		#$this->debug("\n");
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
