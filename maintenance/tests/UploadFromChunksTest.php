<?php

require_once("ApiSetup.php");

class UploadFromChunksTest extends ApiSetup {

	function setUp() {
		global $wgEnableUploads;

		$wgEnableUploads=true;
		ini_set('file_loads', true);
		parent::setup();

	}

	function makeChunk() {
		$file = tempnam( wfTempDir(), "" );
		$fh = fopen($file, "w");
		if($fh == false) {
			$this->markTestIncomplete("Couldn't open $file!\n");
			return;
		}
		fwrite($fh, "123");
		fclose($fh);

		$_FILES['chunk']['tmp_name'] = $file;
		$_FILES['chunk']['size'] = 3;
		$_FILES['chunk']['error'] = null;
		$_FILES['chunk']['name'] = "test.txt";
	}

	function cleanChunk() {
		if(file_exists($_FILES['chunk']['tmp_name']))
		   unlink($_FILES['chunk']['tmp_name']);
	}

	function doApiRequest($params) {
		$session = isset( $_SESSION ) ? $_SESSION : array();
		$req = new FauxRequest($params, true, $session);
		$module = new ApiMain($req, true);
		$module->execute();

		return $module->getResultData();
	}

	function testGetTitle() {
		$filename = tempnam( wfTempDir(), "" );
		$c = new UploadFromChunks();
		$c->initialize(false, "temp.txt", null, $filename, 0, null);
		$this->assertEquals(null, $c->getTitle());

		$c = new UploadFromChunks();
		$c->initialize(false, "temp.png", null, $filename, 0, null);
		$this->assertEquals(Title::makeTitleSafe(NS_FILE, "Temp.png"), $c->getTitle());
	}

	function testLogin() {
		$data = $this->doApiRequest(array('action' => 'login',
										  "lgname" => self::$userName,
										  "lgpassword" => self::$passWord ) );
		$this->assertArrayHasKey("login", $data);
		$this->assertArrayHasKey("result", $data['login']);
		$this->assertEquals("Success", $data['login']['result']);

		return $data;
	}


	/**
	 * @depends testLogin
	 */
	function testGetEditToken($data) {
		global $wgUser;
		$wgUser = User::newFromName(self::$userName);
		$wgUser->load();

		$data = $this->doApiRequest(array('action' => 'query',
										  'prop' => 'info',
										  'intoken' => 'edit'));
	}

	function testSetupChunkSession() {
	}


    /**
     * @expectedException UsageException
     */
	function testPerformUploadInitError() {
		global $wgUser;
		$wgUser = User::newFromId(1);

		$req = new FauxRequest(
			array('action' => 'upload',
				  'enablechunks' => '1',
				  'filename' => 'test.png',
			));
		$module = new ApiMain($req, true);
		$module->execute();
	}

	/**
	 * @depends testLogin
	 */
	function testPerformUploadInitSuccess($login) {
		global $wgUser;

		$wgUser = User::newFromName(self::$userName);
		$token = $wgUser->editToken();

		$data = $this->doApiRequest(
			array('action' => 'upload',
				  'enablechunks' => '1',
				  'filename' => 'test.png',
				  'token' => $token,
			));

		$this->assertArrayHasKey("upload", $data);
		$this->assertArrayHasKey("uploadUrl", $data['upload']);

		return array('data' => $data, 'session' => $_SESSION, 'token' => $token);
	}

	/**
	 * @depends testPerformUploadInitSuccess
	 */
	function testAppendChunk($combo) {
		global $wgUser;
		$data = $combo['data'];
		$_SESSION = $combo['session'];
		$wgUser = User::newFromName(self::$userName);
		$token = $wgUser->editToken();

		$url = $data['upload']['uploadUrl'];
		$params = wfCgiToArray(substr($url, strpos($url, "?")));

		for($i=0;$i<10;$i++) {
			$this->makeChunk();
			$data = $this->doApiRequest($params);
			$this->cleanChunk();
		}

		return array('data' => $data, 'session' => $_SESSION, 'token' => $token, 'params' => $params);
	}

	/**
	 * @depends testAppendChunk
	 */
	function testUploadChunkDone($combo) {
		global $wgUser;
		$data = $combo['data'];
		$params = $combo['params'];
		$_SESSION = $combo['session'];
		$wgUser = User::newFromName(self::$userName);
		$token = $wgUser->editToken();

		$params['done'] = 1;

		$this->makeChunk();
		$data = $this->doApiRequest($params);
		$this->cleanChunk();
	}
}
