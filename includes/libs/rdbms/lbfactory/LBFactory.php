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
use LogicException;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactory implements ILBFactory {
	/** @var ChronologyProtector */
	private $chronProt;
	/** @var object|string Class name or object With profileIn/profileOut methods */
	private $profiler;
	/** @var TransactionProfiler */
	private $trxProfiler;
	/** @var LoggerInterface */
	private $replLogger;
	/** @var LoggerInterface */
	private $connLogger;
	/** @var LoggerInterface */
	private $queryLogger;
	/** @var LoggerInterface */
	private $perfLogger;
	/** @var callable Error logger */
	private $errorLogger;
	/** @var callable Deprecation logger */
	private $deprecationLogger;

	/** @var BagOStuff */
	protected $srvCache;
	/** @var BagOStuff */
	protected $memStash;
	/** @var WANObjectCache */
	protected $wanCache;

	/** @var DatabaseDomain Local domain */
	protected $localDomain;

	/** @var string Local hostname of the app server */
	private $hostname;
	/** @var array Web request information about the client */
	private $requestInfo;
	/** @var bool Whether this PHP instance is for a CLI script */
	private $cliMode;
	/** @var string Agent name for query profiling */
	private $agent;

	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	private $tableAliases = [];
	/** @var string[] Map of (index alias => index) */
	private $indexAliases = [];
	/** @var callable[] */
	private $replicationWaitCallbacks = [];

	/** @var mixed */
	private $ticket;
	/** @var string|bool String if a requested DBO_TRX transaction round is active */
	private $trxRoundId = false;
	/** @var string One of the ROUND_* class constants */
	private $trxRoundStage = self::ROUND_CURSORY;

	/** @var string|bool Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;

	/** @var string|null */
	private $defaultGroup = null;

	const ROUND_CURSORY = 'cursory';
	const ROUND_BEGINNING = 'within-begin';
	const ROUND_COMMITTING = 'within-commit';
	const ROUND_ROLLING_BACK = 'within-rollback';
	const ROUND_COMMIT_CALLBACKS = 'within-commit-callbacks';
	const ROUND_ROLLBACK_CALLBACKS = 'within-rollback-callbacks';

	private static $loggerFields =
		[ 'replLogger', 'connLogger', 'queryLogger', 'perfLogger' ];

	public function __construct( array $conf ) {
		$this->localDomain = isset( $conf['localDomain'] )
			? DatabaseDomain::newFromId( $conf['localDomain'] )
			: DatabaseDomain::newUnspecified();

		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}

		$this->srvCache = $conf['srvCache'] ?? new EmptyBagOStuff();
		$this->memStash = $conf['memStash'] ?? new EmptyBagOStuff();
		$this->wanCache = $conf['wanCache'] ?? WANObjectCache::newEmpty();

		foreach ( self::$loggerFields as $key ) {
			$this->$key = $conf[$key] ?? new \Psr\Log\NullLogger();
		}
		$this->errorLogger = $conf['errorLogger'] ?? function ( Exception $e ) {
			trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
		};
		$this->deprecationLogger = $conf['deprecationLogger'] ?? function ( $msg ) {
			trigger_error( $msg, E_USER_DEPRECATED );
		};

		$this->profiler = $conf['profiler'] ?? null;
		$this->trxProfiler = $conf['trxProfiler'] ?? new TransactionProfiler();

		$this->requestInfo = [
			'IPAddress' => $_SERVER[ 'REMOTE_ADDR' ] ?? '',
			'UserAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
			// Headers application can inject via LBFactory::setRequestInfo()
			'ChronologyProtection' => null,
			'ChronologyClientId' => null, // prior $cpClientId value from LBFactory::shutdown()
			'ChronologyPositionIndex' => null // prior $cpIndex value from LBFactory::shutdown()
		];

		$this->cliMode = $conf['cliMode'] ?? ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' );
		$this->hostname = $conf['hostname'] ?? gethostname();
		$this->agent = $conf['agent'] ?? '';
		$this->defaultGroup = $conf['defaultGroup'] ?? null;

		$this->ticket = mt_rand();
	}

	public function destroy() {
		$this->shutdown( self::SHUTDOWN_NO_CHRONPROT );
		$this->forEachLBCallMethod( 'disable' );
	}

	public function getLocalDomainID() {
		return $this->localDomain->getId();
	}

	public function resolveDomainID( $domain ) {
		return ( $domain !== false ) ? (string)$domain : $this->getLocalDomainID();
	}

	public function shutdown(
		$mode = self::SHUTDOWN_CHRONPROT_SYNC,
		callable $workCallback = null,
		&$cpIndex = null,
		&$cpClientId = null
	) {
		$chronProt = $this->getChronologyProtector();
		if ( $mode === self::SHUTDOWN_CHRONPROT_SYNC ) {
			$this->shutdownChronologyProtector( $chronProt, $workCallback, 'sync', $cpIndex );
		} elseif ( $mode === self::SHUTDOWN_CHRONPROT_ASYNC ) {
			$this->shutdownChronologyProtector( $chronProt, null, 'async', $cpIndex );
		}

		$cpClientId = $chronProt->getClientId();

		$this->commitMasterChanges( __METHOD__ ); // sanity
	}

	/**
	 * @see ILBFactory::newMainLB()
	 * @param bool $domain
	 * @return ILoadBalancer
	 */
	abstract public function newMainLB( $domain = false );

	/**
	 * @see ILBFactory::getMainLB()
	 * @param bool $domain
	 * @return ILoadBalancer
	 */
	abstract public function getMainLB( $domain = false );

	/**
	 * @see ILBFactory::newExternalLB()
	 * @param string $cluster
	 * @return ILoadBalancer
	 */
	abstract public function newExternalLB( $cluster );

	/**
	 * @see ILBFactory::getExternalLB()
	 * @param string $cluster
	 * @return ILoadBalancer
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
				$loadBalancer->$methodName( ...$args );
			},
			[ $methodName, $args ]
		);
	}

	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		$this->forEachLBCallMethod( 'flushReplicaSnapshots', [ $fname ] );
	}

	final public function commitAll( $fname = __METHOD__, array $options = [] ) {
		$this->commitMasterChanges( $fname, $options );
		$this->forEachLBCallMethod( 'flushMasterSnapshots', [ $fname ] );
		$this->forEachLBCallMethod( 'flushReplicaSnapshots', [ $fname ] );
	}

	final public function beginMasterChanges( $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		$this->trxRoundStage = self::ROUND_BEGINNING;
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' already started."
			);
		}
		$this->trxRoundId = $fname;
		// Set DBO_TRX flags on all appropriate DBs
		$this->forEachLBCallMethod( 'beginMasterChanges', [ $fname ] );
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	final public function commitMasterChanges( $fname = __METHOD__, array $options = [] ) {
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		$this->trxRoundStage = self::ROUND_COMMITTING;
		if ( $this->trxRoundId !== false && $this->trxRoundId !== $fname ) {
			throw new DBTransactionError(
				null,
				"$fname: transaction round '{$this->trxRoundId}' still running."
			);
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = $this->getScopedPHPBehaviorForCommit(); // try to ignore client aborts
		// Run pre-commit callbacks and suppress post-commit callbacks, aborting on failure
		do {
			$count = 0; // number of callbacks executed this iteration
			$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$count ) {
				$count += $lb->finalizeMasterChanges();
			} );
		} while ( $count > 0 );
		$this->trxRoundId = false;
		// Perform pre-commit checks, aborting on failure
		$this->forEachLBCallMethod( 'approveMasterChanges', [ $options ] );
		// Log the DBs and methods involved in multi-DB transactions
		$this->logIfMultiDbTransaction();
		// Actually perform the commit on all master DB connections and revert DBO_TRX
		$this->forEachLBCallMethod( 'commitMasterChanges', [ $fname ] );
		// Run all post-commit callbacks in a separate step
		$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
		$e = $this->executePostTransactionCallbacks();
		$this->trxRoundStage = self::ROUND_CURSORY;
		// Throw any last post-commit callback error
		if ( $e instanceof Exception ) {
			throw $e;
		}
	}

	final public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$this->trxRoundStage = self::ROUND_ROLLING_BACK;
		$this->trxRoundId = false;
		// Actually perform the rollback on all master DB connections and revert DBO_TRX
		$this->forEachLBCallMethod( 'rollbackMasterChanges', [ $fname ] );
		// Run all post-commit callbacks in a separate step
		$this->trxRoundStage = self::ROUND_ROLLBACK_CALLBACKS;
		$this->executePostTransactionCallbacks();
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	/**
	 * @return Exception|null
	 */
	private function executePostTransactionCallbacks() {
		// Run all post-commit callbacks until new ones stop getting added
		$e = null; // first callback exception
		do {
			$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$e ) {
				$ex = $lb->runMasterTransactionIdleCallbacks();
				$e = $e ?: $ex;
			} );
		} while ( $this->hasMasterChanges() );
		// Run all listener callbacks once
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$e ) {
			$ex = $lb->runMasterTransactionListenerCallbacks();
			$e = $e ?: $ex;
		} );

		return $e;
	}

	public function hasTransactionRound() {
		return ( $this->trxRoundId !== false );
	}

	public function isReadyForRoundOperations() {
		return ( $this->trxRoundStage === self::ROUND_CURSORY );
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
			'timeout' => $this->cliMode ? 60 : 1,
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
				// The RDBMS may not support getMasterPos()
				if ( !$lb->waitForAll( $masterPositions[$i], $opts['timeout'] ) ) {
					$failed[] = $lb->getServerName( $lb->getWriterIndex() );
				}
			}
		}

		return !$failed;
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

	final public function commitAndWaitForReplication( $fname, $ticket, array $opts = [] ) {
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
		$waitSucceeded = $this->waitForReplication( $opts );
		// If a nested caller committed on behalf of $fname, start another empty $fname
		// transaction, leaving the caller with the same empty transaction state as before.
		if ( $fnameEffective !== $fname ) {
			$this->beginMasterChanges( $fnameEffective );
		}
		return $waitSucceeded;
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
			$this->memStash,
			[
				'ip' => $this->requestInfo['IPAddress'],
				'agent' => $this->requestInfo['UserAgent'],
				'clientId' => $this->requestInfo['ChronologyClientId']
			],
			$this->requestInfo['ChronologyPositionIndex']
		);
		$this->chronProt->setLogger( $this->replLogger );

		if ( $this->cliMode ) {
			$this->chronProt->setEnabled( false );
		} elseif ( $this->requestInfo['ChronologyProtection'] === 'false' ) {
			// Request opted out of using position wait logic. This is useful for requests
			// done by the job queue or background ETL that do not have a meaningful session.
			$this->chronProt->setWaitEnabled( false );
		} elseif ( $this->memStash instanceof EmptyBagOStuff ) {
			// No where to store any DB positions and wait for them to appear
			$this->chronProt->setEnabled( false );
			$this->replLogger->info( 'Cannot use ChronologyProtector with EmptyBagOStuff.' );
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
	 * @param int|null &$cpIndex DB position key write counter; incremented on update
	 */
	protected function shutdownChronologyProtector(
		ChronologyProtector $cp, $workCallback, $mode, &$cpIndex = null
	) {
		// Record all the master positions needed
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $cp ) {
			$cp->shutdownLB( $lb );
		} );
		// Write them to the persistent stash. Try to do something useful by running $work
		// while ChronologyProtector waits for the stash write to replicate to all DCs.
		$unsavedPositions = $cp->shutdown( $workCallback, $mode, $cpIndex );
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
	 * Base parameters to ILoadBalancer::__construct()
	 * @return array
	 */
	final protected function baseLoadBalancerParams() {
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$initStage = ILoadBalancer::STAGE_POSTCOMMIT_CALLBACKS;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$initStage = ILoadBalancer::STAGE_POSTROLLBACK_CALLBACKS;
		} else {
			$initStage = null;
		}

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
			'deprecationLogger' => $this->deprecationLogger,
			'hostname' => $this->hostname,
			'cliMode' => $this->cliMode,
			'agent' => $this->agent,
			'defaultGroup' => $this->defaultGroup,
			'chronologyCallback' => function ( ILoadBalancer $lb ) {
				// Defer ChronologyProtector construction in case setRequestInfo() ends up
				// being called later (but before the first connection attempt) (T192611)
				$this->getChronologyProtector()->initLB( $lb );
			},
			'roundStage' => $initStage
		];
	}

	/**
	 * @param ILoadBalancer $lb
	 */
	protected function initLoadBalancer( ILoadBalancer $lb ) {
		if ( $this->trxRoundId !== false ) {
			$lb->beginMasterChanges( $this->trxRoundId ); // set DBO_TRX
		}

		$lb->setTableAliases( $this->tableAliases );
		$lb->setIndexAliases( $this->indexAliases );
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
	}

	public function setDomainPrefix( $prefix ) {
		$this->localDomain = new DatabaseDomain(
			$this->localDomain->getDatabase(),
			null,
			$prefix
		);

		$this->forEachLB( function ( ILoadBalancer $lb ) use ( $prefix ) {
			$lb->setDomainPrefix( $prefix );
		} );
	}

	public function redefineLocalDomain( $domain ) {
		$this->closeAll();

		$this->localDomain = DatabaseDomain::newFromId( $domain );

		$this->forEachLB( function ( ILoadBalancer $lb ) {
			$lb->redefineLocalDomain( $this->localDomain );
		} );
	}

	public function closeAll() {
		$this->forEachLBCallMethod( 'closeAll', [] );
	}

	public function setAgentName( $agent ) {
		$this->agent = $agent;
	}

	public function appendShutdownCPIndexAsQuery( $url, $index ) {
		$usedCluster = 0;
		$this->forEachLB( function ( ILoadBalancer $lb ) use ( &$usedCluster ) {
			$usedCluster |= ( $lb->getServerCount() > 1 );
		} );

		if ( !$usedCluster ) {
			return $url; // no master/replica clusters touched
		}

		return strpos( $url, '?' ) === false ? "$url?cpPosIndex=$index" : "$url&cpPosIndex=$index";
	}

	/**
	 * @param int $index Write index
	 * @param int $time UNIX timestamp; can be used to detect stale cookies (T190082)
	 * @param string $clientId Agent ID hash from ILBFactory::shutdown()
	 * @return string Timestamp-qualified write index of the form "<index>@<timestamp>#<hash>"
	 * @since 1.32
	 */
	public static function makeCookieValueFromCPIndex( $index, $time, $clientId ) {
		return "$index@$time#$clientId";
	}

	/**
	 * @param string $value Possible result of LBFactory::makeCookieValueFromCPIndex()
	 * @param int $minTimestamp Lowest UNIX timestamp that a non-expired value can have
	 * @return array (index: int or null, clientId: string or null)
	 * @since 1.32
	 */
	public static function getCPInfoFromCookieValue( $value, $minTimestamp ) {
		static $placeholder = [ 'index' => null, 'clientId' => null ];

		if ( !preg_match( '/^(\d+)@(\d+)#([0-9a-f]{32})$/', $value, $m ) ) {
			return $placeholder; // invalid
		}

		$index = (int)$m[1];
		if ( $index <= 0 ) {
			return $placeholder; // invalid
		} elseif ( isset( $m[2] ) && $m[2] !== '' && (int)$m[2] < $minTimestamp ) {
			return $placeholder; // expired
		}

		$clientId = ( isset( $m[3] ) && $m[3] !== '' ) ? $m[3] : null;

		return [ 'index' => $index, 'clientId' => $clientId ];
	}

	public function setRequestInfo( array $info ) {
		if ( $this->chronProt ) {
			throw new LogicException( 'ChronologyProtector already initialized.' );
		}

		$this->requestInfo = $info + $this->requestInfo;
	}

	/**
	 * @param string $stage
	 */
	private function assertTransactionRoundStage( $stage ) {
		if ( $this->trxRoundStage !== $stage ) {
			throw new DBTransactionError(
				null,
				"Transaction round stage must be '$stage' (not '{$this->trxRoundStage}')"
			);
		}
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
