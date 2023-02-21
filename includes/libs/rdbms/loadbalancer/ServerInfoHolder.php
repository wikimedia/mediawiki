<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Holds dbs information
 * @internal
 * @ingroup Database
 */
class ServerInfoHolder {
	/**
	 * Default 'maxLag' when unspecified
	 * @internal Only for use within LoadBalancer/LoadMonitor
	 */
	public const MAX_LAG_DEFAULT = 6;

	public const WRITER_INDEX = 0;

	/** @var array[] Map of (server index => server config array) */
	private $servers;

	public function addServer( $i, $server ) {
		$this->servers[$i] = $server;
	}

	public function getServerMaxLag( $i ) {
		return $this->servers[$i]['max lag'] ?? self::MAX_LAG_DEFAULT;
	}

	public function getServerDriver( $i ) {
		return $this->servers[$i]['driver'] ?? null;
	}

	public function getServerType( $i ) {
		return $this->servers[$i]['type'] ?? 'unknown';
	}

	public function getServerName( $i ): string {
		return $this->servers[$i]['serverName'] ?? 'localhost';
	}

	public function getServerInfo( $i ) {
		return $this->servers[$i] ?? false;
	}

	public function getServerCount() {
		return count( $this->servers );
	}

	public function hasServerIndex( $i ) {
		return isset( $this->servers[$i] );
	}

	public function getLagTimes() {
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

	public function hasStreamingReplicaServers() {
		return (bool)$this->getStreamingReplicaIndexes();
	}

	public function reconfigureServers( $paramServers ) {
		if ( count( $paramServers ) == $this->getServerCount() ) {
			return false;
		}
		$newServers = [];
		foreach ( $paramServers as $i => $server ) {
			$newServers[] = $this->getServerNameFromConfig( $server );
		}

		$closeConnections = false;
		foreach ( $this->servers as $i => $server ) {
			if ( !in_array( $this->getServerNameFromConfig( $server ), $newServers ) ) {
				// db depooled, remove it from list of servers
				unset( $this->servers[$i] );
				$closeConnections = true;
			}
		}
		return $closeConnections;
	}

	public function getServerNameFromConfig( $config ) {
		$name = $config['serverName'] ?? ( $config['host'] ?? '' );
		return ( $name !== '' ) ? $name : 'localhost';
	}

	public function normalizeServerMaps( array $servers, array &$indexBySrvName = null ) {
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

	public function hasReplicaServers() {
		return ( $this->getServerCount() > 1 );
	}
}
