<?php

use MediaWiki\FileBackend\FSFile\TempFSFileFactory;

/**
 * @coversDefaultClass \MediaWiki\FileBackend\FSFile\TempFSFileFactory
 * @covers ::__construct
 * @covers ::newTempFSFile
 */
class TempFSFileTest extends MediaWikiUnitTestCase {
	use TempFSFileTestTrait;

	private function newFile() {
		return ( new TempFSFileFactory() )->newTempFSFile( 'tmp' );
	}
}
