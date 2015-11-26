<?php

class FileBackendDBRepoWrapperTest extends MediaWikiTestCase {
	protected $backendName = 'foo-backend';
	protected $repoName = 'pureTestRepo';

	/**
	 * @dataProvider getBackendPathsProvider
	 * @covers FileBackendDBRepoWrapper::getBackendPaths
	 */
	public function testGetBackendPaths(
		$mocks,
		$latest,
		$dbReadsExpected,
		$dbReturnValue,
		$originalPath,
		$expectedBackendPath,
		$message ) {
		list( $dbMock, $backendMock, $wrapperMock ) = $mocks;

		$dbMock->expects( $dbReadsExpected )
			->method( 'selectField' )
			->will( $this->returnValue( $dbReturnValue ) );

		$newPaths = $wrapperMock->getBackendPaths( array( $originalPath ), $latest );

		$this->assertEquals(
			$expectedBackendPath,
			$newPaths[0],
			$message );
	}

	public function getBackendPathsProvider() {
		$prefix = 'mwstore://' . $this->backendName . '/' . $this->repoName;
		$mocksForCaching = $this->getMocks();

		return array(
			array(
				$mocksForCaching,
				false,
				$this->once(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'Public path translated correctly',
			),
			array(
				$mocksForCaching,
				false,
				$this->never(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'LRU cache leveraged',
			),
			array(
				$this->getMocks(),
				true,
				$this->once(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'Latest obtained',
			),
			array(
				$this->getMocks(),
				true,
				$this->never(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-deleted/f/o/foobar.jpg',
				$prefix . '-original/f/o/o/foobar',
				'Deleted path translated correctly',
			),
			array(
				$this->getMocks(),
				true,
				$this->once(),
				null,
				$prefix . '-public/b/a/baz.jpg',
				$prefix . '-public/b/a/baz.jpg',
				'Path left untouched if no sha1 can be found',
			),
		);
	}

	/**
	 * @covers FileBackendDBRepoWrapper::getFileContentsMulti
	 */
	public function testGetFileContentsMulti() {
		list( $dbMock, $backendMock, $wrapperMock ) = $this->getMocks();

		$sha1Path = 'mwstore://' . $this->backendName . '/' . $this->repoName
			. '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9';
		$filenamePath = 'mwstore://' . $this->backendName . '/' . $this->repoName
			. '-public/f/o/foobar.jpg';

		$dbMock->expects( $this->once() )
			->method( 'selectField' )
			->will( $this->returnValue( '96246614d75ba1703bdfd5d7660bb57407aaf5d9' ) );

		$backendMock->expects( $this->once() )
			->method( 'getFileContentsMulti')
			->will( $this->returnValue( array( $sha1Path => 'foo' ) ) );

		$result = $wrapperMock->getFileContentsMulti( array( 'srcs' => array( $filenamePath ) ) );

		$this->assertEquals(
			array( $filenamePath => 'foo' ),
			$result,
			'File contents paths translated properly'
		);
	}

	protected function getMocks() {
		$dbMock = $this->getMockBuilder( 'DatabaseMysql' )
			->disableOriginalConstructor()
			->getMock();

		$backendMock = $this->getMock( 'FSFileBackend',
			array(),
			array( array(
				'name' => $this->backendName,
				'wikiId' => wfWikiId()
			) ) );

		$wrapperMock = $this->getMock( 'FileBackendDBRepoWrapper',
			array( 'getDB' ),
			array( array(
				'backend' => $backendMock,
				'repoName' => $this->repoName,
				'dbHandleFactory' => null
			) ) );

		$wrapperMock->expects( $this->any() )->method( 'getDB' )->will( $this->returnValue( $dbMock ) );

		return array( $dbMock, $backendMock, $wrapperMock );
	}
}
