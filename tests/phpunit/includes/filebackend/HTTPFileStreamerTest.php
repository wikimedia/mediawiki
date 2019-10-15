<?php

use PHPUnit\Framework\TestCase;

class HTTPFileStreamerTest extends TestCase {

	/**
	 * @covers HTTPFileStreamer::preprocessHeaders
	 * @dataProvider providePreprocessHeaders
	 */
	public function testPreprocessHeaders( array $input, array $expectedRaw, array $expectedOpt ) {
		list( $actualRaw, $actualOpt ) = HTTPFileStreamer::preprocessHeaders( $input );
		$this->assertSame( $expectedRaw, $actualRaw );
		$this->assertSame( $expectedOpt, $actualOpt );
	}

	public function providePreprocessHeaders() {
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

}
