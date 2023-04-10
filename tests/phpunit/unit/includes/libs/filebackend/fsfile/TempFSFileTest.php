<?php

use MediaWiki\FileBackend\FSFile\TempFSFileFactory;

/**
 * @covers \MediaWiki\FileBackend\FSFile\TempFSFileFactory
 */
class TempFSFileTest extends MediaWikiUnitTestCase {
	use TempFSFileTestTrait;

	private function newFile() {
		return ( new TempFSFileFactory() )->newTempFSFile( 'tmp' );
	}
}
