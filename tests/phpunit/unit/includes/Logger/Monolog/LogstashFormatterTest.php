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
		$this->expectDeprecationAndContinue( '/Passing an array to .*::format\(\) is deprecated/' );
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
		$this->expectDeprecationAndContinue( '/Passing an array to .*::format\(\) is deprecated/' );
		$formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_', LogstashFormatter::V1 );
		$record = [ 'extra' => [ 'url' => 1 ], 'context' => [ 'url' => 2 ] ];
		$formatted = json_decode( $formatter->format( $record ), true );
		$this->assertArrayHasKey( 'url', $formatted );
		$this->assertSame( 1, $formatted['url'] );
		$this->assertArrayHasKey( 'ctx_url', $formatted );
		$this->assertSame( 2, $formatted['ctx_url'] );
		$this->assertArrayNotHasKey( 'c_url', $formatted );
	}

	/**
	 * The V0 output shape differs from V1: '@'-prefixed top-level keys, a
	 * nested '@fields' bag, and dedicated '@source_host'/'@source_path' keys
	 * derived from extra.server/extra.url.
	 */
	public function testV0() {
		$formatter = new LogstashFormatter( 'app', 'system', '', '', LogstashFormatter::V0 );
		$record = [
			'channel' => 'ch',
			'message' => 'msg',
			'level' => 200,
			'datetime' => '2020-01-01T00:00:00+00:00',
			'extra' => [ 'server' => 'host1', 'url' => '/wiki/Foo', 'reqId' => 'abc' ],
			'context' => [ 'user' => 'bob' ],
		];
		$formatted = json_decode( $formatter->format( $record ), true );

		$this->assertSame( '2020-01-01T00:00:00+00:00', $formatted['@timestamp'] );
		$this->assertSame( 'system', $formatted['@source'] );
		$this->assertSame( 'app', $formatted['@type'] );
		$this->assertSame( 'msg', $formatted['@message'] );
		$this->assertSame( [ 'ch' ], $formatted['@tags'] );
		$this->assertSame( 'ch', $formatted['@fields']['channel'] );
		$this->assertSame( 200, $formatted['@fields']['level'] );
		// extra.server/extra.url are surfaced as dedicated source_* keys...
		$this->assertSame( 'host1', $formatted['@source_host'] );
		$this->assertSame( '/wiki/Foo', $formatted['@source_path'] );
		// ...and every extra value is also copied into @fields.
		$this->assertSame( 'abc', $formatted['@fields']['reqId'] );
		// Context (contextKey === '') is merged into @fields via fixKeyConflicts.
		$this->assertSame( 'bob', $formatted['@fields']['user'] );
	}

	/**
	 * With a non-empty contextKey, V0 prefixes context fields inside @fields
	 * rather than routing them through the conflict-resolver.
	 */
	public function testV0WithContextPrefix() {
		$formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_', LogstashFormatter::V0 );
		$record = [
			'channel' => 'ch',
			'datetime' => '2020-01-01T00:00:00+00:00',
			'context' => [ 'user' => 'bob' ],
		];
		$formatted = json_decode( $formatter->format( $record ), true );

		$this->assertSame( 'bob', $formatted['@fields']['ctx_user'] );
		$this->assertArrayNotHasKey( 'user', $formatted['@fields'] );
	}

	/**
	 * A Throwable in the context is normalised via the class's own
	 * normalizeException(), including chained previous exceptions.
	 */
	public function testNormalizesExceptionInContext() {
		$formatter = new LogstashFormatter( 'app', 'system', '', '', LogstashFormatter::V1 );
		$record = new LogRecord(
			new \DateTimeImmutable( '2020-01-01T00:00:00+00:00' ),
			'ch',
			Level::Error,
			'boom happened',
			[ 'exception' => new \RuntimeException( 'boom', 0, new \LogicException( 'root cause' ) ) ],
			[]
		);
		$formatted = json_decode( $formatter->format( $record ), true );

		$exception = $formatted['exception'];
		$this->assertSame( 'RuntimeException', $exception['class'] );
		$this->assertSame( 'boom', $exception['message'] );
		// file is reported as "path:line".
		$this->assertStringContainsString( ':', $exception['file'] );
		$this->assertArrayHasKey( 'trace', $exception );
		$this->assertSame( 'LogicException', $exception['previous']['class'] );
		$this->assertSame( 'root cause', $exception['previous']['message'] );
	}
}
