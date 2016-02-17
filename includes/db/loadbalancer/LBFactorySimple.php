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
class LBFactorySimple extends LBFactory {
	/** @var LoadBalancer */
	private $mainLB;
	/** @var LoadBalancer[] */
	private $extLBs = [];

	/** @var string */
	private $loadMonitorClass;

	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->loadMonitorClass = isset( $conf['loadMonitorClass'] )
			? $conf['loadMonitorClass']
			: null;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function newMainLB( $wiki = false ) {
		global $wgDBservers;

		if ( is_array( $wgDBservers ) ) {
			$servers = $wgDBservers;
			foreach ( $servers as $i => &$server ) {
				if ( $i == 0 ) {
					$server['master'] = true;
				} else {
					$server['slave'] = true;
				}
			}
		} else {
			global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBtype, $wgDebugDumpSql;
			global $wgDBssl, $wgDBcompress;

			$flags = DBO_DEFAULT;
			if ( $wgDebugDumpSql ) {
				$flags |= DBO_DEBUG;
			}
			if ( $wgDBssl ) {
				$flags |= DBO_SSL;
			}
			if ( $wgDBcompress ) {
				$flags |= DBO_COMPRESS;
			}

			$servers = [ [
				'host' => $wgDBserver,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'dbname' => $wgDBname,
				'type' => $wgDBtype,
				'load' => 1,
				'flags' => $flags,
				'master' => true
			] ];
		}

		return new LoadBalancer( [
			'servers' => $servers,
			'loadMonitor' => $this->loadMonitorClass,
			'readOnlyReason' => $this->readOnlyReason,
			'trxProfiler' => $this->trxProfiler
		] );
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function getMainLB( $wiki = false ) {
		if ( !isset( $this->mainLB ) ) {
			$this->mainLB = $this->newMainLB( $wiki );
			$this->mainLB->parentInfo( [ 'id' => 'main' ] );
			$this->chronProt->initLB( $this->mainLB );
		}

		return $this->mainLB;
	}

	/**
	 * @throws MWException
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	protected function newExternalLB( $cluster, $wiki = false ) {
		global $wgExternalServers;
		if ( !isset( $wgExternalServers[$cluster] ) ) {
			throw new MWException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}

		return new LoadBalancer( [
			'servers' => $wgExternalServers[$cluster],
			'loadMonitor' => $this->loadMonitorClass,
			'readOnlyReason' => $this->readOnlyReason,
			'trxProfiler' => $this->trxProfiler
		] );
	}

	/**
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @return array
	 */
	public function &getExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $wiki );
			$this->extLBs[$cluster]->parentInfo( [ 'id' => "ext-$cluster" ] );
			$this->chronProt->initLB( $this->extLBs[$cluster] );
		}

		return $this->extLBs[$cluster];
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

	public function shutdown( $flags = 0 ) {
		if ( !( $flags & self::SHUTDOWN_NO_CHRONPROT ) ) {
			$this->shutdownChronologyProtector( $this->chronProt );
		}
		$this->commitMasterChanges( __METHOD__ ); // sanity
	}
}
