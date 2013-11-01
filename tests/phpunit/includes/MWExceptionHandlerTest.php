<?php
/**
 * Tests for includes/Exception.php.
 *
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class MWExceptionHandlerTest extends MediaWikiTestCase {

	/**
	 * @covers MWExceptionHandler::getRedactedTrace
	 */
	function testGetRedactedTrace() {
		try {
			$array = array( 'a', 'b' );
			$object = new StdClass();
			self::helperThrowAnException( $array, $object );
		} catch (Exception $e) {
		}

		# Make sure our strack trace contains an array and an object passed to
		# some function in the stacktrace. Else, we can not assert the trace
		# redaction achieved its job.
		$trace = $e->getTrace();
		$hasObject = false;
		$hasArray = false;
		foreach ( $trace as $frame ) {
			if ( ! isset( $frame['args'] ) ) {
				continue;
			}
			foreach ( $frame['args'] as $arg ) {
				$hasObject = $hasObject || is_object( $arg );
				$hasArray = $hasArray || is_array( $arg );
			}

			if( $hasObject && $hasArray ) {
				break;
			}
		}
		$this->assertTrue( $hasObject,
			"The stacktrace must have a function having an object has parameter" );
		$this->assertTrue( $hasArray,
			"The stacktrace must have a function having an array has parameter" );

		# Now we redact the trace.. and make sure no function arguments are
		# arrays or objects.
		$redacted = MWExceptionHandler::getRedactedTrace( $e );

		foreach ( $redacted as $frame ) {
			if ( ! isset( $frame['args'] ) ) {
				continue;
			}
			foreach ( $frame['args'] as $arg ) {
				$this->assertNotInternalType( 'array', $arg);
				$this->assertNotInternalType( 'object', $arg);
			}
		}
	}

	/**
	 * Helper function for testExpandArgumentsInCall
	 *
	 * Pass it an object and an array :-)
	 *
	 * @throws Exception
	 */
	protected static function helperThrowAnException( $a, $b ) {
		throw new Exception();
	}
}
