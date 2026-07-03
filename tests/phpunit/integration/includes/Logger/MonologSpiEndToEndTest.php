<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger;

use MediaWiki\Logger\Monolog\LegacyHandler;
use MediaWiki\Logger\Monolog\LineFormatter;
use MediaWiki\Logger\Monolog\WikiProcessor;
use MediaWiki\Logger\MonologSpi;
use Monolog\Processor\PsrLogMessageProcessor;

/**
 * End-to-end coverage for the Monolog SPI: build a realistic logger from
 * config and push a record through the full processor -> formatter -> handler
 * pipeline. This is the integration test whose absence let the Monolog 3
 * upgrade ship with broken processors (T397070) and a broken LineFormatter.
 *
 * @covers \MediaWiki\Logger\MonologSpi
 */
class MonologSpiEndToEndTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Logger::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
	}

	public function testRecordFlowsThroughProcessorsFormatterAndHandler() {
		$file = tempnam( sys_get_temp_dir(), 'monologspi' );
		$this->assertNotFalse( $file );

		try {
			$spi = new MonologSpi( [
				'loggers' => [
					'@default' => [
						'processors' => [ 'wiki', 'psr' ],
						'handlers' => [ 'file' ],
					],
				],
				'processors' => [
					'wiki' => [ 'class' => WikiProcessor::class ],
					'psr' => [ 'class' => PsrLogMessageProcessor::class ],
				],
				'handlers' => [
					'file' => [
						'class' => LegacyHandler::class,
						'args' => [ $file ],
						'formatter' => 'line',
					],
				],
				'formatters' => [
					'line' => [ 'class' => LineFormatter::class ],
				],
			] );

			$logger = $spi->getLogger( 'test' );
			$logger->error( 'hello {name}', [
				'name' => 'world',
				'shown' => 'yes',
				'private' => true,
			] );
			// Flush the file sink so its contents are readable.
			$spi->getHandler( 'file' )->close();

			$output = file_get_contents( $file );

			// PsrLogMessageProcessor interpolated {name} from the context.
			$this->assertStringContainsString( 'hello world', $output );
			// WikiProcessor annotated %extra% with request globals.
			$this->assertStringContainsString( MW_VERSION, $output );
			// A surviving context key is rendered...
			$this->assertStringContainsString( 'shown', $output );
			// ...but the internal 'private' flag is dropped by LineFormatter.
			$this->assertStringNotContainsString( 'private', $output );
		} finally {
			unlink( $file );
		}
	}
}
