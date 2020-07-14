<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUpload
 */
class ApiUploadTest extends ApiUploadTestCase {
	private function filePath( $fileName ) {
		return __DIR__ . '/../../data/media/' . $fileName;
	}

	protected function setUp() : void {
		parent::setUp();
		$this->tablesUsed[] = 'watchlist'; // This test might interfere with watchlists test.
		$this->tablesUsed[] = 'watchlist_expiry';
		$this->tablesUsed = array_merge( $this->tablesUsed, LocalFile::getQueryInfo()['tables'] );
		$this->setService( 'RepoGroup', new RepoGroup(
			[
				'class' => LocalRepo::class,
				'name' => 'temp',
				'backend' => new FSFileBackend( [
					'name' => 'temp-backend',
					'wikiId' => wfWikiID(),
					'basePath' => $this->getNewTempDirectory()
				] )
			],
			[],
			null
		) );
		$this->resetServices();

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	public function testUploadRequiresToken() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'The "token" parameter must be set' );
		$this->doApiRequest( [
			'action' => 'upload'
		] );
	}

	public function testUploadMissingParams() {
		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'One of the parameters "filekey", "file" and "url" is required' );
		$this->doApiRequestWithToken( [
			'action' => 'upload',
		], null, self::$users['uploader']->getUser() );
	}

	public function testUploadWithWatch() {
		$fileName = 'TestUpload.jpg';
		$mimeType = 'image/jpeg';
		$filePath = $this->filePath( 'yuv420.jpg' );
		$title = Title::newFromText( $fileName, NS_FILE );
		$user = self::$users['uploader']->getUser();

		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath );
		list( $result ) = $this->doApiRequestWithToken( [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
			'watchlist' => 'watch',
			'watchlistexpiry' => '99990123000000',
		], null, $user );

		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertSame( filesize( $filePath ), (int)$result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertTrue( $user->isTempWatched( $title ) );
	}

	public function testUploadZeroLength() {
		$filePath = $this->getNewTempFile();
		$mimeType = 'image/jpeg';
		$fileName = "ApiTestUploadZeroLength.jpg";

		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath );

		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'The file you submitted was empty' );
		$this->doApiRequestWithToken( [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		], null, self::$users['uploader']->getUser() );
	}

	public function testUploadSameFileName() {
		$fileName = 'TestUploadSameFileName.jpg';
		$mimeType = 'image/jpeg';
		$filePaths = [
			$this->filePath( 'yuv420.jpg' ),
			$this->filePath( 'yuv444.jpg' )
		];

		// we reuse these params
		$params = [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		];

		// first upload .... should succeed

		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[0] );
		list( $result ) = $this->doApiRequestWithToken( $params, null,
			self::$users['uploader']->getUser() );
		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );

		// second upload with the same name (but different content)

		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[1] );
		list( $result ) = $this->doApiRequestWithToken( $params, null,
			self::$users['uploader']->getUser() );
		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertArrayHasKey( 'warnings', $result['upload'] );
		$this->assertArrayHasKey( 'exists', $result['upload']['warnings'] );
	}

	public function testUploadSameContent() {
		$fileNames = [ 'TestUploadSameContent_1.jpg', 'TestUploadSameContent_2.jpg' ];
		$mimeType = 'image/jpeg';
		$filePath = $this->filePath( 'yuv420.jpg' );

		// first upload .... should succeed
		$this->fakeUploadFile( 'file', $fileNames[0], $mimeType, $filePath );
		list( $result ) = $this->doApiRequestWithToken( [
			'action' => 'upload',
			'filename' => $fileNames[0],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for {$fileNames[0]}",
		], null, self::$users['uploader']->getUser() );
		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );

		// second upload with the same content (but different name)
		$this->fakeUploadFile( 'file', $fileNames[1], $mimeType, $filePath );
		list( $result ) = $this->doApiRequestWithToken( [
				'action' => 'upload',
				'filename' => $fileNames[1],
				'file' => 'dummy content',
				'comment' => 'dummy comment',
				'text' => "This is the page text for {$fileNames[1]}",
			], null, self::$users['uploader']->getUser() );

		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertArrayHasKey( 'warnings', $result['upload'] );
		$this->assertArrayHasKey( 'duplicate', $result['upload']['warnings'] );
		$this->assertArrayEquals( [ $fileNames[0] ], $result['upload']['warnings']['duplicate'] );
		$this->assertArrayNotHasKey( 'exists', $result['upload']['warnings'] );
	}

	public function testUploadStash() {
		$fileName = 'TestUploadStash.jpg';
		$mimeType = 'image/jpeg';
		$filePath = $this->filePath( 'yuv420.jpg' );

		$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath );
		list( $result ) = $this->doApiRequestWithToken( [
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		], null, self::$users['uploader']->getUser() );

		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertSame( filesize( $filePath ), (int)$result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertArrayHasKey( 'filekey', $result['upload'] );
		$this->assertEquals( $result['upload']['sessionkey'], $result['upload']['filekey'] );
		$filekey = $result['upload']['filekey'];

		// it should be visible from Special:UploadStash
		// XXX ...but how to test this, with a fake WebRequest with the session?

		// now we should try to release the file from stash
		$this->clearFakeUploads();
		list( $result ) = $this->doApiRequestWithToken( [
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		], null, self::$users['uploader']->getUser() );
		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );
	}

	public function testUploadChunks() {
		$fileName = 'TestUploadChunks.jpg';
		$mimeType = 'image/jpeg';
		$filePath = $this->filePath( 'yuv420.jpg' );
		$fileSize = filesize( $filePath );
		$chunkSize = 20 * 1024; // The file is ~60kB, use 20kB chunks

		$this->setMwGlobals( [
			'wgMinUploadChunkSize' => $chunkSize
		] );

		// Base upload params:
		$params = [
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'filesize' => $fileSize,
			'offset' => 0,
		];

		// Upload chunks
		$handle = fopen( $filePath, "r" );
		$resultOffset = 0;
		$filekey = false;
		while ( !feof( $handle ) ) {
			$chunkData = fread( $handle, $chunkSize );

			// Upload the current chunk into the $_FILE object:
			$this->fakeUploadChunk( 'chunk', 'blob', $mimeType, $chunkData );
			if ( !$filekey ) {
				list( $result ) = $this->doApiRequestWithToken( $params, null,
					self::$users['uploader']->getUser() );
				// Make sure we got a valid chunk continue:
				$this->assertArrayHasKey( 'upload', $result );
				$this->assertArrayHasKey( 'filekey', $result['upload'] );
				$this->assertEquals( 'Continue', $result['upload']['result'] );
				$this->assertEquals( $chunkSize, $result['upload']['offset'] );

				$filekey = $result['upload']['filekey'];
				$resultOffset = $result['upload']['offset'];
			} else {
				// Filekey set to chunk session
				$params['filekey'] = $filekey;
				// Update the offset ( always add chunkSize for subquent chunks
				// should be in-sync with $result['upload']['offset'] )
				$params['offset'] += $chunkSize;
				// Make sure param offset is insync with resultOffset:
				$this->assertEquals( $resultOffset, $params['offset'] );
				// Upload current chunk
				list( $result ) = $this->doApiRequestWithToken( $params, null,
					self::$users['uploader']->getUser() );
				// Make sure we got a valid chunk continue:
				$this->assertArrayHasKey( 'upload', $result );
				$this->assertArrayHasKey( 'filekey', $result['upload'] );

				// Check if we were on the last chunk:
				if ( $params['offset'] + $chunkSize >= $fileSize ) {
					$this->assertEquals( 'Success', $result['upload']['result'] );
					break;
				} else {
					$this->assertEquals( 'Continue', $result['upload']['result'] );
					$resultOffset = $result['upload']['offset'];
				}
			}
		}
		fclose( $handle );

		// Check that we got a valid file result:
		$this->assertEquals( $fileSize, $result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertArrayHasKey( 'filekey', $result['upload'] );
		$filekey = $result['upload']['filekey'];

		// Now we should try to release the file from stash
		$this->clearFakeUploads();
		list( $result ) = $this->doApiRequestWithToken( [
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		], null, self::$users['uploader']->getUser() );
		$this->assertArrayHasKey( 'upload', $result );
		$this->assertEquals( 'Success', $result['upload']['result'] );
	}
}
