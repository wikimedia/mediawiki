<?php
/**
 * Functions for dealing with proxies
 */

function wfGetForwardedFor() {
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = apache_request_headers();
		$index = 'X-Forwarded-For';
		$index2 = 'Client-ip';
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

function wfGetLastIPfromXFF( $xff ) {
	if ( $xff ) {
	// Avoid annoyingly long xff hacks
		$xff = substr( $xff, 0, 255 );
		// Look for the last IP, assuming they are separated by commas or spaces
		$n = ( strrpos($xff, ',') ) ? strrpos($xff, ',') : strrpos($xff, ' ');
		if ( strrpos !== false ) {
			$last = trim( substr( $xff, $n + 1 ) );
			// Make sure it is an IP
			$m = preg_match('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#', $last, $last_ip4);
			$n = preg_match('#:(:[0-9A-Fa-f]{1,4}){1,7}|[0-9A-Fa-f]{1,4}(:{1,2}[0-9A-Fa-f]{1,4}|::$){1,7}#', $last, $last_ip6);
			if ( $m > 0 )
				$xff_ip = $last_ip4;
			else if ( $n > 0 ) 
				$xff_ip = $last_ip6;
			else 
				$xff_ip = null;
		} else {
			$xff_ip = null;
		} 
	} else {
		$xff_ip = null;
	}
	return $xff_ip;
}

function wfGetAgent() {
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = apache_request_headers();
		$index = 'User-Agent';
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

/** Work out the IP address based on various globals */
function wfGetIP() {
	global $wgIP;

	# Return cached result
	if ( !empty( $wgIP ) ) {
		return $wgIP;
	}

	/* collect the originating ips */
	# Client connecting to this webserver
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ipchain = array( IP::canonicalize( $_SERVER['REMOTE_ADDR'] ) );
	} else {
		# Running on CLI?
		$ipchain = array( '127.0.0.1' );
	}
	$ip = $ipchain[0];

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
			if ( isset( $ipchain[$i + 1] ) && IP::isPublic( $ipchain[$i + 1] ) ) {
				$ip = $ipchain[$i + 1];
			}
		} else {
			break;
		}
	}

	wfDebug( "IP: $ip\n" );
	$wgIP = $ip;
	return $ip;
}

function wfIsTrustedProxy( $ip ) {
	global $wgSquidServers, $wgSquidServersNoPurge;

	if ( in_array( $ip, $wgSquidServers ) || 
		in_array( $ip, $wgSquidServersNoPurge ) || 
		wfIsAOLProxy( $ip ) 
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
	global $wgUseMemCached, $wgMemc, $wgProxyMemcExpiry;
	global $wgProxyKey;

	if ( !$wgBlockOpenProxies ) {
		return;
	}

	$ip = wfGetIP();

	# Get MemCached key
	$skip = false;
	if ( $wgUseMemCached ) {
		$mcKey = wfMemcKey( 'proxy', 'ip', $ip );
		$mcValue = $wgMemc->get( $mcKey );
		if ( $mcValue ) {
			$skip = true;
		}
	}

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
	return IP::parseCIDR( $range );
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

/**
 * TODO: move this list to the database in a global IP info table incorporating
 * trusted ISP proxies, blocked IP addresses and open proxies.
 */
function wfIsAOLProxy( $ip ) {
	$ranges = array(
		'64.12.96.0/19',
		'149.174.160.0/20',
		'152.163.240.0/21',
		'152.163.248.0/22',
		'152.163.252.0/23',
		'152.163.96.0/22',
		'152.163.100.0/23',
		'195.93.32.0/22',
		'195.93.48.0/22',
		'195.93.64.0/19',
		'195.93.96.0/19',
		'195.93.16.0/20',
		'198.81.0.0/22',
		'198.81.16.0/20',
		'198.81.8.0/23',
		'202.67.64.128/25',
		'205.188.192.0/20',
		'205.188.208.0/23',
		'205.188.112.0/20',
		'205.188.146.144/30',
		'207.200.112.0/21',
	);

	static $parsedRanges;
	if ( is_null( $parsedRanges ) ) {
		$parsedRanges = array();
		foreach ( $ranges as $range ) {
			$parsedRanges[] =  IP::parseRange( $range );
		}
	}

	$hex = IP::toHex( $ip );
	foreach ( $parsedRanges as $range ) {
		if ( $hex >= $range[0] && $hex <= $range[1] ) {
			return true;
		}
	}
	return false;
}



?>
