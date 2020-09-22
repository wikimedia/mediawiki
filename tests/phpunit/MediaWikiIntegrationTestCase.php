<?php
// phpcs:disable MediaWiki.Commenting.FunctionAnnotations.UnrecognizedAnnotation

use MediaWiki\Logger\LegacyLogger;
use MediaWiki\Logger\LegacySpi;
use MediaWiki\Logger\LogCapturingSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestResult;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SebastianBergmann\Comparator\ComparisonFailure;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @since 1.18
 *
 * Extend this class if you are testing classes which access global variables, methods, services
 * or a storage backend.
 *
 * Consider using MediaWikiUnitTestCase and mocking dependencies if your code uses dependency
 * injection and does not access any globals.
 *
 * @stable for subclassing
 */
abstract class MediaWikiIntegrationTestCase extends PHPUnit\Framework\TestCase {
	use MediaWikiCoversValidator;
	use MediaWikiGroupValidator;
	use MediaWikiTestCaseTrait;

	/**
	 * The original service locator. This is overridden during setUp().
	 *
	 * @var MediaWikiServices|null
	 */
	private static $originalServices;

	/**
	 * Cached service wirings of the original service locator, to work around T247990
	 * @var callable[]
	 */
	private static $originalServiceWirings = [];

	/**
	 * The local service locator, created during setUp().
	 * @var MediaWikiServices
	 */
	private $localServices;

	/**
	 * @var TestUser[]
	 * @since 1.20
	 */
	public static $users;

	/**
	 * Primary database
	 *
	 * @var Database
	 * @since 1.18
	 */
	protected $db;

	/**
	 * @var array
	 * @since 1.19
	 */
	protected $tablesUsed = []; // tables with data

	private static $useTemporaryTables = true;
	private static $reuseDB = false;
	private static $dbSetup = false;
	private static $oldTablePrefix = '';

	/**
	 * Holds the paths of temporary files/directories created through getNewTempFile,
	 * and getNewTempDirectory
	 *
	 * @var array
	 */
	private $tmpFiles = [];

	/**
	 * Holds original values of MediaWiki configuration settings
	 * to be restored in tearDown().
	 * See also setMwGlobals().
	 * @var array
	 */
	private $mwGlobals = [];

	/**
	 * Holds list of MediaWiki configuration settings to be unset in tearDown().
	 * See also setMwGlobals().
	 * @var array
	 */
	private $mwGlobalsToUnset = [];

	/**
	 * Holds original values of ini settings to be restored
	 * in tearDown().
	 * @see setIniSettings()
	 * @var array
	 */
	private $iniSettings = [];

	/**
	 * Holds original loggers which have been replaced by setLogger()
	 * @var LoggerInterface[]
	 */
	private $loggers = [];

	/**
	 * Holds original loggers which have been ignored by setNullLogger()
	 * @var array<array<LegacyLogger|int>>
	 */
	private $ignoredLoggers = [];

	/**
	 * Holds a list of services that were overridden with setService().  Used for printing an error
	 * if overrideMwServices() overrides a service that was previously set.
	 * @var string[]
	 */
	private $overriddenServices = [];

	/**
	 * @var array[] contains temporary hooks as a list of name/handler pairs,
	 *      where a name/false pair indicates the hook being cleared.
	 */
	private $temporaryHookHandlers = [];

	/**
	 * Table name prefix.
	 */
	public const DB_PREFIX = 'unittest_';

	/**
	 * @var array
	 * @since 1.18
	 */
	protected $supportedDBs = [
		'mysql',
		'sqlite',
		'postgres',
	];

	/**
	 * @stable for calling
	 */
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->backupGlobals = false;
		$this->backupStaticAttributes = false;
	}

	private static function initializeForStandardPhpunitEntrypointIfNeeded() {
		if ( function_exists( 'wfRequireOnceInGlobalScope' ) ) {
			$IP = realpath( __DIR__ . '/../..' );
			wfRequireOnceInGlobalScope( "$IP/includes/Defines.php" );
			wfRequireOnceInGlobalScope( "$IP/includes/DefaultSettings.php" );
			wfRequireOnceInGlobalScope( "$IP/includes/GlobalFunctions.php" );
			wfRequireOnceInGlobalScope( "$IP/includes/Setup.php" );
			wfRequireOnceInGlobalScope( "$IP/tests/common/TestsAutoLoader.php" );
			TestSetup::applyInitialConfig();
		}
	}

	/**
	 * @stable for overriding
	 */
	public static function setUpBeforeClass() : void {
		global $IP;
		parent::setUpBeforeClass();
		if ( !file_exists( "$IP/LocalSettings.php" ) ) {
				echo "File \"$IP/LocalSettings.php\" could not be found. "
				. "Test case " . static::class . " extends " . self::class . " "
				. "which requires a working MediaWiki installation.\n"
				. ( new RuntimeException() )->getTraceAsString();
			die();
		}
		self::initializeForStandardPhpunitEntrypointIfNeeded();

		// Get the original service locator
		if ( !self::$originalServices ) {
			self::$originalServices = MediaWikiServices::getInstance();
		}
	}

	/**
	 * Convenience method for getting an immutable test user
	 *
	 * @since 1.28
	 *
	 * @param string|string[] $groups Groups the test user should be in.
	 * @return TestUser
	 */
	public static function getTestUser( $groups = [] ) {
		return TestUserRegistry::getImmutableTestUser( $groups );
	}

	/**
	 * Convenience method for getting a mutable test user
	 *
	 * @since 1.28
	 *
	 * @param string|string[] $groups Groups the test user should be added in.
	 * @return TestUser
	 */
	public static function getMutableTestUser( $groups = [] ) {
		return TestUserRegistry::getMutableTestUser( __CLASS__, $groups );
	}

	/**
	 * Convenience method for getting an immutable admin test user
	 *
	 * @since 1.28
	 *
	 * @return TestUser
	 */
	public static function getTestSysop() {
		return self::getTestUser( [ 'sysop', 'bureaucrat' ] );
	}

	/**
	 * Returns a WikiPage representing an existing page.
	 *
	 * @since 1.32
	 *
	 * @param Title|string|null $title
	 * @return WikiPage
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function getExistingTestPage( $title = null ) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing with pages, the test cases\'s needsDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$title = ( $title === null ) ? 'UTPage' : $title;
		$title = is_string( $title ) ? Title::newFromText( $title ) : $title;
		$page = WikiPage::factory( $title );

		if ( !$page->exists() ) {
			$user = self::getTestSysop()->getUser();
			$page->doEditContent(
				ContentHandler::makeContent( 'UTContent', $title ),
				'UTPageSummary',
				EDIT_NEW | EDIT_SUPPRESS_RC,
				false,
				$user
			);
		}

		return $page;
	}

	/**
	 * Returns a WikiPage representing a non-existing page.
	 *
	 * @since 1.32
	 *
	 * @param Title|string|null $title
	 * @return WikiPage
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function getNonexistingTestPage( $title = null ) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing with pages, the test cases\'s needsDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$title = ( $title === null ) ? 'UTPage-' . rand( 0, 100000 ) : $title;
		$title = is_string( $title ) ? Title::newFromText( $title ) : $title;
		$page = WikiPage::factory( $title );

		if ( $page->exists() ) {
			$page->doDeleteArticleReal( 'Testing', $this->getTestSysop()->getUser() );
		}

		return $page;
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
			$baseConfig = self::$originalServices->getBootstrapConfig();
		}

		/* Some functions require some kind of caching, and will end up using the db,
		 * which we can't allow, as that would open a new connection for mysql.
		 * Replace with a HashBag. They would not be going to persist anyway.
		 */
		$hashCache = [ 'class' => HashBagOStuff::class, 'reportDupes' => false ];
		$objectCaches = [
				CACHE_DB => $hashCache,
				CACHE_ACCEL => $hashCache,
				CACHE_MEMCACHED => $hashCache,
				'apc' => $hashCache,
				'apcu' => $hashCache,
				'wincache' => $hashCache,
			] + $baseConfig->get( 'ObjectCaches' );

		$defaultOverrides->set( 'ObjectCaches', $objectCaches );
		$defaultOverrides->set( 'MainCacheType', CACHE_NONE );
		$defaultOverrides->set( 'JobTypeConf', [ 'default' => [ 'class' => JobQueueMemory::class ] ] );

		// Use a fast hash algorithm to hash passwords.
		$defaultOverrides->set( 'PasswordDefault', 'A' );

		$testConfig = $customOverrides
			? new MultiConfig( [ $customOverrides, $defaultOverrides, $baseConfig ] )
			: new MultiConfig( [ $defaultOverrides, $baseConfig ] );

		return $testConfig;
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
		return function ( MediaWikiServices $services ) use ( $oldFactory, $configurations ) {
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
	 * Resets some non-service singleton instances and other static caches. It's not necessary to
	 * reset services here.
	 */
	public static function resetNonServiceCaches() {
		global $wgRequest, $wgJobClasses;

		foreach ( $wgJobClasses as $type => $class ) {
			JobQueueGroup::singleton()->get( $type )->delete();
		}
		JobQueueGroup::destroySingletons();

		ObjectCache::clear();
		DeferredUpdates::clearPendingUpdates();

		// TODO: move global state into MediaWikiServices
		RequestContext::resetMain();
		if ( session_id() !== '' ) {
			session_write_close();
			session_id( '' );
		}

		$wgRequest = new FauxRequest();
		MediaWiki\Session\SessionManager::resetCache();
	}

	public function run( TestResult $result = null ) : TestResult {
		$this->overrideMwServices();

		if ( $this->needsDB() && !$this->isTestInDatabaseGroup() ) {
			throw new Exception(
				get_class( $this ) . ' apparently needsDB but is not in the Database group'
			);
		}

		$needsResetDB = false;
		if ( !self::$dbSetup || $this->needsDB() ) {
			// set up a DB connection for this test to use

			self::$useTemporaryTables = !$this->getCliArg( 'use-normal-tables' );
			self::$reuseDB = $this->getCliArg( 'reuse-db' );

			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$this->db = $lb->getConnection( DB_MASTER );

			$this->checkDbIsSupported();

			if ( !self::$dbSetup ) {
				$this->setupAllTestDBs();
				$this->addCoreDBData();
			}

			// TODO: the DB setup should be done in setUpBeforeClass(), so the test DB
			// is available in subclass's setUpBeforeClass() and setUp() methods.
			// This would also remove the need for the HACK that is oncePerClass().
			if ( $this->oncePerClass() ) {
				$this->setUpSchema( $this->db );
				$this->resetDB( $this->db, $this->tablesUsed );
				$this->addDBDataOnce();
			}

			$this->addDBData();
			$needsResetDB = true;
		}

		parent::run( $result );

		// We don't mind if we override already-overridden services during cleanup
		$this->overriddenServices = [];
		$this->temporaryHookHandlers = [];

		if ( $needsResetDB ) {
			$this->resetDB( $this->db, $this->tablesUsed );
		}

		self::restoreMwServices();
		$this->localServices = null;
		return $result;
	}

	/**
	 * @return bool
	 */
	private function oncePerClass() {
		// Remember current test class in the database connection,
		// so we know when we need to run addData.

		$class = static::class;

		$first = !isset( $this->db->_hasDataForTestClass )
			|| $this->db->_hasDataForTestClass !== $class;

		$this->db->_hasDataForTestClass = $class;
		return $first;
	}

	/**
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function usesTemporaryTables() {
		return self::$useTemporaryTables;
	}

	/**
	 * Obtains a new temporary file name
	 *
	 * The obtained filename is enlisted to be removed upon tearDown
	 *
	 * @since 1.20
	 *
	 * @return string Absolute name of the temporary file
	 */
	protected function getNewTempFile() {
		$fileName = tempnam(
			wfTempDir(),
			// Avoid backslashes here as they result in inconsistent results
			// between Windows and other OS, as well as between functions
			// that try to normalise these in one or both directions.
			// For example, tempnam rejects directory separators in the prefix which
			// means it rejects any namespaced class on Windows.
			// And then there is, wfMkdirParents which normalises paths always
			// whereas most other PHP and MW functions do not.
			'MW_PHPUnit_' . strtr( static::class, [ '\\' => '_' ] ) . '_'
		);
		$this->tmpFiles[] = $fileName;

		return $fileName;
	}

	/**
	 * obtains a new temporary directory
	 *
	 * The obtained directory is enlisted to be removed (recursively with all its contained
	 * files) upon tearDown.
	 *
	 * @since 1.20
	 *
	 * @return string Absolute name of the temporary directory
	 */
	protected function getNewTempDirectory() {
		// Starting of with a temporary *file*.
		$fileName = $this->getNewTempFile();

		// Converting the temporary file to a *directory*.
		// The following is not atomic, but at least we now have a single place,
		// where temporary directory creation is bundled and can be improved.
		unlink( $fileName );
		// If this fails for some reason, PHP will warn and fail the test.
		mkdir( $fileName, 0777, /* recursive = */ true );

		return $fileName;
	}

	/**
	 * The annotation causes this to be called immediately before setUp()
	 * @before
	 */
	protected function mediaWikiSetUp() {
		$reflection = new ReflectionClass( $this );
		// TODO: Eventually we should assert for test presence in /integration/
		if ( strpos( $reflection->getFileName(), '/unit/' ) !== false ) {
			$this->fail( 'This integration test should not be in "tests/phpunit/unit" !' );
		}

		$this->overriddenServices = [];
		$this->temporaryHookHandlers = [];

		// Cleaning up temporary files
		foreach ( $this->tmpFiles as $fileName ) {
			if ( is_file( $fileName ) || ( is_link( $fileName ) ) ) {
				unlink( $fileName );
			} elseif ( is_dir( $fileName ) ) {
				wfRecursiveRemoveDir( $fileName );
			}
		}

		if ( $this->needsDB() && $this->db ) {
			// Clean up open transactions
			while ( $this->db->trxLevel() > 0 ) {
				$this->db->rollback( __METHOD__, 'flush' );
			}
			// Check for unsafe queries
			if ( $this->db->getType() === 'mysql' ) {
				$this->db->query( "SET sql_mode = 'STRICT_ALL_TABLES'", __METHOD__ );
			}
		}

		MWDebug::clearDeprecationFilters();

		// Reset all caches between tests.
		self::resetNonServiceCaches();

		// XXX: reset maintenance triggers
		// Hook into period lag checks which often happen in long-running scripts
		$lbFactory = $this->localServices->getDBLoadBalancerFactory();
		Maintenance::setLBFactoryTriggers( $lbFactory, $this->localServices->getMainConfig() );

		// T46192 Do not attempt to send a real e-mail
		$this->setTemporaryHook( 'AlternateUserMailer',
			function () {
				return false;
			}
		);
		ob_start( 'MediaWikiIntegrationTestCase::wfResetOutputBuffersBarrier' );
	}

	protected function addTmpFiles( $files ) {
		$this->tmpFiles = array_merge( $this->tmpFiles, (array)$files );
	}

	private static function formatErrorLevel( $errorLevel ) {
		switch ( gettype( $errorLevel ) ) {
			case 'integer':
				return '0x' . strtoupper( dechex( $errorLevel ) );
			case 'NULL':
				return 'null';
			default:
				throw new MWException( 'Unexpected error level type ' . gettype( $errorLevel ) );
		}
	}

	/**
	 * The annotation causes this to be called immediately after tearDown()
	 * @after
	 */
	protected function mediaWikiTearDown() {
		global $wgRequest, $wgSQLMode;

		$status = ob_get_status();
		if ( isset( $status['name'] ) &&
			$status['name'] === 'MediaWikiIntegrationTestCase::wfResetOutputBuffersBarrier'
		) {
			ob_end_flush();
		}

		// Cleaning up temporary files
		foreach ( $this->tmpFiles as $fileName ) {
			if ( is_file( $fileName ) || ( is_link( $fileName ) ) ) {
				unlink( $fileName );
			} elseif ( is_dir( $fileName ) ) {
				wfRecursiveRemoveDir( $fileName );
			}
		}

		if ( $this->needsDB() && $this->db ) {
			// Clean up open transactions
			while ( $this->db->trxLevel() > 0 ) {
				$this->db->rollback( __METHOD__, 'flush' );
			}
			if ( $this->db->getType() === 'mysql' ) {
				$this->db->query( "SET sql_mode = " . $this->db->addQuotes( $wgSQLMode ),
					__METHOD__ );
			}
		}

		// Clear any cached test users so they don't retain references to old services
		TestUserRegistry::clear();

		// Re-enable any disabled deprecation warnings
		MWDebug::clearLog();
		// Restore mw globals
		foreach ( $this->mwGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
		foreach ( $this->mwGlobalsToUnset as $value ) {
			unset( $GLOBALS[$value] );
		}
		foreach ( $this->iniSettings as $name => $value ) {
			ini_set( $name, $value );
		}
		$this->mwGlobals = [];
		$this->mwGlobalsToUnset = [];
		$this->restoreLoggers();

		// TODO: move global state into MediaWikiServices
		RequestContext::resetMain();
		if ( session_id() !== '' ) {
			session_write_close();
			session_id( '' );
		}
		$wgRequest = new FauxRequest();
		MediaWiki\Session\SessionManager::resetCache();
		MediaWiki\Auth\AuthManager::resetCache();

		// If anything faked the time, reset it
		ConvertibleTimestamp::setFakeTime( false );
	}

	/**
	 * Gets the service container to use with integration tests.
	 *
	 * @return MediaWikiServices
	 * @since 1.36
	 */
	protected function getServiceContainer() {
		if ( !$this->localServices ) {
			throw new Exception( __METHOD__ . ' must be called after MediaWikiIntegrationTestCase::run()' );
		}

		if ( $this->localServices !== MediaWikiServices::getInstance() ) {
			throw new Exception( __METHOD__ . ' may lead to inconsistencies because the '
				. ' global MediaWikiServices instance has been replaced by test code.' );
		}

		return $this->localServices;
	}

	/**
	 * Sets a service, maintaining a stashed version of the previous service to be
	 * restored in tearDown.
	 *
	 * @note This calls resetServices() in case any other services depend on the set service(s).
	 *
	 * @param string $name
	 * @param object $service The service instance, or a callable that returns the service instance.
	 *
	 * @since 1.27
	 *
	 */
	protected function setService( $name, $service ) {
		if ( !$this->localServices ) {
			throw new Exception( __METHOD__ . ' must be called after MediaWikiIntegrationTestCase::run()' );
		}

		if ( $this->localServices !== MediaWikiServices::getInstance() ) {
			throw new Exception( __METHOD__ . ' will not work because the global MediaWikiServices '
				. 'instance has been replaced by test code.' );
		}

		if ( is_callable( $service ) ) {
			$instantiator = $service;
		} else {
			$instantiator = function () use ( $service ) {
				return $service;
			};
		}

		$this->overriddenServices[] = $name;

		$this->localServices->disableService( $name );
		$this->localServices->redefineService(
			$name,
			$instantiator
		);

		$this->resetServices();
	}

	/**
	 * Sets a global, maintaining a stashed version of the previous global to be
	 * restored in tearDown
	 *
	 * The key is added to the array of globals that will be reset afterwards
	 * in the tearDown().
	 *
	 * @par Example
	 * @code
	 *     protected function setUp() : void {
	 *         parent::setUp();
	 *         $this->setMwGlobals( 'wgRestrictStuff', true );
	 *     }
	 *
	 *     function testFoo() {}
	 *
	 *     function testBar() {}
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *
	 *         $this->setMwGlobals( 'wgRestrictStuff', false );
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *     }
	 *
	 *     function testQuux() {}
	 * @endcode
	 *
	 * @param array|string $pairs Key to the global variable, or an array
	 *  of key/value pairs.
	 * @param mixed|null $value Value to set the global to (ignored
	 *  if an array is given as first argument).
	 *
	 * @note This will call resetServices().
	 *
	 * @since 1.21
	 */
	protected function setMwGlobals( $pairs, $value = null ) {
		if ( is_string( $pairs ) ) {
			$pairs = [ $pairs => $value ];
		}

		$this->doStashMwGlobals( array_keys( $pairs ) );

		foreach ( $pairs as $key => $value ) {
			$GLOBALS[$key] = $value;
		}

		$this->resetServices();
	}

	/**
	 * Set an ini setting for the duration of the test
	 * @param string $name Name of the setting
	 * @param string $value Value to set
	 * @since 1.32
	 */
	protected function setIniSetting( $name, $value ) {
		$original = ini_get( $name );
		$this->iniSettings[$name] = $original;
		ini_set( $name, $value );
	}

	/**
	 * Check if we can back up a value by performing a shallow copy.
	 * Values which fail this test are copied recursively.
	 *
	 * @param mixed $value
	 * @return bool True if a shallow copy will do; false if a deep copy
	 *  is required.
	 */
	private static function canShallowCopy( $value ) {
		if ( is_scalar( $value ) || $value === null ) {
			return true;
		}
		if ( is_array( $value ) ) {
			foreach ( $value as $subValue ) {
				if ( !is_scalar( $subValue ) && $subValue !== null ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	private function doStashMwGlobals( $globalKeys ) {
		if ( is_string( $globalKeys ) ) {
			$globalKeys = [ $globalKeys ];
		}

		foreach ( $globalKeys as $globalKey ) {
			// NOTE: make sure we only save the global once or a second call to
			// setMwGlobals() on the same global would override the original
			// value.
			if (
				!array_key_exists( $globalKey, $this->mwGlobals ) &&
				!array_key_exists( $globalKey, $this->mwGlobalsToUnset )
			) {
				if ( !array_key_exists( $globalKey, $GLOBALS ) ) {
					$this->mwGlobalsToUnset[$globalKey] = $globalKey;
					continue;
				}
				// NOTE: we serialize then unserialize the value in case it is an object
				// this stops any objects being passed by reference. We could use clone
				// and if is_object but this does account for objects within objects!
				if ( self::canShallowCopy( $GLOBALS[$globalKey] ) ) {
					$this->mwGlobals[$globalKey] = $GLOBALS[$globalKey];
				} elseif (
					// Many MediaWiki types are safe to clone. These are the
					// ones that are most commonly stashed.
					$GLOBALS[$globalKey] instanceof Language ||
					$GLOBALS[$globalKey] instanceof User ||
					$GLOBALS[$globalKey] instanceof FauxRequest
				) {
					$this->mwGlobals[$globalKey] = clone $GLOBALS[$globalKey];
				} else {
					try {
						$this->mwGlobals[$globalKey] = unserialize( serialize( $GLOBALS[$globalKey] ) );
					} catch ( Exception $e ) {
						$this->mwGlobals[$globalKey] = $GLOBALS[$globalKey];
					}
				}
			}
		}
	}

	/**
	 * Merges the given values into a MW global array variable.
	 * Useful for setting some entries in a configuration array, instead of
	 * setting the entire array.
	 *
	 * @param string $name The name of the global, as in wgFooBar
	 * @param array $values The array containing the entries to set in that global
	 *
	 * @throws MWException If the designated global is not an array.
	 *
	 * @note This will call resetServices().
	 *
	 * @since 1.21
	 */
	protected function mergeMwGlobalArrayValue( $name, $values ) {
		if ( !isset( $GLOBALS[$name] ) ) {
			$merged = $values;
		} else {
			if ( !is_array( $GLOBALS[$name] ) ) {
				throw new MWException( "MW global $name is not an array." );
			}

			// NOTE: do not use array_merge, it screws up for numeric keys.
			$merged = $GLOBALS[$name];
			foreach ( $values as $k => $v ) {
				$merged[$k] = $v;
			}
		}

		$this->setMwGlobals( $name, $merged );
	}

	/**
	 * Resets service instances in the global instance of MediaWikiServices.
	 *
	 * In contrast to overrideMwServices(), this does not create a new MediaWikiServices instance,
	 * and it preserves any service instances set via setService().
	 *
	 * The primary use case for this method is to allow changes to global configuration variables
	 * to take effect on services that get initialized based on these global configuration
	 * variables. Similarly, it may be necessary to call resetServices() after calling setService(),
	 * so the newly set service gets picked up by any other service definitions that may use it.
	 *
	 * @see MediaWikiServices::resetServiceForTesting.
	 *
	 * @since 1.34
	 */
	protected function resetServices() {
		// Reset but don't destroy service instances supplied via setService().
		$oldHookContainer = $this->localServices->getHookContainer();
		foreach ( $this->overriddenServices as $name ) {
			$this->localServices->resetServiceForTesting( $name, false );
		}

		// Reset all services with the destroy flag set.
		// This will not have any effect on services that had already been reset above.
		foreach ( $this->localServices->getServiceNames() as $name ) {
			$this->localServices->resetServiceForTesting( $name, true );
		}

		// If the hook container was reset, re-apply temporary hooks.
		$newHookContainer = $this->localServices->getHookContainer();
		if ( $newHookContainer !== $oldHookContainer ) {
			// the same hook may be cleared and registered several times
			foreach ( $this->temporaryHookHandlers as $tuple ) {
				[ $name, $target ] = $tuple;

				if ( !$target ) {
					$newHookContainer->clear( $name );
				} else {
					$newHookContainer->register( $name, $target );
				}
			}
		}

		self::resetLegacyGlobals();
		Language::$mLangObjCache = [];
	}

	/**
	 * Installs a new global instance of MediaWikiServices, allowing test cases to override
	 * settings and services.
	 *
	 * This method can be used to set up specific services or configuration as a fixture.
	 * It should not be used to reset services in between stages of a test - instead, the test
	 * should either be split, or resetServices() should be used.
	 *
	 * If called with no parameters, this method restores all services to their default state.
	 * This is done automatically before each test to isolate tests from any modification
	 * to settings and services that may have been applied by previous tests.
	 * That means that the effect of calling overrideMwServices() is undone before the next
	 * call to a test method.
	 *
	 * @note Calling this after having called setService() in the same test method (or the
	 *       associated setUp) will result in an MWException.
	 *       Tests should use either overrideMwServices() or setService(), but not mix both.
	 *       Since 1.34, resetServices() is available as an alternative compatible with setService().
	 *
	 * @since 1.27
	 *
	 * @param Config|null $configOverrides Configuration overrides for the new MediaWikiServices
	 *        instance.
	 * @param callable[] $services An associative array of services to re-define. Keys are service
	 *        names, values are callables.
	 *
	 * @return MediaWikiServices
	 * @throws MWException
	 */
	protected function overrideMwServices(
		Config $configOverrides = null, array $services = []
	) {
		if ( $this->overriddenServices ) {
			throw new MWException(
				'The following services were set and are now being unset by overrideMwServices: ' .
					implode( ', ', $this->overriddenServices )
			);
		}
		$newInstance = self::installMockMwServices( $configOverrides );

		if ( $this->localServices ) {
			$this->localServices->destroy();
		}

		$this->localServices = $newInstance;

		foreach ( $services as $name => $callback ) {
			$newInstance->redefineService( $name, $callback );
		}

		self::resetLegacyGlobals();
		Language::$mLangObjCache = [];

		return $newInstance;
	}

	/**
	 * Creates a new "mock" MediaWikiServices instance, and installs it.
	 * This effectively resets all cached states in services, with the exception of
	 * the ConfigFactory and the DBLoadBalancerFactory service, which are inherited from
	 * the original MediaWikiServices.
	 *
	 * @note The new original MediaWikiServices instance can later be restored by calling
	 * restoreMwServices(). That original is determined by the first call to this method, or
	 * by setUpBeforeClass, whichever is called first. The caller is responsible for managing
	 * and, when appropriate, destroying any other MediaWikiServices instances that may get
	 * replaced when calling this method.
	 *
	 * @param Config|null $configOverrides Configuration overrides for the new MediaWikiServices
	 *        instance.
	 *
	 * @return MediaWikiServices the new mock service locator.
	 */
	public static function installMockMwServices( Config $configOverrides = null ) {
		// Make sure we have the original service locator
		if ( !self::$originalServices ) {
			self::$originalServices = MediaWikiServices::getInstance();
		}

		// (T247990) Cache the original service wirings to work around a memory leak on PHP 7.4 and above
		if ( !self::$originalServiceWirings ) {
			$serviceWiringFiles = self::$originalServices->getBootstrapConfig()->get( 'ServiceWiringFiles' );

			foreach ( $serviceWiringFiles as $wiringFile ) {
				self::$originalServiceWirings[] = require $wiringFile;
			}
		}

		if ( !$configOverrides ) {
			$configOverrides = new HashConfig();
		}

		$oldConfigFactory = self::$originalServices->getConfigFactory();
		$oldLoadBalancerFactory = self::$originalServices->getDBLoadBalancerFactory();

		$testConfig = self::makeTestConfig( null, $configOverrides );
		$newServices = new MediaWikiServices( $testConfig );

		// Load the default wiring from the specified files.
		// NOTE: this logic mirrors the logic in MediaWikiServices::newInstance
		if ( $configOverrides->has( 'ServiceWiringFiles' ) ) {
			$wiringFiles = $testConfig->get( 'ServiceWiringFiles' );
			$newServices->loadWiringFiles( $wiringFiles );
		} else {
			// (T247990) Avoid including default wirings many times - use cached wirings
			foreach ( self::$originalServiceWirings as $wiring ) {
				$newServices->applyWiring( $wiring );
			}
		}

		// Provide a traditional hook point to allow extensions to configure services.
		Hooks::runner()->onMediaWikiServices( $newServices );

		// Use bootstrap config for all configuration.
		// This allows config overrides via global variables to take effect.
		$bootstrapConfig = $newServices->getBootstrapConfig();
		$newServices->resetServiceForTesting( 'ConfigFactory' );
		$newServices->redefineService(
			'ConfigFactory',
			self::makeTestConfigFactoryInstantiator(
				$oldConfigFactory,
				[ 'main' => $bootstrapConfig ]
			)
		);
		$newServices->resetServiceForTesting( 'DBLoadBalancerFactory' );
		$newServices->redefineService(
			'DBLoadBalancerFactory',
			function ( MediaWikiServices $services ) use ( $oldLoadBalancerFactory ) {
				return $oldLoadBalancerFactory;
			}
		);

		MediaWikiServices::forceGlobalInstance( $newServices );

		self::resetLegacyGlobals();

		return $newServices;
	}

	/**
	 * Restores the original, non-mock MediaWikiServices instance.
	 * The previously active MediaWikiServices instance is destroyed,
	 * if it is different from the original that is to be restored.
	 *
	 * @note this if for internal use by test framework code. It should never be
	 * called from inside a test case, a data provider, or a setUp or tearDown method.
	 *
	 * @return bool true if the original service locator was restored,
	 *         false if there was nothing  too do.
	 */
	public static function restoreMwServices() {
		if ( !self::$originalServices ) {
			return false;
		}

		$currentServices = MediaWikiServices::getInstance();

		if ( self::$originalServices === $currentServices ) {
			return false;
		}

		MediaWikiServices::forceGlobalInstance( self::$originalServices );
		$currentServices->destroy();

		self::resetLegacyGlobals();

		return true;
	}

	/**
	 * Replace legacy globals like $wgParser and $wgContLang with fresh ones so they pick up any
	 * config changes. They're deprecated, but we still support them for now.
	 */
	private static function resetLegacyGlobals() {
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgParser
		global $wgParser, $wgContLang;
		// We don't have to replace the parser if it wasn't unstubbed
		if ( !( $wgParser instanceof StubObject ) ) {
			$wgParser = new StubObject( 'wgParser', function () {
				return MediaWikiServices::getInstance()->getParser();
			} );
		}
		$wgContLang = MediaWikiServices::getInstance()->getContentLanguage();
	}

	/**
	 * @since 1.27
	 * @param string|Language $lang
	 */
	public function setUserLang( $lang ) {
		RequestContext::getMain()->setLanguage( $lang );
		$this->setMwGlobals( 'wgLang', RequestContext::getMain()->getLanguage() );
	}

	/**
	 * @deprecated since 1.35. To change the site language, use setMwGlobals( 'wgLanguageCode' ),
	 *   which will also reset the service. If you want to set the service to a specific object
	 *   (like a mock), use setService( 'ContentLanguage' ).
	 * @since 1.27
	 * @param string|Language $lang
	 */
	public function setContentLang( $lang ) {
		if ( $lang instanceof Language ) {
			// Set to the exact object requested
			$this->setService( 'ContentLanguage', $lang );
			$this->setMwGlobals( 'wgLanguageCode', $lang->getCode() );
		} else {
			$this->setMwGlobals( 'wgLanguageCode', $lang );
		}
	}

	/**
	 * Alters $wgGroupPermissions for the duration of the test.  Can be called
	 * with an array, like
	 *   [ '*' => [ 'read' => false ], 'user' => [ 'read' => false ] ]
	 * or three values to set a single permission, like
	 *   $this->setGroupPermissions( '*', 'read', false );
	 *
	 * @note This will call resetServices().
	 *
	 * @since 1.31
	 * @param array|string $newPerms Either an array of permissions to change,
	 *   in which case the next two parameters are ignored; or a single string
	 *   identifying a group, to use with the next two parameters.
	 * @param string|null $newKey
	 * @param mixed|null $newValue
	 */
	public function setGroupPermissions( $newPerms, $newKey = null, $newValue = null ) {
		global $wgGroupPermissions;

		if ( is_string( $newPerms ) ) {
			$newPerms = [ $newPerms => [ $newKey => $newValue ] ];
		}

		$newPermissions = $wgGroupPermissions;
		foreach ( $newPerms as $group => $permissions ) {
			foreach ( $permissions as $key => $value ) {
				$newPermissions[$group][$key] = $value;
			}
		}

		$this->setMwGlobals( 'wgGroupPermissions', $newPermissions );
	}

	/**
	 * Overrides specific user permissions until services are reloaded
	 *
	 * @since 1.34
	 *
	 * @param User $user
	 * @param string[]|string $permissions
	 *
	 * @throws Exception
	 */
	public function overrideUserPermissions( $user, $permissions = [] ) {
		MediaWikiServices::getInstance()->getPermissionManager()->overrideUserRightsForTesting(
			$user,
			$permissions
		);
	}

	/**
	 * Set the logger for a specified channel, for the duration of the test.
	 * @since 1.27
	 * @param string $channel
	 * @param LoggerInterface $logger
	 */
	protected function setLogger( $channel, LoggerInterface $logger ) {
		// TODO: Once loggers are managed by MediaWikiServices, use
		//       resetServiceForTesting() to set loggers.

		$provider = LoggerFactory::getProvider();
		if ( $provider instanceof LegacySpi || $provider instanceof LogCapturingSpi ) {
			$prev = $provider->setLoggerForTest( $channel, $logger );
			if ( !isset( $this->loggers[$channel] ) ) {
				// Remember for restoreLoggers()
				$this->loggers[$channel] = $prev;
			}
		} else {
			throw new LogicException( __METHOD__ . ': cannot set logger for ' . get_class( $provider ) );
		}
	}

	/**
	 * Restore loggers replaced by setLogger() or setNullLogger().
	 * @since 1.27
	 */
	private function restoreLoggers() {
		$provider = LoggerFactory::getProvider();
		foreach ( $this->loggers as $channel => $logger ) {
			if ( $provider instanceof LegacySpi || $provider instanceof LogCapturingSpi ) {
				// Replace override with original object or null
				$provider->setLoggerForTest( $channel, $logger );
			}
		}
		$this->loggers = [];

		foreach (
			array_splice( $this->ignoredLoggers, 0 )
			as [ $logger, $level ]
		) {
			$logger->setMinimumForTest( $level );
		}
	}

	/**
	 * Ignore all messages for the specified log channel.
	 *
	 * This is an alternative to setLogger() for when an existing logger
	 * must be changed as well (T248195).
	 *
	 * @since 1.35
	 * @param string $channel
	 */
	protected function setNullLogger( $channel ) {
		$spi = LoggerFactory::getProvider();
		$spiCapture = null;
		if ( $spi instanceof LogCapturingSpi ) {
			$spiCapture = $spi;
			$spi = $spiCapture->getInnerSpi();
		}
		if ( !$spi instanceof LegacySpi ) {
			throw new LogicException( __METHOD__ . ': cannot set logger for ' . get_class( $spi ) );
		}

		$existing = $spi->getLogger( $channel );
		$level = $existing->setMinimumForTest( null );
		$this->ignoredLoggers[] = [ $existing, $level ];
		if ( $spiCapture ) {
			$spiCapture->setLoggerForTest( $channel, new NullLogger() );
			// Remember to unset in restoreLoggers()
			$this->loggers[$channel] = null;
		}
	}

	/**
	 * @return string
	 * @since 1.18
	 */
	public function dbPrefix() {
		return self::getTestPrefixFor( $this->db );
	}

	/**
	 * @param IDatabase $db
	 * @return string
	 * @since 1.32
	 */
	public static function getTestPrefixFor( IDatabase $db ) {
		return self::DB_PREFIX;
	}

	/**
	 * @return bool
	 * @since 1.18
	 */
	public function needsDB() {
		// If the test says it uses database tables, it needs the database
		return $this->tablesUsed || $this->isTestInDatabaseGroup();
	}

	/**
	 * Insert a new page.
	 *
	 * Should be called from addDBData().
	 *
	 * @since 1.25 ($namespace in 1.28)
	 * @param string|Title $pageName Page name or title
	 * @param string $text Page's content
	 * @param int|null $namespace Namespace id (name cannot already contain namespace)
	 * @param User|null $user If null, static::getTestSysop()->getUser() is used.
	 * @return array Title object and page id
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function insertPage(
		$pageName,
		$text = 'Sample page for unit test.',
		$namespace = null,
		User $user = null
	) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing with pages, the test cases\'s needsDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		if ( is_string( $pageName ) ) {
			$title = Title::newFromText( $pageName, $namespace );
		} else {
			$title = $pageName;
		}

		if ( !$user ) {
			$user = static::getTestSysop()->getUser();
		}
		$comment = __METHOD__ . ': Sample page for unit test.';

		$page = WikiPage::factory( $title );
		$page->doEditContent( ContentHandler::makeContent( $text, $title ), $comment, 0, false, $user );

		return [
			'title' => $title,
			'id' => $page->getId(),
		];
	}

	/**
	 * Stub. If a test suite needs to add additional data to the database, it should
	 * implement this method and do so. This method is called once per test suite
	 * (i.e. once per class).
	 *
	 * Note data added by this method may be removed by resetDB() depending on
	 * the contents of $tablesUsed.
	 *
	 * To add additional data between test function runs, override addDBData().
	 *
	 * @see addDBData()
	 * @see resetDB()
	 *
	 * @since 1.27
	 * @stable for overriding
	 */
	public function addDBDataOnce() {
	}

	/**
	 * Stub. Subclasses may override this to prepare the database.
	 * Called before every test run (test function or data set).
	 *
	 * @see addDBDataOnce()
	 * @see resetDB()
	 *
	 * @since 1.18
	 * @stable for overriding
	 */
	public function addDBData() {
	}

	/**
	 * @since 1.32
	 */
	protected function addCoreDBData() {
		SiteStatsInit::doPlaceholderInit();

		User::resetIdByNameCache();

		// Make sysop user
		$user = static::getTestSysop()->getUser();

		// Make 1 page with 1 revision
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		if ( $page->getId() == 0 ) {
			$page->doEditContent(
				new WikitextContent( 'UTContent' ),
				'UTPageSummary',
				EDIT_NEW | EDIT_SUPPRESS_RC,
				false,
				$user
			);
			// an edit always attempt to purge backlink links such as history
			// pages. That is unnecessary.
			JobQueueGroup::singleton()->get( 'htmlCacheUpdate' )->delete();
			// WikiPages::doEditUpdates randomly adds RC purges
			JobQueueGroup::singleton()->get( 'recentChangesUpdate' )->delete();

			// doEditContent() probably started the session via
			// User::loadFromSession(). Close it now.
			if ( session_id() !== '' ) {
				session_write_close();
				session_id( '' );
			}
		}
	}

	/**
	 * Restores MediaWiki to using the table set (table prefix) it was using before
	 * setupTestDB() was called. Useful if we need to perform database operations
	 * after the test run has finished (such as saving logs).
	 *
	 * This is called by phpunit/bootstrap.php after the last test.
	 *
	 * @since 1.21
	 */
	public static function teardownTestDB() {
		global $wgJobClasses;

		if ( !self::$dbSetup ) {
			return;
		}

		Hooks::runner()->onUnitTestsBeforeDatabaseTeardown();

		foreach ( $wgJobClasses as $type => $class ) {
			// Delete any jobs under the clone DB (or old prefix in other stores)
			JobQueueGroup::singleton()->get( $type )->delete();
		}

		// T219673: close any connections from code that failed to call reuseConnection()
		// or is still holding onto a DBConnRef instance (e.g. in a singleton).
		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->closeAll();
		CloneDatabase::changePrefix( self::$oldTablePrefix );

		self::$oldTablePrefix = false;
		self::$dbSetup = false;
	}

	/**
	 * Setups a database with cloned tables using the given prefix.
	 *
	 * If reuseDB is true and certain conditions apply, it will just change the prefix.
	 * Otherwise, it will clone the tables and change the prefix.
	 *
	 * @param IMaintainableDatabase $db Database to use
	 * @param string|null $prefix Prefix to use for test tables. If not given, the prefix is determined
	 *        automatically for $db.
	 * @return bool True if tables were cloned, false if only the prefix was changed
	 */
	protected static function setupDatabaseWithTestPrefix(
		IMaintainableDatabase $db,
		$prefix = null
	) {
		if ( $prefix === null ) {
			$prefix = self::getTestPrefixFor( $db );
		}

		if ( !self::$useTemporaryTables && self::$reuseDB ) {
			$db->tablePrefix( $prefix );
			return false;
		}

		if ( !isset( $db->_originalTablePrefix ) ) {
			$oldPrefix = $db->tablePrefix();
			if ( $oldPrefix === $prefix ) {
				// table already has the correct prefix, but presumably no cloned tables
				$oldPrefix = self::$oldTablePrefix;
			}

			$db->tablePrefix( $oldPrefix );
			$tablesCloned = self::listTables( $db );
			$dbClone = new CloneDatabase( $db, $tablesCloned, $prefix, $oldPrefix );
			$dbClone->useTemporaryTables( self::$useTemporaryTables );
			$dbClone->cloneTableStructure();

			$db->tablePrefix( $prefix );
			$db->_originalTablePrefix = $oldPrefix;

			$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
			$lb->setTempTablesOnlyMode( self::$useTemporaryTables, $db->getDomainID() );
		}

		return true;
	}

	/**
	 * Set up all test DBs
	 */
	public function setupAllTestDBs() {
		global $wgDBprefix;

		self::$oldTablePrefix = $wgDBprefix;

		$testPrefix = $this->dbPrefix();

		// switch to a temporary clone of the database
		self::setupTestDB( $this->db, $testPrefix );

		if ( self::isUsingExternalStoreDB() ) {
			self::setupExternalStoreTestDBs( $testPrefix );
		}

		// NOTE: Change the prefix in the LBFactory and $wgDBprefix, to prevent
		// *any* database connections to operate on live data.
		CloneDatabase::changePrefix( $testPrefix );
	}

	/**
	 * Creates an empty skeleton of the wiki database by cloning its structure
	 * to equivalent tables using the given $prefix. Then sets MediaWiki to
	 * use the new set of tables (aka schema) instead of the original set.
	 *
	 * This is used to generate a dummy table set, typically consisting of temporary
	 * tables, that will be used by tests instead of the original wiki database tables.
	 *
	 * @since 1.21
	 *
	 * @note the original table prefix is stored in self::$oldTablePrefix. This is used
	 * by teardownTestDB() to return the wiki to using the original table set.
	 *
	 * @note this method only works when first called. Subsequent calls have no effect,
	 * even if using different parameters.
	 *
	 * @param IMaintainableDatabase $db The database connection
	 * @param string $prefix The prefix to use for the new table set (aka schema).
	 *
	 * @throws MWException If the database table prefix is already $prefix
	 */
	public static function setupTestDB( IMaintainableDatabase $db, $prefix ) {
		if ( self::$dbSetup ) {
			return;
		}

		if ( $db->tablePrefix() === $prefix ) {
			throw new MWException(
				'Cannot run unit tests, the database prefix is already "' . $prefix . '"' );
		}

		// TODO: the below should be re-written as soon as LBFactory, LoadBalancer,
		// and Database no longer use global state.

		self::$dbSetup = true;

		if ( !self::setupDatabaseWithTestPrefix( $db, $prefix ) ) {
			return;
		}

		Hooks::runner()->onUnitTestsAfterDatabaseSetup( $db, $prefix );
	}

	/**
	 * Clones the External Store database(s) for testing
	 *
	 * @param string|null $testPrefix Prefix for test tables. Will be determined automatically
	 *        if not given.
	 */
	protected static function setupExternalStoreTestDBs( $testPrefix = null ) {
		$connections = self::getExternalStoreDatabaseConnections();
		foreach ( $connections as $dbw ) {
			self::setupDatabaseWithTestPrefix( $dbw, $testPrefix );
		}
	}

	/**
	 * Gets master database connections for all of the ExternalStoreDB
	 * stores configured in $wgDefaultExternalStore.
	 *
	 * @return Database[] Array of Database master connections
	 */
	protected static function getExternalStoreDatabaseConnections() {
		global $wgDefaultExternalStore;

		/** @var ExternalStoreDB $externalStoreDB */
		$externalStoreDB = ExternalStore::getStoreObject( 'DB' );
		$defaultArray = (array)$wgDefaultExternalStore;
		$dbws = [];
		foreach ( $defaultArray as $url ) {
			if ( strpos( $url, 'DB://' ) === 0 ) {
				[ $proto, $cluster ] = explode( '://', $url, 2 );
				// Avoid getMaster() because setupDatabaseWithTestPrefix()
				// requires Database instead of plain DBConnRef/IDatabase
				$dbws[] = $externalStoreDB->getMaster( $cluster );
			}
		}

		return $dbws;
	}

	/**
	 * Check whether ExternalStoreDB is being used
	 *
	 * @return bool True if it's being used
	 */
	protected static function isUsingExternalStoreDB() {
		global $wgDefaultExternalStore;
		if ( !$wgDefaultExternalStore ) {
			return false;
		}

		$defaultArray = (array)$wgDefaultExternalStore;
		foreach ( $defaultArray as $url ) {
			if ( strpos( $url, 'DB://' ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @throws LogicException if the given database connection is not a set up to use
	 * mock tables.
	 *
	 * @param IDatabase $db
	 * @since 1.31 this is no longer private.
	 */
	protected function ensureMockDatabaseConnection( IDatabase $db ) {
		if ( $db->tablePrefix() !== $this->dbPrefix() ) {
			throw new LogicException(
				'Trying to delete mock tables, but table prefix does not indicate a mock database.'
			);
		}
	}

	private static $schemaOverrideDefaults = [
		'scripts' => [],
		'create' => [],
		'drop' => [],
		'alter' => [],
	];

	/**
	 * Stub. If a test suite needs to test against a specific database schema, it should
	 * override this method and return the appropriate information from it.
	 *
	 * 'create', 'drop' and 'alter' in the returned array should list all the tables affected
	 * by the 'scripts', even if the test is only interested in a subset of them, otherwise
	 * the overrides may not be fully cleaned up, leading to errors later.
	 *
	 * @stable for overriding
	 * @param IMaintainableDatabase $db The DB connection to use for the mock schema.
	 *        May be used to check the current state of the schema, to determine what
	 *        overrides are needed.
	 *
	 * @return array An associative array with the following fields:
	 *  - 'scripts': any SQL scripts to run. If empty or not present, schema overrides are skipped.
	 * - 'create': A list of tables created (may or may not exist in the original schema).
	 * - 'drop': A list of tables dropped (expected to be present in the original schema).
	 * - 'alter': A list of tables altered (expected to be present in the original schema).
	 */
	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [];
	}

	/**
	 * Undoes the specified schema overrides..
	 * Called once per test class, just before addDataOnce().
	 *
	 * @param IMaintainableDatabase $db
	 * @param array $oldOverrides
	 */
	private function undoSchemaOverrides( IMaintainableDatabase $db, $oldOverrides ) {
		$this->ensureMockDatabaseConnection( $db );

		$oldOverrides = $oldOverrides + self::$schemaOverrideDefaults;
		$originalTables = $this->listOriginalTables( $db );

		// Drop tables that need to be restored or removed.
		$tablesToDrop = array_merge( $oldOverrides['create'], $oldOverrides['alter'] );

		// Restore tables that have been dropped or created or altered,
		// if they exist in the original schema.
		$tablesToRestore = array_merge( $tablesToDrop, $oldOverrides['drop'] );
		$tablesToRestore = array_intersect( $originalTables, $tablesToRestore );

		if ( $tablesToDrop ) {
			$this->dropMockTables( $db, $tablesToDrop );
		}

		if ( $tablesToRestore ) {
			$this->recloneMockTables( $db, $tablesToRestore );

			// Reset the restored tables, mainly for the side effect of
			// re-calling $this->addCoreDBData() if necessary.
			$this->resetDB( $db, $tablesToRestore );
		}
	}

	/**
	 * Applies the schema overrides returned by getSchemaOverrides(),
	 * after undoing any previously applied schema overrides.
	 * Called once per test class, just before addDataOnce().
	 */
	private function setUpSchema( IMaintainableDatabase $db ) {
		// Undo any active overrides.
		$oldOverrides = $db->_schemaOverrides ?? self::$schemaOverrideDefaults;

		if ( $oldOverrides['alter'] || $oldOverrides['create'] || $oldOverrides['drop'] ) {
			$this->undoSchemaOverrides( $db, $oldOverrides );
			unset( $db->_schemaOverrides );
		}

		// Determine new overrides.
		$overrides = $this->getSchemaOverrides( $db ) + self::$schemaOverrideDefaults;

		$extraKeys = array_diff(
			array_keys( $overrides ),
			array_keys( self::$schemaOverrideDefaults )
		);

		if ( $extraKeys ) {
			throw new InvalidArgumentException(
				'Schema override contains extra keys: ' . var_export( $extraKeys, true )
			);
		}

		if ( !$overrides['scripts'] ) {
			// no scripts to run
			return;
		}

		if ( !$overrides['create'] && !$overrides['drop'] && !$overrides['alter'] ) {
			throw new InvalidArgumentException(
				'Schema override scripts given, but no tables are declared to be '
				. 'created, dropped or altered.'
			);
		}

		$this->ensureMockDatabaseConnection( $db );

		// Drop the tables that will be created by the schema scripts.
		$originalTables = $this->listOriginalTables( $db );
		$tablesToDrop = array_intersect( $originalTables, $overrides['create'] );

		if ( $tablesToDrop ) {
			$this->dropMockTables( $db, $tablesToDrop );
		}

		// Run schema override scripts.
		foreach ( $overrides['scripts'] as $script ) {
			$db->sourceFile(
				$script,
				null,
				null,
				__METHOD__,
				function ( $cmd ) {
					return $this->mungeSchemaUpdateQuery( $cmd );
				}
			);
		}

		$db->_schemaOverrides = $overrides;
	}

	private function mungeSchemaUpdateQuery( $cmd ) {
		return self::$useTemporaryTables
			? preg_replace( '/\bCREATE\s+TABLE\b/i', 'CREATE TEMPORARY TABLE', $cmd )
			: $cmd;
	}

	/**
	 * Drops the given mock tables.
	 *
	 * @param IMaintainableDatabase $db
	 * @param array $tables
	 */
	private function dropMockTables( IMaintainableDatabase $db, array $tables ) {
		$this->ensureMockDatabaseConnection( $db );

		foreach ( $tables as $tbl ) {
			$tbl = $db->tableName( $tbl );
			$db->query( "DROP TABLE IF EXISTS $tbl", __METHOD__ );
		}
	}

	/**
	 * Lists all tables in the live database schema, without a prefix.
	 *
	 * @param IMaintainableDatabase $db
	 * @return array
	 */
	private function listOriginalTables( IMaintainableDatabase $db ) {
		if ( !isset( $db->_originalTablePrefix ) ) {
			throw new LogicException( 'No original table prefix know, cannot list tables!' );
		}

		$originalTables = $db->listTables( $db->_originalTablePrefix, __METHOD__ );

		$unittestPrefixRegex = '/^' . preg_quote( $this->dbPrefix(), '/' ) . '/';
		$originalPrefixRegex = '/^' . preg_quote( $db->_originalTablePrefix, '/' ) . '/';

		$originalTables = array_filter(
			$originalTables,
			function ( $pt ) use ( $unittestPrefixRegex ) {
				return !preg_match( $unittestPrefixRegex, $pt );
			}
		);

		$originalTables = array_map(
			function ( $pt ) use ( $originalPrefixRegex ) {
				return preg_replace( $originalPrefixRegex, '', $pt );
			},
			$originalTables
		);

		return array_unique( $originalTables );
	}

	/**
	 * Re-clones the given mock tables to restore them based on the live database schema.
	 * The tables listed in $tables are expected to currently not exist, so dropMockTables()
	 * should be called first.
	 *
	 * @param IMaintainableDatabase $db
	 * @param array $tables
	 */
	private function recloneMockTables( IMaintainableDatabase $db, array $tables ) {
		$this->ensureMockDatabaseConnection( $db );

		if ( !isset( $db->_originalTablePrefix ) ) {
			throw new LogicException( 'No original table prefix know, cannot restore tables!' );
		}

		$originalTables = $this->listOriginalTables( $db );
		$tables = array_intersect( $tables, $originalTables );

		$dbClone = new CloneDatabase( $db, $tables, $db->tablePrefix(), $db->_originalTablePrefix );
		$dbClone->useTemporaryTables( self::$useTemporaryTables );
		$dbClone->cloneTableStructure();

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$lb->setTempTablesOnlyMode( self::$useTemporaryTables, $lb->getLocalDomainID() );
	}

	/**
	 * Empty all tables so they can be repopulated for tests
	 *
	 * @param IDatabase|null $db Database to reset
	 * @param string[] $tablesUsed Tables to reset
	 */
	private function resetDB( ?IDatabase $db, array $tablesUsed ) {
		if ( $db ) {
			// some groups of tables are connected such that if any is used, all should be cleared
			$extraTables = [
				'user' => [ 'user', 'user_groups', 'user_properties', 'actor' ],
				'page' => [ 'page', 'revision', 'ip_changes', 'revision_comment_temp', 'comment', 'archive',
					'revision_actor_temp', 'slots', 'content', 'content_models', 'slot_roles',
					'change_tag' ],
				'logging' => [ 'logging', 'log_search', 'change_tag' ],
			];
			$coreDBDataTables = array_merge( $extraTables['user'], $extraTables['page'] );

			foreach ( $extraTables as $i => $group ) {
				if ( !array_intersect( $tablesUsed, $group ) ) {
					unset( $extraTables[$i] );
				}
			}
			$extraTables = array_values( $extraTables );
			$tablesUsed = array_unique( array_merge( $tablesUsed, ...$extraTables ) );

			if ( in_array( 'user', $tablesUsed ) ) {
				TestUserRegistry::clear();

				// Reset $wgUser, which is probably 127.0.0.1, as its loaded data is probably not valid
				// @todo Should we start setting $wgUser to something nondeterministic
				//  to encourage tests to be updated to not depend on it?
				global $wgUser;
				$wgUser->clearInstanceCache( $wgUser->mFrom );
			}

			$this->truncateTables( $tablesUsed, $db );

			if ( array_intersect( $tablesUsed, $coreDBDataTables ) ) {
				// Reset services that may contain information relating to the truncated tables
				$this->overrideMwServices();
				// Re-add core DB data that was deleted
				$this->addCoreDBData();
			}
		}
	}

	protected function truncateTable( $table, IDatabase $db = null ) {
		$this->truncateTables( [ $table ], $db );
	}

	/**
	 * Empties the given tables and resets any auto-increment counters.
	 * Will also purge caches associated with some well known tables.
	 * If the table is not know, this method just returns.
	 *
	 * @param string[] $tables
	 * @param IDatabase|null $db
	 */
	protected function truncateTables( array $tables, IDatabase $db = null ) {
		$dbw = $db ?: $this->db;

		$dbw->truncate( $tables, __METHOD__ );

		// re-initialize site_stats table
		if ( in_array( 'site_stats', $tables ) ) {
			SiteStatsInit::doPlaceholderInit();
		}
	}

	private static function unprefixTable( &$tableName, $ind, $prefix ) {
		$tableName = substr( $tableName, strlen( $prefix ) );
	}

	private static function isNotUnittest( $table ) {
		return strpos( $table, self::DB_PREFIX ) !== 0;
	}

	/**
	 * @since 1.18
	 *
	 * @param IMaintainableDatabase $db
	 *
	 * @return array
	 */
	public static function listTables( IMaintainableDatabase $db ) {
		$prefix = $db->tablePrefix();
		$tables = $db->listTables( $prefix, __METHOD__ );

		if ( $db->getType() === 'mysql' ) {
			static $viewListCache = null;
			if ( $viewListCache === null ) {
				$viewListCache = $db->listViews( null, __METHOD__ );
			}
			// T45571: cannot clone VIEWs under MySQL
			$tables = array_diff( $tables, $viewListCache );
		}
		array_walk( $tables, [ __CLASS__, 'unprefixTable' ], $prefix );

		// Don't duplicate test tables from the previous fataled run
		$tables = array_filter( $tables, [ __CLASS__, 'isNotUnittest' ] );

		if ( $db->getType() == 'sqlite' ) {
			$tables = array_flip( $tables );
			// these are subtables of searchindex and don't need to be duped/dropped separately
			unset( $tables['searchindex_content'] );
			unset( $tables['searchindex_segdir'] );
			unset( $tables['searchindex_segments'] );
			$tables = array_flip( $tables );
		}

		return $tables;
	}

	/**
	 * Copy test data from one database connection to another.
	 *
	 * This should only be used for small data sets.
	 *
	 * @param IDatabase $source
	 * @param IDatabase $target
	 */
	public function copyTestData( IDatabase $source, IDatabase $target ) {
		if ( $this->db->getType() === 'sqlite' ) {
			// SQLite uses a non-temporary copy of the searchindex table for testing,
			// which gets deleted and re-created when setting up the secondary connection,
			// causing "Error 17" when trying to copy the data. See T191863#4130112.
			throw new RuntimeException(
				'Setting up a secondary database connection with test data is currently not'
				. 'with SQLite. You may want to use markTestSkippedIfDbType() to bypass this issue.'
			);
		}

		$tables = self::listOriginalTables( $source );

		foreach ( $tables as $table ) {
			$res = $source->select( $table, '*', [], __METHOD__ );
			$allRows = [];

			foreach ( $res as $row ) {
				$allRows[] = (array)$row;
			}

			$target->insert( $table, $allRows, __METHOD__, [ 'IGNORE' ] );
		}
	}

	/**
	 * @throws MWException
	 * @since 1.18
	 */
	protected function checkDbIsSupported() {
		if ( !in_array( $this->db->getType(), $this->supportedDBs ) ) {
			throw new MWException( $this->db->getType() . " is not currently supported for unit testing." );
		}
	}

	/**
	 * @since 1.18
	 * @param string $offset
	 * @return mixed
	 */
	public function getCliArg( $offset ) {
		return MediaWikiCliOptions::$additionalOptions[$offset] ?? null;
	}

	/**
	 * @since 1.18
	 * @param string $offset
	 * @param mixed $value
	 */
	public function setCliArg( $offset, $value ) {
		MediaWikiCliOptions::$additionalOptions[$offset] = $value;
	}

	/**
	 * Asserts that the given database query yields the rows given by $expectedRows.
	 * The expected rows should be given as indexed (not associative) arrays, with
	 * the values given in the order of the columns in the $fields parameter.
	 * Note that the rows are sorted by the columns given in $fields.
	 *
	 * @since 1.20
	 *
	 * @param string|array $table The table(s) to query
	 * @param string|array $fields The columns to include in the result (and to sort by)
	 * @param string|array $condition "where" condition(s)
	 * @param array $expectedRows An array of arrays giving the expected rows.
	 * @param array $options Options for the query
	 * @param array $join_conds Join conditions for the query
	 *
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function assertSelect(
		$table, $fields, $condition, array $expectedRows, array $options = [], array $join_conds = []
	) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing database state, the test cases\'s needDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$db = wfGetDB( DB_REPLICA );

		$res = $db->select(
			$table,
			$fields,
			$condition,
			wfGetCaller(),
			$options + [ 'ORDER BY' => $fields ],
			$join_conds
		);
		$this->assertNotEmpty( $res, "query failed: " . $db->lastError() );

		$i = 0;

		foreach ( $expectedRows as $expected ) {
			$r = $res->fetchRow();
			self::stripStringKeys( $r );

			$i += 1;
			$this->assertNotEmpty( $r, "row #$i missing" );

			$this->assertEquals( $expected, $r, "row #$i mismatches" );
		}

		$r = $res->fetchRow();
		self::stripStringKeys( $r );

		$this->assertFalse( $r, "found extra row (after #$i)" );
	}

	/**
	 * Assert that the key-based intersection of the two arrays matches the expected subset
	 *
	 * Order does not matter. Strict type and object identity will be checked.
	 *
	 * @param array $expectedSubset
	 * @param array $actualSuperset
	 * @param string $description
	 * @since 1.35
	 */
	protected function assertArraySubmapSame(
		array $expectedSubset,
		array $actualSuperset,
		$description = ''
	) {
		$patched = array_replace_recursive( $actualSuperset, $expectedSubset );

		ksort( $patched );
		ksort( $actualSuperset );
		$result = ( $actualSuperset === $patched );

		if ( !$result ) {
			$comparisonFailure = new ComparisonFailure(
				$patched,
				$actualSuperset,
				var_export( $patched, true ),
				var_export( $actualSuperset, true )
			);

			$failureDescription = 'Failed asserting that array contains the expected submap.';
			if ( $description != '' ) {
				$failureDescription = $description . "\n" . $failureDescription;
			}

			throw new ExpectationFailedException(
				$failureDescription,
				$comparisonFailure
			);
		} else {
			$this->assertTrue( true, $description );
		}
	}

	/**
	 * Utility method taking an array of elements and wrapping
	 * each element in its own array. Useful for data providers
	 * that only return a single argument.
	 *
	 * @since 1.20
	 *
	 * @param array $elements
	 *
	 * @return array
	 */
	protected function arrayWrap( array $elements ) {
		return array_map(
			function ( $element ) {
				return [ $element ];
			},
			$elements
		);
	}

	/**
	 * Put each HTML element on its own line and then equals() the results
	 *
	 * Use for nicely formatting of PHPUnit diff output when comparing very
	 * simple HTML
	 *
	 * @since 1.20
	 *
	 * @param string $expected HTML on oneline
	 * @param string $actual HTML on oneline
	 * @param string $msg Optional message
	 */
	protected function assertHTMLEquals( $expected, $actual, $msg = '' ) {
		$expected = str_replace( '>', ">\n", $expected );
		$actual = str_replace( '>', ">\n", $actual );

		$this->assertEquals( $expected, $actual, $msg );
	}

	/**
	 * Utility function for eliminating all string keys from an array.
	 * Useful to turn a database result row as returned by fetchRow() into
	 * a pure indexed array.
	 *
	 * @since 1.20
	 *
	 * @param mixed &$r The array to remove string keys from.
	 */
	protected static function stripStringKeys( &$r ) {
		if ( !is_array( $r ) ) {
			return;
		}

		foreach ( $r as $k => $v ) {
			if ( is_string( $k ) ) {
				unset( $r[$k] );
			}
		}
	}

	/**
	 * Asserts the type of the provided value. This can be either
	 * in internal type such as boolean or integer, or a class or
	 * interface the value extends or implements.
	 *
	 * @deprecated since 1.35 Following the PHPUnit deprecation of assertInternalType
	 *
	 * @param string $type
	 * @param mixed $actual
	 * @param string $message
	 */
	protected function assertType( $type, $actual, $message = '' ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( class_exists( $type ) || interface_exists( $type ) ) {
			$this->assertInstanceOf( $type, $actual, $message );
		} else {
			// phpcs:ignore MediaWiki.Usage.PHPUnitDeprecatedMethods.AssertInternalTypeGeneric
			$this->assertInternalType( $type, $actual, $message );
		}
	}

	/**
	 * Returns true if the given namespace defaults to Wikitext
	 * according to $wgNamespaceContentModels
	 *
	 * @param int $ns The namespace ID to check
	 *
	 * @return bool
	 * @since 1.21
	 */
	protected function isWikitextNS( $ns ) {
		global $wgNamespaceContentModels;

		if ( isset( $wgNamespaceContentModels[$ns] ) ) {
			return $wgNamespaceContentModels[$ns] === CONTENT_MODEL_WIKITEXT;
		}

		return true;
	}

	/**
	 * Returns the ID of a namespace that defaults to Wikitext.
	 *
	 * @throws MWException If there is none.
	 * @return int The ID of the wikitext Namespace
	 * @since 1.21
	 */
	protected function getDefaultWikitextNS() {
		global $wgNamespaceContentModels;

		static $wikitextNS = null; // this is not going to change
		if ( $wikitextNS !== null ) {
			return $wikitextNS;
		}

		// quickly short out on most common case:
		if ( !isset( $wgNamespaceContentModels[NS_MAIN] ) ) {
			return NS_MAIN;
		}

		// NOTE: prefer content namespaces
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		$namespaces = array_unique( array_merge(
			$nsInfo->getContentNamespaces(),
			[ NS_MAIN, NS_HELP, NS_PROJECT ], // prefer these
			$nsInfo->getValidNamespaces()
		) );

		$namespaces = array_diff( $namespaces, [
			NS_FILE, NS_CATEGORY, NS_MEDIAWIKI, NS_USER // don't mess with magic namespaces
		] );

		$talk = array_filter( $namespaces, function ( $ns ) use ( $nsInfo ) {
			return $nsInfo->isTalk( $ns );
		} );

		// prefer non-talk pages
		$namespaces = array_diff( $namespaces, $talk );
		$namespaces = array_merge( $namespaces, $talk );

		// check default content model of each namespace
		foreach ( $namespaces as $ns ) {
			if ( !isset( $wgNamespaceContentModels[$ns] ) ||
				$wgNamespaceContentModels[$ns] === CONTENT_MODEL_WIKITEXT
			) {
				$wikitextNS = $ns;

				return $wikitextNS;
			}
		}

		// give up
		// @todo Inside a test, we could skip the test as incomplete.
		//        But frequently, this is used in fixture setup.
		throw new MWException( "No namespace defaults to wikitext!" );
	}

	/**
	 * Check, if $wgDiff3 is set and ready to merge
	 * Will mark the calling test as skipped, if not ready
	 *
	 * @since 1.21
	 */
	protected function markTestSkippedIfNoDiff3() {
		global $wgDiff3;

		# This check may also protect against code injection in
		# case of broken installations.
		Wikimedia\suppressWarnings();
		$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
		Wikimedia\restoreWarnings();

		if ( !$haveDiff3 ) {
			$this->markTestSkipped( "Skip test, since diff3 is not configured" );
		}
	}

	/**
	 * Check if $extName is a loaded PHP extension, will skip the
	 * test whenever it is not loaded.
	 *
	 * @since 1.21
	 * @param string $extName
	 * @return bool
	 */
	protected function checkPHPExtension( $extName ) {
		$loaded = extension_loaded( $extName );
		if ( !$loaded ) {
			$this->markTestSkipped( "PHP extension '$extName' is not loaded, skipping." );
		}

		return $loaded;
	}

	/**
	 * Skip the test if using the specified database type
	 *
	 * @param string $type Database type
	 * @since 1.32
	 */
	protected function markTestSkippedIfDbType( $type ) {
		if ( $this->db->getType() === $type ) {
			$this->markTestSkipped( "The $type database type isn't supported for this test" );
		}
	}

	/**
	 * Used as a marker to prevent wfResetOutputBuffers from breaking PHPUnit.
	 * @param string $buffer
	 * @return string
	 */
	public static function wfResetOutputBuffersBarrier( $buffer ) {
		return $buffer;
	}

	/**
	 * Registers the given hook handler for the duration of the current test case.
	 *
	 * @param string $hookName Hook name
	 * @param mixed $handler Value suitable for a hook handler
	 * @param bool $replace (optional) Default is to replace all existing handlers for the given hook.
	 *        Set false to add to existing handler list.
	 * @since 1.28
	 */
	protected function setTemporaryHook( $hookName, $handler, $replace = true ) {
		if ( $replace ) {
			$this->clearHook( $hookName );
		}
		$this->localServices->getHookContainer()->register( $hookName, $handler );
		$this->temporaryHookHandlers[] = [ $hookName, $handler ];
	}

	/**
	 * Remove all handlers for the given hook for the duration of the current test case.
	 *
	 * @param string $hookName
	 * @since 1.36
	 */
	protected function clearHook( $hookName ) {
		$this->localServices->getHookContainer()->clear( $hookName );
		$this->temporaryHookHandlers[] = [ $hookName, false ];
	}

	/**
	 * Remove a temporary hook previously added with setTemporaryHook().
	 *
	 * @note This is implemented to remove ALL handlers for the given hook
	 *       for the duration of the current test case.
	 * @deprecated since 1.36, use clearHook() instead.
	 *
	 * @param string $hookName
	 */
	protected function removeTemporaryHook( $hookName ) {
		$this->clearHook( $hookName );
	}

	/**
	 * Edits or creates a page/revision
	 * @param string $pageName Page title
	 * @param string $text Content of the page
	 * @param string $summary Optional summary string for the revision
	 * @param int $defaultNs Optional namespace id
	 * @param User|null $user If null, $this->getTestUser()->getUser() is used.
	 * @return Status Object as returned by WikiPage::doEditContent()
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function editPage(
		$pageName,
		$text,
		$summary = '',
		$defaultNs = NS_MAIN,
		User $user = null
	) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing with pages, the test cases\'s needsDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$title = Title::newFromText( $pageName, $defaultNs );
		$page = WikiPage::factory( $title );

		if ( $user === null ) {
			$user = $this->getTestUser()->getUser();
		}

		return $page->doEditContent(
			ContentHandler::makeContent( $text, $title ),
			$summary,
			0,
			false,
			$user
		);
	}

	/**
	 * Revision-deletes a revision.
	 *
	 * @param RevisionRecord|int $rev Revision to delete
	 * @param array $value Keys are RevisionRecord::DELETED_* flags.  Values are 1 to set the bit,
	 *   0 to clear, -1 to leave alone.  (All other values also clear the bit.)
	 * @param string $comment Deletion comment
	 */
	protected function revisionDelete(
		$rev, array $value = [ RevisionRecord::DELETED_TEXT => 1 ], $comment = ''
	) {
		if ( is_int( $rev ) ) {
			$rev = MediaWikiServices::getInstance()
				->getRevisionLookup()
				->getRevisionById( $rev );
		}

		$title = Title::newFromLinkTarget( $rev->getPageAsLinkTarget() );

		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $title, [ $rev->getId() ]
		)->setVisibility( [
			'value' => $value,
			'comment' => $comment,
		] );
	}
}

class_alias( 'MediaWikiIntegrationTestCase', 'MediaWikiTestCase' );
