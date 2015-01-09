<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException Exception
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeNull() {
		new FileRepo();
	}

	/**
	 * @expectedException Exception
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		new FileRepo( array() );
	}

	/**
	 * @expectedException Exception
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedNameKey() {
		new FileRepo( array(
			'backend' => 'foobar'
		) );
	}

	/**
	 * @expectedException Exception
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedBackendKey() {
		new FileRepo( array(
			'name' => 'foobar'
		) );
	}

	/**
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionWithRequiredOptions() {
		$f = new FileRepo( array(
			'name' => 'FileRepoTestRepository',
			'backend' => new FSFileBackend( array(
				'name' => 'local-testing',
				'wikiId' => 'test_wiki',
				'containerPaths' => array()
			) )
		) );
		$this->assertInstanceOf( 'FileRepo', $f );
	}
}
