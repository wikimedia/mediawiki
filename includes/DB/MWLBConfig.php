<?php
/**
 * Generator of database load balancing objects.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Database
 */

namespace MediaWiki\DB;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\LinksUpdate\LinksTable;
use MediaWiki\Exception\MWExceptionRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\EntryPoint;
use UnexpectedValueException;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\LBFactoryMulti;
use Wikimedia\Rdbms\LBFactorySimple;

/**
 * MediaWiki-specific class for generating configuration of load balancers
 *
 * @internal For use by core ServiceWiring only.
 * @ingroup Database
 */
class MWLBConfig {
	private const array DB_TYPES_WITH_SCHEMAS = [ 'postgres' ];

	public const CORE_VIRTUAL_DOMAINS = [
		'virtual-botpasswords',
		'virtual-interwiki',
		'virtual-interwiki-interlanguage',
		LinksTable::VIRTUAL_DOMAIN,
	];

	/**
	 * @internal For use by ServiceWiring
	 */
	public const APPLY_DEFAULT_CONFIG_OPTIONS = [
		MainConfigNames::DBcompress,
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

	/** @var string[] */
	private array $virtualDomains;

	private array $lbConf;

	/**
	 * @param ServiceOptions $options
	 * @param string[] $virtualDomains
	 * @param array $lbConf
	 */
	public function __construct(
		ServiceOptions $options,
		array $virtualDomains,
		array $lbConf
	) {
		$this->options = $options;
		$this->virtualDomains = $virtualDomains;
		$this->lbConf = $this->applyConfig( $lbConf );
	}

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @return array
	 * @internal For use with service wiring
	 */
	private function applyConfig( array $lbConf ): array {
		$this->options->assertRequiredOptions( self::APPLY_DEFAULT_CONFIG_OPTIONS );

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$this->options->get( MainConfigNames::DBname ),
				$this->options->get( MainConfigNames::DBmwschema ),
				$this->options->get( MainConfigNames::DBprefix )
			),
		];

		$serversCheck = [];
		// When making changes here, remember to also specify MediaWiki-specific options
		// for Database classes in the relevant Installer subclass.
		// Such as MysqlInstaller::openConnection and PostgresInstaller::openConnectionWithParams.
		if ( $lbConf['class'] === LBFactorySimple::class ) {
			if ( isset( $lbConf['servers'] ) ) {
				// Server array is already explicitly configured
			} elseif ( is_array( $this->options->get( MainConfigNames::DBservers ) ) ) {
				$lbConf['servers'] = [];
				foreach ( $this->options->get( MainConfigNames::DBservers ) as $i => $server ) {
					$lbConf['servers'][$i] = $this->initServerInfo( $server, $this->options );
				}
			} else {
				$server = $this->initServerInfo(
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
		} elseif ( $lbConf['class'] === LBFactoryMulti::class ) {
			if ( isset( $lbConf['serverTemplate'] ) ) {
				if ( in_array( $lbConf['serverTemplate']['type'], self::DB_TYPES_WITH_SCHEMAS, true ) ) {
					$lbConf['serverTemplate']['schema'] = $this->options->get( MainConfigNames::DBmwschema );
				}
				$lbConf['serverTemplate']['sqlMode'] = $this->options->get( MainConfigNames::SQLMode );
				$serversCheck = [ $lbConf['serverTemplate'] ];
			}
		}

		$this->assertValidServerConfigs(
			$serversCheck,
			$this->options->get( MainConfigNames::DBname ),
			$this->options->get( MainConfigNames::DBprefix )
		);

		$lbConf['virtualDomains'] = array_merge( $this->virtualDomains, self::CORE_VIRTUAL_DOMAINS );
		$lbConf['virtualDomainsMapping'] = $this->options->get( MainConfigNames::VirtualDomainsMapping );

		return $lbConf;
	}

	/**
	 * @return array the LBFactory config
	 */
	public function getConfig() {
		return $this->lbConf;
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
				$request = EntryPoint::getMainRequest();
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

		if ( in_array( $server['type'], self::DB_TYPES_WITH_SCHEMAS, true ) ) {
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
					$this->reportMismatchedDBs( $srvDB, $ldDB );
				}
			} elseif ( $type === 'postgres' ) {
				if ( $srvTP !== '' ) {
					$this->reportIfPrefixSet( $srvTP, $type );
				}
			}

			if ( $srvTP !== '' && $srvTP !== $ldTP ) {
				$this->reportMismatchedPrefixes( $srvTP, $ldTP );
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
}
