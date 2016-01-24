<?php
namespace MediaWiki;

use ChronologyProtector;
use Config;
use ConfigFactory;
use GlobalVarConfig;
use Hooks;
use LBFactory;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LoadBalancer;
use LoadMonitor;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Services\ServiceContainer;
use Profiler;
use RequestContext;
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
		static $instance = null;

		if ( $instance === null ) {
			// NOTE: constructing GlobalVarConfig here is not particularly pretty,
			// but some information from the global scope has to be injected here,
			// even if it's just a file name or database credentials to load
			// configuration from.
			$config = new GlobalVarConfig();
			$instance = new self( $config );

			// Load the default wiring from the specified files.
			$wiringFiles = $config->get( 'ServiceWiringFiles' );
			$instance->loadWiringFiles( $wiringFiles );

			// Provide a traditional hook point to allow extensions to configure services.
			Hooks::run( 'MediaWikiServices', array( $instance ) );
		}

		return $instance;
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
	 * @return ServiceContainer A container for LBFactory instances of different kinds.
	 */
	public function getDBLoadBalancerFactoryContainer() {
		return $this->getService( 'DBLoadBalancerFactoryContainer' );
	}

	/**
	 * @note Since RequestContext suffers from the "kitchen sink syndrome", application logic
	 * should avoid using RequestContext and rely on more specific services instead.
	 *
	 * @return RequestContext
	 */
	public function getRequestContext() {
		return $this->getService( 'RequestContext' );
	}

	/**
	 * @return ObjectCacheManager
	 */
	public function getObjectCacheManager() {
		return $this->getService( 'ObjectCacheManager' );
	}

	/**
	 * @return ChronologyProtector
	 */
	public function getChronologyProtector() {
		return $this->getService( 'ChronologyProtector' );
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
	 * @return StatsdDataFactoryInterface
	 */
	public function getStatsdDataFactory() {
		return $this->getService( 'StatsDataFactory' );
	}

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service getter here, don't forget to add a test
	// case for it in MediaWikiServicesTest::provideGetters() and in
	// MediaWikiServicesTest::provideGetService()!
	///////////////////////////////////////////////////////////////////////////

}
