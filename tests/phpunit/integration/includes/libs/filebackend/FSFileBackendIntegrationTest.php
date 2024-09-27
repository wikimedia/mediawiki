<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 *
 * @covers \Wikimedia\FileBackend\FileBackend
 *
 * @covers \Wikimedia\FileBackend\FileOps\CopyFileOp
 * @covers \Wikimedia\FileBackend\FileOps\CreateFileOp
 * @covers \Wikimedia\FileBackend\FileOps\DeleteFileOp
 * @covers \Wikimedia\FileBackend\FileOps\DescribeFileOp
 * @covers \FSFile
 * @covers \Wikimedia\FileBackend\FSFileBackend
 * @covers \FSFileBackendDirList
 * @covers \FSFileBackendFileList
 * @covers \FSFileBackendList
 * @covers \FSFileOpHandle
 * @covers \FileBackendDBRepoWrapper
 * @covers \Wikimedia\FileBackend\FileBackendError
 * @covers \MediaWiki\FileBackend\FileBackendGroup
 * @covers \FileBackendMultiWrite
 * @covers \FileBackendStore
 * @covers \FileBackendStoreOpHandle
 * @covers \FileBackendStoreShardDirIterator
 * @covers \FileBackendStoreShardFileIterator
 * @covers \FileBackendStoreShardListIterator
 * @covers \Wikimedia\FileBackend\FileOps\FileOp
 * @covers \FileOpBatch
 * @covers \HTTPFileStreamer
 * @covers \LockManagerGroup
 * @covers \Wikimedia\FileBackend\FileOps\MoveFileOp
 * @covers \Wikimedia\FileBackend\FileOps\NullFileOp
 * @covers \Wikimedia\FileBackend\FileOps\StoreFileOp
 * @covers \TempFSFile
 *
 * @covers \FSLockManager
 * @covers \LockManager
 * @covers \NullLockManager
 */
class FSFileBackendIntegrationTest extends FileBackendIntegrationTestBase {
	protected function getBackend() {
		$tmpDir = $this->getNewTempDirectory();
		$lockManagerGroup = $this->getServiceContainer()
			->getLockManagerGroupFactory()->getLockManagerGroup();
		return new FSFileBackend( [
			'name' => 'localtesting',
			'lockManager' => $lockManagerGroup->get( 'fsLockManager' ),
			'wikiId' => WikiMap::getCurrentWikiId(),
			'logger' => LoggerFactory::getInstance( 'FileOperation' ),
			'containerPaths' => [
				'unittest-cont1' => "{$tmpDir}/localtesting-cont1",
				'unittest-cont2' => "{$tmpDir}/localtesting-cont2" ]
		] );
	}
}
