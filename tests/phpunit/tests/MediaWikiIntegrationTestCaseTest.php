<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWikiIntegrationTestCase
 * @group MediaWikiIntegrationTestCaseTest
 * @group Database
 *
 * @author Addshore
 */
class MediaWikiIntegrationTestCaseTest extends MediaWikiIntegrationTestCase {

	private static $startGlobals = [
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingString' => 'foo',
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingStringEmpty' => '',
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingArray' => [ 1, 'foo' => 'bar' ],
		'MediaWikiIntegrationTestCaseTestGLOBAL-ExistingArrayEmpty' => [],
	];

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		foreach ( self::$startGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
	}

	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();
		foreach ( self::$startGlobals as $key => $value ) {
			unset( $GLOBALS[$key] );
		}
	}

	public function provideExistingKeysAndNewValues() {
		$providedArray = [];
		foreach ( array_keys( self::$startGlobals ) as $key ) {
			$providedArray[] = [ $key, 'newValue' ];
			$providedArray[] = [ $key, [ 'newValue' ] ];
		}
		return $providedArray;
	}

	/**
	 * @dataProvider provideExistingKeysAndNewValues
	 *
	 * @covers MediaWikiIntegrationTestCase::setMwGlobals
	 * @covers MediaWikiIntegrationTestCase::tearDown
	 */
	public function testSetGlobalsAreRestoredOnTearDown( $globalKey, $newValue ) {
		$this->setMwGlobals( $globalKey, $newValue );
		$this->assertEquals(
			$newValue,
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);

		$this->mediaWikiTearDown();

		$this->assertEquals(
			self::$startGlobals[$globalKey],
			$GLOBALS[$globalKey],
			'Global failed to be restored on tearDown'
		);
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setMwGlobals
	 * @covers MediaWikiIntegrationTestCase::tearDown
	 */
	public function testSetNonExistentGlobalsAreUnsetOnTearDown() {
		$globalKey = 'abcdefg1234567';
		$this->setMwGlobals( $globalKey, true );
		$this->assertTrue(
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);

		$this->mediaWikiTearDown();

		$this->assertFalse(
			isset( $GLOBALS[$globalKey] ),
			'Global failed to be correctly unset'
		);
	}

	public function testOverrideMwServices() {
		$initialServices = MediaWikiServices::getInstance();

		$this->overrideMwServices();
		$this->assertNotSame( $initialServices, MediaWikiServices::getInstance() );
	}

	public function testSetService() {
		$initialServices = MediaWikiServices::getInstance();
		$initialService = $initialServices->getDBLoadBalancer();
		$mockService = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()->getMock();

		$this->setService( 'DBLoadBalancer', $mockService );
		$this->assertNotSame(
			$initialService,
			MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
		$this->assertSame( $mockService, MediaWikiServices::getInstance()->getDBLoadBalancer() );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setLogger
	 * @covers MediaWikiIntegrationTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingExistingLogger() {
		$logger1 = LoggerFactory::getInstance( 'foo' );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$logger2 = LoggerFactory::getInstance( 'foo' );
		$this->mediaWikiTearDown();
		$logger3 = LoggerFactory::getInstance( 'foo' );

		$this->assertSame( $logger1, $logger3 );
		$this->assertNotSame( $logger1, $logger2 );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setLogger
	 * @covers MediaWikiIntegrationTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingNonExistingLogger() {
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$logger1 = LoggerFactory::getInstance( 'foo' );
		$this->mediaWikiTearDown();
		$logger2 = LoggerFactory::getInstance( 'foo' );

		$this->assertNotSame( $logger1, $logger2 );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $logger2 );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setLogger
	 * @covers MediaWikiIntegrationTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingSameLoggerTwice() {
		$logger1 = LoggerFactory::getInstance( 'baz' );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$this->mediaWikiTearDown();
		$logger2 = LoggerFactory::getInstance( 'baz' );

		$this->assertSame( $logger1, $logger2 );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setNullLogger
	 * @covers MediaWikiIntegrationTestCase::restoreLoggers
	 */
	public function testNullLogger_createAndRemove() {
		$this->setNullLogger( 'tocreate' );
		$logger = LoggerFactory::getInstance( 'tocreate' );
		$this->assertInstanceOf( \Psr\Log\NullLogger::class, $logger );

		$this->mediaWikiTearDown();
		$logger = LoggerFactory::getInstance( 'tocreate' );
		// Unwrap from LogCapturingSpi
		$inner = TestingAccessWrapper::newFromObject( $logger )->logger;
		$this->assertInstanceOf( \MediaWiki\Logger\LegacyLogger::class, $inner );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setNullLogger
	 * @covers MediaWikiIntegrationTestCase::restoreLoggers
	 */
	public function testNullLogger_mutateAndRestore() {
		// Don't rely on the $wgDebugLogGroups and $wgDebugLogFile settings in
		// WMF CI to make LEVEL_DEBUG (100) the default. Control this in the test.
		$this->setMwGlobals( 'wgDebugToolbar', true );

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

		$this->mediaWikiTearDown();
		$this->assertSame(
			100,
			TestingAccessWrapper::newFromObject( $inner )->minimumLevel,
			'restored minimumLevel'
		);
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setupDatabaseWithTestPrefix
	 * @covers MediaWikiIntegrationTestCase::copyTestData
	 */
	public function testCopyTestData() {
		$this->markTestSkippedIfDbType( 'sqlite' );

		$this->tablesUsed[] = 'objectcache';
		$this->db->insert(
			'objectcache',
			[ 'keyname' => __METHOD__, 'value' => 'TEST', 'exptime' => $this->db->timestamp( 11 ) ],
			__METHOD__
		);

		$lbFactory = $this->getServiceContainer()->getDBLoadBalancerFactory();
		$lb = $lbFactory->newMainLB();
		$db = $lb->getConnection( DB_REPLICA );

		$this->assertNotSame( $this->db, $db );

		// Make sure the DB connection has the fake table clones and the fake table prefix
		MediaWikiIntegrationTestCase::setupDatabaseWithTestPrefix( $db, $this->dbPrefix() );

		$this->assertSame( $this->db->tablePrefix(), $db->tablePrefix(), 'tablePrefix' );

		// Make sure the DB connection has all the test data
		$this->copyTestData( $this->db, $db );

		$value = $db->selectField( 'objectcache', 'value', [ 'keyname' => __METHOD__ ], __METHOD__ );
		$this->assertSame( 'TEST', $value, 'Copied Data' );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::resetServices
	 */
	public function testResetServices() {
		$services = MediaWikiServices::getInstance();

		// override a service instance
		$myReadOnlyMode = $this->getMockBuilder( ReadOnlyMode::class )
			->disableOriginalConstructor()
			->getMock();
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
				return (object)[ 'lang' => $conf->get( 'LanguageCode' ) ];
			}
		);

		$lang = $services->getMainConfig()->get( 'LanguageCode' );
		$dummy = $services->getService( '_TEST_ResetService_Dummy' );
		$this->assertSame( $lang, $dummy->lang );

		// the actual test: change config, reset services.
		$this->setMwGlobals( 'wgLanguageCode', 'qqx' );
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

	/**
	 * @covers MediaWikiIntegrationTestCase::getServiceContainer
	 */
	public function testGetServiceContainer() {
		$this->assertSame( MediaWikiServices::getInstance(), $this->getServiceContainer() );
	}

	/**
	 * @covers MediaWikiIntegrationTestCase::setTemporaryHook
	 * @covers MediaWikiIntegrationTestCase::clearHook
	 */
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
	 * @covers NullHttpRequestFactory
	 * @covers NullMultiHttpClient
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
			new WikiPage( Title::newFromText( __METHOD__ ) ),
			'Hello Wörld D'
		);
	}

	public function assertEditPage( $expected, $page, $content ) {
		$status = $this->editPage( $page, $content );
		$this->assertTrue( $status->isOK() );
		$this->assertNotNull( $status->getValue()['revision-record'] );

		/** @var RevisionRecord $rev */
		$rev = $status->getValue()['revision-record'];
		$cnt = $rev->getContent( SlotRecord::MAIN );

		$this->assertSame( $expected, $cnt->serialize() );
	}

}
