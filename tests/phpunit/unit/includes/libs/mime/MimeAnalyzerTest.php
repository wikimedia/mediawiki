<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Media
 * @covers MimeAnalyzer
 */
class MimeAnalyzerTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var MimeAnalyzer */
	private $mimeAnalyzer;

	protected function setUp(): void {
		parent::setUp();
		$this->mimeAnalyzer = new MimeAnalyzer( [
			'infoFile' => MimeAnalyzer::USE_INTERNAL,
			'typeFile' => MimeAnalyzer::USE_INTERNAL,
			'xmlTypes' => [
				'http://www.w3.org/2000/svg:svg' => 'image/svg+xml',
				'svg' => 'image/svg+xml',
				'http://www.lysator.liu.se/~alla/dia/:diagram' => 'application/x-dia-diagram',
				'http://www.w3.org/1999/xhtml:html' => 'text/html', // application/xhtml+xml?
				'html' => 'text/html', // application/xhtml+xml?
			]
		] );
	}

	private function doGuessMimeType( array $parameters = [] ) {
		$class = new ReflectionClass( get_class( $this->mimeAnalyzer ) );
		$method = $class->getMethod( 'doGuessMimeType' );
		$method->setAccessible( true );
		return $method->invokeArgs( $this->mimeAnalyzer, $parameters );
	}

	/**
	 * @dataProvider providerImproveTypeFromExtension
	 * @param string $ext File extension (no leading dot)
	 * @param string $oldMime Initially detected MIME
	 * @param string|null $expectedMime MIME type after taking extension into account
	 */
	public function testImproveTypeFromExtension( $ext, $oldMime, $expectedMime ) {
		$actualMime = $this->mimeAnalyzer->improveTypeFromExtension( $oldMime, $ext );
		$this->assertEquals( $expectedMime, $actualMime );
	}

	public function providerImproveTypeFromExtension() {
		return [
			[ 'gif', 'image/gif', 'image/gif' ],
			[ 'gif', 'unknown/unknown', 'unknown/unknown' ],
			[ 'wrl', 'unknown/unknown', 'model/vrml' ],
			[ 'txt', 'text/plain', 'text/plain' ],
			[ 'csv', 'text/plain', 'text/csv' ],
			[ 'tsv', 'text/plain', 'text/tab-separated-values' ],
			[ 'js', 'text/javascript', 'application/javascript' ],
			[ 'js', 'application/x-javascript', 'application/javascript' ],
			[ 'json', 'text/plain', 'application/json' ],
			[ 'foo', 'application/x-opc+zip', 'application/zip' ],
			[ 'docx', 'application/x-opc+zip',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ],
			[ 'djvu', 'image/x-djvu', 'image/vnd.djvu' ],
			[ 'wav', 'audio/wav', 'audio/wav' ],
			[ 'odt', 'application/vnd.oasis.opendocument',
				'application/vnd.oasis.opendocument.text' ],

			// XXX: It's probably wrong (as in: confusing and error-prone) for
			//   ::improveTypeFromExtension to return null (T253483).
			//   This test case exists to ensure that any changes to the existing
			//   behavior are intentional.
			[ 'no_such_extension', 'unknown/unknown', null ],
		];
	}

	public function provideGetMediaType() {
		// Make sure encoder=ffmpeg2theora don't trigger MEDIATYPE_VIDEO (T65584)
		yield 'Recognize ogg' => [ 'say-test.ogg', 'application/ogg', MEDIATYPE_AUDIO ];

		// Make sure Opus audio files don't trigger MEDIATYPE_MULTIMEDIA (T151352)
		yield 'Recognize Opus' => [ 'say-test.opus', 'application/ogg', MEDIATYPE_AUDIO ];

		// Make sure mp3 files are detected as audio type
		yield 'Recognize mp3' => [ 'say-test-with-id3.mp3', null, MEDIATYPE_AUDIO ];
	}

	/**
	 * @dataProvider provideGetMediaType
	 */
	public function testGetMediaType( $file, $mime, $expectType ) {
		$file = __DIR__ . '/../../../../data/media/' . $file;
		$this->assertEquals(
			$expectType,
			$this->mimeAnalyzer->getMediaType( $file, $mime )
		);
	}

	public function provideDoGuessMimeType() {
		// Make sure MP3 with id3 tag is recognized
		yield 'Recognize mp3 with id3' => [ 'say-test-with-id3.mp3', 'mp3', 'audio/mpeg' ];

		// Make sure MP3 without id3 tag is recognized (MPEG-1 sample rates)
		yield 'Recognize mp3 no id3, MPEG-1' => [ 'say-test-mpeg1.mp3', 'mp3', 'audio/mpeg' ];

		// Make sure MP3 without id3 tag is recognized (MPEG-2 sample rates)
		yield 'Recognize mp3 no id3, MPEG-2' => [ 'say-test-mpeg2.mp3', 'mp3', 'audio/mpeg' ];

		// Make sure MP3 without id3 tag is recognized (MPEG-2.5 sample rates)
		yield 'Recognize mp3 no id3, MPEG-2.5' => [ 'say-test-mpeg2.5.mp3', 'mp3', 'audio/mpeg' ];

		// A ZIP file embedded in the middle of a .doc file is still a Word Document
		yield 'ZIP in DOC' => [ 'zip-in-doc.doc', 'doc', 'application/msword' ];

		yield 'Jpeg2000, lossless' => [ 'jpeg2000-lossless.jp2', 'jp2', 'image/jp2' ];

		yield 'Jpeg2000, part 2' => [ 'jpeg2000-profile.jpf', 'jpf', 'image/jpx' ];
	}

	/**
	 * @dataProvider provideDoGuessMimeType
	 */
	public function testDoGuessMimeType( $file, $ext, $expectType ) {
		$file = __DIR__ . '/../../../../data/media/' . $file;
		$this->assertEquals(
			$expectType,
			$this->doGuessMimeType( [ $file, $ext ] )
		);
	}

	public static function provideDetectZipTypeFromFile() {
		return [
			'[Content_Type].xml at end (T291750)' => [
				'type-at-end.docx',
				'application/x-opc+zip'
			],
			'Typical ODT gives fake generic type' => [
				'lo6-empty.odt',
				'application/vnd.oasis.opendocument'
			],
			'Ye olde GIFAR vulnerability' => [
				'gifar.gif',
				'application/java',
			],
		];
	}

	/**
	 * @dataProvider provideDetectZipTypeFromFile
	 * @param string $fileName
	 * @param string $expected
	 */
	public function testDetectZipTypeFromFile( $fileName, $expected ) {
		$file = fopen( __DIR__ . '/../../../../data/media/' . $fileName, 'r' );
		$this->assertEquals(
			$expected,
			$this->mimeAnalyzer->detectZipTypeFromFile( $file )
		);
	}

	public function providePngZipConfusion() {
		return [
			[
				'An invalid ZIP file due to the signature being too close to the ' .
					'end to accomodate an EOCDR',
				'zip-sig-near-end.png',
				'image/png',
			],
			[
				'An invalid ZIP file due to the comment length running beyond the ' .
					'end of the file',
				'zip-comment-overflow.png',
				'image/png',
			],
			[
				'A ZIP file similar to the above, but without either of those two ' .
					'problems. Not a valid ZIP file, but it passes MimeAnalyzer\'s ' .
					'definition of a ZIP file. This is mostly a double check of the ' .
					'above two tests.',
				'zip-kind-of-valid.png',
				'application/zip',
			],
			[
				'As above with non-zero comment length',
				'zip-kind-of-valid-2.png',
				'application/zip',
			],
			[
				'Ye olde GIFAR vulnerability',
				'gifar.gif',
				'application/java'
			]
		];
	}

	/** @dataProvider providePngZipConfusion */
	public function testPngZipConfusion( $description, $fileName, $expectedType ) {
		$file = __DIR__ . '/../../../../data/media/' . $fileName;
		$actualType = $this->doGuessMimeType( [ $file, 'png' ] );
		$this->assertEquals( $expectedType, $actualType, $description );
	}

	/**
	 * The empty string is not a MIME type and should not be mapped to a file extension.
	 */
	public function testNoEmptyStringMimeType() {
		$this->assertSame( [], $this->mimeAnalyzer->getExtensionsFromMimeType( '' ) );
	}

	/**
	 * @covers MimeAnalyzer::addExtraTypes
	 * @covers MimeAnalyzer::addExtraInfo
	 */
	public function testAddExtraTypes() {
		$mime = new MimeAnalyzer( [
			'infoFile' => MimeAnalyzer::USE_INTERNAL,
			'typeFile' => MimeAnalyzer::USE_INTERNAL,
			'xmlTypes' => [],
			'initCallback' => static function ( $instance ) {
				$instance->addExtraTypes( 'fake/mime fake_extension' );
				$instance->addExtraInfo( 'fake/mime [OFFICE]' );
				$instance->mExtToMime[ 'no_such_extension' ] = 'fake/mime';
			},
		] );
		$this->assertSame( [ 'fake/mime' ], $mime->getMimeTypesFromExtension( 'fake_extension' ) );
		$this->assertSame( 'fake/mime', $mime->getMimeTypeFromExtensionOrNull( 'no_such_extension' ) );

		$mimeAccess = TestingAccessWrapper::newFromObject( $mime );
		$this->assertSame( MEDIATYPE_OFFICE, $mimeAccess->findMediaType( '.fake_extension' ) );
	}

	public function testGetMimeTypesFromNoExtension() {
		$this->assertSame( [], $this->mimeAnalyzer->getMimeTypesFromExtension( 'no_such_extension' ) );
	}

	public static function provideFileExtensions() {
		yield 'ttf file extension should output font/sfnt' => [ 'ttf', 'font/sfnt' ];
		yield 'ttf file extension should output application/font-sfnt' => [ 'ttf', 'application/font-sfnt' ];
		yield 'woff file extension should output font/woff' => [ 'woff', 'font/woff' ];
		yield 'woff file extension should output application/font/woff' => [ 'woff', 'application/font-woff' ];
		yield 'woff2 file extension should output font/woff' => [ 'woff2', 'font/woff2' ];
		yield 'woff2 file extension should output application/font/woff' => [ 'woff2', 'application/font-woff2' ];
		yield 'webm file extension should return video/webm' => [ 'webm', 'video/webm' ];
		yield 'webm file extension should return audio/webm' => [ 'webm', 'audio/webm' ];
	}

	/**
	 * @dataProvider provideFileExtensions
	 */
	public function testGetMimeTypesFromExtension( $inputFileExtension, $expectedOutput ) {
		$actualOutput = $this->mimeAnalyzer->getMimeTypesFromExtension( $inputFileExtension );
		$this->assertContains( $expectedOutput, $actualOutput );
	}

	public static function provideFileExtensionsForMimeType() {
		yield 'font/sfnt should output ttf file extension' => [ 'font/sfnt', [ 'ttf' ] ];
		yield 'application/font-sfnt should output ttf file extension' => [ 'application/font-sfnt', [ 'ttf' ] ];
		yield 'font/woff should output woff file extension' => [ 'font/woff', [ 'woff' ] ];
		yield 'application/font-woff should output woff file extension' => [ 'application/font-woff', [ 'woff' ] ];
		yield 'font/woff2 should output woff2 file extension' => [ 'font/woff2', [ 'woff2' ] ];
		yield 'application/font-woff2 should output woff2 file extension' => [ 'application/font-woff2', [ 'woff2' ] ];
		yield 'text/sgml should output sgml file extension' => [ 'text/sgml', [ 'sgml', 'sgm' ] ];
	}

	/**
	 * @dataProvider provideFileExtensionsForMimeType
	 */
	public function testGetExtensionsFromMimeType( $inputMimeType, $expectedOutput ) {
		$actualOutput = $this->mimeAnalyzer->getExtensionsFromMimeType( $inputMimeType );
		$this->assertSame( $expectedOutput, $actualOutput );
	}

	public function testGetMimeTypeFromExtensionOrNull() {
		$this->assertSame( 'video/webm', $this->mimeAnalyzer->getMimeTypeFromExtensionOrNull( 'webm' ) );
		$this->assertNull( $this->mimeAnalyzer->getMimeTypeFromExtensionOrNull( 'no_such_extension' ) );
	}

	public function testGetExtensionsFromFakeMimeType() {
		$this->assertSame( [], $this->mimeAnalyzer->getExtensionsFromMimeType( 'fake/mime' ) );
	}

	public function testGetExtensionFromMimeTypeOrNull() {
		$this->assertSame( 'sgml', $this->mimeAnalyzer->getExtensionFromMimeTypeOrNull( 'text/sgml' ) );
		$this->assertNull( $this->mimeAnalyzer->getExtensionFromMimeTypeOrNull( 'fake/mime' ) );
	}

	public function testIsValidMajorMimeTypeTrue() {
		$this->assertTrue( $this->mimeAnalyzer->isValidMajorMimeType( 'image' ) );
	}

	public function testIsValidMajorMimeTypeFalse() {
		$this->assertFalse( $this->mimeAnalyzer->isValidMajorMimeType( 'font' ) );
	}
}
