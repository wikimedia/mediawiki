<?php

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\FileBackend\FileBackendMultiWrite;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 * @covers \Wikimedia\FileBackend\FileBackendMultiWrite
 */
class FileBackendMultiWriteIntegrationTest extends FileBackendIntegrationTestBase {
	protected function getBackend() {
		$tmpDir = $this->getNewTempDirectory();
		$lockManagerGroup = $this->getServiceContainer()
			->getLockManagerGroupFactory()->getLockManagerGroup();
		return new FileBackendMultiWrite( [
			'name' => 'localtesting',
			'lockManager' => $lockManagerGroup->get( 'fsLockManager' ),
			'parallelize' => 'implicit',
			'wikiId' => 'testdb',
			'logger' => LoggerFactory::getInstance( 'FileOperation' ),
			'backends' => [
				[
					'name' => 'localmultitesting1',
					'class' => FSFileBackend::class,
					'containerPaths' => [
						'unittest-cont1' => "{$tmpDir}/localtestingmulti1-cont1",
						'unittest-cont2' => "{$tmpDir}/localtestingmulti1-cont2" ],
					'isMultiMaster' => false
				],
				[
					'name' => 'localmultitesting2',
					'class' => FSFileBackend::class,
					'containerPaths' => [
						'unittest-cont1' => "{$tmpDir}/localtestingmulti2-cont1",
						'unittest-cont2' => "{$tmpDir}/localtestingmulti2-cont2" ],
					'isMultiMaster' => true
				]
			]
		] );
	}
}
