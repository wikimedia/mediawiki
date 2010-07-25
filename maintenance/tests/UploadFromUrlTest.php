<?php

class nullClass {
	public function handleOutput() { }
	public function purgeRedundantText() { }
}

class UploadFromUrlTest extends ApiTestSetup {

	function setUp() {
		global $wgEnableUploads, $wgAllowCopyUploads;

		$wgEnableUploads = true;
		$wgAllowCopyUploads = true;
		parent::setup();

		ini_set( 'log_errors', 1 );
		ini_set( 'error_reporting', 1 );
		ini_set( 'display_errors', 1 );
	}

	function doApiRequest( $params, $data = null ) {
		$session = isset( $data[2] ) ? $data[2] : array();
		$_SESSION = $session;

		$req = new FauxRequest( $params, true, $session );
		$module = new ApiMain( $req, true );
		$module->execute();

		return array( $module->getResultData(), $req, $_SESSION );
	}

	function testClearQueue() {
		while ( $job = Job::pop() ) { }
		$this->assertFalse( $job );
	}

	function testLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$userName,
			'lgpassword' => self::$passWord ) );
		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			"lgtoken" => $token,
			"lgname" => self::$userName,
			"lgpassword" => self::$passWord ) );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );

		return $data;
	}

	/**
	 * @depends testLogin
	 */
	function testSetupUrlDownload( $data ) {
		global $wgUser;
		$wgUser = User::newFromName( self::$userName );
		$wgUser->load();
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;
		$exception = false;

		try {
			$this->doApiRequest( array(
				'action' => 'upload',
			), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "The token parameter must be set", $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$exception = false;
		try {
			$this->doApiRequest( array(
				'action' => 'upload',
				'token' => $token,
			), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "One of the parameters sessionkey, file, url is required",
				$e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$exception = false;
		try {
			$this->doApiRequest( array(
				'action' => 'upload',
				'url' => 'http://www.example.com/test.png',
				'token' => $token,
			), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "The filename parameter must be set", $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$wgUser->removeGroup( 'sysop' );
		$exception = false;
		try {
			$this->doApiRequest( array(
				'action' => 'upload',
				'url' => 'http://www.example.com/test.png',
				'filename' => 'Test.png',
				'token' => $token,
			), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "Permission denied", $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$wgUser->addGroup( '*' );
		$wgUser->addGroup( 'sysop' );
		$exception = false;
		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'asyncdownload' => 1,
			'filename' => 'Test.png',
			'token' => $token,
		), $data );

		$this->assertTrue( $data[0]['upload']['queued'], 'Job added' );

		$job = Job::pop();
		$this->assertThat( $job, $this->isInstanceOf( 'UploadFromUrlJob' ),
			"Got Job Object" );

		$job = Job::pop_type( 'upload' );
		$this->assertFalse( $job );
	}

	/**
	 * @depends testLogin
	 */
	function testDoDownload( $data ) {
		global $wgUser;
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;

		$wgUser->addGroup( 'users' );
		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'filename' => 'Test.png',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'asyncdownload' => 1,
			'token' => $token,
		), $data );

		$job = Job::pop();
		$this->assertEquals( 'UploadFromUrlJob', get_class( $job ) );

 		$status = $job->run();

		$this->assertTrue( $status->isOk() );

		return $data;
	}

	/**
	 * @depends testLogin
	 */
	function testSyncDownload( $data ) {
		global $wgUser;
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;

		$job = Job::pop();
		$this->assertFalse( $job );

		$this->deleteFile( 'Test.png' );

		$wgUser->addGroup( 'users' );
		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'filename' => 'Test.png',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'ignorewarnings' => true,
			'token' => $token,
		), $data );

		$job = Job::pop();
		$this->assertFalse( $job );

		$this->assertEquals( 'Success', $data[0]['upload']['result'] );

		return $data;
	}

	/**
	 * @depends testDoDownload
	 */
	function testVerifyDownload( $data ) {
		$t = Title::newFromText( "Test.png", NS_FILE );

		$this->assertTrue( $t->exists() );

		$this->deleteFile( 'Test.png' );
	 }

	/**
	 *
	 */
	function deleteFile( $name ) {

		$t = Title::newFromText( $name, NS_FILE );
		$this->assertTrue($t->exists(), "File '$name' exists");

		if ( $t->exists() ) {
			$file = wfFindFile( $name, array( 'ignoreRedirect' => true ) );
			$empty = "";
			$status = FileDeleteForm::doDelete( $t, $file, $empty, "none", true );
			$a = new Article ( $t );
			$a->doDeleteArticle( "testing" );
		}
		$t = Title::newFromText( $name, NS_FILE );

		$this->assertFalse($t->exists(), "File '$name' was deleted");
	}
 }
