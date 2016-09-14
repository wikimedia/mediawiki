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

use Psr\Log\LoggerInterface;
use MediaWiki\MediaWikiServices;
use MediaWiki\Services\DestructibleService;
use MediaWiki\Logger\LoggerFactory;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactory implements DestructibleService {
	/** @var ChronologyProtector */
	protected $chronProt;
	/** @var TransactionProfiler */
	protected $trxProfiler;
	/** @var LoggerInterface */
	protected $trxLogger;
	/** @var LoggerInterface */
	protected $replLogger;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var WANObjectCache */
	protected $wanCache;

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

	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 * @TODO: inject objects via dependency framework
	 */
	public function __construct( array $conf ) {
		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}
		$this->chronProt = $this->newChronologyProtector();
		$this->trxProfiler = Profiler::instance()->getTransactionProfiler();
		// Use APC/memcached style caching, but avoids loops with CACHE_DB (T141804)
		$cache = ObjectCache::getLocalServerInstance();
		if ( $cache->getQoS( $cache::ATTR_EMULATION ) > $cache::QOS_EMULATION_SQL ) {
			$this->srvCache = $cache;
		} else {
			$this->srvCache = new EmptyBagOStuff();
		}
		$wCache = ObjectCache::getMainWANInstance();
		if ( $wCache->getQoS( $wCache::ATTR_EMULATION ) > $wCache::QOS_EMULATION_SQL ) {
			$this->wanCache = $wCache;
		} else {
			$this->wanCache = WANObjectCache::newEmpty();
		}
		$this->trxLogger = LoggerFactory::getInstance( 'DBTransaction' );
		$this->replLogger = LoggerFactory::getInstance( 'DBReplication' );
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
	 * Disables all access to the load balancer, will cause all database access
	 * to throw a DBAccessError
	 */
	public static function disableBackend() {
		MediaWikiServices::disableStorageBackend();
	}

	/**
	 * Get an LBFactory instance
	 *
	 * @deprecated since 1.27, use MediaWikiServices::getDBLoadBalancerFactory() instead.
	 *
	 * @return LBFactory
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
	}

	/**
	 * Returns the LBFactory class to use and the load balancer configuration.
	 *
	 * @todo instead of this, use a ServiceContainer for managing the different implementations.
	 *
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string Class name
	 */
	public static function getLBFactoryClass( array $config ) {
		// For configuration backward compatibility after removing
		// underscores from class names in MediaWiki 1.23.
		$bcClasses = [
			'LBFactory_Simple' => 'LBFactorySimple',
			'LBFactory_Single' => 'LBFactorySingle',
			'LBFactory_Multi' => 'LBFactoryMulti',
			'LBFactory_Fake' => 'LBFactoryFake',
		];

		$class = $config['class'];

		if ( isset( $bcClasses[$class] ) ) {
			$class = $bcClasses[$class];
			wfDeprecated(
				'$wgLBFactoryConf must be updated. See RELEASE-NOTES for details',
				'1.23'
			);
		}

		return $class;
	}

	/**
	 * Shut down, close connections and destroy the cached instance.
	 *
	 * @deprecated since 1.27, use LBFactory::destroy()
	 */
	public static function destroyInstance() {
		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->destroy();
	}

	/**
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function newMainLB( $wiki = false );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function getMainLB( $wiki = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for
	 * cleaning it up.
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract protected function newExternalLB( $cluster, $wiki = false );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function getExternalLB( $cluster, $wiki = false );

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
	private function forEachLBCallMethod( $methodName, array $args = [] ) {
		$this->forEachLB(
			function ( LoadBalancer $loadBalancer, $methodName, array $args ) {
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
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$e ) {
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
		$this->forEachLB( function ( LoadBalancer $lb ) {
			$lb->runMasterPostTrxCallbacks( IDatabase::TRIGGER_ROLLBACK );
		} );
	}

	/**
	 * Log query info if multi DB transactions are going to be committed now
	 */
	private function logIfMultiDbTransaction() {
		$callersByDB = [];
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$callersByDB ) {
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
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
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
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->laggedReplicaUsed();
		} );

		return $ret;
	}

	/**
	 * @return bool
	 * @since 1.27
	 * @deprecated Since 1.28; use laggedReplicaUsed()
	 */
	public function laggedSlaveUsed() {
		return $this->laggedReplicaUsed();
	}

	/**
	 * Determine if any master connection has pending/written changes from this request
	 * @param float $age How many seconds ago is "recent" [defaults to LB lag wait timeout]
	 * @return bool
	 * @since 1.27
	 */
	public function hasOrMadeRecentMasterChanges( $age = null ) {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $age, &$ret ) {
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
		/** @var LoadBalancer[] $lbs */
		$lbs = [];
		if ( $opts['cluster'] !== false ) {
			$lbs[] = $this->getExternalLB( $opts['cluster'] );
		} elseif ( $opts['wiki'] !== false ) {
			$lbs[] = $this->getMainLB( $opts['wiki'] );
		} else {
			$this->forEachLB( function ( LoadBalancer $lb ) use ( &$lbs ) {
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
			$logger = LoggerFactory::getInstance( 'DBPerformance' );
			$logger->error( __METHOD__ . ": cannot commit; $fname does not have outer scope." );
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
		$request = RequestContext::getMain()->getRequest();
		$chronProt = new ChronologyProtector(
			ObjectCache::getMainStashInstance(),
			[
				'ip' => $request->getIP(),
				'agent' => $request->getHeader( 'User-Agent' ),
			],
			$request->getFloat( 'cpPosTime', $request->getCookie( 'cpPosTime', '' ) )
		);
		if ( PHP_SAPI === 'cli' ) {
			$chronProt->setEnabled( false );
		} elseif ( $request->getHeader( 'ChronologyProtection' ) === 'false' ) {
			// Request opted out of using position wait logic. This is useful for requests
			// done by the job queue or background ETL that do not have a meaningful session.
			$chronProt->setWaitEnabled( false );
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
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $cp ) {
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
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $unsavedPositions ) {
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
			'localDomain' => wfWikiID(),
			'readOnlyReason' => $this->readOnlyReason,
			'srvCache' => $this->srvCache,
			'wanCache' => $this->wanCache,
			'trxProfiler' => $this->trxProfiler,
			'queryLogger' => LoggerFactory::getInstance( 'DBQuery' ),
			'connLogger' => LoggerFactory::getInstance( 'DBConnection' ),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ]
		];
	}

	/**
	 * @param LoadBalancer $lb
	 */
	protected function initLoadBalancer( LoadBalancer $lb ) {
		if ( $this->trxRoundId !== false ) {
			$lb->beginMasterChanges( $this->trxRoundId ); // set DBO_TRX
		}
	}

	/**
	 * Append ?cpPosTime parameter to a URL for ChronologyProtector purposes if needed
	 *
	 * Note that unlike cookies, this works accross domains
	 *
	 * @param string $url
	 * @param float $time UNIX timestamp just before shutdown() was called
	 * @return string
	 * @since 1.28
	 */
	public function appendPreShutdownTimeAsQuery( $url, $time ) {
		$usedCluster = 0;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$usedCluster ) {
			$usedCluster |= ( $lb->getServerCount() > 1 );
		} );

		if ( !$usedCluster ) {
			return $url; // no master/replica clusters touched
		}

		return wfAppendQuery( $url, [ 'cpPosTime' => $time ] );
	}

	/**
	 * Close all open database connections on all open load balancers.
	 * @since 1.28
	 */
	public function closeAll() {
		$this->forEachLBCallMethod( 'closeAll', [] );
	}
}
