<?php

namespace Wikimedia\Tests\FileBackend\FSFile;

use MediaWikiUnitTestCase;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;

/**
 * @covers \Wikimedia\FileBackend\FSFile\TempFSFileFactory
 */
class TempFSFileTest extends MediaWikiUnitTestCase {
	use TempFSFileTestTrait;

	private function newFile(): TempFSFile {
		return ( new TempFSFileFactory() )->newTempFSFile( 'tmp' );
	}
}
