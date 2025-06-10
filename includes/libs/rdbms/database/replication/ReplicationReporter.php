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

use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBPrimaryPos;
use Wikimedia\Rdbms\IDatabase;

/**
 * @internal
 * @ingroup Database
 * @since 1.40
 */
class ReplicationReporter {
	/** @var string Replication topology role of the server; one of the class ROLE_* constants */
	protected $topologyRole;
	/** @var LoggerInterface */
	protected $logger;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var array|null Replication lag estimate at the time of BEGIN for the last transaction */
	private $trxReplicaLagStatus = null;

	public function __construct( $topologyRole, $logger, $srvCache ) {
		$this->topologyRole = $topologyRole;
		$this->logger = $logger;
		$this->srvCache = $srvCache;
	}

	public function getTopologyRole() {
		return $this->topologyRole;
	}

	public function getLag( IDatabase $conn ) {
		if ( $this->topologyRole === IDatabase::ROLE_STREAMING_MASTER ) {
			return 0; // this is the primary DB
		} elseif ( $this->topologyRole === IDatabase::ROLE_STATIC_CLONE ) {
			return 0; // static dataset
		}

		return $this->doGetLag( $conn );
	}

	/**
	 * Get the amount of replication lag for this database server
	 *
	 * Callers should avoid using this method while a transaction is active
	 *
	 * @see getLag()
	 *
	 * @param IDatabase $conn To make queries
	 * @return float|int|false Database replication lag in seconds or false on error
	 * @throws DBError
	 */
	protected function doGetLag( IDatabase $conn ) {
		return 0;
	}

	/**
	 * Get a replica DB lag estimate for this server at the start of a transaction
	 *
	 * This is a no-op unless the server is known a priori to be a replica DB
	 *
	 * @param IDatabase $conn To make queries
	 * @return array ('lag': seconds or false on error, 'since': UNIX timestamp of estimate)
	 * @since 1.27 in Database, moved to ReplicationReporter in 1.40
	 */
	protected function getApproximateLagStatus( IDatabase $conn ) {
		if ( $this->topologyRole === IDatabase::ROLE_STREAMING_REPLICA ) {
			// Avoid exceptions as this is used internally in critical sections
			try {
				$lag = $this->getLag( $conn );
			} catch ( DBError ) {
				$lag = false;
			}
		} else {
			$lag = 0;
		}

		return [ 'lag' => $lag, 'since' => microtime( true ) ];
	}

	public function primaryPosWait( IDatabase $conn, DBPrimaryPos $pos, $timeout ) {
		// Real waits are implemented in the subclass.
		return 0;
	}

	public function getReplicaPos( IDatabase $conn ) {
		// Stub
		return false;
	}

	public function getPrimaryPos( IDatabase $conn ) {
		// Stub
		return false;
	}

	/**
	 * @return array|null Tuple of (reason string, "role") if read-only; null otherwise
	 */
	public function getTopologyBasedReadOnlyReason() {
		if ( $this->topologyRole === IDatabase::ROLE_STREAMING_REPLICA ) {
			return [ 'Server is configured as a read-only replica database.', 'role' ];
		} elseif ( $this->topologyRole === IDatabase::ROLE_STATIC_CLONE ) {
			return [ 'Server is configured as a read-only static clone database.', 'role' ];
		}

		return null;
	}

	public function resetReplicationLagStatus( IDatabase $conn ) {
		// With REPEATABLE-READ isolation, the first SELECT establishes the read snapshot,
		// so get the replication lag estimate before any transaction SELECT queries come in.
		// This way, the lag estimate reflects what will actually be read. Also, if heartbeat
		// tables are used, this avoids counting snapshot lag as part of replication lag.
		$this->trxReplicaLagStatus = null; // clear cached value first
		$this->trxReplicaLagStatus = $this->getApproximateLagStatus( $conn );
	}

	/**
	 * Get the replica DB lag when the current transaction started
	 *
	 * This is useful given that transactions might use point-in-time read snapshots,
	 * in which case the lag estimate should be recorded just before the transaction
	 * establishes the read snapshot (either BEGIN or the first SELECT/write query).
	 *
	 * If snapshots are not used, it is still safe to be pessimistic.
	 *
	 * This returns null if there is no transaction or the lag status was not yet recorded.
	 *
	 * @param IDatabase $conn To make queries
	 * @return array|null ('lag': seconds or false, 'since': UNIX timestamp of BEGIN) or null
	 * @since 1.27 in Database, moved to ReplicationReporter in 1.40
	 */
	final protected function getRecordedTransactionLagStatus( IDatabase $conn ) {
		return $conn->trxLevel() ? $this->trxReplicaLagStatus : null;
	}

	public function getSessionLagStatus( IDatabase $conn ) {
		return $this->getRecordedTransactionLagStatus( $conn ) ?: $this->getApproximateLagStatus( $conn );
	}

	/**
	 * Create a log context to pass to PSR-3 logger functions.
	 *
	 * @param IDatabase $conn To make queries
	 * @param array $extras Additional data to add to context
	 * @return array
	 */
	protected function getLogContext( IDatabase $conn, array $extras = [] ) {
		return array_merge(
			[
				'db_server' => $conn->getServerName(),
				'db_name' => $conn->getDBname(),
				// TODO: Add db_user
			],
			$extras
		);
	}
}
