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
namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Manage a simple setup with one primary database and optionally some replicas.
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

	public function newMainLB( $domain = false ): ILoadBalancerForOwner {
		return $this->newLoadBalancer(
			self::CLUSTER_MAIN_DEFAULT,
			$this->mainServers
		);
	}

	public function getMainLB( $domain = false ): ILoadBalancer {
		if ( $this->mainLB === null ) {
			$this->mainLB = $this->newMainLB( $domain );
		}

		return $this->mainLB;
	}

	public function newExternalLB( $cluster ): ILoadBalancerForOwner {
		if ( !isset( $this->externalServersByCluster[$cluster] ) ) {
			throw new InvalidArgumentException( "Unknown cluster '$cluster'." );
		}

		return $this->newLoadBalancer(
			$cluster,
			$this->externalServersByCluster[$cluster]
		);
	}

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
		foreach ( array_keys( $this->externalServersByCluster ) as $cluster ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	private function newLoadBalancer( string $clusterName, array $servers ) {
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

	public function forEachLB( $callback, array $params = [] ) {
		wfDeprecated( __METHOD__, '1.39' );
		if ( $this->mainLB !== null ) {
			$callback( $this->mainLB, ...$params );
		}
		foreach ( $this->externalLBs as $lb ) {
			$callback( $lb, ...$params );
		}
	}

	protected function getLBsForOwner() {
		if ( $this->mainLB !== null ) {
			yield $this->mainLB;
		}
		foreach ( $this->externalLBs as $lb ) {
			yield $lb;
		}
	}
}
