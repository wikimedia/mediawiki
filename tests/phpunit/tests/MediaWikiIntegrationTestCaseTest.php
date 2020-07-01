<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
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

	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();
		foreach ( self::$startGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
	}

	public static function tearDownAfterClass() : void {
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

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lb = $lbFactory->newMainLB();
		$db = $lb->getConnection( DB_REPLICA );

		// sanity
		$this->assertNotSame( $this->db, $db );

		// Make sure the DB connection has the fake table clones and the fake table prefix
		MediaWikiIntegrationTestCase::setupDatabaseWithTestPrefix( $db, $this->dbPrefix(), false );

		$this->assertSame( $this->db->tablePrefix(), $db->tablePrefix(), 'tablePrefix' );

		// Make sure the DB connection has all the test data
		$this->copyTestData( $this->db, $db );

		$value = $db->selectField( 'objectcache', 'value', [ 'keyname' => __METHOD__ ], __METHOD__ );
		$this->assertSame( 'TEST', $value, 'Copied Data' );
	}

	public function testResetServices() {
		$services = MediaWikiServices::getInstance();

		// override a service instance
		$myReadOnlyMode = $this->getMockBuilder( ReadOnlyMode::class )
			->disableOriginalConstructor()
			->getMock();
		$this->setService( 'ReadOnlyMode', $myReadOnlyMode );

		// sanity check
		$this->assertSame( $myReadOnlyMode, $services->getService( 'ReadOnlyMode' ) );

		// define a custom service
		$services->defineService(
			'_TEST_ResetService_Dummy',
			function ( MediaWikiServices $services ) {
				$conf = $services->getMainConfig();
				return (object)[ 'lang' => $conf->get( 'LanguageCode' ) ];
			}
		);

		// sanity check
		$lang = $services->getMainConfig()->get( 'LanguageCode' );
		$dummy = $services->getService( '_TEST_ResetService_Dummy' );
		$this->assertSame( $lang, $dummy->lang );

		// the actual test: change config, reset services.
		$this->setMwGlobals( 'wgLanguageCode', 'qqx' );

		// the overridden service instance should still be there
		$this->assertSame( $myReadOnlyMode, $services->getService( 'ReadOnlyMode' ) );

		// our custom service should have been re-created with the new language code
		$dummy2 = $services->getService( '_TEST_ResetService_Dummy' );
		$this->assertNotSame( $dummy2, $dummy );
		$this->assertSame( 'qqx', $dummy2->lang );
	}

}
