<?php

require_once( "ApiSetup.php" );

class UploadFromChunksTest extends ApiSetup {

	function setUp() {
		global $wgEnableUploads;

		$wgEnableUploads = true;
		parent::setup();

		ini_set( 'file_loads', 1 );
		ini_set( 'log_errors', 1 );
		ini_set( 'error_reporting', 1 );
		ini_set( 'display_errors', 1 );
	}

	function makeChunk( $content ) {
		$file = tempnam( wfTempDir(), "" );
		$fh = fopen( $file, "wb" );
		if ( $fh == false ) {
			$this->markTestIncomplete( "Couldn't open $file!\n" );
			return;
		}
		fwrite( $fh, $content );
		fclose( $fh );

		$_FILES['chunk']['tmp_name'] = $file;
		$_FILES['chunk']['size'] = 3;
		$_FILES['chunk']['error'] = null;
		$_FILES['chunk']['name'] = "test.txt";
	}

	function cleanChunk() {
		if ( file_exists( $_FILES['chunk']['tmp_name'] ) )
		   unlink( $_FILES['chunk']['tmp_name'] );
	}

	function doApiRequest( $params, $data = null ) {
		$session = isset( $data[2] ) ? $data[2] : array();
		$_SESSION = $session;

		$req = new FauxRequest( $params, true, $session );
		$module = new ApiMain( $req, true );
		$module->execute();

		return array( $module->getResultData(), $req, $_SESSION );
	}

	function testGetTitle() {
		$filename = tempnam( wfTempDir(), "" );
		$c = new UploadFromChunks();
		$c->initialize( false, "temp.txt", null, $filename, 0, null );
		$this->assertEquals( null, $c->getTitle() );

		$c = new UploadFromChunks();
		$c->initialize( false, "temp.png", null, $filename, 0, null );
		$this->assertEquals( Title::makeTitleSafe( NS_FILE, "Temp.png" ), $c->getTitle() );
	}

	function testLogin() {
		$data = $this->doApiRequest( array(
			'action' => 'login',
			'lgname' => self::$userName,
			'lgpassword' => self::$passWord ) );
		$this->assertArrayHasKey( "login", $data[0] );
		$this->assertArrayHasKey( "result", $data[0]['login'] );
		$this->assertEquals( "Success", $data[0]['login']['result'] );
		$this->assertArrayHasKey( 'lgtoken', $data[0]['login'] );

		return $data;
	}

	/**
	 * @depends testLogin
	 */
	function testSetupChunkSession( $data ) {
		global $wgUser;
		$wgUser = User::newFromName( self::$userName );
		$wgUser->load();
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;
		$exception = false;

		$data = $this->doApiRequest( array(
			'filename' => 'tmp.txt',
			'action' => 'upload',
			'enablechunks' => true,
			'token' => $token ), $data );
		$this->assertArrayHasKey( 'uploadUrl', $data[0] );
		$this->assertRegexp( '/action=upload/', $data[0]['uploadUrl'] );
		$this->assertRegexp( '/enablechunks=true/', $data[0]['uploadUrl'] );
		$this->assertRegexp( '/format=json/', $data[0]['uploadUrl'] );
		$this->assertRegexp( '/chunksession=/', $data[0]['uploadUrl'] );
		$this->assertRegexp( '/token=/', $data[0]['uploadUrl'] );

		return $data;
	}

	/**
	 * @depends testSetupChunkSession
	 */
	function testAppendChunkTypeBanned( $data ) {
		global $wgUser;
		$wgUser = User::newFromName( self::$userName );

		$url = $data[0]['uploadUrl'];
		$params = wfCgiToArray( substr( $url, strpos( $url, "?" ) ) );

		$size = 0;
		for ( $i = 0; $i < 4; $i++ ) {
			$this->makeChunk( "123" );
			$size += $_FILES['chunk']['size'];

			$data = $this->doApiRequest( $params, $data );
			$this->assertArrayHasKey( "result", $data[0] );
			$this->assertTrue( (bool)$data[0]["result"] );

			$this->assertArrayHasKey( "filesize", $data[0] );
			$this->assertEquals( $size, $data[0]['filesize'] );

			$this->cleanChunk();
		}

		$data['param'] = $params;
		return $data;
	}

	/**
	 * @depends testLogin
	 */
	function testInvalidSessionKey( $data ) {
		global $wgUser;
		$wgUser = User::newFromName( self::$userName );
		$wgUser->load();
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;
		$exception = false;

		try {
			$this->doApiRequest( array(
				'action' => 'upload',
				'enablechunks' => true,
				'token' => $token,
				'chunksession' => 'bogus' ), $data );
		} catch ( UsageException $e ) {
			$exception = true;
			$this->assertEquals( "Not a valid session key", $e->getMessage() );
		}

		$this->assertTrue( $exception, "Got exception" );
	}

	function testPerformUploadInitError() {
		global $wgUser;
		$wgUser = User::newFromId( 1 );

		$req = new FauxRequest(
			array(
				'action' => 'upload',
				'enablechunks' => 'false',
				'sessionkey' => '1',
				'filename' => 'test.png',
			) );
		$module = new ApiMain( $req, true );
		$gotException = false;
		try {
			$module->execute();
		} catch ( UsageException $e ) {
			$this->assertEquals( "The token parameter must be set", $e->getMessage() );
			$gotException = true;
		}

		$this->assertTrue( $gotException );
	}

	/**
	 * @depends testAppendChunkTypeBanned
	 */
	function testUploadChunkDoneTypeBanned( $data ) {
		global $wgUser;
		$wgUser = User::newFromName( self::$userName );
		$token = $wgUser->editToken();
		$params = $data['param'];
		$params['done'] = 1;

		$this->makeChunk( "123" );

		$gotException = false;
		try {
			$data = $this->doApiRequest( $params, $data );
		} catch ( UsageException $e ) {
			$this->assertEquals( "This type of file is banned",
				$e->getMessage() );
			$gotException = true;
		}
		$this->cleanChunk();
		$this->assertTrue( $gotException );
	}

	/**
	 * @depends testLogin
	 */
	function testUploadChunkDoneDuplicate( $data ) {
		global $wgUser, $wgVerifyMimeType;

		$wgVerifyMimeType = false;
		$wgUser = User::newFromName( self::$userName );
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;
		$data = $this->doApiRequest( array(
			'filename' => 'tmp.png',
			'action' => 'upload',
			'enablechunks' => true,
			'token' => $token ), $data );

		$url = $data[0]['uploadUrl'];
		$params = wfCgiToArray( substr( $url, strpos( $url, "?" ) ) );
		$size = 0;
		for ( $i = 0; $i < 4; $i++ ) {
			$this->makeChunk( "123" );
			$size += $_FILES['chunk']['size'];

			$data = $this->doApiRequest( $params, $data );
			$this->assertArrayHasKey( "result", $data[0] );
			$this->assertTrue( (bool)$data[0]["result"] );

			$this->assertArrayHasKey( "filesize", $data[0] );
			$this->assertEquals( $size, $data[0]['filesize'] );

			$this->cleanChunk();
		}

		$params['done'] = true;

		$this->makeChunk( "123" );
		$data = $this->doApiRequest( $params, $data );
		$this->cleanChunk();

		$this->assertArrayHasKey( 'upload', $data[0] );
		$this->assertArrayHasKey( 'result', $data[0]['upload'] );
		$this->assertEquals( 'Warning', $data[0]['upload']['result'] );

		$this->assertArrayHasKey( 'warnings', $data[0]['upload'] );
		$this->assertArrayHasKey( 'exists',
								 $data[0]['upload']['warnings'] );
		$this->assertEquals( 'Tmp.png',
							$data[0]['upload']['warnings']['exists'] );

	}

	/**
	 * @depends testLogin
	 */
	function testUploadChunkDoneGood( $data ) {
		global $wgUser, $wgVerifyMimeType;
		$wgVerifyMimeType = false;

		$id = Title::newFromText( "Twar.png", NS_FILE )->getArticleID();

		$oldFile = Article::newFromID( $id );
		if ( $oldFile ) {
			$oldFile->doDeleteArticle();
			$oldFile->doPurge();
		}
		$oldFile = wfFindFile( "Twar.png" );
		if ( $oldFile ) {
			$oldFile->delete();
		}

		$wgUser = User::newFromName( self::$userName );
		$data[2]['wsEditToken'] = $data[2]['wsToken'];
		$token = md5( $data[2]['wsToken'] ) . EDIT_TOKEN_SUFFIX;
		$data = $this->doApiRequest( array(
			'filename' => 'twar.png',
			'action' => 'upload',
			'enablechunks' => true,
			'token' => $token ), $data );

		$url = $data[0]['uploadUrl'];
		$params = wfCgiToArray( substr( $url, strpos( $url, "?" ) ) );
		$size = 0;
		for ( $i = 0; $i < 5; $i++ ) {
			$this->makeChunk( "123" );
			$size += $_FILES['chunk']['size'];

			$data = $this->doApiRequest( $params, $data );
			$this->assertArrayHasKey( "result", $data[0] );
			$this->assertTrue( (bool)$data[0]["result"] );

			$this->assertArrayHasKey( "filesize", $data[0] );
			$this->assertEquals( $size, $data[0]['filesize'] );

			$this->cleanChunk();
		}

		$params['done'] = true;

		$this->makeChunk( "456" );
		$data = $this->doApiRequest( $params, $data );

		$this->cleanChunk();

		if ( isset( $data[0]['upload'] ) ) {
			$this->markTestSkipped( "Please run 'php maintenance/deleteArchivedFiles.php --delete --force' and 'php maintenance/deleteArchivedRevisions.php --delete'" );
		}

		$this->assertArrayHasKey( 'result', $data[0] );
		$this->assertEquals( 1, $data[0]['result'] );

		$this->assertArrayHasKey( 'done', $data[0] );
		$this->assertEquals( 1, $data[0]['done'] );

		$this->assertArrayHasKey( 'resultUrl', $data[0] );
		$this->assertRegExp( '/File:Twar.png/', $data[0]['resultUrl'] );
	}
}
