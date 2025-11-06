<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * LoadBalancer manager for sites with one "main" cluster and any number of "external" clusters
 *
 * @see LBFactoryMulti
 *
 * The class allows for large site farms to split up their data in the following ways:
 *   - Vertically shard compact site-specific data by site (e.g. page/comment metadata)
 *   - Vertically shard compact global data by module (e.g. account/notification data)
 *   - Horizontally shard any bulk data by blob key (e.g. page/comment content)
 *
 * @ingroup Database
 */
class LBFactorySimple extends LBFactory {
	/** @var LoadBalancer */
	private $mainLB;
	/** @var LoadBalancer[] */
	private $externalLBs = [];

	/** @var array Configuration for the LoadMonitor to use within LoadBalancer instances */
	private $loadMonitorConfig;

	/** @var array[] Map of (server index => server config map) */
	private $mainServers;
	/** @var array[][] Map of (cluster => server index => server config map) */
	private $externalServersByCluster = [];

	/**
	 * @see LBFactory::__construct()
	 * @param array $conf Additional parameters include:
	 *   - servers : list of server config maps to Database::factory().
	 *      Additionally, the server maps should have a 'load' key, which is used to decide
	 *      how often clients connect to one server verses the others. A 'max lag' key should
	 *      also be set on server maps, indicating how stale the data can be before the load
	 *      balancer tries to avoid using it. The map can have 'is static' set to disable blocking
	 *      replication sync checks (intended for archive servers with unchanging data).
	 *   - externalClusters : map of cluster names to server arrays. The servers arrays have the
	 *      same format as "servers" above.
	 *   - loadMonitor: LoadMonitor::__construct() parameters with "class" field. [optional]
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->mainServers = $conf['servers'] ?? [];
		foreach ( ( $conf['externalClusters'] ?? [] ) as $cluster => $servers ) {
			foreach ( $servers as $index => $server ) {
				$this->externalServersByCluster[$cluster][$index] = $server;
			}
		}

		if ( isset( $conf['loadMonitor'] ) ) {
			$this->loadMonitorConfig = $conf['loadMonitor'];
		} elseif ( isset( $conf['loadMonitorClass'] ) ) { // b/c
			$this->loadMonitorConfig = [ 'class' => $conf['loadMonitorClass'] ];
		} else {
			$this->loadMonitorConfig = [ 'class' => LoadMonitor::class ];
		}
	}

	/** @inheritDoc */
	public function newMainLB( $domain = false ): ILoadBalancerForOwner {
		return $this->newLoadBalancer(
			self::CLUSTER_MAIN_DEFAULT,
			$this->mainServers
		);
	}

	/** @inheritDoc */
	public function getMainLB( $domain = false ): ILoadBalancer {
		$this->mainLB ??= $this->newMainLB( $domain );

		return $this->mainLB;
	}

	/** @inheritDoc */
	public function newExternalLB( $cluster ): ILoadBalancerForOwner {
		if ( !isset( $this->externalServersByCluster[$cluster] ) ) {
			throw new InvalidArgumentException( "Unknown cluster '$cluster'." );
		}

		return $this->newLoadBalancer(
			$cluster,
			$this->externalServersByCluster[$cluster]
		);
	}

	/** @inheritDoc */
	public function getExternalLB( $cluster ): ILoadBalancer {
		if ( !isset( $this->externalLBs[$cluster] ) ) {
			$this->externalLBs[$cluster] = $this->newExternalLB( $cluster );
		}

		return $this->externalLBs[$cluster];
	}

	public function getAllMainLBs(): array {
		return [ self::CLUSTER_MAIN_DEFAULT => $this->getMainLB() ];
	}

	public function getAllExternalLBs(): array {
		$lbs = [];
		foreach ( $this->externalServersByCluster as $cluster => $_ ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	private function newLoadBalancer( string $clusterName, array $servers ): ILoadBalancerForOwner {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams(),
			[
				'servers' => $servers,
				'loadMonitor' => $this->loadMonitorConfig,
				'clusterName' => $clusterName
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	/** @inheritDoc */
	protected function getLBsForOwner() {
		if ( $this->mainLB !== null ) {
			yield $this->mainLB;
		}
		foreach ( $this->externalLBs as $lb ) {
			yield $lb;
		}
	}
}
