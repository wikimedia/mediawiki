<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logger\LoggingContext;
use MediaWiki\Logger\Monolog\ContextProcessor;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @covers \MediaWiki\Logger\Monolog\ContextProcessor
 */
class ContextProcessorTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( LogRecord::class ) ) {
			// Under Monolog 2, LogRecord is only a forward-compat interface and
			// records are arrays; this processor path only exists under Monolog 3.
			$this->markTestSkipped( 'This test requires Monolog 3' );
		}
		// Isolate the process-global diagnostic context from other tests.
		LoggerFactory::setContext( new LoggingContext() );
	}

	protected function tearDown(): void {
		LoggerFactory::setContext( new LoggingContext() );
		parent::tearDown();
	}

	private function newRecord( array $context ): LogRecord {
		return new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: 'test',
			level: Level::Info,
			message: 'hi',
			context: $context,
			extra: [],
		);
	}

	public function testMergesDiagnosticContext() {
		LoggerFactory::getContext()->add( [ 'diag' => 'from-context', 'shared' => 'from-context' ] );

		$processor = new ContextProcessor();
		$result = $processor( $this->newRecord( [ 'call' => 'from-record', 'shared' => 'from-record' ] ) );

		$this->assertInstanceOf( LogRecord::class, $result,
			'Processor returns a LogRecord, not an array' );
		$this->assertSame(
			[
				// Per-call context is preserved and wins the key conflict on 'shared'.
				'call' => 'from-record',
				'shared' => 'from-record',
				'diag' => 'from-context',
			],
			$result->context,
			'Diagnostic context is merged in without overriding per-call keys' );
	}

	public function testLeavesRecordUnchangedWhenContextEmpty() {
		$processor = new ContextProcessor();
		$result = $processor( $this->newRecord( [ 'call' => 'value' ] ) );

		$this->assertSame( [ 'call' => 'value' ], $result->context );
	}
}
