<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Logger\Monolog\BufferHandler;
use Monolog\Handler\NullHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\Monolog\BufferHandler
 */
class BufferHandlerTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Handler\BufferHandler::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
		DeferredUpdates::clearPendingUpdates();
	}

	protected function tearDown(): void {
		DeferredUpdates::clearPendingUpdates();
		parent::tearDown();
	}

	private function newRecord(): LogRecord {
		return new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: 'test',
			level: Level::Error,
			message: 'buffered',
			context: [],
			extra: [],
		);
	}

	public function testFirstHandleSchedulesFlushOnce() {
		$handler = new BufferHandler( new NullHandler() );
		$w = TestingAccessWrapper::newFromObject( $handler );
		$this->assertFalse( $w->initialized, 'The flush is not scheduled before the first record' );

		$handler->handle( $this->newRecord() );
		$this->assertTrue( $w->initialized,
			'Handling the first record schedules the deferred flush' );

		// The guard prevents a second record from scheduling another flush.
		$handler->handle( $this->newRecord() );
		$this->assertTrue( $w->initialized );
	}
}
