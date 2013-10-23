<?php

/**
 * @group API
 * @group Database
 */

/**
 * n.b. Ensure that you can write to the images/ directory as the
 * user that will run tests.
 */

// Note for reviewers: this intentionally duplicates functionality already in "ApiSetup" and so on.
// This framework works better IMO and has less strangeness (such as test cases inheriting from "ApiSetup"...)
// (and in the case of the other Upload tests, this flat out just actually works... )

// TODO: port the other Upload tests, and other API tests to this framework

require_once 'ApiTestCaseUpload.php';

/**
 * @group Database
 * @group Broken
 * Broken test, reports false errors from time to time.
 * See https://bugzilla.wikimedia.org/26169
 *
 * This is pretty sucky... needs to be prettified.
 */
class ApiUploadTest extends ApiTestCaseUpload {
	/**
	 * Testing login
	 * XXX this is a funny way of getting session context
	 */
	public function testLogin() {
		$user = self::$users['uploader'];

		$params = array(
			'action' => 'login',
			'lgname' => $user->username,
			'lgpassword' => $user->password
		);
		list( $result, , $session ) = $this->doApiRequest( $params );
		$this->assertArrayHasKey( "login", $result );
		$this->assertArrayHasKey( "result", $result['login'] );
		$this->assertEquals( "NeedToken", $result['login']['result'] );
		$token = $result['login']['token'];

		$params = array(
			'action' => 'login',
			'lgtoken' => $token,
			'lgname' => $user->username,
			'lgpassword' => $user->password
		);
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
			$this->doApiRequest( array(
				'action' => 'upload'
			) );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "The token parameter must be set", $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadMissingParams( $session ) {
		$exception = false;
		try {
			$this->doApiRequestWithToken( array(
				'action' => 'upload',
			), $session, self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "One of the parameters filekey, file, url, statuskey is required",
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
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		);

		$exception = false;
		try {
			list( $result, , ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertEquals( $fileSize, (int)$result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFilename( $fileName );
		unlink( $filePath );
	}


	/**
	 * @depends testLogin
	 */
	public function testUploadZeroLength( $session ) {
		$mimeType = 'image/png';

		$filePath = tempnam( wfTempDir(), "" );
		$fileName = "apiTestUploadZeroLength.png";

		$this->deleteFileByFileName( $fileName );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		);

		$exception = false;
		try {
			$this->doApiRequestWithToken( $params, $session, self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$this->assertContains( 'The file you submitted was empty', $e->getMessage() );
			$exception = true;
		}
		$this->assertTrue( $exception );

		// clean up
		$this->deleteFileByFilename( $fileName );
		unlink( $filePath );
	}


	/**
	 * @depends testLogin
	 */
	public function testUploadSameFileName( $session ) {
		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 2, $extension, wfTempDir() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		// we'll reuse this filename
		$fileName = basename( $filePaths[0] );

		// clear any other files with the same name
		$this->deleteFileByFileName( $fileName );

		// we reuse these params
		$params = array(
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		);

		// first upload .... should succeed

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user );
		} catch ( UsageException $e ) {
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
				self::$users['uploader']->user ); // FIXME: leaks a temporary file
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertTrue( isset( $result['upload']['warnings'] ) );
		$this->assertTrue( isset( $result['upload']['warnings']['exists'] ) );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFilename( $fileName );
		unlink( $filePaths[0] );
		unlink( $filePaths[1] );
	}


	/**
	 * @depends testLogin
	 */
	public function testUploadSameContent( $session ) {
		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$fileNames[0] = basename( $filePaths[0] );
		$fileNames[1] = "SameContentAs" . $fileNames[0];

		// clear any other files with the same name or content
		$this->deleteFileByContent( $filePaths[0] );
		$this->deleteFileByFileName( $fileNames[0] );
		$this->deleteFileByFileName( $fileNames[1] );

		// first upload .... should succeed

		$params = array(
			'action' => 'upload',
			'filename' => $fileNames[0],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for " . $fileNames[0],
		);

		if ( !$this->fakeUploadFile( 'file', $fileNames[0], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// second upload with the same content (but different name)

		if ( !$this->fakeUploadFile( 'file', $fileNames[1], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileNames[1],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for " . $fileNames[1],
		);

		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user ); // FIXME: leaks a temporary file
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Warning', $result['upload']['result'] );
		$this->assertTrue( isset( $result['upload']['warnings'] ) );
		$this->assertTrue( isset( $result['upload']['warnings']['duplicate'] ) );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFilename( $fileNames[0] );
		$this->deleteFileByFilename( $fileNames[1] );
		unlink( $filePaths[0] );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadStash( $session ) {
		$this->setMwGlobals( array(
			'wgUser' => self::$users['uploader']->user, // @todo FIXME: still used somewhere
		) );

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
			$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		if ( !$this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName",
		);

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user ); // FIXME: leaks a temporary file
		} catch ( UsageException $e ) {
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
		$params = array(
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		);

		$this->clearFakeUploads();
		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception, "No UsageException exception." );

		// clean up
		$this->deleteFileByFilename( $fileName );
		unlink( $filePath );
	}

	/**
	 * @depends testLogin
	 */
	public function testUploadChunks( $session ) {
		$this->setMwGlobals( array(
			'wgUser' => self::$users['uploader']->user, // @todo FIXME: still used somewhere
		) );

		$chunkSize = 1048576;
		// Download a large image file
		// ( using RandomImageGenerator for large files is not stable )
		$mimeType = 'image/jpeg';
		$url = 'http://upload.wikimedia.org/wikipedia/commons/e/ed/Oberaargletscher_from_Oberaar%2C_2010_07.JPG';
		$filePath = wfTempDir() . '/Oberaargletscher_from_Oberaar.jpg';
		try {
			// Only download if the file is not avaliable in the temp location:
			if ( !is_file( $filePath ) ) {
				copy( $url, $filePath );
			}
		} catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		// Base upload params:
		$params = array(
			'action' => 'upload',
			'stash' => 1,
			'filename' => $fileName,
			'filesize' => $fileSize,
			'offset' => 0,
		);

		// Upload chunks
		$chunkSessionKey = false;
		$resultOffset = 0;
		// Open the file:
		$handle = @fopen( $filePath, "r" );
		if ( $handle === false ) {
			$this->markTestIncomplete( "could not open file: $filePath" );
		}
		while ( !feof( $handle ) ) {
			// Get the current chunk
			$chunkData = @fread( $handle, $chunkSize );

			// Upload the current chunk into the $_FILE object:
			$this->fakeUploadChunk( 'chunk', 'blob', $mimeType, $chunkData );

			// Check for chunkSessionKey
			if ( !$chunkSessionKey ) {
				// Upload fist chunk ( and get the session key )
				try {
					list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
						self::$users['uploader']->user );
				} catch ( UsageException $e ) {
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
			// Update the offset ( always add chunkSize for subquent chunks should be in-sync with $result['upload']['offset'] )
			$params['offset'] += $chunkSize;
			// Make sure param offset is insync with resultOffset:
			$this->assertEquals( $resultOffset, $params['offset'] );
			// Upload current chunk
			try {
				list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session,
					self::$users['uploader']->user );
			} catch ( UsageException $e ) {
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
		wfDebug( __METHOD__ . " hohoh filesize {$fileSize} info {$result['upload']['imageinfo']['size']}\n\n" );
		$this->assertEquals( $fileSize, $result['upload']['imageinfo']['size'] );
		$this->assertEquals( $mimeType, $result['upload']['imageinfo']['mime'] );
		$this->assertTrue( isset( $result['upload']['filekey'] ) );
		$filekey = $result['upload']['filekey'];

		// Now we should try to release the file from stash
		$params = array(
			'action' => 'upload',
			'filekey' => $filekey,
			'filename' => $fileName,
			'comment' => 'dummy comment',
			'text' => "This is the page text for $fileName, altered",
		);
		$this->clearFakeUploads();
		$exception = false;
		try {
			list( $result ) = $this->doApiRequestWithToken( $params, $session,
				self::$users['uploader']->user );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFilename( $fileName );
		// don't remove downloaded temporary file for fast subquent tests.
		//unlink( $filePath );
	}
}
