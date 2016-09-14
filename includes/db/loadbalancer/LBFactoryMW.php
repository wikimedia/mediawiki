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

use MediaWiki\MediaWikiServices;
use MediaWiki\Services\DestructibleService;
use MediaWiki\Logger\LoggerFactory;

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactoryMW extends LBFactory implements DestructibleService {
	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 * @TODO: inject objects via dependency framework
	 */
	public function __construct( array $conf ) {
		$defaults = [
			'domain' => wfWikiID(),
			'hostname' => wfHostname(),
			'trxProfiler' => Profiler::instance()->getTransactionProfiler(),
			'replLogger' => LoggerFactory::getInstance( 'DBReplication' ),
			'queryLogger' => LoggerFactory::getInstance( 'wfLogDBError' ),
			'connLogger' => LoggerFactory::getInstance( 'wfLogDBError' ),
			'perfLogger' => LoggerFactory::getInstance( 'DBPerformance' ),
			'errorLogger' => [ MWExceptionHandler::class, 'logException' ]
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

		parent::__construct( $conf + $defaults );
	}

	/**
	 * Disables all access to the load balancer, will cause all database access
	 * to throw a DBAccessError
	 *
	 * @deprecated since 1.28, Use MediaWikiServices::disableStorageBackend()
	 */
	public static function disableBackend() {
		MediaWikiServices::disableStorageBackend();
	}

	/**
	 * Get an LBFactory instance
	 *
	 * @deprecated since 1.27, use MediaWikiServices::getDBLoadBalancerFactory() instead.
	 *
	 * @return LBFactory
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
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
	 *
	 * @deprecated since 1.27, use LBFactory::destroy()
	 */
	public static function destroyInstance() {
		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->destroy();
	}

	/**
	 * @return bool
	 * @since 1.27
	 * @deprecated Since 1.28; use laggedReplicaUsed()
	 */
	public function laggedSlaveUsed() {
		return $this->laggedReplicaUsed();
	}

	protected function newChronologyProtector() {
		$request = RequestContext::getMain()->getRequest();
		$chronProt = new ChronologyProtector(
			ObjectCache::getMainStashInstance(),
			[
				'ip' => $request->getIP(),
				'agent' => $request->getHeader( 'User-Agent' ),
			],
			$request->getFloat( 'cpPosTime', $request->getCookie( 'cpPosTime', '' ) )
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
	 * Append ?cpPosTime parameter to a URL for ChronologyProtector purposes if needed
	 *
	 * Note that unlike cookies, this works accross domains
	 *
	 * @param string $url
	 * @param float $time UNIX timestamp just before shutdown() was called
	 * @return string
	 * @since 1.28
	 */
	public function appendPreShutdownTimeAsQuery( $url, $time ) {
		$usedCluster = 0;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$usedCluster ) {
			$usedCluster |= ( $lb->getServerCount() > 1 );
		} );

		if ( !$usedCluster ) {
			return $url; // no master/replica clusters touched
		}

		return wfAppendQuery( $url, [ 'cpPosTime' => $time ] );
	}
}
