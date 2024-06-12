<?php

namespace MediaWiki\Settings\Source;

use DnsSrvDiscoverer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Settings\Cache\CacheableSource;
use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\Format\JsonFormat;
use Stringable;
use UnexpectedValueException;

/**
 * Settings loaded from an etcd server.
 *
 * @since 1.38
 */
class EtcdSource implements Stringable, CacheableSource {
	/**
	 * Default HTTP client connection and request timeout (2 seconds).
	 */
	private const TIMEOUT = 2;

	/**
	 * Cache expiry TTL for etcd sources (10 seconds).
	 *
	 * @see getExpiryTtl()
	 * @see CacheableSource::getExpiryTtl()
	 */
	private const EXPIRY_TTL = 10;

	/**
	 * Early expiry weight. This value influences the margin by which
	 * processes are selected to expire cached etcd settings early to avoid
	 * cache stampedes.
	 *
	 * @see getExpiryWeight()
	 * @see CacheableSource::getExpiryWeight()
	 */
	private const EXPIRY_WEIGHT = 1.0;

	/** @var Client */
	private $client;

	/** @var Uri */
	private $uri;

	/** @var callable */
	private $mapper;

	/** @var callable */
	private $resolver;

	/** @var JsonFormat */
	private $format;

	/**
	 * Constructs a new EtcdSource for the given etcd server details.
	 *
	 * @param array $params Parameter map:
	 *   - host: Etcd server host/domain. Note that an empty host value will
	 *     result in SRV discovery relative to the host's configured search
	 *     domain.
	 *   - port: Etcd server port. Defaults to 2379.
	 *   - protocol: Endpoint protocol (http/https). Defaults to 'https'.
	 *   - directory: Top level etcd directory to query for settings.
	 *   - discover: Whether to perform SRV discovery on the given
	 *     host/domain. Defaults to true.
	 *   - service: service name used in SRV discovery of the default
	 *     <code>$resolver</code>. Defaults to 'etcd-client-ssl' or
	 *     'etcd-client' when protocol is 'https' or 'http' respectively.
	 * @param ?callable $mapper Function that maps etcd entries to valid
	 *  MediaWiki config/schema/php-ini values. Defaults to simply returning
	 *  the structure stored in etcd.
	 *  Signature: function ( array $settings ): array
	 * @param ?Client $client Guzzle HTTP client used to query etcd.
	 * @param ?callable $resolver Function that must return an array of server
	 *  hostname/port pairs to try. The default resolver will either:
	 *   - use an explicitly given hostname/port if both are provided
	 *   - otherwise attempt DNS SRV discovery at <code>_etcd._tcp.$host</code>
	 *   - fallback to using the host as the etcd server directly
	 *  Signature: function (): array
	 *
	 * @throws SettingsBuilderException if the given host is invalid.
	 */
	public function __construct(
		array $params = [],
		?callable $mapper = null,
		?Client $client = null,
		?callable $resolver = null
	) {
		$params += [
			'host' => '',
			'port' => 2379,
			'protocol' => 'https',
			'directory' => 'mediawiki',
			'discover' => true,
			'service' => null,
		];

		$service =
			$params['service'] ??
			$params['protocol'] == 'https'
			? 'etcd-client-ssl'
			: 'etcd-client';

		$this->mapper = $mapper ?? static function ( $settings ) {
			return $settings;
		};

		$this->client = $client ?? new Client( [
			'timeout' => self::TIMEOUT,
			'connect_timeout' => self::TIMEOUT,
		] );

		$this->uri = ( new Uri() )
			->withHost( $params['host'] )
			->withPort( $params['port'] )
			->withPath( '/v2/keys/' . trim( $params['directory'], '/' ) . '/' )
			->withScheme( $params['protocol'] )
			->withQuery( 'recursive=true' );

		if ( $resolver !== null ) {
			$this->resolver = $resolver;
		} elseif ( $params['discover'] ) {
			$discoverer = new DnsSrvDiscoverer( $service, 'tcp', $params['host'] );
			$this->uri = $this->uri->withHost( $discoverer->getSrvName() )->withPort( null );
			$this->resolver = static function () use ( $discoverer ) {
				return $discoverer->getServers();
			};
		} else {
			$this->resolver = static function () use ( $params ) {
				return [ [ $params['host'], $params['port'] ] ];
			};
		}

		$this->format = new JsonFormat();
	}

	/**
	 * Allow stale results from etcd sources in case all servers become
	 * temporarily unavailable.
	 *
	 * @return bool
	 */
	public function allowsStaleLoad(): bool {
		return true;
	}

	/**
	 * Loads and returns settings from the etcd server.
	 *
	 * @throws SettingsBuilderException
	 * @return array
	 */
	public function load(): array {
		$lastException = false;

		foreach ( ( $this->resolver )() as [ $host, $port ] ) {
			try {
				return $this->loadFromEtcdServer( $host, $port );
			} catch ( ConnectException | ServerException $e ) {
				$lastException = $e;
			}
		}

		throw new SettingsBuilderException(
			'failed to load settings from etcd source: {source}: {message}',
			[
				'source' => $this,
				'message' => $lastException ? $lastException->getMessage() : '',
			]
		);
	}

	/**
	 * The cache expiry TTL (in seconds) for this source.
	 *
	 * @return int
	 */
	public function getExpiryTtl(): int {
		return self::EXPIRY_TTL;
	}

	/**
	 * Coefficient used in determining early expiration of cached settings to
	 * avoid stampedes.
	 *
	 * @return float
	 */
	public function getExpiryWeight(): float {
		return self::EXPIRY_WEIGHT;
	}

	/**
	 * Returns a naive hash key for use in caching based on an etcd request
	 * URL constructed using the etcd request URL. In the case where SRV
	 * discovery is performed, the host in the URL will be the SRV record
	 * name.
	 *
	 * @return string
	 */
	public function getHashKey(): string {
		return (string)$this->uri;
	}

	/**
	 * Returns this etcd source as a string.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return (string)$this->uri;
	}

	/**
	 * @param string $host
	 * @param int $port
	 *
	 * @throws SettingsBuilderException
	 * @return array
	 */
	private function loadFromEtcdServer( string $host, int $port ): array {
		$uri = $this->uri->withHost( $host )->withPort( $port );

		try {
			$response = $this->client->get( $uri, [ 'http_errors' => true ] );
		} catch ( ClientException $e ) {
			throw new SettingsBuilderException(
				'bad request made to etcd server: {message}: uri {uri}',
				[ 'message' => $e->getMessage(), 'uri' => $uri ]
			);
		}

		$settings = [];

		try {
			$resp = $this->format->decode( $response->getBody()->getContents() );

			if (
				!isset( $resp['node'] ) || !is_array( $resp['node'] )
				|| !isset( $resp['node']['dir'] ) || !$resp['node']['dir']
			) {
				throw new SettingsBuilderException(
					'etcd request to {uri} did not return a valid directory node',
					[ 'uri' => $uri ]
				);
			}

			$this->parseDirectory(
				$resp['node'],
				strlen( $resp['node']['key'] ) + 1,
				$settings
			);
		} catch ( UnexpectedValueException $e ) {
			throw new SettingsBuilderException(
				'failed to parse etcd response body: {message}',
				[ 'message' => $e->getMessage() ]
			);
		}

		return ( $this->mapper )( $settings );
	}

	/**
	 * @param array $dir Directory node.
	 * @param int $prefix Length of the directory prefix to remove.
	 * @param array &$settings Flattened settings array to which to write.
	 */
	private function parseDirectory( array $dir, int $prefix, array &$settings ) {
		foreach ( $dir['nodes'] as $node ) {
			if ( isset( $node['dir'] ) && $node['dir'] ) {
				$this->parseDirectory( $node, $prefix, $settings );
			} else {
				$key = substr( $node['key'], $prefix );
				$value = $this->format->decode( $node['value'] );
				$settings[$key] = $value['val'];
			}
		}
	}
}
