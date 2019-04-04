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

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * MediaWiki-specific class for generating database load balancers
 * @ingroup Database
 */
abstract class MWLBFactory {

	/** @var array Cache of already-logged deprecation messages */
	private static $loggedDeprecations = [];

	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @param Config $mainConfig Main config object from MediaWikiServices
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param BagOStuff $srvCace
	 * @param BagOStuff $mainStash
	 * @param WANObjectCache $wanCache
	 * @return array
	 */
	public static function applyDefaultConfig(
		array $lbConf,
		Config $mainConfig,
		ConfiguredReadOnlyMode $readOnlyMode,
		BagOStuff $srvCace,
		BagOStuff $mainStash,
		WANObjectCache $wanCache
	) {
		global $wgCommandLineMode;

		static $typesWithSchema = [ 'postgres', 'msssql' ];

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$mainConfig->get( 'DBname' ),
				$mainConfig->get( 'DBmwschema' ),
				$mainConfig->get( 'DBprefix' )
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
			'defaultGroup' => $mainConfig->get( 'DBDefaultGroup' ),
		];

		$serversCheck = [];
		// When making changes here, remember to also specify MediaWiki-specific options
		// for Database classes in the relevant Installer subclass.
		// Such as MysqlInstaller::openConnection and PostgresInstaller::openConnectionWithParams.
		if ( $lbConf['class'] === Wikimedia\Rdbms\LBFactorySimple::class ) {
			$httpMethod = $_SERVER['REQUEST_METHOD'] ?? null;
			// T93097: hint for how file-based databases (e.g. sqlite) should go about locking.
			// See https://www.sqlite.org/lang_transaction.html
			// See https://www.sqlite.org/lockingv3.html#shared_lock
			$isReadRequest = in_array( $httpMethod, [ 'GET', 'HEAD', 'OPTIONS', 'TRACE' ] );

			if ( isset( $lbConf['servers'] ) ) {
				// Server array is already explicitly configured; leave alone
			} elseif ( is_array( $mainConfig->get( 'DBservers' ) ) ) {
				$lbConf['servers'] = [];
				foreach ( $mainConfig->get( 'DBservers' ) as $i => $server ) {
					if ( $server['type'] === 'sqlite' ) {
						$server += [
							'dbDirectory' => $mainConfig->get( 'SQLiteDataDir' ),
							'trxMode' => $isReadRequest ? 'DEFERRED' : 'IMMEDIATE'
						];
					} elseif ( $server['type'] === 'postgres' ) {
						$server += [
							'port' => $mainConfig->get( 'DBport' ),
							// Work around the reserved word usage in MediaWiki schema
							'keywordTableMap' => [ 'user' => 'mwuser', 'text' => 'pagecontent' ]
						];
					} elseif ( $server['type'] === 'mssql' ) {
						$server += [
							'port' => $mainConfig->get( 'DBport' ),
							'useWindowsAuth' => $mainConfig->get( 'DBWindowsAuthentication' )
						];
					}

					if ( in_array( $server['type'], $typesWithSchema, true ) ) {
						$server += [ 'schema' => $mainConfig->get( 'DBmwschema' ) ];
					}

					$server += [
						'tablePrefix' => $mainConfig->get( 'DBprefix' ),
						'flags' => DBO_DEFAULT,
						'sqlMode' => $mainConfig->get( 'SQLMode' ),
					];

					$lbConf['servers'][$i] = $server;
				}
			} else {
				$flags = DBO_DEFAULT;
				$flags |= $mainConfig->get( 'DebugDumpSql' ) ? DBO_DEBUG : 0;
				$flags |= $mainConfig->get( 'DBssl' ) ? DBO_SSL : 0;
				$flags |= $mainConfig->get( 'DBcompress' ) ? DBO_COMPRESS : 0;
				$server = [
					'host' => $mainConfig->get( 'DBserver' ),
					'user' => $mainConfig->get( 'DBuser' ),
					'password' => $mainConfig->get( 'DBpassword' ),
					'dbname' => $mainConfig->get( 'DBname' ),
					'tablePrefix' => $mainConfig->get( 'DBprefix' ),
					'type' => $mainConfig->get( 'DBtype' ),
					'load' => 1,
					'flags' => $flags,
					'sqlMode' => $mainConfig->get( 'SQLMode' ),
					'trxMode' => $isReadRequest ? 'DEFERRED' : 'IMMEDIATE'
				];
				if ( in_array( $server['type'], $typesWithSchema, true ) ) {
					$server += [ 'schema' => $mainConfig->get( 'DBmwschema' ) ];
				}
				if ( $server['type'] === 'sqlite' ) {
					$server[ 'dbDirectory'] = $mainConfig->get( 'SQLiteDataDir' );
				} elseif ( $server['type'] === 'postgres' ) {
					$server['port'] = $mainConfig->get( 'DBport' );
					// Work around the reserved word usage in MediaWiki schema
					$server['keywordTableMap'] = [ 'user' => 'mwuser', 'text' => 'pagecontent' ];
				} elseif ( $server['type'] === 'mssql' ) {
					$server['port'] = $mainConfig->get( 'DBport' );
					$server['useWindowsAuth'] = $mainConfig->get( 'DBWindowsAuthentication' );
				}
				$lbConf['servers'] = [ $server ];
			}
			if ( !isset( $lbConf['externalClusters'] ) ) {
				$lbConf['externalClusters'] = $mainConfig->get( 'ExternalServers' );
			}

			$serversCheck = $lbConf['servers'];
		} elseif ( $lbConf['class'] === Wikimedia\Rdbms\LBFactoryMulti::class ) {
			if ( isset( $lbConf['serverTemplate'] ) ) {
				if ( in_array( $lbConf['serverTemplate']['type'], $typesWithSchema, true ) ) {
					$lbConf['serverTemplate']['schema'] = $mainConfig->get( 'DBmwschema' );
				}
				$lbConf['serverTemplate']['sqlMode'] = $mainConfig->get( 'SQLMode' );
			}
			$serversCheck = $lbConf['serverTemplate'] ?? [];
		}

		self::sanityCheckServerConfig( $serversCheck, $mainConfig );
		$lbConf = self::applyDefaultCaching( $lbConf, $srvCace, $mainStash, $wanCache );

		return $lbConf;
	}

	/**
	 * @param array $lbConf
	 * @param BagOStuff $sCache
	 * @param BagOStuff $mStash
	 * @param WANObjectCache $wCache
	 * @return array
	 */
	private static function applyDefaultCaching(
		array $lbConf, BagOStuff $sCache, BagOStuff $mStash, WANObjectCache $wCache
	) {
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
	 * @param Config $mainConfig
	 */
	private static function sanityCheckServerConfig( array $servers, Config $mainConfig ) {
		$ldDB = $mainConfig->get( 'DBname' ); // local domain DB
		$ldTP = $mainConfig->get( 'DBprefix' ); // local domain prefix

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

	public static function setSchemaAliases( LBFactory $lbFactory, Config $config ) {
		if ( $config->get( 'DBtype' ) === 'mysql' ) {
			/**
			 * When SQLite indexes were introduced in r45764, it was noted that
			 * SQLite requires index names to be unique within the whole database,
			 * not just within a schema. As discussed in CR r45819, to avoid the
			 * need for a schema change on existing installations, the indexes
			 * were implicitly mapped from the new names to the old names.
			 *
			 * This mapping can be removed if DB patches are introduced to alter
			 * the relevant tables in existing installations. Note that because
			 * this index mapping applies to table creation, even new installations
			 * of MySQL have the old names (except for installations created during
			 * a period where this mapping was inappropriately removed, see
			 * T154872).
			 */
			$lbFactory->setIndexAliases( [
				'ar_usertext_timestamp' => 'usertext_timestamp',
				'un_user_id' => 'user_id',
				'un_user_ip' => 'user_ip',
			] );
		}
	}

	/**
	 * Log a database deprecation warning
	 * @param string $msg Deprecation message
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
