<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class MWExceptionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers MWException
	 */
	public function testMwexceptionThrowing() {
		$this->expectException( MWException::class );
		throw new MWException();
	}

	/**
	 * @dataProvider provideTextUseOutputPage
	 * @covers MWException::useOutputPage
	 */
	public function testUseOutputPage( $expected, $langObj, $wgFullyInitialised, $wgOut ) {
		$this->setMwGlobals( [
			'wgLang' => $langObj,
			'wgFullyInitialised' => $wgFullyInitialised,
			'wgOut' => $wgOut,
		] );

		$e = new MWException();
		$this->assertEquals( $expected, $e->useOutputPage() );
	}

	public function provideTextUseOutputPage() {
		return [
			// expected, langObj, wgFullyInitialised, wgOut
			[ false, null, null, null ],
			[ false, $this->getMockLanguage(), null, null ],
			[ false, $this->getMockLanguage(), true, null ],
			[ false, null, true, null ],
			[ false, null, null, true ],
			[ true, $this->getMockLanguage(), true, true ],
		];
	}

	private function getMockLanguage() {
		return $this->getMockBuilder( Language::class )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @covers MWException::useMessageCache
	 */
	public function testUseMessageCache() {
		$e = new MWException();
		$this->assertTrue( $e->useMessageCache() );
	}

	/**
	 * @covers MWException::isLoggable
	 */
	public function testIsLogable() {
		$e = new MWException();
		$this->assertTrue( $e->isLoggable() );
	}

	/**
	 * @dataProvider provideIsCommandLine
	 * @covers MWException::isCommandLine
	 */
	public function testisCommandLine( $expected, $wgCommandLineMode ) {
		$this->setMwGlobals( [
			'wgCommandLineMode' => $wgCommandLineMode,
		] );
		$e = new MWException();
		$this->assertEquals( $expected, $e->isCommandLine() );
	}

	public static function provideIsCommandLine() {
		return [
			[ false, null ],
			[ true, true ],
		];
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
		return [
			[ Exception::class ],
			[ MWException::class ],
		];
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
		$this->setMwGlobals( [ 'wgLogExceptionBacktrace' => true ] );

		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new $exClass() )
		);
		$this->assertObjectHasAttribute( $key, $json,
			"JSON serialized exception is missing key '$key'"
		);
		$this->assertSame( $expectedKeyType, gettype( $json->$key ),
			"JSON serialized key '$key' has type " . gettype( $json->$key )
			. " (expected: $expectedKeyType)."
		);
	}

	/**
	 * Returns test cases: exception class, key name, gettype()
	 */
	public static function provideJsonSerializedKeys() {
		$testCases = [];
		foreach ( [ Exception::class, MWException::class ] as $exClass ) {
			$exTests = [
				[ 'string', $exClass, 'id' ],
				[ 'string', $exClass, 'file' ],
				[ 'integer', $exClass, 'line' ],
				[ 'string', $exClass, 'message' ],
				[ 'NULL', $exClass, 'url' ],
				# Backtrace only enabled with wgLogExceptionBacktrace = true
				[ 'array', $exClass, 'backtrace' ],
			];
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
		$this->setMwGlobals( [ 'wgLogExceptionBacktrace' => true ] );
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
		$this->setMwGlobals( [ 'wgLogExceptionBacktrace' => false ] );
		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new Exception() )
		);
		$this->assertObjectNotHasAttribute( 'backtrace', $json );
	}

}
