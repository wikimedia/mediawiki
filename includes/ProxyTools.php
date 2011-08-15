<?php
/**
 * Functions for dealing with proxies
 *
 * @file
 */

/**
 * Extracts the XFF string from the request header
 * Note: headers are spoofable
 * @return string
 */
function wfGetForwardedFor() {
	$apacheHeaders = function_exists( 'apache_request_headers' ) ? apache_request_headers() : null;
	if( is_array( $apacheHeaders ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = array();
		foreach ( $apacheHeaders as $tempName => $tempValue ) {
			$set[ strtoupper( $tempName ) ] = $tempValue;
		}
		$index = strtoupper ( 'X-Forwarded-For' );
	} else {
		// Subject to spoofing with headers like X_Forwarded_For
		$set = $_SERVER;
		$index = 'HTTP_X_FORWARDED_FOR';
	}

	#Try to see if XFF is set
	if( isset( $set[$index] ) ) {
		return $set[$index];
	} else {
		return null;
	}
}

/**
 * Returns the browser/OS data from the request header
 * Note: headers are spoofable
 *
 * @deprecated in 1.18; use $wgRequest->getHeader( 'User-Agent' ) instead.
 * @return string
 */
function wfGetAgent() {
	wfDeprecated( __FUNCTION__ );
	if( function_exists( 'apache_request_headers' ) ) {
		// More reliable than $_SERVER due to case and -/_ folding
		$set = array();
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

	/* collect the originating ips */
	# Client connecting to this webserver
	if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = IP::canonicalize( $_SERVER['REMOTE_ADDR'] );
	} elseif( $wgCommandLineMode ) {
		$ip = '127.0.0.1';
	}

	# Append XFF
	$forwardedFor = wfGetForwardedFor();
	if ( $forwardedFor !== null ) {
		$ipchain = array_map( 'trim', explode( ',', $forwardedFor ) );
		$ipchain = array_reverse( $ipchain );
		if ( $ip ) {
			array_unshift( $ipchain, $ip );
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

	$trusted = in_array( $ip, $wgSquidServers ) ||
		in_array( $ip, $wgSquidServersNoPurge );
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
