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
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * MediaWiki-specific class for generating database load balancers
 * @ingroup Database
 */
abstract class MWLBFactory {
	/**
	 * @param array $lbConf Config for LBFactory::__construct()
	 * @param Config $mainConfig Main config object from MediaWikiServices
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @return array
	 */
	public static function applyDefaultConfig( array $lbConf, Config $mainConfig,
		ConfiguredReadOnlyMode $readOnlyMode
	) {
		global $wgCommandLineMode;

		static $typesWithSchema = [ 'postgres', 'msssql' ];

		$lbConf += [
			'localDomain' => new DatabaseDomain(
				$mainConfig->get( 'DBname' ),
				$mainConfig->get( 'DBmwschema' ),
				$mainConfig->get( 'DBprefix' )
			),
			'profiler' => Profiler::instance(),
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'queryLogger' => LoggerFactory::getInstance( 'DBQuery' ),
			'connLogger' => LoggerFactory::getInstance( 'DBConnection' ),
			'perfLogger' => LoggerFactory::getInstance( 'DBPerformance' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ],
			'cliMode' => $wgCommandLineMode,
			'hostname' => wfHostname(),
			'readOnlyReason' => $readOnlyMode->getReason(),
		];

		// When making changes here, remember to also specify MediaWiki-specific options
		// for Database classes in the relevant Installer subclass.
		// Such as MysqlInstaller::openConnection and PostgresInstaller::openConnectionWithParams.
		if ( $lbConf['class'] === Wikimedia\Rdbms\LBFactorySimple::class ) {
			if ( isset( $lbConf['servers'] ) ) {
				// Server array is already explicitly configured; leave alone
			} elseif ( is_array( $mainConfig->get( 'DBservers' ) ) ) {
				foreach ( $mainConfig->get( 'DBservers' ) as $i => $server ) {
					if ( $server['type'] === 'sqlite' ) {
						$server += [ 'dbDirectory' => $mainConfig->get( 'SQLiteDataDir' ) ];
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
						'utf8Mode' => $mainConfig->get( 'DBmysql5' )
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
					'utf8Mode' => $mainConfig->get( 'DBmysql5' )
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
		} elseif ( $lbConf['class'] === Wikimedia\Rdbms\LBFactoryMulti::class ) {
			if ( isset( $lbConf['serverTemplate'] ) ) {
				if ( in_array( $lbConf['serverTemplate']['type'], $typesWithSchema, true ) ) {
					$lbConf['serverTemplate']['schema'] = $mainConfig->get( 'DBmwschema' );
				}
				$lbConf['serverTemplate']['sqlMode'] = $mainConfig->get( 'SQLMode' );
				$lbConf['serverTemplate']['utf8Mode'] = $mainConfig->get( 'DBmysql5' );
			}
		}

		$services = MediaWikiServices::getInstance();

		// Use APC/memcached style caching, but avoids loops with CACHE_DB (T141804)
		$sCache = $services->getLocalServerObjectCache();
		if ( $sCache->getQoS( $sCache::ATTR_EMULATION ) > $sCache::QOS_EMULATION_SQL ) {
			$lbConf['srvCache'] = $sCache;
		}
		$mStash = $services->getMainObjectStash();
		if ( $mStash->getQoS( $mStash::ATTR_EMULATION ) > $mStash::QOS_EMULATION_SQL ) {
			$lbConf['memStash'] = $mStash;
		}
		$wCache = $services->getMainWANObjectCache();
		if ( $wCache->getQoS( $wCache::ATTR_EMULATION ) > $wCache::QOS_EMULATION_SQL ) {
			$lbConf['wanCache'] = $wCache;
		}

		return $lbConf;
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

	public static function setSchemaAliases( LBFactory $lbFactory ) {
		$mainLB = $lbFactory->getMainLB();
		$masterType = $mainLB->getServerType( $mainLB->getWriterIndex() );
		if ( $masterType === 'mysql' ) {
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
}
