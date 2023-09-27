<?php

use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

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
		[ $dbMock, $backendMock, $wrapperMock ] = $mocks;

		$dbMock->expects( $dbReadsExpected )
			->method( 'selectField' )
			->willReturn( $dbReturnValue );
		$dbMock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbMock ) );

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
		[ $dbMock, $backendMock, $wrapperMock ] = $this->getMocks();

		$sha1Path = 'mwstore://' . $this->backendName . '/' . $this->repoName
			. '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9';
		$filenamePath = 'mwstore://' . $this->backendName . '/' . $this->repoName
			. '-public/f/o/foobar.jpg';

		$dbMock->expects( $this->once() )
			->method( 'selectField' )
			->willReturn( '96246614d75ba1703bdfd5d7660bb57407aaf5d9' );
		$dbMock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbMock ) );

		$backendMock->expects( $this->once() )
			->method( 'getFileContentsMulti' )
			->willReturn( [ $sha1Path => 'foo' ] );

		$result = $wrapperMock->getFileContentsMulti( [ 'srcs' => [ $filenamePath ] ] );

		$this->assertEquals(
			[ $filenamePath => 'foo' ],
			$result,
			'File contents paths translated properly'
		);
	}

	protected function getMocks() {
		$dbMock = $this->getMockBuilder( IDatabase::class )
			->disableOriginalClone()
			->disableOriginalConstructor()
			->getMock();
		$dbMock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbMock ) );

		$backendMock = $this->getMockBuilder( FSFileBackend::class )
			->setConstructorArgs( [ [
					'name' => $this->backendName,
					'wikiId' => WikiMap::getCurrentWikiId()
				] ] )
			->getMock();

		$wrapperMock = $this->getMockBuilder( FileBackendDBRepoWrapper::class )
			->onlyMethods( [ 'getDB' ] )
			->setConstructorArgs( [ [
					'backend' => $backendMock,
					'repoName' => $this->repoName,
					'dbHandleFactory' => null
				] ] )
			->getMock();

		$wrapperMock->method( 'getDB' )->willReturn( $dbMock );

		return [ $dbMock, $backendMock, $wrapperMock ];
	}
}
