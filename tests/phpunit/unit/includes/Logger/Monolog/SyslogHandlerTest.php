<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\SyslogHandler;
use Monolog\Level;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Logger\Monolog\SyslogHandler
 */
class SyslogHandlerTest extends \MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Handler\SyslogUdpHandler::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
		if ( !extension_loaded( 'sockets' ) ) {
			$this->markTestSkipped( 'SyslogUdpHandler requires the sockets extension' );
		}
	}

	public function testHeaderEncodesPriorityHostAndAppname() {
		$handler = new SyslogHandler( 'myapp', 'localhost', 514, LOG_USER, Level::Debug );
		$w = TestingAccessWrapper::newFromObject( $handler );

		// severity 3 (Error) with the LOG_USER facility -> PRI = facility + severity
		$header = $w->makeCommonSyslogHeader( 3, new \DateTimeImmutable( '@0' ) );

		$this->assertStringStartsWith( '<' . ( LOG_USER + 3 ) . '>', $header,
			'Header opens with the RFC 3164 priority' );
		$this->assertStringEndsWith( ' myapp: ', $header,
			'Header ends with the reporting hostname and app name' );
		$this->assertStringContainsString( php_uname( 'n' ), $header,
			'The local hostname is embedded in the header' );
	}

	public function testHeaderPriorityTracksFacility() {
		$handler = new SyslogHandler( 'myapp', 'localhost', 514, LOG_LOCAL0, Level::Debug );
		$w = TestingAccessWrapper::newFromObject( $handler );

		$header = $w->makeCommonSyslogHeader( 0, new \DateTimeImmutable( '@0' ) );

		$this->assertStringStartsWith( '<' . LOG_LOCAL0 . '>', $header,
			'A different facility shifts the priority accordingly' );
	}
}
