<?php

/**
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

use IPSet\IPSet;

/**
 * @since 1.28
 */
class ProxyLookup {

	/**
	 * @var string[]
	 */
	private $proxyServers;

	/**
	 * @var string[]
	 */
	private $proxyServersComplex;

	/**
	 * @var IPSet|null
	 */
	private $proxyIPSet;

	/**
	 * @param string[] $proxyServers Simple list of IPs
	 * @param string[] $proxyServersComplex Complex list of IPs/ranges
	 */
	public function __construct( $proxyServers, $proxyServersComplex ) {
		$this->proxyServers = $proxyServers;
		$this->proxyServersComplex = $proxyServersComplex;
	}

	/**
	 * Checks if an IP matches a proxy we've configured
	 *
	 * @param string $ip
	 * @return bool
	 */
	public function isConfiguredProxy( $ip ) {
		global $wgSquidServers, $wgSquidServersNoPurge;

		// Quick check of known singular proxy servers
		$trusted = in_array( $ip, $wgSquidServers );

		// Check known singular proxy servers with ports
		if ( !$trusted ) {
			$ipWithColon = $ip . ':';
			$ipWithColonLen = strlen( $ipWithColon );
			foreach ( $wgSquidServers as $server ) {
				if ( !strncmp( $ipWithColon, $server, $ipWithColonLen ) ) {
					$trusted = true;
					break;
				}
			}
		}

		// Check against addresses and CIDR nets in the NoPurge list
		if ( !$trusted ) {
			if ( !self::$proxyIpSet ) {
				self::$proxyIpSet = new IPSet( $wgSquidServersNoPurge );
			}
			$trusted = self::$proxyIpSet->match( $ip );
		}

		return $trusted;
	}

	/**
	 * Checks if an IP is a trusted proxy provider.
	 * Useful to tell if X-Forwarded-For data is possibly bogus.
	 * CDN cache servers for the site are whitelisted.
	 *
	 * @param string $ip
	 * @return bool
	 */
	public function isTrustedProxy( $ip ) {
		$trusted = self::isConfiguredProxy( $ip );
		Hooks::run( 'IsTrustedProxy', [ &$ip, &$trusted ] );
		return $trusted;
	}

	/**
	 * Clears precomputed data used for proxy support.
	 * Use this only for unit tests.
	 */
	public static function clearCaches() {
		self::$proxyIpSet = null;
	}
}
