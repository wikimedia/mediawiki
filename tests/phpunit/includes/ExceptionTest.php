<?php
/**
 * Tests for includes/Exception.php.
 *
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class ExceptionTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 */
	function testMwexceptionThrowing() {
		throw new MWException();
	}

	/**
	 * Verify the exception classes are JSON serializabe.
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
	 * @dataProvider provideExceptionClasses
	 */
	function testJsonSerializeExceptions( $exception_class ) {
		$json = MWExceptionHandler::jsonSerializeException(
			new $exception_class()
		);
		$this->assertNotEquals( false, $json,
			"The $exception_class exception should be JSON serializable, got false." );
	}

	function provideExceptionClasses() {
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
	 * @param $expectedKeyType String Type expected as returned by gettype()
	 * @param $exClass String An exception class (ie: Exception, MWException)
	 * @param $key String Name of the key to validate in the serialized JSON
	 * @dataProvider provideJsonSerializedKeys
	 */
	function testJsonserializeexceptionKeys( $expectedKeyType, $exClass, $key ) {

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
	function provideJsonSerializedKeys() {
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
	function testJsonserializeexceptionBacktracingEnabled() {
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
	function testJsonserializeexceptionBacktracingDisabled() {
		$this->setMwGlobals( array( 'wgLogExceptionBacktrace' => false ) );
		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new Exception() )
		);
		$this->assertObjectNotHasAttribute( 'backtrace', $json );

	}

}
