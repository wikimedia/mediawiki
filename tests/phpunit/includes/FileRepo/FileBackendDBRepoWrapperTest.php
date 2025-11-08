<?php

use MediaWiki\FileRepo\FileBackendDBRepoWrapper;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

class FileBackendDBRepoWrapperTest extends MediaWikiIntegrationTestCase {
	private const BACKEND_NAME = 'foo-backend';
	private const REPO_NAME = 'pureTestRepo';

	/**
	 * @dataProvider getBackendPathsProvider
	 * @covers \MediaWiki\FileRepo\FileBackendDBRepoWrapper::getBackendPaths
	 */
	public function testGetBackendPaths(
		$latest,
		$dbReadsExpectedArray,
		$dbReturnValue,
		$originalPath,
		$expectedBackendPath
	) {
		[ $dbMock, $backendMock, $wrapperMock ] = $this->getMocks();

		foreach ( $dbReadsExpectedArray as $message => $dbReadsExpected ) {
			$dbMock->expects( $dbReadsExpected )
				->method( 'selectField' )
				->willReturn( $dbReturnValue );
			$dbMock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $dbMock ) );

			$newPaths = $wrapperMock->getBackendPaths( [ $originalPath ], $latest );

			$this->assertEquals(
				$expectedBackendPath,
				$newPaths[0],
				$message
			);
		}
	}

	public static function getBackendPathsProvider() {
		$prefix = 'mwstore://' . self::BACKEND_NAME . '/' . self::REPO_NAME;

		return [
			[
				false,
				[ 'Public path translated correctly' => self::once(), 'LRU cache leveraged' => self::never() ],
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
			],
			[
				true,
				[ 'Latest obtained' => self::once() ],
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-public/f/o/foobar.jpg',
				$prefix . '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9',
			],
			[
				true,
				[ 'Deleted path translated correctly' => self::never() ],
				'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
				$prefix . '-deleted/f/o/foobar.jpg',
				$prefix . '-original/f/o/o/foobar',
			],
			[
				true,
				[ 'Path left untouched if no sha1 can be found' => self::once() ],
				null,
				$prefix . '-public/b/a/baz.jpg',
				$prefix . '-public/b/a/baz.jpg',
			],
		];
	}

	/**
	 * @covers \MediaWiki\FileRepo\FileBackendDBRepoWrapper::getFileContentsMulti
	 */
	public function testGetFileContentsMulti() {
		[ $dbMock, $backendMock, $wrapperMock ] = $this->getMocks();

		$sha1Path = 'mwstore://' . self::BACKEND_NAME . '/' . self::REPO_NAME
			. '-original/9/6/2/96246614d75ba1703bdfd5d7660bb57407aaf5d9';
		$filenamePath = 'mwstore://' . self::BACKEND_NAME . '/' . self::REPO_NAME
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
					'name' => self::BACKEND_NAME,
					'wikiId' => WikiMap::getCurrentWikiId()
				] ] )
			->getMock();

		$wrapperMock = $this->getMockBuilder( FileBackendDBRepoWrapper::class )
			->onlyMethods( [ 'getDB' ] )
			->setConstructorArgs( [ [
					'backend' => $backendMock,
					'repoName' => self::REPO_NAME,
					'dbHandleFactory' => null
				] ] )
			->getMock();

		$wrapperMock->method( 'getDB' )->willReturn( $dbMock );

		return [ $dbMock, $backendMock, $wrapperMock ];
	}
}
