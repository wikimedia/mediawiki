<?php

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\WikiMap\WikiMap;

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 *
 * @covers \Wikimedia\FileBackend\FileBackend
 *
 * @covers \CopyFileOp
 * @covers \CreateFileOp
 * @covers \DeleteFileOp
 * @covers \DescribeFileOp
 * @covers \FSFile
 * @covers \FSFileBackend
 * @covers \FSFileBackendDirList
 * @covers \FSFileBackendFileList
 * @covers \FSFileBackendList
 * @covers \FSFileOpHandle
 * @covers \FileBackendDBRepoWrapper
 * @covers \FileBackendError
 * @covers \MediaWiki\FileBackend\FileBackendGroup
 * @covers \FileBackendMultiWrite
 * @covers \FileBackendStore
 * @covers \FileBackendStoreOpHandle
 * @covers \FileBackendStoreShardDirIterator
 * @covers \FileBackendStoreShardFileIterator
 * @covers \FileBackendStoreShardListIterator
 * @covers \FileOp
 * @covers \FileOpBatch
 * @covers \HTTPFileStreamer
 * @covers \LockManagerGroup
 * @covers \MoveFileOp
 * @covers \NullFileOp
 * @covers \StoreFileOp
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
