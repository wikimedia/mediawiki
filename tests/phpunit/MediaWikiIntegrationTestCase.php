<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LegacyLogger;
use MediaWiki\Logger\LegacySpi;
use MediaWiki\Logger\LogCapturingSpi;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestResult;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SebastianBergmann\Comparator\ComparisonFailure;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @since 1.18
 *
 * Extend this class if you are testing classes which access global variables, methods, services
 * or a storage backend.
 *
 * Consider using MediaWikiUnitTestCase and mocking dependencies if your code uses dependency
 * injection and does not access any globals.
 *
 * @stable to extend
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
	 * Cloned database
	 *
	 * @var ?CloneDatabase
	 */
	private static $dbClone = null;

	/**
	 * @var array
	 * @since 1.19
	 */
	protected $tablesUsed = []; // tables with data

	private static $useTemporaryTables = true;
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
	 * @var ?HashConfig
	 */
	private $overriddenConfig = null;

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
	 * @stable to call
	 * @param string|null $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->backupGlobals = false;
		$this->backupStaticAttributes = false;
	}

	private static function initializeForStandardPhpunitEntrypointIfNeeded() {
		if ( defined( 'MW_PHPUNIT_UNIT' ) ) {
			$IP = realpath( __DIR__ . '/../..' );
			TestSetup::requireOnceInGlobalScope( "$IP/includes/Defines.php" );
			TestSetup::requireOnceInGlobalScope( "$IP/includes/GlobalFunctions.php" );
			TestSetup::requireOnceInGlobalScope( "$IP/includes/Setup.php" );
			TestSetup::requireOnceInGlobalScope( "$IP/tests/common/TestsAutoLoader.php" );
			TestSetup::applyInitialConfig();
		}
	}

	/**
	 * The annotation causes this to be called immediately before setUpBeforeClass()
	 * @beforeClass
	 */
	final public static function mediaWikiSetUpBeforeClass(): void {
		$settingsFile = wfDetectLocalSettingsFile();
		if ( !is_file( $settingsFile ) ) {
				echo "The file $settingsFile could not be found. "
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
		return static::getTestUser( [ 'sysop', 'bureaucrat' ] );
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
			$user = static::getTestSysop()->getUser();
			$page->doUserEditContent(
				ContentHandler::makeContent(
					'UTContent',
					$title,
					// Regardless of how the wiki is configure or what extensions are present,
					// force this page to be a wikitext one.
					CONTENT_MODEL_WIKITEXT
				),
				$user,
				'UTPageSummary',
				EDIT_NEW | EDIT_SUPPRESS_RC
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
		$wikiPageFactory = MediaWikiServices::getInstance()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		if ( $page->exists() ) {
			$this->deletePage( $page );
		}

		return $page;
	}

	/**
	 * Determine config overrides, taking into account the local system's actual settings and
	 * avoiding interference with any custom overrides.
	 *
	 * @param Config|null $customOverrides Custom overrides that should take precedence
	 *        over the default overrides. Settings from $customOverrides that conflict
	 *        with a default override will replace that default override.
	 * @param Config|null $baseConfig Used to get the baseline value for settings.
	 *        This is used when the override should only affect part of a setting
	 *        that contains a complex structure, such ObjectCaches.
	 *        If not given, the original main config will be used.
	 *        The base config will not be used as a fallback for config keys that are
	 *        not overwritten, it is only used to determine values of keys that are
	 *        overwritten.
	 *
	 * @return array Config overrides
	 */
	public static function getConfigOverrides(
		Config $customOverrides = null,
		Config $baseConfig = null
	): array {
		$overrides = [];

		if ( !$baseConfig ) {
			if ( self::$originalServices ) {
				$baseConfig = self::$originalServices->getMainConfig();
			} else {
				$baseConfig = MediaWikiServices::getInstance()->getMainConfig();
			}
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
				'UTCache' => $hashCache,
			] + $baseConfig->get( MainConfigNames::ObjectCaches );

		// Use hash based caches
		$overrides[ MainConfigNames::ObjectCaches ] = $objectCaches;

		// Use a hash based BagOStuff as the main cache
		$overrides[ MainConfigNames::MainCacheType ] = CACHE_HASH;

		// Don't actually store jobs
		$overrides[ MainConfigNames::JobTypeConf ] = [ 'default' => [ 'class' => JobQueueMemory::class ] ];

		// Use a fast hash algorithm to hash passwords.
		$overrides[ MainConfigNames::PasswordDefault ] = 'A';

		// Since $overrides would shadow entries in $customOverrides, copy any
		// conflicting entries from $customOverrides into $overrides.
		// Later, $overrides and $customOverrides will be combined in a MultiConfig.
		// If $customOverrides was an IterableConfig, we wouldn't need to do that,
		// we could just copy it entirely into $overrides.
		if ( $customOverrides ) {
			foreach ( $overrides as $key => $dummy ) {
				if ( $customOverrides->has( $key ) ) {
					$overrides[ $key ] = $customOverrides->get( $key );
				}
			}
		}

		return $overrides;
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
		return static function ( MediaWikiServices $services ) use ( $oldFactory, $configurations ) {
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

		$jobQueueFactory = MediaWikiServices::getInstance()->getJobQueueGroupFactory();

		foreach ( $wgJobClasses as $type => $class ) {
			$jobQueueFactory->makeJobQueueGroup()->get( $type )->delete();
		}

		ObjectCache::clear();
		DeferredUpdates::clearPendingUpdates();

		// TODO: move global state into MediaWikiServices
		RequestContext::resetMain();
		if ( session_id() !== '' ) {
			session_write_close();
			session_id( '' );
		}

		$wgRequest = RequestContext::getMain()->getRequest();
		MediaWiki\Session\SessionManager::resetCache();
	}

	public function run( TestResult $result = null ): TestResult {
		if ( $result === null ) {
			$result = $this->createResult();
		}

		try {
			$this->overrideMwServices();

			if ( $this->needsDB() && !$this->isTestInDatabaseGroup() ) {
				throw new LogicException(
					get_class( $this ) . ' apparently needsDB but is not in the Database group'
				);
			}

			$needsResetDB = false;
			if ( !self::$dbSetup || $this->needsDB() ) {
				// Set up a DB connection for this test to use
				$useTemporaryTables = !$this->getCliArg( 'use-normal-tables' );

				$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
				$this->db = $lb->getConnectionInternal( DB_PRIMARY );

				$this->checkDbIsSupported();

				if ( !self::$dbSetup ) {
					self::setupAllTestDBs(
						$this->db, $this->dbPrefix(), $useTemporaryTables
					);
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
		} catch ( Throwable $e ) {
			$result->stop();
			$result->addError( $this, $e, 0 );

			return $result;
		}

		parent::run( $result );

		try {
			// We don't mind if we override already-overridden services during cleanup
			$this->overriddenServices = [];
			$this->temporaryHookHandlers = [];

			if ( $needsResetDB ) {
				$this->resetDB( $this->db, $this->tablesUsed );
			}

			self::restoreMwServices();
			$this->localServices = null;
		} catch ( Throwable $e ) {
			$result->stop();
			$result->addError( $this, $e, 0 );
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	private function oncePerClass() {
		// Remember current test class in the database connection,
		// so we know when we need to run addData.

		$class = static::class;

		$hasDataForTestClass = DynamicPropertyTestHelper::getDynamicProperty( $this->db, 'hasDataForTestClass' );

		$first = $hasDataForTestClass !== $class;

		DynamicPropertyTestHelper::setDynamicProperty( $this->db, 'hasDataForTestClass', $class );
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
	final protected function mediaWikiSetUp(): void {
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
		}

		// Reset all caches between tests.
		self::resetNonServiceCaches();

		// T46192 Do not attempt to send a real e-mail
		$this->setTemporaryHook( 'AlternateUserMailer',
			static function () {
				return false;
			}
		);
		ob_start( 'MediaWikiIntegrationTestCase::wfResetOutputBuffersBarrier' );
	}

	protected function addTmpFiles( $files ) {
		$this->tmpFiles = array_merge( $this->tmpFiles, (array)$files );
	}

	/**
	 * The annotation causes this to be called immediately after tearDown()
	 * @after
	 */
	final protected function mediaWikiTearDown(): void {
		global $wgRequest;

		$status = ob_get_status();
		if ( isset( $status['name'] ) &&
			$status['name'] === 'MediaWikiIntegrationTestCase::wfResetOutputBuffersBarrier'
		) {
			ob_end_flush();
		}

		if ( $this->needsDB() && $this->db ) {
			// Clean up open transactions
			while ( $this->db->trxLevel() > 0 ) {
				$this->db->rollback( __METHOD__, 'flush' );
			}
		}

		// Clear any cached test users so they don't retain references to old services
		TestUserRegistry::clear();

		// Restore config
		if ( $this->overriddenConfig ) {
			$this->overriddenConfig->clear();
		}

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

		// Cleaning up temporary files - after logger, if temp files used there
		foreach ( $this->tmpFiles as $fileName ) {
			if ( is_file( $fileName ) || ( is_link( $fileName ) ) ) {
				unlink( $fileName );
			} elseif ( is_dir( $fileName ) ) {
				wfRecursiveRemoveDir( $fileName );
			}
		}

		// TODO: move global state into MediaWikiServices
		RequestContext::resetMain();
		if ( session_id() !== '' ) {
			session_write_close();
			session_id( '' );
		}
		$wgRequest = RequestContext::getMain()->getRequest();
		MediaWiki\Session\SessionManager::resetCache();

		// If anything changed the content language, we need to
		// reset the SpecialPageFactory.
		MediaWikiServices::getInstance()->resetServiceForTesting(
			'SpecialPageFactory'
		);
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
	 * Get a configuration variable
	 *
	 * @param string $name
	 * @return mixed
	 * @since 1.38
	 */
	protected function getConfVar( $name ) {
		return $this->getServiceContainer()->getMainConfig()->get( $name );
	}

	/**
	 * Sets a service, maintaining a stashed version of the previous service to be
	 * restored in tearDown.
	 *
	 * @note This calls resetServices() in case any other services depend on the set service(s).
	 *
	 * @param string $name
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object|callable $service The service instance, or a callable that returns the service instance.
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
			$instantiator = static function () use ( $service ) {
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
	 * @note Since 1.39, use overrideConfigValue() to override configuration.
	 *       Since then, setMwGlobals() should only be used for the rare case of global variables
	 *       that are not configuration.
	 *
	 * @param array|string $pairs Key to the global variable, or an array
	 *  of key/value pairs.
	 * @param mixed|null $value Value to set the global to (ignored
	 *  if an array is given as first argument).
	 *
	 * @since 1.21
	 */
	protected function setMwGlobals( $pairs, $value = null ) {
		if ( is_string( $pairs ) ) {
			$pairs = [ $pairs => $value ];
		}

		$this->stashMwGlobals( array_keys( $pairs ) );

		foreach ( $pairs as $key => $value ) {
			$GLOBALS[$key] = $value;
		}

		$this->resetServices();
	}

	/**
	 * Overrides a config setting for the duration of the current test case.
	 * The original value of the config setting will be restored after the test case finishes.
	 *
	 * @note This will cause any existing service instances to be reset.
	 *
	 * @see setMwGlobals
	 * @see \MediaWiki\Settings\SettingsBuilder::overrideConfigValue
	 *
	 * @par Example
	 * @code
	 *     protected function setUp() : void {
	 *         parent::setUp();
	 *         $this->overrideConfigValue( MainConfigNames::RestrictStuff, true );
	 *     }
	 *
	 *     function testFoo() {}
	 *
	 *     function testBar() {}
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *
	 *         $this->overrideConfigValue( MainConfigNames::RestrictStuff, false );
	 *         $this->assertTrue( self::getX()->doStuff() );
	 *     }
	 *
	 *     function testQuux() {}
	 * @endcode
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @since 1.39
	 */
	protected function overrideConfigValue( string $key, $value ) {
		$this->overriddenConfig->set( $key, $value );

		// When nothing reads config from globals anymore, we will no longer need to call
		// setMwGlobals() here.
		$this->setMwGlobals( "wg$key", $value );
	}

	/**
	 * Set the main object cache that will be returned by ObjectCache::getLocalClusterInstance().
	 *
	 * Per default, the main object cache is disabled during testing (that is, the cache is an
	 * EmptyBagOStuff).
	 *
	 * The $cache parameter support the following kinds of values:
	 * - a string: refers to an entry in the ObjectCaches array, see MainConfigSchema::ObjectCaches.
	 *   MainCacheType will be set to this value. Use CACHE_HASH to use a HashBagOStuff.
	 * - an int: refers to an entry in the ObjectCaches array, see MainConfigSchema::ObjectCaches.
	 *   MainCacheType will be set to this value. Use CACHE_NONE to disable caching.
	 * - a BagOStuff: the object will be injected into the ObjectCache class under the name
	 *   'UTCache', and MainCacheType will be set to 'UTCache'.
	 *
	 * @note Most entries in the ObjectCaches config setting are overwritten during testing.
	 *       To set the cache to anything other than CACHE_HASH, you will have to override
	 *       the ObjectCaches setting first.
	 *
	 * @note This will cause any existing service instances to be reset.
	 *
	 * @param string|BagOStuff $cache
	 *
	 * @return string|int The new value of the MainCacheType setting.
	 */
	protected function setMainCache( $cache ) {
		if ( $cache instanceof BagOStuff ) {
			// ObjectCache::$instances is reset after each test by resetNonGlobalServices().
			ObjectCache::$instances[ 'UTCache' ] = $cache;
			$cache = 'UTCache';
		}

		if ( !is_string( $cache ) && !is_int( $cache ) ) {
			throw new InvalidArgumentException( 'Bad type of $cache parameter: ' . get_debug_type( $cache ) );
		}

		$this->overrideConfigValue( MainConfigNames::MainCacheType, $cache );
		return $cache;
	}

	/**
	 * Overrides a set of config settings for the duration of the current test case.
	 * The original values of the config settings will be restored after the test case finishes.
	 *
	 * @note This will cause any existing service instances to be reset.
	 *
	 * @see setMwGlobals
	 * @see \MediaWiki\Settings\SettingsBuilder::overrideConfigValues
	 *
	 * @param array<string,mixed> $values
	 *
	 * @since 1.39
	 */
	protected function overrideConfigValues( array $values ) {
		$vars = [];

		foreach ( $values as $key => $value ) {
			$this->overriddenConfig->set( $key, $value );
			$var = "wg$key";
			$vars[$var] = $value;
		}

		// When nothing reads config from globals anymore, we will no longer need to call
		// setMwGlobals() here.
		$this->setMwGlobals( $vars );
	}

	/**
	 * Set the global request in the two places it is stored.
	 * @param WebRequest $request
	 * @since 1.36
	 */
	protected function setRequest( $request ) {
		global $wgRequest;
		// It's not necessary to stash the value with setMwGlobals(), since
		// it's reset on teardown anyway.
		$wgRequest = $request;
		RequestContext::getMain()->setRequest( $request );
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

	/**
	 * Stash the values of globals which the test is going to modify.
	 * Stashed values will be restored on tear down.
	 *
	 * @since 1.38
	 * @param string[] $globalKeys
	 */
	protected function stashMwGlobals( $globalKeys ) {
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
	 * variables. It is called by setMwGlobals/overrideConfigValues
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
	 * @param Config|null $customOverrides Custom configuration overrides for the new MediaWikiServices
	 *        instance.
	 * @param callable[] $services An associative array of services to re-define. Keys are service
	 *        names, values are callables.
	 *
	 * @return MediaWikiServices
	 * @throws MWException
	 * @since 1.27
	 */
	protected function overrideMwServices(
		Config $customOverrides = null, array $services = []
	) {
		if ( $this->overriddenServices ) {
			throw new MWException(
				'The following services were set and are now being unset by overrideMwServices: ' .
					implode( ', ', $this->overriddenServices )
			);
		}

		$this->overriddenConfig = new HashConfig();

		// Create the Config object that will be stacked on top of the real bootstrap config
		// to create the config that will be used as the bootstrap as well as the main config
		// by the service container.
		// overrideConfigValue() will write to $this->overriddenConfig later and reset
		// services as appropriate.
		// Make sure that $this->overriddenConfig is the top layer of overrides.
		// XXX: If $customOverrides was guaranteed to be an IterableConfig, this could be
		//      simplified by making getConfigOverrides() copy it into the $configOverrides array.
		//      Or we could just get rid of $customOverrides.
		if ( $customOverrides ) {
			$serviceConfig = new MultiConfig( [ $this->overriddenConfig, $customOverrides ] );
		} else {
			$serviceConfig = $this->overriddenConfig;
		}

		// NOTE: $serviceConfig doesn't have the overrides yet, they will be added
		//       by calling overrideConfigValues() below.
		$newInstance = self::installMockMwServices( $serviceConfig );

		if ( $this->localServices ) {
			$this->localServices->destroy();
		}

		$this->localServices = $newInstance;

		// Determine the config overrides that should apply during testing.
		$configOverrides = self::getConfigOverrides( $customOverrides );

		$this->overrideConfigValues( $configOverrides );

		foreach ( $services as $name => $callback ) {
			$newInstance->redefineService( $name, $callback );
		}

		self::resetLegacyGlobals();

		return $newInstance;
	}

	/**
	 * Creates a new "mock" MediaWikiServices instance, and installs it.
	 * This effectively resets all cached states in services, with the exception of
	 * the ConfigFactory and the DBLoadBalancerFactory service, which are inherited from
	 * the original MediaWikiServices.
	 *
	 * @warning This method interacts with global state in a complex way. There should
	 * generally be no need to call it directly. Subclasses should use more specific methods
	 * like setService() or overrideConfigValues() instead.
	 *
	 * @note The new original MediaWikiServices instance can later be restored by calling
	 * restoreMwServices(). That original is determined by the first call to this method, or
	 * by setUpBeforeClass, whichever is called first. The caller is responsible for managing
	 * and, when appropriate, destroying any other MediaWikiServices instances that may get
	 * replaced when calling this method.
	 *
	 * @param Config|array|null $configOverrides Configuration overrides for the new
	 *        MediaWikiServices instance. Should be constructed by calling getConfigOverrides(),
	 *        to ensure that the configuration is safe for testing.
	 *
	 * @return MediaWikiServices the new mock service locator.
	 */
	public static function installMockMwServices( $configOverrides = null ) {
		// Make sure we have the original service locator
		if ( !self::$originalServices ) {
			self::$originalServices = MediaWikiServices::getInstance();
		}

		if ( $configOverrides === null ) {
			// Only use the default overrides if $configOverrides is not given.
			// Don't try to be smart and combine the custom overrides with the default overrides.
			// This gives overrideMwServices() full control over the configuration when it calls
			// this method.
			$configOverrides = self::getConfigOverrides();
		}

		if ( is_array( $configOverrides ) ) {
			$configOverrides = new HashConfig( $configOverrides );
		}

		// (T247990) Cache the original service wirings to work around a memory leak on PHP 7.4 and above
		if ( !self::$originalServiceWirings ) {
			$serviceWiringFiles = self::$originalServices->getBootstrapConfig()->get( MainConfigNames::ServiceWiringFiles );

			foreach ( $serviceWiringFiles as $wiringFile ) {
				self::$originalServiceWirings[] = require $wiringFile;
			}
		}

		$oldConfigFactory = self::$originalServices->getConfigFactory();
		$oldLoadBalancerFactory = self::$originalServices->getDBLoadBalancerFactory();

		$originalConfig = self::$originalServices->getBootstrapConfig();
		$testConfig = new MultiConfig( [ $configOverrides, $originalConfig ] );

		$newServices = new MediaWikiServices( $testConfig );

		// Load the default wiring from the specified files.
		// NOTE: this logic mirrors the logic in MediaWikiServices::newInstance
		if ( $configOverrides && $configOverrides->has( MainConfigNames::ServiceWiringFiles ) ) {
			$wiringFiles = $configOverrides->get( MainConfigNames::ServiceWiringFiles );
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
		$newServices->resetServiceForTesting( 'LocalServerObjectCache' );
		$newServices->redefineService(
			'LocalServerObjectCache',
			static function ( MediaWikiServices $services ) {
				return ObjectCache::getInstance( 'hash' );
			}
		);
		$newServices->resetServiceForTesting( 'DBLoadBalancerFactory' );
		$newServices->redefineService(
			'DBLoadBalancerFactory',
			static function ( MediaWikiServices $services ) use ( $oldLoadBalancerFactory ) {
				return $oldLoadBalancerFactory;
			}
		);

		// Prevent real HTTP requests from tests
		$newServices->resetServiceForTesting( 'HttpRequestFactory' );
		$newServices->redefineService(
			'HttpRequestFactory',
			static function ( MediaWikiServices $services ) {
				return new NullHttpRequestFactory();
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

	private static function resetLegacyGlobals() {
		ParserOptions::clearStaticCache();
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
	 * @deprecated since 1.35. To change the site language, use overrideConfigValue( 'LanguageCode' ),
	 *   which will also reset the service. If you want to set the service to a specific object
	 *   (like a mock), use setService( 'ContentLanguage' ).
	 * @since 1.27
	 * @param string|Language $lang
	 */
	public function setContentLang( $lang ) {
		if ( $lang instanceof Language ) {
			// Set to the exact object requested
			$this->setService( 'ContentLanguage', $lang );
			$this->overrideConfigValue( MainConfigNames::LanguageCode, $lang->getCode() );
		} else {
			$this->overrideConfigValue( MainConfigNames::LanguageCode, $lang );
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
		if ( is_string( $newPerms ) ) {
			$newPerms = [ $newPerms => [ $newKey => $newValue ] ];
		}

		$newPermissions = $this->getServiceContainer()->getMainConfig()
			->get( MainConfigNames::GroupPermissions );

		foreach ( $newPerms as $group => $permissions ) {
			foreach ( $permissions as $key => $value ) {
				$newPermissions[$group][$key] = $value;
			}
		}

		$this->overrideConfigValue( MainConfigNames::GroupPermissions, $newPermissions );
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
		$page->doUserEditContent( ContentHandler::makeContent( $text, $title ), $user, $comment );

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
	 * @stable to override
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
	 * @stable to override
	 */
	public function addDBData() {
	}

	/**
	 * @since 1.32
	 */
	protected function addCoreDBData() {
		SiteStatsInit::doPlaceholderInit();

		// Make sysop user
		$user = static::getTestSysop()->getUser();

		// Make 1 page with 1 revision
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		if ( $page->getId() == 0 ) {
			$page->doUserEditContent(
				new WikitextContent( 'UTContent' ),
				$user,
				'UTPageSummary',
				EDIT_NEW | EDIT_SUPPRESS_RC
			);
			// an edit always attempt to purge backlink links such as history
			// pages. That is unnecessary.
			$jobQueueGroup = MediaWikiServices::getInstance()->getJobQueueGroup();
			$jobQueueGroup->get( 'htmlCacheUpdate' )->delete();
			// WikiPages::doEditUpdates randomly adds RC purges
			$jobQueueGroup->get( 'recentChangesUpdate' )->delete();

			// doUserEditContent() probably started the session via
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

		$services = MediaWikiServices::getInstance();
		$jobQueueGroup = $services->getJobQueueGroup();
		foreach ( $wgJobClasses as $type => $class ) {
			// Delete any jobs under the clone DB (or old prefix in other stores)
			$jobQueueGroup->get( $type )->delete();
		}

		if ( self::$dbClone ) {
			self::$dbClone->destroy( true );
			self::$dbClone = null;
		}

		// T219673: close any connections from code that failed to call reuseConnection()
		// or is still holding onto a DBConnRef instance (e.g. in a singleton).
		$services->getDBLoadBalancerFactory()->closeAll( __METHOD__ );
		CloneDatabase::changePrefix( self::$oldTablePrefix );

		self::$oldTablePrefix = false;
		self::$dbSetup = false;
	}

	/**
	 * Setups a database with cloned tables using the given prefix.
	 *
	 * @param IMaintainableDatabase $db Database to use
	 * @param string|null $prefix Prefix to use for test tables. If not given, the prefix is determined
	 *   automatically for $db.
	 * @return CloneDatabase|null A CloneDatabase object if tables were cloned,
	 *   or null if the connection has already had its tables cloned.
	 */
	protected static function setupDatabaseWithTestPrefix(
		IMaintainableDatabase $db,
		$prefix = null
	) {
		if ( $prefix === null ) {
			$prefix = self::getTestPrefixFor( $db );
		}

		$originalTablePrefix = DynamicPropertyTestHelper::getDynamicProperty( $db, 'originalTablePrefix' );

		if ( $originalTablePrefix !== null ) {
			return null;
		}

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
		DynamicPropertyTestHelper::setDynamicProperty( $db, 'originalTablePrefix', $oldPrefix );

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$lb->setTempTablesOnlyMode( self::$useTemporaryTables, $db->getDomainID() );
		return $dbClone;
	}

	public static function setupAllTestDBs( $db, ?string $testPrefix = null, ?bool $useTemporaryTables = null ) {
		global $wgDBprefix;

		self::$oldTablePrefix = $wgDBprefix;

		$testPrefix = $testPrefix ?? self::getTestPrefixFor( $db );

		// switch to a temporary clone of the database
		self::$useTemporaryTables = $useTemporaryTables ?? self::$useTemporaryTables;
		self::setupTestDB( $db, $testPrefix );

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

		$dbClone = self::setupDatabaseWithTestPrefix( $db, $prefix );
		if ( $dbClone ) {
			self::$dbClone = $dbClone;
		}

		Hooks::runner()->onUnitTestsAfterDatabaseSetup( $db, $prefix );

		self::$dbSetup = true;
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
	 * Gets primary database connections for all of the ExternalStoreDB
	 * stores configured in $wgDefaultExternalStore.
	 *
	 * @return Database[] Array of Database primary connections
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
				// Avoid getPrimary() because setupDatabaseWithTestPrefix()
				// requires Database instead of plain DBConnRef/IDatabase
				$dbws[] = $externalStoreDB->getPrimary( $cluster );
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
				"Trying to delete mock tables, but table prefix '{$db->tablePrefix()}' " .
				"does not indicate a mock database (expected '{$this->dbPrefix()}')"
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
	 * @stable to override
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
	 * Undoes the specified schema overrides.
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
	 * @param IMaintainableDatabase $db
	 */
	private function setUpSchema( IMaintainableDatabase $db ) {
		// Undo any active overrides.
		$oldOverrides = DynamicPropertyTestHelper::getDynamicProperty( $db, 'activeSchemaOverrides' ) ?? self::$schemaOverrideDefaults;

		if ( $oldOverrides['alter'] || $oldOverrides['create'] || $oldOverrides['drop'] ) {
			$this->undoSchemaOverrides( $db, $oldOverrides );
			DynamicPropertyTestHelper::unsetDynamicProperty( $db, 'activeSchemaOverrides' );
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

		DynamicPropertyTestHelper::setDynamicProperty( $db, 'activeSchemaOverrides', $overrides );
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
		$originalTablePrefix = DynamicPropertyTestHelper::getDynamicProperty( $db, 'originalTablePrefix' );
		if ( $originalTablePrefix === null ) {
			throw new LogicException( 'No original table prefix know, cannot list tables!' );
		}

		$originalTables = $db->listTables( $originalTablePrefix, __METHOD__ );

		$unittestPrefixRegex = '/^' . preg_quote( $this->dbPrefix(), '/' ) . '/';
		$originalPrefixRegex = '/^' . preg_quote( $originalTablePrefix, '/' ) . '/';

		$originalTables = array_filter(
			$originalTables,
			static function ( $pt ) use ( $unittestPrefixRegex ) {
				return !preg_match( $unittestPrefixRegex, $pt );
			}
		);

		$originalTables = array_map(
			static function ( $pt ) use ( $originalPrefixRegex ) {
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

		$originalTablePrefix = DynamicPropertyTestHelper::getDynamicProperty( $db, 'originalTablePrefix' );

		if ( $originalTablePrefix === null ) {
			throw new LogicException( 'No original table prefix know, cannot restore tables!' );
		}

		$originalTables = $this->listOriginalTables( $db );
		$tables = array_intersect( $tables, $originalTables );

		self::$dbClone = new CloneDatabase( $db, $tables, $db->tablePrefix(), $originalTablePrefix );
		self::$dbClone->useTemporaryTables( self::$useTemporaryTables );
		self::$dbClone->cloneTableStructure();

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$lb->setTempTablesOnlyMode( self::$useTemporaryTables, $db->getDomainID() );
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
					'slots', 'content', 'content_models', 'slot_roles',
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

				// Reset context user, which is probably 127.0.0.1, as its loaded
				// data is probably not valid. This used to manipulate $wgUser but
				// since that is deprecated tests are more likely to be relying on
				// RequestContext::getMain() instead.
				// @todo Should we start setting the user to something nondeterministic
				//  to encourage tests to be updated to not depend on it?
				$user = RequestContext::getMain()->getUser();
				$user->clearInstanceCache( $user->mFrom );
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
		return strpos( $table, self::DB_PREFIX ) !== 0 &&
			strpos( $table, ParserTestRunner::DB_PREFIX ) !== 0;
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
	 * @param IMaintainableDatabase $source
	 * @param IMaintainableDatabase $target
	 */
	public function copyTestData( IMaintainableDatabase $source, IMaintainableDatabase $target ) {
		if ( $this->db->getType() === 'sqlite' ) {
			// SQLite uses a non-temporary copy of the searchindex table for testing,
			// which gets deleted and re-created when setting up the secondary connection,
			// causing "Error 17" when trying to copy the data. See T191863#4130112.
			throw new RuntimeException(
				'Setting up a secondary database connection with test data is currently not supported'
				. ' with SQLite. You may want to use markTestSkippedIfDbType() to bypass this issue.'
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
		$this->assertNotFalse( $res, "query failed: " . $db->lastError() );

		$i = 0;

		foreach ( $expectedRows as $expected ) {
			$r = $res->fetchRow();
			self::stripStringKeys( $r );

			$i += 1;
			$this->assertNotFalse( $r, "row #$i missing" );

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
			static function ( $element ) {
				return [ $element ];
			},
			$elements
		);
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

		$talk = array_filter( $namespaces, static function ( $ns ) use ( $nsInfo ) {
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

		// This check may also protect against code injection in
		// case of broken installations.
		$haveDiff3 = $wgDiff3 && @is_file( $wgDiff3 );
		if ( !$haveDiff3 ) {
			$this->markTestSkipped( "Skip test, since diff3 is not configured" );
		}
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
	 * Skip the test if the specified extension is not loaded.
	 *
	 * @note Core tests should not depend on extensions, so this is mostly
	 * useful when testing extensions that optionally depend on other extensions.
	 *
	 * @param string $extensionName
	 * @since 1.37
	 */
	protected function markTestSkippedIfExtensionNotLoaded( string $extensionName ) {
		if ( !ExtensionRegistry::getInstance()->isLoaded( $extensionName ) ) {
			$this->markTestSkipped( "Extension $extensionName is required for this test" );
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
	 * @param string $hookName
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
	 * @param string|PageIdentity|LinkTarget|WikiPage $page the page to edit
	 * @param string|Content $content the new content of the page
	 * @param string $summary Optional summary string for the revision
	 * @param int $defaultNs Optional namespace id
	 * @param Authority|null $performer If null, static::getTestUser()->getAuthority() is used.
	 * @return Status Object as returned by WikiPage::doUserEditContent()
	 * @throws MWException If this test cases's needsDB() method doesn't return true.
	 *         Test cases can use "@group Database" to enable database test support,
	 *         or list the tables under testing in $this->tablesUsed, or override the
	 *         needsDB() method.
	 */
	protected function editPage(
		$page,
		$content,
		$summary = '',
		$defaultNs = NS_MAIN,
		Authority $performer = null
	) {
		if ( !$this->needsDB() ) {
			throw new MWException( 'When testing with pages, the test cases\'s needsDB()' .
				' method should return true. Use @group Database or $this->tablesUsed.' );
		}

		$services = $this->getServiceContainer();
		if ( $page instanceof WikiPage ) {
			$title = $page->getTitle();
		} elseif ( $page instanceof PageIdentity ) {
			$page = $services->getWikiPageFactory()->newFromTitle( $page );
			$title = $page->getTitle();
		} elseif ( $page instanceof LinkTarget ) {
			$page = $services->getWikiPageFactory()->newFromLinkTarget( $page );
			$title = $page->getTitle();
		} else {
			$title = $services->getTitleFactory()->newFromText( $page, $defaultNs );
			$page = $services->getWikiPageFactory()->newFromTitle( $title );
		}

		if ( $performer === null ) {
			$performer = static::getTestUser()->getAuthority();
		}

		if ( is_string( $content ) ) {
			$content = $services->getContentHandlerFactory()
				->getContentHandler( $title->getContentModel() )
				->unserializeContent( $content );
		}

		return $page->doUserEditContent(
			$content,
			$performer,
			$summary
		);
	}

	/**
	 * @param ProperPageIdentity $page
	 * @param string $summary
	 * @param Authority|null $deleter
	 */
	protected function deletePage( ProperPageIdentity $page, string $summary = '', Authority $deleter = null ): void {
		$deleter = $deleter ?? new UltimateAuthority( new UserIdentityValue( 0, 'MediaWiki default' ) );
		MediaWikiServices::getInstance()->getDeletePageFactory()
			->newDeletePage( $page, $deleter )
			->deleteUnsafe( $summary );
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

		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $rev->getPage(), [ $rev->getId() ]
		)->setVisibility( [
			'value' => $value,
			'comment' => $comment,
		] );
	}

	/**
	 * Run jobs in the job queue and assert things about the result.
	 *
	 * Call this from a test to run jobs. If this is not called, the default
	 * behaviour is to discard jobs.
	 *
	 * @param array $assertOptions An associative array with the following options:
	 *    - minJobs: The minimum number of jobs expected to be run, default 1
	 *    - numJobs: The exact number of jobs expected to be run. If set, this
	 *      overrides minJobs.
	 *    - complete: Assert that the runner finished with "none-ready", which
	 *      means execution stopped because the queue was empty. Default true.
	 *    - ignoreErrorsMatchingFormat: Allow job errors where the error message
	 *      matches the given format.
	 * @param array $runOptions Options to pass through to JobRunner::run()
	 *
	 * @since 1.37
	 */
	protected function runJobs( array $assertOptions = [], array $runOptions = [] ) {
		$runner = $this->getServiceContainer()->getJobRunner();
		$status = $runner->run( $runOptions );

		$minJobs = $assertOptions['minJobs'] ?? 1;
		$numJobs = $assertOptions['numJobs'] ?? null;
		$complete = $assertOptions['complete'] ?? true;
		$ignoreFormat = $assertOptions['ignoreErrorsMatchingFormat'] ?? false;

		if ( $complete ) {
			$this->assertSame( 'none-ready', $status['reached'] );
		}
		if ( $numJobs !== null ) {
			$this->assertCount( $numJobs, $status['jobs'],
				"Number of jobs executed must be exactly $numJobs" );
		} else {
			$this->assertGreaterThanOrEqual( $minJobs, count( $status['jobs'] ),
				"Number of jobs executed must be at least $minJobs" );
		}
		foreach ( $status['jobs'] as $jobStatus ) {
			if ( $ignoreFormat !== false ) {
				$this->assertThat( $jobStatus['error'],
					$this->logicalOr(
						$this->isNull(),
						$this->matches( $ignoreFormat )
					),
					"Error for job of type {$jobStatus['type']}"
				);
			} else {
				$this->assertNull( $jobStatus['error'],
					"Error for job of type {$jobStatus['type']}" );
			}
		}
	}
}
