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
	 * @param LogRecord $record The input record.
	 * @param array $expected Associative array of expected keys and their values.
	 * @param array $notExpected List of keys that should not exist.
	 */
	public function testV1( LogRecord $record, array $expected, array $notExpected ) {
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
		$datetime = new \DateTimeImmutable( '@0' );
		return [
			[
				new LogRecord( $datetime, 'testchannel', Level::Info, 'testmessage',
					[ 'bar' => 2 ], [ 'foo' => 1 ] ),
				[ 'foo' => 1, 'bar' => 2 ],
				[ 'logstash_formatter_key_conflict' ],
			],
			[
				// Also asserts LogRecord fields flow through normalisation: message,
				// type (= applicationName), channel and level name.
				new LogRecord( $datetime, 'testchannel', Level::Info, 'testmessage',
					[ 'url' => 2 ], [ 'url' => 1 ] ),
				[ 'message' => 'testmessage', 'type' => 'app', 'channel' => 'testchannel',
					'level' => 'INFO', 'url' => 1, 'c_url' => 2,
					'logstash_formatter_key_conflict' => [ 'url' ] ],
				[],
			],
			[
				new LogRecord( $datetime, 'x', Level::Info, 'testmessage',
					[ 'channel' => 'y' ], [] ),
				[ 'channel' => 'x', 'c_channel' => 'y',
					'logstash_formatter_key_conflict' => [ 'channel' ] ],
				[],
			],
		];
	}

	public function testV1WithPrefix() {
		$formatter = new LogstashFormatter( 'app', 'system', '', 'ctx_', LogstashFormatter::V1 );
		$record = new LogRecord(
			new \DateTimeImmutable( '@0' ), 'testchannel', Level::Info, 'testmessage',
			[ 'url' => 2 ], [ 'url' => 1 ]
		);
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
		$record = new LogRecord(
			new \DateTimeImmutable( '2020-01-01T00:00:00+00:00' ),
			'ch',
			Level::Info,
			'msg',
			[ 'user' => 'bob' ],
			[ 'server' => 'host1', 'url' => '/wiki/Foo', 'reqId' => 'abc' ]
		);
		$formatted = json_decode( $formatter->format( $record ), true );

		$this->assertSame( '2020-01-01T00:00:00.000000+00:00', $formatted['@timestamp'] );
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
		$record = new LogRecord(
			new \DateTimeImmutable( '2020-01-01T00:00:00+00:00' ),
			'ch',
			Level::Info,
			'',
			[ 'user' => 'bob' ],
			[]
		);
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
