<?php

require_once("ApiSetup.php");

class UploadFromChunksTest extends ApiSetup {

	function setUp() {
		global $wgEnableUploads;

		$wgEnableUploads=true;
		ini_set('file_loads', true);
	}

	function testGetEditToken() {
	}

	function testInitFromSessionKey() {

	}

	function testInitialize() {
	}

	function testSetupChunkSession() {
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
		unlink($_FILES['chunk']['tmp_name']);
	}

    /**
     * @expectedException UsageException
     */
	function testPerformUploadInitError() {
		global $wgUser;

		$wgUser = User::newFromId(1);
		$token = $wgUser->editToken();
		$this->makeChunk();

		$req = new FauxRequest(
			array('action' => 'upload',
				  'enablechunks' => '1',
				  'filename' => 'test.txt',
				  'token' => $token,
			));
		$module = new ApiMain($req, true);
		$module->execute();
	}

	function testPerformUploadInitSuccess() {
		global $wgUser;

		$wgUser = User::newFromId(1);
		$token = $wgUser->editToken();
		$this->makeChunk();

		$req = new FauxRequest(
			array('action' => 'upload',
				  'enablechunks' => '1',
				  'filename' => 'test.txt',
				  'token' => $token,
			));
		$module = new ApiMain($req, true);
		$module->execute();
	}

	function testAppendToUploadFile() {
	}

	function testAppendChunk() {
	}

	function testPeformUploadChunk() {
	}

	function testPeformUploadDone() {
	}



}
