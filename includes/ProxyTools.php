<?php
/**
 * Functions for dealing with proxies
 *
 * @file
 */

/**
 * Extracts the XFF string from the request header
 * Checks first for "X-Forwarded-For", then "Client-ip"
 * Note: headers are spoofable
 * @return string
 */
function wfGetForwardedFor() {
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = array ();
		foreach ( apache_request_headers() as $tempName => $tempValue ) {
			$set[ strtoupper( $tempName ) ] = $tempValue;
		}
		$index = strtoupper ( 'X-Forwarded-For' );
		$index2 = strtoupper ( 'Client-ip' );
	} else {
		// Subject to spoofing with headers like X_Forwarded_For
		$set = $_SERVER;
		$index = 'HTTP_X_FORWARDED_FOR';
		$index2 = 'CLIENT-IP';
	}

	#Try a couple of headers
	if( isset( $set[$index] ) ) {
		return $set[$index];
	} else if( isset( $set[$index2] ) ) {
		return $set[$index2];
	} else {
		return null;
	}
}

/**
 * Returns the browser/OS data from the request header
 * Note: headers are spoofable
 * @return string
 */
function wfGetAgent() {
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = array ();
		foreach ( apache_request_headers() as $tempName => $tempValue ) {
			$set[ strtoupper( $tempName ) ] = $tempValue;
		}
		$index = strtoupper ( 'User-Agent' );
	} else {
		// Subject to spoofing with headers like X_Forwarded_For
		$set = $_SERVER;
		$index = 'HTTP_USER_AGENT';
	}
	if( isset( $set[$index] ) ) {
		return $set[$index];
	} else {
		return '';
	}
}

/**
 * Work out the IP address based on various globals
 * For trusted proxies, use the XFF client IP (first of the chain)
 * @return string
 */
function wfGetIP() {
	global $wgUsePrivateIPs, $wgCommandLineMode;
	static $ip = false;

	# Return cached result
	if ( !empty( $ip ) ) {
		return $ip;
	}

	$ipchain = array();

	/* collect the originating ips */
	# Client connecting to this webserver
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = IP::canonicalize( $_SERVER['REMOTE_ADDR'] );
	} elseif( $wgCommandLineMode ) {
		$ip = '127.0.0.1';
	}
	if( $ip ) {
		$ipchain[] = $ip;
	}

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
		$curIP = IP::canonicalize( $curIP );
		if ( wfIsTrustedProxy( $curIP ) ) {
			if ( isset( $ipchain[$i + 1] ) ) {
				if( $wgUsePrivateIPs || IP::isPublic( $ipchain[$i + 1 ] ) ) {
					$ip = $ipchain[$i + 1];
				}
			}
		} else {
			break;
		}
	}

	# Allow extensions to improve our guess
	wfRunHooks( 'GetIP', array( &$ip ) );

	if( !$ip ) {
		throw new MWException( "Unable to determine IP" );
	}

	wfDebug( "IP: $ip\n" );
	return $ip;
}

/**
 * Checks if an IP is a trusted proxy providor
 * Useful to tell if X-Fowarded-For data is possibly bogus
 * Squid cache servers for the site and AOL are whitelisted
 * @param $ip String
 * @return bool
 */
function wfIsTrustedProxy( $ip ) {
	global $wgSquidServers, $wgSquidServersNoPurge;

	if ( in_array( $ip, $wgSquidServers ) ||
		in_array( $ip, $wgSquidServersNoPurge )
	) {
		$trusted = true;
	} else {
		$trusted = false;
	}
	wfRunHooks( 'IsTrustedProxy', array( &$ip, &$trusted ) );
	return $trusted;
}

/**
 * Forks processes to scan the originating IP for an open proxy server
 * MemCached can be used to skip IPs that have already been scanned
 */
function wfProxyCheck() {
	global $wgBlockOpenProxies, $wgProxyPorts, $wgProxyScriptPath;
	global $wgMemc, $wgProxyMemcExpiry;
	global $wgProxyKey;

	if ( !$wgBlockOpenProxies ) {
		return;
	}

	$ip = wfGetIP();

	# Get MemCached key
	$mcKey = wfMemcKey( 'proxy', 'ip', $ip );
	$mcValue = $wgMemc->get( $mcKey );
	$skip = (bool)$mcValue;

	# Fork the processes
	if ( !$skip ) {
		$title = SpecialPage::getTitleFor( 'Blockme' );
		$iphash = md5( $ip . $wgProxyKey );
		$url = $title->getFullURL( 'ip='.$iphash );

		foreach ( $wgProxyPorts as $port ) {
			$params = implode( ' ', array(
						escapeshellarg( $wgProxyScriptPath ),
						escapeshellarg( $ip ),
						escapeshellarg( $port ),
						escapeshellarg( $url )
						));
			exec( "php $params >" . wfGetNull() . " 2>&1 &" );
		}
		# Set MemCached key
		$wgMemc->set( $mcKey, 1, $wgProxyMemcExpiry );
	}
}

/**
 * Convert a network specification in CIDR notation to an integer network and a number of bits
 * @return array(string, int)
 */
function wfParseCIDR( $range ) {
	return IP::parseCIDR( $range );
}

/**
 * Check if an IP address is in the local proxy list
 * @return bool
 */
function wfIsLocallyBlockedProxy( $ip ) {
	global $wgProxyList;

	if ( !$wgProxyList ) {
		return false;
	}
	wfProfileIn( __METHOD__ );

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
	wfProfileOut( __METHOD__ );
	return $ret;
}

