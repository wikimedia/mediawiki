<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * Just to test one deprecated method and one line of ServiceWiring code.
 */
class TempFSFileIntegrationTest extends MediaWikiIntegrationTestCase {
	/**
	 * @coversNothing
	 */
	public function testServiceWiring() {
		$this->setMwGlobals( 'wgTmpDirectory', '/hopefully invalid' );
		$factory = MediaWikiServices::getInstance()->getTempFSFileFactory();
		$this->assertSame( '/hopefully invalid',
			( TestingAccessWrapper::newFromObject( $factory ) )->tmpDirectory );
	}

	use TempFSFileTestTrait;

	private function newFile() {
		return TempFSFile::factory( 'tmp' );
	}
}
