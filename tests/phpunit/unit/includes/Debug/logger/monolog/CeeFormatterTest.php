<?php

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\CeeFormatter;
use MediaWiki\Logger\Monolog\LogstashFormatter;

/**
 * @covers \MediaWiki\Logger\Monolog\CeeFormatter
 */
class CeeFormatterTest extends \MediaWikiUnitTestCase {
	public function testV1() {
		$ls_formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_' );
		$cee_formatter = new CeeFormatter( 'app', 'system', '', 'ctx_' );
		$record = [
			'extra' => [ 'url' => 1 ],
			'context' => [ 'url' => 2 ],
			// T218688, Pin time to January 1, 2020
			'datetime' => '2020-01-01T00:00:00+00:00'
		];

		$this->assertSame(
			$cee_formatter->format( $record ),
			"@cee: " . $ls_formatter->format( $record )
		);
	}
}
