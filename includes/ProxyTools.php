<?php
/**
 * Functions for dealing with proxies
 * @package MediaWiki
 */

function wfGetForwardedFor() {
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = apache_request_headers();
		$index = 'X-Forwarded-For';
	} else {
		// Subject to spoofing with headers like X_Forwarded_For
		$set = $_SERVER;
		$index = 'HTTP_X_FORWARDED_FOR';
	}
	if( isset( $set[$index] ) ) {
		return $set[$index];
	} else {
		return null;
	}
}

/** Work out the IP address based on various globals */
function wfGetIP() {
	global $wgSquidServers, $wgSquidServersNoPurge, $wgIP;

	# Return cached result
	if ( !empty( $wgIP ) ) {
		return $wgIP;
	}

	/* collect the originating ips */
	# Client connecting to this webserver
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ipchain = array( $_SERVER['REMOTE_ADDR'] );
	} else {
		# Running on CLI?
		$ipchain = array( '127.0.0.1' );
	}
	$ip = $ipchain[0];

	# Get list of trusted proxies
	# Flipped for quicker access
	$trustedProxies = array_flip( array_merge( $wgSquidServers, $wgSquidServersNoPurge ) );
	if ( count( $trustedProxies ) ) {
		# Append XFF on to $ipchain
		$forwardedFor = wfGetForwardedFor();
		if ( isset( $forwardedFor ) ) {
			$xff = array_map( 'trim', explode( ',', $forwardedFor ) );
			$xff = array_reverse( $xff );
			$ipchain = array_merge( $ipchain, $xff );
		}
		# Step through XFF list and find the last address in the list which is a trusted server
		# Set $ip to the IP address given by that trusted server, unless the address is not sensible (e.g. private)
		foreach ( $ipchain as $i => $curIP ) {
			if ( array_key_exists( $curIP, $trustedProxies ) ) {
				if ( isset( $ipchain[$i + 1] ) && wfIsIPPublic( $ipchain[$i + 1] ) ) {
					$ip = $ipchain[$i + 1];
				}
			} else {
				break;
			}
		}
	}

	wfDebug( "IP: $ip\n" );
	$wgIP = $ip;
	return $ip;
}

/**
 * Given an IP address in dotted-quad notation, returns an unsigned integer.
 * Like ip2long() except that it actually works and has a consistent error return value.
 */
function wfIP2Unsigned( $ip ) {
	$n = ip2long( $ip );
	if ( $n == -1 || $n === false ) { # Return value on error depends on PHP version
		$n = false;
	} elseif ( $n < 0 ) {
		$n += pow( 2, 32 );
	}
	return $n;
}

/**
 * Return a zero-padded hexadecimal representation of an IP address
 */
function wfIP2Hex( $ip ) {
	$n = wfIP2Unsigned( $ip );
	if ( $n !== false ) {
		$n = sprintf( '%08X', $n );
	}
	return $n;
}

/**
 * Determine if an IP address really is an IP address, and if it is public,
 * i.e. not RFC 1918 or similar
 */
function wfIsIPPublic( $ip ) {
	$n = wfIP2Unsigned( $ip );
	if ( !$n ) {
		return false;
	}
	
	// ip2long accepts incomplete addresses, as well as some addresses
	// followed by garbage characters. Check that it's really valid.
	if( $ip != long2ip( $n ) ) {
		return false;
	}

	static $privateRanges = false;
	if ( !$privateRanges ) {
		$privateRanges = array(
			array( '10.0.0.0',    '10.255.255.255' ),   # RFC 1918 (private)
			array( '172.16.0.0',  '172.31.255.255' ),   #     "
			array( '192.168.0.0', '192.168.255.255' ),  #     "
			array( '0.0.0.0',     '0.255.255.255' ),    # this network
			array( '127.0.0.0',   '127.255.255.255' ),  # loopback
		);
	}

	foreach ( $privateRanges as $r ) {
		$start = wfIP2Unsigned( $r[0] );
		$end = wfIP2Unsigned( $r[1] );
		if ( $n >= $start && $n <= $end ) {
			return false;
		}
	}
	return true;
}

/**
 * Forks processes to scan the originating IP for an open proxy server
 * MemCached can be used to skip IPs that have already been scanned
 */
function wfProxyCheck() {
	global $wgBlockOpenProxies, $wgProxyPorts, $wgProxyScriptPath;
	global $wgUseMemCached, $wgMemc, $wgDBname, $wgProxyMemcExpiry;
	global $wgProxyKey;

	if ( !$wgBlockOpenProxies ) {
		return;
	}

	$ip = wfGetIP();

	# Get MemCached key
	$skip = false;
	if ( $wgUseMemCached ) {
		$mcKey = "$wgDBname:proxy:ip:$ip";
		$mcValue = $wgMemc->get( $mcKey );
		if ( $mcValue ) {
			$skip = true;
		}
	}

	# Fork the processes
	if ( !$skip ) {
		$title = Title::makeTitle( NS_SPECIAL, 'Blockme' );
		$iphash = md5( $ip . $wgProxyKey );
		$url = $title->getFullURL( 'ip='.$iphash );

		foreach ( $wgProxyPorts as $port ) {
			$params = implode( ' ', array(
						escapeshellarg( $wgProxyScriptPath ),
						escapeshellarg( $ip ),
						escapeshellarg( $port ),
						escapeshellarg( $url )
						));
			exec( "php $params &>/dev/null &" );
		}
		# Set MemCached key
		if ( $wgUseMemCached ) {
			$wgMemc->set( $mcKey, 1, $wgProxyMemcExpiry );
		}
	}
}

/**
 * Convert a network specification in CIDR notation to an integer network and a number of bits
 */
function wfParseCIDR( $range ) {
	$parts = explode( '/', $range, 2 );
	if ( count( $parts ) != 2 ) {
		return array( false, false );
	}
	$network = wfIP2Unsigned( $parts[0] );
	if ( $network !== false && is_numeric( $parts[1] ) && $parts[1] >= 0 && $parts[1] <= 32 ) {
		$bits = $parts[1];
	} else {
		$network = false;
		$bits = false;
	}
	return array( $network, $bits );
}

/**
 * Check if an IP address is in the local proxy list
 */
function wfIsLocallyBlockedProxy( $ip ) {
	global $wgProxyList;
	$fname = 'wfIsLocallyBlockedProxy';

	if ( !$wgProxyList ) {
		return false;
	}
	wfProfileIn( $fname );

	if ( !is_array( $wgProxyList ) ) {
		# Load from the specified file
		$wgProxyList = array_map( 'trim', file( $wgProxyList ) );
	}

	if ( !is_array( $wgProxyList ) ) {
		$ret = false;
	} elseif ( array_search( $ip, $wgProxyList ) !== false ) {
		$ret = true;
	} elseif ( array_key_exists( $ip, $wgProxyList ) ) {
		# Old-style flipped proxy list
		$ret = true;
	} else {
		$ret = false;
	}
	wfProfileOut( $fname );
	return $ret;
}




?>
