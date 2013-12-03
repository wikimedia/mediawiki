<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeNull() {
		new FileRepo();
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		new FileRepo( array() );
	}

	/**
	 * @expectedException MWException
	 * @covers FileRepo::__construct
	 */
	public function testFileRepoConstructionOptionNeedNameKey() {
		new FileRepo( array(
			'backend' => 'foobar'
		) );
	}

	/**
	 * @expectedException MWException
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
				'lockManager' => 'nullLockManager',
				'containerPaths' => array()
			) )
		) );
		$this->assertInstanceOf( 'FileRepo', $f );
	}
}
