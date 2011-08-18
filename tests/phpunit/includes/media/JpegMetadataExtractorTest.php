<?php
class JpegMetadataExtractorTest extends MediaWikiTestCase {

	public function setUp() {
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';
	}

	public function testUtf8Comment() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-comment-utf.jpg' );
		$this->assertEquals( array( 'UTF-8 JPEG Comment — ¼' ), $res['COM'] );
	}
	/** The file is iso-8859-1, but it should get auto converted */
	public function testIso88591Comment() {
		$res = JpegMetadataExtractor::segmentSplitter( $this->filePath . 'jpeg-comment-iso8859-1.jpg' );
		$this->assertEquals( array( 'ISO-8859-1 JPEG Comment - ¼' ), $res['COM'] );
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
		$this->assertEquals( array( 'foo', 'bar' ), $res['COM'] );
	}
}
