<?php

class TempFSFileIntegrationTest extends MediaWikiIntegrationTestCase {
	use TempFSFileTestTrait;

	private function newFile() {
		return TempFSFile::factory( 'tmp' );
	}
}
