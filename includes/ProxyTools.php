<?php
/**
 * Functions for dealing with proxies
 *
 * @file
 */

/**
 * Extracts the XFF string from the request header
 * Note: headers are spoofable
 *
 * @deprecated in 1.19; use $wgRequest->getHeader( 'X-Forwarded-For' ) instead.
 * @return string
 */
function wfGetForwardedFor() {
	global $wgRequest;
	return $wgRequest->getHeader( 'X-Forwarded-For' );
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
	global $wgRequest;
	return $wgRequest->getHeader( 'User-Agent' );
}

/**
 * Work out the IP address based on various globals
 * For trusted proxies, use the XFF client IP (first of the chain)
 *
 * @deprecated in 1.19; call $wgRequest->getIP() directly.
 * @return string
 */
function wfGetIP() {
	global $wgRequest;
	return $wgRequest->getIP();
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
	global $wgMemc, $wgProxyMemcExpiry, $wgRequest;
	global $wgProxyKey;

	if ( !$wgBlockOpenProxies ) {
		return;
	}

	$ip = $wgRequest->getIP();

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
