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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Deferred\LinksUpdate\ExternalLinksTable;
use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Exception\MWExceptionRenderer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\TracerInterface;

/**
 * MediaWiki-specific class for generating database load balancers
 *
 * @internal For use by core ServiceWiring only.
 * @ingroup Database
 */
class MWLBFactory {

	/** Cache of already-logged deprecation messages */
	private static array $loggedDeprecations = [];

	public const CORE_VIRTUAL_DOMAINS = [
		'virtual-botpasswords',
		'virtual-interwiki',
		'virtual-interwiki-interlanguage',
		ExternalLinksTable::VIRTUAL_DOMAIN,
		TemplateLinksTable::VIRTUAL_DOMAIN,
		ImageLinksTable::VIRTUAL_DOMAIN,
	];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const APPLY_DEFAULT_CONFIG_OPTIONS = [
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
		MainConfigNames::DBStrictWarnings,
		MainConfigNames::DBtype,
		MainConfigNames::DBuser,
		MainConfigNames::DebugDumpSql,
		MainConfigNames::DebugLogFile,
		MainConfigNames::DebugToolbar,
		MainConfigNames::ExternalServers,
		MainConfigNames::SQLiteDataDir,
		MainConfigNames::SQLMode,
		MainConfigNames::VirtualDomainsMapping,
	];
	private ServiceOptions $options;
	private ConfiguredReadOnlyMode $readOnlyMode;
	private ChronologyProtector $chronologyProtector;
	private BagOStuff $srvCache;
	private WANObjectCache $wanCache;
	private CriticalSectionProvider $csProvider;
	private StatsFactory $statsFactory;
	private TracerInterface $tracer;
	/** @var string[] */
	private array $virtualDomains;

	/**
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param ChronologyProtector $chronologyProtector
	 * @param BagOStuff $srvCache
	 * @param WANObjectCache $wanCache
	 * @param CriticalSectionProvider $csProvider
	 * @param StatsFactory $statsFactory
	 * @param string[] $virtualDomains
	 * @param TracerInterface $tracer
	 */
	public function __construct(
		ServiceOptions $options,
		ConfiguredReadOnlyMode $readOnlyMode,
		ChronologyProtector $chronologyProtector,
		BagOStuff $srvCache,
		WANObjectCache $wanCache,
		CriticalSectionProvider $csProvider,
		StatsFactory $statsFactory,
		array $virtualDomains,
		TracerInterface $tracer
	) {
		$this->options = $options;
		$this->readOnlyMode = $readOnlyMode;
		$this->chronologyProtector = $chronologyProtector;
		$this->srvCache = $srvCache;
		$this->wanCache = $wanCache;
		$this->csProvider = $csProvider;
		$this->statsFactory = $statsFactory;
		$this->virtualDomains = $virtualDomains;
		$this->tracer = $tracer;
	}

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @return array
	 * @internal For use with service wiring
	 */
	public function applyDefaultConfig( array $lbConf ): array {
		$this->options->assertRequiredOptions( self::APPLY_DEFAULT_CONFIG_OPTIONS );

		$typesWithSchema = self::getDbTypesWithSchemas();
		if ( Profiler::instance() instanceof ProfilerStub ) {
			$profilerCallback = null;
		} else {
			$profilerCallback = static function ( $section ) {
				return Profiler::instance()->scopedProfileIn( $section );
			};
		}

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$this->options->get( MainConfigNames::DBname ),
				$this->options->get( MainConfigNames::DBmwschema ),
				$this->options->get( MainConfigNames::DBprefix )
			),
			'profiler' => $profilerCallback,
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'logger' => LoggerFactory::getInstance( 'rdbms' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ],
			'deprecationLogger' => [ static::class, 'logDeprecation' ],
			'statsFactory' => $this->statsFactory,
			'cliMode' => MW_ENTRY_POINT === 'cli',
			'readOnlyReason' => $this->readOnlyMode->getReason(),
			'defaultGroup' => $this->options->get( MainConfigNames::DBDefaultGroup ),
			'criticalSectionProvider' => $this->csProvider,
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
				if ( $this->options->get( MainConfigNames::DBStrictWarnings ) ) {
					$server['strictWarnings'] = true;
				}

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

		$lbConf['chronologyProtector'] = $this->chronologyProtector;
		$lbConf['srvCache'] = $this->srvCache;
		$lbConf['wanCache'] = $this->wanCache;
		$lbConf['tracer'] = $this->tracer;
		$lbConf['virtualDomains'] = array_merge( $this->virtualDomains, self::CORE_VIRTUAL_DOMAINS );
		$lbConf['virtualDomainsMapping'] = $this->options->get( MainConfigNames::VirtualDomainsMapping );

		return $lbConf;
	}

	private function getDbTypesWithSchemas(): array {
		return [ 'postgres' ];
	}

	/**
	 * @param array $server
	 * @param ServiceOptions $options
	 * @return array
	 */
	private function initServerInfo( array $server, ServiceOptions $options ): array {
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
	private function assertValidServerConfigs( array $servers, string $ldDB, string $ldTP ): void {
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
	private function reportIfPrefixSet( string $prefix, string $dbType ): never {
		$e = new UnexpectedValueException(
			"\$wgDBprefix is set to '$prefix' but the database type is '$dbType'. " .
			"MediaWiki does not support using a table prefix with this RDBMS type."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_RAW );
		exit;
	}

	/**
	 * @param string $srvDB Server config database
	 * @param string $ldDB Local DB domain database
	 * @return never
	 */
	private function reportMismatchedDBs( string $srvDB, string $ldDB ): never {
		$e = new UnexpectedValueException(
			"\$wgDBservers has dbname='$srvDB' but \$wgDBname='$ldDB'. " .
			"Set \$wgDBname to the database used by this wiki project. " .
			"There is rarely a need to set 'dbname' in \$wgDBservers. " .
			"Cross-wiki database access, use of WikiMap::getCurrentWikiDbDomain(), " .
			"use of Database::getDomainId(), and other features are not reliable when " .
			"\$wgDBservers does not match the local wiki database/prefix."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_RAW );
		exit;
	}

	/**
	 * @param string $srvTP Server config table prefix
	 * @param string $ldTP Local DB domain database
	 * @return never
	 */
	private function reportMismatchedPrefixes( string $srvTP, string $ldTP ): never {
		$e = new UnexpectedValueException(
			"\$wgDBservers has tablePrefix='$srvTP' but \$wgDBprefix='$ldTP'. " .
			"Set \$wgDBprefix to the table prefix used by this wiki project. " .
			"There is rarely a need to set 'tablePrefix' in \$wgDBservers. " .
			"Cross-wiki database access, use of WikiMap::getCurrentWikiDbDomain(), " .
			"use of Database::getDomainId(), and other features are not reliable when " .
			"\$wgDBservers does not match the local wiki database/prefix."
		);
		MWExceptionRenderer::output( $e, MWExceptionRenderer::AS_RAW );
		exit;
	}

	/**
	 * Decide which LBFactory class to use.
	 *
	 * @internal For use by ServiceWiring
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string Class name
	 */
	public function getLBFactoryClass( array $config ): string {
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

	public function setDomainAliases( ILBFactory $lbFactory ): void {
		$domain = DatabaseDomain::newFromId( $lbFactory->getLocalDomainID() );
		// For compatibility with hyphenated $wgDBname values on older wikis, handle callers
		// that assume corresponding database domain IDs and wiki IDs have identical values
		$rawLocalDomain = strlen( $domain->getTablePrefix() )
			? "{$domain->getDatabase()}-{$domain->getTablePrefix()}"
			: (string)$domain->getDatabase();

		$lbFactory->setDomainAliases( [ $rawLocalDomain => $domain ] );
	}

	/**
	 * Log a database deprecation warning
	 *
	 * @param string $msg Deprecation message
	 */
	public static function logDeprecation( string $msg ): void {
		if ( isset( self::$loggedDeprecations[$msg] ) ) {
			return;
		}
		self::$loggedDeprecations[$msg] = true;
		MWDebug::sendRawDeprecated( $msg, true, wfGetCaller() );
	}
}
