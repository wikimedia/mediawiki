<?php

namespace Wikimedia\Tests\FileBackend\FSFile;

use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\FileBackend\FSFile\TempFSFileFactory
 */
class TempFSFileTest extends MediaWikiUnitTestCase {
	use TempFSFileTestTrait;

	private function newFile() {
		return ( new TempFSFileFactory() )->newTempFSFile( 'tmp' );
	}
}
