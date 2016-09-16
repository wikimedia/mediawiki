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

/**
 * Legacy MediaWiki-specific class for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactoryMW extends LBFactory {
	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 * @TODO: inject objects via dependency framework
	 */
	public function __construct( array $conf ) {
		parent::__construct( self::applyDefaultConfig( $conf ) );
	}

	/**
	 * @param array $conf
	 * @return array
	 * @TODO: inject objects via dependency framework
	 */
	public static function applyDefaultConfig( array $conf ) {
		global $wgDBtype, $wgSQLMode, $wgDBmysql5, $wgDBname, $wgDBprefix, $wgDBmwschema;
		global $wgCommandLineMode;

		$defaults = [
			'localDomain' => new DatabaseDomain( $wgDBname, null, $wgDBprefix ),
			'hostname' => wfHostname(),
			'profiler' => Profiler::instance(),
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'queryLogger' => LoggerFactory::getInstance( 'DBQuery' ),
			'connLogger' => LoggerFactory::getInstance( 'DBConnection' ),
			'perfLogger' => LoggerFactory::getInstance( 'DBPerformance' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ],
			'cliMode' => $wgCommandLineMode,
			'agent' => ''
		];
		// Use APC/memcached style caching, but avoids loops with CACHE_DB (T141804)
		$sCache = ObjectCache::getLocalServerInstance();
		if ( $sCache->getQoS( $sCache::ATTR_EMULATION ) > $sCache::QOS_EMULATION_SQL ) {
			$defaults['srvCache'] = $sCache;
		}
		$cCache = ObjectCache::getLocalClusterInstance();
		if ( $cCache->getQoS( $cCache::ATTR_EMULATION ) > $cCache::QOS_EMULATION_SQL ) {
			$defaults['memCache'] = $cCache;
		}
		$wCache = ObjectCache::getMainWANInstance();
		if ( $wCache->getQoS( $wCache::ATTR_EMULATION ) > $wCache::QOS_EMULATION_SQL ) {
			$defaults['wanCache'] = $wCache;
		}

		// Determine schema defaults. Currently Microsoft SQL Server uses $wgDBmwschema,
		// and everything else doesn't use a schema (e.g. null)
		// Although postgres and oracle support schemas, we don't use them (yet)
		// to maintain backwards compatibility
		$schema = ( $wgDBtype === 'mssql' ) ? $wgDBmwschema : null;

		if ( isset( $conf['serverTemplate'] ) ) { // LBFactoryMulti
			$conf['serverTemplate']['schema'] = $schema;
			$conf['serverTemplate']['sqlMode'] = $wgSQLMode;
			$conf['serverTemplate']['utf8Mode'] = $wgDBmysql5;
		} elseif ( isset( $conf['servers'] ) ) { // LBFactorySimple
			foreach ( $conf['servers'] as $i => $server ) {
				$conf['servers'][$i]['schema'] = $schema;
			}
		}

		return $conf + $defaults;
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

		return $class;
	}
}
