<?php

/**
 * @group Database
 * @group Destructive
 */

/**
 * n.b. Ensure that you can write to the images/ directory as the 
 * user that will run tests.
 */

// Note for reviewers: this intentionally duplicates functionality already in "ApiSetup" and so on.
// This framework works better IMO and has less strangeness (such as test cases inheriting from "ApiSetup"...)
// (and in the case of the other Upload tests, this flat out just actually works... )
	
// TODO: refactor into several files
// TODO: port the other Upload tests, and other API tests to this framework

require_once( dirname( __FILE__ ) . '/RandomImageGenerator.php' );
require_once( dirname( __FILE__ ) . '/../../../../../includes/User.php' );

/* Wraps the user object, so we can also retain full access to properties like password if we log in via the API */
class ApiTestUser {
	public $username;
	public $password;
	public $email;
	public $groups;
	public $user;

	function __construct( $username, $realname = 'Real Name', $email = 'sample@sample.com', $groups = array() ) {
		$this->username = $username; 
		$this->realname = $realname; 
		$this->email = $email;
		$this->groups = $groups;

		// don't allow user to hardcode or select passwords -- people sometimes run tests 	
		// on live wikis. Sometimes we create sysop users in these tests. A sysop user with
	 	// a known password would be a Bad Thing.
		$this->password = User::randomPassword();

		$this->user = User::newFromName( $this->username );
		$this->user->load();

		// In an ideal world we'd have a new wiki (or mock data store) for every single test.
		// But for now, we just need to create or update the user with the desired properties.
		// we particularly need the new password, since we just generated it randomly.
		// In core MediaWiki, there is no functionality to delete users, so this is the best we can do.
		if ( !$this->user->getID() ) {
			// create the user
			$this->user = User::createNew( 
				$this->username, array(
					"email" => $this->email,
					"real_name" => $this->realname
				) 
			);
			if ( !$this->user ) {
				throw new Exception( "error creating user" );
			}
		}
		
		// update the user to use the new random password and other details
		$this->user->setPassword( $this->password );
		$this->user->setEmail( $this->email );
		$this->user->setRealName( $this->realname );
		// remove all groups, replace with any groups specified
		foreach ( $this->user->getGroups() as $group ) {
			$this->user->removeGroup( $group );
		}
		if ( count( $this->groups ) ) {
			foreach ( $this->groups as $group ) {
				$this->user->addGroup( $group );
			}
		}
		$this->user->saveSettings();
		
	}

}

abstract class ApiTestCase extends PHPUnit_Framework_TestCase {
	public static $users;

	function setUp() {
		global $wgContLang, $wgAuth, $wgMemc, $wgRequest, $wgUser;

		$wgMemc = new FakeMemCachedClient();
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );

		self::$users = array(
			'sysop' => new ApiTestUser( 
				'Apitestsysop', 
				'Api Test Sysop', 
				'api_test_sysop@sample.com', 
				array( 'sysop' ) 	
			),
			'uploader' => new ApiTestUser( 
				'Apitestuser',
				'Api Test User',
				'api_test_user@sample.com',
				array() 		
			)
		);

		$wgUser = self::$users['sysop']->user;

	}

	function tearDown() {
		global $wgMemc;
		$wgMemc = null;
	}

	protected function doApiRequest( $params, $session = null ) {
		$_SESSION = isset( $session ) ? $session : array();

		$request = new FauxRequest( $params, true, $_SESSION );
		$module = new ApiMain( $request, true );
		$module->execute();

		return array( $module->getResultData(), $request, $_SESSION );
	}

	/**
	 * Add an edit token to the API request
	 * This is cheating a bit -- we grab a token in the correct format and then add it to the pseudo-session and to the
	 * request, without actually requesting a "real" edit token
	 * @param $params: key-value API params
	 * @param $data: a structure which also contains the session
	 */
	protected function doApiRequestWithToken( $params, $session ) {
		if ( $session['wsToken'] ) {
			// add edit token to fake session
			$session['wsEditToken'] = $session['wsToken'];
			// add token to request parameters
			$params['token'] = md5( $session['wsToken'] ) . EDIT_TOKEN_SUFFIX;
			return $this->doApiRequest( $params, $session );
		} else {
			throw new Exception( "request data not in right format" );
		}
	}

}

/**
 * @group Database
 * @group Destructive
 */
class ApiUploadTest extends ApiTestCase {
	/**
	 * Fixture -- run before every test 
	 */
	public function setUp() {
		global $wgEnableUploads, $wgEnableAPI, $wgDebugLogFile;
		parent::setUp();

		$wgEnableUploads = true;
		$wgEnableAPI = true;
		wfSetupSession();

		$wgDebugLogFile = '/private/tmp/mwtestdebug.log';
		ini_set( 'log_errors', 1 );
		ini_set( 'error_reporting', 1 );
		ini_set( 'display_errors', 1 );
		
		$this->clearFakeUploads();
	}

	/**
	 * Fixture -- run after every test 
	 * Clean up temporary files etc.
	 */
	function tearDown() {
	}


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
		list( $result, $request, $session ) = $this->doApiRequest( $params );
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
		list( $result, $request, $session ) = $this->doApiRequest( $params );
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
			$this->assertEquals( "One of the parameters sessionkey, file, url, statuskey is required",
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

		$filePaths = $randomImageGenerator->writeImages( 1, $extension, dirname( wfTempDir() ) );
		$filePath = $filePaths[0];
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
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
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

		$filePaths = $randomImageGenerator->writeImages( 2, $extension, dirname( wfTempDir() ) );
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
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
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
			list( $result, $request, $session ) = $this->doApiRequestWithToken( $params, $session );
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
		$filePaths = $randomImageGenerator->writeImages( 1, $extension, dirname( wfTempDir() ) );
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

		$filePaths = $randomImageGenerator->writeImages( 1, $extension, dirname( wfTempDir() ) );
		$filePath = $filePaths[0];
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
		$this->assertTrue( isset( $result['upload']['sessionkey'] ) );
		$sessionkey = $result['upload']['sessionkey'];
		
		// it should be visible from Special:UploadStash 
		// XXX ...but how to test this, with a fake WebRequest with the session?

		// now we should try to release the file from stash
		$params = array(
			'action' => 'upload',
			'sessionkey' => $sessionkey,
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



	/**
	 * Helper function -- remove files and associated articles by Title
	 * @param {Title} title to be removed
	 */
	public function deleteFileByTitle( $title ) {
		if ( $title->exists() ) { 
			$file = wfFindFile( $title, array( 'ignoreRedirect' => true ) );
			$noOldArchive = ""; // yes this really needs to be set this way
			$comment = "removing for test";
			$restrictDeletedVersions = false;
			$status = FileDeleteForm::doDelete( $title, $file, $noOldArchive, $comment, $restrictDeletedVersions );
			if ( !$status->isGood() ) {
				return false;
			}
			$article = new Article( $title );
			$article->doDeleteArticle( "removing for test" );

			// see if it now doesn't exist; reload	
			$title = Title::newFromText( $fileName, NS_FILE );
		}
		return ! ( $title && is_a( $title, 'Title' ) && $title->exists() );
	}

	/**
	 * Helper function -- remove files and associated articles with a particular filename 
	 * @param {String} filename to be removed
	 */
	public function deleteFileByFileName( $fileName ) {
		return $this->deleteFileByTitle( Title::newFromText( $fileName, NS_FILE ) );
	}


	/**
	 * Helper function -- given a file on the filesystem, find matching content in the db (and associated articles) and remove them.
	 * @param {String} path to file on the filesystem
	 */
	public function deleteFileByContent( $filePath ) {
		$hash = File::sha1Base36( $filePath );
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		$success = true;
		foreach ( $dupes as $dupe ) {
			$success &= $this->deleteFileByTitle( $dupe->getTitle() );
		}
		return $success;
	}

	/** 
	 * Fake an upload by dumping the file into temp space, and adding info to $_FILES.
	 * (This is what PHP would normally do).
	 * @param {String}: fieldname - name this would have in the upload form 
	 * @param {String}: fileName - name to title this 
	 * @param {String}: mime type
	 * @param {String}: filePath - path where to find file contents
	 */
	function fakeUploadFile( $fieldName, $fileName, $type, $filePath ) {
		$tmpName = tempnam( wfTempDir(), "" );
		if ( !file_exists( $filePath ) ) {
			throw new Exception( "$filePath doesn't exist!" );
		};

		if ( !copy( $filePath, $tmpName ) ) { 
			throw new Exception( "couldn't copy $filePath to $tmpName" );
		}

		clearstatcache();
		$size = filesize( $tmpName );
		if ( $size === false ) {
			throw new Exception( "couldn't stat $tmpName" );
		}

		$_FILES[ $fieldName ] = array(
			'name'		=> $fileName,
			'type'		=> $type,
			'tmp_name' 	=> $tmpName,
			'size' 		=> $size,
			'error'		=> null
		);

		return true;

	}

	/**
	 * Remove traces of previous fake uploads
	 */
	function clearFakeUploads() {
		$_FILES = array();
	}


}

