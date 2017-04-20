<?php
/**
 * Copyright 2017
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
 * @author Aaron Schulz
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\WaitConditionLoop;

/**
 * Interface for configuration instances
 *
 * @since 1.29
 */
class EtcdConfig implements Config, LoggerAwareInterface {
	/** @var MultiHttpClient */
	private $http;
	/** @var BagOStuff */
	private $srvCache;
	/** @var array */
	private $procCache;
	/** @var LoggerInterface */
	private $logger;

	/** @var string */
	private $host;
	/** @var string */
	private $protocol;
	/** @var string */
	private $directory;
	/** @var string */
	private $encoding;
	/** @var integer */
	private $baseCacheTTL;
	/** @var integer */
	private $skewCacheTTL;
	/** @var integer */
	private $timeout;
	/** @var string */
	private $directoryHash;

	/**
	 * @param array $params Parameter map:
	 *   - host: the host address and port
	 *   - protocol: either http or https
	 *   - directory: the etc "directory" were MediaWiki specific variables are located
	 *   - encoding: one of ("JSON", "YAML"). Defaults to JSON. [optional]
	 *   - cache: BagOStuff instance or ObjectFactory spec thereof for a server cache.
	 *            The cache will also be used as a fallback if etcd is down. [optional]
	 *   - cacheTTL: logical cache TTL in seconds [optional]
	 *   - skewTTL: maximum seconds to randomly lower the assigned TTL on cache save [optional]
	 *   - timeout: seconds to wait for etcd before throwing an error [optional]
	 */
	public function __construct( array $params ) {
		$params += [
			'protocol' => 'http',
			'encoding' => 'JSON',
			'cacheTTL' => 10,
			'skewTTL' => 1,
			'timeout' => 10
		];

		$this->host = $params['host'];
		$this->protocol = $params['protocol'];
		$this->directory = trim( $params['directory'], '/' );
		$this->directoryHash = sha1( $this->directory );
		$this->encoding = $params['encoding'];
		$this->skewCacheTTL = $params['skewTTL'];
		$this->baseCacheTTL = max( $params['cacheTTL'] - $this->skewCacheTTL, 0 );
		$this->timeout = $params['timeout'];

		if ( !isset( $params['cache'] ) ) {
			$this->srvCache = new HashBagOStuff( [] );
		} elseif ( $params['cache'] instanceof BagOStuff ) {
			$this->srvCache = $params['cache'];
		} else {
			$this->srvCache = ObjectFactory::getObjectFromSpec( $params['cache'] );
		}

		$this->logger = new Psr\Log\NullLogger();
		$this->http = new MultiHttpClient( [
			'connTimeout' => $this->timeout,
			'reqTimeout' => $this->timeout
		] );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function has( $name ) {
		$this->load();

		return array_key_exists( $name, $this->procCache['config'] );
	}

	public function get( $name ) {
		$this->load();

		if ( !array_key_exists( $name, $this->procCache['config'] ) ) {
			throw new ConfigException( "No entry found for '$name'." );
		}

		return $this->procCache['config'][$name];
	}

	private function load() {
		if ( $this->procCache !== null ) {
			return; // already loaded
		}

		$now = microtime( true );
		$key = $this->srvCache->makeKey( 'variable', $this->directoryHash );

		// Get the cached value or block until it is regenerated (by this or another thread)...
		$data = null; // latest config info
		$error = null; // last error message
		$loop = new WaitConditionLoop(
			function () use ( $key, $now, &$data, &$error ) {
				// Check if the values are in cache yet...
				$data = $this->srvCache->get( $key );
				if ( is_array( $data ) && $data['expires'] > $now ) {
					$this->logger->debug( "Found up-to-date etcd configuration cache." );

					return WaitConditionLoop::CONDITION_REACHED;
				}

				// Cache is either empty or stale;
				// refresh the cache from etcd, using a mutex to reduce stampedes...
				if ( $this->srvCache->lock( $key, 0, $this->baseCacheTTL ) ) {
					try {
						list( $config, $error, $retry ) = $this->fetchAllFromEtcd();
						if ( $config === null ) {
							$this->logger->error( "Failed to fetch configuration: $error" );
							// Fail fast if the error is likely to just keep happening
							return $retry
								? WaitConditionLoop::CONDITION_CONTINUE
								: WaitConditionLoop::CONDITION_FAILED;
						}

						// Avoid having all servers expire cache keys at the same time
						$expiry = microtime( true ) + $this->baseCacheTTL;
						$expiry += mt_rand( 0, 1e6 ) / 1e6 * $this->skewCacheTTL;

						$data = [ 'config' => $config, 'expires' => $expiry ];
						$this->srvCache->set( $key, $data, BagOStuff::TTL_INDEFINITE );

						$this->logger->info( "Refreshed stale etcd configuration cache." );

						return WaitConditionLoop::CONDITION_REACHED;
					} finally {
						$this->srvCache->unlock( $key ); // release mutex
					}
				}

				if ( is_array( $data ) ) {
					$this->logger->info( "Using stale etcd configuration cache." );

					return WaitConditionLoop::CONDITION_REACHED;
				}

				return WaitConditionLoop::CONDITION_CONTINUE;
			},
			$this->timeout
		);

		if ( $loop->invoke() !== WaitConditionLoop::CONDITION_REACHED ) {
			// No cached value exists and etcd query failed; throw an error
			throw new ConfigException( "Failed to load configuration from etcd: $error" );
		}

		$this->procCache = $data;
	}

	/**
	 * @return array (config array or null, error string, allow retries)
	 */
	public function fetchAllFromEtcd() {
		$dsd = new DnsSrvDiscoverer( $this->host );
		$servers = $dsd->getServers();
		if ( !$servers ) {
			return $this->fetchAllFromEtcdServer( $this->host );
		}

		do {
			// Pick a random etcd server from dns
			$server = $dsd->pickServer( $servers );
			$host = IP::combineHostAndPort( $server['target'], $server['port'] );
			// Try to load the config from this particular server
			list( $config, $error, $retry ) = $this->fetchAllFromEtcdServer( $host );
			if ( is_array( $config ) || !$retry ) {
				break;
			}

			// Avoid the server next time if that failed
			$dsd->removeServer( $server, $servers );
		} while ( $servers );

		return [ $config, $error, $retry ];
	}

	/**
	 * @param string $address Host and port
	 * @return array (config array or null, error string, whether to allow retries)
	 */
	protected function fetchAllFromEtcdServer( $address ) {
		// Retrieve all the values under the MediaWiki config directory
		list( $rcode, $rdesc, /* $rhdrs */, $rbody, $rerr ) = $this->http->run( [
			'method' => 'GET',
			'url' => "{$this->protocol}://{$address}/v2/keys/{$this->directory}/",
			'headers' => [ 'content-type' => 'application/json' ]
		] );

		static $terminalCodes = [ 404 => true ];
		if ( $rcode < 200 || $rcode > 399 ) {
			return [
				null,
				strlen( $rerr ) ? $rerr : "HTTP $rcode ($rdesc)",
				empty( $terminalCodes[$rcode] )
			];
		}

		$info = json_decode( $rbody, true );
		if ( $info === null || !isset( $info['node']['nodes'] ) ) {
			return [ null, $rcode, "Unexpected JSON response; missing 'nodes' list.", false ];
		}

		$config = [];
		foreach ( $info['node']['nodes'] as $node ) {
			if ( !empty( $node['dir'] ) ) {
				continue; // skip directories
			}

			$name = basename( $node['key'] );
			$value = $this->unserialize( $node['value'] );
			if ( !is_array( $value ) || !isset( $value['val'] ) ) {
				return [ null, "Failed to parse value for '$name'.", false ];
			}

			$config[$name] = $value['val'];
		}

		return [ $config, null, false ];
	}

	/**
	 * @param string $string
	 * @return mixed
	 */
	private function unserialize( $string ) {
		if ( $this->encoding === 'YAML' ) {
			return yaml_parse( $string );
		} else { // JSON
			return json_decode( $string, true );
		}
	}
}
