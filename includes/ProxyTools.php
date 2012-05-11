<?php
/**
 * Functions for dealing with proxies.
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
 */

/**
 * Extracts the XFF string from the request header
 * Note: headers are spoofable
 *
 * @deprecated in 1.19; use $wgRequest->getHeader( 'X-Forwarded-For' ) instead.
 * @return string
 */
function wfGetForwardedFor() {
	wfDeprecated( __METHOD__, '1.19' );
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
	wfDeprecated( __METHOD__, '1.18' );
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
	wfDeprecated( __METHOD__, '1.19' );
	global $wgRequest;
	return $wgRequest->getIP();
}

/**
 * Checks if an IP is a trusted proxy providor.
 * Useful to tell if X-Fowarded-For data is possibly bogus.
 * Squid cache servers for the site are whitelisted.
 *
 * @param $ip String
 * @return bool
 */
function wfIsTrustedProxy( $ip ) {
	$trusted = wfIsConfiguredProxy( $ip );
	wfRunHooks( 'IsTrustedProxy', array( &$ip, &$trusted ) );
	return $trusted;
}

/**
 * Checks if an IP matches a proxy we've configured.
 * @param $ip String
 * @return bool
 */
function wfIsConfiguredProxy( $ip ) {
	global $wgSquidServers, $wgSquidServersNoPurge;
	$trusted = in_array( $ip, $wgSquidServers ) ||
		in_array( $ip, $wgSquidServersNoPurge );
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
		$url = wfExpandUrl( $title->getFullURL( 'ip='.$iphash ), PROTO_HTTP );

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
