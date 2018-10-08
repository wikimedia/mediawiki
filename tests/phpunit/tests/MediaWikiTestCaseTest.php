<?php
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers MediaWikiTestCase
 * @group MediaWikiTestCaseTest
 *
 * @author Addshore
 */
class MediaWikiTestCaseTest extends MediaWikiTestCase {

	private static $startGlobals = [
		'MediaWikiTestCaseTestGLOBAL-ExistingString' => 'foo',
		'MediaWikiTestCaseTestGLOBAL-ExistingStringEmpty' => '',
		'MediaWikiTestCaseTestGLOBAL-ExistingArray' => [ 1, 'foo' => 'bar' ],
		'MediaWikiTestCaseTestGLOBAL-ExistingArrayEmpty' => [],
	];

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		foreach ( self::$startGlobals as $key => $value ) {
			$GLOBALS[$key] = $value;
		}
	}

	public static function tearDownAfterClass() {
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
	 * @covers MediaWikiTestCase::setMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testSetGlobalsAreRestoredOnTearDown( $globalKey, $newValue ) {
		$this->setMwGlobals( $globalKey, $newValue );
		$this->assertEquals(
			$newValue,
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);

		$this->tearDown();

		$this->assertEquals(
			self::$startGlobals[$globalKey],
			$GLOBALS[$globalKey],
			'Global failed to be restored on tearDown'
		);
	}

	/**
	 * @dataProvider provideExistingKeysAndNewValues
	 *
	 * @covers MediaWikiTestCase::stashMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testStashedGlobalsAreRestoredOnTearDown( $globalKey, $newValue ) {
		$this->hideDeprecated( 'MediaWikiTestCase::stashMwGlobals' );
		$this->stashMwGlobals( $globalKey );
		$GLOBALS[$globalKey] = $newValue;
		$this->assertEquals(
			$newValue,
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);

		$this->tearDown();

		$this->assertEquals(
			self::$startGlobals[$globalKey],
			$GLOBALS[$globalKey],
			'Global failed to be restored on tearDown'
		);
	}

	/**
	 * @covers MediaWikiTestCase::stashMwGlobals
	 * @covers MediaWikiTestCase::tearDown
	 */
	public function testSetNonExistentGlobalsAreUnsetOnTearDown() {
		$globalKey = 'abcdefg1234567';
		$this->setMwGlobals( $globalKey, true );
		$this->assertTrue(
			$GLOBALS[$globalKey],
			'Global failed to correctly set'
		);

		$this->tearDown();

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
	 * @covers MediaWikiTestCase::setLogger
	 * @covers MediaWikiTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingExistingLogger() {
		$logger1 = LoggerFactory::getInstance( 'foo' );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$logger2 = LoggerFactory::getInstance( 'foo' );
		$this->tearDown();
		$logger3 = LoggerFactory::getInstance( 'foo' );

		$this->assertSame( $logger1, $logger3 );
		$this->assertNotSame( $logger1, $logger2 );
	}

	/**
	 * @covers MediaWikiTestCase::setLogger
	 * @covers MediaWikiTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingNonExistingLogger() {
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$logger1 = LoggerFactory::getInstance( 'foo' );
		$this->tearDown();
		$logger2 = LoggerFactory::getInstance( 'foo' );

		$this->assertNotSame( $logger1, $logger2 );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $logger2 );
	}

	/**
	 * @covers MediaWikiTestCase::setLogger
	 * @covers MediaWikiTestCase::restoreLoggers
	 */
	public function testLoggersAreRestoredOnTearDown_replacingSameLoggerTwice() {
		$logger1 = LoggerFactory::getInstance( 'baz' );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$this->setLogger( 'foo', $this->createMock( LoggerInterface::class ) );
		$this->tearDown();
		$logger2 = LoggerFactory::getInstance( 'baz' );

		$this->assertSame( $logger1, $logger2 );
	}
}
