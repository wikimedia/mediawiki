<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LBFactory;

class MediaWikiTestEnvironment {

	/**
	 * @var array
	 */
	private $supportedDBs = [
		'mysql',
		'sqlite',
		'postgres',
		'oracle'
	];

	/**
	 * @var bool
	 */
	private $useTemporaryTables;

	/**
	 * @var bool
	 */
	private $reuseDB;

	/**
	 * Table name prefixes. Oracle likes it shorter.
	 */
	const DB_PREFIX = 'unittest_';
	const ORA_DB_PREFIX = 'ut_';

	/**
	 * @param bool $useTemporaryTables
	 * @param bool $reuseDB
	 */
	public function __construct( $useTemporaryTables = true, $reuseDB = false ) {
		$this->useTemporaryTables = $useTemporaryTables;
		$this->reuseDB = $reuseDB;
	}

	/**
	 * Stashes the global instance of MediaWikiServices, and installs a new one,
	 * allowing test cases to override settings and services.
	 * The previous instance of MediaWikiServices will be restored on tearDown.
	 *
	 * @param Config $configOverrides Configuration overrides for the new MediaWikiServices instance.
	 * @param callable[] $services An associative array of services to re-define. Keys are service
	 *        names, values are callables.
	 *
	 * @return MediaWikiServices
	 * @throws MWException
	 */
	public function overrideMwServices( Config $configOverrides = null, array $services = [] ) {
		if ( !$configOverrides ) {
			$configOverrides = new HashConfig();
		}

		$oldInstance = MediaWikiServices::getInstance();
		$oldLBFactory = $oldInstance->getDBLoadBalancerFactory();
		$oldConfigFactory = $oldInstance->getConfigFactory();

		$testConfig = self::makeTestConfig( null, $configOverrides );
		$newInstance = new MediaWikiServices( $testConfig );

		// Load the default wiring from the specified files.
		// NOTE: this logic mirrors the logic in MediaWikiServices::newInstance.
		$wiringFiles = $testConfig->get( 'ServiceWiringFiles' );
		$newInstance->loadWiringFiles( $wiringFiles );

		// Provide a traditional hook point to allow extensions to configure services.
		Hooks::run( 'MediaWikiServices', [ $newInstance ] );

		foreach ( $services as $name => $callback ) {
			$newInstance->redefineService( $name, $callback );
		}

		self::installTestServices(
			$oldLBFactory,
			$oldConfigFactory,
			$newInstance
		);
		MediaWikiServices::forceGlobalInstance( $newInstance );

		return $newInstance;
	}

	/**
	 * Resets some well known services that typically have state that may interfere with unit tests.
	 * This is a lightweight alternative to resetGlobalServices().
	 *
	 * @note There is no guarantee that no references remain to stale service instances destroyed
	 * by a call to doLightweightServiceReset().
	 *
	 * @throws MWException if called outside of PHPUnit tests.
	 *
	 * @see resetGlobalServices()
	 */
	public function doLightweightServiceReset() {
		global $wgRequest;

		JobQueueGroup::destroySingletons();
		ObjectCache::clear();
		$services = MediaWikiServices::getInstance();
		$services->resetServiceForTesting( 'MainObjectStash' );
		$services->resetServiceForTesting( 'LocalServerObjectCache' );
		$services->getMainWANObjectCache()->clearProcessCache();
		FileBackendGroup::destroySingleton();

		// TODO: move global state into MediaWikiServices
		RequestContext::resetMain();
		if ( session_id() !== '' ) {
			session_write_close();
			session_id( '' );
		}

		$wgRequest = new FauxRequest();
		MediaWiki\Session\SessionManager::resetCache();
	}

	/**
	 * Reset global services, and install testing environment.
	 * This is the testing equivalent of MediaWikiServices::resetGlobalInstance().
	 * This should only be used to set up the testing environment, not when
	 * running unit tests. Use MediaWikiTestCase::overrideMwServices() for that.
	 *
	 * @see MediaWikiServices::resetGlobalInstance()
	 * @see prepareServices()
	 * @see MediaWikiTestCase::overrideMwServices()
	 *
	 * @param Config|null $bootstrapConfig The bootstrap config to use with the new
	 *        MediaWikiServices
	 *
	 * @return MediaWikiServices
	 */
	public static function resetGlobalServices( Config $bootstrapConfig = null ) {
		$oldServices = MediaWikiServices::getInstance();
		$oldLBFactory = $oldServices->getDBLoadBalancerFactory();
		$oldConfigFactory = $oldServices->getConfigFactory();

		// Detach $oldLBFactory, so it doesn't get destroyed when $oldServices is
		// destroyed by resetGlobalInstance() below.
		$oldServices->resetServiceForTesting( 'DBLoadBalancerFactory', false );

		$testConfig = self::makeTestConfig( $bootstrapConfig );

		MediaWikiServices::resetGlobalInstance( $testConfig );

		$serviceLocator = MediaWikiServices::getInstance();
		self::installTestServices(
			$oldLBFactory,
			$oldConfigFactory,
			$serviceLocator
		);
		return $serviceLocator;
	}

	/**
	 * @param LBFactory $oldLBFactory LBFactory to re-use if possible.
	 *        NOTE: If not re-used, $oldLBFactory->destroy() will be called!
	 * @param ConfigFactory $oldConfigFactory
	 * @param MediaWikiServices $newServices
	 *
	 * @throws MWException
	 */
	private static function installTestServices(
		LBFactory $oldLBFactory,
		ConfigFactory $oldConfigFactory,
		MediaWikiServices $newServices
	) {
		// Re-use the old CloakingLBFactory if possible.
		if (
			$oldLBFactory instanceof CloakingLBFactory
			&& !$oldLBFactory->getMainLB()->isDisabled()
		) {
			$cloakingLBFactory = $oldLBFactory;
		} else {
			// XXX: If $oldLBFactory was cloaked but disabled,
			//      we should re-cloak and re-inject data.
			//      Or at least we should warn.

			$mainConfig = $newServices->getMainConfig();
			$lbFactoryConf = MWLBFactory::applyDefaultConfig(
				$mainConfig->get( 'LBFactoryConf' ),
				$mainConfig,
				$newServices->getConfiguredReadOnlyMode()
			);

			$cloakingLBFactory = new CloakingLBFactory( $lbFactoryConf );

			$oldLBFactory->destroy();
		}

		// Keep using the same CloakingLBFactory instance.
		$newServices->redefineService(
			'DBLoadBalancerFactory',
			function( MediaWikiServices $services ) use ( $cloakingLBFactory ) {
				return $cloakingLBFactory;
			}
		);

		// Use bootstrap config for all configuration.
		// This allows config overrides via global variables to take effect.
		$bootstrapConfig = $newServices->getBootstrapConfig();
		$newServices->resetServiceForTesting( 'ConfigFactory' );
		$newServices->redefineService(
			'ConfigFactory',
			self::makeTestConfigFactoryInstantiator(
				$oldConfigFactory,
				[ 'main' =>  $bootstrapConfig ]
			)
		);
	}

	/**
	 * @param ConfigFactory $oldFactory
	 * @param Config[] $configurations
	 *
	 * @return Closure
	 */
	private static function makeTestConfigFactoryInstantiator(
		ConfigFactory $oldFactory,
		array $configurations
	) {
		return function( MediaWikiServices $services ) use ( $oldFactory, $configurations ) {
			$factory = new ConfigFactory();

			// clone configurations from $oldFactory that are not overwritten by $configurations
			$namesToClone = array_diff(
				$oldFactory->getConfigNames(),
				array_keys( $configurations )
			);

			foreach ( $namesToClone as $name ) {
				$factory->register( $name, $oldFactory->makeConfig( $name ) );
			}

			foreach ( $configurations as $name => $config ) {
				$factory->register( $name, $config );
			}

			return $factory;
		};
	}

	/**
	 * Create a config suitable for testing, based on a base config, default overrides,
	 * and custom overrides.
	 *
	 * @param Config|null $baseConfig
	 * @param Config|null $customOverrides
	 *
	 * @return Config
	 */
	private static function makeTestConfig(
		Config $baseConfig = null,
		Config $customOverrides = null
	) {
		$defaultOverrides = new HashConfig();

		if ( !$baseConfig ) {
			$baseConfig = MediaWikiServices::getInstance()->getBootstrapConfig();
		}

		/* Some functions require some kind of caching, and will end up using the db,
		 * which we can't allow, as that would open a new connection for mysql.
		 * Replace with a HashBag. They would not be going to persist anyway.
		 */
		$hashCache = [ 'class' => 'HashBagOStuff', 'reportDupes' => false ];
		$objectCaches = [
				CACHE_DB => $hashCache,
				CACHE_ACCEL => $hashCache,
				CACHE_MEMCACHED => $hashCache,
				'apc' => $hashCache,
				'apcu' => $hashCache,
				'xcache' => $hashCache,
				'wincache' => $hashCache,
			] + $baseConfig->get( 'ObjectCaches' );

		$defaultOverrides->set( 'ObjectCaches', $objectCaches );
		$defaultOverrides->set( 'MainCacheType', CACHE_NONE );
		$defaultOverrides->set( 'JobTypeConf', [ 'default' => [ 'class' => 'JobQueueMemory' ] ] );

		// Use a fast hash algorithm to hash passwords.
		$defaultOverrides->set( 'PasswordDefault', 'A' );

		$testConfig = $customOverrides
			? new MultiConfig( [ $customOverrides, $defaultOverrides, $baseConfig ] )
			: new MultiConfig( [ $defaultOverrides, $baseConfig ] );

		return $testConfig;
	}

	/**
	 * @throws MWException
	 * @since 1.18
	 */
	protected function checkDbIsSupported() {
		$db = wfGetDB( DB_MASTER );
		if ( !in_array( $db->getType(), $this->supportedDBs ) ) {
			throw new MWException( $db->getType() . " is not currently supported for unit testing." );
		}
	}

	/**
	 * @return string
	 * @since 1.18
	 */
	public function dbPrefix() {
		$db = wfGetDB( DB_MASTER );
		return $db->getType() == 'oracle' ? self::ORA_DB_PREFIX : self::DB_PREFIX;
	}

	/**
	 * Restores MediaWiki to using the table set (table prefix) it was using before
	 * setupTestDB() was called. Useful if we need to perform database operations
	 * after the test run has finished (such as saving logs or profiling info).
	 *
	 * @since 1.21
	 */
	public static function teardownTestDB() {
		global $wgJobClasses;

		foreach ( $wgJobClasses as $type => $class ) {
			// Delete any jobs under the clone DB (or old prefix in other stores)
			JobQueueGroup::singleton()->get( $type )->delete();
		}

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		if ( $lbFactory instanceof CloakingLBFactory && $lbFactory->isCloaked() ) {
			$lbFactory->uncloakDatabase();
		}
	}

	/**
	 * Creates an empty skeleton of the wiki database by cloning its structure
	 * to equivalent tables using the given $prefix. Then sets MediaWiki to
	 * use the new set of tables (aka schema) instead of the original set.
	 *
	 * This is used to generate a dummy table set, typically consisting of temporary
	 * tables, that will be used by tests instead of the original wiki database tables.
	 *
	 * @see CloakingLBFactory::cloakDatabase()
	 *
	 * @throws MWException If the database table prefix is already $prefix
	 */
	public function setupTestDB() {
		$this->checkDbIsSupported();

		$services = MediaWikiServices::getInstance();
		$lbFactory = $services->getDBLoadBalancerFactory();

		if ( !( $lbFactory instanceof CloakingLBFactory ) ) {
			throw new MWException( 'Corrupt test environment: '
				. 'The global LBFactory instance is not a CloakingLBFactory' );
		}

		// TODO: the below should be re-written as soon as LBFactory, LoadBalancer,
		// and Database no longer use global state.

		if ( $lbFactory->isCloaked() ) {
			// nothing to do
			$this->db = wfGetDB( DB_MASTER );
			return;
		}

		$lbFactory->cloakDatabase( [
			'testDbPrefix' => $this->dbPrefix(),
			'useTemporaryTables' => $this->useTemporaryTables,
			'reuseDB' => $this->reuseDB,
		] );
	}

}
