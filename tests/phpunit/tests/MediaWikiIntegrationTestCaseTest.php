<?php

use MediaWiki\Content\TextContent;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWikiIntegrationTestCase
 * @group MediaWikiIntegrationTestCaseTest
 * @group Database
 * @author Addshore
 */
class MediaWikiIntegrationTestCaseTest extends MediaWikiIntegrationTestCase {

	private const START_GLOBALS = [
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingString' => 'foo',
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingStringEmpty' => '',
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingArray' => [ 1, 'foo' => 'bar' ],
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingArrayEmpty' => [],
	];

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		foreach ( self::START_GLOBALS as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
	}

	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();
		foreach ( self::START_GLOBALS as $key => $value ) {
			unset( $GLOBALS[$key] );
		}
	}

	public static function provideExistingKeysAndNewValues() {
		$providedArray = [];
		foreach ( self::START_GLOBALS as $key => $_ ) {
			$providedArray[] = [ $key, 'newValue' ];
			$providedArray[] = [ $key, [ 'newValue' ] ];
		}
		return $providedArray;
	}

	/**
	 * @dataProvider provideExistingKeysAndNewValues
	 */
	public function testSetGlobalsAreRestoredOnTearDown__before( $globalKey, $newValue ) {
		$this->setMwGlobals( $globalKey, $newValue );
		$this->assertEquals(
			$newValue,
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);
	}

	/**
	 * @note This cannot use depends because the other test also uses a data provider.
	 * @dataProvider provideExistingKeysAndNewValues
	 */
	public function testSetGlobalsAreRestoredOnTearDown__after( $globalKey ) {
		$this->assertSame(
			self::START_GLOBALS[$globalKey],
			$GLOBALS[$globalKey],
			'Global failed to be restored on tearDown'
		);
	}

	public function testObjectCache() {
		$this->assertSame( 'hash', $this->getConfVar( MainConfigNames::MainCacheType ) );

		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getLocalClusterInstance() );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getLocalServerInstance() );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_ANYTHING ) );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_ACCEL ) );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_DB ) );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_MEMCACHED ) );

		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getLocalServerObjectCache() );
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()->getMainObjectStash() );
	}

	public function testSetNonExistentGlobalsAreUnsetOnTearDown__before() {
		$globalKey = 'abcdefg1234567';
		$this->setMwGlobals( $globalKey, true );
		$this->assertTrue(
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);
		return $globalKey;
	}

	/**
	 * @depends testSetNonExistentGlobalsAreUnsetOnTearDown__before
	 */
	public function testSetNonExistentGlobalsAreUnsetOnTearDown__after( string $globalKey ) {
		$this->assertFalse(
			isset( $GLOBALS[$globalKey] ),
			'Global failed to be correctly unset'
		);
	}

	public function testOverrideConfigValues__before() {
		$nsInfo1 = $this->getServiceContainer()->getNamespaceInfo();

		$oldSitename = $this->getConfVar( MainConfigNames::Sitename );

		$this->overrideConfigValue( MainConfigNames::Sitename, 'TestingSitenameOverride' );
		$nsInfo2 = $this->getServiceContainer()->getNamespaceInfo();

		$this->overrideConfigValues( [ 'TestDummyConfig4556' => 'TestDummyConfigOverride' ] );
		$nsInfo3 = $this->getServiceContainer()->getNamespaceInfo();

		$this->assertNotSame( $nsInfo1, $nsInfo2, 'Service instances should have been reset' );
		$this->assertNotSame( $nsInfo2, $nsInfo3, 'Service instances should have been reset' );

		$config = $this->getServiceContainer()->getMainConfig();
		$fakeConfigKey = 'TestDummyConfig4556';
		$this->assertSame( 'TestingSitenameOverride', $config->get( MainConfigNames::Sitename ) );
		$this->assertSame( 'TestDummyConfigOverride', $config->get( $fakeConfigKey ) );

		return [ $oldSitename, $fakeConfigKey ];
	}

	/**
	 * @depends testOverrideConfigValues__before
	 */
	public function testOverrideConfigValues__after( array $data ) {
		[ $oldSitename, $fakeConfigKey ] = $data;
		$config = $this->getServiceContainer()->getMainConfig();
		$this->assertSame(
			$oldSitename,
			$config->get( MainConfigNames::Sitename ),
			'Config variable should have been restored'
		);
		$this->assertFalse( $config->has( $fakeConfigKey ), 'Config variable should have been unset' );
	}

	/**
	 * Some configuration variables are overridden when setting up the test environment.
	 * Make sure that these can be overridden consistently.
	 */
	public function testOverrideTestConfig() {
		$config = $this->getServiceContainer()->getMainConfig();

		// Check that default overrides were applied
		$this->assertSame(
			'A',
			$config->get( MainConfigNames::PasswordDefault )
		);

		// Make sure that overrides applied by the test environment are
		// consistent between the main config and global variables.
		$this->assertSame(
			$config->get( MainConfigNames::MainCacheType ),
			$GLOBALS[ 'wgMainCacheType' ]
		);
		$this->assertSame(
			$config->get( MainConfigNames::PasswordDefault ),
			$GLOBALS[ 'wgPasswordDefault' ]
		);
		$this->assertSame(
			$config->get( MainConfigNames::JobTypeConf ),
			$GLOBALS[ 'wgJobTypeConf' ]
		);

		$this->overrideConfigValue( MainConfigNames::JobTypeConf, 'XXX' );
		$config = $this->getServiceContainer()->getMainConfig();
		$this->assertSame( 'XXX', $config->get( MainConfigNames::JobTypeConf ) );
		$this->assertSame( 'XXX', $GLOBALS[ 'wgJobTypeConf' ] );

		$this->overrideConfigValues( [ MainConfigNames::JobTypeConf => 'YYY' ] );
		$config = $this->getServiceContainer()->getMainConfig();
		$this->assertSame( 'YYY', $config->get( MainConfigNames::JobTypeConf ) );
		$this->assertSame( 'YYY', $GLOBALS[ 'wgJobTypeConf' ] );

		$this->setMwGlobals( 'wgJobTypeConf', 'ZZZ' );
		$config = $this->getServiceContainer()->getMainConfig();
		$this->assertSame( 'ZZZ', $GLOBALS[ 'wgJobTypeConf' ] );

		// Values set with overrideConfigValue() take precedence over values set
		// with setMwGlobals().
		$this->assertSame( 'YYY', $config->get( MainConfigNames::JobTypeConf ) );
	}

	public function testSetMainCache() {
		// Cache should be set to a hash by default.
		$this->assertInstanceOf( HashBagOStuff::class, $this->getServiceContainer()
			->getObjectCacheFactory()->getLocalClusterInstance() );

		// Use HashBagOStuff.
		$this->setMainCache( CACHE_HASH );
		$cache = $this->getServiceContainer()->getObjectCacheFactory()->getLocalClusterInstance();
		$this->assertInstanceOf( HashBagOStuff::class, $cache );

		// Install different HashBagOStuff
		$cache = new HashBagOStuff();
		$name = $this->setMainCache( $cache );
		$this->assertSame( $cache, $this->getServiceContainer()->getObjectCacheFactory()->getLocalClusterInstance() );
		$this->assertSame( $cache, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( $name ) );

		// Our custom cache object should not replace an existing entry.
		$this->assertNotSame( $cache, $this->getServiceContainer()->getObjectCacheFactory()->getInstance( CACHE_HASH ) );
		$this->setMainCache( CACHE_HASH );
		$this->assertNotSame( $cache, $this->getServiceContainer()->getObjectCacheFactory()->getLocalClusterInstance() );

		// We should be able to disable the cache.
		$this->assertSame( CACHE_NONE, $this->setMainCache( CACHE_NONE ) );
		$this->assertInstanceOf( EmptyBagOStuff::class, $this->getServiceContainer()
			->getObjectCacheFactory()->getLocalClusterInstance() );
	}

	public function testOverrideMwServices() {
		$initialServices = MediaWikiServices::getInstance();

		$this->overrideMwServices();
		$this->assertNotSame( $initialServices, MediaWikiServices::getInstance() );
	}

	public function testSetService() {
		$initialServices = MediaWikiServices::getInstance();
		$initialService = $initialServices->getDBLoadBalancer();
		$mockService = $this->createMock( LoadBalancer::class );

		$this->setService( 'DBLoadBalancer', $mockService );
		$this->assertNotSame(
			$initialService,
			MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
		$this->assertSame( $mockService, MediaWikiServices::getInstance()->getDBLoadBalancer() );
	}

	public function testLoggersAreRestoredOnTearDown_replacingExistingLogger__before() {
		$oldLogger = LoggerFactory::getInstance( 'foo' );
		$logger = new NullLogger();
		$this->setLogger( 'foo', $logger );
		$overriddenLogger = LoggerFactory::getInstance( 'foo' );
		$this->assertSame( $logger, $overriddenLogger );
		$this->assertNotSame( $oldLogger, $overriddenLogger );
		return $oldLogger;
	}

	/**
	 * @depends testLoggersAreRestoredOnTearDown_replacingExistingLogger__before
	 */
	public function testLoggersAreRestoredOnTearDown_replacingExistingLogger__after( LoggerInterface $mockLogger ) {
		$curLogger = LoggerFactory::getInstance( 'foo' );
		$this->assertNotSame( $mockLogger, $curLogger );
	}

	/**
	 */
	public function testLoggersAreRestoredOnTearDown_replacingNonExistingLogger__before() {
		$logger = new NullLogger();
		$this->setLogger( 'foo', $logger );
		$overriddenLogger = LoggerFactory::getInstance( 'foo' );
		$this->assertSame( $logger, $overriddenLogger );
		return $overriddenLogger;
	}

	/**
	 * @depends testLoggersAreRestoredOnTearDown_replacingNonExistingLogger__before
	 */
	public function testLoggersAreRestoredOnTearDown_replacingNonExistingLogger__after(
		LoggerInterface $overriddenLogger
	) {
		$curLogger = LoggerFactory::getInstance( 'foo' );

		$this->assertNotSame( $overriddenLogger, $curLogger );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $curLogger );
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testLoggersAreRestoredOnTearDown_replacingSameLoggerTwice__before() {
		LoggerFactory::getInstance( 'baz' );
		$this->setLogger( 'foo', new NullLogger() );
		$this->setLogger( 'foo', new NullLogger() );
	}

	/**
	 * @depends testLoggersAreRestoredOnTearDown_replacingSameLoggerTwice__before
	 */
	public function testLoggersAreRestoredOnTearDown_replacingSameLoggerTwice__after() {
		$curLogger = LoggerFactory::getInstance( 'baz' );
		$this->assertNotInstanceOf( MockObject::class, $curLogger );
	}

	public function testNullLogger_createAndRemove__before() {
		$this->setNullLogger( 'tocreate' );
		$logger = LoggerFactory::getInstance( 'tocreate' );
		$this->assertInstanceOf( \Psr\Log\NullLogger::class, $logger );
	}

	/**
	 * @depends testNullLogger_createAndRemove__before
	 */
	public function testNullLogger_createAndRemove__after() {
		$logger = LoggerFactory::getInstance( 'tocreate' );
		// Unwrap from LogCapturingSpi
		$inner = TestingAccessWrapper::newFromObject( $logger )->logger;
		$this->assertInstanceOf( \MediaWiki\Logger\LegacyLogger::class, $inner );
	}

	public function testNullLogger_mutateAndRestore__before() {
		// Don't rely on the $wgDebugLogGroups and $wgDebugLogFile settings in
		// WMF CI to make LEVEL_DEBUG (100) the default. Control this in the test.
		$this->overrideConfigValue( MainConfigNames::DebugToolbar, true );

		$logger = LoggerFactory::getInstance( 'tomutate' );
		// Unwrap from LogCapturingSpi
		$inner = TestingAccessWrapper::newFromObject( $logger )->logger;
		$this->assertInstanceOf( \MediaWiki\Logger\LegacyLogger::class, $inner );
		$this->assertSame(
			100,
			TestingAccessWrapper::newFromObject( $inner )->minimumLevel,
			'original minimumLevel'
		);

		$this->setNullLogger( 'tomutate' );
		$this->assertSame(
			999,
			TestingAccessWrapper::newFromObject( $inner )->minimumLevel,
			'changed minimumLevel'
		);
		return $inner;
	}

	/**
	 * @depends testNullLogger_mutateAndRestore__before
	 */
	public function testNullLogger_mutateAndRestore__after( LoggerInterface $inner ) {
		$this->assertSame(
			100,
			TestingAccessWrapper::newFromObject( $inner )->minimumLevel,
			'restored minimumLevel'
		);
	}

	public function testCopyTestData() {
		// Avoid self-deadlocks with Sqlite
		$this->markTestSkippedIfDbType( 'sqlite' );

		$this->db->newInsertQueryBuilder()
			->insertInto( 'objectcache' )
			->row( [ 'keyname' => __METHOD__, 'value' => 'TEST', 'exptime' => $this->db->timestamp( 11 ) ] )
			->caller( __METHOD__ )
			->execute();

		// Make an untracked DB_PRIMARY connection
		$lb = $this->getServiceContainer()->getDBLoadBalancerFactory()->newMainLB();
		// Need a Database where the DB domain changes during table cloning
		$db = $lb->getConnectionInternal( DB_PRIMARY );

		$this->assertNotSame( $this->db, $db );

		// Make sure the DB connection has the fake table clones and the fake table prefix
		MediaWikiIntegrationTestCase::setupDatabaseWithTestPrefix( $db, self::dbPrefix() );

		$this->assertSame( $this->db->tablePrefix(), $db->tablePrefix(), 'tablePrefix' );

		// Make sure the DB connection has all the test data
		$this->copyTestData( $this->db, $db );

		$value = $db->newSelectQueryBuilder()
			->select( 'value' )
			->from( 'objectcache' )
			->where( [ 'keyname' => __METHOD__ ] )
			->caller( __METHOD__ )->fetchField();
		$this->assertSame( 'TEST', $value, 'Copied Data' );

		$lb->closeAll( __METHOD__ );
	}

	public function testResetServices() {
		$services = MediaWikiServices::getInstance();

		// override a service instance
		$myReadOnlyMode = $this->createMock( ReadOnlyMode::class );
		$this->setService( 'ReadOnlyMode', $myReadOnlyMode );
		$this->setTemporaryHook( 'MyTestHook', static function ( &$n ) {
			$n++;
		}, true );

		$this->assertSame( $myReadOnlyMode, $services->getService( 'ReadOnlyMode' ) );

		// define a custom service
		$services->defineService(
			'_TEST_ResetService_Dummy',
			static function ( MediaWikiServices $services ) {
				$conf = $services->getMainConfig();
				return (object)[ 'lang' => $conf->get( MainConfigNames::LanguageCode ) ];
			}
		);

		$lang = $services->getMainConfig()->get( MainConfigNames::LanguageCode );
		$dummy = $services->getService( '_TEST_ResetService_Dummy' );
		$this->assertSame( $lang, $dummy->lang );

		// the actual test: change config, reset services.
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->resetServices();

		// the overridden service instance should still be there
		$this->assertSame( $myReadOnlyMode, $services->getService( 'ReadOnlyMode' ) );

		// the temporary hook should still be there
		$this->assertTrue(
			$this->getServiceContainer()->getHookContainer()->isRegistered( 'MyTestHook' )
		);

		// our custom service should have been re-created with the new language code
		$dummy2 = $services->getService( '_TEST_ResetService_Dummy' );
		$this->assertNotSame( $dummy2, $dummy );
		$this->assertSame( 'qqx', $dummy2->lang );
	}

	public function testGetServiceContainer() {
		$this->assertSame( MediaWikiServices::getInstance(), $this->getServiceContainer() );
	}

	public function testSetTemporaryHook() {
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$name = 'MWITCT_Dummy_Hook';

		$inc = static function ( &$n ) {
			$n++;
		};

		// add two handlers
		$this->setTemporaryHook( $name, $inc, false );
		$this->setTemporaryHook( $name, $inc, false );

		$count = 0;
		$hookContainer->run( $name, [ &$count ] );
		$this->assertSame( 2, $count );

		// replace existing hooks
		$this->setTemporaryHook( $name, $inc );

		$count = 0;
		$hookContainer->run( $name, [ &$count ] );
		$this->assertSame( 1, $count );

		// clear all hooks
		$this->clearHook( $name );

		$count = 0;
		$hookContainer->run( $name, [ &$count ] );
		$this->assertSame( 0, $count );

		// Put back a hook handler, so we can check in testSetTemporaryHookGetsReset
		// that hooks get reset between tests.
		$this->setTemporaryHook( $name, $inc );
		$this->assertTrue( $hookContainer->isRegistered( 'MWITCT_Dummy_Hook' ) );
	}

	public function testSetTemporaryHookGetsReset() {
		// We just check here that the hook we added in testSetTemporaryHook() is no longer present.
		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$this->assertFalse( $hookContainer->isRegistered( 'MWITCT_Dummy_Hook' ) );
	}

	/**
	 * @covers \NullHttpRequestFactory
	 * @covers \NullMultiHttpClient
	 */
	public function testHttpRequestsArePrevented() {
		$httpRequestFactory = $this->getServiceContainer()->getHttpRequestFactory();

		$prevented = true;
		try {
			$httpRequestFactory->get( 'http://0.0.0.0/' );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'get() should fail' );

		try {
			$httpRequestFactory->post( 'http://0.0.0.0/' );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'post() should fail' );

		try {
			$httpRequestFactory->request( 'HEAD', 'http://0.0.0.0/' );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'request() should fail' );

		try {
			$httpRequestFactory->create( 'http://0.0.0.0/' );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'create() should fail' );

		try {
			$client = $httpRequestFactory->createGuzzleClient();
			$client->get( 'http://0.0.0.0/' );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'createGuzzleClient() should fail' );

		$multiClient = $httpRequestFactory->createMultiClient();
		$req = [ 'url' => 'http://0.0.0.0/' ];

		try {
			$multiClient->run( $req );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'MultiHttpRequest::run() should fail' );

		try {
			$multiClient->runMulti( [ $req ] );
			$prevented = false;
		} catch ( AssertionFailedError $e ) {
			// pass
		}

		$this->assertTrue( $prevented, 'MultiHttpRequest::runMulti() should fail' );
	}

	public function testEditPage() {
		// NOTE: can't use a data provider, since creating Title or WikiPage instances
		//       is not safe without the test DB having been initialized.

		$this->assertEditPage( 'Hello Wörld A', __METHOD__, 'Hello Wörld A' );
		$this->assertEditPage( 'Hello Wörld B', __METHOD__, new TextContent( 'Hello Wörld B' ) );
		$this->assertEditPage( 'Hello Wörld C', Title::newFromText( __METHOD__ ), 'Hello Wörld C' );
		$this->assertEditPage(
			'Hello Wörld D',
			$this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) ),
			'Hello Wörld D'
		);
	}

	public function assertEditPage( $expected, $page, $content ) {
		$status = $this->editPage( $page, $content );
		$this->assertStatusOK( $status );
		$this->assertNotNull( $status->getNewRevision() );

		$rev = $status->getNewRevision();
		$cnt = $rev->getContent( SlotRecord::MAIN );

		$this->assertSame( $expected, $cnt->serialize() );
	}

	public function testInsertPage() {
		// NOTE: can't use a data provider, since creating Title or WikiPage instances
		//       is not safe without the test DB having been initialized.
		$dataProvider = [
			'string' => [
				'title' => 'Test',
				'expectedFullText' => 'Test'
			],
			'string with namespace' => [
				'title' => 'User:Test',
				'expectedFullText' => 'User:Test'
			],
			'Title object' => [
				'title' => Title::newFromText( 'Test' ),
				'expectedFullText' => 'Test'
			],
			'Title object with namespace' => [
				'title' => Title::newFromText( 'User:Test' ),
				'expectedFullText' => 'User:Test'
			],
			'TitleValue object' => [
				'title' => new TitleValue( NS_MAIN, 'Test' ),
				'expectedFullText' => 'Test'
			],
			'TitleValue object with namespace' => [
				'title' => new TitleValue( NS_USER, 'Test' ),
				'expectedFullText' => 'User:Test'
			],
			'PageIdentity object' => [
				'title' => new PageIdentityValue( 0, NS_MAIN, 'Test', PageIdentityValue::LOCAL ),
				'expectedFullText' => 'Test'
			],
			'PageIdentity object with namespace' => [
				'title' => new PageIdentityValue( 0, NS_USER, 'Test', PageIdentityValue::LOCAL ),
				'expectedFullText' => 'User:Test'
			],
		];

		foreach ( $dataProvider as $testName => $value ) {
			$title = $value[ 'title' ];
			$expectedFullText = $value[ 'expectedFullText' ];

			$array = $this->insertPage( $title, 'Test' );
			$this->assertTrue( $array[ 'title' ] instanceof Title,
				$testName . ': should return a Title object' );
			$this->assertIsInt( $array[ 'id' ],
				$testName . ': should return a valid page ID' );
			$this->assertSame( $expectedFullText, $array[ 'title' ]->getFullText(),
				$testName . ': should return the correct full text' );
		}
	}
}
