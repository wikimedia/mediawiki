<?php
/**
 * @group Upload
 */
class UploadTest extends PHPUnit_Framework_TestCase {
	protected $upload;
	

	function setUp() {
		parent::setup();
		$this->upload = new UploadTestHandler;	
	}
	
	/**
	 * Test various forms of valid and invalid titles that can be supplied.
	 */
	public function testTitleValidation() {

		
		/* Test a valid title */
		$this->assertUploadTitleAndCode( 'ValidTitle.jpg', 
			'ValidTitle.jpg', UploadTestHandler::OK,
			'upload valid title' );
			
		/* A title with a slash */
		$this->assertUploadTitleAndCode( 'A/B.jpg',
			'B.jpg', UploadTestHandler::OK,
			'upload title with slash' );
		
		/* A title with illegal char */
		$this->assertUploadTitleAndCode( 'A:B.jpg',
			'A-B.jpg', UploadTestHandler::OK,
			'upload title with colon' );
			
		/* A title without extension */
		$this->assertUploadTitleAndCode( 'A',
			null, UploadTestHandler::FILETYPE_MISSING,
			'upload title without extension' );
		
		/* A title with no basename */
		$this->assertUploadTitleAndCode( '.A',
			null, UploadTestHandler::MIN_LENGTH_PARTNAME,
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
		if ( $code == UploadTestHandler::OK ) {
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
		$this->assertEquals( UploadTestHandler::EMPTY_FILE,
			$result['status'], 
			'upload empty file' );
	}

}

class UploadTestHandler extends UploadBase {
		public function initializeFromRequest( &$request ) {}
		public function testTitleValidation( $name ) {
			$this->mTitle = false;
			$this->mDesiredDestName = $name;
			$this->getTitle();
			return $this->mTitleError;
		}


}