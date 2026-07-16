<?php

use MediaWiki\Request\FauxRequest;
use MediaWiki\Upload\UploadFromChunks;

/**
 * @group Database
 *
 * @covers \MediaWiki\Upload\UploadFromChunks
 */
class UploadFromChunksTest extends MediaWikiIntegrationTestCase {

	public function testUploadWithTwoChunks() {
		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/../../data/media/1bit-png.png';
		$filename = 'TestChunked.png';
		$filecontent = file_get_contents( $filepath );
		$offset = 0;

		// copy the chunk data to temp location
		$chunkPath = $this->getNewTempFile();
		$chunkSize = 100;
		file_put_contents( $chunkPath, substr( $filecontent, $offset, $chunkSize ) );

		$request = new FauxRequest( [], true );
		$request->setUpload( 'chunk', [
			'name' => $filename,
			'type' => 'image/png',
			'tmp_name' => $chunkPath,
			'size' => $chunkSize,
			'error' => '',
		] );

		$upload = new UploadFromChunks( $user );
		// handle first chunk
		$upload->initialize(
			$filename,
			$request->getUpload( 'chunk' )
		);
		$status = $upload->tryStashFile( $user, true );
		$this->assertStatusGood( $status );
		$stashFile = $status->getValue();
		$filekey = $stashFile->getFileKey();

		// handle new chunk
		$offset += $chunkSize;
		$chunkPath = $this->getNewTempFile();
		$chunkSize = strlen( $filecontent ) - $offset;
		file_put_contents( $chunkPath, substr( $filecontent, $offset, $chunkSize ) );

		$request->setUpload( 'chunk', [
			'name' => $filename,
			'type' => 'image/png',
			'tmp_name' => $chunkPath,
			'size' => $chunkSize,
			'error' => '',
		] );

		$upload->continueChunks(
			$filename,
			$filekey,
			$request->getUpload( 'chunk' )
		);

		$status = $upload->addChunk(
			$chunkPath,
			$chunkSize,
			$offset
		);
		$this->assertStatusGood( $status );

		$status = $upload->concatenateChunks();
		$this->assertStatusGood( $status );

		$newFilekey = $upload->getStashFile()->getFileKey();
		$this->assertNotSame( $filekey, $newFilekey );
	}

	public function testUploadWholeFileInOneChunk() {
		$user = $this->getTestUser()->getUser();
		$filepath = __DIR__ . '/../../data/media/1bit-png.png';
		$filename = 'TestOnlyOneChunk.png';

		$request = new FauxRequest( [], true );
		$request->setUpload( 'chunk', [
			'name' => $filename,
			'type' => 'image/png',
			'tmp_name' => $filepath,
			'size' => filesize( $filepath ),
			'error' => '',
		] );

		$upload = new UploadFromChunks( $user );
		$upload->initialize(
			$filename,
			$request->getUpload( 'chunk' )
		);
		$status = $upload->tryStashFile( $user, true );
		$this->assertStatusGood( $status );
		$stashFile = $status->getValue();
		$filekey = $stashFile->getFileKey();

		$status = $upload->concatenateChunks();
		$this->assertStatusGood( $status );

		$newFilekey = $upload->getStashFile()->getFileKey();
		$this->assertNotSame( $filekey, $newFilekey );
	}

	public function testUploadWhenRepoFails() {
		$repoMock = $this->createMock( LocalRepo::class );
		$repoMock->expects( $this->any() )
			->method( 'quickImportBatch' )
			->willReturn( Status::newFatal( 'Some repo error' ) );
		$upload = new UploadFromChunks( $this->getTestUser()->getUser(), false, $repoMock );

		// Add some data to a chunk file.
		$chunkPath = $this->getNewTempFile();
		$chuckContents = 'test';
		file_put_contents( $chunkPath, $chuckContents );

		// Try to add the chunk, and confirm that the error from the repo is passed back through.
		$chunkStatus = $upload->addChunk( $chunkPath, strlen( $chuckContents ), 0 );
		$this->assertFalse( $chunkStatus->isOK() );
		$this->assertStringContainsString( 'Some repo error', $chunkStatus->getMessages()[0]->getKey() );
	}
}
