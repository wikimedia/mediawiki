<?php

/**
 * @group Upload
 */
class UploadBaseTest extends MediaWikiTestCase {

	/** @var UploadTestHandler */
	protected $upload;

	protected function setUp() {
		global $wgHooks;
		parent::setUp();

		$this->upload = new UploadTestHandler;
		$this->hooks = $wgHooks;
		$wgHooks['InterwikiLoadPrefix'][] = function ( $prefix, &$data ) {
			return false;
		};
	}

	protected function tearDown() {
		global $wgHooks;
		$wgHooks = $this->hooks;

		parent::tearDown();
	}


	/**
	 * First checks the return code
	 * of UploadBase::getTitle() and then the actual returned title
	 *
	 * @dataProvider provideTestTitleValidation
	 * @covers UploadBase::getTitle
	 */
	public function testTitleValidation( $srcFilename, $dstFilename, $code, $msg ) {
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
	 * Test various forms of valid and invalid titles that can be supplied.
	 */
	public static function provideTestTitleValidation() {
		return array(
			/* Test a valid title */
			array( 'ValidTitle.jpg', 'ValidTitle.jpg', UploadBase::OK,
				'upload valid title' ),
			/* A title with a slash */
			array( 'A/B.jpg', 'B.jpg', UploadBase::OK,
				'upload title with slash' ),
			/* A title with illegal char */
			array( 'A:B.jpg', 'A-B.jpg', UploadBase::OK,
				'upload title with colon' ),
			/* Stripping leading File: prefix */
			array( 'File:C.jpg', 'C.jpg', UploadBase::OK,
				'upload title with File prefix' ),
			/* Test illegal suggested title (r94601) */
			array( '%281%29.JPG', null, UploadBase::ILLEGAL_FILENAME,
				'illegal title for upload' ),
			/* A title without extension */
			array( 'A', null, UploadBase::FILETYPE_MISSING,
				'upload title without extension' ),
			/* A title with no basename */
			array( '.jpg', null, UploadBase::MIN_LENGTH_PARTNAME,
				'upload title without basename' ),
			/* A title that is longer than 255 bytes */
			array( str_repeat( 'a', 255 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 255 bytes' ),
			/* A title that is longer than 240 bytes */
			array( str_repeat( 'a', 240 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 240 bytes' ),
		);
	}

	/**
	 * Test the upload verification functions
	 * @covers UploadBase::verifyUpload
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
		$filename = tempnam( wfTempDir(), "mwuploadtest" );

		$fh = fopen( $filename, 'w' );
		ftruncate( $fh, $size );
		fclose( $fh );

		return $filename;
	}

	/**
	 * test uploading a 100 bytes file with $wgMaxUploadSize = 100
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
		$this->upload->initializePathInfo( basename( $filename ) . '.txt', $filename, 100 );
		$result = $this->upload->verifyUpload();
		unlink( $filename );

		$this->assertEquals(
			array( 'status' => UploadBase::OK ), $result );

		$wgMaxUploadSize = $savedGlobal; // restore global
	}
}

class UploadTestHandler extends UploadBase {
	public function initializeFromRequest( &$request ) {
	}

	public function testTitleValidation( $name ) {
		$this->mTitle = false;
		$this->mDesiredDestName = $name;
		$this->mTitleError = UploadBase::OK;
		$this->getTitle();

		return $this->mTitleError;
	}
}
