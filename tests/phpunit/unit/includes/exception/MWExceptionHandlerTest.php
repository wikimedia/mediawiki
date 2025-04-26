<?php

use MediaWiki\Exception\MWException;
use MediaWiki\Exception\MWExceptionHandler;
use Wikimedia\NormalizedException\NormalizedException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Exception\MWExceptionHandler
 * @author Antoine Musso
 */
class MWExceptionHandlerTest extends \MediaWikiUnitTestCase {

	/** @var int */
	private $oldSettingValue;

	protected function setUp(): void {
		parent::setUp();
		// We need to make sure the traces have function arguments as we're testing
		// their handling.
		$this->oldSettingValue = ini_set( 'zend.exception_ignore_args', 0 );
	}

	protected function tearDown(): void {
		TestingAccessWrapper::newFromClass( MWExceptionHandler::class )
			->logExceptionBacktrace = true;
		ini_set( 'zend.exception_ignore_args', $this->oldSettingValue );
		parent::tearDown();
	}

	/**
	 * Test end-to-end formatting of an exception, such as used by LogstashFormatter.
	 *
	 * @see MWExceptionHandler::prettyPrintTrace
	 */
	public function testTraceFormatting() {
		try {
			$dummy = new TestThrowerDummy();
			$startLine = __LINE__ + 1;
			$dummy->main();
		} catch ( Exception $e ) {
		}

		$startFile = __FILE__;
		$dummyFile = TestThrowerDummy::getFile();
		$dummyClass = TestThrowerDummy::class;
		$expected = <<<TEXT
from {$dummyFile}(17)
#0 {$dummyFile}(13): {$dummyClass}->getQuux()
#1 {$dummyFile}(9): {$dummyClass}->getBar()
#2 {$dummyFile}(5): {$dummyClass}->doFoo()
#3 {$startFile}($startLine): {$dummyClass}->main()
TEXT;

		// Trim up until our call()
		$trace = MWExceptionHandler::getRedactedTraceAsString( $e );
		$actual = implode( "\n", array_slice( explode( "\n", trim( $trace ) ), 0, 5 ) );

		$this->assertEquals( $expected, $actual );
	}

	public function testGetRedactedTrace() {
		$refvar = 'value';
		try {
			$array = [ 'a', 'b' ];
			$object = (object)[];
			self::helperThrowForArgs( $array, $object, $refvar );
		} catch ( Exception $e ) {
		}

		// Make sure our stack trace contains an array and an object passed to
		// some function in the stacktrace. Else, we cannot assert the trace
		// redaction achieved its job.
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
		$this->assertTrue( $hasObject, "The stacktrace has a frame with an object parameter" );
		$this->assertTrue( $hasArray, "The stacktrace has a frame with an array parameter" );

		// Now we redact the trace.. and verify there are no longer any arrays or objects
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

		$this->assertEquals( 'value', $refvar, 'Reference variable' );
	}

	public function testGetLogNormalMessage() {
		$this->assertSame(
			'[{reqId}] {exception_url}   Exception: message',
			MWExceptionHandler::getLogNormalMessage( new Exception( 'message' ) )
		);
		$this->assertSame(
			'[{reqId}] {exception_url}   message',
			MWExceptionHandler::getLogNormalMessage( new ErrorException( 'message' ) )
		);
		$this->assertSame(
			'[{reqId}] {exception_url}   ' . NormalizedException::class . ': {placeholder}',
			MWExceptionHandler::getLogNormalMessage(
				new NormalizedException( '{placeholder}', [ 'placeholder' => 'message' ] )
			)
		);
	}

	public function testGetLogContext() {
		$e = new Exception( 'message' );
		$context = MWExceptionHandler::getLogContext( $e );
		$this->assertSame( $e, $context['exception'] );

		$e = new NormalizedException( 'message', [ 'param' => 'value' ] );
		$context = MWExceptionHandler::getLogContext( $e );
		$this->assertSame( $e, $context['exception'] );
		$this->assertSame( 'value', $context['param'] );
	}

	/**
	 * @dataProvider provideGetStructuredExceptionDataKeys
	 * @param string $expectedKeyType Type expected as returned by get_debug_type()
	 * @param string $exClass An exception class (ie: Exception, MWException)
	 * @param string $key Name of the key to validate in the serialized JSON
	 */
	public function testGetStructuredExceptionDataKeys( $expectedKeyType, $exClass, $key ) {
		$data = MWExceptionHandler::getStructuredExceptionData( new $exClass() );
		$this->assertArrayHasKey( $key, $data );
		$this->assertSame( $expectedKeyType, get_debug_type( $data[$key] ), "Type of the '$key' key" );
	}

	/**
	 * Each case provides: [ type, exception class, key name ]
	 */
	public static function provideGetStructuredExceptionDataKeys() {
		foreach ( [ Exception::class, MWException::class ] as $exClass ) {
			yield [ 'string', $exClass, 'id' ];
			yield [ 'string', $exClass, 'file' ];
			yield [ 'int', $exClass, 'line' ];
			yield [ 'string', $exClass, 'message' ];
			yield [ 'null', $exClass, 'url' ];
			// Backtrace only enabled with wgLogExceptionBacktrace = true
			yield [ 'array', $exClass, 'backtrace' ];
		}
	}

	/**
	 * Given wgLogExceptionBacktrace is true
	 * then serialized exception must have a backtrace
	 */
	public function testGetStructuredExceptionDataBacktracingEnabled() {
		TestingAccessWrapper::newFromClass( MWExceptionHandler::class )
			->logExceptionBacktrace = true;
		$data = MWExceptionHandler::getStructuredExceptionData( new Exception() );
		$this->assertArrayHasKey( 'backtrace', $data );
	}

	/**
	 * Given wgLogExceptionBacktrace is false
	 * then serialized exception must not have a backtrace
	 */
	public function testGetStructuredExceptionDataBacktracingDisabled() {
		TestingAccessWrapper::newFromClass( MWExceptionHandler::class )
			->logExceptionBacktrace = false;
		$data = MWExceptionHandler::getStructuredExceptionData( new Exception() );
		$this->assertArrayNotHasKey( 'backtrace', $data );
	}

	/**
	 * Helper function for testGetRedactedTrace
	 *
	 * @param array $a
	 * phpcs:disable MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object $b
	 * @param mixed &$c
	 * @throws Exception
	 */
	protected static function helperThrowForArgs( array $a, object $b, &$c ) {
		throw new Exception();
	}
}
