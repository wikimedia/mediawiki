<?php

class FileBackendDBRepoWrapperTest extends MediaWikiTestCase {
	protected $backendName = 'foo-backend';
	protected $repoName = 'pureTestRepo';

	/**
	 * @covers FileBackendDBRepoWrapper::getBackendPaths
	 */
	public function testGetBackendPaths() {
		$sha1 = array(
			'96246614d75ba1703bdfd5d7660bb57407aaf5d9',
			'a16358be6e2306b153b1f071477e68837266075e',
		);

		list( $dbMock, $backendMock, $wrapperMock ) = $this->getMocks();

		$dbMock->expects( $this->at( 0 ) )
			->method( 'selectField' )
			->will( $this->returnValue( $sha1[0] ) );

		$dbMock->expects( $this->at( 1 ) )
			->method( 'selectField' )
			->will( $this->returnValue( $sha1[1] ) );

		$newPaths = $wrapperMock->getBackendPaths( array(
			'mwstore://' . $this->backendName . '/' . $this->repoName . '-public/f/o/foobar.jpg',
			'mwstore://' . $this->backendName . '/' . $this->repoName . '-public/b/a/baz.jpg',
		) );

		$this->assertEquals(
			'mwstore://' . $this->backendName . '/'. $this->repoName .'-original/9/6/2/' . $sha1[0] . '.jpg',
			$newPaths[0],
			'First backend path is translated correctly' );

		$this->assertEquals(
			'mwstore://' . $this->backendName . '/'. $this->repoName .'-original/a/1/6/' . $sha1[1] . '.jpg',
			$newPaths[1],
			'Second backend path is translated correctly' );
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
