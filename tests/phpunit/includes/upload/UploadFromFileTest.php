<?php

use MediaWiki\Request\FauxRequest;

/**
 * @group Database
 *
 * @covers \UploadFromFile
 */
class UploadFromFileTest extends MediaWikiIntegrationTestCase {

	public function testUploadFile() {
		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/../../data/media/1bit-png.png';
		$filename = 'TestFile.png';

		// file is removed after upload, provide a copy to allow the delete
		$tempFile = $this->getNewTempFile();
		file_put_contents( $tempFile, file_get_contents( $filepath ) );

		$request = new FauxRequest( [], true );
		$request->setUpload( 'file', [
			'name' => $filename,
			'type' => 'image/png',
			'tmp_name' => $tempFile,
			'size' => filesize( $tempFile ),
			'error' => '',
		] );

		$upload = new UploadFromFile( $user );
		$upload->initialize(
			$filename,
			$request->getUpload( 'file' )
		);
		$status = $upload->performUpload( 'comment', 'page', false, $user );
		$this->assertStatusGood( $status );
	}
}
