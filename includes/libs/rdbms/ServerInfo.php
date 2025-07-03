<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Container for accessing information about the database servers in a database cluster
 *
 * @internal
 * @ingroup Database
 */
class ServerInfo {
	/**
	 * Default 'maxLag' when unspecified
	 * @internal Only for use within LoadBalancer/LoadMonitor
	 */
	public const MAX_LAG_DEFAULT = 6;

	public const WRITER_INDEX = 0;

	/** @var array[] Map of (server index => server config array) */
	private $servers;

	public function addServer( int $i, array $server ) {
		$this->servers[$i] = $server;
	}

	public function getServerMaxLag( int $i ): int {
		return $this->servers[$i]['max lag'] ?? self::MAX_LAG_DEFAULT;
	}

	public function getServerDriver( int $i ): ?string {
		return $this->servers[$i]['driver'] ?? null;
	}

	public function getServerType( int $i ): string {
		return $this->servers[$i]['type'] ?? 'unknown';
	}

	public function getServerName( int $i ): string {
		return $this->servers[$i]['serverName'] ?? 'localhost';
	}

	public function getServerInfo( int $i ): array|false {
		return $this->servers[$i] ?? false;
	}

	public function getServerCount(): int {
		return count( $this->servers );
	}

	public function hasServerIndex( int $i ): bool {
		return isset( $this->servers[$i] );
	}

	public function getLagTimes(): array {
		$knownLagTimes = []; // map of (server index => 0 seconds)
		$indexesWithLag = [];
		foreach ( $this->servers as $i => $server ) {
			if ( empty( $server['is static'] ) ) {
				$indexesWithLag[] = $i; // DB server might have replication lag
			} else {
				$knownLagTimes[$i] = 0; // DB server is a non-replicating and read-only archive
			}
		}

		return [ $indexesWithLag, $knownLagTimes ];
	}

	/**
	 * @param int $i Server index
	 * @param string|null $field Server index field [optional]
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function getServerInfoStrict( $i, $field = null ) {
		if ( !isset( $this->servers[$i] ) || !is_array( $this->servers[$i] ) ) {
			throw new InvalidArgumentException( "No server with index '$i'" );
		}

		if ( $field !== null ) {
			if ( !array_key_exists( $field, $this->servers[$i] ) ) {
				throw new InvalidArgumentException( "No field '$field' in server index '$i'" );
			}

			return $this->servers[$i][$field];
		}

		return $this->servers[$i];
	}

	/**
	 * @return int[] List of replica server indexes
	 */
	public function getStreamingReplicaIndexes() {
		$indexes = [];
		foreach ( $this->servers as $i => $server ) {
			if ( $i !== self::WRITER_INDEX && empty( $server['is static'] ) ) {
				$indexes[] = $i;
			}
		}

		return $indexes;
	}

	public function hasStreamingReplicaServers(): bool {
		return (bool)$this->getStreamingReplicaIndexes();
	}

	public function reconfigureServers( array $paramServers ): array {
		$newIndexBySrvName = [];
		$this->normalizeServerMaps( $paramServers, $newIndexBySrvName );

		// Map of (existing server index => corresponding index in new config or null)
		$newIndexByServerIndex = [];
		// Remove servers that no longer exist in the new config and preserve those that
		// still exist, even if they switched replication roles (e.g. primary/secondary).
		// Note that if the primary server is depooled and a replica server is promoted
		// to primary, then DB_PRIMARY handles will fail with server index errors. Note
		// that if the primary server swaps roles with a replica server, then write queries
		// to DB_PRIMARY handles will fail with read-only errors.
		foreach ( $this->servers as $i => $server ) {
			$srvName = $this->getServerName( $i );
			// Since pooling or depooling of servers causes the remaining servers to be
			// assigned different indexes, find the corresponding index by server name.
			// Also, note that the primary can be reconfigured as a replica (moved from
			// the writer index) and vice versa (moved to the writer index).
			$newIndex = $newIndexByServerIndex[$i] = $newIndexBySrvName[$srvName] ?? null;
			if ( $newIndex === null ) {
				unset( $this->servers[$i] );
			}
		}

		return $newIndexByServerIndex;
	}

	public function normalizeServerMaps( array $servers, ?array &$indexBySrvName = null ): array {
		if ( !$servers ) {
			throw new InvalidArgumentException( 'Missing or empty "servers" parameter' );
		}

		$listKey = -1;
		$indexBySrvName = [];
		foreach ( $servers as $i => $server ) {
			if ( ++$listKey !== $i ) {
				throw new UnexpectedValueException( 'List expected for "servers" parameter' );
			}
			$srvName = $server['serverName'] ?? $server['host'] ?? '';
			$srvName = ( $srvName !== '' ) ? $srvName : 'localhost';
			if ( isset( $indexBySrvName[$srvName] ) ) {
				// Duplicate server names confuse caching, logging, and reconfigure()
				throw new UnexpectedValueException( 'Duplicate server name "' . $srvName . '"' );
			}
			$indexBySrvName[$srvName] = $i;
			$servers[$i]['serverName'] = $srvName;
			$servers[$i]['groupLoads'] ??= [];
		}
		return $servers;
	}

	/**
	 * @return string Name of the primary DB server of the relevant DB cluster (e.g. "db1052")
	 */
	public function getPrimaryServerName() {
		return $this->getServerName( self::WRITER_INDEX );
	}

	public function hasReplicaServers(): bool {
		return ( $this->getServerCount() > 1 );
	}
}
