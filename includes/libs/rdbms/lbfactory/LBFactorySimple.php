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

/**
 * A simple single-master LBFactory that gets its configuration from the b/c globals
 */
class LBFactorySimple extends LBFactoryMW {
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

	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->servers = isset( $conf['servers'] ) ? $conf['servers'] : [];
		foreach ( $this->servers as $i => $server ) {
			if ( $i == 0 ) {
				$this->servers[$i]['master'] = true;
			} else {
				$this->servers[$i]['replica'] = true;
			}
		}

		$this->externalClusters = isset( $conf['externalClusters'] )
			? $conf['externalClusters']
			: [];
		$this->loadMonitorClass = isset( $conf['loadMonitorClass'] )
			? $conf['loadMonitorClass']
			: null;
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
			$this->getChronologyProtector()->initLB( $this->mainLB );
		}

		return $this->mainLB;
	}

	/**
	 * @param string $cluster
	 * @param bool|string $domain
	 * @return LoadBalancer
	 * @throws InvalidArgumentException
	 */
	protected function newExternalLB( $cluster, $domain = false ) {
		if ( !isset( $this->externalClusters[$cluster] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}

		return $this->newLoadBalancer( $this->externalClusters[$cluster] );
	}

	/**
	 * @param string $cluster
	 * @param bool|string $domain
	 * @return array
	 */
	public function getExternalLB( $cluster, $domain = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $domain );
			$this->getChronologyProtector()->initLB( $this->extLBs[$cluster] );
		}

		return $this->extLBs[$cluster];
	}

	private function newLoadBalancer( array $servers ) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams(),
			[
				'servers' => $servers,
				'loadMonitor' => $this->loadMonitorClass,
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
			call_user_func_array( $callback, array_merge( [ $this->mainLB ], $params ) );
		}
		foreach ( $this->extLBs as $lb ) {
			call_user_func_array( $callback, array_merge( [ $lb ], $params ) );
		}
	}
}
