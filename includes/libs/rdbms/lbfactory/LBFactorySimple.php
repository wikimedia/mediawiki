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
	private $extLBs = [];

	/** @var array[] Map of (server index => server config) */
	private $servers = [];
	/** @var array[] Map of (cluster => (server index => server config)) */
	private $externalClusters = [];

	/** @var string */
	private $loadMonitorClass;

	/**
	 * @see LBFactory::__construct()
	 * @param array $conf Parameters of LBFactory::__construct() as well as:
	 *   - servers : list of server configuration maps to Database::factory().
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

		$this->servers = $conf['servers'] ?? [];
		foreach ( $this->servers as $i => $server ) {
			if ( $i == 0 ) {
				$this->servers[$i]['master'] = true;
			} else {
				$this->servers[$i]['replica'] = true;
			}
		}

		$this->externalClusters = $conf['externalClusters'] ?? [];
		$this->loadMonitorClass = $conf['loadMonitorClass'] ?? 'LoadMonitor';
	}

	/**
	 * @param bool|string $domain
	 * @return LoadBalancer
	 */
	public function newMainLB( $domain = false ) {
		return $this->newLoadBalancer( $this->servers );
	}

	/**
	 * @param bool|string $domain
	 * @return LoadBalancer
	 */
	public function getMainLB( $domain = false ) {
		if ( !isset( $this->mainLB ) ) {
			$this->mainLB = $this->newMainLB( $domain );
		}

		return $this->mainLB;
	}

	public function newExternalLB( $cluster ) {
		if ( !isset( $this->externalClusters[$cluster] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": Unknown cluster \"$cluster\"." );
		}

		return $this->newLoadBalancer( $this->externalClusters[$cluster] );
	}

	public function getExternalLB( $cluster ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster );
		}

		return $this->extLBs[$cluster];
	}

	public function getAllMainLBs() {
		return [ 'DEFAULT' => $this->getMainLB() ];
	}

	public function getAllExternalLBs() {
		$lbs = [];
		foreach ( $this->externalClusters as $cluster => $unused ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	private function newLoadBalancer( array $servers ) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams(),
			[
				'servers' => $servers,
				'loadMonitor' => [ 'class' => $this->loadMonitorClass ],
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = [] ) {
		if ( isset( $this->mainLB ) ) {
			$callback( $this->mainLB, ...$params );
		}
		foreach ( $this->extLBs as $lb ) {
			$callback( $lb, ...$params );
		}
	}
}
