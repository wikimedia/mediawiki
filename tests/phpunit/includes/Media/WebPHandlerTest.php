<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Media\WebPHandler;

/**
 * @covers \MediaWiki\Media\WebPHandler
 */
class WebPHandlerTest extends MediaWikiMediaTestCase {

	/**
	 * @dataProvider provideTestExtractMetaData
	 */
	public function testExtractMetaData( $header, $expectedResult ) {
		$tempFileName = $this->getNewTempFile();

		// Put header into file
		file_put_contents( $tempFileName, $header );

		$this->assertEquals( $expectedResult, WebPHandler::extractMetadata( $tempFileName ) );
	}

	public static function provideTestExtractMetaData() {
		return [
			// Files from https://developers.google.com/speed/webp/gallery2
			[ "\x52\x49\x46\x46\x90\x68\x01\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x83\x68\x01\x00\x2F\x8F\x01\x4B\x10\x8D\x38\x6C\xDB\x46\x92\xE0\xE0\x82\x7B\x6C",
				[ 'compression' => 'lossless', 'width' => 400, 'height' => 301, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x64\x5B\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x8F\x01\x00\x2C\x01\x00\x41\x4C\x50\x48\xE5\x0E",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 400, 'height' => 301, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xA8\x72\x00\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x9B\x72\x00\x00\x2F\x81\x81\x62\x10\x8D\x40\x8C\x24\x39\x6E\x73\x73\x38\x01\x96",
				[ 'compression' => 'lossless', 'width' => 386, 'height' => 395, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xE0\x42\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x81\x01\x00\x8A\x01\x00\x41\x4C\x50\x48\x56\x10",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 386, 'height' => 395, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x70\x61\x02\x00\x57\x45\x42\x50\x56\x50\x38\x4C\x63\x61\x02\x00\x2F\x1F\xC3\x95\x10\x8D\xC8\x72\xDB\xC8\x92\x24\xD8\x91\xD9\x91",
				[ 'compression' => 'lossless', 'width' => 800, 'height' => 600, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x1C\x1D\x01\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x1F\x03\x00\x57\x02\x00\x41\x4C\x50\x48\x25\x8B",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 800, 'height' => 600, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xFA\xC5\x00\x00\x57\x45\x42\x50\x56\x50\x38\x4C\xEE\xC5\x00\x00\x2F\xA4\x81\x28\x10\x8D\x40\x68\x24\xC9\x91\xA4\xAE\xF3\x97\x75",
				[ 'compression' => 'lossless', 'width' => 421, 'height' => 163, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xF6\x5D\x00\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\xA4\x01\x00\xA2\x00\x00\x41\x4C\x50\x48\x38\x1A",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 421, 'height' => 163, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xC4\x96\x01\x00\x57\x45\x42\x50\x56\x50\x38\x4C\xB8\x96\x01\x00\x2F\x2B\xC1\x4A\x10\x11\x87\x6D\xDB\x48\x12\xFC\x60\xB0\x83\x24",
				[ 'compression' => 'lossless', 'width' => 300, 'height' => 300, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x0A\x11\x01\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x10\x00\x00\x00\x2B\x01\x00\x2B\x01\x00\x41\x4C\x50\x48\x67\x6E",
				[ 'compression' => 'unknown', 'animated' => false, 'transparency' => true, 'width' => 300, 'height' => 300, 'bits' => 8 ] ],

			// Lossy files from https://developers.google.com/speed/webp/gallery1
			[ "\x52\x49\x46\x46\x68\x76\x00\x00\x57\x45\x42\x50\x56\x50\x38\x20\x5C\x76\x00\x00\xD2\xBE\x01\x9D\x01\x2A\x26\x02\x70\x01\x3E\xD5\x4E\x97\x43\xA2",
				[ 'compression' => 'lossy', 'width' => 550, 'height' => 368, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\xB0\xEC\x00\x00\x57\x45\x42\x50\x56\x50\x38\x20\xA4\xEC\x00\x00\xB2\x4B\x02\x9D\x01\x2A\x26\x02\x94\x01\x3E\xD1\x50\x96\x46\x26",
				[ 'compression' => 'lossy', 'width' => 550, 'height' => 404, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x7A\x19\x03\x00\x57\x45\x42\x50\x56\x50\x38\x20\x6E\x19\x03\x00\xB2\xF8\x09\x9D\x01\x2A\x00\x05\xD0\x02\x3E\xAD\x46\x99\x4A\xA5",
				[ 'compression' => 'lossy', 'width' => 1280, 'height' => 720, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x44\xB3\x02\x00\x57\x45\x42\x50\x56\x50\x38\x20\x38\xB3\x02\x00\x52\x57\x06\x9D\x01\x2A\x00\x04\x04\x03\x3E\xA5\x44\x96\x49\x26",
				[ 'compression' => 'lossy', 'width' => 1024, 'height' => 772, 'bits' => 8 ] ],
			[ "\x52\x49\x46\x46\x02\x43\x01\x00\x57\x45\x42\x50\x56\x50\x38\x20\xF6\x42\x01\x00\x12\xC0\x05\x9D\x01\x2A\x00\x04\xF0\x02\x3E\x79\x34\x93\x47\xA4",
				[ 'compression' => 'lossy', 'width' => 1024, 'height' => 752, 'bits' => 8 ] ],

			// Animated file from https://groups.google.com/a/chromium.org/d/topic/blink-dev/Y8tRC4mdQz8/discussion
			[ "\x52\x49\x46\x46\xD0\x0B\x02\x00\x57\x45\x42\x50\x56\x50\x38\x58\x0A\x00\x00\x00\x12\x00\x00\x00\x3F\x01\x00\x3F\x01\x00\x41\x4E",
				[ 'compression' => 'unknown', 'animated' => true, 'transparency' => true, 'width' => 320, 'height' => 320, 'bits' => 8 ] ],

			// Error cases
			[ '', false ],
			[ '                                    ', false ],
			[ 'RIFF                                ', false ],
			[ 'RIFF1234WEBP                        ', false ],
			[ 'RIFF1234WEBPVP8                     ', false ],
			[ 'RIFF1234WEBPVP8L                    ', false ],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideTestWithFileExtractMetaData
	 */
	public function testWithFileExtractMetaData( $filename, $expectedResult ) {
		$this->assertEquals( $expectedResult, WebPHandler::extractMetadata( $filename ) );
	}

	public static function provideTestWithFileExtractMetaData() {
		return [
			[ __DIR__ . '/../../data/media/2_webp_ll.webp',
				[
					'compression' => 'lossless',
					'width' => 386,
					'height' => 395,
					'bits' => 8
				]
			],
			[ __DIR__ . '/../../data/media/2_webp_a.webp',
				[
					'compression' => 'lossy',
					'animated' => false,
					'transparency' => true,
					'width' => 386,
					'height' => 395,
					'bits' => 8
				]
			],
		];
	}

	/**
	 * @dataProvider provideTestGetSizeAndMetadata
	 */
	public function testGetSizeAndMetadata( $path, $expectedResult ) {
		$handler = new WebPHandler();
		$this->assertEquals( $expectedResult, $handler->getSizeAndMetadata( null, $path ) );
	}

	public static function provideTestGetSizeAndMetadata() {
		return [
			// Public domain files from https://developers.google.com/speed/webp/gallery2
			[
				__DIR__ . '/../../data/media/2_webp_a.webp',
				[
					'width' => 386,
					'height' => 395,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => true,
						'width' => 386,
						'height' => 395,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
					],
				]
			],
			[
				__DIR__ . '/../../data/media/2_webp_ll.webp',
				[
					'width' => 386,
					'height' => 395,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossless',
						'width' => 386,
						'height' => 395,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
					],
				]
			],
			[
				__DIR__ . '/../../data/media/webp_animated.webp',
				[
					'width' => 300,
					'height' => 225,
					'bits' => 8,
					'metadata' => [
						'compression' => 'unknown',
						'animated' => true,
						'transparency' => true,
						'width' => 300,
						'height' => 225,
						'bits' => 8,
						'frameCount' => 100,
						'duration' => 10.0,
						'looped' => true,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
					],
				]
			],
			[
				__DIR__ . '/../../data/media/exif.webp',
				[
					'width' => 40,
					'height' => 10,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => false,
						'width' => 40,
						'height' => 10,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
						'media-metadata' => [
							'GPSLatitude' => 88.51805555555555,
							'GPSLongitude' => -21.12357,
							'GPSAltitude' => -3.1415926530119025,
							'GPSDOP' => '5/1',
							'GPSVersionID' => '2.2.0.0'
						]
					]
				]
			],
			// Using non-standard "Exif\0\0" prefix
			[
				__DIR__ . '/../../data/media/exif-prefix.webp',
				[
					'width' => 40,
					'height' => 10,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => false,
						'width' => 40,
						'height' => 10,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
						'media-metadata' => [
							'GPSLatitude' => 88.51805555555555,
							'GPSLongitude' => -21.12357,
							'GPSAltitude' => -3.1415926530119025,
							'GPSDOP' => '5/1',
							'GPSVersionID' => '2.2.0.0'
						]
					]
				]
			],
			// Using standard "xmp " fourcc
			[
				__DIR__ . '/../../data/media/xmp.webp',
				[
					'width' => 420,
					'height' => 300,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => false,
						'width' => 420,
						'height' => 300,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
						'media-metadata' => [
							'ImageDescription' => [
								'x-default' => 'An example image',
								'en' => 'right translation',
								'_type' => 'lang'
							]
						]
					]
				]
			],
			// Using the "xmp\0" fourcc (not standard "xmp ").
			[
				__DIR__ . '/../../data/media/xmp-null.webp',
				[
					'width' => 420,
					'height' => 300,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => false,
						'width' => 420,
						'height' => 300,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
						'media-metadata' => [
							'ImageDescription' => [
								'x-default' => 'Image with XMPnull byte fourcc',
								'en' => 'right translation',
								'_type' => 'lang'
							]
						]
					]
				]
			],
			// Containing both XMP and Exif
			[
				__DIR__ . '/../../data/media/xmp-exif.webp',
				[
					'width' => 420,
					'height' => 300,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossy',
						'animated' => false,
						'transparency' => false,
						'width' => 420,
						'height' => 300,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
						'media-metadata' => [
							'ImageDescription' => [
								'x-default' => 'right(iptc)',
								'en' => 'right translation',
								'_type' => 'lang'
							],
							'XResolution' => '72/1',
							'YResolution' => '72/1',
							'ResolutionUnit' => 2,
							'YCbCrPositioning' => 1,
						]
					]
				]
			],
			// Lossless red square at max resolution to confirm height decoding
			[
				__DIR__ . '/../../data/media/webp_ll_large.webp',
				[
					'width' => 16383,
					'height' => 16383,
					'bits' => 8,
					'metadata' => [
						'compression' => 'lossless',
						'width' => 16383,
						'height' => 16383,
						'bits' => 8,
						'metadata' => [
							'_MW_WEBP_VERSION' => 3,
						],
					],
				]
			],

			// Error cases
			[
				__FILE__,
				[ 'metadata' => [ '_error' => '0' ] ],
			],
		];
	}

	/**
	 * Tests the WebP MIME detection. This should really be a separate test, but sticking it
	 * here for now.
	 *
	 * @dataProvider provideTestGetMimeType
	 */
	public function testGuessMimeType( $path ) {
		$mime = $this->getServiceContainer()->getMimeAnalyzer();
		$this->assertEquals( 'image/webp', $mime->guessMimeType( $path, false ) );
	}

	public static function provideTestGetMimeType() {
		return [
				// Public domain files from https://developers.google.com/speed/webp/gallery2
				[ __DIR__ . '/../../data/media/2_webp_a.webp' ],
				[ __DIR__ . '/../../data/media/2_webp_ll.webp' ],
				[ __DIR__ . '/../../data/media/webp_animated.webp' ],
		];
	}

	/**
	 * @dataProvider provideTestIsAnimated
	 * @covers \MediaWiki\Media\WebPHandler::isAnimatedImage
	 */
	public function testIsAnimated( $filename, $expected ) {
		$file = $this->dataFile( $filename, 'image/webp' );
		$handler = new WebPHandler();
		$this->assertSame( $expected, $handler->isAnimatedImage( $file ) );
	}

	public static function provideTestIsAnimated() {
		return [
			[ 'webp_animated.webp', true ],
			[ '2_webp_a.webp', false ],
			[ '2_webp_ll.webp', false ],
		];
	}

	/**
	 * @dataProvider provideTestCanAnimateThumbnail
	 * @covers \MediaWiki\Media\WebPHandler::canAnimateThumbnail
	 */
	public function testCanAnimateThumbnail( $webpThumbnailType, $maxAnimatedWebPArea, $expected ) {
		$this->overrideConfigValue( MainConfigNames::WebPThumbnailType, $webpThumbnailType );
		$this->overrideConfigValue( MainConfigNames::MaxAnimatedWebPArea, $maxAnimatedWebPArea );
		// webp_animated.webp is 300x225 with 100 frames, so its image area is 6,750,000
		$file = $this->dataFile( 'webp_animated.webp', 'image/webp' );
		$handler = new WebPHandler();
		$this->assertSame( $expected, $handler->canAnimateThumbnail( $file ) );
	}

	public static function provideTestCanAnimateThumbnail() {
		return [
			'webp output, under area limit' => [ [ 'webp', 'image/webp' ], 12_500_000, true ],
			'webp output, over area limit' => [ [ 'webp', 'image/webp' ], 1_000_000, false ],
			'png output, under area limit' => [ [ 'png', 'image/png' ], 12_500_000, false ],
			'png output, over area limit' => [ [ 'png', 'image/png' ], 1_000_000, false ],
		];
	}

	/**
	 * @dataProvider provideTestGetThumbType
	 * @covers \MediaWiki\Media\WebPHandler::getThumbType
	 */
	public function testGetThumbType( $webpThumbnailType ) {
		$this->overrideConfigValue( MainConfigNames::WebPThumbnailType, $webpThumbnailType );
		$handler = new WebPHandler();
		$this->assertSame( $webpThumbnailType, $handler->getThumbType( 'webp', 'image/webp' ) );
	}

	public static function provideTestGetThumbType() {
		return [
			'webp' => [ [ 'webp', 'image/webp' ] ],
			'png' => [ [ 'png', 'image/png' ] ],
		];
	}
}

/* Python code to extract a header and convert to PHP format:
 * print '"%s"' % ''.implode( '\\x%02X' % ord(c) for c in urllib.urlopen(url).read(36) )
 */
