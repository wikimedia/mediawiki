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
namespace Wikimedia\Rdbms;

use ArrayUtils;
use BagOStuff;
use EmptyBagOStuff;
use InvalidArgumentException;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use NullStatsdDataFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use WANObjectCache;
use Wikimedia\ScopedCallback;

/**
 * @see ILoadBalancer
 * @ingroup Database
 */
class LoadBalancer implements ILoadBalancerForOwner {
	/** @var ILoadMonitor */
	private $loadMonitor;
	/** @var BagOStuff */
	private $srvCache;
	/** @var WANObjectCache */
	private $wanCache;
	/** @var DatabaseFactory */
	private $databaseFactory;

	/** @var TransactionProfiler */
	private $trxProfiler;
	/** @var StatsdDataFactoryInterface */
	private $statsd;
	/** @var LoggerInterface */
	private $logger;
	/** @var callable Exception logger */
	private $errorLogger;
	/** @var DatabaseDomain Local DB domain ID and default for new connections */
	private $localDomain;

	/** @var array<string,array<int,Database[]>> Map of (connection pool => server index => Database[]) */
	private $conns;

	/** @var string|null The name of the DB cluster */
	private $clusterName;
	/** @var ServerInfo */
	private $serverInfo;
	/** @var array[] Map of (group => server index => weight) */
	private $groupLoads;
	/** @var string|null Default query group to use with getConnection() */
	private $defaultGroup;

	/** @var array[] $aliases Map of (table => (dbname, schema, prefix) map) */
	private $tableAliases = [];
	/** @var string[] Map of (index alias => index) */
	private $indexAliases = [];
	/** @var DatabaseDomain[]|string[] Map of (domain alias => DB domain) */
	private $domainAliases = [];
	/** @var callable[] Map of (name => callable) */
	private $trxRecurringCallbacks = [];
	/** @var bool[] Map of (domain => whether to use "temp tables only" mode) */
	private $tempTablesOnlyMode = [];

	/** @var string|false Explicit DBO_TRX transaction round active or false if none */
	private $trxRoundId = false;
	/** @var string Stage of the current transaction round in the transaction round life-cycle */
	private $trxRoundStage = self::ROUND_CURSORY;
	/** @var int[] The group replica server indexes keyed by group */
	private $readIndexByGroup = [];
	/** @var DBPrimaryPos|null Replication sync position or false if not set */
	private $waitForPos;
	/** @var bool Whether a lagged replica DB was used */
	private $laggedReplicaMode = false;
	/** @var string|false Reason this instance is read-only or false if not */
	private $readOnlyReason = false;
	/** @var int Total number of new connections ever made with this instance */
	private $connectionCounter = 0;
	/** @var bool */
	private $disabled = false;
	private ?ChronologyProtector $chronologyProtector = null;
	/** @var bool Whether the session consistency callback already executed */
	private $chronologyProtectorCalled = false;

	/** @var Database|null The last connection handle that caused a problem */
	private $lastErrorConn;

	/** @var DatabaseDomain[] Map of (domain ID => domain instance) */
	private $nonLocalDomainCache = [];

	/**
	 * @var int Modification counter for invalidating connections held by
	 *      DBConnRef instances. This is bumped by reconfigure().
	 */
	private $modcount = 0;

	/** The "server index" LB info key; see {@link IDatabase::getLBInfo()} */
	private const INFO_SERVER_INDEX = 'serverIndex';
	/** The "connection category" LB info key; see {@link IDatabase::getLBInfo()} */
	private const INFO_CONN_CATEGORY = 'connCategory';

	/**
	 * Default 'maxLag' when unspecified
	 * @internal Only for use within LoadBalancer/LoadMonitor
	 */
	public const MAX_LAG_DEFAULT = ServerInfo::MAX_LAG_DEFAULT;

	/** Warn when this many connection are held */
	private const CONN_HELD_WARN_THRESHOLD = 10;

	/** Default 'waitTimeout' when unspecified */
	private const MAX_WAIT_DEFAULT = 10;
	/** Seconds to cache primary DB server read-only status */
	private const TTL_CACHE_READONLY = 5;

	/** A category of connections that are tracked and transaction round aware */
	private const CATEGORY_ROUND = 'round';
	/** A category of connections that are tracked and in autocommit-mode */
	private const CATEGORY_AUTOCOMMIT = 'auto-commit';
	/** A category of connections that are untracked and in gauge-mode */
	private const CATEGORY_GAUGE = 'gauge';

	/** Transaction round, explicit or implicit, has not finished writing */
	private const ROUND_CURSORY = 'cursory';
	/** Transaction round writes are complete and ready for pre-commit checks */
	private const ROUND_FINALIZED = 'finalized';
	/** Transaction round passed final pre-commit checks */
	private const ROUND_APPROVED = 'approved';
	/** Transaction round was committed and post-commit callbacks must be run */
	private const ROUND_COMMIT_CALLBACKS = 'commit-callbacks';
	/** Transaction round was rolled back and post-rollback callbacks must be run */
	private const ROUND_ROLLBACK_CALLBACKS = 'rollback-callbacks';
	/** Transaction round encountered an error */
	private const ROUND_ERROR = 'error';

	/** @var int Idiom for getExistingReaderIndex() meaning "no index selected" */
	private const READER_INDEX_NONE = -1;

	public function __construct( array $params ) {
		$this->configure( $params );

		$this->conns = self::newTrackedConnectionsArray();
	}

	/**
	 * @param array $params A database configuration array, see $wgLBFactoryConf.
	 *
	 * @return void
	 */
	protected function configure( array $params ): void {
		$this->localDomain = isset( $params['localDomain'] )
			? DatabaseDomain::newFromId( $params['localDomain'] )
			: DatabaseDomain::newUnspecified();

		$this->serverInfo = new ServerInfo();
		$this->groupLoads = [ self::GROUP_GENERIC => [] ];
		foreach ( $this->serverInfo->normalizeServerMaps( $params['servers'] ?? [] ) as $i => $server ) {
			$this->serverInfo->addServer( $i, $server );
			foreach ( $server['groupLoads'] as $group => $weight ) {
				$this->groupLoads[$group][$i] = $weight;
			}
			$this->groupLoads[self::GROUP_GENERIC][$i] = $server['load'];
		}

		if ( isset( $params['readOnlyReason'] ) && is_string( $params['readOnlyReason'] ) ) {
			$this->readOnlyReason = $params['readOnlyReason'];
		}

		$this->srvCache = $params['srvCache'] ?? new EmptyBagOStuff();
		$this->wanCache = $params['wanCache'] ?? WANObjectCache::newEmpty();

		// Note: this parameter is normally absent. It is injectable for testing purposes only.
		$this->databaseFactory = $params['databaseFactory'] ?? new DatabaseFactory( $params );

		$this->errorLogger = $params['errorLogger'] ?? static function ( Throwable $e ) {
				trigger_error( get_class( $e ) . ': ' . $e->getMessage(), E_USER_WARNING );
		};
		$this->logger = $params['logger'] ?? new NullLogger();

		$this->clusterName = $params['clusterName'] ?? null;
		$this->trxProfiler = $params['trxProfiler'] ?? new TransactionProfiler();
		$this->statsd = $params['statsdDataFactory'] ?? new NullStatsdDataFactory();

		// Set up LoadMonitor
		$loadMonitorConfig = $params['loadMonitor'] ?? [ 'class' => 'LoadMonitorNull' ];
		$loadMonitorConfig += [ 'lagWarnThreshold' => self::MAX_LAG_DEFAULT ];
		$compat = [
			'LoadMonitor' => LoadMonitor::class,
			'LoadMonitorNull' => LoadMonitorNull::class
		];
		$class = $loadMonitorConfig['class'];
		if ( isset( $compat[$class] ) ) {
			$class = $compat[$class];
		}
		$this->loadMonitor = new $class(
			$this, $this->srvCache, $this->wanCache, $loadMonitorConfig );
		$this->loadMonitor->setLogger( $this->logger );
		$this->loadMonitor->setStatsdDataFactory( $this->statsd );

		if ( isset( $params['chronologyProtector'] ) ) {
			$this->chronologyProtector = $params['chronologyProtector'];
		}

		if ( isset( $params['roundStage'] ) ) {
			if ( $params['roundStage'] === self::STAGE_POSTCOMMIT_CALLBACKS ) {
				$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
			} elseif ( $params['roundStage'] === self::STAGE_POSTROLLBACK_CALLBACKS ) {
				$this->trxRoundStage = self::ROUND_ROLLBACK_CALLBACKS;
			}
		}

		$group = $params['defaultGroup'] ?? self::GROUP_GENERIC;
		$this->defaultGroup = isset( $this->groupLoads[ $group ] ) ? $group : self::GROUP_GENERIC;
	}

	private static function newTrackedConnectionsArray() {
		// Note that CATEGORY_GAUGE connections are untracked
		return [
			self::CATEGORY_ROUND => [],
			self::CATEGORY_AUTOCOMMIT => []
		];
	}

	public function getClusterName(): string {
		// Fallback to the current primary name if not specified
		return $this->clusterName ?? $this->getServerName( ServerInfo::WRITER_INDEX );
	}

	public function getLocalDomainID(): string {
		return $this->localDomain->getId();
	}

	public function resolveDomainID( $domain ): string {
		return $this->resolveDomainInstance( $domain )->getId();
	}

	/**
	 * @param DatabaseDomain|string|false $domain
	 * @return DatabaseDomain
	 */
	final protected function resolveDomainInstance( $domain ): DatabaseDomain {
		if ( $domain instanceof DatabaseDomain ) {
			return $domain; // already a domain instance
		} elseif ( $domain === false || $domain === $this->localDomain->getId() ) {
			return $this->localDomain;
		} elseif ( isset( $this->domainAliases[$domain] ) ) {
			$this->domainAliases[$domain] =
				DatabaseDomain::newFromId( $this->domainAliases[$domain] );

			return $this->domainAliases[$domain];
		}

		$cachedDomain = $this->nonLocalDomainCache[$domain] ?? null;
		if ( $cachedDomain === null ) {
			$cachedDomain = DatabaseDomain::newFromId( $domain );
			$this->nonLocalDomainCache = [ $domain => $cachedDomain ];
		}

		return $cachedDomain;
	}

	/**
	 * Get the first group in $groups with assigned servers, falling back to the default group
	 *
	 * @param string[]|string|false $groups Query group(s) in preference order, [], or false
	 * @param int $i Specific server index or DB_PRIMARY/DB_REPLICA
	 * @return string Query group
	 */
	private function resolveGroups( $groups, $i ) {
		// If a specific replica server was specified, then $groups makes no sense
		if ( $i > 0 && $groups !== [] && $groups !== false ) {
			$list = implode( ', ', (array)$groups );
			throw new LogicException( "Query group(s) ($list) given with server index (#$i)" );
		}

		if ( $groups === [] || $groups === false || $groups === $this->defaultGroup ) {
			$resolvedGroup = $this->defaultGroup;
		} elseif ( is_string( $groups ) ) {
			$resolvedGroup = isset( $this->groupLoads[$groups] ) ? $groups : $this->defaultGroup;
		} elseif ( is_array( $groups ) ) {
			$resolvedGroup = $this->defaultGroup;
			foreach ( $groups as $group ) {
				if ( isset( $this->groupLoads[$group] ) ) {
					$resolvedGroup = $group;
					break;
				}
			}
		} else {
			$resolvedGroup = $this->defaultGroup;
		}

		return $resolvedGroup;
	}

	/**
	 * Sanitize connection flags provided by a call to getConnection()
	 *
	 * @param int $flags Bitfield of class CONN_* constants
	 * @param int $i Specific server index or DB_PRIMARY/DB_REPLICA
	 * @param string $domain Database domain
	 * @return int Sanitized bitfield
	 */
	private function sanitizeConnectionFlags( $flags, $i, $domain ) {
		// Whether an outside caller is explicitly requesting the primary database server
		if ( $i === self::DB_PRIMARY || $i === ServerInfo::WRITER_INDEX ) {
			$flags |= self::CONN_INTENT_WRITABLE;
		}

		if ( self::fieldHasBit( $flags, self::CONN_TRX_AUTOCOMMIT ) ) {
			// Callers use CONN_TRX_AUTOCOMMIT to bypass REPEATABLE-READ staleness without
			// resorting to row locks (e.g. FOR UPDATE) or to make small out-of-band commits
			// during larger transactions. This is useful for avoiding lock contention.
			// Assuming all servers are of the same type (or similar), which is overwhelmingly
			// the case, use the primary server information to get the attributes. The information
			// for $i cannot be used since it might be DB_REPLICA, which might require connection
			// attempts in order to be resolved into a real server index.
			$attributes = $this->getServerAttributes( ServerInfo::WRITER_INDEX );
			if ( $attributes[Database::ATTR_DB_LEVEL_LOCKING] ) {
				// The RDBMS does not support concurrent writes (e.g. SQLite), so attempts
				// to use separate connections would just cause self-deadlocks. Note that
				// REPEATABLE-READ staleness is not an issue since DB-level locking means
				// that transactions are Strict Serializable anyway.
				$flags &= ~self::CONN_TRX_AUTOCOMMIT;
				$type = $this->serverInfo->getServerType( ServerInfo::WRITER_INDEX );
				$this->logger->info( __METHOD__ . ": CONN_TRX_AUTOCOMMIT disallowed ($type)" );
			} elseif ( isset( $this->tempTablesOnlyMode[$domain] ) ) {
				// T202116: integration tests are active and queries should be all be using
				// temporary clone tables (via prefix). Such tables are not visible across
				// different connections nor can there be REPEATABLE-READ snapshot staleness,
				// so use the same connection for everything.
				$flags &= ~self::CONN_TRX_AUTOCOMMIT;
			}
		}

		return $flags;
	}

	/**
	 * @param IDatabase $conn
	 * @param int $flags
	 * @throws DBUnexpectedError
	 */
	private function enforceConnectionFlags( IDatabase $conn, $flags ) {
		if (
			self::fieldHasBit( $flags, self::CONN_TRX_AUTOCOMMIT ) ||
			// Handles with open transactions are avoided since they might be subject
			// to REPEATABLE-READ snapshots, which could affect the lag estimate query.
			self::fieldHasBit( $flags, self::CONN_UNTRACKED_GAUGE )
		) {
			if ( $conn->trxLevel() ) {
				throw new DBUnexpectedError(
					$conn,
					'Handle requested with autocommit-mode yet it has a transaction'
				);
			}

			$conn->clearFlag( $conn::DBO_TRX ); // auto-commit mode
		}
	}

	/**
	 * @param array $loads
	 * @param int|float $maxLag Restrict the maximum allowed lag to this many seconds, or INF for no max
	 * @return int|string|false
	 */
	private function getRandomNonLagged( array $loads, $maxLag = INF ) {
		$lags = $this->getLagTimes();

		# Unset excessively lagged servers
		foreach ( $lags as $i => $lag ) {
			if ( $i !== ServerInfo::WRITER_INDEX ) {
				# How much lag this server nominally is allowed to have
				$maxServerLag = $this->serverInfo->getServerMaxLag( $i ); // default
				# Constrain that further by $maxLag argument
				$maxServerLag = min( $maxServerLag, $maxLag );

				$srvName = $this->serverInfo->getServerName( $i );
				if ( $lag === false && !is_infinite( $maxServerLag ) ) {
					$this->logger->debug(
						__METHOD__ . ": server {db_server} is not replicating?",
						[ 'db_server' => $srvName ]
					);
					unset( $loads[$i] );
				} elseif ( $lag > $maxServerLag ) {
					$this->logger->debug(
						__METHOD__ .
							": server {db_server} has {lag} seconds of lag (>= {maxlag})",
						[ 'db_server' => $srvName, 'lag' => $lag, 'maxlag' => $maxServerLag ]
					);
					unset( $loads[$i] );
				}
			}
		}

		if ( array_sum( $loads ) == 0 ) {
			// All the replicas with non-zero load are lagged and the primary has zero load.
			// Inform caller so that it can use switch to read-only mode and use a lagged replica.
			return false;
		}

		# Return a random representative of the remainder
		return ArrayUtils::pickRandom( $loads );
	}

	public function getReaderIndex( $group = false ) {
		$group = is_string( $group ) ? $group : self::GROUP_GENERIC;

		if ( !$this->serverInfo->hasReplicaServers() ) {
			// There is only one possible server to use (the primary)
			return ServerInfo::WRITER_INDEX;
		}

		$index = $this->getExistingReaderIndex( $group );
		if ( $index !== self::READER_INDEX_NONE ) {
			// A reader index was already selected for this query group. Keep using it,
			// since any session replication position was already waited on and any
			// active transaction will be reused (e.g. for point-in-time snapshots).
			return $index;
		}

		// Get the server weight array for this load group
		$loads = $this->groupLoads[$group] ?? [];
		if ( !$loads ) {
			$this->logger->info( __METHOD__ . ": no loads for group $group" );
			return false;
		}

		// Load any session replication positions, before any connection attempts,
		// since reading them afterwards can only cause more delay due to possibly
		// seeing even higher replication positions (e.g. from concurrent requests).
		$this->loadSessionPrimaryPos();

		// Scale the configured load ratios according to each server's load/state.
		// This can sometimes trigger server connections due to cache regeneration.
		$this->loadMonitor->scaleLoads( $loads );

		// Pick a server, accounting for weight, load, lag, and session consistency
		$i = $this->pickReaderIndex( $loads );
		if ( $i === false ) {
			// Connection attempts failed
			return false;
		}

		// If data seen by queries is expected to reflect writes from a prior transaction,
		// then wait for the chosen server to apply those changes. This is used to improve
		// session consistency.
		if ( !$this->awaitSessionPrimaryPos( $i ) ) {
			// Data will be outdated compared to what was expected
			$this->setLaggedReplicaMode();
		}

		// Keep using this server for DB_REPLICA handles for this group
		if ( $i < 0 ) {
			throw new UnexpectedValueException( "Cannot set a negative read server index" );
		}
		$this->readIndexByGroup[$group] = $i;

		$serverName = $this->getServerName( $i );
		$this->logger->debug( __METHOD__ . ": using server $serverName for group '$group'" );

		return $i;
	}

	/**
	 * Get the server index chosen for DB_REPLICA connections for the given query group
	 *
	 * @param string $group Query group; use false for the generic group
	 * @return int Specific server index or LoadBalancer::READER_INDEX_NONE if none was chosen
	 */
	protected function getExistingReaderIndex( $group ) {
		return $this->readIndexByGroup[$group] ?? self::READER_INDEX_NONE;
	}

	/**
	 * Pick a server that is reachable, preferably non-lagged, and return its server index
	 *
	 * This will leave the server connection open within the pool for reuse
	 *
	 * @param array $loads List of server weights
	 * @return int|false reader index or false
	 */
	private function pickReaderIndex( array $loads ) {
		if ( $loads === [] ) {
			throw new InvalidArgumentException( "Server configuration array is empty" );
		}

		// Quickly look through the available servers for a server that meets criteria...
		$currentLoads = $loads;
		$i = false;
		while ( count( $currentLoads ) ) {
			if ( $this->waitForPos && $this->waitForPos->asOfTime() ) {
				$this->logger->debug( __METHOD__ . ": session has replication position" );
				// ChronologyProtector::getSessionPrimaryPos called in $this->loadSessionPrimaryPos()
				// sets "waitForPos" for session consistency.
				// This triggers doWait() after connect, so it's especially good to
				// avoid lagged servers so as to avoid excessive delay in that method.
				$ago = microtime( true ) - $this->waitForPos->asOfTime();
				// Aim for <= 1 second of waiting (being too picky can backfire)
				$i = $this->getRandomNonLagged( $currentLoads, $ago + 1 );
			} else {
				// Any server with less lag than it's 'max lag' param is preferable
				$i = $this->getRandomNonLagged( $currentLoads );
			}

			if ( $i === false && count( $currentLoads ) ) {
				$this->setLaggedReplicaMode();
				// All replica DBs lagged, just pick anything.
				$i = ArrayUtils::pickRandom( $currentLoads );
			}

			if ( $i === false ) {
				// pickRandom() returned false.
				// This is permanent and means the configuration or LoadMonitor
				// wants us to return false.
				$this->logger->debug( __METHOD__ . ": no suitable server found" );
				return false;
			}

			$serverName = $this->getServerName( $i );
			$this->logger->debug( __METHOD__ . ": connecting to $serverName..." );

			// Get a connection to this server without triggering complementary connections
			// to other servers (due to things like lag or read-only checks). We want to avoid
			// the risk of overhead and recursion here.
			$conn = $this->getServerConnection( $i, self::DOMAIN_ANY, self::CONN_SILENCE_ERRORS );
			if ( !$conn ) {
				$this->logger->warning( __METHOD__ . ": failed connecting to $serverName" );
				unset( $currentLoads[$i] ); // avoid this server next iteration
				continue;
			}

			// Return this server
			break;
		}

		// If all servers were down, quit now
		if ( $currentLoads === [] ) {
			$this->logger->error( __METHOD__ . ": all servers down" );
		}

		return $i;
	}

	public function waitForAll( DBPrimaryPos $pos, $timeout = null ) {
		$timeout = $timeout ?: self::MAX_WAIT_DEFAULT;

		$oldPos = $this->waitForPos;
		try {
			$this->waitForPos = $pos;

			$ok = true;
			foreach ( $this->serverInfo->getStreamingReplicaIndexes() as $i ) {
				if ( $this->serverHasLoadInAnyGroup( $i ) ) {
					$start = microtime( true );
					$ok = $this->awaitSessionPrimaryPos( $i, $timeout ) && $ok;
					$timeout -= intval( microtime( true ) - $start );
					if ( $timeout <= 0 ) {
						break; // timeout reached
					}
				}
			}

			return $ok;
		} finally {
			// Restore the old position; this is used for throttling, not lag-protection
			$this->waitForPos = $oldPos;
		}
	}

	/**
	 * @param int $i Specific server index
	 * @return bool
	 */
	private function serverHasLoadInAnyGroup( $i ) {
		foreach ( $this->groupLoads as $loadsByIndex ) {
			if ( ( $loadsByIndex[$i] ?? 0 ) > 0 ) {
				return true;
			}
		}

		return false;
	}

	public function getAnyOpenConnection( $i, $flags = 0 ) {
		$i = ( $i === self::DB_PRIMARY ) ? ServerInfo::WRITER_INDEX : $i;

		// Connection handles required to be in auto-commit mode use a separate connection
		// pool since the main pool is effected by implicit and explicit transaction rounds
		$autoCommitOnly = self::fieldHasBit( $flags, self::CONN_TRX_AUTOCOMMIT );

		$conn = false;
		foreach ( $this->conns as $type => $poolConnsByServer ) {
			if ( $i === self::DB_REPLICA ) {
				// Consider all existing connections to any server
				$applicableConnsByServer = $poolConnsByServer;
			} else {
				// Consider all existing connections to a specific server
				$applicableConnsByServer = isset( $poolConnsByServer[$i] )
					? [ $i => $poolConnsByServer[$i] ]
					: [];
			}

			$conn = $this->pickAnyOpenConnection( $applicableConnsByServer, $autoCommitOnly );
			if ( $conn ) {
				$this->logger->debug( __METHOD__ . ": found '$type' connection to #$i." );
				break;
			}
		}

		if ( $conn ) {
			$this->enforceConnectionFlags( $conn, $flags );
		}

		return $conn;
	}

	/**
	 * @param Database[][] $connsByServer Map of (server index => array of DB handles)
	 * @param bool $autoCommitOnly Whether to only look for auto-commit connections
	 * @return IDatabase|false An appropriate open connection or false if none found
	 */
	private function pickAnyOpenConnection( array $connsByServer, $autoCommitOnly ) {
		foreach ( $connsByServer as $i => $conns ) {
			foreach ( $conns as $conn ) {
				if ( !$conn->isOpen() ) {
					$this->logger->warning(
						__METHOD__ .
						": pooled DB handle for {db_server} (#$i) has no open connection.",
						$this->getConnLogContext( $conn )
					);
					continue; // some sort of error occurred?
				}

				if ( $autoCommitOnly ) {
					if (
						$conn->getLBInfo( self::INFO_CONN_CATEGORY ) !== self::CATEGORY_AUTOCOMMIT
					) {
						// Connection is aware of transaction rounds
						continue;
					}

					if ( $conn->trxLevel() ) {
						// Some sort of bug left a transaction open
						$this->logger->warning(
							__METHOD__ .
							": pooled DB handle for {db_server} (#$i) has a pending transaction.",
							$this->getConnLogContext( $conn )
						);
						continue;
					}
				}

				return $conn;
			}
		}

		return false;
	}

	/**
	 * Wait for a given replica DB to catch up to the primary DB pos stored in "waitForPos"
	 *
	 * @see loadSessionPrimaryPos()
	 *
	 * @param int $index Specific server index
	 * @param int|null $timeout Max seconds to wait; default is "waitTimeout"
	 * @return bool Success
	 */
	private function awaitSessionPrimaryPos( $index, $timeout = null ) {
		$timeout = max( 1, intval( $timeout ?: self::MAX_WAIT_DEFAULT ) );

		if ( !$this->waitForPos || $index === ServerInfo::WRITER_INDEX ) {
			return true;
		}

		$srvName = $this->getServerName( $index );

		// Check if we already know that the DB has reached this point
		$key = $this->srvCache->makeGlobalKey( __CLASS__, 'last-known-pos', $srvName, 'v2' );

		/** @var DBPrimaryPos $knownReachedPos */
		$position = $this->srvCache->get( $key );
		if ( !is_array( $position ) ) {
			$knownReachedPos = null;
		} else {
			$class = $position['_type_'];
			$knownReachedPos = $class::newFromArray( $position );
		}
		if (
			$knownReachedPos instanceof DBPrimaryPos &&
			$knownReachedPos->hasReached( $this->waitForPos )
		) {
			$this->logger->debug(
				__METHOD__ .
				": replica DB {db_server} known to be caught up (pos >= $knownReachedPos).",
				[ 'db_server' => $srvName ]
			);

			return true;
		}

		$close = false; // close the connection afterwards
		$flags = self::CONN_SILENCE_ERRORS;
		// Check if there is an existing connection that can be used
		$conn = $this->getAnyOpenConnection( $index, $flags );
		if ( !$conn ) {
			// Get a connection to this server without triggering complementary connections
			// to other servers (due to things like lag or read-only checks). We want to avoid
			// the risk of overhead and recursion here.
			$conn = $this->getServerConnection( $index, self::DOMAIN_ANY, $flags );
			if ( !$conn ) {
				$this->logger->warning(
					__METHOD__ . ': failed to connect to {db_server}',
					[ 'db_server' => $srvName ]
				);

				return false;
			}
			// Avoid connection spam in waitForAll() when connections
			// are made just for the sake of doing this lag check.
			$close = true;
		}

		$this->logger->info(
			__METHOD__ .
			': waiting for replica DB {db_server} to catch up...',
			$this->getConnLogContext( $conn )
		);

		$result = $conn->primaryPosWait( $this->waitForPos, $timeout );

		$ok = ( $result !== null && $result != -1 );
		if ( $ok ) {
			// Remember that the DB reached this point
			$this->srvCache->set( $key, $this->waitForPos->toArray(), BagOStuff::TTL_DAY );
		}

		if ( $close ) {
			$this->closeConnection( $conn );
		}

		return $ok;
	}

	public function getConnection( $i, $groups = [], $domain = false, $flags = 0 ) {
		return $this->getConnectionRef( $i, $groups, $domain, $flags );
	}

	public function getConnectionInternal( $i, $groups = [], $domain = false, $flags = 0 ): IDatabase {
		$domain = $this->resolveDomainID( $domain );
		$group = $this->resolveGroups( $groups, $i );
		$flags = $this->sanitizeConnectionFlags( $flags, $i, $domain );
		// If given DB_PRIMARY/DB_REPLICA, resolve it to a specific server index. Resolving
		// DB_REPLICA might trigger getServerConnection() calls due to the getReaderIndex()
		// connectivity checks or LoadMonitor::scaleLoads() server state cache regeneration.
		// The use of getServerConnection() instead of getConnection() avoids infinite loops.
		$serverIndex = $i;
		if ( $i === self::DB_PRIMARY ) {
			$serverIndex = ServerInfo::WRITER_INDEX;
		} elseif ( $i === self::DB_REPLICA ) {
			$groupIndex = $this->getReaderIndex( $group );
			if ( $groupIndex !== false ) {
				// Group connection succeeded
				$serverIndex = $groupIndex;
			}
			if ( $serverIndex < 0 ) {
				$this->reportConnectionError( 'could not connect to any replica DB server' );
			}
		} elseif ( !$this->serverInfo->hasServerIndex( $i ) ) {
			throw new UnexpectedValueException( "Invalid server index index #$i" );
		}
		// Get an open connection to that server (might trigger a new connection)
		return $this->getServerConnection( $serverIndex, $domain, $flags );
	}

	public function getServerConnection( $i, $domain, $flags = 0 ) {
		$domainInstance = DatabaseDomain::newFromId( $domain );
		// Number of connections made before getting the server index and handle
		$priorConnectionsMade = $this->connectionCounter;
		// Get an open connection to this server (might trigger a new connection)
		$conn = $this->reuseOrOpenConnectionForNewRef( $i, $domainInstance, $flags );
		// Throw an error or otherwise bail out if the connection attempt failed
		if ( !( $conn instanceof IDatabase ) ) {
			if ( !self::fieldHasBit( $flags, self::CONN_SILENCE_ERRORS ) ) {
				$this->reportConnectionError();
			}

			return false;
		}

		// Profile any new connections caused by this method
		if ( $this->connectionCounter > $priorConnectionsMade ) {
			$this->trxProfiler->recordConnection(
				$conn->getServerName(),
				$conn->getDBname(),
				self::fieldHasBit( $flags, self::CONN_INTENT_WRITABLE )
			);
		}

		if ( !$conn->isOpen() ) {
			$this->lastErrorConn = $conn;
			// Connection was made but later unrecoverably lost for some reason.
			// Do not return a handle that will just throw exceptions on use, but
			// let the calling code, e.g. getReaderIndex(), try another server.
			if ( !self::fieldHasBit( $flags, self::CONN_SILENCE_ERRORS ) ) {
				$this->reportConnectionError();
			}
			return false;
		}

		return $conn;
	}

	public function reuseConnection( IDatabase $conn ) {
		// no-op
	}

	public function getConnectionRef( $i, $groups = [], $domain = false, $flags = 0 ): IDatabase {
		if ( self::fieldHasBit( $flags, self::CONN_SILENCE_ERRORS ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' got CONN_SILENCE_ERRORS; connection is already deferred'
			);
		}

		$domain = $this->resolveDomainID( $domain );
		$role = ( $i === self::DB_PRIMARY || $i === ServerInfo::WRITER_INDEX )
			? self::DB_PRIMARY
			: self::DB_REPLICA;

		return new DBConnRef( $this, [ $i, $groups, $domain, $flags ], $role, $this->modcount );
	}

	public function getMaintenanceConnectionRef(
		$i,
		$groups = [],
		$domain = false,
		$flags = 0
	): DBConnRef {
		if ( self::fieldHasBit( $flags, self::CONN_SILENCE_ERRORS ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' CONN_SILENCE_ERRORS is not supported'
			);
		}

		$domain = $this->resolveDomainID( $domain );
		$role = ( $i === self::DB_PRIMARY || $i === ServerInfo::WRITER_INDEX )
			? self::DB_PRIMARY
			: self::DB_REPLICA;

		return new DBConnRef( $this, [ $i, $groups, $domain, $flags ], $role, $this->modcount );
	}

	/**
	 * Get a live connection handle to the given domain
	 *
	 * This will reuse an existing tracked connection when possible. In some cases, this
	 * involves switching the DB domain of an existing handle in order to reuse it. If no
	 * existing handles can be reused, then a new connection will be made.
	 *
	 * @param int $i Specific server index
	 * @param DatabaseDomain $domain Database domain ID required by the reference
	 * @param int $flags Bit field of class CONN_* constants
	 * @return IDatabase|null Database or null on error
	 * @throws DBError When database selection fails
	 * @throws InvalidArgumentException When the server index is invalid
	 * @throws UnexpectedValueException When the DB domain of the connection is corrupted
	 * @throws DBAccessError If disable() was called
	 */
	private function reuseOrOpenConnectionForNewRef( $i, DatabaseDomain $domain, $flags = 0 ) {
		// Figure out which connection pool to use based on the flags
		if ( $this->fieldHasBit( $flags, self::CONN_UNTRACKED_GAUGE ) ) {
			// Use low timeouts, use autocommit mode, ignore transaction rounds
			$category = self::CATEGORY_GAUGE;
		} elseif ( self::fieldHasBit( $flags, self::CONN_TRX_AUTOCOMMIT ) ) {
			// Use autocommit mode, ignore transaction rounds
			$category = self::CATEGORY_AUTOCOMMIT;
		} else {
			// Respect DBO_DEFAULT, respect transaction rounds
			$category = self::CATEGORY_ROUND;
		}

		$conn = null;
		// Reuse a free connection in the pool from any domain if possible. There should only
		// be one connection in this pool unless either:
		//  - a) IDatabase::databasesAreIndependent() returns true (e.g. postgres) and two
		//       or more database domains have been used during the load balancer's lifetime
		//  - b) Two or more nested function calls used getConnection() on different domains.
		//       Normally, callers should use getConnectionRef() instead of getConnection().
		foreach ( ( $this->conns[$category][$i] ?? [] ) as $poolConn ) {
			// Check if any required DB domain changes for the new reference are possible
			// Calling selectDomain() would trigger a reconnect, which will break if a
			// transaction is active or if there is any other meaningful session state.
			$isShareable = !(
				$poolConn->databasesAreIndependent() &&
				$domain->getDatabase() !== null &&
				$domain->getDatabase() !== $poolConn->getDBname()
			);
			if ( $isShareable ) {
				$conn = $poolConn;
				// Make any required DB domain changes for the new reference
				if ( !$domain->isUnspecified() ) {
					$conn->selectDomain( $domain );
				}
				$this->logger->debug( __METHOD__ . ": reusing connection for $i/$domain" );
				break;
			}
		}

		// If necessary, try to open a new connection and add it to the pool
		if ( !$conn ) {
			$conn = $this->reallyOpenConnection(
				$i,
				$domain,
				[ self::INFO_CONN_CATEGORY => $category ]
			);
			if ( $conn->isOpen() ) {
				// Make Database::isReadOnly() respect server-side and configuration-based
				// read-only mode. Note that replica handles are always seen as read-only
				// in Database::isReadOnly() and Database::assertIsWritablePrimary().
				if ( $i === ServerInfo::WRITER_INDEX ) {
					if ( $this->readOnlyReason !== false ) {
						$readOnlyReason = $this->readOnlyReason;
					} elseif ( $this->isPrimaryRunningReadOnly( $conn ) ) {
						$readOnlyReason = 'The primary database server is running in read-only mode.';
					} else {
						$readOnlyReason = false;
					}
					$conn->setLBInfo( $conn::LB_READ_ONLY_REASON, $readOnlyReason );
				}
				// Connection obtained; check if it belongs to a tracked connection category
				if ( isset( $this->conns[$category] ) ) {
					// Track this connection for future reuse
					$this->conns[$category][$i][] = $conn;
				}
			} else {
				$this->logger->warning( __METHOD__ . ": connection error for $i/$domain" );
				$this->lastErrorConn = $conn;
				$conn = null;
			}
		}

		if ( $conn instanceof IDatabase ) {
			// Check to make sure that the right domain is selected
			$this->assertConnectionDomain( $conn, $domain );
			// Check to make sure that the CONN_* flags are respected
			$this->enforceConnectionFlags( $conn, $flags );
		}

		return $conn;
	}

	/**
	 * Sanity check to make sure that the right domain is selected
	 *
	 * @param Database $conn
	 * @param DatabaseDomain $domain
	 * @throws DBUnexpectedError
	 */
	private function assertConnectionDomain( Database $conn, DatabaseDomain $domain ) {
		if ( !$domain->isCompatible( $conn->getDomainID() ) ) {
			throw new UnexpectedValueException(
				"Got connection to '{$conn->getDomainID()}', but expected one for '{$domain}'"
			);
		}
	}

	public function getServerAttributes( $i ) {
		return $this->databaseFactory->attributesFromType(
			$this->getServerType( $i ),
			$this->serverInfo->getServerDriver( $i )
		);
	}

	/**
	 * Open a new network connection to a server (uncached)
	 *
	 * Returns a Database object whether or not the connection was successful.
	 *
	 * @param int $i Specific server index
	 * @param DatabaseDomain $domain Domain the connection is for, possibly unspecified
	 * @param array $lbInfo Additional information for setLBInfo()
	 * @return Database
	 * @throws DBAccessError
	 * @throws InvalidArgumentException
	 */
	protected function reallyOpenConnection( $i, DatabaseDomain $domain, array $lbInfo ) {
		if ( $this->disabled ) {
			throw new DBAccessError();
		}

		$server = $this->serverInfo->getServerInfoStrict( $i );
		if ( $lbInfo[self::INFO_CONN_CATEGORY] === self::CATEGORY_GAUGE ) {
			// Use low connection/read timeouts for connection used for gauging server health.
			// Gauge information should be cached and used to avoid outages. Indefinite hanging
			// while gauging servers would do the opposite.
			$server['connectTimeout'] = min( 1, $server['connectTimeout'] ?? INF );
			$server['receiveTimeout'] = min( 1, $server['receiveTimeout'] ?? INF );
			// Avoid implicit transactions and avoid any SET query for session variables during
			// Database::open(). If a server becomes slow, every extra query can cause significant
			// delays, even with low connect/receive timeouts.
			$server['flags'] ??= 0;
			$server['flags'] &= ~IDatabase::DBO_DEFAULT;
			$server['flags'] |= IDatabase::DBO_GAUGE;
		} else {
			// Use implicit transactions unless explicitly configured otherwise
			$server['flags'] ??= IDatabase::DBO_DEFAULT;
		}

		if ( !empty( $server['is static'] ) ) {
			$topologyRole = IDatabase::ROLE_STATIC_CLONE;
		} else {
			$topologyRole = ( $i === ServerInfo::WRITER_INDEX )
				? IDatabase::ROLE_STREAMING_MASTER
				: IDatabase::ROLE_STREAMING_REPLICA;
		}

		$conn = $this->databaseFactory->create(
			$server['type'],
			array_merge( $server, [
				// Basic replication role information
				'topologyRole' => $topologyRole,
				// Use the database specified in $domain (null means "none or entrypoint DB");
				// fallback to the $server default if the RDBMs is an embedded library using a
				// file on disk since there would be nothing to access to without a DB/file name.
				'dbname' => $this->getServerAttributes( $i )[Database::ATTR_DB_IS_FILE]
					? ( $domain->getDatabase() ?? $server['dbname'] ?? null )
					: $domain->getDatabase(),
				// Override the $server default schema with that of $domain if specified
				'schema' => $domain->getSchema(),
				// Use the table prefix specified in $domain
				'tablePrefix' => $domain->getTablePrefix(),
				'srvCache' => $this->srvCache,
				'logger' => $this->logger,
				'errorLogger' => $this->errorLogger,
				'trxProfiler' => $this->trxProfiler,
				'lbInfo' => [ self::INFO_SERVER_INDEX => $i ] + $lbInfo
			] ),
			Database::NEW_UNCONNECTED
		);
		// Set alternative table/index names before any queries can be issued
		$conn->setTableAliases( $this->tableAliases );
		$conn->setIndexAliases( $this->indexAliases );
		// Account for any active transaction round and listeners
		if ( $i === ServerInfo::WRITER_INDEX ) {
			if ( $this->trxRoundId !== false ) {
				$this->applyTransactionRoundFlags( $conn );
			}
			foreach ( $this->trxRecurringCallbacks as $name => $callback ) {
				$conn->setTransactionListener( $name, $callback );
			}
		}

		// Make the connection handle live
		try {
			$conn->initConnection();
			++$this->connectionCounter;
		} catch ( DBConnectionError $e ) {
			$this->lastErrorConn = $conn;
			// ignore; let the DB handle the logging
		}

		if ( $conn->isOpen() ) {
			$this->logger->debug( __METHOD__ . ": opened new connection for $i/$domain" );
		} else {
			$this->logger->warning(
				__METHOD__ . ": connection error for $i/{db_domain}",
				[ 'db_domain' => $domain->getId() ]
			);
		}

		// Log when many connection are made during a single request/script
		$count = 0;
		foreach ( $this->conns as $poolConnsByServer ) {
			foreach ( $poolConnsByServer as $serverConns ) {
				$count += count( $serverConns );
			}
		}
		if ( $count >= self::CONN_HELD_WARN_THRESHOLD ) {
			$this->logger->warning(
				__METHOD__ . ": {connections}+ connections made (primary={primarydb})",
				$this->getConnLogContext(
					$conn,
					[
						'connections' => $count,
						'primarydb' => $this->serverInfo->getPrimaryServerName(),
						'db_domain' => $domain->getId()
					]
				)
			);
		}

		$this->assertConnectionDomain( $conn, $domain );

		return $conn;
	}

	/**
	 * Make sure that any "waitForPos" replication positions are loaded and available
	 *
	 * Each load balancer cluster has up to one replication position for the session.
	 * These are used when data read by queries is expected to reflect writes caused
	 * by a prior request/script from the same client.
	 *
	 * @see awaitSessionPrimaryPos()
	 */
	private function loadSessionPrimaryPos() {
		if ( !$this->chronologyProtectorCalled && $this->chronologyProtector ) {
			$this->chronologyProtectorCalled = true;
			$pos = $this->chronologyProtector->getSessionPrimaryPos( $this );
			$this->logger->debug( __METHOD__ . ': executed chronology callback.' );
			if ( $pos ) {
				if ( !$this->waitForPos || $pos->hasReached( $this->waitForPos ) ) {
					$this->waitForPos = $pos;
				}
			}
		}
	}

	/**
	 * @param string $extraLbError Separat load balancer error
	 * @throws DBConnectionError
	 * @return never
	 */
	private function reportConnectionError( $extraLbError = '' ) {
		if ( $this->lastErrorConn instanceof IDatabase ) {
			$srvName = $this->lastErrorConn->getServerName();
			$lastDbError = $this->lastErrorConn->lastError() ?: 'unknown error';

			$exception = new DBConnectionError(
				$this->lastErrorConn,
				$extraLbError
					? "{$extraLbError}; {$lastDbError} ({$srvName})"
					: "{$lastDbError} ({$srvName})"
			);

			if ( $extraLbError ) {
				$this->logger->warning(
					__METHOD__ . ": $extraLbError; {last_error} ({db_server})",
					$this->getConnLogContext(
						$this->lastErrorConn,
						[
							'method' => __METHOD__,
							'last_error' => $lastDbError
						]
					)
				);
			}
		} else {
			$exception = new DBConnectionError(
				null,
				$extraLbError ?: 'could not connect to the DB server'
			);

			if ( $extraLbError ) {
				$this->logger->error(
					__METHOD__ . ": $extraLbError",
					[
						'method' => __METHOD__,
						'last_error' => '(last connection error missing)'
					]
				);
			}
		}

		throw $exception;
	}

	public function getWriterIndex() {
		return ServerInfo::WRITER_INDEX;
	}

	public function getServerCount() {
		return $this->serverInfo->getServerCount();
	}

	public function hasReplicaServers() {
		return $this->serverInfo->hasReplicaServers();
	}

	public function hasStreamingReplicaServers() {
		return $this->serverInfo->hasStreamingReplicaServers();
	}

	public function getServerName( $i ): string {
		return $this->serverInfo->getServerName( $i );
	}

	public function getServerInfo( $i ) {
		return $this->serverInfo->getServerInfo( $i );
	}

	public function getServerType( $i ) {
		return $this->serverInfo->getServerType( $i );
	}

	public function getPrimaryPos() {
		$conn = $this->getAnyOpenConnection( ServerInfo::WRITER_INDEX );
		if ( $conn ) {
			return $conn->getPrimaryPos();
		}

		$conn = $this->getConnectionInternal( ServerInfo::WRITER_INDEX, self::CONN_SILENCE_ERRORS );
		// @phan-suppress-next-line PhanRedundantCondition
		if ( !$conn ) {
			$this->reportConnectionError();
		}

		try {
			return $conn->getPrimaryPos();
		} finally {
			$this->closeConnection( $conn );
		}
	}

	/**
	 * Apply updated configuration.
	 *
	 * This only unregisters servers that were removed in the new configuration.
	 * It does not register new servers nor update the group load weights.
	 *
	 * This invalidates any open connections. However, existing connections may continue to be
	 * used while they are in an active transaction. In that case, the old connection will be
	 * discarded on the first operation after the transaction is complete. The next operation
	 * will use a new connection based on the new configuration.
	 *
	 * @internal for use by LBFactory::reconfigure()
	 *
	 * @see DBConnRef::ensureConnection()
	 * @see LBFactory::reconfigure()
	 *
	 * @param array $params A database configuration array, see $wgLBFactoryConf.
	 *
	 * @return void
	 */
	public function reconfigure( array $params ) {
		$anyServerDepooled = false;

		$paramServers = $params['servers'];
		$newIndexByServerIndex = $this->serverInfo->reconfigureServers( $paramServers );
		foreach ( $newIndexByServerIndex as $i => $ni ) {
			if ( $ni !== null ) {
				// Server still exists in the new config
				$newWeightByGroup = $paramServers[$ni]['groupLoads'] ?? [];
				$newWeightByGroup[ILoadBalancer::GROUP_GENERIC] = $paramServers[$ni]['load'];
				// Check if the server was removed from any load groups
				foreach ( $this->groupLoads as $group => $weightByIndex ) {
					if ( isset( $weightByIndex[$i] ) && !isset( $newWeightByGroup[$group] ) ) {
						// Server no longer in this load group in the new config
						$anyServerDepooled = true;
						unset( $this->groupLoads[$group][$i] );
					}
				}
			} else {
				// Server no longer exists in the new config
				$anyServerDepooled = true;
				// Note that if the primary server is depooled and a replica server promoted
				// to new primary, then DB_PRIMARY handles will fail with server index errors
				foreach ( $this->groupLoads as $group => $loads ) {
					unset( $this->groupLoads[$group][$i] );
				}
			}
		}

		if ( $anyServerDepooled ) {
			// NOTE: We could close all connection here, but some may be in the middle of
			//       a transaction. So instead, we leave it to DBConnRef to close the
			//       connection when it detects that the modcount has changed and no
			//       transaction is open.
			$this->logger->info( 'Reconfiguring dbs!' );
			// Unpin DB_REPLICA connection groups from server indexes
			$this->readIndexByGroup = [];
			// We could close all connection here, but some may be in the middle of a
			// transaction. So instead, we leave it to DBConnRef to close the connection
			// when it detects that the modcount has changed and no transaction is open.
			$this->conns = self::newTrackedConnectionsArray();
			// Bump modification counter to invalidate the connections held by DBConnRef
			// instances. This will cause the next call to a method on the DBConnRef
			// to get a new connection from getConnectionInternal()
			$this->modcount++;
		}
	}

	public function disable( $fname = __METHOD__ ) {
		$this->closeAll( $fname );
		$this->disabled = true;
	}

	public function closeAll( $fname = __METHOD__ ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();
		foreach ( $this->getOpenConnections() as $conn ) {
			$conn->close( $fname );
		}

		$this->conns = self::newTrackedConnectionsArray();
	}

	/**
	 * Close a connection
	 *
	 * Using this function makes sure the LoadBalancer knows the connection is closed.
	 * If you use $conn->close() directly, the load balancer won't update its state.
	 *
	 * @param IDatabase $conn
	 */
	private function closeConnection( IDatabase $conn ) {
		if ( $conn instanceof DBConnRef ) {
			// Avoid calling close() but still leaving the handle in the pool
			throw new RuntimeException( 'Cannot close DBConnRef instance; it must be shareable' );
		}

		$domain = $conn->getDomainID();
		$serverIndex = $conn->getLBInfo( self::INFO_SERVER_INDEX );
		if ( $serverIndex === null ) {
			throw new UnexpectedValueException( "Handle on '$domain' missing server index" );
		}

		$srvName = $this->serverInfo->getServerName( $serverIndex );

		$found = false;
		foreach ( $this->conns as $type => $poolConnsByServer ) {
			$key = array_search( $conn, $poolConnsByServer[$serverIndex] ?? [], true );
			if ( $key !== false ) {
				$found = true;
				unset( $this->conns[$type][$serverIndex][$key] );
			}
		}

		if ( !$found ) {
			$this->logger->warning(
				__METHOD__ .
				": orphaned connection to database {$this->stringifyConn( $conn )} at '$srvName'."
			);
		}

		$this->logger->debug(
			__METHOD__ .
			": closing connection to database {$this->stringifyConn( $conn )} at '$srvName'."
		);

		$conn->close( __METHOD__ );
	}

	public function finalizePrimaryChanges( $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( [ self::ROUND_CURSORY, self::ROUND_FINALIZED ] );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Loop until callbacks stop adding callbacks on other connections
		$total = 0;
		do {
			$count = 0; // callbacks execution attempts
			foreach ( $this->getOpenPrimaryConnections() as $conn ) {
				// Run any pre-commit callbacks while leaving the post-commit ones suppressed.
				// Any error should cause all (peer) transactions to be rolled back together.
				$count += $conn->runOnTransactionPreCommitCallbacks();
			}
			$total += $count;
		} while ( $count > 0 );
		// Defer post-commit callbacks until after COMMIT/ROLLBACK happens on all handles
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->setTrxEndCallbackSuppression( true );
		}
		$this->trxRoundStage = self::ROUND_FINALIZED;

		return $total;
	}

	public function approvePrimaryChanges( int $maxWriteDuration, $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( self::ROUND_FINALIZED );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			// Any atomic sections should have been closed by now and there definitely should
			// not be any open transactions started by begin() from callers outside Database.
			if ( $conn->explicitTrxActive() ) {
				throw new DBTransactionError(
					$conn,
					"Explicit transaction still active; a caller might have failed to call " .
					"endAtomic() or cancelAtomic()."
				);
			}
			// Assert that the time to replicate the transaction will be reasonable.
			// If this fails, then all DB transactions will be rollback back together.
			$time = $conn->pendingWriteQueryDuration( $conn::ESTIMATE_DB_APPLY );
			if ( $maxWriteDuration > 0 ) {
				if ( $time > $maxWriteDuration ) {
					$humanTimeSec = round( $time, 3 );
					throw new DBTransactionSizeError(
						$conn,
						"Transaction spent {time}s in writes, exceeding the {$maxWriteDuration}s limit",
						// Message parameters for: transaction-duration-limit-exceeded
						[ $time, $maxWriteDuration ],
						null,
						[ 'time' => $humanTimeSec ]
					);
				} elseif ( $time > 0 ) {
					$timeMs = $time * 1000;
					$humanTimeMs = round( $timeMs, $timeMs > 1 ? 0 : 3 );
					$this->logger->debug(
						"Transaction spent {time_ms}ms in writes, under the {$maxWriteDuration}s limit",
						[ 'time_ms' => $humanTimeMs ]
					);
				}
			}
			// If a connection sits idle for too long it might be dropped, causing transaction
			// writes and session locks to be lost. Ping all the server connections before making
			// any attempt to commit the transactions belonging to the active transaction round.
			if ( $conn->writesOrCallbacksPending() || $conn->sessionLocksPending() ) {
				if ( !$conn->ping() ) {
					throw new DBTransactionError(
						$conn,
						"Pre-commit ping failed on server {$conn->getServerName()}"
					);
				}
			}
		}
		$this->trxRoundStage = self::ROUND_APPROVED;
	}

	public function beginPrimaryChanges( $fname = __METHOD__ ) {
		if ( $this->trxRoundId !== false ) {
			throw new DBTransactionError(
				null,
				"Transaction round '{$this->trxRoundId}' already started"
			);
		}
		$this->assertTransactionRoundStage( self::ROUND_CURSORY );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		// Clear any empty transactions (no writes/callbacks) from the implicit round
		$this->flushPrimarySnapshots( $fname );

		$this->trxRoundId = $fname;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Mark applicable handles as participating in this explicit transaction round.
		// For each of these handles, any writes and callbacks will be tied to a single
		// transaction. The (peer) handles will reject begin()/commit() calls unless they
		// are part of an en masse commit or an en masse rollback.
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$this->applyTransactionRoundFlags( $conn );
		}
		$this->trxRoundStage = self::ROUND_CURSORY;
	}

	public function commitPrimaryChanges( $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( self::ROUND_APPROVED );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$failures = [];

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		// Commit any writes and clear any snapshots as well (callbacks require AUTOCOMMIT).
		// Note that callbacks should already be suppressed due to finalizePrimaryChanges().
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			try {
				$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
			} catch ( DBError $e ) {
				( $this->errorLogger )( $e );
				$failures[] = "{$conn->getServerName()}: {$e->getMessage()}";
			}
		}
		if ( $failures ) {
			throw new DBTransactionError(
				null,
				"Commit failed on server(s) " . implode( "\n", array_unique( $failures ) )
			);
		}
		if ( $restore ) {
			// Unmark handles as participating in this explicit transaction round
			foreach ( $this->getOpenPrimaryConnections() as $conn ) {
				$this->undoTransactionRoundFlags( $conn );
			}
		}
		$this->trxRoundStage = self::ROUND_COMMIT_CALLBACKS;
	}

	public function runPrimaryTransactionIdleCallbacks( $fname = __METHOD__ ) {
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$type = IDatabase::TRIGGER_COMMIT;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$type = IDatabase::TRIGGER_ROLLBACK;
		} else {
			throw new DBTransactionError(
				null,
				"Transaction should be in the callback stage (not '{$this->trxRoundStage}')"
			);
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$oldStage = $this->trxRoundStage;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise

		// Now that the COMMIT/ROLLBACK step is over, enable post-commit callback runs
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->setTrxEndCallbackSuppression( false );
		}

		$errors = [];
		$fname = __METHOD__;
		// Loop until callbacks stop adding callbacks on other connections
		do {
			// Run any pending callbacks for each connection...
			$count = 0; // callback execution attempts
			foreach ( $this->getOpenPrimaryConnections() as $conn ) {
				if ( $conn->trxLevel() ) {
					continue; // retry in the next iteration, after commit() is called
				}
				$count += $conn->runOnTransactionIdleCallbacks( $type, $errors );
			}
			// Clear out any active transactions left over from callbacks...
			foreach ( $this->getOpenPrimaryConnections() as $conn ) {
				if ( $conn->writesPending() ) {
					// A callback from another handle wrote to this one and DBO_TRX is set
					$fnames = implode( ', ', $conn->pendingWriteAndCallbackCallers() );
					$this->logger->warning(
						"$fname: found writes pending ($fnames).",
						$this->getConnLogContext(
							$conn,
							[ 'exception' => new RuntimeException() ]
						)
					);
				} elseif ( $conn->trxLevel() ) {
					// A callback from another handle read from this one and DBO_TRX is set,
					// which can easily happen if there is only one DB (no replicas)
					$this->logger->debug( "$fname: found empty transaction." );
				}
				try {
					$conn->commit( $fname, $conn::FLUSHING_ALL_PEERS );
				} catch ( DBError $ex ) {
					$errors[] = $ex;
				}
			}
		} while ( $count > 0 );

		$this->trxRoundStage = $oldStage;

		return $errors[0] ?? null;
	}

	public function runPrimaryTransactionListenerCallbacks( $fname = __METHOD__ ) {
		if ( $this->trxRoundStage === self::ROUND_COMMIT_CALLBACKS ) {
			$type = IDatabase::TRIGGER_COMMIT;
		} elseif ( $this->trxRoundStage === self::ROUND_ROLLBACK_CALLBACKS ) {
			$type = IDatabase::TRIGGER_ROLLBACK;
		} else {
			throw new DBTransactionError(
				null,
				"Transaction should be in the callback stage (not '{$this->trxRoundStage}')"
			);
		}
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$errors = [];
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->runTransactionListenerCallbacks( $type, $errors );
		}
		$this->trxRoundStage = self::ROUND_CURSORY;

		return $errors[0] ?? null;
	}

	public function rollbackPrimaryChanges( $fname = __METHOD__ ) {
		/** @noinspection PhpUnusedLocalVariableInspection */
		$scope = ScopedCallback::newScopedIgnoreUserAbort();

		$restore = ( $this->trxRoundId !== false );
		$this->trxRoundId = false;
		$this->trxRoundStage = self::ROUND_ERROR; // "failed" until proven otherwise
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->rollback( $fname, $conn::FLUSHING_ALL_PEERS );
		}
		if ( $restore ) {
			// Unmark handles as participating in this explicit transaction round
			foreach ( $this->getOpenPrimaryConnections() as $conn ) {
				$this->undoTransactionRoundFlags( $conn );
			}
		}
		$this->trxRoundStage = self::ROUND_ROLLBACK_CALLBACKS;
	}

	public function flushPrimarySessions( $fname = __METHOD__ ) {
		$this->assertTransactionRoundStage( [ self::ROUND_CURSORY ] );
		if ( $this->hasPrimaryChanges() ) {
			// Any transaction should have been rolled back beforehand
			throw new DBTransactionError( null, "Cannot reset session while writes are pending" );
		}

		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->flushSession( $fname, $conn::FLUSHING_ALL_PEERS );
		}
	}

	/**
	 * @param string|string[] $stage
	 * @throws DBTransactionError
	 */
	private function assertTransactionRoundStage( $stage ) {
		$stages = (array)$stage;

		if ( !in_array( $this->trxRoundStage, $stages, true ) ) {
			$stageList = implode(
				'/',
				array_map( static function ( $v ) {
					return "'$v'";
				}, $stages )
			);
			throw new DBTransactionError(
				null,
				"Transaction round stage must be $stageList (not '{$this->trxRoundStage}')"
			);
		}
	}

	/**
	 * Make all DB servers with DBO_DEFAULT/DBO_TRX set join the transaction round
	 *
	 * Some servers may have neither flag enabled, meaning that they opt out of such
	 * transaction rounds and remain in auto-commit mode. Such behavior might be desired
	 * when a DB server is used for something like simple key/value storage.
	 *
	 * @param Database $conn
	 */
	private function applyTransactionRoundFlags( Database $conn ) {
		if ( $conn->getLBInfo( self::INFO_CONN_CATEGORY ) !== self::CATEGORY_ROUND ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			// DBO_TRX is controlled entirely by CLI mode presence with DBO_DEFAULT.
			// Force DBO_TRX even in CLI mode since a commit round is expected soon.
			$conn->setFlag( $conn::DBO_TRX, $conn::REMEMBER_PRIOR );
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( $conn::LB_TRX_ROUND_ID, $this->trxRoundId );
		}
	}

	/**
	 * @param Database $conn
	 */
	private function undoTransactionRoundFlags( Database $conn ) {
		if ( $conn->getLBInfo( self::INFO_CONN_CATEGORY ) !== self::CATEGORY_ROUND ) {
			return; // transaction rounds do not apply to these connections
		}

		if ( $conn->getFlag( $conn::DBO_TRX ) ) {
			$conn->setLBInfo( $conn::LB_TRX_ROUND_ID, null ); // remove the round ID
		}

		if ( $conn->getFlag( $conn::DBO_DEFAULT ) ) {
			$conn->restoreFlags( $conn::RESTORE_PRIOR );
		}
	}

	public function flushReplicaSnapshots( $fname = __METHOD__ ) {
		foreach ( $this->conns as $poolConnsByServer ) {
			foreach ( $poolConnsByServer as $serverIndex => $serverConns ) {
				if ( $serverIndex === ServerInfo::WRITER_INDEX ) {
					continue; // skip primary
				}
				foreach ( $serverConns as $conn ) {
					$conn->flushSnapshot( $fname );
				}
			}
		}
	}

	public function flushPrimarySnapshots( $fname = __METHOD__ ) {
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->flushSnapshot( $fname );
		}
	}

	public function hasPrimaryConnection() {
		return (bool)$this->getAnyOpenConnection( ServerInfo::WRITER_INDEX );
	}

	public function hasPrimaryChanges() {
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			if ( $conn->writesOrCallbacksPending() ) {
				return true;
			}
		}

		return false;
	}

	public function lastPrimaryChangeTimestamp() {
		$lastTime = false;
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$lastTime = max( $lastTime, $conn->lastDoneWrites() );
		}

		return $lastTime;
	}

	public function hasOrMadeRecentPrimaryChanges( $age = null ) {
		$age ??= self::MAX_WAIT_DEFAULT;

		return ( $this->hasPrimaryChanges()
			|| $this->lastPrimaryChangeTimestamp() > microtime( true ) - $age );
	}

	public function pendingPrimaryChangeCallers() {
		$fnames = [];
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$fnames = array_merge( $fnames, $conn->pendingWriteCallers() );
		}

		return $fnames;
	}

	public function explicitTrxActive() {
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			if ( $conn->explicitTrxActive() ) {
				return true;
			}
		}
		return false;
	}

	private function setLaggedReplicaMode(): void {
		$this->laggedReplicaMode = true;
		$this->logger->warning( __METHOD__ . ": setting lagged replica mode" );
	}

	public function laggedReplicaUsed() {
		return $this->laggedReplicaMode;
	}

	public function getReadOnlyReason( $domain = false ) {
		if ( $this->readOnlyReason !== false ) {
			return $this->readOnlyReason;
		} elseif ( $this->isPrimaryRunningReadOnly() ) {
			return 'The primary database server is running in read-only mode.';
		}

		return false;
	}

	/**
	 * @note This method suppresses DBError exceptions in order to avoid severe downtime
	 * @param IDatabase|null $conn Recently acquired primary connection; null if not applicable
	 * @return bool Whether the entire primary DB server or the local domain DB is read-only
	 */
	private function isPrimaryRunningReadOnly( IDatabase $conn = null ) {
		// Context will often be HTTP GET/HEAD; heavily cache the results
		return (bool)$this->wanCache->getWithSetCallback(
			// Note that table prefixes are not related to server-side read-only mode
			$this->wanCache->makeGlobalKey(
				'rdbms-server-readonly',
				$this->serverInfo->getPrimaryServerName()
			),
			self::TTL_CACHE_READONLY,
			function ( $oldValue ) use ( $conn ) {
				$scope = $this->trxProfiler->silenceForScope();
				$conn ??= $this->getServerConnection(
					ServerInfo::WRITER_INDEX,
					self::DOMAIN_ANY,
					self::CONN_SILENCE_ERRORS
				);
				if ( $conn ) {
					try {
						$value = (int)$conn->serverIsReadOnly();
					} catch ( DBError $e ) {
						$value = is_int( $oldValue ) ? $oldValue : 0;
					}
				} else {
					$value = 0;
				}
				ScopedCallback::consume( $scope );

				return $value;
			},
			[
				'busyValue' => 0,
				'pcTTL' => WANObjectCache::TTL_PROC_LONG
			]
		);
	}

	public function pingAll() {
		$success = true;
		foreach ( $this->getOpenConnections() as $conn ) {
			if ( !$conn->ping() ) {
				$success = false;
			}
		}

		return $success;
	}

	/**
	 * Get all open connections
	 * @return \Generator|Database[]
	 */
	private function getOpenConnections() {
		foreach ( $this->conns as $poolConnsByServer ) {
			foreach ( $poolConnsByServer as $serverConns ) {
				foreach ( $serverConns as $conn ) {
					yield $conn;
				}
			}
		}
	}

	/**
	 * Get all open primary connections
	 * @return \Generator|Database[]
	 */
	private function getOpenPrimaryConnections() {
		foreach ( $this->conns as $poolConnsByServer ) {
			/** @var IDatabase $conn */
			foreach ( ( $poolConnsByServer[ServerInfo::WRITER_INDEX] ?? [] ) as $conn ) {
				yield $conn;
			}
		}
	}

	public function getMaxLag() {
		$host = '';
		$maxLag = -1;
		$maxIndex = 0;

		if ( $this->serverInfo->hasReplicaServers() ) {
			$lagTimes = $this->getLagTimes();
			foreach ( $lagTimes as $i => $lag ) {
				if ( $this->groupLoads[self::GROUP_GENERIC][$i] > 0 && $lag > $maxLag ) {
					$maxLag = $lag;
					$host = $this->serverInfo->getServerInfoStrict( $i, 'host' );
					$maxIndex = $i;
				}
			}
		}

		return [ $host, $maxLag, $maxIndex ];
	}

	public function getLagTimes() {
		if ( !$this->hasReplicaServers() ) {
			return [ ServerInfo::WRITER_INDEX => 0 ]; // no replication = no lag
		}
		[ $indexesWithLag, $knownLagTimes ] = $this->serverInfo->getLagTimes();
		return $this->loadMonitor->getLagTimes( $indexesWithLag ) + $knownLagTimes;
	}

	public function waitForPrimaryPos( IDatabase $conn ) {
		if ( $conn->getLBInfo( self::INFO_SERVER_INDEX ) === ServerInfo::WRITER_INDEX ) {
			return true; // not a replica DB server
		}

		// Get the current primary DB position, opening a connection only if needed
		$flags = self::CONN_SILENCE_ERRORS;
		$primaryConn = $this->getAnyOpenConnection( ServerInfo::WRITER_INDEX, $flags );
		if ( $primaryConn ) {
			$pos = $primaryConn->getPrimaryPos();
		} else {
			$primaryConn = $this->getServerConnection( ServerInfo::WRITER_INDEX, self::DOMAIN_ANY, $flags );
			if ( !$primaryConn ) {
				throw new DBReplicationWaitError(
					null,
					"Could not obtain a primary database connection to get the position"
				);
			}
			$pos = $primaryConn->getPrimaryPos();
			$this->closeConnection( $primaryConn );
		}

		if ( $pos instanceof DBPrimaryPos ) {
			$this->logger->debug( __METHOD__ . ': waiting' );
			$result = $conn->primaryPosWait( $pos, self::MAX_WAIT_DEFAULT );
			$ok = ( $result !== null && $result != -1 );
			if ( $ok ) {
				$this->logger->debug( __METHOD__ . ': done waiting (success)' );
			} else {
				$this->logger->debug( __METHOD__ . ': done waiting (failure)' );
			}
		} else {
			$ok = false; // something is misconfigured
			$this->logger->error(
				__METHOD__ . ': could not get primary pos for {db_server}',
				$this->getConnLogContext( $conn, [ 'exception' => new RuntimeException() ] )
			);
		}

		return $ok;
	}

	public function setTransactionListener( $name, callable $callback = null ) {
		if ( $callback ) {
			$this->trxRecurringCallbacks[$name] = $callback;
		} else {
			unset( $this->trxRecurringCallbacks[$name] );
		}
		foreach ( $this->getOpenPrimaryConnections() as $conn ) {
			$conn->setTransactionListener( $name, $callback );
		}
	}

	public function setTableAliases( array $aliases ) {
		$this->tableAliases = $aliases;
	}

	public function setIndexAliases( array $aliases ) {
		$this->indexAliases = $aliases;
	}

	public function setDomainAliases( array $aliases ) {
		$this->domainAliases = $aliases;
	}

	public function setLocalDomainPrefix( $prefix ) {
		$oldLocalDomain = $this->localDomain;
		$this->localDomain = new DatabaseDomain(
			$this->localDomain->getDatabase(),
			$this->localDomain->getSchema(),
			$prefix
		);

		// Update the prefix for existing connections.
		// Existing DBConnRef handles will not be affected.
		foreach ( $this->getOpenConnections() as $conn ) {
			if ( $oldLocalDomain->equals( $conn->getDomainID() ) ) {
				$conn->tablePrefix( $prefix );
			}
		}
	}

	public function redefineLocalDomain( $domain ) {
		$this->closeAll( __METHOD__ );
		$this->localDomain = DatabaseDomain::newFromId( $domain );
	}

	public function setTempTablesOnlyMode( $value, $domain ) {
		$old = $this->tempTablesOnlyMode[$domain] ?? false;
		if ( $value ) {
			$this->tempTablesOnlyMode[$domain] = true;
		} else {
			unset( $this->tempTablesOnlyMode[$domain] );
		}

		return $old;
	}

	/**
	 * @param IDatabase $conn
	 * @return string Description of a connection handle for log messages
	 * @throws InvalidArgumentException
	 */
	private function stringifyConn( IDatabase $conn ) {
		return $conn->getLBInfo( self::INFO_SERVER_INDEX ) . '/' . $conn->getDomainID();
	}

	/**
	 * @param int $flags A bitfield of flags
	 * @param int $bit Bit flag constant
	 * @return bool Whether the bit field has the specified bit flag set
	 */
	private function fieldHasBit( int $flags, int $bit ) {
		return ( ( $flags & $bit ) === $bit );
	}

	/**
	 * Create a log context to pass to PSR-3 logger functions.
	 *
	 * @param IDatabase $conn
	 * @param array $extras Additional data to add to context
	 * @return array
	 */
	protected function getConnLogContext( IDatabase $conn, array $extras = [] ) {
		return array_merge(
			[
				'db_server' => $conn->getServerName(),
				'db_domain' => $conn->getDomainID()
			],
			$extras
		);
	}

}

/**
 * @deprecated since 1.29
 */
class_alias( LoadBalancer::class, 'LoadBalancer' );
