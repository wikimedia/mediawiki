<?php

namespace MediaWiki\Logger\Monolog;

/**
 * Flay per https://phabricator.wikimedia.org/T218688.
 *
 * @group Broken
 * @covers \MediaWiki\Logger\Monolog\CeeFormatter
 */
class CeeFormatterTest extends \MediaWikiUnitTestCase {
	public function testV1() {
		$ls_formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_' );
		$cee_formatter = new CeeFormatter( 'app', 'system', '', 'ctx_' );
		$record = [ 'extra' => [ 'url' => 1 ], 'context' => [ 'url' => 2 ] ];
		$this->assertSame(
			$cee_formatter->format( $record ),
			"@cee: " . $ls_formatter->format( $record ) );
	}
}
