<?php

use MediaWiki\MainConfigNames;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\FileBackend\FSFile\TempFSFileTestTrait;

/**
 * Just to test one deprecated method and one line of ServiceWiring code.
 */
class TempFSFileIntegrationTest extends MediaWikiIntegrationTestCase {
	use TempFSFileTestTrait;

	/**
	 * @coversNothing
	 */
	public function testServiceWiring() {
		$this->overrideConfigValue( MainConfigNames::TmpDirectory, '/hopefully invalid' );
		$factory = $this->getServiceContainer()->getTempFSFileFactory();
		$this->assertSame( '/hopefully invalid',
			( TestingAccessWrapper::newFromObject( $factory ) )->tmpDirectory );
	}

	// For TempFSFileTestTrait
	private function newFile(): TempFSFile {
		return TempFSFile::factory( 'tmp' );
	}
}
