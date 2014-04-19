<?php

class FakeDB {
	public function selectField( $table, $field, $where, $method = 'Unknown' ) {
		if ( $field === 'sha1' ) {
			return 'deadbeefedcba9876543210';
		}
	}
}

class FileBackendFakeDBRepoWrapper extends FileBackendDBRepoWrapper {
	protected function getDB( $index ) {
		return new FakeDB();
	}

	public function getRepoName() {
		return $this->repoName;
	}
}

class FileBackendDBRepoWrapperTest extends MediaWikiTestCase {

	protected function constructWrapper() {
		$backend = new FSFileBackend( array(
			'name' => 'local-backend',
			'wikiId' => wfWikiId(),
			'containerPaths' => array(
				'cont1' => "/testdir/local-backend/tempimages/cont1",
				'cont2' => "/testdir/local-backend/tempimages/cont2"
			)
		) );

		return new FileBackendFakeDBRepoWrapper( array(
			'backend' => $backend,
			'repoName' => 'pureTestRepo',
			'dbHandleFactory' => function ( $index ) {
				return wfGetDB( $index );
			}
		) );
	}

	/**
	 * @covers FileBackendDBRepoWrapper::__construct
	 */
	public function testWrapperConstruction() {
		$wrapper = $this->constructWrapper();
		$this->assertInstanceOf( 'FileBackendDBRepoWrapper', $wrapper, 'Construction of repo wrapper succeeded.' );
	}

	/**
	 * @covers FileBackendDBRepoWrapper::getBackendPaths
	 */
	public function testGetBackendPaths() {
		$wrapper = $this->constructWrapper();

		$newPaths = $wrapper->getBackendPaths( array(
			$wrapper->getRepoName() . '-public/foobar.jpg',
		) );

		$this->assertEquals( current( $newPaths ), 'd/e/a/deadbeefedcba9876543210.jpg' );
	}
}
