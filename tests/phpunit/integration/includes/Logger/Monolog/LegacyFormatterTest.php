<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\LegacyFormatter;
use MediaWiki\MainConfigNames;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @covers \MediaWiki\Logger\Monolog\LegacyFormatter
 */
class LegacyFormatterTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( \Monolog\Formatter\NormalizerFormatter::class ) ) {
			$this->markTestSkipped( 'This test requires monolog to be installed' );
		}
		$this->overrideConfigValues( [
			MainConfigNames::DebugLogGroups => [],
			MainConfigNames::LogExceptionBacktrace => false,
		] );
	}

	public function testFormatDelegatesToLegacyLogger() {
		$formatter = new LegacyFormatter();
		$record = new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: 'test',
			level: Level::Error,
			message: 'the message',
			context: [],
			extra: [],
		);

		$out = $formatter->format( $record );

		// An unrecognised channel is rendered in wfDebug's historic style,
		// prefixing the channel name in brackets.
		$this->assertStringContainsString( '[test] the message', $out );
	}
}
