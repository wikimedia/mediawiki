<?php
/**
 * @group Upload
 */
class UploadTest extends MediaWikiTestCase {
	protected $upload;


	function setUp() {
		global $wgContLang;
		parent::setUp();
		$wgContLang = Language::factory( 'en' );
		$this->upload = new UploadTestHandler;
	}

	/**
	 * Test various forms of valid and invalid titles that can be supplied.
	 */
	public function testTitleValidation() {


		/* Test a valid title */
		$this->assertUploadTitleAndCode( 'ValidTitle.jpg',
			'ValidTitle.jpg', UploadBase::OK,
			'upload valid title' );

		/* A title with a slash */
		$this->assertUploadTitleAndCode( 'A/B.jpg',
			'B.jpg', UploadBase::OK,
			'upload title with slash' );

		/* A title with illegal char */
		$this->assertUploadTitleAndCode( 'A:B.jpg',
			'A-B.jpg', UploadBase::OK,
			'upload title with colon' );

		/* A title without extension */
		$this->assertUploadTitleAndCode( 'A',
			null, UploadBase::FILETYPE_MISSING,
			'upload title without extension' );

		/* A title with no basename */
		$this->assertUploadTitleAndCode( '.jpg',
			null, UploadBase::MIN_LENGTH_PARTNAME,
			'upload title without basename' );

	}
	/**
	 * Helper function for testTitleValidation. First checks the return code
	 * of UploadBase::getTitle() and then the actual returned titl
	 */
	private function assertUploadTitleAndCode( $srcFilename, $dstFilename, $code, $msg ) {
		/* Check the result code */
		$this->assertEquals( $code,
			$this->upload->testTitleValidation( $srcFilename ),
			"$msg code" );

		/* If we expect a valid title, check the title itself. */
		if ( $code == UploadBase::OK ) {
			$this->assertEquals( $dstFilename,
				$this->upload->getTitle()->getText(),
				"$msg text" );
		}
	}

	/**
	 * Test the upload verification functions
	 */
	public function testVerifyUpload() {
		/* Setup with zero file size */
		$this->upload->initializePathInfo( '', '', 0 );
		$result = $this->upload->verifyUpload();
		$this->assertEquals( UploadBase::EMPTY_FILE,
			$result['status'],
			'upload empty file' );
	}

	// Helper used to create an empty file of size $size.
	private function createFileOfSize( $size ) {
		$filename = '/tmp/mwuploadtest-' . posix_getpid() . '.txt' ;

		$fh = fopen( $filename, 'w' );
		fseek( $fh, $size-1, SEEK_SET);
		fwrite( $fh, 0x00 );
		fclose( $fh );

		return $filename;
	}

	/**
	 * test uploading a 100 bytes file with wgMaxUploadSize = 100
	 *
	 * This method should be abstracted so we can test different settings.
	 */

	public function testMaxUploadSize() {
		global $wgMaxUploadSize;
		$savedGlobal = $wgMaxUploadSize; // save global
		global $wgFileExtensions;
		$wgFileExtensions[] = 'txt';

		$wgMaxUploadSize = 100;

		$filename = $this->createFileOfSize( $wgMaxUploadSize );
		$this->upload->initializePathInfo( basename($filename), $filename, 100 );
		$result = $this->upload->verifyUpload();
		unlink( $filename );

		$this->assertEquals(
			array( 'status' => UploadTestHandler::OK ), $result );

		$wgMaxUploadSize = $savedGlobal;  // restore global
	}
}

class UploadTestHandler extends UploadBase {
		public function initializeFromRequest( &$request ) { }
		public function testTitleValidation( $name ) {
			$this->mTitle = false;
			$this->mDesiredDestName = $name;
			$this->getTitle();
			return $this->mTitleError;
		}


}
