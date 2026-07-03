<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\WikiProcessor;
use MediaWiki\WikiMap\WikiMap;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @covers \MediaWiki\Logger\Monolog\WikiProcessor
 */
class WikiProcessorTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !class_exists( LogRecord::class ) ) {
			// Under Monolog 2, LogRecord is only a forward-compat interface and
			// records are arrays; this processor path only exists under Monolog 3.
			$this->markTestSkipped( 'This test requires Monolog 3' );
		}
	}

	public function testAnnotatesExtraWithRequestGlobals() {
		$record = new LogRecord(
			datetime: new \DateTimeImmutable( '@0' ),
			channel: 'test',
			level: Level::Info,
			message: 'hi',
			context: [],
			extra: [ 'preexisting' => 'kept' ],
		);

		$processor = new WikiProcessor();
		$result = $processor( $record );

		// The regression this guards: WikiProcessor must accept a LogRecord
		// object (Monolog 3), not fatal on an `array` type-hint.
		$this->assertInstanceOf( LogRecord::class, $result );

		$extra = $result->extra;
		$this->assertSame( 'kept', $extra['preexisting'],
			'Existing extra fields are preserved' );
		$this->assertSame( MW_VERSION, $extra['mwversion'] );
		$this->assertSame( WikiMap::getCurrentWikiId(), $extra['wiki'] );
		$this->assertIsString( $extra['host'] );
		$this->assertIsString( $extra['reqId'] );
		// Tests run under the CLI SAPI, so the CLI argv branch is exercised.
		$this->assertIsString( $extra['cli_argv'] );
	}
}
