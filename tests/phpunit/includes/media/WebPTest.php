<?php
class WebPHandlerTest extends MediaWikiTestCase {
	public function setUp() {
		parent::setUp();
		// Allocated file for testing
		$this->tempFileName = tempnam( wfTempDir(), 'WEBP' );
	}
	public function tearDown() {
		parent::tearDown();
		unlink( $this->tempFileName );
	}
	/**
	 * @dataProvider provideTestExtractMetaData
	 */
	public function testExtractMetaData( $header, $expectedResult ) {
		// Put header into file
		file_put_contents( $this->tempFileName, $header );

		$this->assertEquals( $expectedResult, WebPHandler::extractMetadata( $this->tempFileName ) );
	}
	public function provideTestExtractMetaData() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// Files from https://developers.google.com/speed/webp/gallery2
			[ "\x52\x49\x46\x46\x90\x68\x01\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x83\x68\x01\x00\x2F\x8F\x01\x4B\x10\x8D\x38\x6C\xDB\x46\x92\xE0\xE0\x82\x7B\x6C",
				[ 'compression' => 'lossless', 'width' => 400, 'height' => 301 ] ],
			[ "\x52\x49\x46\x46\x64\x5B\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x8F\x01\x00\x2C\x01\x00\x41\x4C\x50\x48\xE5\x0E",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 400, 'height' => 301 ] ],
			[ "\x52\x49\x46\x46\xA8\x72\x00\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x9B\x72\x00\x00\x2F\x81\x81\x62\x10\x8D\x40\x8C\x24\x39\x6E\x73\x73\x38\x01\x96",
				[ 'compression' => 'lossless', 'width' => 386, 'height' => 395 ] ],
			[ "\x52\x49\x46\x46\xE0\x42\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x81\x01\x00\x8A\x01\x00\x41\x4C\x50\x48\x56\x10",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 386, 'height' => 395 ] ],
			[ "\x52\x49\x46\x46\x70\x61\x02\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x63\x61\x02\x00\x2F\x1F\xC3\x95\x10\x8D\xC8\x72\xDB\xC8\x92\x24\xD8\x91\xD9\x91",
				[ 'compression' => 'lossless', 'width' => 800, 'height' => 600 ] ],
			[ "\x52\x49\x46\x46\x1C\x1D\x01\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x1F\x03\x00\x57\x02\x00\x41\x4C\x50\x48\x25\x8B",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 800, 'height' => 600 ] ],
			[ "\x52\x49\x46\x46\xFA\xC5\x00\x00\x57\x45\x42\x50\x56\x50\x38\x4C\xEE\xC5\x00\x00\x2F\xA4\x81\x28\x10\x8D\x40\x68\x24\xC9\x91\xA4\xAE\xF3\x97\x75",
				[ 'compression' => 'lossless', 'width' => 421, 'height' => 163 ] ],
			[ "\x52\x49\x46\x46\xF6\x5D\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\xA4\x01\x00\xA2\x00\x00\x41\x4C\x50\x48\x38\x1A",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 421, 'height' => 163 ] ],
			[ "\x52\x49\x46\x46\xC4\x96\x01\x00\x57\x45\x42\x50\x56\x50\x38\x4C\xB8\x96\x01\x00\x2F\x2B\xC1\x4A\x10\x11\x87\x6D\xDB\x48\x12\xFC\x60\xB0\x83\x24",
				[ 'compression' => 'lossless', 'width' => 300, 'height' => 300 ] ],
			[ "\x52\x49\x46\x46\x0A\x11\x01\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x2B\x01\x00\x2B\x01\x00\x41\x4C\x50\x48\x67\x6E",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 300, 'height' => 300 ] ],

			// Lossy files from https://developers.google.com/speed/webp/gallery1
			[ "\x52\x49\x46\x46\x68\x76\x00\x00\x57\x45\x42\x50\x56\x50\x38\x20\x5C\x76\x00\x00\xD2\xBE\x01\x9D\x01\x2A\x26\x02\x70\x01\x3E\xD5\x4E\x97\x43\xA2",
				[ 'compression' => 'lossy', 'width' => 550, 'height' => 368 ] ],
			[ "\x52\x49\x46\x46\xB0\xEC\x00\x00\x57\x45\x42\x50\x56\x50\x38\x20\xA4\xEC\x00\x00\xB2\x4B\x02\x9D\x01\x2A\x26\x02\x94\x01\x3E\xD1\x50\x96\x46\x26",
				[ 'compression' => 'lossy', 'width' => 550, 'height' => 404 ] ],
			[ "\x52\x49\x46\x46\x7A\x19\x03\x00\x57\x45\x42\x50\x56\x50\x38\x20\x6E\x19\x03\x00\xB2\xF8\x09\x9D\x01\x2A\x00\x05\xD0\x02\x3E\xAD\x46\x99\x4A\xA5",
				[ 'compression' => 'lossy', 'width' => 1280, 'height' => 720 ] ],
			[ "\x52\x49\x46\x46\x44\xB3\x02\x00\x57\x45\x42\x50\x56\x50\x38\x20\x38\xB3\x02\x00\x52\x57\x06\x9D\x01\x2A\x00\x04\x04\x03\x3E\xA5\x44\x96\x49\x26",
				[ 'compression' => 'lossy', 'width' => 1024, 'height' => 772 ] ],
			[ "\x52\x49\x46\x46\x02\x43\x01\x00\x57\x45\x42\x50\x56\x50\x38\x20\xF6\x42\x01\x00\x12\xC0\x05\x9D\x01\x2A\x00\x04\xF0\x02\x3E\x79\x34\x93\x47\xA4",
				[ 'compression' => 'lossy', 'width' => 1024, 'height' => 752 ] ],

			// Animated file from https://groups.google.com/a/chromium.org/d/topic/blink-dev/Y8tRC4mdQz8/discussion
			[ "\x52\x49\x46\x46\xD0\x0B\x02\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x12\x00\x00\x00\x3F\x01\x00\x3F\x01\x00\x41\x4E",
				[ 'compression' => 'unknown', 'animated' => true, 'transparency' => true, 'width' => 320, 'height' => 320 ] ],

			// Error cases
			[ '', false ],
			[ '                                    ', false ],
			[ 'RIFF                                ', false ],
			[ 'RIFF1234WEBP                        ', false ],
			[ 'RIFF1234WEBPVP8                     ', false ],
			[ 'RIFF1234WEBPVP8L                    ', false ],
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * @dataProvider provideTestWithFileExtractMetaData
	 */
	public function testWithFileExtractMetaData( $filename, $expectedResult ) {
		$this->assertEquals( $expectedResult, WebPHandler::extractMetadata( $filename ) );
	}
	public function provideTestWithFileExtractMetaData() {
		return [
			[ __DIR__ . '/../../data/media/2_webp_ll.webp',
				[
					'compression' => 'lossless',
					'width' => 386,
					'height' => 395
				]
			],
			[ __DIR__ . '/../../data/media/2_webp_a.webp',
				[
					'compression' => 'lossy',
					'animated' => false,
					'transparency' => true,
					'width' => 386,
					'height' => 395
				]
			],
		];
	}

	/**
	 * @dataProvider provideTestGetImageSize
	 */
	public function testGetImageSize( $path, $expectedResult ) {
		$handler = new WebPHandler();
		$this->assertEquals( $expectedResult, $handler->getImageSize( null, $path ) );
	}
	public function provideTestGetImageSize() {
		return [
			// Public domain files from https://developers.google.com/speed/webp/gallery2
			[ __DIR__ . '/../../data/media/2_webp_a.webp', [ 386, 395 ] ],
			[ __DIR__ . '/../../data/media/2_webp_ll.webp', [ 386, 395 ] ],
			[ __DIR__ . '/../../data/media/webp_animated.webp', [ 300, 225 ] ],

			// Error cases
			[ __FILE__, false ],
		];
	}

	/**
	 * Tests the WebP MIME detection. This should really be a separate test, but sticking it
	 * here for now.
	 *
	 * @dataProvider provideTestGetMimeType
	 */
	public function testGuessMimeType( $path ) {
		$mime = MimeMagic::singleton();
		$this->assertEquals( 'image/webp', $mime->guessMimeType( $path, false ) );
	}
	public function provideTestGetMimeType() {
		return [
				// Public domain files from https://developers.google.com/speed/webp/gallery2
				[ __DIR__ . '/../../data/media/2_webp_a.webp' ],
				[ __DIR__ . '/../../data/media/2_webp_ll.webp' ],
				[ __DIR__ . '/../../data/media/webp_animated.webp' ],
		];
	}
}

/* Python code to extract a header and convert to PHP format:
 * print '"%s"' % ''.implode( '\\x%02X' % ord(c) for c in urllib.urlopen(url).read(36) )
 */
