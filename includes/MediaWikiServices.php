<?php
namespace MediaWiki;

use ConfigFactory;
use FileBackendGroup;
use GlobalVarConfig;
use Config;
use Hooks;
use LBFactory;
use LoadBalancer;
use MediaWiki\Services\ServiceContainer;
use ObjectCacheManager;
use Profiler;
use SiteLookup;
use SiteStore;

/**
 * Service locator for MediaWiki core services.
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
 *
 * @since 1.27
 */

/**
 * MediaWikiServices is the service locator for the application scope of MediaWiki.
 * Its implemented as a simple configurable DI container.
 * MediaWikiServices acts as a top level factory/registry for top level services, and builds
 * the network of service objects that defines MediaWiki's application logic.
 * It acts as an entry point to MediaWiki's dependency injection mechanism.
 *
 * Services are defined in the "wiring" array passed to the constructor,
 * or by calling defineService().
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */
class MediaWikiServices extends ServiceContainer {

	/**
	 * @var MediaWikiServices|null
	 */
	private static $instance = null;

	/**
	 * Returns the global default instance of the top level service locator.
	 *
	 * The default instance is initialized using the service constructor callbacks
	 * defined in ServiceWiring.php.
	 *
	 * @note This should only be called by static functions! The instance returned here
	 * should not be passed around! Objects that need access to a service should have
	 * that service injected into the constructor, never a service locator!
	 *
	 * @return MediaWikiServices
	 */
	public static function getInstance() {
		if ( self::$instance === null ) {
			self::resetGlobalInstance();
		}

		return self::$instance;
	}

	/**
	 * Creates a new instance of MediaWikiServices and sets it as the global default
	 * instance. getInstance() will return a different MediaWikiServices object
	 * after every call to resetGlobalServiceLocator().
	 *
	 * @warning ONLY USE THIS IF YOU ABSOLUTELY MUST AND KNOW WHAT YOU ARE DOING!
	 * Calling resetGlobalServiceLocator() may leave the application in an inconsistent
	 * state. Calling this is only safe under the ASSUMPTION that NO REFERENCE to
	 * any of the services managed by MediaWikiServices exist. If any service objects
	 * managed by the old MediaWikiServices instance remain in use, they may INTERFERE
	 * with the operation of the services managed by the new MediaWikiServices.
	 * Operating with a mix of services created by the old and the new
	 * MediaWikiServices instance may lead to INCONSISTENCIES and even DATA LOSS!
	 * Any class implementing LAZY LOADING is especially prone to this problem,
	 * since instances would typically retain a reference to a storage layer service.
	 *
	 * Legitimate use cases for this method are limited to extreme cases like the
	 * installer resetting services once initial database credentials are provided by
	 * the user, or resetting services after pctl_fork to make sure each process is
	 * using a separate set of file handles and network connections.
	 *
	 * @param Config|null $bootstrapConfig The Config object to be registered as the
	 *        'BootstrapConfig' service. This has to contain at least the information
	 *        needed to set up the 'ConfigFactory' service. If not given, the bootstrap
	 *        config of the old instance of MediaWikiServices will be re-used. If there
	 *        was no previous instance, a new GlobalVarConfig object will be used to
	 *        boostrap the services.
	 *
	 * @throws \FatalError
	 * @throws \MWException
	 */
	public static function resetGlobalInstance( Config $bootstrapConfig = null ) {
		if ( self::$instance ) {
			if ( $bootstrapConfig === null ) {
				$bootstrapConfig = self::$instance->getBootstrapConfig();
			}

			self::$instance->destroy();
		}

		if ( $bootstrapConfig === null ) {
			// NOTE: constructing GlobalVarConfig here is not particularly pretty,
			// but some information from the global scope has to be injected here,
			// even if it's just a file name or database credentials to load
			// configuration from.
			$bootstrapConfig = new GlobalVarConfig();
		}

		self::$instance = new self( $bootstrapConfig );

		// Load the default wiring from the specified files.
		$wiringFiles = $bootstrapConfig->get( 'ServiceWiringFiles' );
		self::$instance->loadWiringFiles( $wiringFiles );

		// Provide a traditional hook point to allow extensions to configure services.
		Hooks::run( 'MediaWikiServices', array( self::$instance ) );
	}

	/**
	 * @param Config $config The Config object to be registered as the 'BootstrapConfig' service.
	 *        This has to contain at least the information needed to set up the 'ConfigFactory'
	 *        service.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		// register the given Config object as the bootstrap config service.
		$this->defineService( 'BootstrapConfig', function() use ( $config ) {
			return $config;
		} );
	}

	/**
	 * Returns the Config object containing the bootstrap configuration.
	 * Bootstrap configuration would typically include database credentials
	 * and other information that may be needed before the ConfigFactory
	 * service can be instantiated.
	 *
	 * @note This should only be used during bootstrapping, in particular
	 * when creating the MainConfig service. Application logic should
	 * use getMainConfig() to get a Config instances.
	 *
	 * @return Config
	 */
	public function getBootstrapConfig() {
		return $this->getService( 'BootstrapConfig' );
	}

	//////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * @return ConfigFactory
	 */
	public function getConfigFactory() {
		return $this->getService( 'ConfigFactory' );
	}

	/**
	 * Returns the Config object that provides configuration for MediaWiki core.
	 * This may or may not be the same object that is returned by getBootstrapConfig().
	 *
	 * @return Config
	 */
	public function getMainConfig() {
		return $this->getService( 'MainConfig' );
	}

	/**
	 * @return SiteLookup
	 */
	public function getSiteLookup() {
		return $this->getService( 'SiteLookup' );
	}

	/**
	 * @return SiteStore
	 */
	public function getSiteStore() {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @return LBFactory
	 */
	public function getDBLoadBalancerFactory() {
		return $this->getService( 'DBLoadBalancerFactory' );
	}

	/**
	 * @return LoadBalancer The main DB load balancer for the local wiki.
	 */
	public function getDBLoadBalancer() {
		return $this->getService( 'DBLoadBalancer' );
	}
	/**
	 * @return ObjectCacheManager
	 */
	public function getObjectCacheManager() {
		return $this->getService( 'ObjectCacheManager' );
	}

	/**
	 * @return Profiler
	 */
	public function getProfiler() {
		return $this->getService( 'Profiler' );
	}

	/**
	 * @return \MediaWiki\Logger\Spi
	 */
	public function getLoggerFactory() {
		return $this->getService( 'LoggerFactory' );
	}

	/**
	 * @return FileBackendGroup
	 */
	public function getFileBackendGroup() {
		return $this->getService( 'FileBackendGroup' );
	}

	!!!FIX:
	/*
	LockManagerGroup::destroySingletons();
	JobQueueGroup::destroySingletons();
	RedisConnectionPool::destroySingletons();
	$wgMemc = null;
	*/

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service getter here, don't forget to add a test
	// case for it in MediaWikiServicesTest::provideGetters() and in
	// MediaWikiServicesTest::provideGetService()!
	///////////////////////////////////////////////////////////////////////////

}
