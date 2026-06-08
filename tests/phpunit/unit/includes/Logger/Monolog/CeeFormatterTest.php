<?php

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\CeeFormatter;
use MediaWiki\Logger\Monolog\LogstashFormatter;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @covers \MediaWiki\Logger\Monolog\CeeFormatter
 */
class CeeFormatterTest extends \MediaWikiUnitTestCase {
	public function testV1() {
		$ls_formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_' );
		$cee_formatter = new CeeFormatter( 'app', 'system', '', 'ctx_' );
		$record = new LogRecord(
			// T218688, Pin time to January 1, 2020
			new \DateTimeImmutable( '2020-01-01T00:00:00+00:00' ),
			'testchannel', Level::Info, 'testmessage',
			[ 'url' => 2 ], [ 'url' => 1 ]
		);

		$this->assertSame(
			$cee_formatter->format( $record ),
			"@cee: " . $ls_formatter->format( $record )
		);
	}
}
