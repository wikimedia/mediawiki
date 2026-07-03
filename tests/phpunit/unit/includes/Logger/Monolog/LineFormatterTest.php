<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use AssertionError;
use InvalidArgumentException;
use LengthException;
use LogicException;
use MediaWiki\Logger\Monolog\LineFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use RuntimeException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\Monolog\LineFormatter
 */
class LineFormatterTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Formatter\LineFormatter::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
	}

	/**
	 * Build a Monolog 3 LogRecord with the given context, at Error level.
	 */
	private function newRecord( array $context ): LogRecord {
		return new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: 'test',
			level: Level::Error,
			message: 'the message',
			context: $context,
			extra: [],
		);
	}

	public function testNormalizeExceptionNoTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LogicException]", $out );
		$this->assertStringNotContainsString( "\n  #0", $out );
	}

	public function testNormalizeExceptionTrace() {
		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new LogicException( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LogicException]", $out );
		$this->assertStringContainsString( "\n  #0", $out );
	}

	public function testNormalizeExceptionErrorNoTrace() {
		if ( !class_exists( AssertionError::class ) ) {
			$this->markTestSkipped( 'AssertionError class does not exist' );
		}

		$fixture = new LineFormatter();
		$fixture->includeStacktraces( false );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new AssertionError( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Error AssertionError]", $out );
		$this->assertStringNotContainsString( "\n  #0", $out );
	}

	public function testNormalizeExceptionErrorTrace() {
		if ( !class_exists( AssertionError::class ) ) {
			$this->markTestSkipped( 'AssertionError class does not exist' );
		}

		$fixture = new LineFormatter();
		$fixture->includeStacktraces( true );
		$fixture = TestingAccessWrapper::newFromObject( $fixture );
		$boom = new InvalidArgumentException( 'boom', 0,
			new LengthException( 'too long', 0,
				new AssertionError( 'Spock wuz here' )
			)
		);
		$out = $fixture->normalizeException( $boom );
		$this->assertStringContainsString( "\n[Exception InvalidArgumentException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Exception LengthException]", $out );
		$this->assertStringContainsString( "\nCaused by: [Error AssertionError]", $out );
		$this->assertStringContainsString( "\n  #0", $out );
	}

	/**
	 * The 'private' flag is an internal routing hint and must never reach the
	 * formatted output. Regression test for T397070: under Monolog 3 the record
	 * is a readonly LogRecord, so unset()-ing $record['context']['private'] in
	 * place silently no-ops and the flag leaked into %context%.
	 */
	public function testFormatDropsPrivateFlag() {
		$formatter = new LineFormatter( '%message% ctx=%context%' );
		$out = $formatter->format( $this->newRecord( [ 'private' => true, 'kept' => 'yes' ] ) );

		$this->assertStringNotContainsString( 'private', $out,
			'The private flag is stripped from the formatted context' );
		$this->assertStringContainsString( 'kept', $out,
			'Other context keys survive' );
	}

	/**
	 * A Throwable in context.exception is pretty-printed at the %exception%
	 * placeholder and removed from %context% so it is not emitted twice.
	 */
	public function testFormatExtractsThrowableToPlaceholder() {
		$formatter = new LineFormatter( '%message% exc=[%exception%] ctx=%context%' );
		$formatter->includeStacktraces( false );
		$out = $formatter->format( $this->newRecord( [
			'exception' => new RuntimeException( 'kaboom' ),
			'kept' => 'yes',
		] ) );

		$this->assertStringContainsString( '[Exception RuntimeException]', $out );
		$this->assertStringContainsString( 'kaboom', $out );
		// The exception must not also appear in the %context% JSON blob.
		$this->assertStringNotContainsString( '"exception"', $out );
		$this->assertStringContainsString( 'kept', $out );
	}

	/**
	 * An array (structured) exception uses normalizeExceptionArray().
	 */
	public function testFormatExtractsExceptionArray() {
		$formatter = new LineFormatter( '%message% exc=[%exception%]' );
		$out = $formatter->format( $this->newRecord( [
			'exception' => [
				'class' => 'FooError',
				'message' => 'from array',
				'file' => 'Foo.php',
				'line' => 42,
				'trace' => [],
			],
		] ) );

		$this->assertStringContainsString( '[Exception FooError]', $out );
		$this->assertStringContainsString( 'from array', $out );
	}

	/**
	 * A non-Throwable, non-array exception value is stringified.
	 */
	public function testFormatStringifiesScalarException() {
		$formatter = new LineFormatter( 'exc=[%exception%]' );
		$out = $formatter->format( $this->newRecord( [ 'exception' => 'just a string' ] ) );

		$this->assertStringContainsString( 'just a string', $out );
	}

	/**
	 * Without a %exception% placeholder in the format, the exception is left in
	 * the context untouched (and rendered as part of %context%).
	 */
	public function testFormatKeepsExceptionWhenNoPlaceholder() {
		$formatter = new LineFormatter( '%message% ctx=%context%' );
		$out = $formatter->format( $this->newRecord( [
			'exception' => new RuntimeException( 'stays' ),
		] ) );

		$this->assertStringContainsString( 'exception', $out,
			'With no %exception% placeholder the exception remains in %context%' );
	}
}
