<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use LogicException;
use MediaWiki\Logger\Monolog\LegacyHandler;
use Monolog\Level;
use Monolog\LogRecord;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\Monolog\LegacyHandler
 */
class LegacyHandlerTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Handler\AbstractProcessingHandler::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
	}

	private function newRecord( string $channel, string $formatted ): LogRecord {
		return new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: $channel,
			level: Level::Error,
			message: $formatted,
			context: [],
			extra: [],
			formatted: $formatted,
		);
	}

	public function testEmptyUriThrows() {
		$handler = TestingAccessWrapper::newFromObject( new LegacyHandler( '' ) );
		$this->expectException( LogicException::class );
		$handler->openSink();
	}

	public function testUdpUriParsing() {
		$handler = new LegacyHandler( 'udp://127.0.0.1:8420/myprefix' );
		$w = TestingAccessWrapper::newFromObject( $handler );
		$w->openSink();

		$this->assertSame( '127.0.0.1', $w->host );
		$this->assertSame( 8420, $w->port );
		$this->assertSame( 'myprefix', $w->prefix );
		$this->assertTrue( $w->useUdp() );

		$handler->close();
	}

	public function testUdpUriWithoutPrefix() {
		$handler = new LegacyHandler( 'udp://127.0.0.1:8420' );
		$w = TestingAccessWrapper::newFromObject( $handler );
		$w->openSink();

		$this->assertSame( '', $w->prefix );

		$handler->close();
	}

	public function testUdpUriMissingHostThrows() {
		$handler = TestingAccessWrapper::newFromObject( new LegacyHandler( 'udp://:8420' ) );
		$this->expectException( UnexpectedValueException::class );
		$handler->openSink();
	}

	public function testUdpUriMissingPortThrows() {
		$handler = TestingAccessWrapper::newFromObject( new LegacyHandler( 'udp://127.0.0.1' ) );
		$this->expectException( UnexpectedValueException::class );
		$handler->openSink();
	}

	public function testUnopenableFileThrows() {
		$handler = TestingAccessWrapper::newFromObject(
			new LegacyHandler( '/this/path/does/not/exist/file.log' )
		);
		$this->expectException( UnexpectedValueException::class );
		$handler->openSink();
	}

	public function testWritesToFileSink() {
		$file = tempnam( sys_get_temp_dir(), 'legacyhandler' );
		$this->assertNotFalse( $file );
		try {
			$handler = new LegacyHandler( $file );
			$w = TestingAccessWrapper::newFromObject( $handler );
			$this->assertFalse( $w->useUdp() );

			$w->write( $this->newRecord( 'ch', "hello world\n" ) );
			$handler->close();

			$this->assertSame( "hello world\n", file_get_contents( $file ) );
		} finally {
			unlink( $file );
		}
	}

	public function testUdpUriDetectsIpv6() {
		$handler = new LegacyHandler( 'udp://[::1]:8420/pfx' );
		$w = TestingAccessWrapper::newFromObject( $handler );
		$w->openSink();

		$this->assertTrue( $w->useUdp() );

		$handler->close();
	}

	/**
	 * Bind a loopback UDP listener and return [ $socket, $port ].
	 *
	 * Skips the test when loopback UDP transmission is unavailable (e.g. inside
	 * a network sandbox that denies sendto()), so the delivery assertions run
	 * in CI without hanging or failing elsewhere.
	 */
	private function newLoopbackListener(): array {
		if ( !extension_loaded( 'sockets' ) ) {
			$this->markTestSkipped( 'This test requires the sockets extension' );
		}
		// Probe sendto() capability with a throwaway pair, keeping the real
		// listener's receive queue clean.
		$probeRecv = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		socket_bind( $probeRecv, '127.0.0.1', 0 );
		$probePort = 0;
		socket_getsockname( $probeRecv, $addr, $probePort );
		$probeSend = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		$permitted = @socket_sendto( $probeSend, 'x', 1, 0, '127.0.0.1', $probePort ) !== false;
		socket_close( $probeSend );
		socket_close( $probeRecv );
		if ( !$permitted ) {
			$this->markTestSkipped( 'Loopback UDP transmission is not permitted here' );
		}

		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		socket_bind( $sock, '127.0.0.1', 0 );
		// Never let a missing datagram hang the suite.
		socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, [ 'sec' => 2, 'usec' => 0 ] );
		$port = 0;
		socket_getsockname( $sock, $addr, $port );
		return [ $sock, $port ];
	}

	public function testUdpWriteAppliesFixedPrefixPerLine() {
		[ $listener, $port ] = $this->newLoopbackListener();
		try {
			$handler = new LegacyHandler( "udp://127.0.0.1:$port/PFX" );
			// No trailing newline: '/^/m' would otherwise prefix the empty final line too.
			TestingAccessWrapper::newFromObject( $handler )
				->write( $this->newRecord( 'ch', "line1\nline2" ) );

			$buf = '';
			socket_recvfrom( $listener, $buf, 65536, 0, $from, $fromPort );
			$this->assertSame( "PFX line1\nPFX line2\n", $buf );

			$handler->close();
		} finally {
			socket_close( $listener );
		}
	}

	public function testUdpWriteResolvesChannelPrefix() {
		[ $listener, $port ] = $this->newLoopbackListener();
		try {
			$handler = new LegacyHandler( "udp://127.0.0.1:$port/{channel}" );
			TestingAccessWrapper::newFromObject( $handler )
				->write( $this->newRecord( 'mychan', 'msg' ) );

			$buf = '';
			socket_recvfrom( $listener, $buf, 65536, 0, $from, $fromPort );
			$this->assertSame( "mychan msg\n", $buf );

			$handler->close();
		} finally {
			socket_close( $listener );
		}
	}

	public function testUdpWriteTruncatesOversizePrefixedPayload() {
		[ $listener, $port ] = $this->newLoopbackListener();
		try {
			$handler = new LegacyHandler( "udp://127.0.0.1:$port/PFX" );
			TestingAccessWrapper::newFromObject( $handler )
				->write( $this->newRecord( 'ch', str_repeat( 'a', 70000 ) ) );

			$buf = '';
			socket_recvfrom( $listener, $buf, 70000, 0, $from, $fromPort );
			// A prefixed payload is capped at 65506 bytes plus a trailing newline.
			$this->assertSame( 65507, strlen( $buf ) );
			$this->assertStringStartsWith( 'PFX a', $buf );
			$this->assertStringEndsWith( "\n", $buf );

			$handler->close();
		} finally {
			socket_close( $listener );
		}
	}
}
