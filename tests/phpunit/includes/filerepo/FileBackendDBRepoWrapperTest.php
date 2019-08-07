<?php

class FileBackendDBRepoWrapperTest extends MediaWikiIntegrationTestCase {
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

		$newPaths = $wrapperMock->getBackendPaths( [ $originalPath ], $latest );

		$this->assertEquals(
			$expectedBackendPath,
			$newPaths[0],
			$message );
	}

	public function getBackendPathsProvider() {
		$prefix = 'mwstore://' . $this->backendName . '/' . $this->repoName;
		$mocksForCaching = $this->getMocks();

		return [
			[
				$mocksForCaching,
				false,
				$this->once(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'Public path translated correctly',
			],
			[
				$mocksForCaching,
				false,
				$this->never(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'LRU cache leveraged',
			],
			[
				$this->getMocks(),
				true,
				$this->once(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				'Latest obtained',
			],
			[
				$this->getMocks(),
				true,
				$this->never(),
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-deleted/f/o/foobar.jpg',
				$prefix . '-original/f/o/o/foobar',
				'Deleted path translated correctly',
			],
			[
				$this->getMocks(),
				true,
				$this->once(),
				null,
				$prefix . '-public/b/a/baz.jpg',
				$prefix . '-public/b/a/baz.jpg',
				'Path left untouched if no sha1 can be found',
			],
		];
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
			->method( 'getFileContentsMulti' )
			->will( $this->returnValue( [ $sha1Path => 'foo' ] ) );

		$result = $wrapperMock->getFileContentsMulti( [ 'srcs' => [ $filenamePath ] ] );

		$this->assertEquals(
			[ $filenamePath => 'foo' ],
			$result,
			'File contents paths translated properly'
		);
	}

	protected function getMocks() {
		$dbMock = $this->getMockBuilder( Wikimedia\Rdbms\IDatabase::class )
			->disableOriginalClone()
			->disableOriginalConstructor()
			->getMock();

		$backendMock = $this->getMockBuilder( FSFileBackend::class )
			->setConstructorArgs( [ [
					'name' => $this->backendName,
					'wikiId' => wfWikiID()
				] ] )
			->getMock();

		$wrapperMock = $this->getMockBuilder( FileBackendDBRepoWrapper::class )
			->setMethods( [ 'getDB' ] )
			->setConstructorArgs( [ [
					'backend' => $backendMock,
					'repoName' => $this->repoName,
					'dbHandleFactory' => null
				] ] )
			->getMock();

		$wrapperMock->expects( $this->any() )->method( 'getDB' )->will( $this->returnValue( $dbMock ) );

		return [ $dbMock, $backendMock, $wrapperMock ];
	}
}
