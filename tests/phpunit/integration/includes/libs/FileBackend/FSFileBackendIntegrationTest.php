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
 * @covers \Wikimedia\FileBackend\FSFile\FSFile
 * @covers \Wikimedia\FileBackend\FSFileBackend
 * @covers \Wikimedia\FileBackend\FileIteration\FSFileBackendDirList
 * @covers \Wikimedia\FileBackend\FileIteration\FSFileBackendFileList
 * @covers \Wikimedia\FileBackend\FileIteration\FSFileBackendList
 * @covers \Wikimedia\FileBackend\FileOpHandle\FSFileOpHandle
 * @covers \MediaWiki\FileRepo\FileBackendDBRepoWrapper
 * @covers \Wikimedia\FileBackend\FileBackendError
 * @covers \MediaWiki\FileBackend\FileBackendGroup
 * @covers \Wikimedia\FileBackend\FileBackendMultiWrite
 * @covers \Wikimedia\FileBackend\FileBackendStore
 * @covers \Wikimedia\FileBackend\FileOpHandle\FileBackendStoreOpHandle
 * @covers \Wikimedia\FileBackend\FileIteration\FileBackendStoreShardDirIterator
 * @covers \Wikimedia\FileBackend\FileIteration\FileBackendStoreShardFileIterator
 * @covers \Wikimedia\FileBackend\FileIteration\FileBackendStoreShardListIterator
 * @covers \Wikimedia\FileBackend\FileOps\FileOp
 * @covers \Wikimedia\FileBackend\FileOpBatch
 * @covers \Wikimedia\FileBackend\HTTPFileStreamer
 * @covers \LockManagerGroup
 * @covers \Wikimedia\FileBackend\FileOps\MoveFileOp
 * @covers \Wikimedia\FileBackend\FileOps\NullFileOp
 * @covers \Wikimedia\FileBackend\FileOps\StoreFileOp
 * @covers \Wikimedia\FileBackend\FSFile\TempFSFile
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
