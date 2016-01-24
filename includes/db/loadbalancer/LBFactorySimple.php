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
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;

/**
 * A simple single-master LBFactory that gets its configuration from the b/c globals
 */
class LBFactorySimple extends LBFactory {
	/** @var LoadBalancer */
	private $mainLB;
	/** @var LoadBalancer[] */
	private $extLBs = array();

	/** @var array[] */
	private $servers;

	/** @var array[] */
	private $externalServers;

	/**
	 * LBFactorySimple constructor.
	 *
	 * @param array $servers
	 * @param array $externalServers
	 * @param BagOStuff $srvCache
	 * @param ChronologyProtector $chronProtect
	 * @param TransactionProfiler $trxProfiler
	 * @param LoggerInterface $transactionLogger
	 * @param StatsdDataFactoryInterface $stats
	 */
	public function __construct( //FIXME: fix all useages, including subclasses
		array $servers,
		array $externalServers,
		BagOStuff $srvCache,
		ChronologyProtector $chronProtect,
		TransactionProfiler $trxProfiler,
		LoggerInterface $transactionLogger,
		StatsdDataFactoryInterface $stats
	) {
		parent::__construct( $srvCache, $chronProtect, $trxProfiler, $transactionLogger, $stats );

		Assert::parameterElementType( 'array', $servers, '$servers' );
		Assert::parameterElementType( 'array', $externalServers, '$externalServers' );

		$this->servers = $servers;
		$this->externalServers = $externalServers;

		foreach ( $this->servers as $i => &$server ) {
			if ( $i == 0 ) {
				$server['master'] = true;
			} else {
				$server['slave'] = true;
			}
		}
	}

	/**
	 * @param Config $config
	 *
	 * @return array[]
	 */
	public static function buildServerSpecsFromConfig( Config $config ) {
		if ( $config->has( 'DBServers' ) ) {
			$servers = $config->get( 'DBServers' );

			if ( is_array( $servers ) ) {
				return $servers;
			}
		}

		$flags = DBO_DEFAULT;
		if ( $config->get( 'DebugDumpSql' ) ) {
			$flags |= DBO_DEBUG;
		}
		if ( $config->get( 'DBssl' ) ) {
			$flags |= DBO_SSL;
		}
		if ( $config->get( 'DBcompress' ) ) {
			$flags |= DBO_COMPRESS;
		}

		$servers = array( array(
			'host' => $config->get( 'DBserver' ),
			'user' => $config->get( 'DBuser' ),
			'password' => $config->get( 'DBpassword' ),
			'dbname' => $config->get( 'DBname' ),
			'type' => $config->get( 'DBtype' ),
			'load' => 1,
			'flags' => $flags,
			'master' => true
		) );

		return $servers;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function newMainLB( $wiki = false ) {
		$loadBalancer = new LoadBalancer(
			$this->servers,
			$this->srvCache,
			$this->trxProfiler
		);

		$loadBalancer->setReadOnlyReason( $this->readOnlyReason );
		return $loadBalancer;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function getMainLB( $wiki = false ) {
		if ( !isset( $this->mainLB ) ) {
			$this->mainLB = $this->newMainLB( $wiki );
			$this->mainLB->parentInfo( array( 'id' => 'main' ) );
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
		if ( !isset( $this->externalServers[$cluster] ) ) {
			throw new MWException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}

		$loadBalancer = new LoadBalancer(
			$this->externalServers[$cluster],
			$this->srvCache,
			$this->trxProfiler
		);

		$loadBalancer->setReadOnlyReason( $this->readOnlyReason );
		return $loadBalancer;
	}

	/**
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @return array
	 */
	public function &getExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $wiki );
			$this->extLBs[$cluster]->parentInfo( array( 'id' => "ext-$cluster" ) );
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
	public function forEachLB( $callback, array $params = array() ) {
		if ( isset( $this->mainLB ) ) {
			call_user_func_array( $callback, array_merge( array( $this->mainLB ), $params ) );
		}
		foreach ( $this->extLBs as $lb ) {
			call_user_func_array( $callback, array_merge( array( $lb ), $params ) );
		}
	}

	public function shutdown( $flags = 0 ) {
		if ( !( $flags & self::SHUTDOWN_NO_CHRONPROT ) ) {
			$this->shutdownChronologyProtector( $this->chronProt );
		}
		$this->commitMasterChanges( __METHOD__ ); // sanity
	}
}
