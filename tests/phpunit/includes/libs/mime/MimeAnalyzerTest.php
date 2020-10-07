<?php
/**
 * @group Media
 * @covers MimeAnalyzer
 */
class MimeAnalyzerTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var MimeAnalyzer */
	private $mimeAnalyzer;

	protected function setUp() : void {
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
		parent::setUp();
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

			// XXX: It's probably wrong (as in: confusing and error-prone) for
			//   ::improveTypeFromExtension to return null (T253483).
			//   This test case exists to ensure that any changes to the existing
			//   behavior are intentional.
			[ 'no_such_extension', 'unknown/unknown', null ],
		];
	}

	/**
	 * Test to make sure that encoder=ffmpeg2theora doesn't trigger
	 * MEDIATYPE_VIDEO (T65584)
	 */
	public function testOggRecognize() {
		$oggFile = __DIR__ . '/../../../data/media/say-test.ogg';
		$actualType = $this->mimeAnalyzer->getMediaType( $oggFile, 'application/ogg' );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that Opus audio files don't trigger
	 * MEDIATYPE_MULTIMEDIA (bug T151352)
	 */
	public function testOpusRecognize() {
		$oggFile = __DIR__ . '/../../../data/media/say-test.opus';
		$actualType = $this->mimeAnalyzer->getMediaType( $oggFile, 'application/ogg' );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that mp3 files are detected as audio type
	 */
	public function testMP3AsAudio() {
		$file = __DIR__ . '/../../../data/media/say-test-with-id3.mp3';
		$actualType = $this->mimeAnalyzer->getMediaType( $file );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that MP3 with id3 tag is recognized
	 */
	public function testMP3WithID3Recognize() {
		$file = __DIR__ . '/../../../data/media/say-test-with-id3.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-1 sample rates)
	 */
	public function testMP3NoID3RecognizeMPEG1() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg1.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-2 sample rates)
	 */
	public function testMP3NoID3RecognizeMPEG2() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg2.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-2.5 sample rates)
	 */
	public function testMP3NoID3RecognizeMPEG2_5() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg2.5.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * A ZIP file embedded in the middle of a .doc file is still a Word Document.
	 */
	public function testZipInDoc() {
		$file = __DIR__ . '/../../../data/media/zip-in-doc.doc';
		$actualType = $this->doGuessMimeType( [ $file, 'doc' ] );
		$this->assertEquals( 'application/msword', $actualType );
	}

	/**
	 * @covers MimeAnalyzer::detectZipType
	 * @dataProvider provideOpendocumentsformatHeaders
	 */
	public function testDetectZipTypeRecognizesOpendocuments( $expected, $header ) {
		$this->assertEquals(
			$expected,
			$this->mimeAnalyzer->detectZipType( $header )
		);
	}

	/**
	 * An ODF file is a ZIP file of multiple files. The first one being
	 * 'mimetype' and is not compressed.
	 */
	public function provideOpendocumentsformatHeaders() {
		$thirtychars = str_repeat( 0, 30 );
		return [
			'Database front end document header based on ODF 1.2' => [
				'application/vnd.oasis.opendocument.base',
				$thirtychars . 'mimetypeapplication/vnd.oasis.opendocument.basePK',
			],
		];
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
					'definition of a ZIP file. This is mostly a sanity check of the ' .
					'above two tests.',
				'zip-kind-of-valid.png',
				'application/zip',
			],
			[
				'As above with non-zero comment length',
				'zip-kind-of-valid-2.png',
				'application/zip',
			],
		];
	}

	/** @dataProvider providePngZipConfusion */
	public function testPngZipConfusion( $description, $fileName, $expectedType ) {
		$file = __DIR__ . '/../../../data/media/' . $fileName;
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
		$mimeAnalyzer = new MimeAnalyzer( [
			'infoFile' => MimeAnalyzer::USE_INTERNAL,
			'typeFile' => MimeAnalyzer::USE_INTERNAL,
			'xmlTypes' => [],
			'initCallback' => function ( $instance ) {
				$instance->addExtraTypes( 'fake/mime fake_extension' );
				$instance->addExtraInfo( 'fake/mime [OFFICE]' );
				$instance->mExtToMime[ 'no_such_extension' ] = 'fake/mime';
			},
		] );
		$this->assertSame( MEDIATYPE_OFFICE, $mimeAnalyzer->findMediaType( '.fake_extension' ) );
		$this->assertSame(
			'fake/mime', $mimeAnalyzer->getMimeTypeFromExtensionOrNull( 'no_such_extension' ) );
	}

	public function testGetMimeTypesFromExtension() {
		$this->assertSame(
			[ 'video/webm', 'audio/webm' ], $this->mimeAnalyzer->getMimeTypesFromExtension( 'webm' ) );
		$this->assertSame( [], $this->mimeAnalyzer->getMimeTypesFromExtension( 'no_such_extension' ) );
	}

	public function testGetMimeTypeFromExtensionOrNull() {
		$this->assertSame( 'video/webm', $this->mimeAnalyzer->getMimeTypeFromExtensionOrNull( 'webm' ) );
		$this->assertNull( $this->mimeAnalyzer->getMimeTypeFromExtensionOrNull( 'no_such_extension' ) );
	}

	public function testGetExtensionsFromMimeType() {
		$this->assertSame(
			[ 'sgml', 'sgm' ], $this->mimeAnalyzer->getExtensionsFromMimeType( 'text/sgml' ) );
		$this->assertSame( [], $this->mimeAnalyzer->getExtensionsFromMimeType( 'fake/mime' ) );
	}

	public function testGetExtensionFromMimeTypeOrNull() {
		$this->assertSame( 'sgml', $this->mimeAnalyzer->getExtensionFromMimeTypeOrNull( 'text/sgml' ) );
		$this->assertNull( $this->mimeAnalyzer->getExtensionFromMimeTypeOrNull( 'fake/mime' ) );
	}
}
