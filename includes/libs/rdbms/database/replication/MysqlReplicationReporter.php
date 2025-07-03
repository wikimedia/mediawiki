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
namespace Wikimedia\Rdbms\Replication;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;
use stdClass;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\DBPrimaryPos;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\MySQLPrimaryPos;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\Query;

/**
 * @internal
 * @ingroup Database
 * @since 1.40
 */
class MysqlReplicationReporter extends ReplicationReporter {
	/** @var MySQLPrimaryPos */
	protected $lastKnownReplicaPos;
	/** @var string Method to detect replica DB lag */
	protected $lagDetectionMethod;
	/** @var array Method to detect replica DB lag */
	protected $lagDetectionOptions = [];
	/** @var bool bool Whether to use GTID methods */
	protected $useGTIDs = false;
	/** @var stdClass|null */
	private $replicationInfoRow;
	// Cache getServerId() for 24 hours
	private const SERVER_ID_CACHE_TTL = 86400;

	/** @var float Warn if lag estimates are made for transactions older than this many seconds */
	private const LAG_STALE_WARN_THRESHOLD = 0.100;

	/**
	 * @param string $topologyRole
	 * @param LoggerInterface $logger
	 * @param BagOStuff $srvCache
	 * @param string $lagDetectionMethod
	 * @param array $lagDetectionOptions
	 * @param bool $useGTIDs
	 */
	public function __construct(
		$topologyRole,
		$logger,
		$srvCache,
		$lagDetectionMethod,
		$lagDetectionOptions,
		$useGTIDs
	) {
		parent::__construct( $topologyRole, $logger, $srvCache );
		$this->lagDetectionMethod = $lagDetectionMethod;
		$this->lagDetectionOptions = $lagDetectionOptions;
		$this->useGTIDs = $useGTIDs;
	}

	protected function doGetLag( IDatabase $conn ) {
		if ( $this->lagDetectionMethod === 'pt-heartbeat' ) {
			return $this->getLagFromPtHeartbeat( $conn );
		} else {
			return $this->getLagFromSlaveStatus( $conn );
		}
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @return int|false Second of lag
	 */
	protected function getLagFromSlaveStatus( IDatabase $conn ) {
		$query = new Query(
			'SHOW SLAVE STATUS',
			ISQLPlatform::QUERY_SILENCE_ERRORS | ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
			'SHOW',
			null,
			'SHOW SLAVE STATUS'
		);
		$res = $conn->query( $query, __METHOD__ );
		$row = $res ? $res->fetchObject() : false;
		// If the server is not replicating, there will be no row
		if ( $row && strval( $row->Seconds_Behind_Master ) !== '' ) {
			// https://mariadb.com/kb/en/delayed-replication/
			// https://dev.mysql.com/doc/refman/5.6/en/replication-delayed.html
			return intval( $row->Seconds_Behind_Master + ( $row->SQL_Remaining_Delay ?? 0 ) );
		}

		return false;
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @return float|false Seconds of lag
	 */
	protected function getLagFromPtHeartbeat( IDatabase $conn ) {
		$currentTrxInfo = $this->getRecordedTransactionLagStatus( $conn );
		if ( $currentTrxInfo ) {
			// There is an active transaction and the initial lag was already queried
			$staleness = microtime( true ) - $currentTrxInfo['since'];
			if ( $staleness > self::LAG_STALE_WARN_THRESHOLD ) {
				// Avoid returning higher and higher lag value due to snapshot age
				// given that the isolation level will typically be REPEATABLE-READ
				// but UTC_TIMESTAMP() is not affected by point-in-time snapshots
				$this->logger->warning(
					"Using cached lag value for {db_server} due to active transaction",
					$this->getLogContext( $conn, [
						'method' => __METHOD__,
						'age' => $staleness,
						'exception' => new RuntimeException()
					] )
				);
			}

			return $currentTrxInfo['lag'];
		}

		$ago = $this->fetchSecondsSinceHeartbeat( $conn );
		if ( $ago !== null ) {
			return max( $ago, 0.0 );
		}

		$this->logger->error(
			"Unable to find pt-heartbeat row for {db_server}",
			$this->getLogContext( $conn, [
				'method' => __METHOD__
			] )
		);

		return false;
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @return float|null Elapsed seconds since the newest beat or null if none was found
	 * @see https://www.percona.com/doc/percona-toolkit/2.1/pt-heartbeat.html
	 */
	protected function fetchSecondsSinceHeartbeat( IDatabase $conn ) {
		// Some setups might have pt-heartbeat running on each replica server.
		// Exclude the row for events originating on this DB server. Assume that
		// there is only one active replication channel and that any other row
		// getting updates must be the row for the primary DB server.
		$where = $conn->makeList(
			$this->lagDetectionOptions['conds'] ?? [ 'server_id != @@server_id' ],
			ISQLPlatform::LIST_AND
		);
		// User mysql server time so that query time and trip time are not counted.
		// Use ORDER BY for channel based queries since that field might not be UNIQUE.
		$query = new Query(
			"SELECT TIMESTAMPDIFF(MICROSECOND,ts,UTC_TIMESTAMP(6)) AS us_ago " .
			"FROM heartbeat.heartbeat WHERE $where ORDER BY ts DESC LIMIT 1",
			ISQLPlatform::QUERY_SILENCE_ERRORS | ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
			'SELECT',
			null,
			"SELECT TIMESTAMPDIFF(MICROSECOND,ts,UTC_TIMESTAMP(6)) AS us_ago " .
			"FROM heartbeat.heartbeat WHERE ? ORDER BY ts DESC LIMIT 1",
		);
		$res = $conn->query( $query, __METHOD__ );
		$row = $res ? $res->fetchObject() : false;

		return $row ? ( $row->us_ago / 1e6 ) : null;
	}

	public function getApproximateLagStatus( IDatabase $conn ) {
		if ( $this->lagDetectionMethod === 'pt-heartbeat' ) {
			// Disable caching since this is fast enough and we don't want
			// to be *too* pessimistic by having both the cache TTL and the
			// pt-heartbeat interval count as lag in getSessionLagStatus()
			return parent::getApproximateLagStatus( $conn );
		}

		$key = $this->srvCache->makeGlobalKey( 'mysql-lag', $conn->getServerName() );
		$approxLag = $this->srvCache->get( $key );
		if ( !$approxLag ) {
			$approxLag = parent::getApproximateLagStatus( $conn );
			$this->srvCache->set( $key, $approxLag, 1 );
		}

		return $approxLag;
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @param string $fname
	 * @return stdClass Process cached row
	 */
	public function getReplicationSafetyInfo( IDatabase $conn, $fname ) {
		if ( $this->replicationInfoRow === null ) {
			$this->replicationInfoRow = $conn->selectRow(
				[],
				[
					'innodb_autoinc_lock_mode' => '@@innodb_autoinc_lock_mode',
					'binlog_format' => '@@binlog_format',
				],
				[],
				$fname
			);
		}
		return $this->replicationInfoRow;
	}

	/**
	 * @return bool Whether GTID support is used (mockable for testing)
	 */
	protected function useGTIDs() {
		return $this->useGTIDs;
	}

	public function primaryPosWait( IDatabase $conn, DBPrimaryPos $pos, $timeout ) {
		if ( !( $pos instanceof MySQLPrimaryPos ) ) {
			throw new InvalidArgumentException( "Position not an instance of MySQLPrimaryPos" );
		}

		if ( $this->topologyRole === IDatabase::ROLE_STATIC_CLONE ) {
			$this->logger->debug(
				"Bypassed replication wait; database has a static dataset",
				$this->getLogContext( $conn, [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
			);

			return 0; // this is a copy of a read-only dataset with no primary DB
		} elseif ( $this->lastKnownReplicaPos && $this->lastKnownReplicaPos->hasReached( $pos ) ) {
			$this->logger->debug(
				"Bypassed replication wait; replication known to have reached {raw_pos}",
				$this->getLogContext( $conn, [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
			);

			return 0; // already reached this point for sure
		}

		// Call doQuery() directly, to avoid opening a transaction if DBO_TRX is set
		if ( $pos->getGTIDs() ) {
			// Get the GTIDs from this replica server too see the domains (channels)
			$refPos = $this->getReplicaPos( $conn );
			if ( !$refPos ) {
				$this->logger->error(
					"Could not get replication position on replica DB to compare to {raw_pos}",
					$this->getLogContext( $conn, [ 'method' => __METHOD__, 'raw_pos' => $pos ] )
				);

				return -1; // this is the primary DB itself?
			}
			// GTIDs with domains (channels) that are active and are present on the replica
			$gtidsWait = $pos::getRelevantActiveGTIDs( $pos, $refPos );
			if ( !$gtidsWait ) {
				$this->logger->error(
					"No active GTIDs in {raw_pos} share a domain with those in {current_pos}",
					$this->getLogContext( $conn, [
						'method' => __METHOD__,
						'raw_pos' => $pos,
						'current_pos' => $refPos
					] )
				);

				return -1; // $pos is from the wrong cluster?
			}
			// Wait on the GTID set
			$gtidArg = $conn->addQuotes( implode( ',', $gtidsWait ) );
			if ( strpos( $gtidArg, ':' ) !== false ) {
				// MySQL GTIDs, e.g "source_id:transaction_id"
				$query = new Query(
					"SELECT WAIT_FOR_EXECUTED_GTID_SET($gtidArg, $timeout)",
					ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
					'SELECT',
					null,
					"SELECT WAIT_FOR_EXECUTED_GTID_SET(?, ?)"
				);
			} else {
				// MariaDB GTIDs, e.g."domain:server:sequence"
				$query = new Query(
					"SELECT MASTER_GTID_WAIT($gtidArg, $timeout)",
					ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
					'SELECT',
					null,
					"SELECT MASTER_GTID_WAIT(?, ?)"
				);
			}
			$waitPos = implode( ',', $gtidsWait );
		} else {
			// Wait on the binlog coordinates
			$encFile = $conn->addQuotes( $pos->getLogFile() );
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$encPos = intval( $pos->getLogPosition()[$pos::CORD_EVENT] );
			$query = new Query(
				"SELECT MASTER_POS_WAIT($encFile, $encPos, $timeout)",
				ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
				'SELECT',
				null,
				"SELECT MASTER_POS_WAIT(?, ?, ?)"
			);
			$waitPos = $pos->__toString();
		}

		$start = microtime( true );
		$res = $conn->query( $query, __METHOD__ );
		$row = $res->fetchRow();
		$seconds = max( microtime( true ) - $start, 0 );

		// Result can be NULL (error), -1 (timeout), or 0+ per the MySQL manual
		$status = ( $row[0] !== null ) ? intval( $row[0] ) : null;
		if ( $status === null ) {
			$this->logger->error(
				"An error occurred while waiting for replication to reach {wait_pos}",
				$this->getLogContext( $conn, [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'sql' => $query->getSQL(),
					'seconds_waited' => $seconds,
					'exception' => new RuntimeException()
				] )
			);
		} elseif ( $status < 0 ) {
			$this->logger->info(
				"Timed out waiting for replication to reach {wait_pos}",
				$this->getLogContext( $conn, [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'timeout' => $timeout,
					'sql' => $query->getSQL(),
					'seconds_waited' => $seconds,
				] )
			);
		} elseif ( $status >= 0 ) {
			$this->logger->debug(
				"Replication has reached {wait_pos}",
				$this->getLogContext( $conn, [
					'raw_pos' => $pos,
					'wait_pos' => $waitPos,
					'seconds_waited' => $seconds,
				] )
			);
			// Remember that this position was reached to save queries next time
			$this->lastKnownReplicaPos = $pos;
		}

		return $status;
	}

	/**
	 * Get the position of the primary DB from SHOW SLAVE STATUS
	 *
	 * @param IDatabase $conn To make queries
	 * @return MySQLPrimaryPos|false
	 */
	public function getReplicaPos( IDatabase $conn ) {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( $conn, __METHOD__ );
			// Use gtid_slave_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_slave_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					return new MySQLPrimaryPos( $data[$name], $now );
				}
			}
		}

		$data = $this->getServerRoleStatus( $conn, 'SLAVE', __METHOD__ );
		if ( $data && strlen( $data['Relay_Master_Log_File'] ) ) {
			return new MySQLPrimaryPos(
				"{$data['Relay_Master_Log_File']}/{$data['Exec_Master_Log_Pos']}",
				$now
			);
		}

		return false;
	}

	/**
	 * Get the position of the primary DB from SHOW MASTER STATUS
	 *
	 * @param IDatabase $conn To make queries
	 * @return MySQLPrimaryPos|false
	 */
	public function getPrimaryPos( IDatabase $conn ) {
		$now = microtime( true ); // as-of-time *before* fetching GTID variables

		$pos = false;
		if ( $this->useGTIDs() ) {
			// Try to use GTIDs, fallbacking to binlog positions if not possible
			$data = $this->getServerGTIDs( $conn, __METHOD__ );
			// Use gtid_binlog_pos for MariaDB and gtid_executed for MySQL
			foreach ( [ 'gtid_binlog_pos', 'gtid_executed' ] as $name ) {
				if ( isset( $data[$name] ) && strlen( $data[$name] ) ) {
					$pos = new MySQLPrimaryPos( $data[$name], $now );
					break;
				}
			}
			// Filter domains that are inactive or not relevant to the session
			if ( $pos ) {
				$pos->setActiveOriginServerId( $this->getServerId( $conn ) );
				$pos->setActiveOriginServerUUID( $this->getServerUUID( $conn ) );
				if ( isset( $data['gtid_domain_id'] ) ) {
					$pos->setActiveDomain( $data['gtid_domain_id'] );
				}
			}
		}

		if ( !$pos ) {
			$data = $this->getServerRoleStatus( $conn, 'MASTER', __METHOD__ );
			if ( $data && strlen( $data['File'] ) ) {
				$pos = new MySQLPrimaryPos( "{$data['File']}/{$data['Position']}", $now );
			}
		}

		return $pos;
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @return string Value of server_id (32-bit integer, unique to the replication topology)
	 * @throws DBQueryError
	 */
	protected function getServerId( IDatabase $conn ) {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-id', $conn->getServerName() ),
			self::SERVER_ID_CACHE_TTL,
			static function () use ( $conn, $fname ) {
				$query = new Query(
					"SELECT @@server_id AS id",
					ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
					'SELECT',
					null,
					"SELECT @@server_id AS id"
				);
				$res = $conn->query( $query, $fname );

				return $res->fetchObject()->id;
			}
		);
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @return string|null Value of server_uuid (hyphenated 128-bit hex string, globally unique)
	 * @throws DBQueryError
	 */
	protected function getServerUUID( IDatabase $conn ) {
		$fname = __METHOD__;
		return $this->srvCache->getWithSetCallback(
			$this->srvCache->makeGlobalKey( 'mysql-server-uuid', $conn->getServerName() ),
			self::SERVER_ID_CACHE_TTL,
			static function () use ( $conn, $fname ) {
				$query = new Query(
					"SHOW GLOBAL VARIABLES LIKE 'server_uuid'",
					ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
					'SHOW',
					null,
					"SHOW GLOBAL VARIABLES LIKE 'server_uuid'"
				);
				$res = $conn->query( $query, $fname );
				$row = $res->fetchObject();

				return $row ? $row->Value : null;
			}
		);
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @param string $fname
	 * @return string[]
	 */
	protected function getServerGTIDs( IDatabase $conn, $fname ) {
		$map = [];

		$flags = ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE;

		// Get global-only variables like gtid_executed
		$query = new Query(
			"SHOW GLOBAL VARIABLES LIKE 'gtid_%'",
			$flags,
			'SHOW',
			null,
			"SHOW GLOBAL VARIABLES LIKE 'gtid_%'"
		);
		$res = $conn->query( $query, $fname );
		foreach ( $res as $row ) {
			$map[$row->Variable_name] = $row->Value;
		}
		// Get session-specific (e.g. gtid_domain_id since that is were writes will log)
		$query = new Query(
			"SHOW SESSION VARIABLES LIKE 'gtid_%'",
			$flags,
			'SHOW',
			null,
			"SHOW SESSION VARIABLES LIKE 'gtid_%'"
		);
		$res = $conn->query( $query, $fname );
		foreach ( $res as $row ) {
			$map[$row->Variable_name] = $row->Value;
		}

		return $map;
	}

	/**
	 * @param IDatabase $conn To make queries
	 * @param string $role One of "MASTER"/"SLAVE"
	 * @param string $fname
	 * @return array<string,mixed>|null Latest available server status row; false on failure
	 */
	protected function getServerRoleStatus( IDatabase $conn, $role, $fname ) {
		$query = new Query(
			"SHOW $role STATUS",
			ISQLPlatform::QUERY_SILENCE_ERRORS | ISQLPlatform::QUERY_IGNORE_DBO_TRX | ISQLPlatform::QUERY_CHANGE_NONE,
			'SHOW',
			null,
			"SHOW $role STATUS"
		);
		$res = $conn->query( $query, $fname );
		$row = $res ? $res->fetchRow() : false;

		return ( $row ?: null );
	}

}
