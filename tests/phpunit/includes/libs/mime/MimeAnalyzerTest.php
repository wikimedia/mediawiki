<?php
/**
 * @group Media
 * @covers MimeAnalyzer
 */
class MimeAnalyzerTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var MimeAnalyzer */
	private $mimeAnalyzer;

	function setUp() {
		global $IP;

		$this->mimeAnalyzer = new MimeAnalyzer( [
			'infoFile' => $IP . "/includes/libs/mime/mime.info",
			'typeFile' => $IP . "/includes/libs/mime/mime.types",
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

	function doGuessMimeType( array $parameters = [] ) {
		$class = new ReflectionClass( get_class( $this->mimeAnalyzer ) );
		$method = $class->getMethod( 'doGuessMimeType' );
		$method->setAccessible( true );
		return $method->invokeArgs( $this->mimeAnalyzer, $parameters );
	}

	/**
	 * @dataProvider providerImproveTypeFromExtension
	 * @param string $ext File extension (no leading dot)
	 * @param string $oldMime Initially detected MIME
	 * @param string $expectedMime MIME type after taking extension into account
	 */
	function testImproveTypeFromExtension( $ext, $oldMime, $expectedMime ) {
		$actualMime = $this->mimeAnalyzer->improveTypeFromExtension( $oldMime, $ext );
		$this->assertEquals( $expectedMime, $actualMime );
	}

	function providerImproveTypeFromExtension() {
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
		];
	}

	/**
	 * Test to make sure that encoder=ffmpeg2theora doesn't trigger
	 * MEDIATYPE_VIDEO (T65584)
	 */
	function testOggRecognize() {
		$oggFile = __DIR__ . '/../../../data/media/say-test.ogg';
		$actualType = $this->mimeAnalyzer->getMediaType( $oggFile, 'application/ogg' );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that Opus audio files don't trigger
	 * MEDIATYPE_MULTIMEDIA (bug T151352)
	 */
	function testOpusRecognize() {
		$oggFile = __DIR__ . '/../../../data/media/say-test.opus';
		$actualType = $this->mimeAnalyzer->getMediaType( $oggFile, 'application/ogg' );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that mp3 files are detected as audio type
	 */
	function testMP3AsAudio() {
		$file = __DIR__ . '/../../../data/media/say-test-with-id3.mp3';
		$actualType = $this->mimeAnalyzer->getMediaType( $file );
		$this->assertEquals( MEDIATYPE_AUDIO, $actualType );
	}

	/**
	 * Test to make sure that MP3 with id3 tag is recognized
	 */
	function testMP3WithID3Recognize() {
		$file = __DIR__ . '/../../../data/media/say-test-with-id3.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-1 sample rates)
	 */
	function testMP3NoID3RecognizeMPEG1() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg1.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-2 sample rates)
	 */
	function testMP3NoID3RecognizeMPEG2() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg2.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * Test to make sure that MP3 without id3 tag is recognized (MPEG-2.5 sample rates)
	 */
	function testMP3NoID3RecognizeMPEG2_5() {
		$file = __DIR__ . '/../../../data/media/say-test-mpeg2.5.mp3';
		$actualType = $this->doGuessMimeType( [ $file, 'mp3' ] );
		$this->assertEquals( 'audio/mpeg', $actualType );
	}

	/**
	 * A ZIP file embedded in the middle of a .doc file is still a Word Document.
	 */
	function testZipInDoc() {
		$file = __DIR__ . '/../../../data/media/zip-in-doc.doc';
		$actualType = $this->doGuessMimeType( [ $file, 'doc' ] );
		$this->assertEquals( 'application/msword', $actualType );
	}
}
