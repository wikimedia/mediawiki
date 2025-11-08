<?php

use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\MainConfigNames;

/**
 * @group Upload
 */
class UploadBaseTest extends MediaWikiIntegrationTestCase {

	/** @var UploadTestHandler */
	protected $upload;

	protected function setUp(): void {
		parent::setUp();

		$this->upload = new UploadTestHandler;

		$this->overrideConfigValue(
			MainConfigNames::InterwikiCache,
			ClassicInterwikiLookup::buildCdbHash( [
				// no entries, no interwiki prefixes
			] )
		);
	}

	/**
	 * First checks the return code
	 * of UploadBase::getTitle() and then the actual returned title
	 *
	 * @dataProvider provideTestTitleValidation
	 * @covers \UploadBase::getTitle
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
		return [
			/* Test a valid title */
			[ 'ValidTitle.jpg', 'ValidTitle.jpg', UploadBase::OK,
				'upload valid title' ],
			/* A title with a slash */
			[ 'A/B.jpg', 'A-B.jpg', UploadBase::OK,
				'upload title with slash' ],
			/* A title with illegal char */
			[ 'A:B.jpg', 'A-B.jpg', UploadBase::OK,
				'upload title with colon' ],
			/* Stripping leading File: prefix */
			[ 'File:C.jpg', 'C.jpg', UploadBase::OK,
				'upload title with File prefix' ],
			/* Test illegal suggested title (r94601) */
			[ '%281%29.JPG', null, UploadBase::ILLEGAL_FILENAME,
				'illegal title for upload' ],
			/* A title without extension */
			[ 'A', null, UploadBase::FILETYPE_MISSING,
				'upload title without extension' ],
			/* A title with no basename */
			[ '.jpg', null, UploadBase::MIN_LENGTH_PARTNAME,
				'upload title without basename' ],
			/* A title that is longer than 255 bytes */
			[ str_repeat( 'a', 255 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 255 bytes' ],
			/* A title that is longer than 240 bytes */
			[ str_repeat( 'a', 240 ) . '.jpg', null, UploadBase::FILENAME_TOO_LONG,
				'upload title longer than 240 bytes' ],
		];
	}

	/**
	 * Test the upload verification functions
	 * @covers \UploadBase::verifyUpload
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
		$filename = $this->getNewTempFile();

		$fh = fopen( $filename, 'w' );
		ftruncate( $fh, $size );
		fclose( $fh );

		return $filename;
	}

	/**
	 * @covers \UploadBase::verifyUpload
	 *
	 * test uploading a 100 bytes file with $wgMaxUploadSize = 100
	 *
	 * This method should be abstracted so we can test different settings.
	 */
	public function testMaxUploadSize() {
		$this->overrideConfigValues( [
			MainConfigNames::MaxUploadSize => 100,
			MainConfigNames::FileExtensions => [
				'txt',
			],
		] );

		$filename = $this->createFileOfSize( 100 );
		$this->upload->initializePathInfo( basename( $filename ) . '.txt', $filename, 100 );
		$result = $this->upload->verifyUpload();

		$this->assertEquals(
			[ 'status' => UploadBase::OK ],
			$result
		);
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
