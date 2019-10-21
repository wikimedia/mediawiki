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
use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * MediaWiki-specific class for generating database load balancers
 * @ingroup Database
 */
abstract class MWLBFactory {

	/** @var array Cache of already-logged deprecation messages */
	private static $loggedDeprecations = [];

	/**
	 * @var array
	 * @since 1.34
	 */
	public const APPLY_DEFAULT_CONFIG_OPTIONS = [
		'DBcompress',
		'DBDefaultGroup',
		'DBmwschema',
		'DBname',
		'DBpassword',
		'DBport',
		'DBprefix',
		'DBserver',
		'DBservers',
		'DBssl',
		'DBtype',
		'DBuser',
		'DBWindowsAuthentication',
		'DebugDumpSql',
		'DebugLogFile',
		'ExternalServers',
		'SQLiteDataDir',
		'SQLMode',
	];

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param BagOStuff $srvCache
	 * @param BagOStuff $mainStash
	 * @param WANObjectCache $wanCache
	 * @return array
	 * @internal For use with service wiring
	 */
	public static function applyDefaultConfig(
		array $lbConf,
		ServiceOptions $options,
		ConfiguredReadOnlyMode $readOnlyMode,
		BagOStuff $srvCache,
		BagOStuff $mainStash,
		WANObjectCache $wanCache
	) {
		$options->assertRequiredOptions( self::APPLY_DEFAULT_CONFIG_OPTIONS );

		global $wgCommandLineMode;

		$typesWithSchema = self::getDbTypesWithSchemas();

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$options->get( 'DBname' ),
				$options->get( 'DBmwschema' ),
				$options->get( 'DBprefix' )
			),
			'profiler' => function ( $section ) {
				return Profiler::instance()->scopedProfileIn( $section );
			},
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'queryLogger' => LoggerFactory::getInstance( 'DBQuery' ),
			'connLogger' => LoggerFactory::getInstance( 'DBConnection' ),
			'perfLogger' => LoggerFactory::getInstance( 'DBPerformance' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ],
			'deprecationLogger' => [ static::class, 'logDeprecation' ],
			'cliMode' => $wgCommandLineMode,
			'hostname' => wfHostname(),
			'readOnlyReason' => $readOnlyMode->getReason(),
			'defaultGroup' => $options->get( 'DBDefaultGroup' ),
		];

		$serversCheck = [];
		// When making changes here, remember to also specify MediaWiki-specific options
		// for Database classes in the relevant Installer subclass.
		// Such as MysqlInstaller::openConnection and PostgresInstaller::openConnectionWithParams.
		if ( $lbConf['class'] === Wikimedia\Rdbms\LBFactorySimple::class ) {
			if ( isset( $lbConf['servers'] ) ) {
				// Server array is already explicitly configured
			} elseif ( is_array( $options->get( 'DBservers' ) ) ) {
				$lbConf['servers'] = [];
				foreach ( $options->get( 'DBservers' ) as $i => $server ) {
					$lbConf['servers'][$i] = self::initServerInfo( $server, $options );
				}
			} else {
				$server = self::initServerInfo(
					[
						'host' => $options->get( 'DBserver' ),
						'user' => $options->get( 'DBuser' ),
						'password' => $options->get( 'DBpassword' ),
						'dbname' => $options->get( 'DBname' ),
						'type' => $options->get( 'DBtype' ),
						'load' => 1
					],
					$options
				);

				$server['flags'] |= $options->get( 'DBssl' ) ? DBO_SSL : 0;
				$server['flags'] |= $options->get( 'DBcompress' ) ? DBO_COMPRESS : 0;

				$lbConf['servers'] = [ $server ];
			}
			if ( !isset( $lbConf['externalClusters'] ) ) {
				$lbConf['externalClusters'] = $options->get( 'ExternalServers' );
			}

			$serversCheck = $lbConf['servers'];
		} elseif ( $lbConf['class'] === Wikimedia\Rdbms\LBFactoryMulti::class ) {
			if ( isset( $lbConf['serverTemplate'] ) ) {
				if ( in_array( $lbConf['serverTemplate']['type'], $typesWithSchema, true ) ) {
					$lbConf['serverTemplate']['schema'] = $options->get( 'DBmwschema' );
				}
				$lbConf['serverTemplate']['sqlMode'] = $options->get( 'SQLMode' );
			}
			$serversCheck = [ $lbConf['serverTemplate'] ] ?? [];
		}

		self::assertValidServerConfigs(
			$serversCheck,
			$options->get( 'DBname' ),
			$options->get( 'DBprefix' )
		);

		$lbConf = self::injectObjectCaches( $lbConf, $srvCache, $mainStash, $wanCache );

		return $lbConf;
	}

	/**
	 * @return array
	 */
	private static function getDbTypesWithSchemas() {
		return [ 'postgres' ];
	}

	/**
	 * @param array $server
	 * @param ServiceOptions $options
	 * @return array
	 */
	private static function initServerInfo( array $server, ServiceOptions $options ) {
		if ( $server['type'] === 'sqlite' ) {
			$httpMethod = $_SERVER['REQUEST_METHOD'] ?? null;
			// T93097: hint for how file-based databases (e.g. sqlite) should go about locking.
			// See https://www.sqlite.org/lang_transaction.html
			// See https://www.sqlite.org/lockingv3.html#shared_lock
			$isHttpRead = in_array( $httpMethod, [ 'GET', 'HEAD', 'OPTIONS', 'TRACE' ] );
			$server += [
				'dbDirectory' => $options->get( 'SQLiteDataDir' ),
				'trxMode' => $isHttpRead ? 'DEFERRED' : 'IMMEDIATE'
			];
		} elseif ( $server['type'] === 'postgres' ) {
			$server += [
				'port' => $options->get( 'DBport' ),
				// Work around the reserved word usage in MediaWiki schema
				'keywordTableMap' => [ 'user' => 'mwuser', 'text' => 'pagecontent' ]
			];
		}

		if ( in_array( $server['type'], self::getDbTypesWithSchemas(), true ) ) {
			$server += [ 'schema' => $options->get( 'DBmwschema' ) ];
		}

		$flags = $server['flags'] ?? DBO_DEFAULT;
		if ( $options->get( 'DebugDumpSql' ) || $options->get( 'DebugLogFile' ) ) {
			$flags |= DBO_DEBUG;
		}
		$server['flags'] = $flags;

		$server += [
			'tablePrefix' => $options->get( 'DBprefix' ),
			'sqlMode' => $options->get( 'SQLMode' ),
		];

		return $server;
	}

	/**
	 * @param array $lbConf
	 * @param BagOStuff $sCache
	 * @param BagOStuff $mStash
	 * @param WANObjectCache $wCache
	 * @return array
	 */
	private static function injectObjectCaches(
		array $lbConf, BagOStuff $sCache, BagOStuff $mStash, WANObjectCache $wCache
	) {
		// Fallback if APC style caching is not an option
		if ( $sCache instanceof EmptyBagOStuff ) {
			$sCache = new HashBagOStuff( [ 'maxKeys' => 100 ] );
		}

		// Use APC/memcached style caching, but avoids loops with CACHE_DB (T141804)
		if ( $sCache->getQoS( $sCache::ATTR_EMULATION ) > $sCache::QOS_EMULATION_SQL ) {
			$lbConf['srvCache'] = $sCache;
		}
		if ( $mStash->getQoS( $mStash::ATTR_EMULATION ) > $mStash::QOS_EMULATION_SQL ) {
			$lbConf['memStash'] = $mStash;
		}
		if ( $wCache->getQoS( $wCache::ATTR_EMULATION ) > $wCache::QOS_EMULATION_SQL ) {
			$lbConf['wanCache'] = $wCache;
		}

		return $lbConf;
	}

	/**
	 * @param array $servers
	 * @param string $ldDB Local domain database name
	 * @param string $ldTP Local domain prefix
	 */
	private static function assertValidServerConfigs( array $servers, $ldDB, $ldTP ) {
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
	 */
	private static function reportIfPrefixSet( $prefix, $dbType ) {
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
	 */
	private static function reportMismatchedDBs( $srvDB, $ldDB ) {
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
	 */
	private static function reportMismatchedPrefixes( $srvTP, $ldTP ) {
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
	 * Returns the LBFactory class to use and the load balancer configuration.
	 *
	 * @todo instead of this, use a ServiceContainer for managing the different implementations.
	 *
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string Class name
	 * @internal For use with service wiring
	 */
	public static function getLBFactoryClass( array $config ) {
		// For configuration backward compatibility after removing
		// underscores from class names in MediaWiki 1.23.
		$bcClasses = [
			'LBFactory_Simple' => 'LBFactorySimple',
			'LBFactory_Single' => 'LBFactorySingle',
			'LBFactory_Multi' => 'LBFactoryMulti'
		];

		$class = $config['class'];

		if ( isset( $bcClasses[$class] ) ) {
			$class = $bcClasses[$class];
			wfDeprecated(
				'$wgLBFactoryConf must be updated. See RELEASE-NOTES for details',
				'1.23'
			);
		}

		// For configuration backward compatibility after moving classes to namespaces (1.29)
		$compat = [
			'LBFactorySingle' => Wikimedia\Rdbms\LBFactorySingle::class,
			'LBFactorySimple' => Wikimedia\Rdbms\LBFactorySimple::class,
			'LBFactoryMulti' => Wikimedia\Rdbms\LBFactoryMulti::class
		];

		if ( isset( $compat[$class] ) ) {
			$class = $compat[$class];
		}

		return $class;
	}

	/**
	 * Log a database deprecation warning
	 * @param string $msg Deprecation message
	 * @internal For use with service wiring
	 */
	public static function logDeprecation( $msg ) {
		global $wgDevelopmentWarnings;

		if ( isset( self::$loggedDeprecations[$msg] ) ) {
			return;
		}
		self::$loggedDeprecations[$msg] = true;

		if ( $wgDevelopmentWarnings ) {
			trigger_error( $msg, E_USER_DEPRECATED );
		}
		wfDebugLog( 'deprecated', $msg, 'private' );
	}
}
