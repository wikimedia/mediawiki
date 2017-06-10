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

namespace Wikimedia\Rdbms;

use Psr\Log\LoggerInterface;
use Wikimedia\ScopedCallback;
use BagOStuff;
use EmptyBagOStuff;
use WANObjectCache;
use Exception;
use RuntimeException;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactory implements ILBFactory {
	/** @var ChronologyProtector */
	protected $chronProt;
	/** @var object|string Class name or object With profileIn/profileOut methods */
	protected $profiler;
	/** @var TransactionProfiler */
	protected $trxProfiler;
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

	/** @var DatabaseDomain Local domain */
	protected $localDomain;
	/** @var string Local hostname of the app server */
	protected $hostname;
	/** @var array Web request information about the client */
	protected $requestInfo;

	/** @var mixed */
	protected $ticket;
	/** @var string|bool String if a requested DBO_TRX transaction round is active */
	protected $trxRoundId = false;
	/** @var string|bool Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;
	/** @var callable[] */
	protected $replicationWaitCallbacks = [];

	/** @var bool Whether this PHP instance is for a CLI script */
	protected $cliMode;
	/** @var string Agent name for query profiling */
	protected $agent;

	private static $loggerFields =
		[ 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ];

	public function __construct( array $conf ) {
		$this->localDomain = isset( $conf['localDomain'] )
			? DatabaseDomain::newFromId( $conf['localDomain'] )
			: DatabaseDomain::newUnspecified();

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
				trigger_error( E_USER_WARNING, get_class( $e ) . ': ' . $e->getMessage() );
			};

		$this->profiler = isset( $conf['profiler'] ) ? $conf['profiler'] : null;
		$this->trxProfiler = isset( $conf['trxProfiler'] )
			? $conf['trxProfiler']
			: new TransactionProfiler();

		$this->requestInfo = [
			'IPAddress' => isset( $_SERVER[ 'REMOTE_ADDR' ] ) ? $_SERVER[ 'REMOTE_ADDR' ] : '',
			'UserAgent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '',
			'ChronologyProtection' => 'true'
		];

		$this->cliMode = isset( $conf['cliMode'] ) ? $conf['cliMode'] : PHP_SAPI === 'cli';
		$this->hostname = isset( $conf['hostname'] ) ? $conf['hostname'] : gethostname();
		$this->agent = isset( $conf['agent'] ) ? $conf['agent'] : '';

		$this->ticket = mt_rand();
	}

	public function destroy() {
		$this->shutdown( self::SHUTDOWN_NO_CHRONPROT );
		$this->forEachLBCallMethod( 'disable' );
	}

	public function shutdown(
		$mode = self::SHUTDOWN_CHRONPROT_SYNC, callable $workCallback = null
	) {
		$chronProt = $this->getChronologyProtector();
		if ( $mode === self::SHUTDOWN_CHRONPROT_SYNC ) {
			$this->shutdownChronologyProtector( $chronProt, $workCallback, 'sync' );
		} elseif ( $mode === self::SHUTDOWN_CHRONPROT_ASYNC ) {
			$this->shutdownChronologyProtector( $chronProt, null, 'async' );
		}

		$this->commitMasterChanges( __METHOD__ ); // sanity
	}

	/**
	 * @see ILBFactory::newMainLB()
	 * @param bool $domain
	 * @return LoadBalancer
	 */
	abstract public function newMainLB( $domain = false );

	/**
	 * @see ILBFactory::getMainLB()
	 * @param bool $domain
	 * @return LoadBalancer
	 */
	abstract public function getMainLB( $domain = false );

	/**
	 * @see ILBFactory::newExternalLB()
	 * @param string $cluster
	 * @return LoadBalancer
	 */
	abstract public function newExternalLB( $cluster );

	/**
	 * @see ILBFactory::getExternalLB()
	 * @param string $cluster
	 * @return LoadBalancer
	 */
	abstract public function getExternalLB( $cluster );

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

	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		$this->forEachLBCallMethod( 'flushReplicaSnapshots', [ $fname ] );
	}

	public function commitAll( $fname = __METHOD__, array $options = [] ) {
		$this->commitMasterChanges( $fname, $options );
		$this->forEachLBCallMethod( 'commitAll', [ $fname ] );
	}

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

	public function commitMasterChanges( $fname = __METHOD__, array $options = [] ) {
		if ( $this->trxRoundId !== false && $this->trxRoundId !== $fname ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' still running."
			);
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = $this->getScopedPHPBehaviorForCommit(); // try to ignore client aborts
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

	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$this->trxRoundId = false;
		$this->forEachLBCallMethod( 'suppressTransactionEndCallbacks' );
		$this->forEachLBCallMethod( 'rollbackMasterChanges', [ $fname ] );
		// Run all post-rollback callbacks
		$this->forEachLB( function ( ILoadBalancer $lb ) {
			$lb->runMasterPostTrxCallbacks( IDatabase::TRIGGER_ROLLBACK );
		} );
	}

	public function hasTransactionRound() {
		return ( $this->trxRoundId !== false );
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
			$this->queryLogger->info( $msg );
		}
	}

	public function hasMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasMasterChanges();
		} );

		return $ret;
	}

	public function laggedReplicaUsed() {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->laggedReplicaUsed();
		} );

		return $ret;
	}

	public function hasOrMadeRecentMasterChanges( $age = null ) {
		$ret = false;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $age, &$ret ) {
			$ret = $ret || $lb->hasOrMadeRecentMasterChanges( $age );
		} );
		return $ret;
	}

	public function waitForReplication( array $opts = [] ) {
		$opts += [
			'domain' => false,
			'cluster' => false,
			'timeout' => 60,
			'ifWritesSince' => null
		];

		if ( $opts['domain'] === false && isset( $opts['wiki'] ) ) {
			$opts['domain'] = $opts['wiki']; // b/c
		}

		// Figure out which clusters need to be checked
		/** @var ILoadBalancer[] $lbs */
		$lbs = [];
		if ( $opts['cluster'] !== false ) {
			$lbs[] = $this->getExternalLB( $opts['cluster'] );
		} elseif ( $opts['domain'] !== false ) {
			$lbs[] = $this->getMainLB( $opts['domain'] );
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
				// T29975 - Don't try to wait for replica DBs if there are none
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
				// The DBMS may not support getMasterPos()
				if ( !$lb->waitForAll( $masterPositions[$i], $opts['timeout'] ) ) {
					$failed[] = $lb->getServerName( $lb->getWriterIndex() );
				}
			}
		}

		if ( $failed ) {
			throw new DBReplicationWaitError(
				null,
				"Could not wait for replica DBs to catch up to " .
				implode( ', ', $failed )
			);
		}
	}

	public function setWaitForReplicationListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->replicationWaitCallbacks[$name] = $callback;
		} else {
			unset( $this->replicationWaitCallbacks[$name] );
		}
	}

	public function getEmptyTransactionTicket( $fname ) {
		if ( $this->hasMasterChanges() ) {
			$this->queryLogger->error( __METHOD__ . ": $fname does not have outer scope.\n" .
				( new RuntimeException() )->getTraceAsString() );

			return null;
		}

		return $this->ticket;
	}

	public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] ) {
		if ( $ticket !== $this->ticket ) {
			$this->perfLogger->error( __METHOD__ . ": $fname does not have outer scope.\n" .
				( new RuntimeException() )->getTraceAsString() );

			return;
		}

		// The transaction owner and any caller with the empty transaction ticket can commit
		// so that getEmptyTransactionTicket() callers don't risk seeing DBTransactionError.
		if ( $this->trxRoundId !== false && $fname !== $this->trxRoundId ) {
			$this->queryLogger->info( "$fname: committing on behalf of {$this->trxRoundId}." );
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

	public function getChronologyProtectorTouched( $dbName ) {
		return $this->getChronologyProtector()->getTouched( $dbName );
	}

	public function disableChronologyProtection() {
		$this->getChronologyProtector()->setEnabled( false );
	}

	/**
	 * @return ChronologyProtector
	 */
	protected function getChronologyProtector() {
		if ( $this->chronProt ) {
			return $this->chronProt;
		}

		$this->chronProt = new ChronologyProtector(
			$this->memCache,
			[
				'ip' => $this->requestInfo['IPAddress'],
				'agent' => $this->requestInfo['UserAgent'],
			],
			isset( $_GET['cpPosTime'] ) ? $_GET['cpPosTime'] : null
		);
		$this->chronProt->setLogger( $this->replLogger );

		if ( $this->cliMode ) {
			$this->chronProt->setEnabled( false );
		} elseif ( $this->requestInfo['ChronologyProtection'] === 'false' ) {
			// Request opted out of using position wait logic. This is useful for requests
			// done by the job queue or background ETL that do not have a meaningful session.
			$this->chronProt->setWaitEnabled( false );
		}

		$this->replLogger->debug( __METHOD__ . ': using request info ' .
			json_encode( $this->requestInfo, JSON_PRETTY_PRINT ) );

		return $this->chronProt;
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
			'localDomain' => $this->localDomain,
			'readOnlyReason' => $this->readOnlyReason,
			'srvCache' => $this->srvCache,
			'wanCache' => $this->wanCache,
			'profiler' => $this->profiler,
			'trxProfiler' => $this->trxProfiler,
			'queryLogger' => $this->queryLogger,
			'connLogger' => $this->connLogger,
			'replLogger' => $this->replLogger,
			'errorLogger' => $this->errorLogger,
			'hostname' => $this->hostname,
			'cliMode' => $this->cliMode,
			'agent' => $this->agent,
			'chronologyProtector' => $this->getChronologyProtector()
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

	public function setDomainPrefix( $prefix ) {
		$this->localDomain = new DatabaseDomain(
			$this->localDomain->getDatabase(),
			null,
			$prefix
		);

		$this->forEachLB( function( ILoadBalancer $lb ) use ( $prefix ) {
			$lb->setDomainPrefix( $prefix );
		} );
	}

	public function closeAll() {
		$this->forEachLBCallMethod( 'closeAll', [] );
	}

	public function setAgentName( $agent ) {
		$this->agent = $agent;
	}

	public function appendPreShutdownTimeAsQuery( $url, $time ) {
		$usedCluster = 0;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$usedCluster ) {
			$usedCluster |= ( $lb->getServerCount() > 1 );
		} );

		if ( !$usedCluster ) {
			return $url; // no master/replica clusters touched
		}

		return strpos( $url, '?' ) === false ? "$url?cpPosTime=$time" : "$url&cpPosTime=$time";
	}

	public function setRequestInfo( array $info ) {
		$this->requestInfo = $info + $this->requestInfo;
	}

	/**
	 * Make PHP ignore user aborts/disconnects until the returned
	 * value leaves scope. This returns null and does nothing in CLI mode.
	 *
	 * @return ScopedCallback|null
	 */
	final protected function getScopedPHPBehaviorForCommit() {
		if ( PHP_SAPI != 'cli' ) { // https://bugs.php.net/bug.php?id=47540
			$old = ignore_user_abort( true ); // avoid half-finished operations
			return new ScopedCallback( function () use ( $old ) {
				ignore_user_abort( $old );
			} );
		}

		return null;
	}

	function __destruct() {
		$this->destroy();
	}
}

class_alias( LBFactory::class, 'LBFactory' );
