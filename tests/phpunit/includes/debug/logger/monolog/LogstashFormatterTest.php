<?php

namespace MediaWiki\Logger\Monolog;

class LogstashFormatterTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider provideV1
	 * @param array $record The input record.
	 * @param array $expected Associative array of expected keys and their values.
	 * @param array $notExpected List of keys that should not exist.
	 */
	public function testV1( array $record, array $expected, array $notExpected ) {
		$formatter = new LogstashFormatter( 'app', 'system', null, null, LogstashFormatter::V1 );
		$formatted = json_decode( $formatter->format( $record ), true );
		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $formatted );
			$this->assertSame( $value, $formatted[$key] );
		}
		foreach ( $notExpected as $key ) {
			$this->assertArrayNotHasKey( $key, $formatted );
		}
	}

	public function provideV1() {
		return [
			[
				[ 'extra' => [ 'foo' => 1 ], 'context' => [ 'bar' => 2 ] ],
				[ 'foo' => 1, 'bar' => 2 ],
				[ 'logstash_formatter_key_conflict' ],
			],
			[
				[ 'extra' => [ 'url' => 1 ], 'context' => [ 'url' => 2 ] ],
				[ 'url' => 1, 'c_url' => 2, 'logstash_formatter_key_conflict' => [ 'url' ] ],
				[],
			],
			[
				[ 'channel' => 'x', 'context' => [ 'channel' => 'y' ] ],
				[ 'channel' => 'x', 'c_channel' => 'y',
					'logstash_formatter_key_conflict' => [ 'channel' ] ],
				[],
			],
		];
	}

	public function testV1WithPrefix() {
		$formatter = new LogstashFormatter( 'app', 'system', null, 'ctx_', LogstashFormatter::V1 );
		$record = [ 'extra' => [ 'url' => 1 ], 'context' => [ 'url' => 2 ] ];
		$formatted = json_decode( $formatter->format( $record ), true );
		$this->assertArrayHasKey( 'url', $formatted );
		$this->assertSame( 1, $formatted['url'] );
		$this->assertArrayHasKey( 'ctx_url', $formatted );
		$this->assertSame( 2, $formatted['ctx_url'] );
		$this->assertArrayNotHasKey( 'c_url', $formatted );
	}
}
