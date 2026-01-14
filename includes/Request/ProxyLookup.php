<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Request;

use BagOStuff;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use Wikimedia\IPSet;

/**
 * @since 1.28
 */
class ProxyLookup {

	/** @var string[] */
	private $proxyServers;

	/** @var string[] */
	private $proxyServersComplex;

	/** @var IPSet|null */
	private $proxyIPSet;

	/** @var HookRunner */
	private $hookRunner;

	/** @var BagOStuff */
	private $cache;

	/**
	 * @param string[] $proxyServers Simple list of IPs
	 * @param string[] $proxyServersComplex Complex list of IPs/ranges
	 * @param HookContainer $hookContainer
	 * @param BagOStuff $cache In-process cache for the IPSet object
	 */
	public function __construct(
		$proxyServers,
		$proxyServersComplex,
		HookContainer $hookContainer,
		BagOStuff $cache
	) {
		$this->proxyServers = $proxyServers;
		$this->proxyServersComplex = $proxyServersComplex;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->cache = $cache;
	}

	/**
	 * Get or create the IPSet object, using cache if available
	 *
	 * @return IPSet
	 */
	private function getIPSet() {
		if ( $this->proxyIPSet ) {
			return $this->proxyIPSet;
		}

		$this->proxyIPSet = $this->cache->getWithSetCallback(
			$this->cache->makeGlobalKey( 'ProxyLookup', 'ipset', crc32( json_encode( $this->proxyServersComplex ) ) ),
			BagOStuff::TTL_INDEFINITE,
			function () {
				return new IPSet( $this->proxyServersComplex );
			}
		);

		return $this->proxyIPSet;
	}

	/**
	 * Checks if an IP matches a proxy we've configured
	 *
	 * @param string $ip
	 * @return bool
	 */
	public function isConfiguredProxy( $ip ) {
		// Quick check of known singular proxy servers
		if ( in_array( $ip, $this->proxyServers, true ) ) {
			return true;
		}

		// Check against addresses and CIDR nets in the complex list
		return $this->getIPSet()->match( $ip );
	}

	/**
	 * Checks if an IP is a trusted proxy provider.
	 * Useful to tell if X-Forwarded-For data is possibly bogus.
	 * CDN cache servers for the site are allowed.
	 *
	 * @param string $ip
	 * @return bool
	 */
	public function isTrustedProxy( $ip ) {
		$trusted = $this->isConfiguredProxy( $ip );
		$this->hookRunner->onIsTrustedProxy( $ip, $trusted );
		return $trusted;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ProxyLookup::class, 'ProxyLookup' );
