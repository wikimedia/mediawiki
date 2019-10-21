<?php
/**
 * Generator of database load balancing objects.
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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * A simple single-master LBFactory that gets its configuration from the b/c globals
 */
class LBFactorySimple extends LBFactory {
	/** @var LoadBalancer */
	private $mainLB;
	/** @var LoadBalancer[] */
	private $externalLBs = [];

	/** @var array[] Map of (server index => server config map) */
	private $mainServers = [];
	/** @var array[][] Map of (cluster => server index => server config map) */
	private $externalServersByCluster = [];

	/** @var string */
	private $loadMonitorClass;

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
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->mainServers = $conf['servers'] ?? [];
		foreach ( $this->mainServers as $i => $server ) {
			if ( $i == 0 ) {
				$this->mainServers[$i]['master'] = true;
			} else {
				$this->mainServers[$i]['replica'] = true;
			}
		}

		foreach ( ( $conf['externalClusters'] ?? [] ) as $cluster => $servers ) {
			foreach ( $servers as $index => $server ) {
				$this->externalServersByCluster[$cluster][$index] = $server;
			}
		}

		$this->loadMonitorClass = $conf['loadMonitorClass'] ?? LoadMonitor::class;
	}

	public function newMainLB( $domain = false, $owner = null ) {
		return $this->newLoadBalancer( $this->mainServers, $owner );
	}

	public function getMainLB( $domain = false ) {
		if ( $this->mainLB === null ) {
			$this->mainLB = $this->newMainLB( $domain, $this->getOwnershipId() );
		}

		return $this->mainLB;
	}

	public function newExternalLB( $cluster, $owner = null ) {
		if ( !isset( $this->externalServersByCluster[$cluster] ) ) {
			throw new InvalidArgumentException( "Unknown cluster '$cluster'." );
		}

		return $this->newLoadBalancer( $this->externalServersByCluster[$cluster], $owner );
	}

	public function getExternalLB( $cluster ) {
		if ( !isset( $this->externalLBs[$cluster] ) ) {
			$this->externalLBs[$cluster] = $this->newExternalLB( $cluster, $this->getOwnershipId() );
		}

		return $this->externalLBs[$cluster];
	}

	public function getAllMainLBs() {
		return [ self::CLUSTER_MAIN_DEFAULT => $this->getMainLB() ];
	}

	public function getAllExternalLBs() {
		$lbs = [];
		foreach ( array_keys( $this->externalServersByCluster ) as $cluster ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	private function newLoadBalancer( array $servers, $owner ) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams( $owner ),
			[
				'servers' => $servers,
				'loadMonitor' => [ 'class' => $this->loadMonitorClass ],
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	public function forEachLB( $callback, array $params = [] ) {
		if ( $this->mainLB !== null ) {
			$callback( $this->mainLB, ...$params );
		}
		foreach ( $this->externalLBs as $lb ) {
			$callback( $lb, ...$params );
		}
	}
}
