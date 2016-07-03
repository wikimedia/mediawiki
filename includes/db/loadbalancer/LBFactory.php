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
use MediaWiki\Logger\LoggerFactory;

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
	protected $logger;

	/** @var LBFactory */
	private static $instance;

	/** @var string|bool Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;

	const SHUTDOWN_NO_CHRONPROT = 1; // don't save ChronologyProtector positions (for async code)

	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 */
	public function __construct( array $conf ) {
		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}

		$this->chronProt = $this->newChronologyProtector();
		$this->trxProfiler = Profiler::instance()->getTransactionProfiler();
		$this->logger = LoggerFactory::getInstance( 'DBTransaction' );
	}

	/**
	 * Disables all access to the load balancer, will cause all database access
	 * to throw a DBAccessError
	 */
	public static function disableBackend() {
		global $wgLBFactoryConf;
		self::$instance = new LBFactoryFake( $wgLBFactoryConf );
	}

	/**
	 * Get an LBFactory instance
	 *
	 * @return LBFactory
	 */
	public static function singleton() {
		global $wgLBFactoryConf;

		if ( is_null( self::$instance ) ) {
			$class = self::getLBFactoryClass( $wgLBFactoryConf );
			$config = $wgLBFactoryConf;
			if ( !isset( $config['readOnlyReason'] ) ) {
				$config['readOnlyReason'] = wfConfiguredReadOnlyReason();
			}
			self::$instance = new $class( $config );
		}

		return self::$instance;
	}

	/**
	 * Returns the LBFactory class to use and the load balancer configuration.
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
	 */
	public static function destroyInstance() {
		if ( self::$instance ) {
			self::$instance->shutdown();
			self::$instance->forEachLBCallMethod( 'closeAll' );
			self::$instance = null;
		}
	}

	/**
	 * Set the instance to be the given object
	 *
	 * @param LBFactory $instance
	 */
	public static function setInstance( $instance ) {
		self::destroyInstance();
		self::$instance = $instance;
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
	abstract public function &getExternalLB( $cluster, $wiki = false );

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
	 * @param integer $flags Supports SHUTDOWN_* flags
	 * STUB
	 */
	public function shutdown( $flags = 0 ) {
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
	 * Commit on all connections. Done for two reasons:
	 * 1. To commit changes to the masters.
	 * 2. To release the snapshot on all connections, master and slave.
	 * @param string $fname Caller name
	 */
	public function commitAll( $fname = __METHOD__ ) {
		$this->logMultiDbTransaction();

		$start = microtime( true );
		$this->forEachLBCallMethod( 'commitAll', [ $fname ] );
		$timeMs = 1000 * ( microtime( true ) - $start );

		RequestContext::getMain()->getStats()->timing( "db.commit-all", $timeMs );
	}

	/**
	 * Commit changes on all master connections
	 * @param string $fname Caller name
	 * @param array $options Options map:
	 *   - maxWriteDuration: abort if more than this much time was spent in write queries
	 */
	public function commitMasterChanges( $fname = __METHOD__, array $options = [] ) {
		$limit = isset( $options['maxWriteDuration'] ) ? $options['maxWriteDuration'] : 0;

		$this->logMultiDbTransaction();
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $limit ) {
			$lb->forEachOpenConnection( function ( IDatabase $db ) use ( $limit ) {
				$time = $db->pendingWriteQueryDuration();
				if ( $limit > 0 && $time > $limit ) {
					throw new DBTransactionError(
						$db,
						wfMessage( 'transaction-duration-limit-exceeded', $time, $limit )->text()
					);
				}
			} );
		} );

		$this->forEachLBCallMethod( 'commitMasterChanges', [ $fname ] );
	}

	/**
	 * Rollback changes on all master connections
	 * @param string $fname Caller name
	 * @since 1.23
	 */
	public function rollbackMasterChanges( $fname = __METHOD__ ) {
		$this->forEachLBCallMethod( 'rollbackMasterChanges', [ $fname ] );
	}

	/**
	 * Log query info if multi DB transactions are going to be committed now
	 */
	private function logMultiDbTransaction() {
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
			$this->logger->info( $msg );
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
	 * Detemine if any lagged slave connection was used
	 * @since 1.27
	 * @return bool
	 */
	public function laggedSlaveUsed() {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->laggedSlaveUsed();
		} );

		return $ret;
	}

	/**
	 * Determine if any master connection has pending/written changes from this request
	 * @return bool
	 * @since 1.27
	 */
	public function hasOrMadeRecentMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasOrMadeRecentMasterChanges();
		} );
		return $ret;
	}

	/**
	 * Waits for the slave DBs to catch up to the current master position
	 *
	 * Use this when updating very large numbers of rows, as in maintenance scripts,
	 * to avoid causing too much lag. Of course, this is a no-op if there are no slaves.
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
				// Bug 27975 - Don't try to wait for slaves if there are none
				// Prevents permission error when getting master position
				continue;
			} elseif ( $opts['ifWritesSince']
				&& $lb->lastMasterChangeTimestamp() < $opts['ifWritesSince']
			) {
				continue; // no writes since the last wait
			}
			$masterPositions[$i] = $lb->getMasterPos();
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
				"Could not wait for slaves to catch up to " .
				implode( ', ', $failed )
			);
		}
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
				'agent' => $request->getHeader( 'User-Agent' )
			]
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
	 * @param ChronologyProtector $cp
	 */
	protected function shutdownChronologyProtector( ChronologyProtector $cp ) {
		// Get all the master positions needed
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $cp ) {
			$cp->shutdownLB( $lb );
		} );
		// Write them to the stash
		$unsavedPositions = $cp->shutdown();
		// If the positions failed to write to the stash, at least wait on local datacenter
		// slaves to catch up before responding. Even if there are several DCs, this increases
		// the chance that the user will see their own changes immediately afterwards. As long
		// as the sticky DC cookie applies (same domain), this is not even an issue.
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $unsavedPositions ) {
			$masterName = $lb->getServerName( $lb->getWriterIndex() );
			if ( isset( $unsavedPositions[$masterName] ) ) {
				$lb->waitForAll( $unsavedPositions[$masterName] );
			}
		} );
	}
}

/**
 * Exception class for attempted DB access
 */
class DBAccessError extends MWException {
	public function __construct() {
		parent::__construct( "Mediawiki tried to access the database via wfGetDB(). " .
			"This is not allowed." );
	}
}

/**
 * Exception class for replica DB wait timeouts
 */
class DBReplicationWaitError extends Exception {
}
