<?php

use MediaWiki\Utils\MWTimestamp;
use PHPUnit\Framework\TestCase;
use Wikimedia\FileBackend\HTTPFileStreamer;

/**
 * @covers \Wikimedia\FileBackend\HTTPFileStreamer
 */
class HTTPFileStreamerTest extends TestCase {
	use MediaWikiCoversValidator;

	private const FILE = MW_INSTALL_PATH . '/tests/phpunit/data/media/test.jpg';

	/** @var int */
	private $obLevel = null;

	protected function setUp(): void {
		$this->obLevel = ob_get_level();
		ob_start();
		parent::setUp();
	}

	protected function tearDown(): void {
		while ( ob_get_level() > $this->obLevel ) {
			ob_end_clean();
		}
		parent::tearDown();
	}

	/**
	 * @dataProvider providePreprocessHeaders
	 */
	public function testPreprocessHeaders( array $input, array $expectedRaw, array $expectedOpt ) {
		[ $actualRaw, $actualOpt ] = HTTPFileStreamer::preprocessHeaders( $input );
		$this->assertSame( $expectedRaw, $actualRaw );
		$this->assertSame( $expectedOpt, $actualOpt );
	}

	public static function providePreprocessHeaders() {
		return [
			[
				[ 'Vary' => 'cookie', 'Cache-Control' => 'private' ],
				[ 'Vary: cookie', 'Cache-Control: private' ],
				[],
			],
			[
				[
					'Range' => 'bytes=(123-456)',
					'Content-Type' => 'video/mp4',
					'If-Modified-Since' => 'Wed, 21 Oct 2015 07:28:00 GMT',
				],
				[ 'Content-Type: video/mp4' ],
				[ 'range' => 'bytes=(123-456)', 'if-modified-since' => 'Wed, 21 Oct 2015 07:28:00 GMT' ],
			],
		];
	}

	private function makeStreamerParams( &$actual ) {
		$actual = [
			'reset' => 0,
			'file' => null,
			'range' => null,
			'headers' => [],
			'status' => 200,
		];

		return [
			'obResetFunc' => static function () use ( &$actual ) {
				$actual['reset']++;
			},
			'streamMimeFunc' => static function () {
				return 'test/test';
			},
			'headerFunc' => static function ( $header ) use ( &$actual ) {
				if ( preg_match( '/^HTTP.*? (\d+)/', $header, $m ) ) {
					$actual['status'] = (int)$m[1];
				}

				$actual['headers'][] = $header;
			},
		];
	}

	public static function provideStream() {
		$mtime = filemtime( self::FILE );
		$size = filesize( self::FILE );

		$modified = MWTimestamp::convert( TS_RFC2822, $mtime );

		yield 'simple stream' => [
			[],
			[],
			[
				"Last-Modified: $modified",
				'Content-type: test/test',
				"Content-Length: $size"
			]
		];

		yield 'extra header' => [
			[ 'Extra: yes' ],
			[],
			[
				'Extra: yes',
				"Last-Modified: $modified",
				'Content-type: test/test',
				"Content-Length: $size"
			]
		];

		yield 'modified' => [
			[],
			[
				'if-modified-since' => MWTimestamp::convert( TS_RFC2822, $mtime - 1 )
			],
			[
				"Last-Modified: $modified",
				'Content-type: test/test',
				"Content-Length: $size"
			]
		];

		yield 'not modified' => [
			[],
			[
				'if-modified-since' => MWTimestamp::convert( TS_RFC2822, $mtime + 1 )
			],
			[
				'HTTP/1.1 304 Not Modified'
			]
		];
	}

	/**
	 * @dataProvider provideStream
	 */
	public function testStream( $extraHeaders, $reqHeaders, $expectedHeaders ) {
		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( self::FILE, $params );
		$ok = $streamer->stream( $extraHeaders, true, $reqHeaders );

		$this->assertTrue( $ok );

		if ( !isset( $actual['status'] ) ) {
			$this->assertSame( self::FILE, $actual['file'] );
		}
		$this->assertSame( 1, $actual['reset'] );

		foreach ( $expectedHeaders as $exp ) {
			$this->assertContains( $exp, $actual['headers'] );
		}
	}

	public static function provideStream_range() {
		$filesize = filesize( self::FILE );
		$length = $filesize;
		$start = 0;
		$end = $length - 1;

		yield 'all' => [
			'bytes=-',
			206,
			[
				"Content-Length: $length",
				"Content-Range: bytes $start-$end/$filesize",
			],
			[ $start, $end, $length ]
		];

		$length = 100;
		$start = 0;
		$end = $length - 1;
		yield 'prefix' => [
			'bytes=0-99',
			206,
			[
				"Content-Length: $length",
				"Content-Range: bytes $start-$end/$filesize",
			],
			[ $start, $end, $length ]
		];

		$length = 100;
		$start = 100;
		$end = $start + $length - 1;
		yield 'middle' => [
			'bytes=100-199',
			206,
			[
				"Content-Length: $length",
				"Content-Range: bytes $start-$end/$filesize",
			],
			[ $start, $end, $length ]
		];

		$length = 100;
		$start = $filesize - $length;
		$end = $filesize - 1;
		yield 'suffix' => [
			'bytes=-100',
			206,
			[
				"Content-Length: $length",
				"Content-Range: bytes $start-$end/$filesize",
			],
			[ $start, $end, $length ]
		];

		$length = $filesize - 100;
		$start = 100;
		$end = $start + $length - 1;
		yield 'remaining' => [
			'bytes=100-',
			206,
			[
				"Content-Length: $length",
				"Content-Range: bytes $start-$end/$filesize",
			],
			[ $start, $end, $length ]
		];

		yield 'impossible' => [
			'bytes=1000-2000',
			416,
			[ 'Cache-Control: no-cache' ],
			null
		];

		yield 'unrecognized' => [
			'foo=1000-2000',
			200,
			[ "Content-Length: $filesize" ],
			null
		];
	}

	/**
	 * Check parsing of the Range header and corresponding response headers.
	 *
	 * @dataProvider provideStream_range
	 */
	public function testStream_range(
		$range,
		$expectedStatus,
		$expectedHeaders,
		$expectedRange
	) {
		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( self::FILE, $params );
		$streamer->stream( [], true, [ 'range' => $range ] );

		$this->assertSame( $expectedStatus, $actual['status'] );

		foreach ( $expectedHeaders as $exp ) {
			$this->assertContains( $exp, $actual['headers'] );
		}

		if ( $expectedStatus < 300 ) {
			[ $start, , $length ] = $expectedRange;
			$this->assertBufferContainsFile( $start, $length );
		}
	}

	/**
	 * Check we ar ereaching the correct chunks from the file
	 */
	public function testStream_chunks() {
		$data = file_get_contents( self::FILE );

		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( self::FILE, $params );

		// grab a chunk from the middle
		ob_start();
		$streamer->stream( [], true, [ 'range' => 'bytes=100-199' ] );
		$chunk1 = ob_get_clean();

		// get the start
		ob_start();
		$streamer->stream( [], true, [ 'range' => 'bytes=0-99' ] );
		$chunk2 = ob_get_clean();

		// fetch the rest
		ob_start();
		$streamer->stream( [], true, [ 'range' => 'bytes=200-' ] );
		$chunk3 = ob_get_clean();

		$this->assertSame( $data, $chunk2 . $chunk1 . $chunk3 );
	}

	public function testStream_404() {
		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( 'Xyzzy.jpg', $params );

		ob_start();
		$ok = $streamer->stream();
		$data = ob_get_clean();

		$this->assertFalse( $ok );
		$this->assertStringContainsString( '<h1>File not found</h1>', $data );
	}

	public function testStream_allowOB() {
		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( self::FILE, $params );
		$streamer->stream( [], true, [], HTTPFileStreamer::STREAM_ALLOW_OB );

		// Expect no buffer reset, even though the file was sent
		$this->assertSame( 0, $actual['reset'] );
		$this->assertBufferContainsFile();
	}

	public function testStream_headless() {
		$params = $this->makeStreamerParams( $actual );

		$streamer = new HTTPFileStreamer( self::FILE, $params );
		$streamer->stream( [ 'Extra: yes' ], true, [], HTTPFileStreamer::STREAM_HEADLESS );

		// Expect no headers, not even "extra"
		$this->assertSame( [], $actual['headers'] );
		$this->assertBufferContainsFile();
	}

	private function assertBufferContainsFile( ?int $offset = null, ?int $length = null ) {
		$actual = ob_get_clean();
		$expected = file_get_contents( self::FILE );

		if ( $offset !== null || $length !== null ) {
			$expected = substr( $expected, $offset ?? 0, $length );
		}

		$this->assertSame( $expected, $actual );
	}

}
