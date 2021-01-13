<?php
/**
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class MWExceptionHandlerTest extends \MediaWikiUnitTestCase {

	private $oldSettingValue;

	protected function setUp() : void {
		parent::setUp();
		// We need to make sure the traces have function arguments as we're testing
		// their handling.
		$this->oldSettingValue = ini_set( 'zend.exception_ignore_args', 0 );
	}

	protected function tearDown() : void {
		ini_set( 'zend.exception_ignore_args', $this->oldSettingValue );
		parent::tearDown();
	}

	/**
	 * @covers MWExceptionHandler::getRedactedTrace
	 */
	public function testGetRedactedTrace() {
		$refvar = 'value';
		try {
			$array = [ 'a', 'b' ];
			$object = (object)[];
			self::helperThrowAnException( $array, $object, $refvar );
		} catch ( Exception $e ) {
		}

		# Make sure our stack trace contains an array and an object passed to
		# some function in the stacktrace. Else, we can not assert the trace
		# redaction achieved its job.
		$trace = $e->getTrace();
		$hasObject = false;
		$hasArray = false;
		foreach ( $trace as $frame ) {
			if ( !isset( $frame['args'] ) ) {
				continue;
			}
			foreach ( $frame['args'] as $arg ) {
				$hasObject = $hasObject || is_object( $arg );
				$hasArray = $hasArray || is_array( $arg );
			}

			if ( $hasObject && $hasArray ) {
				break;
			}
		}
		$this->assertTrue( $hasObject,
			"The stacktrace must contain a function having an object as a parameter" );
		$this->assertTrue( $hasArray,
			"The stacktrace must contain a function having an array as a parameter" );

		# Now we redact the trace.. and make sure no function arguments are
		# arrays or objects.
		$redacted = MWExceptionHandler::getRedactedTrace( $e );

		foreach ( $redacted as $frame ) {
			if ( !isset( $frame['args'] ) ) {
				continue;
			}
			foreach ( $frame['args'] as $arg ) {
				$this->assertIsNotArray( $arg );
				$this->assertIsNotObject( $arg );
			}
		}

		$this->assertEquals( 'value', $refvar, 'Ensuring reference variable wasn\'t changed' );
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
		$GLOBALS['wgLogExceptionBacktrace'] = true;

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
		$GLOBALS['wgLogExceptionBacktrace'] = true;
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
		$GLOBALS['wgLogExceptionBacktrace'] = false;
		$json = json_decode(
			MWExceptionHandler::jsonSerializeException( new Exception() )
		);
		$this->assertObjectNotHasAttribute( 'backtrace', $json );
	}

	/**
	 * Helper function for testExpandArgumentsInCall
	 *
	 * Pass it an object and an array, and something by reference :-)
	 *
	 * @throws Exception
	 */
	protected static function helperThrowAnException( $a, $b, &$c ) {
		throw new Exception();
	}
}
