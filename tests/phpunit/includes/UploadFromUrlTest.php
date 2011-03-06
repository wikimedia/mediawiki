<?php

require_once dirname( __FILE__ ) . '/api/ApiSetup.php';

/**
 * @group Broken
 * @group Upload
 */
class UploadFromUrlTest extends ApiTestSetup {

	public function setUp() {
		global $wgEnableUploads, $wgAllowCopyUploads, $wgAllowAsyncCopyUploads;
		parent::setUp();

		$wgEnableUploads = true;
		$wgAllowCopyUploads = true;
		$wgAllowAsyncCopyUploads = true;
		wfSetupSession();

		ini_set( 'log_errors', 1 );
		ini_set( 'error_reporting', 1 );
		ini_set( 'display_errors', 1 );

		if ( wfLocalFile( 'UploadFromUrlTest.png' )->exists() ) {
			$this->deleteFile( 'UploadFromUrlTest.png' );
		}
	}

	protected function doApiRequest( $params, $unused = null, $appendModule = false ) {
		$sessionId = session_id();
		session_write_close();

		$req = new FauxRequest( $params, true, $_SESSION );
		$module = new ApiMain( $req, true );
		$module->execute();

		wfSetupSession( $sessionId );
		return array( $module->getResultData(), $req );
	}

	/**
	 * Ensure that the job queue is empty before continuing
	 */
	public function testClearQueue() {
		while ( $job = Job::pop() ) { }
		$this->assertFalse( $job );
	}

	/**
	 * @todo Document why we test login, since the $wgUser hack used doesn't
	 * require login
	 */
	public function testLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => $this->user->userName,
			'lgpassword' => $this->user->passWord ) );
		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "NeedToken", $data[0]['login']['result'] );
		$token = $data[0]['login']['token'];

		$data = $this->doApiRequest( array(
			'action' => 'login',
			"lgtoken" => $token,
			'lgname' => $this->user->userName,
			'lgpassword' => $this->user->passWord ) );

		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );

		return $data;
	}

	/**
	 * @depends testLogin
	 * @depends testClearQueue
	 */
	public function testSetupUrlDownload( $data ) {
		$token = $this->user->editToken();
		$exception = false;

		try {
			$this->doApiRequest( array(
				'action' => 'upload',
			) );
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
			$this->assertEquals( "One of the parameters sessionkey, file, url, statuskey is required",
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

		$this->user->removeGroup( 'sysop' );
		$exception = false;
		try {
			$this->doApiRequest( array(
				'action' => 'upload',
				'url' => 'http://www.example.com/test.png',
				'filename' => 'UploadFromUrlTest.png',
				'token' => $token,
			), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "Permission denied", $e->getMessage() );
		}
		$this->assertTrue( $exception, "Got exception" );

		$this->user->addGroup( 'sysop' );
		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'asyncdownload' => 1,
			'filename' => 'UploadFromUrlTest.png',
			'token' => $token,
		), $data );

		$this->assertEquals( $data[0]['upload']['result'], 'Queued', 'Queued upload' );

		$job = Job::pop();
		$this->assertThat( $job, $this->isInstanceOf( 'UploadFromUrlJob' ), 'Queued upload inserted' );
	}

	/**
	 * @depends testLogin
	 * @depends testClearQueue
	 */
	public function testAsyncUpload( $data ) {
		$token = $this->user->editToken();

		$this->user->addGroup( 'users' );

		$data = $this->doAsyncUpload( $token, true );
		$this->assertEquals( $data[0]['upload']['result'], 'Success' );
		$this->assertEquals( $data[0]['upload']['filename'], 'UploadFromUrlTest.png' );
		$this->assertTrue( wfLocalFile( $data[0]['upload']['filename'] )->exists() );

		$this->deleteFile( 'UploadFromUrlTest.png' );

		return $data;
	}

	/**
	 * @depends testLogin
	 * @depends testClearQueue
	 */
	public function testAsyncUploadWarning( $data ) {
		$token = $this->user->editToken();

		$this->user->addGroup( 'users' );


		$data = $this->doAsyncUpload( $token );

		$this->assertEquals( $data[0]['upload']['result'], 'Warning' );
		$this->assertTrue( isset( $data[0]['upload']['sessionkey'] ) );

		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'sessionkey' => $data[0]['upload']['sessionkey'],
			'filename' => 'UploadFromUrlTest.png',
			'ignorewarnings' => 1,
			'token' => $token,
		) );
		$this->assertEquals( $data[0]['upload']['result'], 'Success' );
		$this->assertEquals( $data[0]['upload']['filename'], 'UploadFromUrlTest.png' );
		$this->assertTrue( wfLocalFile( $data[0]['upload']['filename'] )->exists() );

		$this->deleteFile( 'UploadFromUrlTest.png' );

		return $data;
	}

	/**
	 * @depends testLogin
	 * @depends testClearQueue
	 */
	public function testSyncDownload( $data ) {
		$token = $this->user->editToken();

		$job = Job::pop();
		$this->assertFalse( $job, 'Starting with an empty jobqueue' );

		$this->user->addGroup( 'users' );
		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'filename' => 'UploadFromUrlTest.png',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'ignorewarnings' => true,
			'token' => $token,
		), $data );

		$job = Job::pop();
		$this->assertFalse( $job );

		$this->assertEquals( 'Success', $data[0]['upload']['result'] );
		$this->deleteFile( 'UploadFromUrlTest.png' );

		return $data;
	}

	public function testLeaveMessage() {
		$token = $this->user->user->editToken();

		$talk = $this->user->user->getTalkPage();
		if ( $talk->exists() ) {
			$a = new Article( $talk );
			$a->doDeleteArticle( '' );
		}

		$this->assertFalse( (bool)$talk->getArticleId( Title::GAID_FOR_UPDATE ), 'User talk does not exist' );

		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'filename' => 'UploadFromUrlTest.png',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'asyncdownload' => 1,
			'token' => $token,
			'leavemessage' => 1,
			'ignorewarnings' => 1,
		) );

		$job = Job::pop();
		$this->assertEquals( 'UploadFromUrlJob', get_class( $job ) );
		$job->run();

		$this->assertTrue( wfLocalFile( 'UploadFromUrlTest.png' )->exists() );
		$this->assertTrue( (bool)$talk->getArticleId( Title::GAID_FOR_UPDATE ), 'User talk exists' );

		$this->deleteFile( 'UploadFromUrlTest.png' );

		$talkRev = Revision::newFromTitle( $talk );
		$talkSize = $talkRev->getSize();

		$exception = false;
		try {
			$data = $this->doApiRequest( array(
				'action' => 'upload',
				'filename' => 'UploadFromUrlTest.png',
				'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
				'asyncdownload' => 1,
				'token' => $token,
				'leavemessage' => 1,
			) );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( 'Using leavemessage without ignorewarnings is not supported', $e->getMessage() );
		}
		$this->assertTrue( $exception );

		$job = Job::pop();
		$this->assertFalse( $job );

		return;

		/**
		// Broken until using leavemessage with ignorewarnings is supported
		$job->run();

		$this->assertFalse( wfLocalFile( 'UploadFromUrlTest.png' )->exists() );

		$talkRev = Revision::newFromTitle( $talk );
		$this->assertTrue( $talkRev->getSize() > $talkSize, 'New message left' );
		*/
	}

	/**
	 * Helper function to perform an async upload, execute the job and fetch
	 * the status
	 *
	 * @return array The result of action=upload&statuskey=key
	 */
	private function doAsyncUpload( $token, $ignoreWarnings = false, $leaveMessage = false ) {
		$params = array(
			'action' => 'upload',
			'filename' => 'UploadFromUrlTest.png',
			'url' => 'http://bits.wikimedia.org/skins-1.5/common/images/poweredby_mediawiki_88x31.png',
			'asyncdownload' => 1,
			'token' => $token,
		);
		if ( $ignoreWarnings ) {
			$params['ignorewarnings'] = 1;
		}
		if ( $leaveMessage ) {
			$params['leavemessage'] = 1;
		}

		$data = $this->doApiRequest( $params );
		$this->assertEquals( $data[0]['upload']['result'], 'Queued' );
		$this->assertTrue( isset( $data[0]['upload']['statuskey'] ) );
		$statusKey = $data[0]['upload']['statuskey'];

		$job = Job::pop();
		$this->assertEquals( 'UploadFromUrlJob', get_class( $job ) );

		$status = $job->run();
		$this->assertTrue( $status );

		$data = $this->doApiRequest( array(
			'action' => 'upload',
			'statuskey' => $statusKey,
			'token' => $token,
		) );

		return $data;
	}


	/**
	 *
	 */
	protected function deleteFile( $name ) {
		$t = Title::newFromText( $name, NS_FILE );
		$this->assertTrue($t->exists(), "File '$name' exists");

		if ( $t->exists() ) {
			$file = wfFindFile( $name, array( 'ignoreRedirect' => true ) );
			$empty = "";
			FileDeleteForm::doDelete( $t, $file, $empty, "none", true );
			$a = new Article ( $t );
			$a->doDeleteArticle( "testing" );
		}
		$t = Title::newFromText( $name, NS_FILE );

		$this->assertFalse($t->exists(), "File '$name' was deleted");
	}
 }
