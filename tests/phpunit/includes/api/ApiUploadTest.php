<?php

/**
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

require_once( 'ApiTestCaseUpload.php' );

/**
 * @group Database
 *
 * This is pretty sucky... needs to be prettified.
 */
class ApiUploadTest extends ApiTestCaseUpload {

	/**
	 * Testing login
	 * XXX this is a funny way of getting session context
	 */
	function testLogin() {
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$exception = false;
		try {
			$this->doApiRequestWithToken( array(
				'action' => 'upload',
			), $session );
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
		}
		catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );


		if (! $this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text'	=> "This is the page text for $fileName",
		);

		$exception = false;
		try {
			list( $result, , ) = $this->doApiRequestWithToken( $params, $session );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertEquals( $fileSize, ( int )$result['upload']['imageinfo']['size'] );
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$mimeType = 'image/png';

		$filePath = tempnam( wfTempDir(), "" );
		$fileName = "apiTestUploadZeroLength.png";

		$this->deleteFileByFileName( $fileName );

		if (! $this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text'	=> "This is the page text for $fileName",
		);

		$exception = false;
		try {
			$this->doApiRequestWithToken( $params, $session );
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
		}
		catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$filePaths = $randomImageGenerator->writeImages( 2, $extension, wfTempDir() );
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
			'text'	=> "This is the page text for $fileName",
		);

		// first upload .... should succeed

		if (! $this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , $session ) = $this->doApiRequestWithToken( $params, $session );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// second upload with the same name (but different content)

		if (! $this->fakeUploadFile( 'file', $fileName, $mimeType, $filePaths[1] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, , ) = $this->doApiRequestWithToken( $params, $session );
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
		}
		catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}
		$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
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
			'text'	=> "This is the page text for " . $fileNames[0],
		);

		if (! $this->fakeUploadFile( 'file', $fileNames[0], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$exception = false;
		try {
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );


		// second upload with the same content (but different name)

		if (! $this->fakeUploadFile( 'file', $fileNames[1], $mimeType, $filePaths[0] ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'filename' => $fileNames[1],
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text'	=> "This is the page text for " . $fileNames[1],
		);

		$exception = false;
		try {
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
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
		global $wgUser;
		$wgUser = self::$users['uploader']->user;

		$extension = 'png';
		$mimeType = 'image/png';

		try {
			$randomImageGenerator = new RandomImageGenerator();
		}
		catch ( Exception $e ) {
			$this->markTestIncomplete( $e->getMessage() );
		}

		$filePaths = $randomImageGenerator->writeImages( 1, $extension, wfTempDir() );
		$filePath = $filePaths[0];
		$fileSize = filesize( $filePath );
		$fileName = basename( $filePath );

		$this->deleteFileByFileName( $fileName );
		$this->deleteFileByContent( $filePath );

		if (! $this->fakeUploadFile( 'file', $fileName, $mimeType, $filePath ) ) {
			$this->markTestIncomplete( "Couldn't upload file!\n" );
		}

		$params = array(
			'action' => 'upload',
			'stash'	=> 1,
			'filename' => $fileName,
			'file' => 'dummy content',
			'comment' => 'dummy comment',
			'text'	=> "This is the page text for $fileName",
		);

		$exception = false;
		try {
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertFalse( $exception );
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertEquals( $fileSize, ( int )$result['upload']['imageinfo']['size'] );
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
			'text'	=> "This is the page text for $fileName, altered",
		);

		$this->clearFakeUploads();
		$exception = false;
		try {
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
		} catch ( UsageException $e ) {
			$exception = true;
		}
		$this->assertTrue( isset( $result['upload'] ) );
		$this->assertEquals( 'Success', $result['upload']['result'] );
		$this->assertFalse( $exception );

		// clean up
		$this->deleteFileByFilename( $fileName );
		unlink( $filePath );
	}
}

