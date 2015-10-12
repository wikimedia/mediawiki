<?php
namespace MediaWiki;

use ApiQueryInfo;
use CentralIdLookup;
use Config;
use ConfigFactory;
use DeferredUpdates;
use FileBackendGroup;
use GlobalVarConfig;
use Hooks;
use IP;
use JobQueueAggregator;
use Language;
use LBFactory;
use LinkCache;
use LoadBalancer;
use LockManagerGroup;
use MagicWord;
use MediaHandler;
use MediaWiki\Services\ServiceContainer;
use MessageCache;
use MWException;
use MWNamespace;
use MWTidy;
use ObjectCache;
use RedisConnectionPool;
use RepoGroup;
use RequestContext;
use ResourceLoader;
use SiteLookup;
use SiteStore;
use SpecialPageFactory;
use Title;
use User;

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
	 * Replaces the global MediaWikiServices instance.
	 *
	 * @note This is for use in PHPUnit tests only!
	 *
	 * @throws MWException if called outside of PHPUnit tests.
	 *
	 * @param MediaWikiServices $services The new MediaWikiServices object.
	 *
	 * @return MediaWikiServices The old MediaWikiServices object, so it can be restored later.
	 */
	public static function forceGlobalInstance( MediaWikiServices $services ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'setGlobalInstance() must not be used outside unit tests.' );
		}

		$old = self::getInstance();
		self::$instance = $services;

		self::resetLegacyServices();

		return $old;
	}

	/**
	 * Creates a new instance of MediaWikiServices and sets it as the global default
	 * instance. getInstance() will return a different MediaWikiServices object
	 * after every call to resetGlobalServiceLocator().
	 *
	 * @warning This should not be used during normal operation. It is intended for use
	 * when the configuration has changed significantly since bootstrap time, e.g.
	 * during the installation process or during testing.
	 *
	 * @warning Calling resetGlobalServiceLocator() may leave the application in an inconsistent
	 * state. Calling this is only safe under the ASSUMPTION that NO REFERENCE to
	 * any of the services managed by MediaWikiServices exist. If any service objects
	 * managed by the old MediaWikiServices instance remain in use, they may INTERFERE
	 * with the operation of the services managed by the new MediaWikiServices.
	 * Operating with a mix of services created by the old and the new
	 * MediaWikiServices instance may lead to INCONSISTENCIES and even DATA LOSS!
	 * Any class implementing LAZY LOADING is especially prone to this problem,
	 * since instances would typically retain a reference to a storage layer service.
	 *
	 * @see forceGlobalInstance()
	 * @see resetGlobalInstance()
	 * @see resetBetweenTest()
	 *
	 * @param Config|null $bootstrapConfig The Config object to be registered as the
	 *        'BootstrapConfig' service. This has to contain at least the information
	 *        needed to set up the 'ConfigFactory' service. If not given, the bootstrap
	 *        config of the old instance of MediaWikiServices will be re-used. If there
	 *        was no previous instance, a new GlobalVarConfig object will be used to
	 *        bootstrap the services.
	 *
	 * @throws MWException
	 */
	public static function resetGlobalInstance( Config $bootstrapConfig = null ) {
		if ( self::$instance ) {
			if ( $bootstrapConfig === null ) {
				$bootstrapConfig = self::$instance->getBootstrapConfig();
			}

			self::$instance->destroy();
			$first = false;
		} else {
			$first = true;
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

		if ( !$first ) {
			self::resetLegacyServices();
		}
	}

	/**
	 * Resets global instances of services that have not yet been ported to using
	 * MediaWikiServices to manage their default instance.
	 *
	 * @note eventually, all global service instances are to be managed by MediaWikiServices.
	 * To emulate the effect of resetting the global service locator, we reset the individual
	 * static singletons for now.
	 *
	 * @note As long as we don't know the interdependencies between the services, the only way
	 * to reset services consistently is to reset all services at once. This should be ok since
	 * there should rarely be a need to reset all processes.
	 */
	private static function resetLegacyServices() {
		global $wgContLang, $wgUser, $wgMemc;

		$services = self::getInstance();
		$config = $services->getMainConfig();

		// NOTE: all the services instance that get reset below should be migrated
		// to be managed by MediaWikiServices. Eventually, this method can then be
		// removed.

		User::resetIdByNameCache();
		LinkCache::singleton()->clear();
		Title::clearCaches();

		MWTidy::destroySingleton();
		MagicWord::clearCache();
		SpecialPageFactory::resetList();
		JobQueueAggregator::destroySingleton();
		DeferredUpdates::clearPendingUpdates();
		CentralIdLookup::resetCache();
		MediaHandler::resetCache();
		IP::clearCaches();
		ResourceLoader::clearCache();

		ApiQueryInfo::resetTokenCache();

		RepoGroup::destroySingleton();

		MessageCache::destroyInstance();

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		Language::$mLangObjCache = array();
		Language::getLocalisationCache()->unloadAll();

		ObjectCache::clear();
		RedisConnectionPool::destroySingletons();
		FileBackendGroup::destroySingleton();
		LockManagerGroup::destroySingletons();

		RequestContext::resetMain();

		$wgContLang = Language::factory( $config->get( 'LanguageCode' ) );
		$wgContLang->resetNamespaces(); # reset namespace cache

		$wgMemc = ObjectCache::getLocalClusterInstance();
		$wgUser = RequestContext::getMain()->getUser();
	}

	/**
	 * Disables all storage layer services. After calling this, any attempt to access the
	 * storage layer will result in an error. Use resetGlobalInstance() to restore normal
	 * operation.
	 *
	 * @warning This is intended for extreme situations only and should never be used
	 * while serving normal web requests. Legitimate use cases for this method include
	 * the installation process. Test fixtures may also use this, if the fixture relies
	 * on globalState.
	 *
	 * @see resetGlobalInstance()
	 * @see resetChildProcessServices()
	 */
	public static function disableStorageBackend() {
		// TODO: also disable some Caches, JobQueues, etc
		$destroy = array( 'DBLoadBalancer', 'DBLoadBalancerFactory' );
		$services = self::getInstance();

		foreach ( $destroy as $name ) {
			$services->disableService( $name );
		}
	}

	/**
	 * Resets any services that may have become stale after a child process
	 * returns from after pcntl_fork(). It's also safe, but generally unnecessary,
	 * to call this method from the pafent process.
	 *
	 * @note This is intended for use in the context of process forking only!
	 *
	 * @see resetGlobalInstance()
	 * @see disableStorageBackend()
	 */
	public static function resetChildProcessServices() {
		// NOTE: for now, just reset everything. Since we don't know the interdependencies
		// between services, we can't do this more selectively at this time.
		self::resetGlobalInstance();

		// Child, reseed because there is no bug in PHP:
		// http://bugs.php.net/bug.php?id=42465
		mt_srand( getmypid() );
	}

	/**
	 * Resets any services that has state that may interfere with unit tests.
	 * Eventually, all unit tests should be fully isolated. Until then, this
	 * method can be used to reset state between tests.
	 *
	 * @note This is not a full reset of all services! It is intended as a lightweight
	 * alternative to resetGlobalInstance() and forceGlobalInstance() for use between
	 * test runs.
	 *
	 * @throws MWException if called outside of PHPUnit tests.
	 *
	 * @see resetGlobalInstance()
	 * @see forceGlobalInstance()
	 * @see disableStorageBackend()
	 */
	public static function resetBetweenTest() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException( 'resetBetweenTest() must not be used outside unit tests.' );
		}

		ObjectCache::clear();
	}

	/**
	 * @param Config $config The Config object to be registered as the 'BootstrapConfig' service.
	 *        This has to contain at least the information needed to set up the 'ConfigFactory'
	 *        service.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		// Register the given Config object as the bootstrap config service.
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
	 * @return LoadBalancer
	 */
	public function getDBLoadBalancer() {
		return $this->getService( 'DBLoadBalancer' );
	}

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service getter here, don't forget to add a test
	// case for it in MediaWikiServicesTest::provideGetters() and in
	// MediaWikiServicesTest::provideGetService()!
	///////////////////////////////////////////////////////////////////////////

}
