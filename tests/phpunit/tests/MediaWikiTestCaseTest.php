<?php
use MediaWiki\Logger\LoggerFactory;

/**
 * @covers MediaWikiTestCase
 * @author Addshore
 */
class MediaWikiTestCaseTest extends MediaWikiTestCase {

	const GLOBAL_KEY_NONEXISTING = 'MediaWikiTestCaseTestGLOBAL-NONExisting';

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
	 */
	public function testExceptionThrownWhenStashingNonExistentGlobals() {
		$this->setExpectedException(
			'Exception',
			'Global with key ' . self::GLOBAL_KEY_NONEXISTING . ' doesn\'t exist and cant be stashed'
		);

		$this->stashMwGlobals( self::GLOBAL_KEY_NONEXISTING );
	}

	/**
	 * @covers MediaWikiTestCase::setLogger
	 * @covers MediaWikiTestCase::restoreLogger
	 */
	public function testLoggersAreRestoredOnTearDown() {
		// replacing an existing logger
		$logger1 = LoggerFactory::getInstance( 'foo' );
		$this->setLogger( 'foo', $this->getMock( '\Psr\Log\LoggerInterface' ) );
		$logger2 = LoggerFactory::getInstance( 'foo' );
		$this->tearDown();
		$logger3 = LoggerFactory::getInstance( 'foo' );

		$this->assertSame( $logger1, $logger3 );
		$this->assertNotSame( $logger1, $logger2 );

		// replacing a non-existing logger
		$this->setLogger( 'bar', $this->getMock( '\Psr\Log\LoggerInterface' ) );
		$logger1 = LoggerFactory::getInstance( 'bar' );
		$this->tearDown();
		$logger2 = LoggerFactory::getInstance( 'bar' );

		$this->assertNotSame( $logger1, $logger2 );
		$this->assertInstanceOf( '\Psr\Log\LoggerInterface', $logger2 );

		// replacing same logger twice
		$logger1 = LoggerFactory::getInstance( 'baz' );
		$this->setLogger( 'baz', $this->getMock( '\Psr\Log\LoggerInterface' ) );
		$this->setLogger( 'baz', $this->getMock( '\Psr\Log\LoggerInterface' ) );
		$this->tearDown();
		$logger2 = LoggerFactory::getInstance( 'baz' );

		$this->assertSame( $logger1, $logger2 );
	}
}
