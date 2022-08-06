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
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\RequestTimeout\CriticalSectionProvider;

/**
 * MediaWiki-specific class for generating database load balancers
 *
 * @internal For use by core ServiceWiring only.
 * @ingroup Database
 */
class MWLBFactory {

	/** @var array Cache of already-logged deprecation messages */
	private static $loggedDeprecations = [];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const APPLY_DEFAULT_CONFIG_OPTIONS = [
		'CommandLineMode',
		MainConfigNames::DBcompress,
		MainConfigNames::DBDefaultGroup,
		MainConfigNames::DBmwschema,
		MainConfigNames::DBname,
		MainConfigNames::DBpassword,
		MainConfigNames::DBport,
		MainConfigNames::DBprefix,
		MainConfigNames::DBserver,
		MainConfigNames::DBservers,
		MainConfigNames::DBssl,
		MainConfigNames::DBtype,
		MainConfigNames::DBuser,
		MainConfigNames::DebugDumpSql,
		MainConfigNames::DebugLogFile,
		MainConfigNames::DebugToolbar,
		MainConfigNames::ExternalServers,
		MainConfigNames::SQLiteDataDir,
		MainConfigNames::SQLMode,
	];
	/**
	 * @var ServiceOptions
	 */
	private $options;
	/**
	 * @var ConfiguredReadOnlyMode
	 */
	private $readOnlyMode;
	/**
	 * @var BagOStuff
	 */
	private $cpStash;
	/**
	 * @var BagOStuff
	 */
	private $srvCache;
	/**
	 * @var WANObjectCache
	 */
	private $wanCache;
	/**
	 * @var CriticalSectionProvider
	 */
	private $csProvider;
	/**
	 * @var StatsdDataFactoryInterface
	 */
	private $statsdDataFactory;

	/**
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param BagOStuff $cpStash
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wanCache
	 * @param CriticalSectionProvider $csProvider
	 * @param StatsdDataFactoryInterface $statsdDataFactory
	 */
	public function __construct(
		ServiceOptions $options,
		ConfiguredReadOnlyMode $readOnlyMode,
		BagOStuff $cpStash,
		BagOStuff $srvCache,
		WANObjectCache $wanCache,
		CriticalSectionProvider $csProvider,
		StatsdDataFactoryInterface $statsdDataFactory
	) {
		$this->options = $options;
		$this->readOnlyMode = $readOnlyMode;
		$this->cpStash = $cpStash;
		$this->srvCache = $srvCache;
		$this->wanCache = $wanCache;
		$this->csProvider = $csProvider;
		$this->statsdDataFactory = $statsdDataFactory;
	}

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @return array
	 * @internal For use with service wiring
	 */
	public function applyDefaultConfig( array $lbConf ) {
		$this->options->assertRequiredOptions( self::APPLY_DEFAULT_CONFIG_OPTIONS );

		$typesWithSchema = self::getDbTypesWithSchemas();

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$this->options->get( MainConfigNames::DBname ),
				$this->options->get( MainConfigNames::DBmwschema ),
				$this->options->get( MainConfigNames::DBprefix )
			),
			'profiler' => static function ( $section ) {
				return Profiler::instance()->scopedProfileIn( $section );
			},
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'queryLogger' => LoggerFactory::getInstance( 'DBQuery' ),
			'connLogger' => LoggerFactory::getInstance( 'DBConnection' ),
			'perfLogger' => LoggerFactory::getInstance( 'DBPerformance' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ],
			'deprecationLogger' => [ static::class, 'logDeprecation' ],
			'statsdDataFactory' => $this->statsdDataFactory,
			'cliMode' => $this->options->get( 'CommandLineMode' ),
			'readOnlyReason' => $this->readOnlyMode->getReason(),
			'defaultGroup' => $this->options->get( MainConfigNames::DBDefaultGroup ),
			'criticalSectionProvider' => $this->csProvider
		];

		$serversCheck = [];
		// When making changes here, remember to also specify MediaWiki-specific options
		// for Database classes in the relevant Installer subclass.
		// Such as MysqlInstaller::openConnection and PostgresInstaller::openConnectionWithParams.
		if ( $lbConf['class'] === Wikimedia\Rdbms\LBFactorySimple::class ) {
			if ( isset( $lbConf['servers'] ) ) {
				// Server array is already explicitly configured
			} elseif ( is_array( $this->options->get( MainConfigNames::DBservers ) ) ) {
				$lbConf['servers'] = [];
				foreach ( $this->options->get( MainConfigNames::DBservers ) as $i => $server ) {
					$lbConf['servers'][$i] = self::initServerInfo( $server, $this->options );
				}
			} else {
				$server = self::initServerInfo(
					[
						'host' => $this->options->get( MainConfigNames::DBserver ),
						'user' => $this->options->get( MainConfigNames::DBuser ),
						'password' => $this->options->get( MainConfigNames::DBpassword ),
						'dbname' => $this->options->get( MainConfigNames::DBname ),
						'type' => $this->options->get( MainConfigNames::DBtype ),
						'load' => 1
					],
					$this->options
				);

				if ( $this->options->get( MainConfigNames::DBssl ) ) {
					$server['ssl'] = true;
				}
				$server['flags'] |= $this->options->get( MainConfigNames::DBcompress ) ? DBO_COMPRESS : 0;

				$lbConf['servers'] = [ $server ];
			}
			if ( !isset( $lbConf['externalClusters'] ) ) {
				$lbConf['externalClusters'] = $this->options->get( MainConfigNames::ExternalServers );
			}

			$serversCheck = $lbConf['servers'];
		} elseif ( $lbConf['class'] === Wikimedia\Rdbms\LBFactoryMulti::class ) {
			if ( isset( $lbConf['serverTemplate'] ) ) {
				if ( in_array( $lbConf['serverTemplate']['type'], $typesWithSchema, true ) ) {
					$lbConf['serverTemplate']['schema'] = $this->options->get( MainConfigNames::DBmwschema );
				}
				$lbConf['serverTemplate']['sqlMode'] = $this->options->get( MainConfigNames::SQLMode );
				$serversCheck = [ $lbConf['serverTemplate'] ];
			}
		}

		self::assertValidServerConfigs(
			$serversCheck,
			$this->options->get( MainConfigNames::DBname ),
			$this->options->get( MainConfigNames::DBprefix )
		);

		$lbConf['cpStash'] = $this->cpStash;
		$lbConf['srvCache'] = $this->srvCache;
		$lbConf['wanCache'] = $this->wanCache;

		return $lbConf;
	}

	/**
	 * @return array
	 */
	private function getDbTypesWithSchemas() {
		return [ 'postgres' ];
	}

	/**
	 * @param array $server
	 * @param ServiceOptions $options
	 * @return array
	 */
	private function initServerInfo( array $server, ServiceOptions $options ) {
		if ( $server['type'] === 'sqlite' ) {
			$httpMethod = $_SERVER['REQUEST_METHOD'] ?? null;
			// T93097: hint for how file-based databases (e.g. sqlite) should go about locking.
			// See https://www.sqlite.org/lang_transaction.html
			// See https://www.sqlite.org/lockingv3.html#shared_lock
			$isHttpRead = in_array( $httpMethod, [ 'GET', 'HEAD', 'OPTIONS', 'TRACE' ] );
			if ( MW_ENTRY_POINT === 'rest' && !$isHttpRead ) {
				// Hack to support some re-entrant invocations using sqlite
				// See: T259685, T91820
				$request = \MediaWiki\Rest\EntryPoint::getMainRequest();
				if ( $request->hasHeader( 'Promise-Non-Write-API-Action' ) ) {
					$isHttpRead = true;
				}
			}
			$server += [
				'dbDirectory' => $options->get( MainConfigNames::SQLiteDataDir ),
				'trxMode' => $isHttpRead ? 'DEFERRED' : 'IMMEDIATE'
			];
		} elseif ( $server['type'] === 'postgres' ) {
			$server += [ 'port' => $options->get( MainConfigNames::DBport ) ];
		}

		if ( in_array( $server['type'], self::getDbTypesWithSchemas(), true ) ) {
			$server += [ 'schema' => $options->get( MainConfigNames::DBmwschema ) ];
		}

		$flags = $server['flags'] ?? DBO_DEFAULT;
		if ( $options->get( MainConfigNames::DebugDumpSql )
			|| $options->get( MainConfigNames::DebugLogFile )
			|| $options->get( MainConfigNames::DebugToolbar )
		) {
			$flags |= DBO_DEBUG;
		}
		$server['flags'] = $flags;

		$server += [
			'tablePrefix' => $options->get( MainConfigNames::DBprefix ),
			'sqlMode' => $options->get( MainConfigNames::SQLMode ),
		];

		return $server;
	}

	/**
	 * @param array $servers
	 * @param string $ldDB Local domain database name
	 * @param string $ldTP Local domain prefix
	 */
	private function assertValidServerConfigs( array $servers, $ldDB, $ldTP ) {
		foreach ( $servers as $server ) {
			$type = $server['type'] ?? null;
			$srvDB = $server['dbname'] ?? null; // server DB
			$srvTP = $server['tablePrefix'] ?? ''; // server table prefix

			if ( $type === 'mysql' ) {
				// A DB name is not needed to connect to mysql; 'dbname' is useless.
				// This field only defines the DB to use for unspecified DB domains.
				if ( $srvDB !== null && $srvDB !== $ldDB ) {
					self::reportMismatchedDBs( $srvDB, $ldDB );
				}
			} elseif ( $type === 'postgres' ) {
				if ( $srvTP !== '' ) {
					self::reportIfPrefixSet( $srvTP, $type );
				}
			}

			if ( $srvTP !== '' && $srvTP !== $ldTP ) {
				self::reportMismatchedPrefixes( $srvTP, $ldTP );
			}
		}
	}

	/**
	 * @param string $prefix Table prefix
	 * @param string $dbType Database type
	 * @return never
	 */
	private function reportIfPrefixSet( $prefix, $dbType ) {
		$e = new UnexpectedValueException(
			"\$wgDBprefix is set to '$prefix' but the database type is '$dbType'. " .
			"MediaWiki does not support using a table prefix with this RDBMS type."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_PRETTY );
		exit;
	}

	/**
	 * @param string $srvDB Server config database
	 * @param string $ldDB Local DB domain database
	 * @return never
	 */
	private function reportMismatchedDBs( $srvDB, $ldDB ) {
		$e = new UnexpectedValueException(
			"\$wgDBservers has dbname='$srvDB' but \$wgDBname='$ldDB'. " .
			"Set \$wgDBname to the database used by this wiki project. " .
			"There is rarely a need to set 'dbname' in \$wgDBservers. " .
			"Cross-wiki database access, use of WikiMap::getCurrentWikiDbDomain(), " .
			"use of Database::getDomainId(), and other features are not reliable when " .
			"\$wgDBservers does not match the local wiki database/prefix."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_PRETTY );
		exit;
	}

	/**
	 * @param string $srvTP Server config table prefix
	 * @param string $ldTP Local DB domain database
	 * @return never
	 */
	private function reportMismatchedPrefixes( $srvTP, $ldTP ) {
		$e = new UnexpectedValueException(
			"\$wgDBservers has tablePrefix='$srvTP' but \$wgDBprefix='$ldTP'. " .
			"Set \$wgDBprefix to the table prefix used by this wiki project. " .
			"There is rarely a need to set 'tablePrefix' in \$wgDBservers. " .
			"Cross-wiki database access, use of WikiMap::getCurrentWikiDbDomain(), " .
			"use of Database::getDomainId(), and other features are not reliable when " .
			"\$wgDBservers does not match the local wiki database/prefix."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_PRETTY );
		exit;
	}

	/**
	 * Decide which LBFactory class to use.
	 *
	 * @internal For use by ServiceWiring
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string Class name
	 */
	public function getLBFactoryClass( array $config ) {
		$compat = [
			// For LocalSettings.php compat after removing underscores (since 1.23).
			'LBFactory_Single' => Wikimedia\Rdbms\LBFactorySingle::class,
			'LBFactory_Simple' => Wikimedia\Rdbms\LBFactorySimple::class,
			'LBFactory_Multi' => Wikimedia\Rdbms\LBFactoryMulti::class,
			// For LocalSettings.php compat after moving classes to namespaces (since 1.29).
			'LBFactorySingle' => Wikimedia\Rdbms\LBFactorySingle::class,
			'LBFactorySimple' => Wikimedia\Rdbms\LBFactorySimple::class,
			'LBFactoryMulti' => Wikimedia\Rdbms\LBFactoryMulti::class
		];

		$class = $config['class'];
		return $compat[$class] ?? $class;
	}

	/**
	 * @param ILBFactory $lbFactory
	 */
	public function setDomainAliases( ILBFactory $lbFactory ) {
		$domain = DatabaseDomain::newFromId( $lbFactory->getLocalDomainID() );
		// For compatibility with hyphenated $wgDBname values on older wikis, handle callers
		// that assume corresponding database domain IDs and wiki IDs have identical values
		$rawLocalDomain = strlen( $domain->getTablePrefix() )
			? "{$domain->getDatabase()}-{$domain->getTablePrefix()}"
			: (string)$domain->getDatabase();

		$lbFactory->setDomainAliases( [ $rawLocalDomain => $domain ] );
	}

	/**
	 * Apply global state from the current web request or other PHP process.
	 *
	 * This technically violates the principle constraint on ServiceWiring to be
	 * deterministic for a given site configuration. The exemption made here
	 * is solely to aid in debugging and influence non-nominal behaviour such
	 * as ChronologyProtector. That is, the state applied here must never change
	 * the logical destination or meaning of any database-related methods, it
	 * merely applies preferences and debugging information.
	 *
	 * The code here must be non-essential, with LBFactory behaving the same toward
	 * its consumers regardless of whether this is applied or not.
	 *
	 * For example, something may instantiate LBFactory for the current wiki without
	 * calling this, and its consumers must not be able to tell the difference.
	 * Likewise, in the future MediaWiki may instantiate service wiring and LBFactory
	 * for a foreign wiki in the same farm and apply the current global state to that,
	 * and that should be fine as well.
	 *
	 * @param ILBFactory $lbFactory
	 * @param Config $config
	 * @param IBufferingStatsdDataFactory $stats
	 */
	public function applyGlobalState(
		ILBFactory $lbFactory,
		Config $config,
		IBufferingStatsdDataFactory $stats
	): void {
		// Use the global WebRequest singleton. The main reason for using this
		// is to call WebRequest::getIP() which is non-trivial to reproduce statically
		// because it needs $wgUsePrivateIPs, as well as ProxyLookup and HookRunner services.
		// TODO: Create a static version of WebRequest::getIP that accepts these three
		// as dependencies, and then call that here. The other uses of $req below can
		// trivially use $_COOKIES, $_GET and $_SERVER instead.
		$req = RequestContext::getMain()->getRequest();

		// Set user IP/agent information for agent session consistency purposes
		$reqStart = (int)( $_SERVER['REQUEST_TIME_FLOAT'] ?? time() );
		$cpPosInfo = LBFactory::getCPInfoFromCookieValue(
			// The cookie has no prefix and is set by MediaWiki::preOutputCommit()
			$req->getCookie( 'cpPosIndex', '' ),
			// Mitigate broken client-side cookie expiration handling (T190082)
			$reqStart - ChronologyProtector::POSITION_COOKIE_TTL
		);
		$lbFactory->setRequestInfo( [
			'IPAddress' => $req->getIP(),
			'UserAgent' => $req->getHeader( 'User-Agent' ),
			'ChronologyProtection' => $req->getHeader( 'MediaWiki-Chronology-Protection' ),
			'ChronologyPositionIndex' => $req->getInt( 'cpPosIndex', $cpPosInfo['index'] ),
			'ChronologyClientId' => $cpPosInfo['clientId']
				?? $req->getHeader( 'MediaWiki-Chronology-Client-Id' )
		] );

		if ( $config->get( 'CommandLineMode' ) ) {
			// Disable buffering and delaying of DeferredUpdates and stats
			// for maintenance scripts and PHPUnit tests.
			// Hook into period lag checks which often happen in long-running scripts
			$lbFactory->setWaitForReplicationListener(
				__METHOD__,
				static function () use ( $stats, $config ) {
					DeferredUpdates::tryOpportunisticExecute();
					// Flush stats periodically in long-running CLI scripts to avoid OOM (T181385)
					MediaWiki::emitBufferedStatsdData( $stats, $config );
				}
			);
			// Check for other windows to run them. A script may read or do a few writes
			// to the primary DB but mostly be writing to something else, like a file store.
			$lbFactory->getMainLB()->setTransactionListener(
				__METHOD__,
				static function ( $trigger ) use ( $stats, $config ) {
					if ( $trigger === IDatabase::TRIGGER_COMMIT ) {
						DeferredUpdates::tryOpportunisticExecute();
					}
					// Flush stats periodically in long-running CLI scripts to avoid OOM (T181385)
					MediaWiki::emitBufferedStatsdData( $stats, $config );
				}
			);

		}
	}

	/**
	 * Log a database deprecation warning
	 *
	 * @param string $msg Deprecation message
	 */
	public static function logDeprecation( $msg ) {
		if ( isset( self::$loggedDeprecations[$msg] ) ) {
			return;
		}
		self::$loggedDeprecations[$msg] = true;
		MWDebug::sendRawDeprecated( $msg, true, wfGetCaller() );
	}
}
