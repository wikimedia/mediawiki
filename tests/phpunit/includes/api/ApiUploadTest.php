<?php
/**
 * n.b. Ensure that you can write to the images/ directory as the
 * user that will run tests.
 *
 * Note for reviewers: this intentionally duplicates functionality already in
 * "ApiSetup" and so on. This framework works better IMO and has less
 * strangeness (such as test cases inheriting from "ApiSetup"...) (and in the
 * case of the other Upload tests, this flat out just actually works... )
 *
 * @todo Port the other Upload tests, and other API tests to this framework
 *
 * @todo Broken test, reports false errors from time to time.
 * See https://phabricator.wikimedia.org/T28169
 *
 * @todo This is pretty sucky... needs to be prettified.
 *
 * @group API
 * @group Database
 * @group medium
 * @group Broken
 */
class ApiUploadTest extends ApiTestCaseUpload {
	/**
	 * Testing login
	 * XXX this is a funny way of getting session context
	 */
	public function testLogin() {
		$user = self::$users['uploader'];
		$userName = $user->getUser()->getName();
		$password = $user->getPassword();

		$params = [
			'action' => 'login',
			'lgname' => $userName,
			'lgpassword' => $password
		];
		list( $result, , $session ) = $this->doApiRequest( $params );
		$this->assertArrayHasKey( "login", $result );
		$this->assertArrayHasKey( "result", $result['login'] );
		$this->assertEquals( "NeedToken", $result['login']['result'] );
		$token = $result['login']['token'];

		$params = [
			'action' => 'login',
			'lgtoken' => $token,
			'lgname' => $userName,
			'lgpassword' => $password
		];
		list( $result, , $session ) = $this->doApiRequest( $params, $session );
		$this->assertArrayHasKey( "login", $result );
		$this->assertArrayHasKey( "result", $result['login'] );
		$this->assertEquals( "Success", $result['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $result['login'] );

		$this->assertNotEmpty( $session, 'API Login must return a session' );

		return $session;
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadRequiresToken( $session ) {
		$exception = false;
		try {
			$this->doApiRequest( [
				'action' => 'upload'
			] );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertEquals( 'The "token" parameter must be set', $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadMissingParams( $session ) {
		$exception = false;
		try {
			$this->doApiRequestWithToken( [
				'action' => 'upload',
			], $session, self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
			$this->assertEquals( "One of the parameters filekey, file, url is required",
				$e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );
	}

	/**
	 * @depends testLogin
	 */
	public function testUpload( $session ) {
		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, $this->getNewTempDirectory() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		/** @var array $filePaths */
		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		];

		$exception = false;
		try {
			list( $result, , ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertEquals( $fileSize, (int)$result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFileName( $fileName );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadZeroLength( $session ) {
		$mimeType = 'image/png';

		$filePath = $this->getNewTempFile();
		$fileName = "apiTestUploadZeroLength.png";

		$this->deleteFileByFileName( $fileName );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		];

		$exception = false;
		try {
			$this->doApiRequestWithToken( $params, $session, self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$this->assertContains( 'The file you submitted was empty', $e->getMessage() );
			$exception = true;
		}
		$this->assertTrue( $exception );

		// clean up
		$this->deleteFileByFileName( $fileName );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadSameFileName( $session ) {
		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 2, $extension, $this->getNewTempDirectory() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		// we'll reuse this filename
		/** @var array $filePaths */
		$fileName = basename( $filePaths[0] );

		// clear any other files with the same name
		$this->deleteFileByFileName( $fileName );

		// we reuse these params
		$params = [
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		];

		// first upload .... should succeed

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// second upload with the same name (but different content)

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[1] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() ); // FIXME: leaks a temporary file
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertTrue( isset( $result['upload']['warnings'] ) );
		$this->assertTrue( isset( $result['upload']['warnings']['exists'] ) );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFileName( $fileName );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadSameContent( $session ) {
		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, $this->getNewTempDirectory() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		/** @var array $filePaths */
		$fileNames[0] = basename( $filePaths[0] );
		$fileNames[1] = "SameContentAs" . $fileNames[0];

		// clear any other files with the same name or content
		$this->deleteFileByContent( $filePaths[0] );
		$this->deleteFileByFileName( $fileNames[0] );
		$this->deleteFileByFileName( $fileNames[1] );

		// first upload .... should succeed

		$params = [
			'action' => 'upload',
			'filename' => $fileNames[0],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for " . $fileNames[0],
		];

		if ( !$this->fakeUploadFile( 'file', $fileNames[0], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// second upload with the same content (but different name)

		if ( !$this->fakeUploadFile( 'file', $fileNames[1], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = [
			'action' => 'upload',
			'filename' => $fileNames[1],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for " . $fileNames[1],
		];

		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() ); // FIXME: leaks a temporary file
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertTrue( isset( $result['upload']['warnings'] ) );
		$this->assertTrue( isset( $result['upload']['warnings']['duplicate'] ) );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFileName( $fileNames[0] );
		$this->deleteFileByFileName( $fileNames[1] );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadStash( $session ) {
		$this->setMwGlobals( [
			'wgUser' => self::$users['uploader']->getUser(), // @todo FIXME: still used somewhere
		] );

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, $this->getNewTempDirectory() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		/** @var array $filePaths */
		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = [
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		];

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() ); // FIXME: leaks a temporary file
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertFalse( $exception );
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertEquals( $fileSize, (int)$result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertTrue( isset( $result['upload']['filekey'] ) );
		$this->assertEquals( $result['upload']['sessionkey'], $result['upload']['filekey'] );
		$filekey = $result['upload']['filekey'];

		// it should be visible from Special:UploadStash
		// XXX ...but how to test this, with a fake WebRequest with the session?

		// now we should try to release the file from stash
		$params = [
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		];

		$this->clearFakeUploads();
		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception, "No ApiUsageException exception." );

		// clean up
		$this->deleteFileByFileName( $fileName );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadChunks( $session ) {
		$this->setMwGlobals( [
			// @todo FIXME: still used somewhere
			'wgUser' => self::$users['uploader']->getUser(),
		] );

		$chunkSize = 1048576;
		// Download a large image file
		// (using RandomImageGenerator for large files is not stable)
		// @todo Don't download files from wikimedia.org
		$mimeType = 'image/jpeg';
		$url = 'http://upload.wikimedia.org/wikipedia/commons/'
			. 'e/ed/Oberaargletscher_from_Oberaar%2C_2010_07.JPG';
		$filePath = $this->getNewTempDirectory() . '/Oberaargletscher_from_Oberaar.jpg';
		try {
			copy( $url, $filePath );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		// Base upload params:
		$params = [
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'filesize' => $fileSize,
			'offset' => 0,
		];

		// Upload chunks
		$chunkSessionKey = false;
		$resultOffset = 0;
		// Open the file:
		MediaWiki\suppressWarnings();
		$handle = fopen( $filePath, "r" );
		MediaWiki\restoreWarnings();

		if ( $handle === false ) {
			$this->markTestIncomplete( "could not open file: $filePath" );
		}

		while ( !feof( $handle ) ) {
			// Get the current chunk
			MediaWiki\suppressWarnings();
			$chunkData = fread( $handle, $chunkSize );
			MediaWiki\restoreWarnings();

			// Upload the current chunk into the $_FILE object:
			$this->fakeUploadChunk( 'chunk', 'blob', $mimeType, $chunkData );

			// Check for chunkSessionKey
			if ( !$chunkSessionKey ) {
				// Upload fist chunk ( and get the session key )
				try {
					list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
						self::$users['uploader']->getUser() );
				} catch ( ApiUsageException $e ) {
					$this->markTestIncomplete( $e->getMessage() );
				}
				// Make sure we got a valid chunk continue:
				$this->assertTrue( isset( $result['upload'] ) );
				$this->assertTrue( isset( $result['upload']['filekey'] ) );
				// If we don't get a session key mark test incomplete.
				if ( !isset( $result['upload']['filekey'] ) ) {
					$this->markTestIncomplete( "no filekey provided" );
				}
				$chunkSessionKey = $result['upload']['filekey'];
				$this->assertEquals( 'Continue', $result['upload']['result'] );
				// First chunk should have chunkSize == offset
				$this->assertEquals( $chunkSize, $result['upload']['offset'] );
				$resultOffset = $result['upload']['offset'];
				continue;
			}
			// Filekey set to chunk session
			$params['filekey'] = $chunkSessionKey;
			// Update the offset ( always add chunkSize for subquent chunks
			// should be in-sync with $result['upload']['offset'] )
			$params['offset'] += $chunkSize;
			// Make sure param offset is insync with resultOffset:
			$this->assertEquals( $resultOffset, $params['offset'] );
			// Upload current chunk
			try {
				list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
					self::$users['uploader']->getUser() );
			} catch ( ApiUsageException $e ) {
				$this->markTestIncomplete( $e->getMessage() );
			}
			// Make sure we got a valid chunk continue:
			$this->assertTrue( isset( $result['upload'] ) );
			$this->assertTrue( isset( $result['upload']['filekey'] ) );

			// Check if we were on the last chunk:
			if ( $params['offset'] + $chunkSize >= $fileSize ) {
				$this->assertEquals( 'Success', $result['upload']['result'] );
				break;
			} else {
				$this->assertEquals( 'Continue', $result['upload']['result'] );
				// update $resultOffset
				$resultOffset = $result['upload']['offset'];
			}
		}
		fclose( $handle );

		// Check that we got a valid file result:
		wfDebug( __METHOD__
			. " hohoh filesize {$fileSize} info {$result['upload']['imageinfo']['size']}\n\n" );
		$this->assertEquals( $fileSize, $result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertTrue( isset( $result['upload']['filekey'] ) );
		$filekey = $result['upload']['filekey'];

		// Now we should try to release the file from stash
		$params = [
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		];
		$this->clearFakeUploads();
		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->getUser() );
		} catch ( ApiUsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFileName( $fileName );
	}
}
