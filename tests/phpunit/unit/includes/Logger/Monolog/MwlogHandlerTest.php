<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\MwlogHandler;
use Monolog\Handler\SyslogUdp\UdpSocket;
use Monolog\Level;
use Monolog\LogRecord;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\Monolog\MwlogHandler
 */
class MwlogHandlerTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Handler\SyslogUdpHandler::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
		if ( !extension_loaded( 'sockets' ) ) {
			$this->markTestSkipped( 'SyslogUdpHandler requires the sockets extension' );
		}
	}

	/**
	 * Build a MwlogHandler whose UdpSocket is replaced by a stub that records
	 * every ( $line, $header ) pair written to it.
	 *
	 * @param array<int, array{0: string, 1: string}> &$written
	 * @return MwlogHandler
	 */
	private function newHandlerCapturing( array &$written ): MwlogHandler {
		$socket = $this->createMock( UdpSocket::class );
		$socket->method( 'write' )->willReturnCallback(
			static function ( string $line, string $header ) use ( &$written ) {
				$written[] = [ $line, $header ];
			}
		);

		$handler = new MwlogHandler( 'myapp-', 'localhost', 514, LOG_USER );
		$handler->setSocket( $socket );

		return $handler;
	}

	private function newRecord( Level $level, string $channel, string $formatted ): LogRecord {
		return new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: $channel,
			level: $level,
			message: $formatted,
			context: [],
			extra: [],
			formatted: $formatted
		);
	}

	/**
	 * RFC 3164/5424 priority = facility + severity. The severities here are
	 * hard-coded from RFC 5424 (Emergency=0 … Debug=7) so the test is an
	 * independent oracle for the Monolog-level → syslog-severity mapping that
	 * MwlogHandler::write() performs, rather than re-deriving it from the code
	 * under test.
	 */
	public static function provideLevels(): array {
		return [
			'Debug' => [ Level::Debug, 7 ],
			'Info' => [ Level::Info, 6 ],
			'Notice' => [ Level::Notice, 5 ],
			'Warning' => [ Level::Warning, 4 ],
			'Error' => [ Level::Error, 3 ],
			'Critical' => [ Level::Critical, 2 ],
			'Alert' => [ Level::Alert, 1 ],
			'Emergency' => [ Level::Emergency, 0 ],
		];
	}

	/**
	 * @dataProvider provideLevels
	 */
	public function testWriteEncodesLevelAsSyslogPriority( Level $level, int $expectedSeverity ) {
		$written = [];
		$handler = $this->newHandlerCapturing( $written );

		$record = $this->newRecord( $level, 'test-channel', 'hello world' );
		TestingAccessWrapper::newFromObject( $handler )->write( $record );

		$this->assertCount( 1, $written, 'A single-line message is written once' );
		[ $line, $header ] = $written[0];

		$expectedPri = LOG_USER + $expectedSeverity;
		$this->assertStringStartsWith( "<{$expectedPri}>", $header,
			'Header begins with the RFC 5424 priority (facility + severity)' );
		$this->assertStringEndsWith( ' myapp-test-channel: ', $header,
			'Header ends with the app prefix and channel' );
		$this->assertSame( 'hello world', $line );
	}

	public function testWriteSplitsMultiLineMessages() {
		$written = [];
		$handler = $this->newHandlerCapturing( $written );

		$record = $this->newRecord( Level::Warning, 'multi', "first\nsecond\nthird" );
		TestingAccessWrapper::newFromObject( $handler )->write( $record );

		$this->assertSame(
			[ 'first', 'second', 'third' ],
			array_column( $written, 0 ),
			'Each line is written separately' );

		$expectedPri = LOG_USER + 4;
		foreach ( $written as [ , $header ] ) {
			$this->assertStringStartsWith( "<{$expectedPri}>", $header,
				'Every line shares the same priority header' );
		}
	}
}
