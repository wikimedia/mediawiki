<?php
/**
 * Generator and manager of database load balancing objects
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

use Psr\Log\LoggerInterface;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactory {
	/** @var ChronologyProtector */
	protected $chronProt;
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var LoggerInterface */
	protected $trxLogger;
	/** @var LoggerInterface */
	protected $replLogger;
	/** @var LoggerInterface */
	protected $connLogger;
	/** @var LoggerInterface */
	protected $queryLogger;
	/** @var LoggerInterface */
	protected $perfLogger;
	/** @var callable Error logger */
	protected $errorLogger;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var BagOStuff */
	protected $memCache;
	/** @var WANObjectCache */
	protected $wanCache;

	/** @var string Local domain */
	protected $domain;
	/** @var mixed */
	protected $ticket;
	/** @var string|bool String if a requested DBO_TRX transaction round is active */
	protected $trxRoundId = false;
	/** @var string|bool Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;
	/** @var callable[] */
	protected $replicationWaitCallbacks = [];

	const SHUTDOWN_NO_CHRONPROT = 0; // don't save DB positions at all
	const SHUTDOWN_CHRONPROT_ASYNC = 1; // save DB positions, but don't wait on remote DCs
	const SHUTDOWN_CHRONPROT_SYNC = 2; // save DB positions, waiting on all DCs

	private static $loggerFields =
		[ 'trxLogger', 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ];

	/**
	 * @TODO: document base params here
	 * @param array $conf
	 */
	public function __construct( array $conf ) {
		$this->domain = isset( $conf['domain'] ) ? $conf['domain'] : '';
		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}

		$this->srvCache = isset( $conf['srvCache'] ) ? $conf['srvCache'] : new EmptyBagOStuff();
		$this->memCache = isset( $conf['memCache'] ) ? $conf['memCache'] : new EmptyBagOStuff();
		$this->wanCache = isset( $conf['wanCache'] )
			? $conf['wanCache']
			: WANObjectCache::newEmpty();

		foreach ( self::$loggerFields as $key ) {
			$this->$key = isset( $conf[$key] ) ? $conf[$key] : new \Psr\Log\NullLogger();
		}
		$this->errorLogger = isset( $conf['errorLogger'] )
			? $conf['errorLogger']
			: function ( Exception $e ) {
				trigger_error( E_WARNING, $e->getMessage() );
			};

		$this->chronProt = isset( $conf['chronProt'] )
			? $conf['chronProt']
			: $this->newChronologyProtector();
		$this->trxProfiler = isset( $conf['trxProfiler'] )
			? $conf['trxProfiler']
			: new TransactionProfiler();

		$this->ticket = mt_rand();
	}

	/**
	 * Disables all load balancers. All connections are closed, and any attempt to
	 * open a new connection will result in a DBAccessError.
	 * @see LoadBalancer::disable()
	 */
	public function destroy() {
		$this->shutdown( self::SHUTDOWN_NO_CHRONPROT );
		$this->forEachLBCallMethod( 'disable' );
	}

	/**
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * @param bool|string $domain Wiki ID, or false for the current wiki
	 * @return ILoadBalancer
	 */
	abstract public function newMainLB( $domain = false );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param bool|string $domain Wiki ID, or false for the current wiki
	 * @return ILoadBalancer
	 */
	abstract public function getMainLB( $domain = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for
	 * cleaning it up.
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $domain Wiki ID, or false for the current wiki
	 * @return ILoadBalancer
	 */
	abstract protected function newExternalLB( $cluster, $domain = false );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $domain Wiki ID, or false for the current wiki
	 * @return ILoadBalancer
	 */
	abstract public function getExternalLB( $cluster, $domain = false );

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	abstract public function forEachLB( $callback, array $params = [] );

	/**
	 * Prepare all tracked load balancers for shutdown
	 * @param integer $mode One of the class SHUTDOWN_* constants
	 * @param callable|null $workCallback Work to mask ChronologyProtector writes
	 */
	public function shutdown(
		$mode = self::SHUTDOWN_CHRONPROT_SYNC, callable $workCallback = null
	) {
		if ( $mode === self::SHUTDOWN_CHRONPROT_SYNC ) {
			$this->shutdownChronologyProtector( $this->chronProt, $workCallback, 'sync' );
		} elseif ( $mode === self::SHUTDOWN_CHRONPROT_ASYNC ) {
			$this->shutdownChronologyProtector( $this->chronProt, null, 'async' );
		}

		$this->commitMasterChanges( __METHOD__ ); // sanity
	}

	/**
	 * Call a method of each tracked load balancer
	 *
	 * @param string $methodName
	 * @param array $args
	 */
	protected function forEachLBCallMethod( $methodName, array $args = [] ) {
		$this->forEachLB(
			function ( ILoadBalancer $loadBalancer, $methodName, array $args ) {
				call_user_func_array( [ $loadBalancer, $methodName ], $args );
			},
			[ $methodName, $args ]
		);
	}

	/**
	 * Commit all replica DB transactions so as to flush any REPEATABLE-READ or SSI snapshot
	 *
	 * @param string $fname Caller name
	 * @since 1.28
	 */
	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		$this->forEachLBCallMethod( 'flushReplicaSnapshots', [ $fname ] );
	}

	/**
	 * Commit on all connections. Done for two reasons:
	 * 1. To commit changes to the masters.
	 * 2. To release the snapshot on all connections, master and replica DB.
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 */
	public function commitAll( $fname = __METHOD__, array $options = [] ) {
		$this->commitMasterChanges( $fname, $options );
		$this->forEachLBCallMethod( 'commitAll', [ $fname ] );
	}

	/**
	 * Flush any master transaction snapshots and set DBO_TRX (if DBO_DEFAULT is set)
	 *
	 * The DBO_TRX setting will be reverted to the default in each of these methods:
	 *   - commitMasterChanges()
	 *   - rollbackMasterChanges()
	 *   - commitAll()
	 *
	 * This allows for custom transaction rounds from any outer transaction scope.
	 *
	 * @param string $fname
	 * @throws DBTransactionError
	 * @since 1.28
	 */
	public function beginMasterChanges( $fname = __METHOD__ ) {
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' already started."
			);
		}
		$this->trxRoundId = $fname;
		// Set DBO_TRX flags on all appropriate DBs
		$this->forEachLBCallMethod( 'beginMasterChanges', [ $fname ] );
	}

	/**
	 * Commit changes on all master connections
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 * @throws Exception
	 */
	public function commitMasterChanges( $fname = __METHOD__, array $options = [] ) {
		if ( $this->trxRoundId !== false && $this->trxRoundId !== $fname ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' still running."
			);
		}
		// Run pre-commit callbacks and suppress post-commit callbacks, aborting on failure
		$this->forEachLBCallMethod( 'finalizeMasterChanges' );
		$this->trxRoundId = false;
		// Perform pre-commit checks, aborting on failure
		$this->forEachLBCallMethod( 'approveMasterChanges', [ $options ] );
		// Log the DBs and methods involved in multi-DB transactions
		$this->logIfMultiDbTransaction();
		// Actually perform the commit on all master DB connections and revert DBO_TRX
		$this->forEachLBCallMethod( 'commitMasterChanges', [ $fname ] );
		// Run all post-commit callbacks
		/** @var Exception $e */
		$e = null; // first callback exception
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$e ) {
			$ex = $lb->runMasterPostTrxCallbacks( IDatabase::TRIGGER_COMMIT );
			$e = $e ?: $ex;
		} );
		// Commit any dangling DBO_TRX transactions from callbacks on one DB to another DB
		$this->forEachLBCallMethod( 'commitMasterChanges', [ $fname ] );
		// Throw any last post-commit callback error
		if ( $e instanceof Exception ) {
			throw $e;
		}
	}

	/**
	 * Rollback changes on all master connections
	 * @param string $fname Caller name
	 * @since 1.23
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$this->trxRoundId = false;
		$this->forEachLBCallMethod( 'suppressTransactionEndCallbacks' );
		$this->forEachLBCallMethod( 'rollbackMasterChanges', [ $fname ] );
		// Run all post-rollback callbacks
		$this->forEachLB( function ( ILoadBalancer $lb ) {
			$lb->runMasterPostTrxCallbacks( IDatabase::TRIGGER_ROLLBACK );
		} );
	}

	/**
	 * Log query info if multi DB transactions are going to be committed now
	 */
	private function logIfMultiDbTransaction() {
		$callersByDB = [];
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$callersByDB ) {
			$masterName = $lb->getServerName( $lb->getWriterIndex() );
			$callers = $lb->pendingMasterChangeCallers();
			if ( $callers ) {
				$callersByDB[$masterName] = $callers;
			}
		} );

		if ( count( $callersByDB ) >= 2 ) {
			$dbs = implode( ', ', array_keys( $callersByDB ) );
			$msg = "Multi-DB transaction [{$dbs}]:\n";
			foreach ( $callersByDB as $db => $callers ) {
				$msg .= "$db: " . implode( '; ', $callers ) . "\n";
			}
			$this->trxLogger->info( $msg );
		}
	}

	/**
	 * Determine if any master connection has pending changes
	 * @return bool
	 * @since 1.23
	 */
	public function hasMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasMasterChanges();
		} );

		return $ret;
	}

	/**
	 * Detemine if any lagged replica DB connection was used
	 * @return bool
	 * @since 1.28
	 */
	public function laggedReplicaUsed() {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->laggedReplicaUsed();
		} );

		return $ret;
	}

	/**
	 * Determine if any master connection has pending/written changes from this request
	 * @param float $age How many seconds ago is "recent" [defaults to LB lag wait timeout]
	 * @return bool
	 * @since 1.27
	 */
	public function hasOrMadeRecentMasterChanges( $age = null ) {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $age, &$ret ) {
			$ret = $ret || $lb->hasOrMadeRecentMasterChanges( $age );
		} );
		return $ret;
	}

	/**
	 * Waits for the replica DBs to catch up to the current master position
	 *
	 * Use this when updating very large numbers of rows, as in maintenance scripts,
	 * to avoid causing too much lag. Of course, this is a no-op if there are no replica DBs.
	 *
	 * By default this waits on all DB clusters actually used in this request.
	 * This makes sense when lag being waiting on is caused by the code that does this check.
	 * In that case, setting "ifWritesSince" can avoid the overhead of waiting for clusters
	 * that were not changed since the last wait check. To forcefully wait on a specific cluster
	 * for a given wiki, use the 'wiki' parameter. To forcefully wait on an "external" cluster,
	 * use the "cluster" parameter.
	 *
	 * Never call this function after a large DB write that is *still* in a transaction.
	 * It only makes sense to call this after the possible lag inducing changes were committed.
	 *
	 * @param array $opts Optional fields that include:
	 *   - wiki : wait on the load balancer DBs that handles the given wiki
	 *   - cluster : wait on the given external load balancer DBs
	 *   - timeout : Max wait time. Default: ~60 seconds
	 *   - ifWritesSince: Only wait if writes were done since this UNIX timestamp
	 * @throws DBReplicationWaitError If a timeout or error occured waiting on a DB cluster
	 * @since 1.27
	 */
	public function waitForReplication( array $opts = [] ) {
		$opts += [
			'wiki' => false,
			'cluster' => false,
			'timeout' => 60,
			'ifWritesSince' => null
		];

		// Figure out which clusters need to be checked
		/** @var ILoadBalancer[] $lbs */
		$lbs = [];
		if ( $opts['cluster'] !== false ) {
			$lbs[] = $this->getExternalLB( $opts['cluster'] );
		} elseif ( $opts['wiki'] !== false ) {
			$lbs[] = $this->getMainLB( $opts['wiki'] );
		} else {
			$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$lbs ) {
				$lbs[] = $lb;
			} );
			if ( !$lbs ) {
				return; // nothing actually used
			}
		}

		// Get all the master positions of applicable DBs right now.
		// This can be faster since waiting on one cluster reduces the
		// time needed to wait on the next clusters.
		$masterPositions = array_fill( 0, count( $lbs ), false );
		foreach ( $lbs as $i => $lb ) {
			if ( $lb->getServerCount() <= 1 ) {
				// Bug 27975 - Don't try to wait for replica DBs if there are none
				// Prevents permission error when getting master position
				continue;
			} elseif ( $opts['ifWritesSince']
				&& $lb->lastMasterChangeTimestamp() < $opts['ifWritesSince']
			) {
				continue; // no writes since the last wait
			}
			$masterPositions[$i] = $lb->getMasterPos();
		}

		// Run any listener callbacks *after* getting the DB positions. The more
		// time spent in the callbacks, the less time is spent in waitForAll().
		foreach ( $this->replicationWaitCallbacks as $callback ) {
			$callback();
		}

		$failed = [];
		foreach ( $lbs as $i => $lb ) {
			if ( $masterPositions[$i] ) {
				// The DBMS may not support getMasterPos() or the whole
				// load balancer might be fake (e.g. $wgAllDBsAreLocalhost).
				if ( !$lb->waitForAll( $masterPositions[$i], $opts['timeout'] ) ) {
					$failed[] = $lb->getServerName( $lb->getWriterIndex() );
				}
			}
		}

		if ( $failed ) {
			throw new DBReplicationWaitError(
				"Could not wait for replica DBs to catch up to " .
				implode( ', ', $failed )
			);
		}
	}

	/**
	 * Add a callback to be run in every call to waitForReplication() before waiting
	 *
	 * Callbacks must clear any transactions that they start
	 *
	 * @param string $name Callback name
	 * @param callable|null $callback Use null to unset a callback
	 * @since 1.28
	 */
	public function setWaitForReplicationListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->replicationWaitCallbacks[$name] = $callback;
		} else {
			unset( $this->replicationWaitCallbacks[$name] );
		}
	}

	/**
	 * Get a token asserting that no transaction writes are active
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @return mixed A value to pass to commitAndWaitForReplication()
	 * @since 1.28
	 */
	public function getEmptyTransactionTicket( $fname ) {
		if ( $this->hasMasterChanges() ) {
			$this->trxLogger->error( __METHOD__ . ": $fname does not have outer scope." );
			return null;
		}

		return $this->ticket;
	}

	/**
	 * Convenience method for safely running commitMasterChanges()/waitForReplication()
	 *
	 * This will commit and wait unless $ticket indicates it is unsafe to do so
	 *
	 * @param string $fname Caller name (e.g. __METHOD__)
	 * @param mixed $ticket Result of getEmptyTransactionTicket()
	 * @param array $opts Options to waitForReplication()
	 * @throws DBReplicationWaitError
	 * @since 1.28
	 */
	public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] ) {
		if ( $ticket !== $this->ticket ) {
			$this->perfLogger->error( __METHOD__ . ": $fname does not have outer scope." );
			return;
		}

		// The transaction owner and any caller with the empty transaction ticket can commit
		// so that getEmptyTransactionTicket() callers don't risk seeing DBTransactionError.
		if ( $this->trxRoundId !== false && $fname !== $this->trxRoundId ) {
			$this->trxLogger->info( "$fname: committing on behalf of {$this->trxRoundId}." );
			$fnameEffective = $this->trxRoundId;
		} else {
			$fnameEffective = $fname;
		}

		$this->commitMasterChanges( $fnameEffective );
		$this->waitForReplication( $opts );
		// If a nested caller committed on behalf of $fname, start another empty $fname
		// transaction, leaving the caller with the same empty transaction state as before.
		if ( $fnameEffective !== $fname ) {
			$this->beginMasterChanges( $fnameEffective );
		}
	}

	/**
	 * @param string $dbName DB master name (e.g. "db1052")
	 * @return float|bool UNIX timestamp when client last touched the DB or false if not recent
	 * @since 1.28
	 */
	public function getChronologyProtectorTouched( $dbName ) {
		return $this->chronProt->getTouched( $dbName );
	}

	/**
	 * Disable the ChronologyProtector for all load balancers
	 *
	 * This can be called at the start of special API entry points
	 *
	 * @since 1.27
	 */
	public function disableChronologyProtection() {
		$this->chronProt->setEnabled( false );
	}

	/**
	 * @return ChronologyProtector
	 */
	protected function newChronologyProtector() {
		$chronProt = new ChronologyProtector(
			$this->memCache,
			[
				'ip' => isset( $_SERVER[ 'REMOTE_ADDR' ] ) ? $_SERVER[ 'REMOTE_ADDR' ] : '',
				'agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : ''
			],
			isset( $_GET['cpPosTime'] ) ? $_GET['cpPosTime'] : null
		);
		if ( PHP_SAPI === 'cli' ) {
			$chronProt->setEnabled( false );
		}

		return $chronProt;
	}

	/**
	 * Get and record all of the staged DB positions into persistent memory storage
	 *
	 * @param ChronologyProtector $cp
	 * @param callable|null $workCallback Work to do instead of waiting on syncing positions
	 * @param string $mode One of (sync, async); whether to wait on remote datacenters
	 */
	protected function shutdownChronologyProtector(
		ChronologyProtector $cp, $workCallback, $mode
	) {
		// Record all the master positions needed
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $cp ) {
			$cp->shutdownLB( $lb );
		} );
		// Write them to the persistent stash. Try to do something useful by running $work
		// while ChronologyProtector waits for the stash write to replicate to all DCs.
		$unsavedPositions = $cp->shutdown( $workCallback, $mode );
		if ( $unsavedPositions && $workCallback ) {
			// Invoke callback in case it did not cache the result yet
			$workCallback(); // work now to block for less time in waitForAll()
		}
		// If the positions failed to write to the stash, at least wait on local datacenter
		// replica DBs to catch up before responding. Even if there are several DCs, this increases
		// the chance that the user will see their own changes immediately afterwards. As long
		// as the sticky DC cookie applies (same domain), this is not even an issue.
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $unsavedPositions ) {
			$masterName = $lb->getServerName( $lb->getWriterIndex() );
			if ( isset( $unsavedPositions[$masterName] ) ) {
				$lb->waitForAll( $unsavedPositions[$masterName] );
			}
		} );
	}

	/**
	 * Base parameters to LoadBalancer::__construct()
	 * @return array
	 */
	final protected function baseLoadBalancerParams() {
		return [
			'localDomain' => $this->domain,
			'readOnlyReason' => $this->readOnlyReason,
			'srvCache' => $this->srvCache,
			'wanCache' => $this->wanCache,
			'trxProfiler' => $this->trxProfiler,
			'queryLogger' => $this->queryLogger,
			'connLogger' => $this->connLogger,
			'replLogger' => $this->replLogger,
			'errorLogger' => $this->errorLogger
		];
	}

	/**
	 * @param ILoadBalancer $lb
	 */
	protected function initLoadBalancer( ILoadBalancer $lb ) {
		if ( $this->trxRoundId !== false ) {
			$lb->beginMasterChanges( $this->trxRoundId ); // set DBO_TRX
		}
	}

	/**
	 * Close all open database connections on all open load balancers.
	 * @since 1.28
	 */
	public function closeAll() {
		$this->forEachLBCallMethod( 'closeAll', [] );
	}
}
