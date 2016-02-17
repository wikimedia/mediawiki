<?php
/**
 * @todo Could use a test of extended XMP segments. Hard to find programs that
 * create example files, and creating my own in vim propbably wouldn't
 * serve as a very good "test". (Adobe photoshop probably creates such files
 * but it costs money). The implementation of it currently in MediaWiki is based
 * solely on reading the standard, without any real world test files.
 *
 * @group Media
 * @covers JpegMetadataExtractor
 */
class JpegMetadataExtractorTest extends MediaWikiTestCase {

	protected $filePath;

	protected function setUp() {
		parent::setUp();

		$this->filePath = __DIR__ . '/../../data/media/';
	}

	/**
	 * We also use this test to test padding bytes don't
	 * screw stuff up
	 *
	 * @param string $file Filename
	 *
	 * @dataProvider provideUtf8Comment
	 */
	public function testUtf8Comment( $file ) {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . $file );
		$this->assertEquals( [ 'UTF-8 JPEG Comment — ¼' ], $res['COM'] );
	}

	public static function provideUtf8Comment() {
		return [
			[ 'jpeg-comment-utf.jpg' ],
			[ 'jpeg-padding-even.jpg' ],
			[ 'jpeg-padding-odd.jpg' ],
		];
	}

	/** The file is iso-8859-1, but it should get auto converted */
	public function testIso88591Comment() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-comment-iso8859-1.jpg' );
		$this->assertEquals( [ 'ISO-8859-1 JPEG Comment - ¼' ], $res['COM'] );
	}

	/** Comment values that are non-textual (random binary junk) should not be shown.
	 * The example test file has a comment with a 0x5 byte in it which is a control character
	 * and considered binary junk for our purposes.
	 */
	public function testBinaryCommentStripped() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-comment-binary.jpg' );
		$this->assertEmpty( $res['COM'] );
	}

	/* Very rarely a file can have multiple comments.
	 *   Order of comments is based on order inside the file.
	 */
	public function testMultipleComment() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-comment-multiple.jpg' );
		$this->assertEquals( [ 'foo', 'bar' ], $res['COM'] );
	}

	public function testXMPExtraction() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-xmp-psir.jpg' );
		$expected = file_get_contents( $this->filePath . 'jpeg-xmp-psir.xmp' );
		$this->assertEquals( $expected, $res['XMP'] );
	}

	public function testPSIRExtraction() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-xmp-psir.jpg' );
		$expected = '50686f746f73686f7020332e30003842494d04040000000'
			. '000181c02190004746573741c02190003666f6f1c020000020004';
		$this->assertEquals( $expected, bin2hex( $res['PSIR'][0] ) );
	}

	public function testXMPExtractionAltAppId() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-xmp-alt.jpg' );
		$expected = file_get_contents( $this->filePath . 'jpeg-xmp-psir.xmp' );
		$this->assertEquals( $expected, $res['XMP'] );
	}

	public function testIPTCHashComparisionNoHash() {
		$segments = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-xmp-psir.jpg' );
		$res = JpegMetadataExtractor::doPSIR( $segments['PSIR'][0] );

		$this->assertEquals( 'iptc-no-hash', $res );
	}

	public function testIPTCHashComparisionBadHash() {
		$segments = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-iptc-bad-hash.jpg' );
		$res = JpegMetadataExtractor::doPSIR( $segments['PSIR'][0] );

		$this->assertEquals( 'iptc-bad-hash', $res );
	}

	public function testIPTCHashComparisionGoodHash() {
		$segments = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-iptc-good-hash.jpg' );
		$res = JpegMetadataExtractor::doPSIR( $segments['PSIR'][0] );

		$this->assertEquals( 'iptc-good-hash', $res );
	}

	public function testExifByteOrder() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'exif-user-comment.jpg' );
		$expected = 'BE';
		$this->assertEquals( $expected, $res['byteOrder'] );
	}
}
