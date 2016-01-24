<?php
/**
 * Simple generator of database connections that always returns the same object.
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

/**
 * An LBFactory class that always returns a single database object.
 */
class LBFactorySingle extends LBFactory {
	/** @var LoadBalancerSingle */
	private $lb;

	/**
	 * @param DatabaseBase $connection
	 * @param BagOStuff $srvCache
	 * @param ChronologyProtector $chronProtect
	 * @param TransactionProfiler $trxProfiler
	 * @param LoadMonitor $loadMonitor
	 * @param LoggerInterface $logger
	 * @param StatsdDataFactoryInterface $stats
	 */
	public function __construct( //FIXME: update usages (subclasses too!)
		DatabaseBase $connection,
		BagOStuff $srvCache,
		ChronologyProtector $chronProtect,
		TransactionProfiler $trxProfiler,
		LoadMonitor $loadMonitor,
		LoggerInterface $logger,
		StatsdDataFactoryInterface $stats
	) {
		parent::__construct( $srvCache, $chronProtect, $trxProfiler, $loadMonitor, $logger, $stats );

		$this->lb = new LoadBalancerSingle(
			$connection,
			$srvCache,
			$trxProfiler,
			$loadMonitor
		);
	}

	/**
	 * @see LBFactory::setReadOnlyReason
	 * @param false|string $reason
	 */
	public function setReadOnlyReason( $reason ) {
		parent::setReadOnlyReason( $reason );

		$this->lb->setReadOnlyReason( $reason );
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancerSingle
	 */
	public function newMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancerSingle
	 */
	public function getMainLB( $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancerSingle
	 */
	protected function newExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancerSingle
	 */
	public function &getExternalLB( $cluster, $wiki = false ) {
		return $this->lb;
	}

	/**
	 * @param string|callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = array() ) {
		call_user_func_array( $callback, array_merge( array( $this->lb ), $params ) );
	}
}

/**
 * Helper class for LBFactorySingle.
 */
class LoadBalancerSingle extends LoadBalancer {
	/** @var DatabaseBase */
	private $db;

	/**
	 * @param DatabaseBase $connection
	 * @param BagOStuff $srvCache
	 * @param TransactionProfiler $trxProfiler
	 * @param LoadMonitor $loadMonitor
	 */
	public function __construct(
		DatabaseBase $connection,
		BagOStuff $srvCache,
		TransactionProfiler $trxProfiler,
		LoadMonitor $loadMonitor
	) {
		$this->db = $connection;

		parent::__construct(
			array(
				array(
					'type' => $this->db->getType(),
					'host' => $this->db->getServer(),
					'dbname' => $this->db->getDBname(),
					'load' => 1,
				)
			),
			$srvCache,
			$trxProfiler,
			$loadMonitor
		);
	}

	/**
	 * @see LBFactory::setReadOnlyReason()
	 * @param false|string $reason
	 */
	public function setReadOnlyReason( $reason ) {
		parent::setReadOnlyReason( $reason );
		$this->db->setLBInfo( 'readOnlyReason', $reason );
	}

	/**
	 *
	 * @param string $server
	 * @param bool $dbNameOverride
	 *
	 * @return DatabaseBase
	 */
	protected function reallyOpenConnection( $server, $dbNameOverride = false ) {
		return $this->db;
	}
}
