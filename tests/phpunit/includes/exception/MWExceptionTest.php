<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class MWExceptionTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 */
	public function testMwexceptionThrowing() {
		throw new MWException();
	}

	/**
	 * @dataProvider provideTextUseOutputPage
	 * @covers MWException::useOutputPage
	 */
	public function testUseOutputPage( $expected, $wgLang, $wgFullyInitialised, $wgOut ) {
		$this->setMwGlobals( array(
			'wgLang' => $wgLang,
			'wgFullyInitialised' => $wgFullyInitialised,
			'wgOut' => $wgOut,
		) );

		$e = new MWException();
		$this->assertEquals( $expected, $e->useOutputPage() );
	}

	public function provideTextUseOutputPage() {
		return array(
			// expected, wgLang, wgFullyInitialised, wgOut
			array( false, null, null, null ),
			array( false, $this->getMockLanguage(), null, null ),
			array( false, $this->getMockLanguage(), true, null ),
			array( false, null, true, null ),
			array( false, null, null, true ),
			array( true, $this->getMockLanguage(), true, true ),
		);
	}

	private function getMockLanguage() {
		return $this->getMockBuilder( 'Language' )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @dataProvider provideUseMessageCache
	 * @covers MWException::useMessageCache
	 */
	public function testUseMessageCache( $expected, $wgLang ) {
		$this->setMwGlobals( array(
			'wgLang' => $wgLang,
		) );
		$e = new MWException();
		$this->assertEquals( $expected, $e->useMessageCache() );
	}

	public function provideUseMessageCache() {
		return array(
			array( false, null ),
			array( true, $this->getMockLanguage() ),
		);
	}

	/**
	 * @covers MWException::isLoggable
	 */
	public function testIsLogable() {
		$e = new MWException();
		$this->assertTrue( $e->isLoggable() );
	}

	/**
	 * @dataProvider provideRunHooks
	 * @covers MWException::runHooks
	 */
	public function testRunHooks( $wgExceptionHooks, $name, $args, $expectedReturn ) {
		$this->setMwGlobals( array(
			'wgExceptionHooks' => $wgExceptionHooks,
		) );
		$e = new MWException();
		$this->assertEquals( $expectedReturn, $e->runHooks( $name, $args ) );
	}

	public static function provideRunHooks() {
		return array(
			array( null, null, null, null ),
			array( array(), 'name', array(), null ),
			array( array( 'name' => false ), 'name', array(), null ),
			array(
				array( 'mockHook' => array( 'MWExceptionTest::mockHook' ) ),
				'mockHook', array(), 'YAY.[]'
			),
			array(
				array( 'mockHook' => array( 'MWExceptionTest::mockHook' ) ),
				'mockHook', array( 'a' ), 'YAY.{"1":"a"}'
			),
			array(
				array( 'mockHook' => array( 'MWExceptionTest::mockHook' ) ),
				'mockHook', array( null ), null
			),
		);
	}

	/**
	 * Used in conjunction with provideRunHooks and testRunHooks as a mock callback for a hook
	 */
	public static function mockHook() {
		$args = func_get_args();
		if ( !$args[0] instanceof MWException ) {
			return '$caller not instance of MWException';
		}
		unset( $args[0] );
		if ( array_key_exists( 1, $args ) && $args[1] === null ) {
			return null;
		}
		return 'YAY.' . json_encode( $args );
	}

	/**
	 * @dataProvider provideIsCommandLine
	 * @covers MWException::isCommandLine
	 */
	public function testisCommandLine( $expected, $wgCommandLineMode ) {
		$this->setMwGlobals( array(
			'wgCommandLineMode' => $wgCommandLineMode,
		) );
		$e = new MWException();
		$this->assertEquals( $expected, $e->isCommandLine() );
	}

	public static function provideIsCommandLine() {
		return array(
			array( false, null ),
			array( true, true ),
		);
	}

	/**
	 * Verify the exception classes are JSON serializabe.
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
	 * @dataProvider provideExceptionClasses
	 */
	public function testJsonSerializeExceptions( $exception_class ) {
		$json = MWExceptionHandler::jsonSerializeException(
			new $exception_class()
		);
		$this->assertNotEquals( false, $json,
			"The $exception_class exception should be JSON serializable, got false." );
	}

	public static function provideExceptionClasses() {
		return array(
			array( 'Exception' ),
			array( 'MWException' ),
		);
	}

	/**
	 * Lame JSON schema validation.
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
	 *
	 * @param string $expectedKeyType Type expected as returned by gettype()
	 * @param string $exClass An exception class (ie: Exception, MWException)
	 * @param string $key Name of the key to validate in the serialized JSON
	 * @dataProvider provideJsonSerializedKeys
	 */
	public function testJsonserializeexceptionKeys( $expectedKeyType, $exClass, $key ) {

		# Make sure we log a backtrace:
		$this->setMwGlobals( array( 'wgLogExceptionBacktrace' => true ) );

		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new $exClass())
		);
		$this->assertObjectHasAttribute( $key, $json,
			"JSON serialized exception is missing key '$key'"
		);
		$this->assertInternalType( $expectedKeyType, $json->$key,
			"JSON serialized key '$key' has type " . gettype( $json->$key )
			. " (expected: $expectedKeyType)."
		);
	}

	/**
	 * Returns test cases: exception class, key name, gettype()
	 */
	public static function provideJsonSerializedKeys() {
		$testCases = array();
		foreach ( array( 'Exception', 'MWException' ) as $exClass ) {
			$exTests = array(
				array( 'string', $exClass, 'id' ),
				array( 'string', $exClass, 'file' ),
				array( 'integer', $exClass, 'line' ),
				array( 'string', $exClass, 'message' ),
				array( 'null', $exClass, 'url' ),
				# Backtrace only enabled with wgLogExceptionBacktrace = true
				array( 'array', $exClass, 'backtrace' ),
			);
			$testCases = array_merge( $testCases, $exTests );
		}
		return $testCases;
	}

	/**
	 * Given wgLogExceptionBacktrace is true
	 * then serialized exception SHOULD have a backtrace
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
	 */
	public function testJsonserializeexceptionBacktracingEnabled() {
		$this->setMwGlobals( array( 'wgLogExceptionBacktrace' => true ) );
		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new Exception() )
		);
		$this->assertObjectHasAttribute( 'backtrace', $json );
	}

	/**
	 * Given wgLogExceptionBacktrace is false
	 * then serialized exception SHOULD NOT have a backtrace
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
	 */
	public function testJsonserializeexceptionBacktracingDisabled() {
		$this->setMwGlobals( array( 'wgLogExceptionBacktrace' => false ) );
		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new Exception() )
		);
		$this->assertObjectNotHasAttribute( 'backtrace', $json );

	}

}
