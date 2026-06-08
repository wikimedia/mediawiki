<?php

namespace MediaWiki\Tests\Logger\Monolog;

use MediaWiki\Logger\Monolog\LogstashFormatter;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * @covers \MediaWiki\Logger\Monolog\LogstashFormatter
 */
class LogstashFormatterTest extends \MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideV1
	 * @param array $record The input record.
	 * @param array $expected Associative array of expected keys and their values.
	 * @param array $notExpected List of keys that should not exist.
	 */
	public function testV1( array $record, array $expected, array $notExpected ) {
		$formatter = new LogstashFormatter( 'app', 'system', '', '', LogstashFormatter::V1 );
		$formatted = json_decode( $formatter->format( $record ), true );
		foreach ( $expected as $key => $value ) {
			$this->assertArrayHasKey( $key, $formatted );
			$this->assertSame( $value, $formatted[$key] );
		}
		foreach ( $notExpected as $key ) {
			$this->assertArrayNotHasKey( $key, $formatted );
		}
	}

	public static function provideV1() {
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

	/**
	 * Same key-conflict behaviour as testV1 data set #1, but exercising the
	 * Monolog 3 LogRecord input branch rather than the legacy array form.
	 */
	public function testV1WithLogRecord() {
		$formatter = new LogstashFormatter( 'app', 'system', '', '', LogstashFormatter::V1 );
		$record = new LogRecord(
			new \DateTimeImmutable( '2020-01-01T00:00:00+00:00' ),
			'thechannel',
			Level::Warning,
			'themessage',
			[ 'url' => 2 ],
			[ 'url' => 1 ]
		);
		$formatted = json_decode( $formatter->format( $record ), true );
		// LogRecord fields flow through normalisation
		$this->assertSame( 'themessage', $formatted['message'] );
		$this->assertSame( 'app', $formatted['type'] );
		$this->assertSame( 'thechannel', $formatted['channel'] );
		$this->assertSame( 'WARNING', $formatted['level'] );
		// extra wins the reserved 'url' key; context is c_-prefixed and flagged
		$this->assertSame( 1, $formatted['url'] );
		$this->assertSame( 2, $formatted['c_url'] );
		$this->assertSame( [ 'url' ], $formatted['logstash_formatter_key_conflict'] );
	}

	public function testV1WithPrefix() {
		$formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_', LogstashFormatter::V1 );
		$record = [ 'extra' => [ 'url' => 1 ], 'context' => [ 'url' => 2 ] ];
		$formatted = json_decode( $formatter->format( $record ), true );
		$this->assertArrayHasKey( 'url', $formatted );
		$this->assertSame( 1, $formatted['url'] );
		$this->assertArrayHasKey( 'ctx_url', $formatted );
		$this->assertSame( 2, $formatted['ctx_url'] );
		$this->assertArrayNotHasKey( 'c_url', $formatted );
	}
}
